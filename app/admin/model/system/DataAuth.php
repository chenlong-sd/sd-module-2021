<?php
/**
 *
 * DataAuth.php
 * User: ChenLong
 * DateTime: 2020-11-26 18:12:25
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use think\Model;


class DataAuth extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'role_id' => 'int',
        'administrators_id' => 'int',
        'table_names' => 'varchar',
        'auth_id' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];

}
