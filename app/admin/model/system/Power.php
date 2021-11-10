<?php
/**
 * 
 * Power.php
 * User: ChenLong
 * DateTime: 2020-04-03 15:28
 */

namespace app\admin\model\system;

use app\common\BaseModel;

/**
 * Class Power
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Power extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'route_id' => 'int',
        'role_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];
   
}