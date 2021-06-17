<?php
/**
 * Date: 2021/1/26 15:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage\module;


class OpenPage
{
    /**
     * @var string
     */
    private $pageCode;

    /**
     * @var string
     */
    private $confirm = '';

    /**
     * @var string 权限控制
     */
    private $power = 'normal';

    /**
     *  constructor.
     * @param string $pageCode
     * @param bool $power
     */
    public function __construct(string $pageCode, bool $power)
    {
        $this->pageCode = $pageCode;
        $this->power    = $power ? 'normal' : 'false';
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
        if ($this->power === 'false') {
            return $this->power;
        }

        if ($this->confirm) {
            return sprintf($this->confirm, $this->pageCode);
        }
        return $this->pageCode;
    }
}
