<?php
/**
 * Date: 2020/9/26 11:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Time extends UnitBase
{
    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = $this->getInput()->addAttr('type', 'text')->addAttr('value', $this->default)
            ->addAttr($attr)->setId($this->nameReplace() . "-sc");
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass('layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input));
    }

    /**
     * @return mixed|string
     */
    public function getJs(): string
    {
        $range = is_bool($this->options['range'])
            ? ($this->options['range'] ? 'true' : 'false')
            : "'{$this->options['range']}'";
        $type = $this->options['type'] ?? 'date';

        return <<<JS
        layui.laydate.render({
            elem: '#{$this->nameReplace()}-sc'
            ,type: '{$type}'
            ,range: {$range}
        });
JS;
    }
}
