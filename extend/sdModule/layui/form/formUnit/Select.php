<?php
/**
 * Date: 2020/9/26 11:33
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Select extends UnitBase
{

    /**
     * @param array $attr
     * @return mixed|string
     */
    public function getHtml(array $attr):Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();

        $options  = [
            Dom::create('option')->addContent($this->placeholder)->addAttr('value', '')
        ];
        foreach ($this->options as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    $this->getCheck($value_children) and $option->addAttr('selected', '');
                    $optgroup->addContent($option->addAttr($attr));
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                $this->getCheck($value) and $option->addAttr('selected', '');
                $options[] = $option->addAttr($attr);
            }
        }

        $select = Dom::create('select')
            ->addAttr('name', $this->name)
            ->addAttr('lay-search', '')
            ->addContent(implode($options));

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($select);
        }

        return $itemDom->addContent($inputDiv->addContent($select))->addContent($this->getShortTip());
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
