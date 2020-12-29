<?php
/**
 *
 * Log.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:47:33
 */


namespace app\common\model;

use think\Model;
use app\common\BaseModel;
use sdModule\layui\Tag;

/**
 * Class Log
 * @package app\common\model\Log
 * @author chenlong <vip_chenlong@163.com>
 */
class Log extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'method' => 'tinyint',
        'route_id' => 'int',
        'administrators_id' => 'int',
        'param' => 'varchar',
        'route' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];


    
    /**
     * 请求方式返回值处理
     * @param bool $tag
     * @return array
     */   
    public static function getMethodSc($tag = true)
    {
        return $tag === true 
            ? [
                '1' => Tag::init()->gray('GET'),
                '2' => Tag::init()->cyan('POST'),
                
            ]
            : [
                '1' => 'GET',
                '2' => 'POST',
                
            ];
    }


}

