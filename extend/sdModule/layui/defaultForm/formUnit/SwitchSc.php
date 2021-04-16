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
        $option_value = array_keys($this->select_data);
        if ($this->default) {
            $checked = $this->default == current($option_value) ? 'checked' : '';
        }else{
            $checked = $this->preset == current($option_value) ? 'checked' : '';
        }

        $value = $this->default ?: ($this->preset ?: '');
        $title = implode('|', $this->select_data);

        $html  = "<input type=\"checkbox\" lay-filter='{$this->name}' lay-text='{$title}'  {$attr} lay-skin=\"switch\" {$checked} >";
        $html .= "<input type='hidden' name=\"{$this->name}\" value='{$value}'/>";
        return $html;
    }

    /**
     * @return string
     */
    public function getJs()
    {
        $option_value = array_keys($this->select_data);
        $open_value   = current($option_value);
        $close_value  = next($option_value);
        return <<<JS
    form.on('switch({$this->name})', function(data){
        let value = data.elem.checked ? "{$open_value}" : "{$close_value}";
        layui.jquery("input[name={$this->name}]").val(value);
    });  
JS;
    }
}
