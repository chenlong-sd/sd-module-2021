<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2021-01-25 12:06:59
 */

namespace app\admin\model;

use \app\common\model\Test as commonTest;


/**
 * Class Test
 * @package app\admin\model\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends commonTest
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     */   
    public function getStatusAttr($value)
    {
        $field = self::getStatusSc();
        
        return $field[$value] ?? $value;
    }


}
