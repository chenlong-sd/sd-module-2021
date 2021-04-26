<?php
/**
 * Date: 2020/9/26 16:39
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Textarea extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('please enter');
        return <<<HTML
                <textarea name="{$this->name}" {$attr} placeholder="{$placeholder}" class="layui-textarea">{$this->preset}</textarea>
HTML;

    }
}
