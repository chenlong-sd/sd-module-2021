<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020/4/2 13:14
 */


namespace app\admin\validate\system;


use app\common\BaseValidate;

/**
 * Class Administrators
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends BaseValidate
{
    protected $rule = [
        'account' => 'require|length:4,16|unique:administrators,delete_time=0',
        'password' => 'require|length:6,16',
        'password_old' => 'require|length:6,16',
        'password_confirm|password' => 'requireWith:password|confirm',
        'captcha' => 'require|captcha',
        'status' => 'require|in:1,2',
        'role_id' => 'require',
        'id' => 'require|number'
    ];

    protected $message = [
        'account.require' => 'administrator.account require',
        'account.length' => 'administrator.account length',
        'account.unique' => 'administrator.account exist',
        'password.require' => 'administrator.password require',
        'password.length' => 'administrator.password length',
        'password_old.require' => 'administrator.password_old require',
        'password_old.length' => 'administrator.password_old length',
        'status' => 'administrator.administrators status',
        'role_id' => 'administrator.administrators role_id',
        'id' => 'administrator.administrators id',
        'captcha' => 'administrator.captcha error'

    ];

    protected $scene = [
        'login' => ['account', 'password', 'captcha'],
        'create' => ['account', 'password', 'password_confirm', 'name', 'role_id', 'status'],
        'update' => ['account', 'password', 'password_confirm', 'name', 'role_id', 'status', 'id'],
        'password' => ['password_old', 'password', 'password_confirm']
    ];

    public function sceneUpdate()
    {
        return $this->only(['account', 'password', 'password_confirm.confirm', 'name', 'role_id', 'status', 'id'])
            ->remove('password', 'require');
    }

    public function sceneLogin()
    {
        return $this->only($this->scene['login'])->remove('account', 'unique');
    }
}
