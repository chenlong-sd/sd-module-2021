<?php
/**
 * datetime: 2021/11/10 9:36
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
 * 可用于禁用
 * Class AvailableAndDisable
 * @package app\admin\enum
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/10
 */
class AvailableAndDisable extends Enum
{
    const AVAILABLE = 1; // 可用
    const DISABLE   = 2; // 禁用

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected static function map(): array
    {
        return [
            self::AVAILABLE => Layui::tag()->green('normal'),
            self::DISABLE   => Layui::tag()->red('disable')
        ];
    }
}
