<?php
/**
 * datetime: 2021/11/9 11:46
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\service\system;

use app\admin\AdminBaseService;
use app\admin\model\system\Log;
use app\common\service\BackstageListsService;

class LogService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = Log::join('route', 'i.route_id = route.id ', 'left')
            ->join('administrators', 'i.administrators_id = administrators.id ', 'left')
            ->field('i.id,i.method,route.title route_title,route.id route_id,administrators.name administrators_name,i.route,i.create_time');

        return $service->setModel($model)->getListsData();
    }

}
