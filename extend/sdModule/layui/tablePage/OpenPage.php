<?php
/**
 * Date: 2021/1/26 15:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


class OpenPage
{
    /**
     * @var string
     */
    private string $pageCode;

    /**
     * @var string
     */
    private string $confirm = '';

    /**
     *  constructor.
     * @param string $pageCode
     */
    public function __construct(string $pageCode)
    {
        $this->pageCode = $pageCode;
    }

    /**
     * @param string $tip
     * @param array $config
     * @return $this
     */
    public function setConfirm(string $tip, array $config = []): OpenPage
    {
        $configStr = $config ? json_encode($config, JSON_UNESCAPED_UNICODE) . ',' : '';
        $tip = TableAux::pageTitleHandle($tip);
        $this->confirm = "layer.confirm('{$tip}', {$configStr} function(index){ %s layer.close(index);});";
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->confirm) {
            return sprintf($this->confirm, $this->pageCode);
        }
        return $this->pageCode;
    }
}
