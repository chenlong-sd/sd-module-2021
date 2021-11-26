<?php
/**
 *
 * NewDictionary.php
 * DateTime: 2021-11-24 23:14:45
 */

namespace app\admin\validate\system;

use app\common\BaseValidate;

/**
 * 新字典表 验证器
 * Class NewDictionary
 * @package app\common\validate\system\NewDictionary
 */
class NewDictionary extends BaseValidate
{
    protected $rule = [
        'id|新字典表' => 'require|number',
        'type|类型' => 'require|number|in:1,2',
        'sign|字典标识ID' => 'require',
        'name|字典名称' => 'require',
        'image|增强状态下的图片' => 'require',
        'introduce|增强状态下的简介' => 'require',
    ];

    protected $scene = [
        'create' => ['type', 'sign', 'name',],
        'update' => ['id', 'type', 'sign', 'name',],
    ];
}
