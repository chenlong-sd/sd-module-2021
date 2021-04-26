<?php
/**
 * Date: 2020/9/26 11:33
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Select extends UnitBase
{
    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $option = "<option value=''>{$this->placeholder}</option>";
        foreach ($this->select_data as $value => $label) {
            if (is_array($label)) {
                $option .= "<optgroup label=\"{$value}\">";
                foreach ($label as $value_children => $label_children){
                    $option .= "<option value='{$value_children}' {$this->getCheck($value_children)}>{$label_children}</option>";
                }
                $option .= " </optgroup>";
            }else{
                $option .= "<option value='{$value}' {$this->getCheck($value)}>{$label}</option>";
            }
        }
        return "<select name=\"{$this->name}\" {$attr} lay-search=\"\">{$option}</select>";
    }

    /**
     * 获取选中状态
     * @param $value
     * @return string
     */
    private function getCheck($value)
    {
        return $value == $this->preset ? 'selected' : '';
    }

}
