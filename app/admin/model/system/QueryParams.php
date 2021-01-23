<?php
/**
 *
 * QueryParams.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:10:23
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use sdModule\layui\Layui;
use think\Model;


/**
 * Class QueryParams
 * @package app\admin\controller\QueryParams
 * @author chenlong <vip_chenlong@163.com>
 */
class QueryParams  extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'api_id' => 'int',
        'method' => 'tinyint',
        'param_type' => 'tinyint',
        'name' => 'varchar',
        'test_value' => 'varchar',
        'describe' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',

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
                '1' => Layui::tag()->green('GET'),
                '2' => Layui::tag()->cyan('POST'),
                '3' => Layui::tag()->red('HEADER'),

            ]
            : [
                '1' => 'GET',
                '2' => 'POST',
                '3' => 'HEADER',

            ];
    }

    /**
     * 参数类型返回值处理
     * @param bool $tag
     * @return array
     */
    public static function getParamTypeSc($tag = true)
    {
        return $tag === true
            ? [
                '1' => Layui::tag()->green('文本'),
                '2' => Layui::tag()->cyan('文件'),

            ]
            : [
                '1' => '文本',
                '2' => '文件',

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
    public function getParamTypeAttr($value)
    {
        $field = self::getParamTypeSc();
        
        return $field[$value] ?? $value;
    }


}
