<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2021-12-04 10:34:45
 */


namespace app\common\model;

use app\common\BaseModel;


/**
 * 测试表 模型
 * Class Test
 * @property $id
 * @property $title
 * @property $cover
 * @property $show_images
 * @property $intro
 * @property $status
 * @property $administrators_id
 * @property $pid
 * @property $content
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends BaseModel
{

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

}

