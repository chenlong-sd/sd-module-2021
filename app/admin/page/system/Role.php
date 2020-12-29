<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\model\system\DataAuth;
use app\common\BasePage;
use sdModule\layui\defaultForm\Form as DefaultForm;
use sdModule\layui\defaultForm\FormData;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\TableAux;
use sdModule\layuiSearch\Form;
use sdModule\layuiSearch\generate\TimeRange;
use sdModule\layuiSearch\SearchForm;

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
        $table->addEvent(['directly_under', 'all'], true);
        $table->addEvent(['power']);
        $table->setBarEventHtml('directly_under', Layui::button('直属', 'username')->setEvent('directly_under')->normal('sm'));
        $table->setBarEventHtml('all', Layui::button('全部', 'group')->setEvent('all')->normal('sm'));
        $table->setEventHtml('power', Layui::button('权限设置', 'auz')->setEvent('power')->normal('xs'));

        $table->setBarEventJs('directly_under', TableAux::searchWhere(['mode' => 'directly_under']));
        $table->setBarEventJs('all', TableAux::searchWhere(['mode' => 'all']));
        $table->setEventJs('power', TableAux::openPage([url('system.Power/power'), 'role_id'], '权限设置'));

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
            FormData::hidden('id'),
            FormData::text('role', '角色名'),
            FormData::textarea('describe', '角色描述'),
        ];
        if (env('APP.DATA_AUTH', false)){
            $default = DataAuth::where(['delete_time' => 0])
                ->where(['role_id' => request()->get('id')])
                ->column('auth_id', 'table_names');

            foreach (config('admin.data_auth') as $data){
                $form_data[] = FormData::selects("data_auth_table_{$data['table']}", $data['remark'], Administrators::dataAuth($data['table']))
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
    public function searchFormData():string
    {
        $form_data = [
            SearchForm::Text('i.id', "ID")->label(true)->html(),
            SearchForm::Text('i.role%%', "角色名")->label(true)->html(),
            SearchForm::Text('ip.role%%', "父级角色")->label(true)->html(),
            SearchForm::Text('administrators.name%%', "创建者")->label(true)->html(),
            SearchForm::TimeRange("i.create_time_~", "创建时间")->label(true)->html(TimeRange::TYPE_DATETIME),
        ];

        return Form::CreateHTML($form_data);
    }

    /**
     * @return array
     */
    public function setQuickSearchField(): array
    {
        return [
            'role%%' => lang('role.role')
        ];
    }
}
