<?php
/**
 * ApiModule.php
 * Date: 2020-12-11 11:08:43
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
use sdModule\layui\form\FormUnit;


/**
 * Class ApiModule
 * @package app\admin\page
 */
class ApiModule extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return ListsPage
     * @throws \app\common\SdException
     */
    public function getTablePageData(): ListsPage
    {
        $table = ListsPage::create([
            TableAux::column()->checkbox(),
            TableAux::column('item_name', '模块名')
                ->setTemplate("return obj.api.length ? `<span style=\"color:red;font-weight: bold\">[ 待对接数：\${obj.api.length} ]</span> \${obj.item_name}`: '<span style=\"color:black\">'+ obj.item_name +'</span>'"),
            TableAux::column('api_number', '接口数量'),
            TableAux::column('url_prefix', '路径前缀'),
            TableAux::column('update_time', '修改时间'),
        ]);

        $table->setHandleAttr([
            'width' => 150
        ]);
        $table->addEvent('api')->setNormalBtn('接口','release','xs')
            ->setJs(TableAux::openTabs([url('system.Api/index'), 'url_prefix', 'api_module_id'], '【{item_name}】接口维护'));
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
            FormUnit::hidden('id'),
            FormUnit::text('item_name', '模块名'),
            FormUnit::tag('url_prefix', '路径前缀'),
            FormUnit::text('token', 'Token参数')->placeholder('key=value&key1=value1'),
            FormUnit::text('describe', '描述'),
        ];

        $form = DefaultForm::create($unit);
        $form->setDefaultData($default_data);

        return $form->complete();
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
            FormUnit::build(
                FormUnit::text('i.item_name%%')->placeholder('模块名'),
                FormUnit::time('i.update_time_~')->setTime('datetime', '~')->placeholder('修改时间'),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit())
            ),
        ];
        return DefaultForm::create($form_data)->setSubmitHtml()->complete();
    }

}
