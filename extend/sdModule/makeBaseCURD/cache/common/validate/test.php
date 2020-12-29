<?php
/**
 *
 * Test.php
 * User: ChenLong
 * DateTime: 2020-10-20 18:16:21
 */

namespace app\common\validate;

use think\Validate;

/**
 * Class Test
 * @package app\common\validate\Test
 * @author chenlong <vip_chenlong@163.com>
 */
class Test extends Validate
{
    protected $rule = [
        'id|测试表' => 'require|number',
        'title|标题' => 'require',
        'cover|封面' => 'require',
        'show_images|展示图' => 'require',
        'intro|简介' => 'require',
        'status|状态' => 'require|number|in:0,1',
        'administrators_id|管理员' => 'require|number',
        'pid|上级' => 'require|number',
        'content|详情' => 'require',
    ];

    protected $scene = [
        'add' => ['title', 'cover', 'show_images', 'intro', 'status', 'administrators_id', 'pid', 'content'],
        'edit' => ['id', 'title', 'cover', 'show_images', 'intro', 'status', 'administrators_id', 'pid', 'content'],
    ];
}
