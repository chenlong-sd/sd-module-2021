<?php
/**
 * Date: 2020/11/25 14:54
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layuiSearch\Form;
use sdModule\layuiSearch\generate\TimeRange;
use sdModule\layuiSearch\SearchForm;

class Log extends BasePage
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
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     */
    public function formData(string $scene, array $default_data = []): DefaultForm
    {
        return DefaultForm::create([]);
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return $this->lang('lists title');
    }

    public function searchFormData():string
    {
        $data = [
            SearchForm::Text("i.id", "ID")->label(true)->html(),
            SearchForm::Select("i.method", lang('log.request type'))->label(true)->html(\app\admin\model\system\Log::getMethodSc(false)),
            SearchForm::Text("route.title%%", lang('log.route title'))->label(true)->html(),
            SearchForm::Text("administrators.name%%", lang('log.administrator'))->label(true)->html(),
            SearchForm::Text("i.param%%", lang('log.request param'))->label(true)->html(),
            SearchForm::Text("i.route%%", lang('log.request url'))->label(true)->html(),
            SearchForm::TimeRange("i.create_time_~", lang('create_time'))->label(true)->html(TimeRange::TYPE_DATETIME),

        ];
        return Form::CreateHTML($data);
    }

    /**
     * @return array
     */
    public function setQuickSearchField():array
    {
        return [
            'route.title%%' => lang('route.route_title'),
            'administrators.name%%' => lang('administrator.administrator')
        ];
    }
}
