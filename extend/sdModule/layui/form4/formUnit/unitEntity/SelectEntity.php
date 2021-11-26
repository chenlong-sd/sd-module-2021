<?php
/**
 * datetime: 2021/11/21 10:58
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Select;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class SelectEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/21
 */
class SelectEntity extends Select implements UnitI
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

        $options  = [
            Dom::create('option')->addContent($this->placeholder)->addAttr('value', '')
        ];
        foreach ($this->options as $value => $label) {
            if (is_array($label)) { // 有分组选项时
                $optgroup = Dom::create('optgroup')->addAttr('label', $value);
                foreach ($label as $value_children => $label_children){
                    $option = Dom::create('option')->addContent($label_children)->addAttr('value', $value_children);
                    $this->getCheck($value_children) and $option->addAttr('selected', '');
                    $optgroup->addContent($option);
                }
                $options[] = $optgroup;
            }else{
                $option = Dom::create('option')->addContent($label)->addAttr('value', $value);
                $this->getCheck($value) and $option->addAttr('selected', '');
                $options[] = $option;
            }
        }

        $select = Dom::create('select')
            ->addAttr('name', $this->name)
            ->addAttr('lay-search', '')
            ->addAttr('lay-filter', "filter-$this->name")
            ->addContent(implode($options))
            ->addAttr($this->getCurrentSceneInputAttr($scene));

        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');

            return $itemDom->addContent($inputDiv->addContent($select))
                ->addContent($this->getShortTipElement($this->shortTip));
        }

        $inputDiv->addClass('layui-inline');
        return $inputDiv->addContent($select);
    }

    public function getJs(): string
    {
        $whereObj = empty($this->showWhere['value']) ? '[]' : json_encode($this->showWhere['value'], JSON_UNESCAPED_UNICODE);
        $associationOptions = '{}';
        if ($this->associationOptions){
            $associationOptions = json_encode($this->associationOptions, JSON_UNESCAPED_UNICODE);
        }
        $name = strtr($this->name, ['%' => '', '>' => '', '<' => '', '=' => '']);

        return <<<JS
        window['associationOptions_$name'] = $associationOptions;
        window['sc-4-select-$this->name'] = $whereObj;
        layui.form.on('select(filter-$this->name)', function(data){
          let event_value = data.value;
          window['sc4ControlShow'](window['sc-4-select-$this->name'], event_value);
          window['sc4AssociationOptionsRender_$name'](event_value)
          layui.form.render();
        });
        $(()=>window['sc4ControlShow'](window['sc-4-select-$this->name'], '$this->defaultValue'));
        window["sc4AssociationOptionsRender_$name"] = (v) => {
            for(let field in window['associationOptions_$name']) {
               window["sc4OptionsRender"](layui.jquery(`select[name=\${field}]`),v ? window['associationOptions_$name'][field][v] : []);
               if (window.hasOwnProperty(`sc4AssociationOptionsRender_\${field}`)){
                    window[`sc4AssociationOptionsRender_\${field}`](null);
               }
            }
        };
JS;
    }


    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function getCheck($value): bool
    {
        if ($this->defaultValue === '' || $this->defaultValue === null) {
            return false;
        }
        return $value == $this->defaultValue;
    }

}

