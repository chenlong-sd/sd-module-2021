<?php
/**
 * Role
 * User: ChenLong
 * DateTime: 2020-10-20 17:57:28
 */

namespace app\admin\controller;

use \app\common\controller\Admin;


/**
 * Class Role
 * @package app\admin\controller\Role
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends Admin
{
    public string $page_name = "角色";

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
                ['role', 'i.pid = role.id ', 'left'],
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
            ])
            ->setField('i.id,i.role,role.role parent_role,i.pid,i.describe,administrators.name administrators_name,i.administrators_id,i.create_time,i.update_time,i.delete_time')
            ->listsRequest();
    }


    /**
     * 搜索表单生成
     * @return array|mixed
     */
    public function setSearchForm()
    {
        return [
            
        ];
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
