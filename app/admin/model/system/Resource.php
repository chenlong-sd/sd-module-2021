<?php
/**
 *
 * Resource.php
 * User: ChenLong
 * DateTime: 2020/5/6 16:42
 */


namespace app\admin\model\system;


use app\common\BaseModel;
use sdModule\layui\Layui;

/**
 * Class Resource
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Resource extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'type' => 'tinyint',
        'tag' => 'varchar',
        'pid' => 'int',
        'path' => 'varchar',
        'md5' => 'char',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * 分类值处理
     * @param bool $tag
     * @return array
     */
    public static function getTypeSc($tag = true)
    {
        return $tag === true
            ? [
                '1' => Layui::tag()->orange('虚拟文件夹'),
                '2' => Layui::tag()->green('文件'),
            ]
            : [
                '1' => '虚拟文件夹',
                '2' => '文件',
            ];
    }

    /**
     * 分类值展示处理
     * @param $value
     * @return string
     */
    public function getTypeAttr($value)
    {
        $field = self::getTypeSc();

        return $field[$value] ?? $value;
    }

}
