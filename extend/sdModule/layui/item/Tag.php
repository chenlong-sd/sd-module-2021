<?php


namespace sdModule\layui\item;

use sdModule\layui\Dom;

/**
 * Class Tag
 * @method Dom orange($value = '') 橙
 * @method Dom green($value = '') 绿
 * @method Dom cyan($value = '') 青
 * @method Dom blue($value = '') 蓝
 * @method Dom black($value = '') 黑
 * @method Dom gray($value = '') 灰
 * @method Dom red($value = '') 灰
 * @package sdModule\layui
 */
class Tag
{

    /**
     * 边框类型
     * @param string $value
     * @return Dom
     */
    public function rim(string $value): Dom
    {
        return Dom::create('span')->addClass('layui-badge-rim')->addContent($this->lang($value));
    }

    /**
     * 自定义颜色的标签
     * @param string $color
     * @param string $value
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    public function customColor(string $color, string $value = ''): Dom
    {
        $dot = $value ? "" : "-dot";
        return Dom::create('span')->addClass("layui-badge{$dot}")
            ->addAttr('style', "background-color: {$color}")
            ->addContent($this->lang($value));
    }

    /**
     * 生成标签
     * @param string $value
     * @param string $color
     * @return Dom
     */
    private function generate(string $value, string $color): Dom
    {
        $dot = $value ? "" : "-dot";
        return Dom::create('span')->addClass("layui-badge{$dot} layui-bg-{$color}")->addContent($this->lang($value));
    }

    /**
     * 多语言的处理
     * @param string $value
     * @return string|null
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/6
     */
    private function lang(string $value): ?string
    {
        return $value ? lang($value) : $value;
    }

    /**
     * @param $method
     * @param $args
     * @return Dom
     */
    public function __call($method, $args): Dom
    {
        return $this->generate(current($args) ?: '', $method);
    }
}
