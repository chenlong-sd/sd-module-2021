<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:16:20
 */

namespace app\admin\model;

use \app\common\model\Test as commonTest;
use sdModule\layui\TablePage;
use sdModule\layui\defaultForm\FormData;
use app\admin\model\system\Administrators;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;

class Test extends commonTest
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     */   
    public function getStatusAttr($value)
    {
        $field = self::getStatusSc();
        
        return $field[$value] ?? $value;
    }


    /**
     * 返回form表单的构建数据类
     * @return array
     */
    public static function FormData()
    {
        return [
            FormData::hidden('id'),
            FormData::text('title', '标题'),
            FormData::image('cover', '封面'),
            FormData::images('show_images', '展示图'),
            FormData::text('intro', '简介'),
            FormData::radio('status', '状态', self::getStatusSc()),
            FormData::select('administrators_id', '管理员', Administrators::addSoftDelWhere()->column('name', 'id')),
            FormData::select('pid', '上级', Test::addSoftDelWhere()->column('title', 'id')),
            FormData::uEditor('content', '详情'),
        ];
    }

    /**
     * @return TablePage
     */
    public static function getTablePage()
    {
        $table = TablePage::create([
            TablePage::column(['type' => 'checkbox']),
            TablePage::column('id', 'ID'),
            TablePage::column('title', '标题'),
            TablePage::column('cover', '封面', '@image'),
            TablePage::column('intro', '简介'),
            TablePage::column('status', '状态'),
            TablePage::column('administrators_name', '管理员'),
            TablePage::column('parent_title', '上级'),
            TablePage::column('create_time', '创建时间'),
            TablePage::column('update_time', '修改时间'),
            TablePage::column('delete_time', '删除时间'),
        ]);

        $table->setHandleWidth(150);
        return $table;
    }

    /**
    * 返回搜索表单的构建数据类
    * @return array
    */
    public static function searchFormData()
    {
        return [
            SearchForm::Text('i.id', "ID")->label(true)->html(),
            SearchForm::Text('i.title%%', "标题")->label(true)->html(),
            SearchForm::Text('i.show_images%%', "展示图")->label(true)->html(),
            SearchForm::Text('i.intro%%', "简介")->label(true)->html(),
            SearchForm::Select('i.status', "状态")->label(true)->html(self::getStatusSc(false)),
            SearchForm::Text('administrators.name%%', "管理员")->label(true)->html(),
            SearchForm::Text('test.title%%', "上级")->label(true)->html(),
            SearchForm::Text('i.content%%', "详情")->label(true)->html(),
            SearchForm::TimeRange("i.create_time_~", "创建时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::TimeRange("i.update_time_~", "修改时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::Text('i.delete_time', "删除时间")->label(true)->html(),
        ];
    }
}
