<?php
/**
 * Date: 2020/11/20 18:51
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm;


class UnitData
{
    private string $name;
    private string $label;
    private string $type;
    private string $placeholder;
    private array  $select_data;
    private array  $remove_scene;
    private array  $config;
    private array  $input_attr;
    /**
     * @var null|array|int
     */
    private $preset = null;

    /**
     * 生成form单元数据
     * @param string $name
     * @param string $label
     * @param string $type
     * @param string $placeholder
     * @param array $select_data
     * @return UnitData
     */
    public static function _self(string $name, string $label, string $type, string $placeholder, array $select_data)
    {
        $instance               = new self();
        $instance->name         = $name;
        $instance->label        = $label;
        $instance->type         = $type;
        $instance->placeholder  = $placeholder;
        $instance->select_data  = $select_data;
        $instance->preset       = null;
        $instance->remove_scene = [];
        $instance->config       = [];
        $instance->input_attr   = [];
        return $instance;
    }

    /**
     * 设置默认值
     * @param string|int|array $preset
     * @return UnitData
     */
    public function preset($preset)
    {
        $this->preset = $preset;
        return $this;
    }

    /**
     * 设置元素配置
     * selects @link https://maplemei.gitee.io/xm-select/#/component/install
     * @param array $config 配置值
     * @return $this
     */
    public function unitConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 此表单排斥场景
     * @param array $scene
     * @return UnitData
     */
    public function removeScene(array $scene)
    {
        $this->remove_scene = $scene;
        return $this;
    }

    /**
     * 设置input的属性值
     * @param array $attr ["scene" => "attr"]
     * @example ['add' => 'disable']
     * @return UnitData
     */
    public function inputAttr(array $attr)
    {
        $this->input_attr = $attr;
        return $this;
    }

    /**
     * 获取对应数据
     * @param string $attr
     * @return mixed|null
     */
    public function get(string $attr)
    {
        return $this->$attr ?? null;
    }
}
