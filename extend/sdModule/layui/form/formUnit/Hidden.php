<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Hidden extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        return Dom::create('input')->setIsSingleLabel(true)
            ->addAttr([
                'name' => $this->name,
                'value' => $this->preset,
                'autocomplete' => 'off',
                'class' => 'layui-input'
            ]);
    }
}
