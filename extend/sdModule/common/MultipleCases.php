<?php
/**
 * datetime: 2021/11/5 9:33
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\common;

/**
 * 多例设置
 * Class MultipleCases
 * @package sdModule\common
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/5
 */
abstract class MultipleCases
{
    /**
     * 存储自身实例的数组
     * @var static[]
     */
    private static $instances = [];

    /**
     * 根据标记创建对应的实例
     * @param string $tag 实例标记
     * @return static
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    final public static function create(string $tag): MultipleCases
    {
        if (!static::isAvailable($tag)) {
            throw new \Exception("Unable to create the instance, Supplied tag $tag");
        }

        $classTag = static::class . '@' . $tag;
        if (!isset(self::$instances[$classTag])) {
            self::$instances[$classTag] = new static();
            self::$instances[$classTag]->init($tag);
        }

        return self::$instances[$classTag];
    }

    /**
     * 判断该实例是否被允许
     * @param string $tag
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    abstract protected static function isAvailable(string $tag): bool;

    /**
     * 实例初始化的自定义处置
     * @param string $tag
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    abstract protected function init(string $tag);
    
    final private function __construct(){}
    final private function __clone(){}
    final private function __sleep(){}
}
