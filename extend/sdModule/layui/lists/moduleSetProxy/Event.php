<?php
/**
 * datetime: 2021/12/10 12:20
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\moduleSetProxy;

use sdModule\layui\lists\module\Event as EventEntity;
use sdModule\layui\lists\PageData;

/**
 * Class EventProxy
 * @method Event setPrimaryBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setDangerBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setNormalBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setWarmBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setDefaultBtn(string $title, string $icon = '', string $size = 'xs')
 * @package sdModule\layui\lists\moduleSetProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/10
 */
class Event
{
    /**
     * @var EventEntity
     */
    private $event;

    /**
     * EventProxy constructor.
     * @param PageData $page_data
     * @param string $event
     * @param bool $is_bar
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function __construct(PageData $page_data, string $event = '', bool $is_bar = false)
    {
        $this->event = new EventEntity($page_data, $event, $is_bar);
    }

    /**
     * 设置按钮
     * @param string $title
     * @param string $type
     * @param string $icon
     * @param string $size
     * @return Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function setBtn(string $title, string $type, string $icon = '', string $size = 'xs'): Event
    {
        $this->event->setBtn($title, $type, $icon, $size);
        return $this;
    }

    /**
     * 设置事件JS
     * @param string $js
     * @return Event
     */
    public function setJs(string $js): Event
    {
        $this->event->setJs($js);
        return $this;
    }

    /**
     * 设置展示条件
     * @param string $whereExpression
     * @return Event
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function setWhere(string $whereExpression): Event
    {
        $this->event->setWhere($whereExpression);
        return $this;
    }

    /**
     * 设置菜单组
     * @param string $group_name 组名
     * @return Event
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/22
     */
    public function setMenuGroup(string $group_name): Event
    {
        $this->event->setMenuGroup($group_name);
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Event
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function __call($name, $arguments):Event
    {
        $this->event->$name(...$arguments);
        return $this;
    }
}
