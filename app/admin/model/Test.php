<?php
/**
 *
 * Test.php
 * DateTime: 2021-12-04 10:34:45
 */

namespace app\admin\model;

use app\common\model\Test as commonTest;
use app\common\enum\TestEnumStatus;

/**
 * 测试表 模型
 * Class Test
 * @package app\admin\model\Test
 */
class Test extends commonTest
{

    
    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getStatusAttr($value): string
    {
        return TestEnumStatus::create($value)->getDes();
    }


}
