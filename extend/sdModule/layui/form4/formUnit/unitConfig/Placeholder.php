<?php
/**
 * datetime: 2021/11/19 1:48
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

trait Placeholder
{
    /**
     * @var string 表单placeholder
     */
    protected $placeholder = '请输入';

    /**
     * @param string $placeholder
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function placeholder(string $placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

}
