<?php
/**
 * datetime: 2021/11/25 11:26
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Color;
use sdModule\layui\form4\formUnit\UnitI;

class ColorEntity extends Color implements UnitI
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
        $input    = $this->getInputElement()->addAttr('type', 'text')
            ->setId("$this->name-color-input")
            ->addAttr($this->getCurrentSceneInputAttr($scene))
            ->addAttr('value', $this->defaultValue);

        $color = Dom::create()->addClass('layui-inline')
            ->addAttr('style', 'left:-11px;height:38px')
            ->addContent(Dom::create()->setId("$this->name-color"));

        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass('layui-input-inline');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input))
            ->addContent($color)
            ->addContent($this->getShortTipElement($this->shortTip));
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function getJs(): string
    {
        $predefine = $this->jsConfig['predefine'] ?? 0;
        $colors    = json_encode($this->jsConfig['colors'] ?? []);
        $alpha     = $this->jsConfig['alpha'] ?? 1;
        $format    = $this->jsConfig['format'] ?? 'hex';
        return <<<JS

    layui.colorpicker.render({
        elem: '#{$this->name}-color'
        ,color: '$this->defaultValue'
        ,predefine: $predefine // 开启预定义颜色
        ,alpha: $alpha //开启透明度
        ,format: '$format' //开启透明度
        ,colors: $colors //预定义颜色
        ,done: function(color){
            $('#$this->name-color-input').val(color);
        }
    });

JS;

    }

}