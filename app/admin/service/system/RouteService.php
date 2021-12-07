<?php
/**
 * datetime: 2021/11/9 9:20
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\enum\RouteEnumType;
use app\admin\model\system\Power;
use app\admin\model\system\Route;
use app\common\SdException;
use app\common\service\BackstageListsService;
use sdModule\common\Sc;
use think\facade\App;
use think\facade\Db;

class RouteService extends AdminBaseService
{
    /**
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = Route::join('route', 'i.pid = route.id', 'left')
            ->order('weigh', 'asc')
            ->field('i.id,i.title,i.route,i.pid,route.title parent,i.type,i.weigh,i.icon,i.create_time');

        return $service->setModel($model)->getListsData();
    }

    /**
     * 删除路由前的处理
     * @param $ids
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected function beforeDelete(&$ids)
    {
        $all = Route::field('id,pid')->select()->toArray();

        // 查找该路由的所有子权限
        $delArr = Sc::tree($all)->setWhere(['id' => current($ids)])->getLineData();
        $ids    = array_column($delArr, 'id');

        // 删除对应路由的权限
        Power::where(['route_id' => $ids])->update(['delete_time' => time()]);
    }

    /**
     * 存储数据之前处理反斜杠
     * @param array $data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected function beforeWrite(array &$data)
    {
        if (!empty($data['route'])) {
            $data['route'] = strtr($data['route'], ['\\' => '/']);
        }
    }

    /**
     * 自动检测新增的地址
     * @param string $path 相对于controller的文件夹
     * @return array
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/12
     */
    public function automaticDetection(string $path = ''): array
    {
        $controllerRoot = App::getAppPath() . '/controller/';
        $currentPath    = $controllerRoot . $path . ($path ? '/' : '');
        $f = opendir($currentPath);
        try {
            $allAccessible = [];

            while ($r = readdir($f)) {
                if ($r === '.' || $r === '..') {
                    continue;
                }
                // path 为真的时候，需要在之间加上 / ,path 为空， 则不需要
                $filename = $path . ($path ? '/' : '') . "$r";
                if (is_dir($currentPath . "$r")) {
                    $allAccessible = array_merge($allAccessible, $this->automaticDetection($filename));
                }else{
                    $allAccessible[] = $this->getAllAccessible($filename);
                }
            }
        } catch (\Throwable $throwable) {
            throw new SdException($throwable->getMessage());
        } finally {
            closedir($f);
        }

        // 已定义路由的
        $defined = array_map('strtolower', array_keys(request()->rule()->getRouter()->getName()));

        // 已经存数据库的
        $save = array_map('strtolower', Route::column('route'));

        // 过滤 已定义路由的 和 已经存数据库的
        $allAccessible = array_map(function ($v) use ($save, $defined) {
            $v['accessible'] = array_filter($v['accessible'], function ($v1) use ($save, $defined) {
                $r = strtolower($v1['route']);
                return !in_array($r, $save) && !in_array($r, $defined);
            });
            return $v;
        }, $allAccessible);

        return array_values(array_filter($allAccessible, function ($v){
            return count($v['accessible']);
        }));
    }


    /**
     * 获取所有的可访问地址
     * @param string $filename 文件名字
     * @throws \ReflectionException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/12
     */
    private function getAllAccessible(string $filename): array
    {
        $controllerNamespace = "app\\admin\\controller\\";
        // 去掉文件后缀，凭借上控制器的基础命名空间，组成控制器的完全名字
        $classname = $controllerNamespace . strtr($filename, ['/' => "\\", '.php' => '']);
        $classReflection = new \ReflectionClass($classname);
        // 匹配出控制器的名字
        preg_match('/\*(.+)控制器/', $classReflection->getDocComment(), $controllerTitle);
        $controllerName = $controllerTitle ? $controllerTitle[1] : '——';
        $accessible = [];
        // 对应控制器
        $prefix = strtr($filename, ['/' => '.', '.php' => '']);
        foreach ($classReflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            if ($method->getName() === '__construct') {
                continue;
            }
            // 匹配出对应的函数名字
            preg_match('/@title\((.+)\)/', $method->getDocComment(), $title);
            $title = $title ? trim($title[1], "'\"")  : '——';
            $route  = $prefix . '/' . $method->getName();

            $accessible[] = compact('title', 'route');
        }

        return [
            'controller_name' => $controllerName . "($prefix)",
            'accessible'      => $accessible
        ];
    }

    /**
     * 保存自动检测出的权限节点
     * @param array $routes 新增的路由数据
     * @param array $parents 对应的父级数据
     * @param array $controllers
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/13
     */
    public function saveAutomaticDetectionRoute(array $routes, array $parents, array $controllers)
    {
        Db::startTrans();
        try {
            // 过滤为空的父节点
            $parents = array_filter($parents);
            // 查出对应父节点下面最大的排序权重值
            $maxWeigh = Route::whereIn('pid', array_merge($parents, [0]))->group('pid')->field('max(weigh) weigh,pid')->select()->toArray();
            $maxWeigh = array_column($maxWeigh, 'weigh', 'pid');
            // 定义要保存的节点数据变量
            $saveRoute = [];
            // 循环处理每一个路由节点
            foreach ($controllers as $index => $controller){
                // 解析出名字和路由地址
                preg_match('/(.+)\((.*)\)/', $controller, $controllerMatch);
                $controller = $controllerMatch[1] ?? $controller;

                foreach (($routes[$index] ?? []) as $route){
                    // 解析出名字和路由地址
                    preg_match('/(.+)\((.*)\)/', $route, $resolve);
                    if (!$resolve) continue;
                    $newRoute = [];
                    list(, $newRoute['title'], $newRoute['route']) = $resolve;
                    $newRoute['create_time'] = $newRoute['update_time'] = date('Y-m-d H:i:s');
                    // 如果没有父节点，则创建一个父节点
                    if (empty($parents[$index])) {
                        empty($maxWeigh[0]) and $maxWeigh[0] = 0;
                        $newParents = Route::create([
                            'title' => $controller,
                            'route' => '',
                            'pid'   => 0,
                            'type'  => RouteEnumType::NODE,
                            'weigh' => ++$maxWeigh[0],
                            'icon'  => '',
                        ]);

                        if (!$newParents->id) {
                            throw new SdException("根节点“{$controller}”创建失败");
                        }
                        // 在父节点对象里面增加此，避免后续出现重复创建
                        $parents[$index] = $newParents->id;
                        // 在排序权重值里面增加此数据
                        $maxWeigh[$newParents->id] = 0;
                    }
                    $newRoute['pid']   = $parents[$index];
                    empty($maxWeigh[$newRoute['pid']]) and $maxWeigh[$newRoute['pid']] = 0;
                    $newRoute['weigh'] = ++$maxWeigh[$newRoute['pid']];
                    $newRoute['type']  = RouteEnumType::NODE;

                    // 增加到要保存的节点里面
                    $saveRoute[] = $newRoute;

                }
            }

            Route::insertAll($saveRoute);

            Db::commit();
        } catch (\Throwable $throwable) {
            Db::rollback();
            throw new SdException($throwable->getMessage().$throwable->getLine());
        }
    }
}

