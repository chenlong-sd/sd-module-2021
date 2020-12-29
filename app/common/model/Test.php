<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2020-11-25 17:38:39
 */


namespace app\common\model;

use think\Model;
use app\common\BaseModel;
use sdModule\layui\Layui;

/**
 * Class Test
 * @package app\common\model\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Model
{
    use BaseModel;

    protected $schema = [
        'id' => 'int',
        'title' => 'varchar',
        'cover' => 'varchar',
        'show_images' => 'varchar',
        'intro' => 'varchar',
        'status' => 'tinyint',
        'administrators_id' => 'int',
        'pid' => 'int',
        'content' => 'text',
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
                '1' => Layui::tag()->blue('正常'),
                '2' => Layui::tag()->red('冻结'),
                
            ]
            : [
                '1' => '正常',
                '2' => '冻结',
                
            ];
    }


}

