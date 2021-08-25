<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Text extends UnitBase
{

    /**
     * @param array $attr
     * @return mixed|string
     */
    public function getHtml(array $attr):Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = $this->getInput()->addAttr('type', 'text')
            ->addAttr($attr)->addAttr('value', $this->default);
        $this->options and $input->addAttr('list', 'datalist-' . $this->name);

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
            $this->options and $inputDiv->addContent($this->optionsHandle());
        }else{
            $inputDiv->addClass('layui-inline');
            $inputDiv->addContent($input);
            $this->options and $inputDiv->addContent($this->optionsHandle());
            return $inputDiv;
        }

        return $itemDom->addContent($inputDiv->addContent($input))->addContent($this->getShortTip());
    }

    /**
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/20
     */
    private function optionsHandle(): Dom
    {
        $datalist = Dom::create('datalist')->setId('datalist-' . $this->name);
        foreach ($this->options as $value){
            $datalist->addContent(Dom::create('option')->addAttr('value', $value));
        }
        return $datalist;
    }
}
