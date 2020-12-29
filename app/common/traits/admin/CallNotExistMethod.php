<?php
/**
 *
 * CallNotExistMethod.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/6/18 12:01
 */


namespace app\common\traits\admin;
/**
 * Trait CallNotExistMethod
 * @package app\common\controller\traits
 */
trait CallNotExistMethod
{
    /**
     * @param $method
     * @param $vars
     * @return string
     * @throws \Exception
     */
    public function __call($method, $vars)
    {
        if (substr($method, 0, 3) === 'set') {
            $property = parse_name(substr($method, 3), 0, false);
            $this->$property = $vars[0] ?? $vars;
            return $this;
        }
        return $this->fetch($method, $vars);
    }

}

