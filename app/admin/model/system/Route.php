<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020/4/3 15:22
 */


namespace app\admin\model\system;


use app\admin\enum\RouteEnumType;
use app\admin\AdminLoginSession;
use app\admin\service\system\AdministratorsService;
use app\common\BaseModel;
use app\common\SdException;
use sdModule\common\Sc;
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
     * 获取菜单
     * @return array
     * @throws SdException|\Exception
     */
    public function getMenu(): array
    {
        return Sc::tree($this->getRouteFromType(RouteEnumType::create(RouteEnumType::LEFT_MENU)))->getTreeData();
    }

    /**
     * @return array
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/29
     */
    public function getNode(): array
    {
        return [
            'top'  => Sc::tree($this->getRouteFromType(RouteEnumType::create(RouteEnumType::TOP_MENU)))->setLevel(1)->getTreeData(),
            'left' => Sc::tree($this->getRouteFromType(RouteEnumType::create(RouteEnumType::LEFT_MENU)))->setLevel(1)->getTreeData(),
            'node' => Sc::tree($this->getRouteFromType())->setLevel(3)->getTreeData(),
        ];
    }


    /**
     * 根据类型找路由
     * @param RouteEnumType|null $type
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    public function getRouteFromType(RouteEnumType $type = null): array
    {
        $route = $this->alias('i')->field('i.id,i.title,i.route,i.pid,i.icon,i.type node_type')->order('weigh');

        // 有类型的时候
        if ($type instanceof RouteEnumType) {
            $route = $route->where('i.type', $type->getValue());
        }

        // 不是超管
        if (!AdministratorsService::isSuper()) {
            $route = $route->join('power p', 'p.route_id = i.id')
            ->where('p.role_id', 'in', explode(',', AdminLoginSession::getRoleId()));
        }

        try {
            return  $route->select()->toArray();
        } catch (\Exception $exception) {
            Log::write($exception->getMessage(), 'error');
            return [];
        }
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
