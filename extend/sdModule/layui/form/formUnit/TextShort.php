<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class TextShort extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('please enter');
        return <<<HTML
       <div class="layui-form-item">
            <label class="layui-form-label">{$this->label}</label>
            <div class="layui-input-inline">
                <input type="text" {$attr} name="{$this->name}" maxlength="32" placeholder="{$placeholder}" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">{$this->options['tip']}</div>
        </div>
HTML;

    }
}
