<?php
/**
 * Date: 2020/9/26 11:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class Time extends UnitBase
{
    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $placeholder = $this->placeholder ?: $this->lang('please enter');
        return <<<HTML
                <input type="text" id="{$this->name}-sc" {$attr} name="{$this->name}" placeholder="{$placeholder}" value='' autocomplete="off" class="layui-input">
HTML;
    }

    /**
     * @return mixed|string
     */
    public function getJs()
    {
        $range = is_bool($this->select_data['range'])
            ? ($this->select_data['range'] ? 'true' : 'false')
            : "'{$this->select_data['range']}'";
        $type = $this->select_data['type'] ?? 'date';

        return <<<JS
        layui.laydate.render({
            elem: '#{$this->name}-sc'
            ,type: '{$type}'
            ,range: {$range}
        });
JS;
    }
}
