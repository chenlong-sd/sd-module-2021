<?php
/**
 * datetime: 2021/11/10 12:01
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;
use sdModule\layui\Layui;

/**
 * 日志请求类型
 * Class LogEnumMethod
 * @package app\admin\enum
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/10
 */
class LogEnumMethod extends Enum
{
    const GET  = '1';
    const POST = '2';

    /**
     * @inheritDoc
     */
    protected static function map(): array
    {
        return [
            self::GET  => Layui::tag()->orange('GET'),
            self::POST => Layui::tag()->green('POST'),
        ];
    }
}