<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-10-20 17:57:29
 */

namespace app\common\validate;

use think\Validate;

/**
 * Class Role
 * @package app\common\validate\Role
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends Validate
{
    protected $rule = [
        'id|角色' => 'require|number',
        'role|角色名' => 'require',
        'pid|父级角色' => 'require|number',
        'describe|角色描述' => 'require',
        'administrators_id|创建角色的管理员' => 'require|number',
    ];

    protected $scene = [
        'add' => ['role', 'pid', 'describe', 'administrators_id'],
        'edit' => ['id', 'role', 'pid', 'describe', 'administrators_id'],
    ];
}
