<?php
/**
 * datetime: 2021/11/18 11:28
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit;

use sdModule\layui\Dom;

/**
 * Class BaseFormUnit
 * @package sdModule\layui\form4\formUnit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
abstract class BaseFormUnit
{
    /**
     * @var string 表单name
     */
    protected $name = '';
    /**
     * @var string 表单label
     */
    protected $label = '';

    /**
     * @var array 展示条件
     */
    protected $showWhere = [];
    /**
     * @var string 当前表单的元素ID
     */
    protected $formUnitId = null;
    /**
     * @var array 表单元素的class
     */
    protected $itemClass = [];
    /**
     * @var bool 是否是行内表单
     */
    protected $isInline = false;

    /**
     * @var array 不显示的场景
     */
    protected $removeScene = [];

    /**
     * BaseFormUnit constructor.
     * @param string $name
     * @param string $label
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function __construct(string $name = '', string $label = '')
    {
        $this->name  = $name;
        $this->label = $label;

        $name = $name ?: "R" . mt_rand(10000, 99999);
        $name = strtr($name, ['%' => '', '.' => '', '>' => '', '<' => '', '~' => '', '=' => '']);
        $this->formUnitId("sc-4-$name-id");
    }

    /**
     * 设置展示条件
     * @param string $field 哪个字段
     * @param string|array $value 哪个值或那一集合值
     * @return BaseFormUnit
     */
    public function showWhere(string $field, $value): BaseFormUnit
    {
        $this->showWhere = compact('field', 'value');
        return $this;
    }

    /**
     * @param string|null $formUnitId
     * @return BaseFormUnit
     */
    public function formUnitId(string $formUnitId): BaseFormUnit
    {
        $this->formUnitId = $formUnitId;
        return $this;
    }

    /**
     * @param array $itemClass
     * @return BaseFormUnit
     */
    public function itemClass(array $itemClass): BaseFormUnit
    {
        $this->itemClass = $itemClass;
        return $this;
    }

    /**
     * @param bool $isInline
     * @return BaseFormUnit
     */
    public function setInline(bool $isInline): BaseFormUnit
    {
        $this->isInline = $isInline;
        return $this;
    }

    /**
     * @param array $removeScene
     * @return BaseFormUnit
     */
    public function removeScene(array $removeScene): BaseFormUnit
    {
        $this->removeScene = $removeScene;
        return $this;
    }


    /**
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function getItemElement(): Dom
    {
        return Dom::create()->setId($this->formUnitId)
            ->addClass($this->itemClass)
            ->addClass($this->isInline ? 'layui-inline' : 'layui-form-item');
    }

    /**
     * 获取基本表单input
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    protected function getInputElement(): Dom
    {
        return Dom::create('input')->setSingleLabel()
            ->addClass('layui-input')
            ->addAttr([
                'name'         => $this->name,
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
    protected function getLabelElement(string $labelText): Dom
    {
        return Dom::create('label')->addClass('layui-form-label')
            ->addContent($labelText);
    }

    /**
     * 获取提示语的html
     * @return Dom|string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    protected function getShortTipElement(string $shortTip)
    {
        return $shortTip ? Dom::create()->addClass('layui-inline layui-word-aux')->addContent($shortTip) : '';
    }

}
