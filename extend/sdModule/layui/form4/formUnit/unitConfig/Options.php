<?php
/**
 * datetime: 2021/11/19 1:38
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

use sdModule\layui\Dom;

trait Options
{
    protected $options = [];

    /**
     * 设置表单的可选项
     * @param array  $options 可选项值
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

}
