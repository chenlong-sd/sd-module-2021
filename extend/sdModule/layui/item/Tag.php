<?php


namespace sdModule\layui\item;

use sdModule\layui\Dom;

/**
 * Class Tag
 * @method string orange($value = '') 橙
 * @method string green($value = '') 绿
 * @method string cyan($value = '') 青
 * @method string blue($value = '') 蓝
 * @method string black($value = '') 黑
 * @method string gray($value = '') 灰
 * @method string red($value = '') 灰
 * @package sdModule\layui
 */
class Tag
{

    /**
     * 边框类型
     * @param string $value
     * @return string
     */
    public function rim(string $value): string
    {
        $dom = Dom::create('span')->addClass('layui-badge-rim')->addContent($this->lang($value));
        return strtr($dom, ['"' => '\'']);
    }

    /**
     * 生成标签
     * @param string $value
     * @param string $color
     * @return string
     */
    private function generate(string $value, string $color): string
    {
        $dot = $value ? "" : "-dot";
        if (in_array($color, ['orange', 'green', 'cyan', 'blue', 'black', 'gray', 'red',])) {
            $dom = Dom::create('span')->addClass("layui-badge{$dot} layui-bg-{$color}")->addContent($this->lang($value));
        }else{
            $color = ltrim($color, 'customColor');
            $color = "#{$color}";
            $dom = Dom::create('span')->addClass("layui-badge{$dot}")
                ->addAttr('style', "background-color: {$color}")
                ->addContent($this->lang($value));
        }
        return strtr($dom, ['"' => '\'']);
    }

    /**
     * 多语言的处理
     * @param $value
     * @return mixed|string
     */
    private function lang(string $value): ?string
    {
        return $value ? lang($value) : $value;
    }

    /**
     * @param $method
     * @param $args
     * @return string
     */
    public function __call($method, $args): string
    {
        return $this->generate(current($args) ?: '', $method);
    }
}
