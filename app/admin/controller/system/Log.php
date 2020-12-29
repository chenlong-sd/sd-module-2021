<?php
/**
 * 后台操作日志
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-14 14:04
 */

namespace app\admin\controller\system;

use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;
use app\admin\model\Route;
use app\admin\model\system\Administrators;

/**
 * Class Log
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends \app\common\controller\Admin
{

    /**
     * 列表数据接口
     * @return array|\Closure|mixed|string|\think\Collection|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function listData()
    {
        return $this->setJoin([
                ['route', 'i.route_id = route.id ', 'left'],
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
            ])
            ->setField('i.id,i.method,route.title route_title,route.id route_id,administrators.name administrators_name,i.param,i.route,i.create_time')
            ->listsRequest();
    }
   
}