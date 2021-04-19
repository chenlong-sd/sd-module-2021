<?php
/**
 *
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:36
 */

namespace app\admin\model\system;

use app\common\BaseModel;


/**
 * Class ApiModule
 * @package app\admin\controller\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends BaseModel
{

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


    public function api()
    {
        return $this->hasMany(Api::class)->where('status', 1)->field('id,api_module_id');
    }
}
