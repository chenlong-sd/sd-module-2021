<?php
/**
 * Date: 2020/9/26 10:23
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

abstract class UnitBase
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var array
     */
    public $options = [];
    /**
     * @var string|int|array 预设表单值
     */
    public $default = null;
    /**
     * @var array js配置项
     */
    public $config = [];

    /**
     * @var string 表单基础元素的类
     */
    public $itemClass = 'layui-form-item';

    /**
     * @var array|UnitBase[] 子项目
     */
    public $childrenItem = [];

    /**
     * 短标签
     * @var string
     */
    public $shortTip = '';

    /**
     * @var bool 是否必填
     */
    private $isRequired = false;
    /**
     * @var array 展示条件
     */
    public $showWhere = [];
    /**
     * @var string
     */
    public $boxID = '';


    /**
     * UnitInterface constructor.
     * @param string $name
     * @param string $label
     * @param string $placeholder
     */
    public function __construct(string $name, string $label, string $placeholder = '')
    {
        $this->name         = $name;
        $this->label        = $label;
        $this->placeholder  = $placeholder;
    }

    /**
     * 设置选项值
     * @param array $options
     * @return mixed|static
     */
    public function setOption(array $options = []): UnitBase
    {
        $this->options = $options;
        return $this;
    }

    /**
     * 设置预选
     * @param $default
     * @return $this
     */
    public function setDefault($default): UnitBase
    {
        $this->default = $default;
        return $this;
    }

    /**
     * 设置配置
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config): UnitBase
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 设置必填
     * @param bool $is_required
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function setRequired(bool $is_required = false): UnitBase
    {
        $this->isRequired = $is_required;
        return $this;
    }

    /**
     * 设置展示条件
     * @param array $where
     * @return UnitBase
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    public function setShowWhere(array $where): UnitBase
    {
        $this->showWhere = $where;
        return $this;
    }

    /**
     * 设置盒子的ID
     * @param string $id
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    public function setBoxId(string $id): UnitBase
    {
        $this->boxID = $id;
        return $this;
    }

    /**
     * 获取控制显示条件的js
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    protected function getShowWhereJs(): array
    {
        $where_str = $default = '';
        $defaultValue = is_array($this->default) ? '' : $this->default;
        foreach ($this->showWhere as $value){
            $show_value = json_encode(array_map(function ($v){
                return is_object($v) ? $v : (string)$v;
            },(array)$value['value']));

            $str = "if($show_value.includes(%s)){ \$('#{$value['box_id']}').show();}else{ \$('#{$value['box_id']}').hide();}";

            $where_str .= sprintf($str, 'value');
            $default   .= sprintf($str, "'$defaultValue'");
        }

        return [$where_str, $default];
    }


    /**
     * @return string
     */
    public function getJs(): string
    {
        list($where_str, $default) = $this->getShowWhereJs();

        return !$this->showWhere ? '' : <<<JS
        $default
        \$('input[name=$this->name]').on('change', function (){
            let value = \$(this).val();
            $where_str
        });
JS;
    }

    /**
     * 设置元素的CSS
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/6
     */
    public function getCss(): string
    {
        return '';
    }

    /**
     * @param string $itemClass
     * @return UnitBase
     */
    public function setItemClass(string $itemClass): UnitBase
    {
        $this->itemClass = $itemClass;
        return $this;
    }

    /**
     * 设置子项目
     * @param UnitBase[]|UnitBase $childrenItem
     * @param array $attr 调用子项目的的额外属性
     * @return UnitBase
     */
    public function addChildrenItem($childrenItem, array $attr): UnitBase
    {
        is_array($childrenItem)
            ? $this->childrenItem = array_merge($this->childrenItem, [$childrenItem, $attr])
            : $this->childrenItem[] = [$childrenItem, $attr];
        return $this;
    }

    /**
     * @param string $shortTip
     * @return UnitBase
     */
    public function setShortTip(string $shortTip): UnitBase
    {
        $this->shortTip = $shortTip;
        return $this;
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
     * @param array $attr
     * @return mixed
     */
    abstract public function getHtml(array $attr): Dom;

    /**
     * @return string
     */
    protected function nameReplace(): string
    {
        return strtr($this->name, ['>' => '', '%' => '', '~' => '', '<' => '', '=' => '', '.' => '']);
    }

    /**
     * 获取基本表单input
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    protected function getInput(): Dom
    {
        return Dom::create('input')->setSingleLabel()
            ->addClass('layui-input')
            ->addAttr([
                'name' => $this->name,
                'placeholder' => $this->placeholder ?: $this->lang('please enter'),
                'autocomplete' => 'off',
            ]);
    }

    /**
     * 获取label
     * @param string $labelText
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    protected function getLabel(string $labelText): Dom
    {
        return Dom::create('label')->addClass('layui-form-label')
            ->addContent($labelText)->addContent($this->requiredDom());
    }

    /**
     * 必选的Dom获取
     * @return Dom|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    private function requiredDom()
    {
        return $this->isRequired ? Dom::create('span')
            ->addAttr([
                'style' => 'position: absolute;font-size: 25px;color: red;top: 14px;right: 3px;',
            ])->addContent('*') : '';
    }

    /**
     * 获取表单元素的基础html
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    protected function getItem(): Dom
    {
        return Dom::create()->setId($this->boxID)->addClass($this->itemClass);
    }

    /**
     * 获取提示语的html
     * @return Dom|string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    protected function getShortTip()
    {
        return $this->shortTip ? Dom::create()->addClass('layui-inline layui-word-aux')->addContent($this->shortTip) : '';
    }
}
