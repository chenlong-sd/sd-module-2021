<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\model\system\DataAuth;
use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\form\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use think\facade\Config;

class Role extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     */
    public function getTablePageData(): PageData
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
            ->setJs(EventHandle::openPage([url('system.Power/power'), 'role_id'], '权限设置')->popUps());

        return $table;
    }

    /**
     * @inheritDoc
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function formData(string $scene, array $default_data = []): DefaultForm
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
            $form_data[] = FormUnit::select('assign_table', '账户可用')->options($assign_table);
        }

        if (env('APP.DATA_AUTH', false)){
            $default = DataAuth::where(['delete_time' => 0])
                ->where(['role_id' => request()->get('id')])
                ->column('auth_id', 'table_names');

            foreach (config('admin.data_auth') as $data){
                $form_data[] = FormUnit::selects("data_auth_table_{$data['table']}", $data['remark'])->options(Administrators::dataAuth($data['table']))
                    ->defaultValue(empty($default[$data['table']]) ? [] : explode(',', $default[$data['table']]));
            }
        }
        $form = DefaultForm::create($form_data)
            ->setShortForm([
                'assign_table' => '该类型账户可使用该角色权限登录使用系统'
            ])->setDefaultData($default_data);

        return $form->complete();
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::inline('')->setChildrenItem(
                FormUnit::text('i.id')->placeholder('ID'),
                FormUnit::text('i.role%%')->placeholder('角色名'),
                FormUnit::text('ip.role%%')->placeholder('父级角色'),
                FormUnit::text('administrators.name%%')->placeholder('创建者'),
                FormUnit::time('i.create_time_~')->setTime('datetime', '~')->placeholder('创建时间'),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit())
            )
        ];

        return DefaultForm::create($form_data)->setSubmitHtml('')->complete();
    }

}
