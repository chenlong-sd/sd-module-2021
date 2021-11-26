<?php
/**
 * datetime: 2021/11/19 16:43
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

trait SystemResource
{
    /**
     * @var bool 是否打开系统资源
     */
    protected $isOpenSystemResource = true;

    /**
     * 关闭系统资源
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function closeSystemResource()
    {
        $this->isOpenSystemResource = false;
        return $this;
    }
}