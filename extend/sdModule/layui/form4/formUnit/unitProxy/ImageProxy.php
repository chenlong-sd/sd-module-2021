<?php
/**
 * datetime: 2021/11/19 16:41
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitProxy;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\unit\Image;
use sdModule\layui\form4\formUnit\unitEntity\ImageEntity;

/**
 * Class Image
 * @mixin Image
 * @package sdModule\layui\form4\formUnit\unitProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/19
 */
class ImageProxy extends BaseFormUnitProxy
{

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function proxyUnit(): string
    {
        return ImageEntity::class;
    }
}