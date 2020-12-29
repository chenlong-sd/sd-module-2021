<?php
/**
 * Date: 2020/9/26 10:23
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


abstract class UnitBase
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $label;

    /**
     * @var string
     */
    protected string $placeholder;

    /**
     * @var array
     */
    protected array $select_data = [];
    /**
     * @var string|int|array 预设表单值
     */
    protected $preset = '';

    protected array $config = [];

    /**
     * UnitInterface constructor.
     * @param string $name
     * @param string $label
     * @param string $placeholder
     */
    public function __construct(string $name, string $label, string $placeholder = '')
    {
        $this->name        = $name;
        $this->label       = $label;
        $this->placeholder = $placeholder;
    }

    /**
     * @param array $selectValue
     * @return mixed|static
     */
    public function setData(array $selectValue = [])
    {
        $this->select_data = $selectValue;
        return $this;
    }

    /**
     * 设置预选
     * @param $preset
     * @return $this
     */
    public function setPreset($preset)
    {
        $this->preset = $preset;
        return $this;
    }

    /**
     * 设置配置
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return string
     */
    public function getJs()
    {
        return '';
    }

    /**
     * @param string $lang
     * @return mixed
     */
    protected function lang(string $lang)
    {
        return lang($this->placeholder ?: $lang);
    }

    /**
     * 获取html内容
     * @param string $attr
     * @return mixed
     */
    abstract public function getHtml(string $attr);
}
