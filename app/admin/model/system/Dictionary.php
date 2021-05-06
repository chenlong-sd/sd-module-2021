<?php
/**
 *
 * Dictionary.php
 * User: ChenLong
 * DateTime: 2021-05-06 21:52:58
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use sdModule\layui\Layui;

/**
 * Class Dictionary
 * @property $id
 * @property $sign
 * @property $pid
 * @property $name
 * @property $dictionary_value
 * @property $dictionary_name
 * @property $status
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\system\Dictionary
 * @author chenlong <vip_chenlong@163.com>
 */
class Dictionary extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'sign' => 'varchar',
        'pid' => 'int',
        'name' => 'varchar',
        'dictionary_value' => 'varchar',
        'dictionary_name' => 'varchar',
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
                '1' => Layui::tag()->blue('正常'),
                '2' => Layui::tag()->cyan('停用'),

            ]
            : [
                '1' => '正常',
                '2' => '停用',

            ];
    }

    
    /**
     * 展示处理
     * @param $value
     * @return string
     */   
    public function getStatusAttr($value)
    {
        $field = self::getStatusSc();
        
        return $field[$value] ?? $value;
    }


}
