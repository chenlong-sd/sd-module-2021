<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:28:18
 */

namespace app\common\validate;

use think\Validate;

/**
 * Class Administrators
 * @package app\common\validate\Administrators
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends Validate
{
    protected $rule = [
        'id|管理员' => 'require|number',
        'name|用户名' => 'require',
        'account|账号' => 'require',
        'password|密码' => 'require',
        'error_number|密码错误次数' => 'require|number',
        'error_date|错误日期' => 'require',
        'role_id|角色' => 'require|number',
        'status|状态' => 'require|number|in:0,1',
    ];

    protected $scene = [
        'add' => ['name', 'account', 'password', 'error_number', 'error_date', 'role_id', 'status'],
        'edit' => ['id', 'name', 'account', 'password', 'error_number', 'error_date', 'role_id', 'status'],
    ];
}
