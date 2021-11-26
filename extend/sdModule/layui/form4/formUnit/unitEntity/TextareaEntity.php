<?php
/**
 * datetime: 2021/11/24 23:38
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Textarea;
use sdModule\layui\form4\formUnit\UnitI;

class TextareaEntity extends Textarea implements UnitI
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
        $input    = Dom::create('textarea')->addAttr([
            'name' => $this->name,
            'placeholder' => $this->placeholder ?: '请输入...',
            'class' => 'layui-textarea'
        ])  ->addAttr($this->getCurrentSceneInputAttr($scene))
            ->addContent($this->defaultValue);
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass('layui-input-block');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input));
    }


}
