<?php
/**
 * Api.php
 * Date: 2020-12-11 11:09:23
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layui\defaultForm\FormData;


/**
 * Class Api
 * @package app\admin\page
 */
class Api extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return TablePage
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column(['type' => 'checkbox']),
//            TablePage::column('id', ''),
            TableAux::column('api_name', '接口名'),
            TableAux::column('method', '请求方式'),
            TableAux::column('path', '路径'),
            TableAux::column('status', '对接状态'),
            TableAux::column('update_time', '修改时间'),
        ]);

        $table->setHandleWidth(280);
        $table->setConfig([
            'where' => ['search' => ['api_module_id' => request()->get('id')]],
            'page' => false,
        ]);
        $table->removeBarEvent(['create','delete']);
        $table->setEventWhere('docking', 'd.status_1 == 1');
        $table->addEvent('see')
            ->setNormalBtn('查看','read','xs')
            ->setJs(TableAux::openPage([url('update?see=1')], '查看', ['area' => ['90%', "90%"]]));
        $table->addEvent('docking')
            ->setWarmBtn('已对接','','xs')
            ->setJs(TableAux::ajax(url('docking'), '确认已对接？'));
        $table->addBarEvent('createii')->setDefaultBtn('新增','add-1','sm')
            ->setJs(TableAux::openPage(url(sprintf('system.api/create?api_module_id=%s', request()->get('id'))), '创建', ['area' => ['90%', '90%']]));

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
            FormData::hidden('api_module_id')->preset(request()->get('api_module_id')),
            FormData::text('api_name', '接口名'),
            FormData::text('path', '路径'),
            FormData::text('describe', '描述'),
            FormData::uEditor('response', '响应示例'),
        ];
        $form = DefaultForm::create($unit)->setDefaultData($default_data);

        return $form->complete();
    }

    /**
     * 列表页面的名字
     * @return string
     */
    public function listPageName(): string
    {
        return "api接口表";
    }

    /**
     * DefaultForm
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormData::build(
                FormData::text('i.id', '', 'ID'),
                FormData::text('i.api_name%%', '', ''),
                FormData::text('i.path%%', '', '接口名'),
                FormData::text('i.describe%%', '', '路径'),
                FormData::time('i.update_time_~', '', 'datetime', '~', '修改时间'),
                FormData::custom('', '', DefaultForm::searchSubmit())
            ),
        ];
        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
