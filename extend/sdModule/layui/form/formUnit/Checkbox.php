<?php
/**
 * Date: 2020/9/26 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class Checkbox extends UnitBase
{

    public ?string $default = '';

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr): string
    {
        $option = '';
        foreach ($this->select_data as $value => $label) {
            $option .= "<input type=\"checkbox\" {$attr} name=\"{$this->name}[]\" {$this->getCheck($value)} lay-skin=\"primary\" value=\"{$value}\" title=\"{$label}\">";
        }
        return $option;
    }

    /**
     * 获取选中状态
     * @param $value
     * @return string
     */
    private function getCheck($value)
    {
        $data = $this->default ? explode(',', $this->default) : $this->preset;
        if (!$data){
            return '';
        }
        return in_array($value, $data) ? 'checked' : '';
    }

}
