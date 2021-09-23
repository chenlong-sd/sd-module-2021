<?php
/**
 * Date: 2020/11/9 14:46
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\item;

use sdModule\layui\Dom;

/**
 * Class Button
 * @method Button danger($size = '') size: xs|sm|''|
 * @method Button primary($size = '')
 * @method Button normal($size = '')
 * @method Button warm($size = '')
 * @method Button disabled($size = '')
 * @method Button defaults($size = '')
 * @package sdModule\layui\item
 */
class Button
{
    /**
     * @var array css类名
     */
    private $class_name = ["layui-btn"];

    /**
     * @var string 事件名
     */
    private $event = '';

    /**
     * @var string 图标类
     */
    private $icon = '';

    /**
     * @var string 标题
     */
    private $title = '';

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
        $button = Dom::create('button')->addAttr('type','button')
            ->addClass($this->class_name)->addContent($this->icon())->addContent($this->title);
        if ($this->event){
            $button->addAttr('lay-event', $this->event);
        }
        return $button;
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
    private function icon(): string
    {
        return $this->icon ? Dom::create('i')->addClass("layui-icon layui-icon-{$this->icon}") : '';
    }

    /**
     * 设置按钮大小
     * @param string $size
     * @return Button
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function setSize(string $size): Button
    {
        $this->class_name[] = "layui-btn-{$size}";
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Button
     */
    public function __call($name, $arguments)
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
