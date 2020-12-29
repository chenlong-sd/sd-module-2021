<?php
/**
 * Log
 * User: ChenLong
 * DateTime: 2020-10-20 18:47:33
 */

namespace app\admin\controller;

use \app\common\controller\Admin;


/**
 * Class Log
 * @package app\admin\controller\Log
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends Admin
{
    public string $page_name = "后台操作日志";

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
                ['route', 'i.route_id = route.id ', 'left'],
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
            ])
            ->setField('i.id,i.method,route.title route_title,i.route_id,administrators.name administrators_name,i.administrators_id,i.param,i.route,i.create_time,i.update_time,i.delete_time')
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
