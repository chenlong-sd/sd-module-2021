<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020-10-20 17:57:28
 */


namespace app\common\model;

use think\Model;
use app\common\BaseModel;

/**
 * Class Role
 * @package app\common\model\Role
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'role' => 'varchar',
        'pid' => 'int',
        'describe' => 'varchar',
        'administrators_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];


    

}

