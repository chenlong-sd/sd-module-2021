<?php
/**
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:36
 */

namespace app\admin\controller\system;

use \app\common\controller\Admin;


/**
 * Class ApiModule
 * @package app\admin\controller\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends Admin
{
    /**
     * 列表数据接口
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData()
    {
        return $this
            ->setField('i.id,i.item_name,url_prefix,i.update_time')
            ->listsRequest();
    }

}
