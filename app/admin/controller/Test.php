<?php
/**
 * Test.php
 * User: ChenLong
 * DateTime: 2020-11-25 17:38:39
 */

namespace app\admin\controller;

use \app\common\controller\Admin;
use app\common\ResponseJson;
use sdModule\layui\defaultForm\Form;
use sdModule\layui\defaultForm\FormData;


/**
 * Class Test
 * @package app\admin\controller\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Admin
{
    /**
     * 列表数据接口
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws \app\common\SdException
     */
    public function listData()
    {
        return $this->setJoin([
                ['administrators', 'i.administrators_id = administrators.id ', 'left'],
                ['test', 'i.pid = test.id ', 'left'],
            ])
            ->setField('i.id,i.title,i.cover,i.intro,i.status,administrators.name administrators_name,i.administrators_id,test.title parent_title,i.pid,i.create_time')
            ->listsRequest();
    }

    public function ff()
    {
        if ($this->request->isPost()){
            return ResponseJson::success();
        }
        return $this->fetch('common/save_page', [
            'form' => Form::create([
                FormData::text('vv', 'dd')
            ])
        ]);
    }
}
