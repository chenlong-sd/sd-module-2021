<?php


namespace sdModule\layuiSearch\generate;

/**
 * 表单返回定义
 * Class LayuiForm
 * @package app\common\layuiSearch\generate
 */
class LayuiForm
{
    public $html;
    public $js;

    /**
     * LayuiForm constructor.
     * @param        $html
     * @param string $js
     */
    public function __construct($html, $js = '')
    {
        $this->html = $html;
        $this->js = $js;
    }

    /**
     * @param        $html
     * @param string $js
     * @return LayuiForm
     */
    public static function generate($html, $js = '')
    {
        return new self($html, $js);
    }
}

