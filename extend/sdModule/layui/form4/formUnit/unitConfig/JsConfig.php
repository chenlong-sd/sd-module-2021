<?php
/**
 * datetime: 2021/11/19 2:06
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;


trait JsConfig
{
    /**
     * @var array 表单元素的js配置
     */
    protected $jsConfig = [];

    /**
     * @param array $jsConfig
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function jsConfig(array $jsConfig)
    {
        $this->jsConfig = array_merge($this->jsConfig, $jsConfig);
        return $this;
    }

}