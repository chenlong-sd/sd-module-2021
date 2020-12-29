<?php
/**
 * Date: 2020/11/3 13:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\traits;

/**
 * Trait Lang
 * @package app\common\traits
 */
trait Lang
{
    /**
     * 多语言处理
     * @param string $name
     * @param array $vars
     * @param string $lang
     * @return mixed
     */
    protected function lang(string $name, array $vars = [], string $lang = '')
    {
        $prefix_arr = explode("\\", static::class);
        return lang(implode('.', [parse_name(end($prefix_arr)), $name]), $vars, $lang);
    }
}
