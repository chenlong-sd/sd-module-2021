<?php
/**
 * Date: 2021/1/27 9:22
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


use app\common\SdException;
use sdModule\layui\Dom;
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
    public function __construct(TablePage $page, string $event = '', bool $isBar = false)
    {
        $this->page  = $page;
        $this->isBar = $isBar;
        $this->event = $event ?: "event_" . mt_rand(1, 100);
        $this->setEvent($this->event);
    }

    /**
     * 设置 Event
     * @param string $event
     */
    private function setEvent(string $event)
    {
        $this->setPageAttr($this->isBar ? 'toolbarEvent' : 'toolEvent', $event);
    }

    /**
     * @param $html
     * @return $this
     */
    public function setHtml($html): Event
    {
        $this->setPageAttr($this->isBar ? 'toolbarEventHtml' : 'toolEventHtml', $html);
        return $this;
    }

    /**
     * @param $js
     * @return $this
     */
    public function setJs(string $js): Event
    {
        $this->setPageAttr($this->isBar ? 'toolbarEventJs' : 'toolEventJs', $js);
        return $this;
    }

    /**
     * 设置展示条件
     * @param string $where 条件
     * @param false $is_hidden 是否隐藏按钮
     * @return Event
     */
    public function setWhere(string $where, bool $is_hidden = false): Event
    {
        $this->setPageAttr('eventWhere', $where);
        $this->setPageAttr('whereNotMeet', $is_hidden);
        return $this;
    }

    /**
     * 下拉标题设置
     * @param string $title 菜单标题
     * @param string $icon 菜单图标
     * @return Event
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/7
     */
    public function downTitle(string $title, string $icon = ''): Event
    {
        if ($icon) {
            if (substr($icon, 0, 9) === 'layui-icon'){
                $icon = substr($icon, 9);
            }
            $icon = Dom::create('i')->addClass("layui-icon layui-icon-{$icon}");
        }

        $html = Dom::create('span')->addContent($icon)->addContent("{{d.title}}");
        $this->setPageAttr('downMenuData', [
            'title' => $title,
            'templet' => (string)$html,
            'id' => 'menu' . rand(1, 99999),
            'event' => $this->event
        ]);
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

    /**
     * 设置page的属性
     * @param string|array $attr
     * @param null $value
     */
    private function setPageAttr($attr, $value = null)
    {
        $event = $this->event;
        $fn = function () use ($event, $attr, $value){
            if (is_array($attr)){
                foreach ($attr as $at => $v){
                    $this->$at[$event] = $v;
                }
            }else{
                $this->$attr[$event] = $value;
            }
        };
        $fn->call($this->page);
    }

}
