<?php
/**
 * Date: 2020/11/20 18:51
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm;

/**
 * Class UnitData
 * @package sdModule\layui\defaultForm
 */
class UnitData
{
    const SELECT    = 'select';
    const TEXT      = 'text';
    const HIDDEN    = 'hidden';
    const PASSWORD  = 'password';
    const RADIO     = 'radio';
    const CHECKBOX  = 'checkbox';
    const IMAGE     = 'image';
    const UPLOAD    = 'upload';
    const IMAGES    = 'images';
    const TEXTAREA  = 'textarea';
    const TIME      = 'time';
    const U_EDITOR  = 'u_editor';
    const SELECTS   = 'selects';
    const TEXT_SHORT= 'textShort';
    const TAG       = 'tag';
    const SWITCH    = 'switch_sc';
    const AUX_TITLE = 'aux_title';

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $label = '';
    /**
     * @var string
     */
    private string $type = '';
    /**
     * @var string
     */
    private string $placeholder = '';
    /**
     * @var array
     */
    private array  $selectData = [];
    /**
     * @var array
     */
    private array  $removeScene = [];
    /**
     * @var array
     */
    private array  $config = [];
    /**
     * @var array
     */
    private array  $inputAttr = [];

    /**
     * @var null|array|int
     */
    private $defaultValue = null;

    /**
     * 生成form单元数据
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function create(string $name = '', string $label = ''): UnitData
    {
        $instance        = new self();
        $instance->name  = $name;
        $instance->label = $label;
        return $instance;
    }

    /**
     * 设置默认值
     * @param string|int|array $default
     * @return UnitData
     */
    public function defaultValue($default): UnitData
    {
        $this->defaultValue = $default;
        return $this;
    }

    /**
     * 设置元素配置
     * selects @link https://maplemei.gitee.io/xm-select/#/component/install
     * @param array $config 配置值
     * @return $this
     */
    public function unitConfig(array $config): UnitData
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 此表单排斥场景
     * @param array $scene
     * @return UnitData
     */
    public function removeScene(array $scene): UnitData
    {
        $this->removeScene = $scene;
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
        $this->inputAttr = $attr;
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

    /**
     * @param array $selectData
     * @return UnitData
     */
    public function selectData(array $selectData): UnitData
    {
        $this->selectData = $selectData;
        return $this;
    }

    /**
     * @param string $placeholder
     * @return UnitData
     */
    public function placeholder(string $placeholder): UnitData
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param string $type
     * @return UnitData
     */
    public function setUnitType(string $type): UnitData
    {
        $this->type = $type;
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
        $this->selectData = compact('type', 'range');
        return $this;
    }

    /**
     * 设置上传类型
     * @param string $type
     * @return UnitData
     */
    public function uploadType(string $type = 'file'): UnitData
    {
        $this->selectData = compact('type');
        return $this;
    }

    /**
     * 自定义HTML
     * @param string $html
     * @return $this
     */
    public function customHtml(string $html): UnitData
    {
        $this->selectData = compact('html');
        return $this;
    }
}
