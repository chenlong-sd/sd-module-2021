<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2020-11-25 17:38:39
 */

namespace app\admin\model;

use \app\common\model\Test as commonTest;
use sdModule\layui\TablePage;


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
