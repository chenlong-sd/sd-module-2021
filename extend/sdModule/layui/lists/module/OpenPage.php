<?php
/**
 * Date: 2021/1/26 15:13
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\lists\module;

/**
 * 打开页面
 * Class OpenPage
 * @package sdModule\layui\lists\module
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/21
 */
class OpenPage
{
    use EventHandleParamHandle;

    /**
     * @var string js代码
     */
    private $code;

    /**
     * @var string 确认弹窗的js代码模板
     */
    private $confirm = '%s';

    /**
     * @var string 权限控制
     */
    private $power = 'normal';

    /**
     * @var array|string 页面路径
     */
    private $url;

    /**
     * @var string 页面标题
     */
    private $title;

    /**
     * OpenPage constructor.
     * @param string|array $url 打开页面的路径
     * @param string $title 打开页面的标题
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function __construct($url, string $title)
    {
        $this->title = self::paramReplace($title);
        $this->url   = self::url($url);
        $this->power = access_control(is_array($url) ? current($url) : $url) ? 'normal' : 'false';
    }

    /**
     * 弹窗页面
     * @param array $config
     * @param string $window 打开页面的页面对象，默认为当前页面
     * @return OpenPage
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function popUps(array $config = [], string $window = 'window'): OpenPage
    {
        $config   = json_encode($config, JSON_UNESCAPED_UNICODE);
        $format   = "custom.frame(%s, '%s', %s, %s);";

        $this->code = sprintf($format, $this->url, $this->title, $config, $window);
        return $this;
    }

    /**
     * 打开选项卡模式的页面
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/21
     */
    public function tabs(): OpenPage
    {
        $format      = "custom.openTabsPage(%s + '%s__sc_tab__=1', '%s')";
        $link_symbol = strpos($this->url, '?') === false ? '?' : '&';

        $this->code = sprintf($format, $this->url, $link_symbol, $this->title);
        return $this;
    }

    /**
     * 设置确认弹窗
     * @param string $tip
     * @param array $config
     * @return $this
     */
    public function setConfirm(string $tip, array $config = []): OpenPage
    {
        $configStr = $config ? json_encode($config, JSON_UNESCAPED_UNICODE) . ',' : '';
        $tip       = self::paramReplace($tip);

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

        return sprintf($this->confirm, $this->code);
    }
}
