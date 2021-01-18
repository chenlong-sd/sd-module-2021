<?php
/**
 * 后台操作日志
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-14 14:04
 */

namespace app\admin\controller\system;

use app\common\service\BackstageListService;

/**
 * Class Log
 * @package app\admin\controller\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends \app\common\controller\Admin
{

    /**
     * 列表数据接口
     * @param BackstageListService $service
     * @return array|\Closure|mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListService $service)
    {
        return $service->setModel(\app\admin\model\system\Log::class)->setJoin([
                ['route', 'i.route_id = route.id ', 'left'],
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
            ])
            ->setField('i.id,i.method,route.title route_title,route.id route_id,administrators.name administrators_name,i.param,i.route,i.create_time')
            ->listsRequest();
    }
   
}