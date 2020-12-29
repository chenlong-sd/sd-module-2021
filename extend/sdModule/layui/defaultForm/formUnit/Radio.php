<?php
/**
 * Date: 2020/9/26 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class Radio extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        $option = '';
        foreach ($this->select_data as $value => $label) {
            $option .= "<input type=\"radio\" {$attr} name=\"{$this->name}\" {$this->getCheck($value)} value=\"{$value}\" title=\"{$label}\">";
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
        return $value === $this->preset ? 'checked' : '';
    }

}
