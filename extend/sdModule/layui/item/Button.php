<?php
/**
 * Date: 2020/11/9 14:46
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\item;

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
     * @return string
     */
    private function button()
    {
        return "<button type=\"button\" {$this->getEventStr()} class=\"{$this->getClassNameStr()}\">{$this->icon()}{$this->title}</button>";
    }

    /**
     * 获取事件字符串
     * @return string
     */
    private function getEventStr()
    {
        return $this->event ? "lay-event=\"{$this->event}\"" : "";
    }

    /**
     * 获取类名字符串
     * @return string
     */
    private function getClassNameStr()
    {
        return implode(' ', $this->class_name);
    }

    /**
     * 设置事件
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event = '')
    {
        $this->event = $event;
        return $this;
    }

    /**
     * 设置值按钮类
     * @param mixed ...$class_name
     * @return $this
     */
    public function setBtnClass(...$class_name)
    {
        $this->class_name = array_merge($this->class_name, array_map(fn($v) => "layui-btn-{$v}", $class_name));
        return $this;
    }

    /**
     * 按钮组
     * @param mixed ...$buttons
     * @return string
     */
    public function group(...$buttons)
    {
        $button_str = implode($buttons);
        return "<div class=\"layui-btn-group\">{$button_str}</div>";
    }

    /**
     * 获取图标字符串
     * @return string
     */
    private function icon()
    {
        return $this->icon ? "<i class=\"layui-icon layui-icon-{$this->icon}\"></i>" : '';
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        $this->class_name[] = empty($arguments[0]) ? "" : "layui-btn-{$arguments[0]}";
        $this->class_name[] = $name === 'defaults' ? "" : "layui-btn-{$name}";
        return $this;
    }

    public function __destruct(){}

    public function __toString()
    {
        return $this->button();
    }
}
