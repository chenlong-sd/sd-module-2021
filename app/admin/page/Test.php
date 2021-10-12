<?php
/**
 * Test.php
 * Date: 2021-09-24 18:04:21
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\Dom;
use sdModule\layui\Layui;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormUnit;
use app\admin\model\Test as MyModel;
use app\admin\model\system\Administrators;


/**
 * 测试表
 * Class Test
 * @package app\admin\page\Test
 */
class Test extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function getTablePageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('ID', 'id'),
            Column::normal('标题', 'title'),
            Column::normal('封面', 'cover')->showImage(),
            Column::normal('简介', 'intro'),
            Column::normal('状态', 'status'),
            Column::normal('管理员', 'administrators_name'),
            Column::normal('上级', 'parent_title'),
            Column::normal('创建时间', 'create_time'),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->addBarEvent()->setPrimaryBtn('测试下拉', 'more')->setMenuGroup('test1')
            ->setJs(EventHandle::openPage(url('create'), '测试新增')->popUps(['area' => ['90%', '90%']]));


        $table->setMenuGroup('test1', Layui::button('测试下拉', 'down')->normal('sm'));

        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('title', '标题'),
//            FormUnit::custom()->customHtml(
//                Dom::create()->addClass('layui-form-item')->addContent(
//                    Dom::create('label')->addClass('layui-form-label')->addContent('封面')
//                )->addContent(
//                    Dom::create('button')->addAttr('type', 'button')
//                        ->addClass('layui-btn')->addContent('上传')
//                    ->addContent(Dom::create('input', true)->addAttr(['type' => 'file', 'name' => 'test', 'class' => 'sc-file-upload']))
//                )
//            ),
            FormUnit::images('show_images', '展示图'),
            FormUnit::text('intro', '简介'),
            FormUnit::radio('status', '状态')->options(MyModel::getStatusSc(false)),
            FormUnit::select('administrators_id', '管理员')->options(Administrators::column('name', 'id')),
            FormUnit::select('pid', '上级')->options(MyModel::column('title', 'id')),
            FormUnit::uEditor('content', '详情'),
        ];

        $form = Form::create($unit, $scene)->setDefaultData($default_data);

        return $form->complete();
    }


    /**
     * 创建搜索表单的数据
     * @return Form
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData(): Form
    {
        $form_data = [];
        return Form::create($form_data)->setSubmitHtml()->complete();
    }

}
