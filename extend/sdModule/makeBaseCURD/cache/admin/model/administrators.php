<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:28:17
 */

namespace app\admin\model;

use \app\common\model\Administrators as commonAdministrators;
use sdModule\layui\TablePage;
use sdModule\layui\defaultForm\FormData;
use app\admin\model\system\Role;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;

class Administrators extends commonAdministrators
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
            FormData::text('name', '用户名'),
            FormData::text('account', '账号'),
            FormData::password('password', '密码'),
            FormData::radio('error_number', '密码错误次数'),
            FormData::time('error_date', '错误日期', 'date'),
            FormData::select('role_id', '角色', Role::addSoftDelWhere()->column('role', 'id')),
            FormData::radio('status', '状态', self::getStatusSc()),
        ];
    }

    /**
     * @return TablePage
     */
    public static function getTablePage()
    {
        $table = TablePage::create([
            TablePage::column(['type' => 'checkbox']),
            TablePage::column('id', ''),
            TablePage::column('name', '用户名'),
            TablePage::column('account', '账号'),
            TablePage::column('error_number', '密码错误次数'),
            TablePage::column('lately_time', '最近登录'),
            TablePage::column('error_date', '错误日期'),
            TablePage::column('role_role', '角色'),
            TablePage::column('status', '状态'),
            TablePage::column('create_time', '创建时间'),
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
            SearchForm::Text('i.id', "")->label(true)->html(),
            SearchForm::Text('i.name%%', "用户名")->label(true)->html(),
            SearchForm::Text('i.account%%', "账号")->label(true)->html(),
            SearchForm::Text('i.error_number', "密码错误次数")->label(true)->html(),
            SearchForm::TimeRange("i.lately_time_~", "最近登录")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::TimeRange("i.error_date_~", "错误日期")->label(true)->html(TimeRange::TYPE_DATE),
            SearchForm::Text('role.role%%', "角色")->label(true)->html(),
            SearchForm::Select('i.status', "状态")->label(true)->html(self::getStatusSc(false)),
            SearchForm::TimeRange("i.create_time_~", "创建时间")->label(true)->html(TimeRange::TYPE_DATETIME),
        ];
    }
}
