<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class Text extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('please enter');
        return <<<HTML
                <input type="text" {$attr} name="{$this->name}" placeholder="{$placeholder}" value='{$this->preset}' autocomplete="off" class="layui-input">
HTML;

    }
}
