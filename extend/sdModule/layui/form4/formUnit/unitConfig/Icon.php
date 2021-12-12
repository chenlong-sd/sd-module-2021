<?php
/**
 * datetime: 2021/12/10 13:49
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;
use sdModule\layui\Dom;

/**
 * Trait Icon
 * 表单前后缀的图标设置
 * @package sdModule\layui\form4\formUnit\unitConfig
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/10
 */
trait Icon
{
    /**
     * @var string 前缀图标类名
     */
    protected $prefixIconClass;

    /**
     * @var string 后缀图标
     */
    protected $suffixIconClass;

    /**
     * @var bool 后缀图标是否是功能性图标
     */
    protected $suffixIsFunctionIcon;

    /**
     * @param string $iconClass
     * @return Icon
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function prefixIcon(string $iconClass)
    {
        $this->prefixIconClass = $this->iconClassHandle($iconClass);
        return $this;
    }

    /**
     * @param string $iconClass
     * @param bool $isFunctionIcon 值为TRUE的时候 iconClass 只能为 clear 和 eye
     * @return Icon
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function suffixIcon(string $iconClass, bool $isFunctionIcon = false)
    {
        $this->suffixIconClass      = $this->iconClassHandle($iconClass);
        $this->suffixIsFunctionIcon = $isFunctionIcon;
        return $this;
    }

    /**
     * @param string $iconClass
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    private function iconClassHandle(string $iconClass): string
    {
        if (strpos($iconClass, 'layui-icon') !== false) {
            return strtr($iconClass, ['layui-icon-' => '']);
        }
        return $iconClass;
    }

    /**
     * @return Dom|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    protected function iconElement()
    {
        if ($this->prefixIconClass) {
            return Dom::create()->addClass("layui-input-prefix")
                ->addContent(Dom::create('i')->addClass(["layui-icon", "layui-icon-" . $this->prefixIconClass]));
        }
        if ($this->suffixIconClass && !$this->suffixIsFunctionIcon) {
            return Dom::create()->addClass("layui-input-suffix")
                ->addContent(Dom::create('i')->addClass(["layui-icon", "layui-icon-" . $this->suffixIconClass]));
        }

        return '';
    }
}
