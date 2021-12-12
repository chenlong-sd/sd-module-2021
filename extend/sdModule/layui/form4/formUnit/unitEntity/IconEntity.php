<?php
/**
 * datetime: 2021/12/11 16:24
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Icon;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class IconEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/11
 */
class IconEntity extends Icon implements UnitI
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
            ->addAttr('value', $this->defaultValue)
            ->addAttr('placeholder', $this->placeholder)
            ->setId("$this->formUnitId-sc-4-icon");
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input))->addContent($this->getShortTipElement($this->shortTip));
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/11
     */
    public function getJs(): string
    {
        $jsConfig = $this->jsConfig ? json_encode($this->jsConfig) : '{}';

        return <<<JS
        layui.icon.render(Object.assign({
            elem: '#$this->formUnitId-sc-4-icon'
            ,style: 'color: #5FB878;'
            ,placeholder: '$this->placeholder'
            ,isSplit: true
            ,page: true
            // ,search: false
            ,click: function(obj){
                this.elem.val(obj.fontclass)
            }
        }, $jsConfig))
JS;
    }
}
