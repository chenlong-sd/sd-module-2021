<?php
/**
 * datetime: 2021/11/19 22:03
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Time, UnitI};

class TimeEntity extends Time implements UnitI
{
    use FormUnitT;

    /**
     * @return string
     */
    public function getDateType(): string
    {
        return $this->dateType;
    }

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
            ->addAttr('value', $this->defaultValue)
            ->addAttr('placeholder', $this->placeholder)
            ->setId("$this->formUnitId-sc-4-i");
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input))->addContent($this->getShortTipElement($this->shortTip));
    }


    public function getJs(): string
    {
        $jsConfig = $this->jsConfig ? json_encode($this->jsConfig, JSON_UNESCAPED_UNICODE) : '{}';
        return <<<JS
        layui.laydate.render(Object.assign({
            elem: '#$this->formUnitId-sc-4-i'
            ,type: '$this->dateType'
        },$jsConfig));
JS;
    }
}
