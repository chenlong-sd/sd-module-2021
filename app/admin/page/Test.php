<?php
/**
 * Test.php
 * Date: 2021-05-06 15:48:38
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormUnit;
use app\admin\model\Test as MyModel;
use app\admin\model\system\Administrators;


/**
 * Class Test
 * @package app\admin\page\Test
 */
class Test extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column()->checkbox(),
            TableAux::column('id', 'ID'),
            TableAux::column('title', '标题'),
            TableAux::column('cover', '封面')->image(),
            TableAux::column('intro', '简介'),
            TableAux::column('status', '状态'),
            TableAux::column('administrators_name', '管理员'),
            TableAux::column('parent_title', '上级'),
            TableAux::column('create_time', '创建时间'),
            TableAux::column('update_time', '修改时间'),
            TableAux::column('delete_time', '删除时间'),
        ]);

        $table->setHandleAttr([
            'align' => 'center',
            'width' => 150
        ]);
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
            FormUnit::image('cover', '封面'),
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
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "测试表";
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
