<?php
/**
 * datetime: 2021/11/9 22:45
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
 * Class ApiStatus
 * @package app\admin\enum
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/9
 */
class ApiEnumStatus extends Enum
{
    const WAIT    = 1;
    const SUCCESS = 2;

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected static function map(): array
    {
        return [
            self::WAIT    => Layui::tag()->red('未对接'),
            self::SUCCESS => Layui::tag()->green('已对接')
        ];
    }
}
