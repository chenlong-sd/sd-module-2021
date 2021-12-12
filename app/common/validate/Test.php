<?php
/**
 *
 * Test.php
 * DateTime: 2021-12-04 10:34:46
 */

namespace app\common\validate;

use app\common\BaseValidate;

/**
 * 测试表 验证器
 * Class Test
 * @package app\common\validate\Test
 */
class Test extends BaseValidate
{
    protected $rule = [
        'id|测试表' => 'require|number',
        'title|标题' => 'require',
        'cover|封面' => 'require',
        'show_images|展示图' => 'require',
        'intro|简介' => 'require',
        'status|状态' => 'require|number|in:1,2',
        'administrators_id|管理员' => 'require|number',
        'pid|上级' => 'require|number',
        'content|详情' => 'require',
    ];

    protected $scene = [
        'create' => ['title', 'cover', 'show_images', 'intro', 'status', 'administrators_id', 'pid', 'content'],
        'update' => ['id', 'title', 'cover', 'show_images', 'intro', 'status', 'administrators_id', 'pid', 'content'],
    ];
}
