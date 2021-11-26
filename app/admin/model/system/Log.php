<?php
/**
 * 
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-12 16:52
 */

namespace app\admin\model\system;

use app\admin\enum\LogEnumMethod;
use app\common\BaseModel;
use sdModule\layui\Layui;

/**
 * Class Log
 * @property $id
 * @property $method
 * @property $route_id
 * @property $administrators_id
 * @property $param
 * @property $route
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends BaseModel
{
    protected $schema = [
        'id' => 'int',
        'method' => 'tinyint',
        'route_id' => 'int',
        'administrators_id' => 'int',
        'param' => 'json',
        'route' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * 分类值展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function getMethodAttr($value): string
    {
        return LogEnumMethod::create($value)->getDes();
    }
}
