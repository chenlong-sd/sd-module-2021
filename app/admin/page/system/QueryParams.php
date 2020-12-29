<?php
/**
 * QueryParams.php
 * Date: 2020-12-11 11:10:24
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layuiSearch\Form;
use sdModule\layui\defaultForm\FormData;
use app\admin\model\system\QueryParams as MyModel;
use sdModule\layuiSearch\SearchForm;
use sdModule\layuiSearch\generate\TimeRange;


/**
 * Class QueryParams
 * @package app\admin\page
 */
class QueryParams extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column(['type' => 'checkbox']),
            TableAux::column('id', ''),
            TableAux::column('method', '请求参数类型'),
            TableAux::column('param_type', '参数类型'),
            TableAux::column('name', '参数名'),
            TableAux::column('test_value', '测试值'),
            TableAux::column('describe', '描述'),
            TableAux::column('update_time', '修改时间'),
            TableAux::column('delete_time', '删除时间'),
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
            FormData::radio('method', '请求参数类型', MyModel::getMethodSc(false)),
            FormData::radio('param_type', '参数类型', MyModel::getParamTypeSc(false)),
            FormData::text('name', '参数名'),
            FormData::text('test_value', '测试值'),
            FormData::text('describe', '描述'),
        ];

        $form = DefaultForm::create($unit);

        return $form->complete();
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "请求参数表";
    }

    /**
     * 创建搜索表单的数据
     * @return string
     */
    public function searchFormData():string
    {
        $form_data = [
            SearchForm::Text('i.id', "")->label(true)->html(),
            SearchForm::Select('i.method', "请求参数类型")->label(true)->html(MyModel::getMethodSc(false)),
            SearchForm::Select('i.param_type', "参数类型")->label(true)->html(MyModel::getParamTypeSc(false)),
            SearchForm::Text('i.name%%', "参数名")->label(true)->html(),
            SearchForm::Text('i.test_value%%', "测试值")->label(true)->html(),
            SearchForm::Text('i.describe%%', "描述")->label(true)->html(),
            SearchForm::TimeRange("i.update_time_~", "修改时间")->label(true)->html(TimeRange::TYPE_DATETIME),
            SearchForm::TimeRange("i.delete_time_~", "删除时间")->label(true)->html(TimeRange::TYPE_DATETIME),
        ];
        return Form::CreateHTML($form_data);
    }

    /**
     * @return array 设置快捷搜索
     */
    public function setQuickSearchField():array
    {
        return [
            
        ];
    }

}
