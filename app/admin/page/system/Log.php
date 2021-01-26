<?php
/**
 * Date: 2020/11/25 14:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\defaultForm\FormData;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layuiSearch\Form;
use sdModule\layuiSearch\generate\TimeRange;
use sdModule\layuiSearch\SearchForm;

class Log extends BasePage
{
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        // TODO: Implement formData() method.
    }

    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column(['type' => 'checkbox']),
            TableAux::column('id', 'ID'),
            TableAux::column('method', '请求方式'),
            TableAux::column('route_title', '路由ID'),
            TableAux::column('administrators_name', '操作管理员'),
            TableAux::column('param', '请求参数'),
            TableAux::column('route', '路由地址'),
            TableAux::column('create_time', '创建时间'),
        ]);

        $table->setHandleWidth(150);
        $table->removeEvent(['update', 'delete']);
        $table->removeBarEvent(['create', 'delete']);
        return $table;
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return $this->lang('lists title');
    }

    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormData::build(
                FormData::text('i.id', '', 'ID'),
                FormData::text('i.role%%', '', '角色名'),
                FormData::text('ip.role%%', '', '父级角色'),
                FormData::text('administrators.name%%', '', '创建者'),
                FormData::time('i.create_time_~', '', 'datetime', '~', '创建时间'),
                FormData::custom('', '', DefaultForm::searchSubmit())
            )
        ];

        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }
}
