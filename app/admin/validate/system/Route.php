<?php

/**
 * 
 * Route.php
 * User: ChenLong
 * DateTime: 2020-04-12 23:07
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class Route
 * @package app\common\validate\System
 * @author chenlong <vip_chenlong@163.com>
 */
class Route extends BaseValidate
{
    protected $rule = [
        'title' => 'require',
        'route' => 'require',
        'pid' => 'require|number',
        'type' => 'require|number|in:1,2,3',
        'weigh' => 'require|number',
        'icon' => 'require',
        'id' => 'require|number',
    ];

    protected $message = [
        'title' => 'route.title',
        'route' => 'route.route',
        'pid' => 'route.pid',
        'type' => 'route.type',
        'weigh' => 'route.weigh',
        'icon' => 'route.icon',
        'id' => 'route.id',
    ];
    
    protected $scene = [
        'create' => ['title', 'type', 'weigh', ],
        'update' => ['title', 'type', 'weigh', 'id',],
    ];
}
