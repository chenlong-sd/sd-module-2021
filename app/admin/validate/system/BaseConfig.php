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
        'group|分组信息' => 'require',
        'key|参数信息' => 'require',
        'key_name|配置名称' => 'require',
        'form_type|表单类型' => 'require',
        'key_value|值' => 'require',
        'sort|排序位置' => 'require',
    ];

    protected $scene = [
        'add' => ['group', 'key',  'form_type', 'sort'],
        'edit' => ['id', 'group', 'key',  'form_type', 'sort'],
    ];
}
