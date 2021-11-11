<?php
/**
 * Dictionary.php
 * Date: 2021-05-06 21:52:58
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;

use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
use sdModule\layui\form\Form;
use sdModule\layui\form\FormUnit;
use app\admin\model\system\Dictionary as MyModel;


/**
 * Class Dictionary
 * @package app\admin\page\system\Dictionary
 */
class Dictionary extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return array
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function listPageData(): PageData
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('字典标识', 'sign'),
            Column::normal('标识名称', 'name'),
            Column::normal('状态', 'status'),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->setHandleAttr([
            'width' => 220
        ]);

        $table->addEvent('dictionary')->setPrimaryBtn('字典配置', 'template-1', 'xs')
            ->setJs(EventHandle::openPage([url('system.dictionary/dictionary'), 'id'], '【{name}】字典配置')->tabs());

        return $table;
    }

    /**
     * 字典值页面数据
     * @return PageData
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/8
     */
    public function getDictionaryPageData()
    {
        $table = PageData::create([
            Column::checkbox(),
            Column::normal('字典值', 'dictionary_value'),
            Column::normal('字典名字', 'dictionary_name'),
            Column::normal('状态', 'status_1')->showSwitch('status', MyModel::getStatusSc(false)),
            Column::normal('修改时间', 'update_time'),
        ]);

        $table->setHandleAttr([
            'align' => 'center',
            'width' => 150
        ]);

        $table->setConfig([
            'where' => [
                'search' => ['i.pid' => request()->get('id', 0)]
            ]
        ]);

        $table->removeBarEvent(['create']);
        $table->removeEvent(['update']);
        $table->addBarEvent('dictionary_add')->setDefaultBtn('添加', 'add-1', 'sm')
            ->setJs(TableAux::openPage(url('dictionaryAdd', ['id' => request()->get('id', 0)]), '添加'));

        $table->addEvent('dictionary_edit')->setDefaultBtn('修改', 'edit', 'xs')
            ->setJs(TableAux::openPage([url('dictionaryEdit')], '修改'));

        return $table;
    }

    /**
    * 生成表单的数据
    * @param string $scene
    * @param array $default_data
    * @return Form
    * @throws \ReflectionException
    * @throws \app\common\SdException
    */
    public function formPageData(string $scene, array $default_data = []): Form
    {
        $unit = [
            FormUnit::hidden('id'),
            FormUnit::text('sign', '标识')->removeScene(['value_add', 'value_edit'])->placeholder('例：ball')->required(),
            FormUnit::hidden('pid', '标识ID')->removeScene(['create', 'update'])->defaultValue(request()->get('id', 0)),
            FormUnit::text('name', '标识名称')->removeScene(['value_add', 'value_edit'])->placeholder('例：球类'),
            FormUnit::text('dictionary_value', '字典值')->removeScene(['create', 'update'])->placeholder('例：basketball'),
            FormUnit::text('dictionary_name', '字典名字')->removeScene(['create', 'update'])->placeholder('例：篮球，不填则默认为字典值，如basketball'),
            FormUnit::radio('status', '状态')->options(MyModel::getStatusSc(false))->defaultValue(1),
        ];

        $form = Form::create($unit, $scene)->setSkinToPane()->setDefaultData($default_data);

        if ($scene === 'value_add') {
            $form->setJs('layui.jquery("input[name=dictionary_value]").focus();');
            $form->setSuccessHandle('window.parent.notice.success("'. lang('success') .'");parent.table.reload("sc");location.reload();');
        }

        return $form->complete();
    }

    /**
     * 字典搜索
     * @return Form
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function dictionarySearchFormData(): DefaultForm
    {
        $form = [];

        return DefaultForm::create($form)->setSubmitHtml()->complete();
    }

}
