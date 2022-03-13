<?php
/**
 * datetime: 2021/11/9 11:53
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin\enum;

use app\common\Enum;

/**
 * Class ApiType
 * @package app\admin\enum\system
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/9
 */
class ApiEnumParamType extends Enum
{
    const GET    = '1'; // get参数类型
    const POST   = '2'; // post参数类型
    const HEADER = '3'; // header参数类型

    /**
     * 设置描述映射
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/9
     */
    protected static function map(): array
    {
        return [
            self::GET    => 'GET',
            self::POST   => 'POST',
            self::HEADER => 'HEADER'
        ];
    }


}
