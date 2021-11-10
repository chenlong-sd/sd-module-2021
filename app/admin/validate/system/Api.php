<?php
/**
 *
 * Api.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:09:23
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class Api
 * @package app\common\validate\Api
 * @author chenlong <vip_chenlong@163.com>
 */
class Api extends BaseValidate
{
    protected $rule = [
        'id|api接口表' => 'require|number',
        'api_name|接口名' => 'require',
        'path|路径' => 'require',
        'describe|描述' => 'require',
        'response|响应示例' => 'require',
    ];

    protected $scene = [
        'create' => ['api_name', 'path'],
        'update' => ['id', 'api_name', 'path', 'response'],
    ];
}
