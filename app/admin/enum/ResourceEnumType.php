<?php
/**
 * datetime: 2021/11/10 12:37
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
 * 资源数据类型
 * Class ResourceEnumType
 * @package app\admin\enum
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/10
 */
class ResourceEnumType extends Enum
{
    const DIR  = 1;
    const FILE = 2;

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected static function map(): array
    {
        return [
            self::DIR  => Layui::tag()->orange('虚拟文件夹'),
            self::FILE => Layui::tag()->green('文件'),
        ];
    }
}
