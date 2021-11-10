<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\enum\AdministratorsEnumStatus;
use app\admin\model\system\Administrators as AdministratorsM;
use app\admin\model\system\DataAuth;
use app\admin\model\system\Role;
use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\form\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;
use think\facade\Db;

class Administrators extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return array
     * @throws \ReflectionException
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function listPageData(): array
    {
        $field_data = [
            Column::checkbox(),
            Column::normal('唯一ID', 'id'),
            Column::normal('用户名', 'name'),
            Column::normal('账号', 'account'),
            Column::normal('密码错误次数', 'error_number'),
            Column::normal('最近登录', 'lately_time'),
            Column::normal('错误日期', 'error_date'),
            Column::normal('角色', 'role'),
            Column::normal('状态', 'status_sc')->showSwitch('status', AdministratorsEnumStatus::getAllDescriptionMap()),
            Column::normal('创建时间', 'create_time'),
        ];

        $table = PageData::create($field_data);
        $table->addBarEvent('directly_under')
            ->setNormalBtn('直属','username', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'directly_under']));

        $table->addBarEvent('all')
            ->setNormalBtn('全部','username', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'all']));

        $search = $this->searchFormData();

        return array_merge(parent::listPageData(), compact('table', 'search'));
    }

    /**
     * @inheritDoc
     * @param string $scene
     * @param array $default_data
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function formPageData(string $scene, array $default_data = []): DefaultForm
    {
        $form_data = [
            FormUnit::hidden('id'),
            FormUnit::text('name', lang('administrator.name')),
            FormUnit::text('account', lang('administrator.account')),
            FormUnit::password('password', lang('administrator.password')),
            FormUnit::password('password_confirm', lang('administrator.password confirm')),
            FormUnit::selects('role_id', lang('administrator.role'))->options(Role::where(['administrators_id' => admin_session('id')])->column('role', 'id')),
            FormUnit::radio('status', lang('administrator.status'))->options(AdministratorsM::getStatusSc())->defaultValue(AdministratorsEnumStatus::AVAILABLE),
        ];
        if (env('APP.DATA_AUTH', false)){
            $default = DataAuth::where(['delete_time' => 0])->where(['administrators_id' => request()->get('id')])
                ->column('auth_id', 'table_names');

            foreach (config('admin.data_auth') as $data){
                $form_data[] = FormUnit::selects("data_auth_table_{$data['table']}", $data['remark'])->options(self::dataAuth($data['table']))
                    ->defaultValue(empty($default[$data['table']]) ? [] : explode(',', $default[$data['table']]));
            }
        }

        unset($default_data['password']);

        $form = DefaultForm::create($form_data)->setDefaultData($default_data);
        $form->setShortForm($this->shortInput());

        return $form->complete();
    }

    /**
     * 数据权限的数据获取
     * @param string $table
     * @return array|\think\Collection
     */
    public static function dataAuth(string $table)
    {
        $data_auth = array_column(config('admin.data_auth'), null, 'table');
        if (empty($data_auth[$table])){
            return [];
        }

        try {
            return Db::name($table)->column("{$data_auth[$table]['field']}", "id");
        } catch (\Exception $exception) {
           return [];
        }
    }

    /**
     * @return DefaultForm
     * @throws \ReflectionException
     * @throws \app\common\SdException
     */
    public function searchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::build(
                FormUnit::text('account%%')->placeholder(lang('administrator.account')),
                FormUnit::text('name%%')->placeholder(lang('administrator.administrator')),
                FormUnit::text('r.role%%')->placeholder(lang('administrator.role')),
                FormUnit::select('i.status%%')->options([
                    AdministratorsEnumStatus::AVAILABLE => lang('normal'),
                    AdministratorsEnumStatus::DISABLE   => lang('disable'),
                ])->placeholder(lang('administrator.status')),
                FormUnit::custom()->customHtml(DefaultForm::searchSubmit())
            ),

        ];

        return DefaultForm::create($form_data)->setSubmitHtml()->complete();
    }

    /**
     * @return array
     */
    public function shortInput(): array
    {
        return [
            'password' => lang('administrator.6-16 digit password'),
            'password_confirm' => lang('administrator.6-16 digit password'),
            'account' => lang('administrator.login account'),
        ];
    }
}
