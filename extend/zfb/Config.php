<?php


namespace zfb;

/**
 * 常规参数配置
 * Trait Config
 * @package app\common\wechat
 */
class Config
{
    /**
     * @var array
     */
    private $config;
    private static $instance;

    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return Config
     */
    private static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->config = include_once __DIR__ . '/config_.php';
        }

        return self::$instance;
    }

    /**
     * 获取配置
     * @param $key
     * @return mixed|null
     */
    public static function get($key)
    {
        $value = self::getInstance()->config;
        foreach (explode('.', $key) as $k) {
            if (!is_array($value)) break;
            $value = $value[$k] ?? null;
        }
        return $value;
    }

}

