<?php
/**
 *
 * Log.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:47:33
 */

namespace app\admin\model;

use \app\common\model\Log as commonLog;
use sdModule\layui\TablePage;
use sdModule\layui\defaultForm\FormData;
use app\admin\model\Route;
use app\admin\model\system\Administrators;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;

class Log extends commonLog
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     */   
    public function getMethodAttr($value)
    {
        $field = self::getMethodSc();
        
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
            FormData::radio('method', '请求方式', self::getMethodSc()),
            FormData::select('route_id', '路由ID', Route::addSoftDelWhere()->column('title', 'id')),
            FormData::select('administrators_id', '操作管理员', Administrators::addSoftDelWhere()->column('name', 'id')),
            FormData::text('param', '请求参数'),
            FormData::text('route', '路由地址'),
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
            TablePage::column('method', '请求方式'),
            TablePage::column('route_title', '路由ID'),
            TablePage::column('administrators_name', '操作管理员'),
            TablePage::column('param', '请求参数'),
            TablePage::column('route', '路由地址'),
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
            SearchForm::Select('i.method', "请求方式")->label(true)->html(self::getMethodSc(false)),
            SearchForm::Text('route.title%%', "路由ID")->label(true)->html(),
            SearchForm::Text('administrators.name%%', "操作管理员")->label(true)->html(),
            SearchForm::Text('i.param%%', "请求参数")->label(true)->html(),
            SearchForm::Text('i.route%%', "路由地址")->label(true)->html(),
            SearchForm::TimeRange("i.create_time_~", "创建时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::TimeRange("i.update_time_~", "修改时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::Text('i.delete_time', "删除时间")->label(true)->html(),
        ];
    }
}
