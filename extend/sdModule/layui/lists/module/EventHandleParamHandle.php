<?php
/**
 * datetime: 2021/9/21 10:14
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\module;

/**
 * 页面事件js的参数处理
 * Trait EventHandleParamHandle
 * @package sdModule\layui\lists\module
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/21
 */
trait EventHandleParamHandle
{

    /**
     * 路径参数处理
     * @param array|string $url, 是数组时，后面的参数从该行获取并拼接到链接中
     * @example ['/test', 'id', 'title' => 'title_alias']
     * @return string
     */
    protected static function url($url): string
    {
        if (!is_array($url)) {
            return "'$url'";
        }

        $url_str = array_shift($url);
        $url_str = strpos($url_str, '?') !== false ? "$url_str&" : "$url_str?";

        foreach ($url as $value) {
            if (!is_array($value)) {
                $url_str .= "$value=\${obj.$value}&";
                continue;
            }
            $field = array_key_first($value);
            $url_str .= "$value[$field]=\${obj.$field}&";
        }

        return sprintf('`%s`', rtrim($url_str, '&'));
    }


    /**
     * 变量替换
     * @param string $string
     * @return string|string[]|null
     */
    protected static function paramReplace(string $string)
    {
        return preg_replace_callback('/\{(\w+)\}/', function ($v) {
            return "'+ obj.$v[1] +'";
        }, $string);
    }
}
