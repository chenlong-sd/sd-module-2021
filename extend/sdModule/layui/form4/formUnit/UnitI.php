<?php
/**
 * datetime: 2021/11/18 11:51
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit;

use sdModule\layui\Dom;

/**
 * Interface UnitI
 * @package sdModule\layui\form4\formUnit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
interface UnitI
{

    /**
     * @param string $scene 表单场景
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom;

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getJs(): string;

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getCss(): string;

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getLoadJs(): array;
}
