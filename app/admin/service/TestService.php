<?php
/**
* Test.php
* DateTime: 2021-11-11 16:58:27
*/

namespace app\admin\service;

use app\admin\AdminBaseService;
use app\common\service\BackstageListsService;
use app\common\SdException;
use app\admin\model\Test as MyModel;

/**
* 测试表 服务层
* Class TestService
* @package app\admin\service\TestService
*/
class TestService extends AdminBaseService
{
    /**
     * 列表数据
     * @param BackstageListsService $service
     * @return \think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service): \think\response\Json
    {
        $model = MyModel::field('i.id,i.title,i.cover,i.intro,i.status status_true,i.status,administrators.name administrators_name,i.administrators_id,test.title parent_title,i.pid,i.create_time,i.update_time,i.delete_time')
            ->join('administrators', 'i.administrators_id = administrators.id ', 'left')
            ->join('test', 'i.pid = test.id ', 'left');

        return $service->setModel($model)->getListsData();
    }

}
