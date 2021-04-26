<?php
/**
 * Date: 2020/12/8 10:14
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class AuxTitle  extends UnitBase
{
    /**
     * @var array|string[]
     */
    private array $html = [
        'grey'  => '<blockquote class="layui-elem-quote">%s</blockquote>',
        'white' => '<blockquote class="layui-elem-quote layui-quote-nm">%s</blockquote>',
        'line'  => '<fieldset class="layui-elem-field layui-field-title"><legend>%s</legend><div class="layui-field-box"></div></fieldset>',
        'h3'    => '<h3>%s</h3>',
    ];

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        if ($this->label === '__'){
            return $this->name;
        }

        return sprintf($this->html[$this->label] ?? $this->html['grey'], $this->name);
    }
}
