<?php
/**
 * ApiModule.php
 * Date: 2020-12-11 11:08:43
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layuiSearch\Form;
use sdModule\layui\defaultForm\FormData;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;


/**
 * Class ApiModule
 * @package app\admin\page
 */
class ApiModule extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return TablePage
     * @throws \app\common\SdException
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column(['type' => 'checkbox']),
//            TableAux::column('id', ''),
            TableAux::column('item_name', '模块名'),
            TableAux::column('url_prefix', '路径前缀'),
            TableAux::column('update_time', '修改时间'),
        ]);

        $table->setHandleWidth(250);
        $table->addEvent('api');
        $table->setEventHtml('api', Layui::button('接口', 'release')->setEvent('api')->normal('xs'));
        $table->setEventJs('api', TableAux::openTabs([url('system.Api/index'), 'url_prefix'], '【{item_name}】接口维护'));
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
            FormData::text('item_name', '模块名'),
            FormData::tag('url_prefix', '路径前缀'),
            FormData::text('token', 'Token参数', 'key=value&key1=value1'),
            FormData::text('describe', '描述'),
        ];

        $form = DefaultForm::create($unit);
        $form->setDefaultData($default_data);

        return $form->complete();
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "接口模块";
    }

    /**
     * 创建搜索表单的数据
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormData::build(
                FormData::text('i.item_name%%', '', '模块名'),
                FormData::time('i.update_time_~', '', 'datetime', '~', '修改时间'),
                FormData::custom('', '', DefaultForm::searchSubmit())
            ),
        ];
        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
