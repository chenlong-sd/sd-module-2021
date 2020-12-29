<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-10-20 17:57:28
 */

namespace app\admin\model;

use \app\common\model\Role as commonRole;
use sdModule\layui\TablePage;
use sdModule\layui\defaultForm\FormData;
use app\admin\model\system\Administrators;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;

class Role extends commonRole
{

    

    /**
     * 返回form表单的构建数据类
     * @return array
     */
    public static function FormData()
    {
        return [
            FormData::hidden('id'),
            FormData::text('role', '角色名'),
            FormData::select('pid', '父级角色', Role::addSoftDelWhere()->column('role', 'id')),
            FormData::text('describe', '角色描述'),
            FormData::select('administrators_id', '创建角色的管理员', Administrators::addSoftDelWhere()->column('name', 'id')),
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
            TablePage::column('role', '角色名'),
            TablePage::column('pid', '父级角色'),
            TablePage::column('describe', '角色描述'),
            TablePage::column('administrators_id', '创建角色的管理员'),
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
            SearchForm::Text('i.role%%', "角色名")->label(true)->html(),
            SearchForm::Text('role.role%%', "父级角色")->label(true)->html(),
            SearchForm::Text('i.describe%%', "角色描述")->label(true)->html(),
            SearchForm::Text('administrators.name%%', "创建角色的管理员")->label(true)->html(),
            SearchForm::TimeRange("i.create_time_~", "创建时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::TimeRange("i.update_time_~", "修改时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::Text('i.delete_time', "删除时间")->label(true)->html(),
        ];
    }
}
