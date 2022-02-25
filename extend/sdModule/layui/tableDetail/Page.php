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
    /**
     * @var string
     */
    private $page_name;
    /**
     * @var string
     */
    private $root = '';
    /**
     * @var string
     */
    private $customJs = '';
    /**
     * @var array
     */
    private $event = [];
    /**
     * @var array
     */
    private $afterEvent = [];
    /**
     * @var array
     */
    private $eventJs = [];

    /**
     * @var array | Table[]
     */
    private $table = [];

    /**
     * @var array
     */
    private $loadJs = [];

    private $css = '';

    private $loadCss = [];

    
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
     * @param $jsUrl
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/2/23
     */
    public function addLoadJs($jsUrl)
    {
        is_string($jsUrl) ? $this->loadJs[] = $jsUrl : $this->loadJs = array_merge($this->loadJs, $jsUrl);
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

    /**
     * @param array $loadCss
     * @return Page
     */
    public function setLoadCss(array $loadCss): Page
    {
        $this->loadCss = $loadCss;
        return $this;
    }

    /**
     * @param string $css
     * @return Page
     */
    public function setCss(string $css): Page
    {
        $this->css = $css;
        return $this;
    }
}
