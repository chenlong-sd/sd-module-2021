<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:28:17
 */


namespace app\common\model;

use think\Model;
use app\common\BaseModel;
use sdModule\layui\Tag;

/**
 * Class Administrators
 * @package app\common\model\Administrators
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'account' => 'varchar',
        'password' => 'varchar',
        'error_number' => 'tinyint',
        'lately_time' => 'datetime',
        'error_date' => 'date',
        'role_id' => 'int',
        'status' => 'tinyint',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
        
    ];


    
    /**
     * 状态返回值处理
     * @param bool $tag
     * @return array
     */   
    public static function getStatusSc($tag = true)
    {
        return $tag === true 
            ? [
                '1' => Tag::init()->blue('正常'),
                '2' => Tag::init()->green('冻结'),
                
            ]
            : [
                '1' => '正常',
                '2' => '冻结',
                
            ];
    }


}

