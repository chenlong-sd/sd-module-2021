<?php
/**
 * Date: 2021/3/23 11:47
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tableDetail;


use app\common\SdException;
use sdModule\layui\Layui;

/**
 * Class Event
 * @method Event setPrimaryBtn(string $title, string $icon = '', string $size = '')
 * @method Event setDangerBtn(string $title, string $icon = '', string $size = '')
 * @method Event setNormalBtn(string $title, string $icon = '', string $size = '')
 * @method Event setWarmBtn(string $title, string $icon = '', string $size = '')
 * @method Event setDefaultBtn(string $title, string $icon = '', string $size = '')
 * @package sdModule\layui\tableDetail
 */
class Event
{
    /**
     * @var Page
     */
    private $page;
    /**
     * @var string
     */
    private $event;
    /**
     * @var bool
     */
    private $isAfter;

    /**
     * Event constructor.
     * @param Page $page
     * @param string $event
     * @param bool $is_after
     */
    public function __construct(Page $page, string $event, bool $is_after = false)
    {
        $this->page  = $page;
        $this->event = $event;
        $this->isAfter = $is_after;
    }

    /**
     * @param string $html
     * @return Event
     */
    public function setHtml(string $html): Event
    {
        $this->setPageAttr($this->isAfter ? 'afterEvent' : 'event', $html);
        return $this;
    }

    /**
     * 设置请求
     * @param string $request_url
     * @param array $request_data
     * @return $this
     */
    public function setRequest(string $request_url, array $request_data): Event
    {
        $data = json_encode($request_data, JSON_UNESCAPED_UNICODE);
        $this->setPageAttr('eventJs', "{$this->event}(){ sc_event('{$request_url}', $data) },");
        return $this;
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

    /**
     * @param $name
     * @param $arguments
     * @return Event
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
