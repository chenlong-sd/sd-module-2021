<?php
/**
 * datetime: 2021/9/18 21:32
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\error;

/**
 * 数据列展示类型不支持
 * Class ColumnTypeIsNotSupportedException
 * @package sdModule\layui\lists\error
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/18
 */
class ColumnTypeIsNotSupportedException extends \Exception
{
    /**
     * ColumnTypeIsNotSupportedException constructor.
     * @param string $column_type 类型名
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function __construct(string $column_type)
    {
        return parent::__construct("does not support column type $column_type" );
    }
}
