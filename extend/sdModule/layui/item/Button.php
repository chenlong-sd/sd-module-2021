<?php
/**
 * Date: 2020/11/9 14:46
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\item;

use sdModule\layui\Dom;

/**
 * Class Button
 * @method string danger($size = '') size: xs|sm|''|
 * @method string primary($size = '')
 * @method string normal($size = '')
 * @method string warm($size = '')
 * @method string disabled($size = '')
 * @method string defaults($size = '')
 * @package sdModule\layui\item
 */
class Button
{
    /**
     * @var array css类名
     */
    private array $class_name = ["layui-btn"];

    /**
     * @var string 事件名
     */
    private string $event = '';

    /**
     * @var string 图标类
     */
    private string $icon = '';

    /**
     * @var string 标题
     */
    private string $title = '';

    /**
     * Button constructor.
     * @param string $title
     * @param string $icon_class
     */
    public function __construct(string $title = '', string $icon_class = '')
    {
        $this->title = $title;
        $this->icon = $icon_class;
    }

    /**
     * 生成button Html代码
     * @return Dom
     */
    private function button(): Dom
    {
        return Dom::create('button')->addAttr([
            'type' =>  'button',
            'lay-event' => $this->event,
        ])->addClass($this->class_name)->addContent($this->icon())->addContent($this->title);
    }

    /**
     * 设置事件
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event = ''): Button
    {
        $this->event = $event;
        return $this;
    }

    /**
     * 设置值按钮类
     * @param mixed ...$class_name
     * @return $this
     */
    public function addBtnClass(...$class_name): Button
    {
        $this->class_name = array_merge($this->class_name, $class_name);
        return $this;
    }

    /**
     * 按钮组
     * @param mixed ...$buttons
     * @return string
     */
    public function group(...$buttons): string
    {
        return Dom::create()->addClass('layui-btn-group')->addContent(implode($buttons));
    }

    /**
     * 获取图标字符串
     * @return string
     */
    private function icon()
    {
        return $this->icon ? Dom::create('i')->addClass("layui-icon layui-icon-{$this->icon}") : '';
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     */
    public function __call($name, $arguments): string
    {
        $this->class_name[] = empty($arguments[0]) ? "" : "layui-btn-{$arguments[0]}";
        $this->class_name[] = $name === 'defaults' ? "" : "layui-btn-{$name}";
        return $this;
    }

    public function __destruct(){}

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function __toString(): string
    {
        return (string)$this->button();
    }
}
