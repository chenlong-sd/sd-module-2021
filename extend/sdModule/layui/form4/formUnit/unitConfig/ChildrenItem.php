<?php
/**
 * datetime: 2021/11/19 17:06
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Trait ChildrenItem
 * @package sdModule\layui\form4\formUnit\unitConfig
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/19
 */
trait ChildrenItem
{
    /**
     * @var array|BaseFormUnitProxy[]|UnitI[]
     */
    protected $childrenItem = [];

    /**
     * @param array|BaseFormUnitProxy[]|UnitI[] $unit
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function addChildrenItem(...$unit)
    {
        $this->childrenItem = array_merge($this->childrenItem, $unit);
        return $this;
    }
}
