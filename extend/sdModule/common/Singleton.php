<?php
/**
 * Date: 2020/10/13 13:19
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common;

/**
 * 单例
 * Trait Singleton
 * @package sdModule\common
 */
abstract class Singleton
{
    /**
     * @var Singleton|null
     */
    private static $instance = null;

    /**
     * 获取自身
     * @return static
     */
    final public static function getInstance()
    {
        if (!self::$instance instanceof static){
            self::$instance = new static();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * 初始化的设置
     * @return mixed
     */
    abstract protected function init();

    final private function __construct(){}
    final private function __clone(){}
    final private function __sleep(){}
}
