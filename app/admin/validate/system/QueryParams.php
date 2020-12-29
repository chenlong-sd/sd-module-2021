<?php
/**
 *
 * QueryParams.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:10:24
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class QueryParams
 * @package app\common\validate\QueryParams
 * @author chenlong <vip_chenlong@163.com>
 */
class QueryParams extends BaseValidate
{
    protected $rule = [
        'id|请求参数表' => 'require|number',
        'method|请求参数类型' => 'require|number|in:1,2',
        'param_type|参数类型' => 'require|number|in:1,2',
        'name|参数名' => 'require',
        'test_value|测试值' => 'require',
        'describe|描述' => 'require',
    ];

    protected $scene = [
        'add' => ['method', 'param_type', 'name', 'test_value', 'describe'],
        'edit' => ['id', 'method', 'param_type', 'name', 'test_value', 'describe'],
    ];
}
