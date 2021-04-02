<?php
/**
 * Date: 2021/1/21 18:26
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;


use think\Collection;
use think\db\Query;

class BaseQuery extends Query
{

    /**
     * @param mixed $join
     * @param string|null $condition
     * @param string $type
     * @param array $bind
     * @return $this|BaseQuery
     */
    public function join($join, string $condition = null, string $type = 'INNER', array $bind = [])
    {
        parent::join($join, $condition, $type, $bind);
        $joinOptions = array_pop($this->options['join']);
        $alias = current(current($joinOptions));
        if (strpos($joinOptions[2], "{$alias}.delete_time") === false) {
            $joinOptions[2] .= sprintf(" AND %s.%s = %s", $alias, 'delete_time', 0);
        }
        $this->options['join'][] = $joinOptions;
        return $this;
    }
    /**
     * 原生join 不加 delete_time
     * @param $join
     * @param string|null $condition
     * @param string $type
     * @param array $bind
     * @return BaseQuery
     */
    public function primevalJoin($join, string $condition = null, string $type = 'INNER', array $bind = [])
    {
        return parent::join($join, $condition, $type, $bind);
    }
}
