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
use sdModule\layui\defaultForm\FormUnit;
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
            TableAux::column()->checkbox(),
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
        $unit = [];

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

}
