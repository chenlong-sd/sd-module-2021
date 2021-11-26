<?php
/**
 * datetime: 2021/11/18 21:58
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Checkbox, UnitI};

/**
 * Class Checkbox
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class CheckboxEntity extends Checkbox implements UnitI
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
                'type'      => 'checkbox',
                'lay-skin'  => 'primary',
                'value'     => $value,
                'title'     => $label,
                'name'      => "$this->name[]",
                'lay-filter'=> "sc-4-filter-$this->name"
            ];
            $this->isChecked($value) and $customAttr['checked'] = 'checked';
            $inputDiv->addContent($this->getInputElement()->addAttr($customAttr)
                ->addAttr($this->getCurrentSceneInputAttr($scene)));
        }

        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->isInline ? 'layui-inline' : 'layui-input-block');
            return $itemDom->addContent($inputDiv);
        }

        return $inputDiv->addClass('layui-input-inline');
    }

    /**
     * 获取选中状态
     * @param $value
     * @return bool
     */
    private function isChecked($value): bool
    {
        if (!$this->defaultValue) {
            return false;
        }
        
        if (!is_array($this->defaultValue)) {
            $this->defaultValue = explode(',', $this->defaultValue);
        }

        return in_array($value, $this->defaultValue);
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    private function getShowWhereJs(): string
    {
        if (empty($this->showWhere['value'])) {
            return '';
        }

        $whereObj = json_encode($this->showWhere['value'], JSON_UNESCAPED_UNICODE);
        $default  = json_encode($this->defaultValue);
        return <<<JS
        let sc_4_checkbox_value_$this->name = [];
        window["sc-4-checkbox-$this->name"] = $whereObj;
        layui.form.on('checkbox(sc-4-filter-$this->name)', function(data){
          if (data.elem.checked){
              sc_4_checkbox_value_$this->name.push(data.value);
          }else{
              let sc_4_index = sc_4_checkbox_value_$this->name.indexOf(data.value);
              if (sc_4_index > -1){
                  sc_4_checkbox_value_$this->name.splice(sc_4_index, 1);
              }
          }
          
          window['sc4ControlShow']( window["sc-4-checkbox-$this->name"], sc_4_checkbox_value_$this->name);
        });
        $(()=>window['sc4ControlShow']( window["sc-4-checkbox-$this->name"], $default));
JS;

    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getJs(): string
    {
        return $this->getShowWhereJs();
    }
}
