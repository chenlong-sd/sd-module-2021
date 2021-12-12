<?php
/**
 * datetime: 2021/11/21 10:57
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, InputAttr, Placeholder, Required, ShortTip};

abstract class Select extends BaseFormUnit
{
    use DefaultValue,InputAttr,Placeholder,Required,ShortTip;

    protected $options = [];

    /**
     * @var string
     */
    protected $associatedField = '';

    /**
     * @var array 联动选项
     */
    protected $associationOptions = [];

    /**
     * 设置表单的可选项
     * @param array  $options           可选项值
     *  无 associated_field 时 [
     *    1 => '选项一',
     *    2 => '选项二',
     *    ...
     * ]
     * 有 associated_field 时 [
     *    ['value' = 1, 'title' => '选项一', 'parent_value' => 0],
     *    ['value' = 2, 'title' => '选项二', 'parent_value' => 0],
     *    ...
     * ]
     * @param string $associated_field  关联的字段,选项会随该字段的变化而变化
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function options(array $options, string $associated_field = '')
    {
        $this->options         = $options;
        $this->associatedField = $associated_field;
        return $this;
    }

    public function __construct(string $name = '', string $label = '')
    {
        parent::__construct($name, $label);
        $this->placeholder = '请选择';
    }
}
