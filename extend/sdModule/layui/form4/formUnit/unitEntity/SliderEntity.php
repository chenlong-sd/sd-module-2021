<?php
/**
 * datetime: 2021/11/25 15:05
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Slider;
use sdModule\layui\form4\formUnit\UnitI;

class SliderEntity extends Slider implements UnitI
{
    use FormUnitT;

    /**
     * @param string $scene 表单场景
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom
    {
        $itemDom  = $this->getItemElement();
        $inputDiv = Dom::create();
        $input    = $this->getInputElement()->addAttr('type', 'hidden')
            ->addAttr($this->getCurrentSceneInputAttr($scene))
            ->addAttr('value', $this->defaultValue);

        $slide = Dom::create()->setId("$this->name-slide")
            ->addClass('demo-slider')
            ->addClass($this->itemClass == 'layui-inline' ? 'layui-input-inline' : '')
            ->addAttr('style', 'position: relative;top: 17px;');
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($slide)->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($slide))->addContent($input)
            ->addContent($this->getShortTipElement($this->shortTip));

    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function getJs(): string
    {
        $this->defaultValue = $this->defaultValue ?: 0;
        $default = is_array($this->defaultValue) ? json_encode($this->defaultValue) : $this->defaultValue;
        $config  = json_encode($this->jsConfig);
        return <<<JS
    layui.slider.render(layui.jquery.extend({
        elem: '#{$this->name}-slide'
        ,value:"$default"
        ,change: function(value){
            $('input[name=$this->name]').val(value);
        }
    },$config));
JS;
    }

}