<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020/4/3 15:22
 */


namespace app\admin\model\system;


use app\common\BaseModel;
use app\common\SdException;
use sdModule\common\Sc;
use sdModule\layui\Layui;
use think\facade\Config;
use think\facade\Log;

/**
 * Class Route
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Route extends BaseModel
{

    const TYPE_MENU = 1;
    const TYPE_HANDLE = 2;

    protected $schema = [
        'id' => 'int',
        'title' => 'varchar',
        'route' => 'varchar',
        'pid' => 'int',
        'type' => 'tinyint',
        'weigh' => 'int',
        'icon' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];


    /**
     * 分类值展示处理
     * @param $value
     * @return string
     */
    public function getTypeAttr($value): string
    {
        $field = self::getType();

        return $field[$value] ?? $value;
    }

    /**
     * 获取指定属性的值
     * @return array
     */
    public static function getType(): array
    {
        return [
            self::TYPE_MENU   => Layui::tag()->black(lang('route.menu')),
            self::TYPE_HANDLE => Layui::tag()->blue(lang('route.operating')),
        ];
    }

    /**
     * 获取菜单
     * @return array
     * @throws SdException
     */
    public function getMenu(): array
    {
        return Sc::infinite($this->getMenuRoute())->handle();
    }

    /**
     * 获取菜单路由
     * @return array
     * @throws SdException
     */
    public function getMenuRoute(): array
    {
        try {
            if (admin_session('is_admin') && admin_session('id') == Config::get('admin.super', 1)) {
                $left_route = $this->routeFromType(self::TYPE_MENU, 'id,title,route,pid,icon');
            }else{
                $left_route = self::where([
                    ['p.role_id', 'in', explode(',', admin_session('role_id'))],
                    ['i.type', '=', self::TYPE_MENU],
                ])->alias('i')->order('weigh')
                    ->join('power p', 'p.route_id = i.id')
                    ->field('i.id,i.title,i.route,i.pid,i.icon')
                    ->select()->toArray();
            }

        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
        return $left_route;
    }


    /**
     * 获取节点
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNode()
    {
        $top = $this->routeFromType(self::TYPE_MENU, 'id,title,route,pid,icon');

        return [
            'top'  => Sc::infinite($top)->handle(0),
            'menu' => $this->getMenu()
        ];
    }


    /**
     * 根据类型找路由
     * @param null $type
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function routeFromType($type = null, $field = '*')
    {
        if ($type === null) {
            return self::field($field)->order('weigh')->select()->toArray();
        }
        return  self::field($field)->order('weigh')->where('type',$type)->select()->toArray();
    }


    /**
     * 缓存所有的路由
     * @throws \Exception
     */
    public static function cacheAllRoute()
    {
        $route       = self::column('route', 'id');
        $cache_route = array_map(function ($value) {
            return parse_name(preg_replace_callback('/\.[a-z]/', function ($v){
                return strtoupper($v[0]);
            }, $value), 1);
        }, $route);

        cache(config('admin.route_cache'), $cache_route);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function addHandle($data)
    {
        $this->startTrans();
        try {
            if (!empty($data['children'])) {
                $children = $data['children'];
                unset($data['children']);
                if (!$id = $this->insertGetId($data)) {
                    throw new SdException('fail');
                }

                $this->routeChildren($children, $data['title'], $data['route'], $id);
            }else if (!$this->insertGetId($data)) {
                throw new SdException('fail');
            }

            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            throw $exception;
        }
    }

    /**
     * 保存子操作
     * @param $children
     * @param $title
     * @param $route
     * @param $pid
     * @throws SdException
     */
    public function routeChildren($children, $title, $route, $pid)
    {
        $title = [
            'index'  => $title . lang('List data'),
            'create' => lang('add') . $title,
            'update' => lang('edit') . $title,
            'del'    => lang('delete') . $title,
        ];

        $route = [
            'index'  => strtr($route, ['index' => 'index']),
            'create' => strtr($route, ['index' => 'create']),
            'update' => strtr($route, ['index' => 'update']),
            'del'    => strtr($route, ['index' => 'del']),
        ];

        $data = [];
        $i    = 0;
        foreach ($children as $key => $value) {
            $data[] = [
                'title'       => $title[$key],
                'route'       => $route[$key],
                'pid'         => $pid,
                'type'        => self::TYPE_HANDLE,
                'weigh'       => $i++,
                'icon'        => '',
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
            ];
        }

        if (!$this->insertAll($data)) {
            throw new SdException('route.Sub-operation failed to add');
        }
    }

    /**
     * 删除路由
     * @param $id
     * @return bool
     * @throws \Throwable
     */
    public function deleteRoute($id)
    {
        $this->startTrans();
        try {
            $all = self::column('id,pid');

            $delArr = Sc::infinite($all)->handle($id, true);
            $delAll = array_column($delArr, 'id');

            self::destroy(['id' => $delAll]);
            Power::destroy(['route_id' => $delAll]);

            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            Log::error($exception->getMessage());
            throw new SdException('failed to delete');
        }

        return true;
    }
}
