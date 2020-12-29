<?php

/**
 * 
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-12 16:52
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class Log
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends BaseValidate
{
    protected $rule = [
        'method' => 'require|number|in:1,2',
        'route_id' => 'require|number',
        'administrators_id' => 'require|number',
        'param' => 'require',
        'route' => 'require',
        'id' => 'require|number',
    ];
    
    protected $scene = [
        'add'  => ['method', 'route_id', 'administrators_id', 'param', 'route',],
        'edit' => ['method', 'route_id', 'administrators_id', 'param', 'route', 'id',],
    ];
}