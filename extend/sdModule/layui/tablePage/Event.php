<?php
/**
 * Date: 2021/1/27 9:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


use app\common\SdException;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;

/**
 * Class Event
 * @method Event setPrimaryBtn(string $title, string $icon = '', string $size = '')
 * @method Event setDangerBtn(string $title, string $icon = '', string $size = '')
 * @method Event setNormalBtn(string $title, string $icon = '', string $size = '')
 * @method Event setWarmBtn(string $title, string $icon = '', string $size = '')
 * @method Event setDefaultBtn(string $title, string $icon = '', string $size = '')
 * @package sdModule\layui\tablePage
 */
class Event
{
    private TablePage $page;
    /**
     * @var bool
     */
    private bool $isBar;
    /**
     * @var string|null
     */
    private ?string $event;

    /**
     * Event constructor.
     * @param TablePage $page
     * @param string $event
     * @param bool $isBar
     */
    public function __construct(TablePage $page, string $event, bool $isBar = false)
    {
        $this->page  = $page;
        $this->isBar = $isBar;
        $this->event = $event;
        $this->setEvent($event);
    }

    /**
     * 设置 Event
     * @param string $event
     */
    private function setEvent(string $event)
    {
        $fn = $this->isBar
            ? fn() => $this->toolbarEvent[] = $event
            : fn() => $this->toolEvent[] = $event;
        $fn->call($this->page);
    }

    /**
     * @param $html
     * @return $this
     */
    public function setHtml($html): Event
    {
        $event = $this->event;
        $fn = $this->isBar
            ? fn() => $this->toolbarEventHtml[$event] = $html
            : fn() => $this->toolEventHtml[$event] = $html;
        $fn->call($this->page);
        return $this;
    }

    /**
     * @param $js
     * @return $this
     */
    public function setJs(string $js): Event
    {
        $event = $this->event;
        $fn = $this->isBar
            ? fn() => $this->toolbarEventJs[$event] = $js
            : fn() => $this->toolEventJs[$event] = $js;
        $fn->call($this->page);
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws SdException
     */
    public function __call($name, $arguments): Event
    {
        if (!preg_match('/^set(Primary|Danger|Normal|Warm|Default)Btn$/', $name, $match)) {
            throw new SdException("{$name}方法不存在");
        }
        $method = strtolower($match[1]);
        return $this->setHtml(Layui::button($arguments[0], $arguments[1] ?? '')->setEvent($this->event)->$method($arguments[2] ?? ''));
    }
}
