<?php


namespace sdModule\layuiSearch\generate;

/**
 * 下拉选择
 * Class Select
 * @package sdModule\layuiSearch\generate
 */
class Select extends FormVar implements FormDefine
{
    /**
     * @param array $data
     * @return LayuiForm
     */
    public function html(array $data = []): LayuiForm
    {
        $option = "<option value=''>{$this->placeholder}</option>";
        foreach ($data as $key => $value){
            $option .= "<option value=\"{$key}\">{$value}</option>";
        }

        $html =  <<<HTM
                <div class="layui-inline">
                    {$this->getLabel()}
                    <div class="layui-input-inline">
                        <select name="{$this->name}">
                            {$option}
                        </select>
                    </div>
                </div>
HTM;
        return LayuiForm::generate($html);
    }

}

