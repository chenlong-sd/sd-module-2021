<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Password extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('please enter');
        return <<<HTML
            <input type="password" name="{$this->name}" {$attr}  placeholder="{$placeholder}" value='{$this->preset}' autocomplete="off" class="layui-input">
HTML;

    }
}
