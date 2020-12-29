<?php
/**
 * Test
 * User: ChenLong
 * DateTime: 2020-10-20 18:16:20
 */

namespace app\admin\controller;

use \app\common\controller\Admin;


/**
 * Class Test
 * @package app\admin\controller\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Admin
{
    public string $page_name = "测试表";

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
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
                ['test', 'i.pid = test.id ', 'left'],
            ])
            ->setField('i.id,i.title,i.cover,i.intro,i.status,administrators.name administrators_name,i.administrators_id,test.title parent_title,i.pid,i.create_time,i.update_time,i.delete_time')
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
