<?php
/**
 *
 * Dictionary.php
 * User: ChenLong
 * DateTime: 2021-05-06 21:52:58
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class Dictionary
 * @package app\common\validate\system\Dictionary
 * @author chenlong <vip_chenlong@163.com>
 */
class Dictionary extends BaseValidate
{
    protected $rule = [
        'id|字典表' => 'require|number',
        'sign|标识' => 'require',
        'pid|标识ID' => 'require|number',
        'name|标识名称' => 'require',
        'dictionary_value|字典值' => 'require|unique:dictionary,dictionary_value^pid',
        'dictionary_name|字典名字' => 'require',
        'status|状态' => 'require|number|in:1,2',
    ];

    protected $scene = [
        'create' => ['sign',  'name', 'status'],
        'update' => ['id', 'sign',  'name', 'status'],
        'value_add' => ['pid', 'dictionary_value', 'status'],
        'value_edit' => ['id', 'dictionary_value', 'status'],
    ];
}
