<?php
/**
* TestEnumStatus.php
* Date: 2021-11-11 16:58:26
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
    protected static function setMap(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            self::ZHENGCHANG => Layui::tag()->rim("正常"),
            self::DONGJIE => Layui::tag()->black("冻结"),
        ];
    }



}