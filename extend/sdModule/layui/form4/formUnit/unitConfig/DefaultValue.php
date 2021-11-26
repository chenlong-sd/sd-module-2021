<?php
/**
 * datetime: 2021/11/19 1:46
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

trait DefaultValue
{
    /**
     * @var mixed 表单默认值
     */
    protected $defaultValue = null;

    /**
     * @param $defaultValue
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function defaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }
}