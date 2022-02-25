<?php
/**
 * Api.php
 * Date: 2020-12-11 11:09:23
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
 * Class Api
 * @package app\admin\page
 */
class ApiPage extends BasePage
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
            Column::normal('请求方式', 'method')->moreConfiguration(['width' => 100]),
            Column::normal('接口名', 'api_name'),
            Column::normal('路径', 'path'),
            Column::normal('对接状态', 'status'),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->setConfig([
            'where' => ['search' => ['api_module_id' => request()->get('id')]],
            'page' => false
        ]);
        $table->setHandleAttr([
            'width' => 210,
        ]);

        $table->removeBarEvent(['create','delete']);

        $table->addEvent('see')
            ->setNormalBtn('查看','read','xs')
            ->setJs(EventHandle::openPage([url('update?see=1'), 'id'], '查看')->popUps(['area' => ['90%', "90%"]]));
        $table->addEvent('docking')->setWhere('d.status_1 == 1')
            ->setWarmBtn('已对接','','xs')
            ->setJs(EventHandle::ajax(url('docking'), '确认已对接？'));
        $table->addBarEvent('createii')->setDefaultBtn('新增','add-1','sm')
            ->setJs(EventHandle::openPage(url(sprintf('system.api/create?api_module_id=%s', request()->get('id'))), '创建')->popUps(['area' => ['90%', '90%']]));

        $table->addJs("setInterval(()=>table.reload('sc'), 60000)");

        return $table;
    }

    /**
     * 生成表单的数据
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     */
    public function formPageData(string $scene, array $default_data = []): DefaultForm
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::hidden('api_module_id')->defaultValue(request()->get('api_module_id')),
            FormUnit::text('api_name', '接口名'),
            FormUnit::text('path', '路径'),
            FormUnit::text('describe', '描述'),
            FormUnit::uEditor('response', '响应示例'),
        ];
        $form = DefaultForm::create($unit, $default_data);

        return $form;
    }


    /**
     * DefaultForm
     * @return DefaultForm
     */
    public function listSearchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::group(
                FormUnit::text('i.api_name%%')->placeholder('接口名'),
                FormUnit::text('i.path%%')->placeholder("路径"),
                FormUnit::time('i.update_time_~')->placeholder('修改时间'),
            ),
        ];
        return DefaultForm::create($form_data)->setSearchSubmitElement();
    }

}
