<?php
/**
 * datetime: 2021/11/18 21:02
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Password, UnitI};

/**
 * Class Password
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class PasswordEntity extends Password implements UnitI
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
        $inputDiv = Dom::create();
        $input    = $this->getInputElement()
            ->addAttr('type', 'password')
            ->addAttr('placeholder', $this->placeholder)
            ->addAttr($this->getCurrentSceneInputAttr($scene));

        if ($this->label) {
            $itemDom  = $this->getItemElement();
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
            return $itemDom->addContent($inputDiv->addContent($input))
                ->addContent($this->getShortTipElement($this->shortTip));
        }

        return $inputDiv->addClass('layui-inline')->addContent($input);
    }
}
