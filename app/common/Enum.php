<?php

namespace app\common;
use sdModule\common\MultipleCases;
use sdModule\layui\Dom;

/**
 * 枚举值实现
 * Class Enum
 * @package app\common
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/5
 */
abstract class Enum extends MultipleCases
{
    /**
     * @var string 存储的值
     */
    private $enumValue;

    /**
     * @var array 可用枚举值
     */
    private static $availableEnumValue;

    /**
     * 判断该实例是否被允许
     * @param string $tag
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected static function isAvailable(string $tag): bool
    {
        $selfConstants = static::getAvailableEnumValue();
        return in_array($tag, $selfConstants);
    }

    /**
     * 实例初始化的自定义处置
     * @param string $tag
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/5
     */
    protected function init(string $tag)
    {
        $this->enumValue      = $tag;
    }

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    abstract protected static function map(): array;

    /**
     * 获取可用的枚举值
     * @return array
     */
    private static function getAvailableEnumValue(): array
    {
        if (empty(self::$availableEnumValue[static::class])) {
            self::$availableEnumValue[static::class] = (new \ReflectionClass(static::class))->getConstants();
        }
        return self::$availableEnumValue[static::class];
    }

    /**
     * 获取对应的描述
     * @param bool $isPure 是否获取纯文字
     * @return mixed|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    final public function getDes(bool $isPure = false)
    {
        return self::getMap($isPure)[$this->enumValue] ?? $this->enumValue;
    }

    /**
     * 获取当前值
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/16
     */
    final public function getValue(): string
    {
        return $this->enumValue;
    }

    /**
     * 获取所有枚举值
     * @param bool $isEnumInstance 是否是枚举实例
     * @return array|Enum[]
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    final public static function getAll(bool $isEnumInstance = false): array
    {
        $availableEnumValue = static::getAvailableEnumValue();
        if (!$isEnumInstance) {
            return array_values($availableEnumValue);
        }

        return array_map(function ($value){
            return static::create($value);
        }, array_values($availableEnumValue));
    }

    /**
     * 获取所有的映射枚举值
     * @param bool $isPure 是否映射存文字
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    final public static function getMap(bool $isPure = false): array
    {
        $map = static::map();
        if ($isPure){
            $map = array_map(function ($v) {
                return $v instanceof Dom ? current($v->getContent()) : $v;
            }, $map);
        }

        return $map;
    }

    /**
     * 转字符串时输出枚举值
     * @return string
     */
    final public function __toString()
    {
        return $this->getValue();
    }
}
