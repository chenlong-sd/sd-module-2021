<?php
/**
 * Test.php
 * Date: 2021-11-29 15:12:17
 */

namespace app\admin\page;

use app\common\BasePage;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use app\common\enum\TestEnumStatus;
use app\admin\model\system\Administrators;
use app\common\SdException;
use app\admin\model\Test as MyModel;


/**
 * 测试表
 * Class TestPage
 * @package app\admin\page\TestPage
 */
class TestPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
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
            Column::normal('删除时间', 'delete_time'),
        ]);

        // 更多处理事件及其他设置，$table->setHandleAttr() 可设置操作栏的属性

        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('title', '标题'),
            FormUnit::image('cover', '封面'),
            FormUnit::images('show_images', '展示图'),
            FormUnit::text('intro', '简介'),
            FormUnit::radio('status', '状态')->options(TestEnumStatus::getAllMap(true)),
            FormUnit::select('administrators_id', '管理员')->options(Administrators::column('name', 'id')),
            FormUnit::select('pid', '上级')->options(MyModel::column('title', 'id')),
            FormUnit::uEditor('content', '详情'),
        ];

        $form = Form::create($unit, $default_data)->setScene($scene);

        return $form;
    }


    /**
     * 创建列表搜索表单的数据
     * @return Form
     */
    public function listSearchFormData(): Form
    {
        $form_data = [
            FormUnit::group(
                FormUnit::text('i.title%%')->placeholder('标题'),
                FormUnit::select('i.status')->placeholder('状态')->options(TestEnumStatus::getAllMap(true)),
                FormUnit::time('i.create_time_~',)->dateType('datetime', '~')->placeholder('创建时间'),
            ),
        ];
        
        return Form::create($form_data)->setSearchSubmitElement();
    }


}
