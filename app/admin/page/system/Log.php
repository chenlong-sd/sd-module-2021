<?php
/**
 * Date: 2020/11/25 14:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\form\FormUnit;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;

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
    public function getTablePageData(): ListsPage
    {
        $table = ListsPage::create([
            TableAux::column()->checkbox(),
            TableAux::column('method', '请求方式'),
            TableAux::column('route_title', '权限节点名'),
            TableAux::column('administrators_name', '操作管理员'),
            TableAux::column('param', '请求参数'),
            TableAux::column('route', '节点地址'),
            TableAux::column('create_time', '创建时间'),
        ]);

        $table->removeEvent(['update', 'delete']);
        $table->removeBarEvent(['create', 'delete']);
        return $table;
    }


    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::build(
                FormUnit::text('route.title%%')->placeholder('节点名'),
                FormUnit::select('i.method')->placeholder("请求方式")->options( \app\admin\model\system\Log::getMethodSc(false)),
                FormUnit::text('i.route%%')->placeholder('节点地址'),
                FormUnit::text('administrators.name%%')->placeholder('操作人员'),
                FormUnit::time('i.create_time_~')->placeholder('创建时间'),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit())
            )
        ];

        return DefaultForm::create($form_data)->setSubmitHtml()->complete();
    }
}
