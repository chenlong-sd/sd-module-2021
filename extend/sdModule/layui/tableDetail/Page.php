<?php
/**
 * Date: 2020/12/4 17:10
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tableDetail;

/**
 * Class Page
 * @package sdModule\layui\tableDetail
 */
class Page
{
    private string $page_name;

    private string $root = '';

    private string $customJs = '';

    private array $event = [];

    private array $afterEvent = [];

    private array $eventJs = [];

    /**
     * @var array | Table[]
     */
    private array $table = [];

    
    public function __construct(string $page_name)
    {
        $this->page_name = $page_name;
    }

    /***
     * @param Table $table
     * @return $this
     */
    public function addTable(Table $table)
    {
        $this->table[] = $table;
        return $this;
    }

    /**
     * @param string $event
     * @return Event
     */
    public function addEvent(string $event): Event
    {
        return new Event($this, $event);
//        $this->event[$event] = $html;
//        return $this;
    }

    /**
     * @param string $event
     * @return Event
     */
    public function addAfterEvent(string $event)
    {
        return new Event($this, $event, true);
//        $this->afterEvent[$event] = $html;
//        return $this;
    }

    /**
     * @param string $event
     * @param $url
     * @param array $data
     * @return $this
     */
    public function setEventAjaxRequest(string $event, string $url, array $data)
    {
        $data = json_encode($data);
        $this->eventJs[] = "$event(){ sc_event('{$url}', $data) },";
        return $this;
    }

    /**
     * @param string $js
     * @return Page
     */
    public function customJs(string $js)
    {
        $this->customJs = $js;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        include_once __DIR__ . '/template.php';
        return '   ';
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        if ($this->root) {
            return $this->root;
        }
        return $this->root = strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']) . '/';
    }

    public function lang($lang)
    {
        return lang($lang);
    }
}
