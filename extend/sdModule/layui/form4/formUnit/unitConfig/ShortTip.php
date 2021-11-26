<?php
/**
 * datetime: 2021/11/19 1:40
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

trait ShortTip
{
    protected $shortTip = '';

    /**
     * @param string $shortTip
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function shortTip(string $shortTip)
    {
        $this->shortTip = $shortTip;
        return $this;
    }


}
