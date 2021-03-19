<?php
/**
 *
 * Api.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:09:22
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use sdModule\layui\Layui;


/**
 * Class Api
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
     * 请求参数类型返回值处理
     * @param bool $tag
     * @return array
     */
    public static function getMethodSc($tag = true)
    {
        return $tag === true
            ? [
                'get' => Layui::tag()->orange('GET'),
                'post' => Layui::tag()->red('POST'),

            ]
            : [
                'get' => 'GET',
                'post' => 'POST',
            ];
    }

    /**
     * 请求参数类型返回值处理
     * @param bool $tag
     * @return array
     */
    public static function getStatusSc($tag = true)
    {
        return $tag === true
            ? [
                '1' => Layui::tag()->gray('未对接'),
                '2' => Layui::tag()->red('已对接'),
            ]
            : [
                '1' => '未对接',
                '2' => '已对接',
            ];
    }

    /**
     * 展示处理
     * @param $value
     * @return string
     */
    public function getMethodAttr($value)
    {
        $field = self::getMethodSc();

        return $field[$value] ?? $value;
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
