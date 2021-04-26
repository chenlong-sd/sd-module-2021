<?php
/**
 * Test.php
 * Date: 2021-04-19 09:35:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layui\form\Form as DefaultForm;
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
            TableAux::column('update_time', '修改时间'),
        ]);

        $table->setHandleWidth(80);
        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return DefaultForm
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('title', '标题'),
            FormUnit::image('cover', '封面'),
            FormUnit::images('show_images', '展示图'),
            FormUnit::text('intro', '简介'),
            FormUnit::radio('status', '状态')->selectData(MyModel::getStatusSc(false)),
            FormUnit::select('administrators_id', '管理员')->selectData(Administrators::column('name', 'id')),
            FormUnit::select('pid', '上级')->selectData(MyModel::column('title', 'id')),
            FormUnit::uEditor('content', '详情'),
        ];

        $form = DefaultForm::create($unit)->setDefaultData($default_data);

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
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData(): DefaultForm
    {
        $form_data = [
            FormUnit::build(
                FormUnit::Text('i.title%%')->placeholder('标题'),
                FormUnit::Text('administrators.name%%')->placeholder('管理员'),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit()),
            )
        ];
        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
