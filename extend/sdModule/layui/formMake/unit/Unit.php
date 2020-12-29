<?php
/**
 *
 * Unit.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:21
 */


namespace sdModule\layui\formMake\unit;

/**
 * Interface Unit
 * @package sdModule\layui\formMake\unit
 * @author chenlong <vip_chenlong@163.com>
 */
interface Unit
{
    /**
     * html 代码
     * @param string $label
     * @param string $name
     * @return string
     */
    public function htmlCode(string $label, string $name):string ;

    /**
     * js 代码
     * @param string $name
     * @return string
     */
    public function jsCode(string $name):string ;
}

