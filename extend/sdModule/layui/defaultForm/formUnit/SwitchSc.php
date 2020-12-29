<?php
/**
 * Date: 2020/12/7 13:37
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class SwitchSc extends UnitBase
{
    /**
     * @var int|mixed
     */
    public $default;

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        if ($this->default) {
            $checked = $this->default == $this->select_data['value'][0] ? 'checked' : '';
        }else{
            $checked = $this->preset == $this->select_data['value'][0] ? 'checked' : '';
        }

        $value = $this->default ?: ($this->preset ?: '');

        $html  = "<input type=\"checkbox\" lay-filter='{$this->name}' lay-text='{$this->select_data['title']}'  {$attr} lay-skin=\"switch\" {$checked} >";
        $html .= "<input type='hidden' name=\"{$this->name}\" value='{$value}'/>";
        return $html;
    }

    /**
     * @return string
     */
    public function getJs()
    {
        return <<<JS
    form.on('switch({$this->name})', function(data){
        let value = data.elem.checked ? "{$this->select_data['value'][0]}" : "{$this->select_data['value'][1]}";
        layui.jquery("input[name={$this->name}]").val(value);
    });  
JS;
    }
}
