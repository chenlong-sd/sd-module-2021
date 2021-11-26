<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020/4/3 15:22
 */


namespace app\admin\model\system;


use app\admin\enum\RouteEnumType;
use app\common\BaseModel;
use app\common\SdException;
use sdModule\common\Sc;
use think\facade\Config;
use think\facade\Log;

/**
 * @property $id
 * @property $title
 * @property $route
 * @property $pid
 * @property $type
 * @property $weigh
 * @property $icon
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * Class Route
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Route extends BaseModel
{

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
     * @throws \Exception
     */
    public function getTypeAttr($value): string
    {
        return RouteEnumType::create($value)->getDes();
    }

    /**
     * 获取指定属性的值
     * @return array
     */
    public static function getType(): array
    {
        return RouteEnumType::getAllMap();
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
                $left_route = $this->routeFromType(RouteEnumType::LEFT_MENU, 'id,title,route,pid,icon');
            }else{
                $left_route = self::where([
                    ['p.role_id', 'in', explode(',', admin_session('role_id'))],
                    ['i.type', '=', RouteEnumType::LEFT_MENU],
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
        $top = $this->routeFromType(RouteEnumType::LEFT_MENU, 'id,title,route,pid,icon');

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
     * 删除路由
     * @param $id
     * @return bool
     * @throws \Throwable
     */
    public function deleteRoute($id): bool
    {
        $this->startTrans();
        try {
            $all = self::column('id,pid');

            $delArr = Sc::infinite($all)->handle($id, true);
            $delAll = array_column($delArr, 'id');

            self::where(['id' => $delAll])->update(['delete_time' => 0]);
            Power::where(['route_id' => $delAll])->update(['delete_time' => 0]);

            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            Log::error($exception->getMessage());
            throw new SdException('failed to delete');
        }

        return true;
    }
}
