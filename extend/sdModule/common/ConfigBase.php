<?php
/**
 * Date: 2020/12/21 11:29
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common;


abstract class ConfigBase extends Singleton
{
    /**
     * @var array 配置内容
     */
    protected $config = [];

    /**
     * 获取配置
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public static function get(string $key, $default = null)
    {
        $value = self::getInstance()->config;
        foreach (explode('.', $key) as $k) {
            if (!is_array($value)) break;
            $value = $value[$k] ?? $default;
        }
        return $value;
    }

    /**
     * 动态设置配置
     * @param string|array $key
     * @param null $value
     */
    public static function set($key, $value = null)
    {
        $instance = self::getInstance();
        if (is_array($key)) {
            $instance->config = array_merge($instance->config, $key);
        }else{
            $instance->config[$key] = $value;
        }
    }
}
