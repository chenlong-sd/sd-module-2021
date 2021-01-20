<?php
/**
 * Date: 2021/1/20 13:51
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\pay;


trait ArrayAccess
{

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
