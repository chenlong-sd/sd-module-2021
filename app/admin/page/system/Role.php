<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\model\system\DataAuth;
use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\defaultForm\FormUnit;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;

class Role extends BasePage
{
    public int $md = 12;

    /**
     * @return string
     */
    public function listPageName(): string
    {
        return $this->lang('role');
    }

    /**
     * 获取创建列表table的数据
     * @return TablePage
     * @throws \app\common\SdException
     */
    public function getTablePageData(): TablePage
    {
        $table = TablePage::create([
            TableAux::column(['type' => 'checkbox'], ),
            TableAux::column('id', 'ID'),
            TableAux::column('role', '角色名'),
            TableAux::column('parent_role', '父级角色'),
            TableAux::column('describe', '角色描述'),
            TableAux::column('administrators_id', '创建者'),
            TableAux::column('create_time', '创建时间'),
        ]);

        $table->setHandleWidth(250);

        $table->addBarEvent('directly_under')->setHtml(Layui::button('直属', 'username')->setEvent('directly_under')->normal('sm'))
            ->setJs(TableAux::searchWhere(['mode' => 'directly_under']));

        $table->addBarEvent('all')->setHtml(Layui::button('全部', 'group')->setEvent('all')->normal('sm'))
            ->setJs(TableAux::searchWhere(['mode' => 'all']));

        $table->addEvent('power')->setHtml(Layui::button('权限设置', 'auz')->setEvent('power')->normal('xs'))
            ->setJs(TableAux::openPage([url('system.Power/power'), 'role_id'], '权限设置'));

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
        if (env('APP.DATA_AUTH', false)){
            $default = DataAuth::where(['delete_time' => 0])
                ->where(['role_id' => request()->get('id')])
                ->column('auth_id', 'table_names');

            foreach (config('admin.data_auth') as $data){
                $form_data[] = FormUnit::selects("data_auth_table_{$data['table']}", $data['remark'], Administrators::dataAuth($data['table']))
                    ->preset(empty($default[$data['table']]) ? [] : explode(',', $default[$data['table']]));
            }
        }
        $form = DefaultForm::create($form_data)->setDefaultData($default_data);

        return $form->complete();
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::build(
                FormUnit::text('i.id', '', 'ID'),
                FormUnit::text('i.role%%', '', '角色名'),
                FormUnit::text('ip.role%%', '', '父级角色'),
                FormUnit::text('administrators.name%%', '', '创建者'),
                FormUnit::time('i.create_time_~', '', 'datetime', '~', '创建时间'),
                FormUnit::custom('', '', DefaultForm::searchSubmit())
            )
        ];

        return DefaultForm::create($form_data)->setNoSubmit()->complete();
    }

}
