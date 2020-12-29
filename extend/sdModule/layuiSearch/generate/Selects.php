<?php


namespace sdModule\layuiSearch\generate;

/**
 * 下拉选择
 * Class Select
 * @package sdModule\layuiSearch\generate
 */
class Selects extends FormVar implements FormDefine
{
    /**
     * @param string $name
     * @param string $placeholder
     * @return mixed
     */
    public function __constract(string $name, string $placeholder = '')
    {
        // TODO: Implement name() method.
    }
    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label ? "<label class=\"layui-form-label\">{$this->placeholder}</label>"  : '';
    }
    /**
     * @param bool $status
     * @return $this|mixed
     */
    public function label(bool $status)
    {
        $this->label = $status;
        return $this;
    }

    /**
     * @param string $placeholder
     * @return FormDefine
     */
    public function placeholder(string $placeholder = ''): FormDefine
    {
        // TODO: Implement placeholder() method.
    }

    /**
     * @param array $data
     * @return LayuiForm
     */
    public function html(array $data = []): LayuiForm
    {
        // TODO: Implement html() method.
    }


}

