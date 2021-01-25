<?php
/**
 * Test.php
 * Date: 2021-01-25 12:06:59
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\defaultForm\FormData;
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
            TableAux::column(['type' => 'checkbox']),
            TableAux::column('id', 'ID'),
            TableAux::column('title', '标题'),
            TableAux::column('cover', '封面', '@image'),
            TableAux::column('intro', '简介'),
            TableAux::column('status', '状态'),
            TableAux::column('administrators_name', '管理员'),
            TableAux::column('parent_title', '上级'),
            TableAux::column('create_time', '创建时间'),
        ]);

        $table->setHandleWidth(150);
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
            FormData::hidden('id'),
            FormData::text('title', '标题'),
            FormData::image('cover', '封面'),
            FormData::images('show_images', '展示图'),
            FormData::text('intro', '简介'),
            FormData::radio('status', '状态', MyModel::getStatusSc(false)),
            FormData::select('administrators_id', '管理员', Administrators::column('name', 'id')),
            FormData::select('pid', '上级', MyModel::column('title', 'id')),
            FormData::u_editor('content', '详情'),
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
            FormData::build(
                FormData::Text('i.id', "", 'ID'),
                FormData::Text('i.title%%', "", '标题'),
                FormData::Text('i.intro%%', "", '简介'),
                FormData::Select('i.status', "", MyModel::getStatusSc(false), '状态'),
                FormData::Text('administrators.name%%', "", '管理员'),
                FormData::Text('test.title%%', "", '上级'),
                FormData::time("i.create_time_~", "", 'datetime', '~', '创建时间'),
                FormData::custom('', '', DefaultForm::searchSubmit())
            )
        ];
        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
