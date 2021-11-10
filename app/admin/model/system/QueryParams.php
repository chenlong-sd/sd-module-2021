<?php
/**
 *
 * QueryParams.php
 * User: ChenLong
 * DateTime: 2020-12-11 11:10:23
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use sdModule\layui\Layui;


/**
 * Class QueryParams
 * @package app\admin\controller\QueryParams
 * @author chenlong <vip_chenlong@163.com>
 */
class QueryParams  extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'api_id' => 'int',
        'method' => 'tinyint',
        'param_type' => 'tinyint',
        'name' => 'varchar',
        'test_value' => 'varchar',
        'describe' => 'varchar',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',

    ];

}
