<?php
/**
 * Date: 2021/1/20 13:51
 * User: chenlong <vip_chenlong@163.com>
 */

namespace weChat\apiv3;

/**
 * 参数基类
 * Class BaseParams
 * @package weChat\apiv3
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/7
 */
class BaseParams implements \ArrayAccess,ToArray
{
    /**
     * @var array
     */
    protected $param = [];

    /**
     * @param mixed $offset
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function offsetExists($offset)
    {
        return isset($this->param[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws ParameterDoesNotExistException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)){
            throw new ParameterDoesNotExistException($this, $offset);
        }
        return $this->param[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function offsetSet($offset, $value)
    {
        $this->param[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function offsetUnset($offset)
    {
        unset($this->param[$offset]);
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function toArray(): array
    {
        return array_map(function ($v) {
            return $v instanceof BaseParams ? $v->toArray() : $v;
        }, $this->param);
    }

    /**
     * @param $name
     * @param $value
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __set($name, $value)
    {
        $this->param[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     * @throws ParameterDoesNotExistException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/8
     */
    public function __get($name)
    {
        if (!$this->offsetExists($name)){
            throw new ParameterDoesNotExistException($this, $name);
        }
        return $this->param[$name];
    }
}
