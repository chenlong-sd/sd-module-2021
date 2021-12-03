<?php
/**
 *
 * QuickOperation.php
 * DateTime: 2021-12-03 21:05:04
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use app\admin\enum\QuickOperationEnumIsShow;

/**
 * 快捷操作 模型
 * Class QuickOperation
 * @property $id
 * @property $route_id
 * @property $is_show
 * @property $administrators_id
 * @property $open_table
 * @property $coordinate
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\common\model\system\QuickOperation
 * @author chenlong <vip_chenlong@163.com>
 */
class QuickOperation extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'route_id' => 'int',
        'is_show' => 'tinyint',
        'administrators_id' => 'int',
        'coordinate' => 'int',
        'open_table'  => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',

    ];

    /**
     * 展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */   
    public function getIsShowAttr($value): string
    {
        return QuickOperationEnumIsShow::create($value)->getDes();
    }


}
