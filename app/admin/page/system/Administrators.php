<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\model\system\Administrators as AdministratorsM;
use app\admin\model\system\DataAuth;
use app\admin\model\system\Role;
use app\common\BasePage;
use sdModule\layui\form\Form as DefaultForm;
use sdModule\layui\form\FormUnit;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\tablePage\ListsPage;
use sdModule\layui\tablePage\module\TableAux;
use think\facade\Db;

class Administrators extends BasePage
{

    /**
     * 获取创建列表table的数据
     * @return ListsPage
     * @throws \app\common\SdException
     */
    public function getTablePageData(): ListsPage
    {
        $field_data = [
            TableAux::column()->checkbox(),
            TableAux::column('id', '唯一ID'),
            TableAux::column('name', '用户名'),
            TableAux::column('account', '账号'),
            TableAux::column('error_number', '密码错误次数'),
            TableAux::column('lately_time', '最近登录'),
            TableAux::column('error_date', '错误日期'),
            TableAux::column('role', '角色'),
            TableAux::column('status', '状态'),
            TableAux::column('create_time', '创建时间'),
        ];

        $table = ListsPage::create($field_data);
        $table->addBarEvent('directly_under')
            ->setNormalBtn('直属','username', 'sm')
            ->setJs(TableAux::searchWhere(['mode' => 'directly_under']));

        $table->addBarEvent('all')
            ->setNormalBtn('全部','username', 'sm')
            ->setJs(TableAux::searchWhere(['mode' => 'all']));
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
            FormUnit::text('name', lang('administrator.name')),
            FormUnit::text('account', lang('administrator.account')),
            FormUnit::password('password', lang('administrator.password')),
            FormUnit::password('password_confirm', lang('administrator.password confirm')),
            FormUnit::selects('role_id', lang('administrator.role'))->options(Role::where(['administrators_id' => AdministratorsM::getSession('id')])->column('role', 'id')),
            FormUnit::radio('status', lang('administrator.status'))->options(AdministratorsM::getStatusSc())->defaultValue(AdministratorsM::STATUS_NORMAL),
        ];
        if (env('APP.DATA_AUTH', false)){
            $default = DataAuth::where(['delete_time' => 0])->where(['administrators_id' => request()->get('id')])
                ->column('auth_id', 'table_names');

            foreach (config('admin.data_auth') as $data){
                $form_data[] = FormUnit::selects("data_auth_table_{$data['table']}", $data['remark'])->options(self::dataAuth($data['table']))
                    ->defaultValue(empty($default[$data['table']]) ? [] : explode(',', $default[$data['table']]));
            }
        }

        if ($scene === 'edit' && $default_data){
            $default_data = data_only($default_data, ['id', 'account', 'name', 'status', 'role_id']);
        }

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
                    AdministratorsM::STATUS_NORMAL => lang('normal'),
                    AdministratorsM::STATUS_FROZEN => lang('disable'),
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
