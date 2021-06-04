<?php
/**
 * Date: 2021/6/4 12:04
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Slider extends UnitBase
{

    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = $this->getInput()->addAttr('type', 'hidden')
            ->addAttr($attr)->addAttr('value', $this->default);

        $slide = Dom::create()->setId("{$this->name}-slide")
            ->addClass('demo-slider')
            ->addClass($this->itemClass == 'layui-inline' ? 'layui-input-inline' : '')
            ->addAttr('style', 'position: relative;top: 17px;');
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($slide)->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($slide))->addContent($input)->addContent($this->getShortTip());
    }

    public function getJs(): string
    {
        $this->default = $this->default ?: 0;
        $default = is_array($this->default) ? json_encode($this->default) : $this->default;
        $config = json_encode($this->config);
        return <<<JS
    layui.slider.render(layui.jquery.extend({
        elem: '#{$this->name}-slide'
        ,value:{$default}
        ,change: function(value){
            $('input[name={$this->name}]').val(value);
        }
    },{$config}));
JS;
    }
}
