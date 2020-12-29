<?php
/**
 *
 * Log.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:47:34
 */

namespace app\common\validate;

use think\Validate;

/**
 * Class Log
 * @package app\common\validate\Log
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends Validate
{
    protected $rule = [
        'id|后台操作日志' => 'require|number',
        'method|请求方式' => 'require|number|in:0,1',
        'route_id|路由ID' => 'require|number',
        'administrators_id|操作管理员' => 'require|number',
        'param|请求参数' => 'require',
        'route|路由地址' => 'require',
    ];

    protected $scene = [
        'add' => ['method', 'route_id', 'administrators_id', 'param', 'route'],
        'edit' => ['id', 'method', 'route_id', 'administrators_id', 'param', 'route'],
    ];
}
