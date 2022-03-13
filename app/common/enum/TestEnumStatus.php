<?php
/**
* TestEnumStatus.php
* Date: 2021-12-04 10:34:45
* User: chenlong <vip_chenlong@163.com>
*/

namespace app\common\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
* 测试表
* Class TestEnumStatus
* @package app\common\enum\TestEnumStatus
*/
class TestEnumStatus extends Enum
{

    const ZHENGCHANG = 1;
    const DONGJIE = 2;

    
    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->red("正常"),
            self::DONGJIE => Layui::tag()->cyan("冻结"),
        ];
    }



}