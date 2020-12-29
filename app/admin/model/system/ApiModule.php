<?php
/**
 *
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:36
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use think\Model;


/**
 * Class ApiModule
 * @package app\admin\controller\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends Model
{

    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'item_name' => 'varchar',
        'url_prefix' => 'varchar',
        'token' => 'varchar',
        'describe' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];


}
