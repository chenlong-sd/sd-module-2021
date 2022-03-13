<?php
/**
 * datetime: 2021/11/14 10:02
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
 * 权限节点类型
 * Class RouteEnumType
 * @package app\admin\enum
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/14
 */
class RouteEnumType extends Enum
{
    const LEFT_MENU = 1;
    const TOP_MENU  = 2;
    const NODE      = 3;

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected static function map(): array
    {
        return [
            self::LEFT_MENU => Layui::tag()->green('左侧菜单'),
            self::TOP_MENU  => Layui::tag()->orange('顶部菜单'),
            self::NODE      => Layui::tag()->blue('节点'),
        ];
    }
}
