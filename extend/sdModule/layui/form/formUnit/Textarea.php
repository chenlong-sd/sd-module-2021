<?php
/**
 * Date: 2020/9/26 16:39
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Textarea extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = Dom::create('textarea')->addAttr([
            'name' => $this->name,
            'placeholder' => $this->placeholder ?: $this->lang('please enter'),
            'class' => 'layui-textarea'
        ])
            ->addAttr($attr)->addContent($this->default);
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass('layui-input-block');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input));
    }
}
