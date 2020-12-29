<?php
/**
 *
 * AdministratorsRole.php
 * User: ChenLong
 * DateTime: 2020-11-24 14:05:54
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use think\Model;

class AdministratorsRole extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'administrators_id' => 'int',
        'role_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];

}
