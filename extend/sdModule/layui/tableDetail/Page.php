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
    }

    /**
     * @param string $event
     * @return Event
     */
    public function addAfterEvent(string $event): Event
    {
        return new Event($this, $event, true);
    }

    /**
     * @param string $event
     * @param $url
     * @param array $data
     * @return $this
     */
    public function setEventAjaxRequest(string $event, string $url, array $data): Page
    {
        $data = json_encode($data);
        $this->eventJs[] = "$event(){ sc_event('{$url}', $data) },";
        return $this;
    }

    /**
     * @param string $js
     * @return Page
     */
    public function customJs(string $js): Page
    {
        $this->customJs = $js;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        include_once __DIR__ . '/template.php';
        return '   ';
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        if ($this->root) {
            return $this->root;
        }
        $this->root = preg_replace('/\/+/', '/', strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']) . '/') ?: '';
        return $this->root;
    }

    public function lang($lang)
    {
        return lang($lang);
    }
}
