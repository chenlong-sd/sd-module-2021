<?php
/**
 * Date: 2020/11/20 18:51
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;

use sdModule\layui\Dom;
use sdModule\layui\form\formUnit\UnitBase;

/**
 * Class UnitData
 * @deprecated
 * @package sdModule\layui\defaultForm
 */
class UnitData
{
    /**
     * @var array 表单配置项数据
     *
     */
    private $unitConfig = [];

    /**
     * 生成form单元数据
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function create(string $name = '', string $label = ''): UnitData
    {
        $instance = new self();
        $instance->unitConfig['name']  = $name;
        $instance->unitConfig['label'] = $label;
        return $instance;
    }

    /**
     * 设置默认值
     * @param string|int|array $default
     * @return UnitData
     */
    public function defaultValue($default): UnitData
    {
        $this->unitConfig['defaultValue'] = $default;
        return $this;
    }

    /**
     * 设置元素配置
     * selects @link https://maplemei.gitee.io/xm-select/#/component/install
     * @param array $config 配置值
     * @return $this
     */
    public function unitJsConfig(array $config): UnitData
    {
        $this->unitConfig['config'] = $config;
        return $this;
    }

    /**
     * 此表单排斥场景
     * @param array $scene
     * @return UnitData
     */
    public function removeScene(array $scene): UnitData
    {
        $this->unitConfig['removeScene'] = $scene;
        return $this;
    }

    /**
     * 设置input的属性值
     * @param array $attr ["scene" => "attr"]
     * @example ['add' => 'disable']
     * @return UnitData
     */
    public function inputAttr(array $attr): UnitData
    {
        $this->unitConfig['inputAttr'] = $attr;
        return $this;
    }

    /**
     * 获取对应数据
     * @param string $attr
     * @param null $default
     * @return mixed|null
     */
    public function get(string $attr, $default = null)
    {
        return $this->unitConfig[$attr] ?? $default;
    }

    /**
     * @param array $options
     * @return UnitData
     */
    public function options(array $options): UnitData
    {
        $this->unitConfig['options'] = array_map(function ($v){
            return $v instanceof Dom ? current($v->getContent()) : $v;
        }, $options);
        return $this;
    }

    /**
     * @param string $placeholder
     * @return UnitData
     */
    public function placeholder(string $placeholder): UnitData
    {
        $this->unitConfig['placeholder'] = $placeholder;
        return $this;
    }

    /**
     * 设置表单必填
     * @param string|array|bool $scene
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/23
     */
    public function required($scene = true): UnitData
    {
        $this->unitConfig['required'] = $scene;
        return $this;
    }

    /**
     * @param string $type
     * @return UnitData
     */
    public function setUnitType(string $type): UnitData
    {
        $this->unitConfig['formUnitType'] = $type;
        return $this;
    }

    /**
     * 设置时间格式
     * @param string $type
     * @param string|bool $range
     * @return UnitData
     */
    public function setTime(string $type = 'datetime', $range = false): UnitData
    {
        $this->unitConfig['config'] = compact('type', 'range');
        return $this;
    }

    /**
     * 颜色配置设置
     * @param string $format   格式  hex | rgb
     * @param array $predefine 设置预定义颜色
     * @param bool $alpha      开启透明度
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function colorConfig(string $format = 'hex', array $predefine = [], bool $alpha = true): UnitData
    {
        $this->unitConfig['config'] = compact('format', 'predefine', 'alpha');
        return $this;
    }

    /**
     * 设置上传类型
     * @param string $type
     * @return UnitData
     */
    public function uploadType(string $type = 'file'): UnitData
    {
        $this->unitConfig['config'] = compact('type');
        return $this;
    }

    /**
     * 自定义HTML
     * @param Dom $html
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function customHtml(Dom $html): UnitData
    {
        $this->unitConfig['options'] = compact('html');
        return $this;
    }

    /**
     * 设置子项目
     * @param mixed ...$childrenItem
     * @return UnitData
     */
    public function setChildrenItem(...$childrenItem): UnitData
    {
        $this->unitConfig['childrenItem'] = $childrenItem;
        return $this;
    }

    /**
     * 设置展示条件
     * @param string $field
     * @param $value
     * @return UnitData
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    public function setShowWhere(string $field, $value)
    {
        $this->unitConfig['showWhere'] = compact('field', 'value');
        return $this;
    }

    /**
     * 设置表单盒子的ID
     * @param string $id
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    public function setBoxId(string $id)
    {
        $this->unitConfig['boxId'] = $id;
        return $this;
    }

    /**
     * 联动选项
     * @param string $field 联动的字段，只能为下拉类型的
     * @param array $options 联动的选项，二维数组，
     * @example [
     *      ['id' => '1', 'linkage_id' => '1', 'label' => 'test1'],
     *      ['id' => '2', 'linkage_id' => '1', 'label' => 'test2'],
     *      ['id' => '3', 'linkage_id' => '2', 'label' => 'test3'],
     *      ['id' => '4', 'linkage_id' => '2', 'label' => 'test4'],
     * ]
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/20
     */
    public function linkageOptions(string $field, array $options)
    {
        $this->unitConfig['linkage'] = compact('field', 'options');
        return $this;
    }
}
