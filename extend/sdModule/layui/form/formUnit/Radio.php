<?php
/**
 * Date: 2020/9/26 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Radio extends UnitBase
{


    /**
     * @param array $attr
     * @return mixed|string
     */
    public function getHtml(array $attr):Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();

        foreach ($this->options as $value => $label) {
            $customAttr = [
                'type'      => 'radio',
                'value'     => $value,
                'title'     => $label
            ];
            $checked   = $this->getCheck($value) and $customAttr['checked'] = '';
            $inputDiv->addContent($this->getInput()->addAttr($customAttr)->addAttr($attr));
        }

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            return $inputDiv->addClass('layui-inline');
        }

        return $itemDom->addContent($inputDiv);
    }

    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function getCheck($value): bool
    {
        return $value == $this->default;
    }

}
