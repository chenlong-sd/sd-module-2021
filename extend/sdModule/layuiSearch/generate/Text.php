<?php


namespace sdModule\layuiSearch\generate;

/**
 * 普通文本类型
 * Class Text
 * @package app\common\layuiSearch
 */
class Text extends FormVar implements FormDefine
{

    /**
     * @return LayuiForm
     */
    public function html():LayuiForm
    {
        $html = <<<HTM
                <div class="layui-inline">
                    {$this->getLabel()}
                    <div class="layui-input-inline">
                        <input type="text" name="{$this->name}"  placeholder="{$this->placeholder}" autocomplete="off" class="layui-input">
                    </div>
                </div>
HTM;
        return LayuiForm::generate($html);
    }
}

