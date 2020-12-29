<?php


namespace sdModule\layuiSearch\generate;


class FormVar
{
    /**
     * @var string 表单name
     */
    protected $name;

    /**
     * @var string 表单placeholder
     */
    protected $placeholder;

    /**
     * @var string 表单label
     */
    protected $label;

    /**
     * @param string $name
     * @param string $placeholder
     * @return self
     */
    public function __construct(string $name, string $placeholder = '')
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 获取Lable的html
     * @return mixed
     */
    protected function getLabel()
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
}

