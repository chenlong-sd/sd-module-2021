<?php
/**
 * datetime: 2021/11/24 23:52
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Radio;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class RadioEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/24
 */
class RadioEntity extends Radio implements UnitI
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

        foreach ($this->options as $value => $label) {
            $customAttr = [
                'type'          => 'radio',
                'lay-filter'    => "filter-$this->name",
                'value'         => $value,
                'title'         => $label
            ];
            $this->isChecked($value) and $customAttr['checked'] = '';
            $inputDiv->addContent($this->getInputElement()->addAttr($customAttr)
                ->addAttr($this->getCurrentSceneInputAttr($scene)));
        }

        if ($this->label) {
            $inputDiv->addClass($this->isInline ? 'layui-inline' : 'layui-input-block');
            return $itemDom->addContent($this->getLabelElement($this->label))->addContent($inputDiv);
        }

        return $inputDiv->addClass('layui-inline');
    }

    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function isChecked($value): bool
    {
        if ($this->defaultValue === '' || $this->defaultValue === null) {
            return false;
        }
        return $value == $this->defaultValue;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/24
     */
    public function getJs() :string
    {
        if (empty($this->showWhere['value'])) {
            return '';
        }

        $whereObj = json_encode($this->showWhere['value'], JSON_UNESCAPED_UNICODE);

        return   <<<JS
        window['sc-4-radio-$this->name'] = $whereObj;
        layui.form.on('radio(filter-$this->name)', function(data){
           let event_value = data.value;
           window['sc4ControlShow'](window['sc-4-radio-$this->name'], event_value);
        });
        $(()=>window['sc4ControlShow'](window['sc-4-radio-$this->name'], $this->defaultValue));
JS;
    }
}

