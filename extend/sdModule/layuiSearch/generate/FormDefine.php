<?php


namespace sdModule\layuiSearch\generate;


interface FormDefine
{
    /**
     * @param string $name          定义表单的name值
     * @param string $placeholder   定义表单的placeholder值
     * @return mixed
     */
    public function __construct(string $name, string $placeholder = '');

    /**
     * 生成html
     * @return LayuiForm
     */
    public function html():LayuiForm;

    /**
     * 是否显示label
     * @param $status
     * @return mixed
     */
    public function label(bool $status);
}

