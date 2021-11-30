<?php
/**
 * Date: 2021/6/8 10:03
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\lists\module;

use app\common\SdException;
use sdModule\layui\lists\PageData;

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
     * @var PageData
     */
    private $pageData;

    /**
     * @var bool
     */
    private $isBar;
    /**
     * @var string
     */
    public $where = '';

    /**
     * @var string
     */
    public $menuGroup = '';

    /**
     * Event constructor.
     * @param PageData $page_data
     * @param string $event
     * @param bool $is_bar
     */
    public function __construct(PageData $page_data, string $event = '', bool $is_bar = false)
    {
        $this->event     = $event ?: 'event_' . mt_rand(1, 9999);
        $this->pageData  = $page_data;
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
        $btn_type  = strtolower($match[1]);
        $btn_title = $arguments[0];
        $btn_icon  = $arguments[1] ?? '';
        $btn_size  = $arguments[2] ?? 'xs';

        $this->setBtn($btn_title, $btn_type, $btn_icon, $btn_size);
        return $this->resetEvent();
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
        $this->btnType = $type;
        $this->btnSize = $size;
        $this->title  = $title;
        $this->icon   = $icon;
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
        return $this->resetEvent();
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
        $this->menuGroup = $group_name;
        return $this->resetEvent();
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
        $temp_call  = function () use ($event, $event_name, $is_bar) {
            $event_type = $is_bar ? 'barEvent' : 'event';
            if ($event->js === 'false') {
                unset($this->data[$event_type][$event_name]);
            }else{
                $this->data[$event_type][$event_name] = $event;
            }
        };
        $temp_call->call($this->pageData);
        return $event;
    }
}
