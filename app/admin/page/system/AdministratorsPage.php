<?php
/**
 * Date: 2020/11/25 12:48
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\admin\page\system;


use app\admin\enum\AdministratorsEnumStatus;
use app\admin\AdminLoginSession;
use app\admin\model\system\Role;
use app\common\BasePage;
use sdModule\layui\form4\FormProxy as DefaultForm;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\lists\module\Column;
use sdModule\layui\lists\module\EventHandle;
use sdModule\layui\lists\PageData;

class AdministratorsPage extends BasePage
{
    /**
     * 获取创建列表table的数据
     * @return PageData
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function listPageData(): PageData
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
            Column::normal('状态', 'status_sc')->showSwitch('status', AdministratorsEnumStatus::getMap()),
            Column::normal('创建时间', 'create_time'),
        ];

        $table = PageData::create($field_data);
        $table->addBarEvent('directly_under')
            ->setNormalBtn('直属','username', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'directly_under']));

        $table->addBarEvent('all')
            ->setNormalBtn('全部','username', 'sm')
            ->setJs(EventHandle::addSearch(['mode' => 'all']));

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
            FormUnit::text('name', lang('administrator.name')),
            FormUnit::text('account', lang('administrator.account'))->shortTip(lang('administrator.login account')),
            FormUnit::password('password', lang('administrator.password'))->shortTip(lang('administrator.6-16 digit password')),
            FormUnit::password('password_confirm', lang('administrator.password confirm'))->shortTip(lang('administrator.6-16 digit password')),
            FormUnit::selects('role_id', lang('administrator.role'))->options(Role::where(['administrators_id' => AdminLoginSession::getId()])->column('role', 'id')),
            FormUnit::radio('status', lang('administrator.status'))->options(AdministratorsEnumStatus::getMap(true))->defaultValue(AdministratorsEnumStatus::AVAILABLE),
        ];

        unset($default_data['password']);

        return DefaultForm::create($form_data, $default_data);
    }

    /**
     * 修改密码
     * @return DefaultForm
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/11
     */
    public function updatePassword(): DefaultForm
    {
        $form_data = [
            FormUnit::password('password_old', '原密码')->prefixIcon('password')->suffixIcon('eye', true),
            FormUnit::password('password', '新密码')->prefixIcon('password')->suffixIcon('eye', true),
            FormUnit::password('password_confirm', '确认密码')->prefixIcon('password')->suffixIcon('eye', true),
        ];
        return DefaultForm::create($form_data);
    }

    /**
     * @return DefaultForm
     */
    public function listSearchFormData():DefaultForm
    {
        $form_data = [
            FormUnit::group(
                FormUnit::text('account%%')->placeholder(lang('administrator.account')),
                FormUnit::text('name%%')->placeholder(lang('administrator.administrator')),
                FormUnit::text('r.role%%')->placeholder(lang('administrator.role')),
                FormUnit::select('i.status%%')->options([
                    AdministratorsEnumStatus::AVAILABLE => lang('normal'),
                    AdministratorsEnumStatus::DISABLE   => lang('disable'),
                ])->placeholder(lang('administrator.status')),
            ),

        ];

        return DefaultForm::create($form_data)->setSearchSubmitElement();
    }

}
