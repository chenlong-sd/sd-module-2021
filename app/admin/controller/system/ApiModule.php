<?php
/**
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:36
 */

namespace app\admin\controller\system;

use \app\common\controller\Admin;
use app\common\service\BackstageListsService;


/**
 * Class ApiModule
 * @package app\admin\controller\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends Admin
{
    /**
     * 列表数据接口
     * @param BackstageListsService $service
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $model = \app\admin\model\system\ApiModule::field('i.id,i.item_name,url_prefix,i.update_time,count(a.id) api_number')
            ->join('api a', 'a.api_module_id = i.id', 'left')
            ->group('i.id')
            ->with('api');
        return $service->setModel($model)->setEach(function ($v) {
            $v->url_prefix = implode(', ', explode('|-|', $v->url_prefix));
        })->getListsData();
    }

}
