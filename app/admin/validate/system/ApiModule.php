<?php
/**
 *
 * ApiModule.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:08:43
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * Class ApiModule
 * @package app\common\validate\ApiModule
 * @author chenlong <vip_chenlong@163.com>
 */
class ApiModule extends BaseValidate
{
    protected $rule = [
        'id|接口模块' => 'require|number',
        'item_name|模块名' => 'require',
        'describe|描述' => 'require',
    ];

    protected $scene = [
        'create' => ['item_name', 'describe'],
        'update' => ['id', 'item_name', 'describe'],
    ];
}
