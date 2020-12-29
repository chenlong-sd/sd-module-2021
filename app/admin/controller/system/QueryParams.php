<?php
/**
 * QueryParams.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:10:23
 */

namespace app\admin\controller\system;

use \app\common\controller\Admin;


/**
 * Class QueryParams
 * @package app\admin\controller\QueryParams
 * @author chenlong <vip_chenlong@163.com>
 */
class QueryParams extends Admin
{
    /**
     * 列表数据接口
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData()
    {
        return $this
            ->setField('i.id,i.method,i.param_type,i.name,i.test_value,i.describe,i.update_time,i.delete_time')
            ->listsRequest();
    }

}
