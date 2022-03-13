<?php
/**
* QuickOperationEnumIsShow.php
* Date: 2021-12-03 21:05:04
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 快捷操作
* Class QuickOperationEnumIsShow
* @package app\common\enum\system\QuickOperationEnumIsShow
*/
class QuickOperationEnumIsShow extends Enum
{

    const YES = 1;
    const NOT = 2;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::YES => Layui::tag()->cyan("是"),
            self::NOT => Layui::tag()->blue("否"),
        ];
    }



}