<?php
/**
 *
 * BaseConfig.php
 * User: ChenLong
 * DateTime: 2021-04-02 10:49:18
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class BaseConfig
 * @package app\common\validate\system\BaseConfig
 * @author chenlong <vip_chenlong@163.com>
 */
class BaseConfig extends BaseValidate
{
    protected $rule = [
        'id|基本配置表' => 'require|number',
        'group_id|分组标识' => 'require',
        'group_name|分组名称' => 'require',
        'key_id|配置标识' => 'require',
        'key_name|配置名称' => 'require',
        'form_type|表单类型' => 'require',
        'key_value|值' => 'require',
    ];

    protected $scene = [
        'add' => ['group_id', 'group_name', 'key_id', 'key_name', 'form_type'],
        'edit' => ['id', 'group_id', 'group_name', 'key_id', 'key_name', 'form_type'],
    ];
}
