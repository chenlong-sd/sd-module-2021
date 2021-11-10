<?php

/**
 * 
 * Role.php
 * User: ChenLong
 * DateTime: 2020-04-12 22:17
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class Role
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends BaseValidate
{
    protected $rule = [
        'role' => 'require',
        'pid' => 'require|number',
        'describe' => 'require',
        'id' => 'require|number',
    ];

    protected $message = [
        'role' => 'role.role_name',
        'pid' => 'role.pid',
        'describe' => 'role.describe',
        'id' => 'role.id',
    ];
    
    protected $scene = [
        'create'  => ['role', 'describe',],
        'update'  => ['role', 'describe', 'id',],
    ];
}