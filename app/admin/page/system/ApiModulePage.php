<?php
/**
 * ApiModule.php
 * Date: 2020-12-11 11:08:43
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\form4\FormProxy as DefaultForm;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\form4\FormUnit;


/**
 * Class ApiModule
 * @package app\admin\page
 */
class ApiModulePage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('模块名', 'item_name'),
            Column::normal('接口数量', 'api_number')
                ->setTemplate("return obj.api.length ? `\${obj.api_number} <span style=\"color:red;font-weight: bold\">[ 待对接数：\${obj.api.length} ]</span>` : '<span style=\"color:black\">'+ obj.api_number +'</span>'"),
            Column::normal('路径前缀', 'url_prefix'),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->setHandleAttr([
            'width' => 150
        ]);
        $table->addEvent('api')->setNormalBtn('接口','release')
            ->setJs(EventHandle::openPage([url('system.Api/index'), 'url_prefix', 'api_module_id', 'id'], '【{item_name}】接口维护')->tabs());
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
    public function formPageData(string $scene, array $default_data = []): DefaultForm
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('item_name', '模块名')->inputAttr(),
            FormUnit::tag('url_prefix', '路径前缀'),
            FormUnit::text('token', 'Token参数')->placeholder('key=value&key1=value1'),
            FormUnit::text('describe', '描述'),
        ];

        $form = DefaultForm::create($unit, $default_data);

        return $form;
    }


    /**
     * 创建搜索表单的数据
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function listSearchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::group(
                FormUnit::text('i.item_name%%')->placeholder('模块名'),
                FormUnit::time('i.update_time')->dateType('datetime')->jsConfig(['range' => "~"])->placeholder('修改时间'),
            ),
        ];
        return DefaultForm::create($form_data)->setSearchSubmitElement();
    }

}
