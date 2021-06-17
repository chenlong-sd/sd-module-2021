<?php
/**
 * Test.php
 * User: ChenLong
 * DateTime: 2021-06-17 16:27:13
 */

namespace app\admin\controller;

use \app\common\controller\Admin;
use app\common\service\BackstageListsService;


/**
 * Class Test
 * @package app\admin\controller\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Admin
{
    /**
     * 列表数据接口
     * @param BackstageListsService $service
     * @return false|mixed|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData(BackstageListsService $service)
    {
        $mode = $this->getModel()
            ->join('administrators', 'i.administrators_id = administrators.id ', 'left')
            ->join('test', 'i.pid = test.id ', 'left')
            ->field('i.id,i.title,i.cover,i.intro,i.status,administrators.name administrators_name,i.administrators_id,test.title parent_title,i.pid,i.create_time,i.update_time,i.delete_time');

        return $service->setModel($mode)->getListsData();
    }

}
