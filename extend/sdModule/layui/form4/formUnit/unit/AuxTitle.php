<?php
/**
 * datetime: 2021/11/25 11:50
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;

/**
 * Class AuxTitle
 * @package sdModule\layui\form4\formUnit\unit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
abstract class AuxTitle extends BaseFormUnit
{
    /**
     * 纯粹只显示设置内容，相当于自定义
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function bare(): AuxTitle
    {
        $this->label = '__';
        return $this;
    }

    /**
     * 灰色背景显示 标签显示（默认
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function grey(): AuxTitle
    {
        $this->label = 'grey';
        return $this;
    }

    /**
     * 白色背景 标签显示
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function white(): AuxTitle
    {
        $this->label = 'white';
        return $this;
    }

    /**
     * 横线型 标签显示
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function line(): AuxTitle
    {
        $this->label = 'line';
        return $this;
    }

    /**
     * H3 标签显示
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function h3(): AuxTitle
    {
        $this->label = 'h3';
        return $this;
    }
}
