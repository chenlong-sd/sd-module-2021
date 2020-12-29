<?php
/**
 * 
 * Log.php
 * User: ChenLong
 * DateTime: 2020-05-12 16:52
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layui\item\Tag;
use think\Model;

/**
 * Class Log
 * @package app\admin\model\system
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
     * 分类值处理
     * @param bool $tag
     * @return array
     */
    public static function getMethodSc($tag = true)
    {
        return $tag === true
            ? [
                '1' => Layui::tag()->orange('GET'),
                '2' => Layui::tag()->green('POST'),
            ]
            : [
                '1' => 'GET',
                '2' => 'POST',
            ];
    }

    /**
     * 分类值展示处理
     * @param $value
     * @return string
     */   
    public function getMethodAttr($value)
    {
        $field = self::getMethodSc();
        
        return $field[$value] ?? $value;
    }
}
