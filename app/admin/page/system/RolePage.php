<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\model\system\DataAuth;
use app\common\BasePage;
use sdModule\layui\form4\FormProxy as DefaultForm;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use think\facade\Config;

class RolePage extends BasePage
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
            Column::normal('ID', 'id'),
            Column::normal('角色名', 'role'),
            Column::normal('角色类型', 'assign_table'),
            Column::normal('父级角色', 'parent_role'),
            Column::normal('创建时间', 'create_time'),
        ]);

        $table->setHandleAttr([
            'width' => 180
        ]);

        $table->addBarEvent('directly_under')->setNormalBtn('直属', 'username', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'directly_under']));

        $table->addBarEvent('all')->setNormalBtn('全部', 'group', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'all']));

        $table->addEvent('power')->setNormalBtn('权限设置', 'auz')
            ->setJs(EventHandle::openPage([url('powerSet'), 'role_id'], '权限设置')->popUps());

        return $table;
    }

    /**
     * @inheritDoc
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     */
    public function formPageData(string $scene, array $default_data = []): DefaultForm
    {
        $form_data = [
            FormUnit::hidden('id'),
            FormUnit::text('role', '角色名'),
            FormUnit::textarea('describe', '角色描述'),
        ];

        if ($assign_table = Config::get('admin.open_login_table', [])) {
            $assign_table = array_map(function ($v) {
                return $v['name'] ?? '——';
            }, $assign_table);
            $form_data[] = FormUnit::select('assign_table', '角色类型')
                ->shortTip('该类型账户可使用该角色权限登录使用系统,默认为系统用户类型')->options($assign_table);
        }

        if ($dataAuth = Config::get('admin.data_auth', '')){
            $default = DataAuth::where(['role_id' => request()->get('id')])->column('auth_id', 'table_names');

            $form_data[] = FormUnit::auxTitle('指定数据权限');

            foreach ($dataAuth as $data){
                $form_data[] = FormUnit::checkbox("data_auth_table_{$data['table']}", $data['remark'])->options(DataAuth::canBeSetData($data['table']))
                    ->defaultValue(empty($default[$data['table']]) ? [] : explode(',', $default[$data['table']]));
            }
        }
        $form = DefaultForm::create($form_data, $default_data);

        return $form;
    }

    /**
     * @return DefaultForm
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function listSearchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::group(
                FormUnit::text('i.id')->placeholder('ID'),
                FormUnit::text('i.role%%')->placeholder('角色名'),
                FormUnit::text('ip.role%%')->placeholder('父级角色'),
                FormUnit::text('administrators.name%%')->placeholder('创建者'),
                FormUnit::time('i.create_time_~')->dateType('datetime', '~')->placeholder('创建时间'),
            )
        ];

        return DefaultForm::create($form_data)->setSearchSubmitElement();
    }

}
