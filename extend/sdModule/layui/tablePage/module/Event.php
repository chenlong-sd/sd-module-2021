<?php
/**
 * Date: 2021/6/8 10:03
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage\module;

use app\common\SdException;
use sdModule\layui\Dom;
use sdModule\layui\Layui;
use sdModule\layui\tablePage\ListsPage;

/**
 * Class Event
 * @method Event setPrimaryBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setDangerBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setNormalBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setWarmBtn(string $title, string $icon = '', string $size = 'xs')
 * @method Event setDefaultBtn(string $title, string $icon = '', string $size = 'xs')
 * @package sdModule\layui\tablePage\module
 * @author chenlong <vip_chenlong@163.com>
 * @date 2021/6/8
 */
class Event
{
    /**
     * @var string 事件名字
     */
    public $event;

    /**
     * @var string 菜单标题
     */
    public $title = '';

    /**
     * @var string 图标
     */
    public $icon = '';

    /**
     * @var string 按钮大小
     */
    public $btnSize = 'xs';

    /**
     * @var string 按钮类型
     */
    public $btnType = 'default';

    /**
     * @var string  事件的JS代码
     */
    public $js = '';
    /**
     * @var ListsPage
     */
    private $listsPage;

    /**
     * @var bool
     */
    private $isBar;
    /**
     * @var string
     */
    public $where = '';

    /**
     * Event constructor.
     * @param ListsPage $listsPage
     * @param string $event
     * @param bool $is_bar
     */
    public function __construct(ListsPage $listsPage, string $event = '', bool $is_bar = false)
    {
        $this->event     = $event ?: 'event_' . mt_rand(1, 999);
        $this->listsPage = $listsPage;
        $this->isBar     = $is_bar;
        $this->resetEvent();
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws SdException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function __call($name, $arguments): Event
    {
        if (!preg_match('/^set(Primary|Danger|Normal|Warm|Default)Btn$/', $name, $match)) {
            throw new SdException("{$name}方法不存在");
        }
        $method = strtolower($match[1]);
        $this->setHtml($arguments[0] ?? '', $arguments[1] ?? '');
        $this->setBtn($method, $arguments[2] ?? 'xs');
        return $this->resetEvent();
    }

    /**
     * 设置事件菜单内容
     * @param string $title 菜单标题
     * @param string $icon  菜单图标
     * @return Event
     */
    public function setHtml(string $title, string $icon = ''): Event
    {
        $this->title = $title;
        $this->icon  = $icon;
        return $this->resetEvent();
    }

    /**
     * 设置按钮
     * @param string $type
     * @param string $size
     * @return Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    public function setBtn(string $type, string $size = 'xs'): Event
    {
        $this->btnType = $type;
        $this->btnSize = $size;
        return $this->resetEvent();
    }

    /**
     * 设置事件JS
     * @param string $js
     * @return Event
     */
    public function setJs(string $js): Event
    {
        $this->js = $js;
        return $this->resetEvent();
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
        $this->where = $whereExpression;
        return $this;
    }

    /**
     * 重置事件配置
     * @return Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/8
     */
    private function resetEvent(): Event
    {
        $event      = $this;
        $event_name = $event->event;
        $is_bar     = $this->isBar;
        (function () use ($event, $event_name, $is_bar) {
            $property   = $is_bar ? 'barEvent' : 'event';
            $this->$property[$event_name] = $event;
        })->call($this->listsPage);
        return $event;
    }
}
