<?php
/**
 * 后台操作日志
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-14 14:04
 */

namespace app\admin\controller\system;

use app\common\service\BackstageListsService;

/**
 * Class Log
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends \app\common\controller\Admin
{

    /**
     * 列表数据接口
     * @param BackstageListsService $service
     * @return array|\Closure|mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $model = \app\admin\model\system\Log::join('route', 'i.route_id = route.id ', 'left')
            ->join('administrators', 'i.administrators_id = administrators.id ', 'left')
            ->field('i.id,i.method,route.title route_title,route.id route_id,administrators.name administrators_name,i.param,i.route,i.create_time');

        return $service->setModel($model)->getListsData();
    }
   
}