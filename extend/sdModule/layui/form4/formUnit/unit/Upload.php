<?php
/**
 * datetime: 2021/11/25 15:35
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\DefaultValue;
use sdModule\layui\form4\formUnit\unitConfig\ShortTip;

abstract class Upload extends BaseFormUnit
{
    use DefaultValue, ShortTip;

    protected $type = 'all';

    /**
     * 上传类型
     * @param string $type
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function uploadType(string $type)
    {
        $this->type = $type;
    }

}