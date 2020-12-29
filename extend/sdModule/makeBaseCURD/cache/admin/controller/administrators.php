<?php
/**
 * Administrators
 * User: ChenLong
 * DateTime: 2020-10-20 18:28:17
 */

namespace app\admin\controller;

use \app\common\controller\Admin;


/**
 * Class Administrators
 * @package app\admin\controller\Administrators
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends Admin
{
    public string $page_name = "管理员";

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
                ['role', 'i.role_id = role.id ', 'left'],
            ])
            ->setField('i.id,i.name,i.account,i.error_number,i.lately_time,i.error_date,role.role role_role,i.role_id,i.status,i.create_time')
            ->listsRequest();
    }

    /**
     * 快捷搜索设置
     * @return array
     */
    public function setQuickSearchField(): array
    {
        return [
            
        ];
    }



}
