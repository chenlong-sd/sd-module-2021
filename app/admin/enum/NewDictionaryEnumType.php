<?php
/**
* NewDictionaryEnumType.php
* Date: 2021-11-24 23:14:44
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 新字典表
* Class NewDictionaryEnumType
* @package app\common\enum\system\NewDictionaryEnumType
*/
class NewDictionaryEnumType extends Enum
{

    const NORMAL = 1;
    const STRONG = 2;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::NORMAL => Layui::tag()->green("常规"),
            self::STRONG => Layui::tag()->blue("增强"),
        ];
    }



}