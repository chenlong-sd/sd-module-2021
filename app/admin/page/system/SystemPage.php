<?php
/**
 * datetime: 2021/11/6 11:48
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\page\system;

use app\admin\model\system\BaseConfig as BaseConfigM;
use app\common\BasePage;
use app\common\service\BaseConfigService;
use sdModule\layui\form4\FormProxy as Form;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\form\UnitData;
use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use think\helper\Str;


class SystemPage extends BasePage
{
    /**
     * 数据备份页面
     * @return PageData
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function dataBackUp(): PageData
    {
        $table = PageData::create([
            Column::normal('表名', "name", ),
            Column::normal('表注释', "comment", ),
            Column::normal('数据长度', "length")->setTemplate("return (obj.length / 1024) + ' KB'"),
        ]);
        $table->removeEvent();
        $table->removeBarEvent();

        $table->addBarEvent('all_back')->setWarmBtn('备份全部数据', 'slider', 'sm')
            ->setJs(EventHandle::openPage(url('system.System/backUp'), '备份数据中')->setConfirm('确认备份数据吗？', ['icon' => 3])->popUps());

        $table->addBarEvent('see_all')->setNormalBtn('查看备份数据', 'slider', 'sm')
            ->setJs(EventHandle::openPage(url('system.System/viewBackupFiles'), '已备份的文件')->popUps());

        $table->addEvent('see')->setNormalBtn('查看备份', 'read', 'xs')
            ->setJs(EventHandle::openPage([url(  'system.System/viewBackupFiles'), 'name'], '【{comment}】已备份的文件')->popUps());

        $table->addEvent('back_up')->setWarmBtn('开始备份', 'slider', 'xs')
            ->setJs(EventHandle::openPage([url('system.System/backUp'), 'name'], '备份{comment}数据中')->setConfirm('确认备份{comment}数据吗？', ['icon' => 3])->popUps());

        $table->setHandleAttr([
            'width' => 220
        ]);

        return $table;
    }

    /**
     * 查看备份文件的页面
     * @return PageData
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function viewBackupFiles(): PageData
    {
        $tables = PageData::create([
            Column::normal('文件', 'filename'),
            Column::normal('备份时间', 'time'),
            Column::normal('文件大小', 'size')->setTemplate("return (obj.size / 1024) + ' KB'")
        ]);

        $tables->setHandleAttr([
            'align' => 'center',
            'width' => 200
        ]);

        $tables->removeEvent();
        $tables->removeBarEvent();

        $tables->addEvent('recover')->setNormalBtn('恢复', 'time', 'xs')
            ->setJs(EventHandle::openPage([url('system.System/dataRecover?table=' . request()->get('name')), 'filename'], '数据恢复中....')->popUps()
                ->setConfirm('确认恢复当前数据吗？', ['icon' => 3, 'title' => '提示']));

        $tables->addEvent('del')->setDangerBtn('删除', 'delete', 'xs')
            ->setJs(EventHandle::ajax(url('system.System/backUpDelete?table=' . request()->get('name')), '确认删除数据？')
                ->setConfig(['title' => '警告']));

        $tables->setConfig(['page' => false]);

        return $tables;
    }

    /**
     * 基础信息设置组页面
     * @param string $group_id 分组标识
     * @return Form
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/7
     */
    public function baseInfoItem(string $group_id): Form
    {
        $form_data  = []; // 表单数据
        $short_form = []; // 短标签
        $form_group = []; // 表单组，用来判断一行多个表单
        $init_sort_value = 1; // 设置初始排序值

        try {
            $data = BaseConfigM::where(compact('group_id'))
                ->field(['id', 'group_id', 'key_id', 'key_name', 'form_type', 'options', 'key_value', 'short_tip', 'placeholder', 'required', 'sort'])
                ->order('sort', 'acs')->order('id', 'asc')
                ->select();
        } catch (\Throwable $exception) {
            $data = [];
            \think\facade\Log::write($exception->getMessage(), 'error');
        }

        foreach ($data as $v) {
            $form_type = Str::camel($v->form_type);
            $v->id     = "id$v->id";
            /** @var BaseFormUnitProxy| $form_unit */
            $form_unit = FormUnit::$form_type($v->id,  $v->key_name . BaseConfigService::getDebugParamInfo($v->group_id, $v->key_id, $v->sort));
            // 选项值设置
            if ($v->options){
                try {
                    $form_unit->options(json_decode($v->options, true));
                }catch (\Throwable $exception){}
            }
            // 必选设置
            if ($v->getData('required')) {
                try {
                    $form_unit->required();
                } catch (\Throwable $exception) {}
            }
            // placeholder 设置
            if ($v->placeholder){
                try {
                    $form_unit->placeholder($v->placeholder);
                }catch (\Throwable $exception){}
            }
             // 短标签设置
            if ($v->short_tip){
                try {
                    $form_unit->showTip($v->short_tip);
                }catch (\Throwable $exception){}
            }

            // 当前的排序值不等于上一次的排序值
            if ($v->sort != $init_sort_value && $form_group) {
                // 有行内表单的时候，看看行内表单的个数，大于一个则加入行内，否则不处理
                $form_data[]     = count($form_group) > 1 ? FormUnit::group(...$form_group) : current($form_group);
                // 更新排序值
                $init_sort_value = $v->sort;
                $form_group = [];
            }
            $form_group[] = $form_unit;
        }
        // 处理最后一组表单
        $form_data[] = count($form_group) > 1 ? FormUnit::group(...$form_group) : current($form_group);

        return Form::create($form_data, array_column($data->toArray(), 'key_value', 'id'))
            ->setPane()->addJs('
            layui.jquery(".layui-form-label").on("mouseover", function(){ 
                if(layui.jquery(this).find(".sc-key")){
                  layui.jquery(this).css({overflow:"visible"}).find(".sc-key").show();
                }
            }).on("mouseout", function(){
                if(layui.jquery(this).find(".sc-key")){
                  layui.jquery(this).css({overflow:"hidden"}).find(".sc-key").hide();
                }
            });
            ');
    }
}
