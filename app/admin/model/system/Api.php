<?php
/**
 *
 * Api.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:09:22
 */

namespace app\admin\model\system;

use app\admin\enum\ApiEnumStatus;
use app\common\BaseModel;
use sdModule\layui\Layui;


/**
 * Class Api
 * @property $id
 * @property $api_module_id
 * @property $api_name
 * @property $method
 * @property $path
 * @property $token
 * @property $describe
 * @property $response
 * @property $status
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\admin\controller\Api
 * @author chenlong <vip_chenlong@163.com>
 */
class Api extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'api_module_id' => 'int',
        'api_name' => 'varchar',
        'method' => 'varchar',
        'path' => 'varchar',
        'token' => 'varchar',
        'describe' => 'varchar',
        'response' => 'text',
        'status' => 'tinyint',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];

    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function getStatusAttr($value): string
    {
        return ApiEnumStatus::create($value)->getDes();
    }


}
