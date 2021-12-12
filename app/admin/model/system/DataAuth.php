<?php
/**
 *
 * DataAuth.php
 * User: ChenLong
 * DateTime: 2020-11-26 18:12:25
 */

namespace app\admin\model\system;

use app\common\BaseModel;
use think\facade\Config;
use think\facade\Db;

/**
 * Class DataAuth
 * @package app\admin\model\system
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/9
 */
class DataAuth extends BaseModel
{

    protected $schema = [
        'id'           => 'int',
        'role_id'      => 'int',
        'table_names'  => 'varchar',
        'auth_id'      => 'varchar',
        'create_time'  => 'datetime',
        'update_time'  => 'datetime',
        'delete_time'  => 'int',

    ];


    /**
     * 数据权限的数据获取
     * @param string $table
     * @return array
     */
    public static function canBeSetData(string $table): array
    {
        $data_auth = array_column(Config::get('admin.data_auth', []), null, 'table');
        if (empty($data_auth[$table])){
            return [];
        }

        try {
            return Db::name($table)->where('delete_time', 0)->column($data_auth[$table]['show_field'], 'id');
        } catch (\Exception $exception) {
            return [];
        }
    }

}
