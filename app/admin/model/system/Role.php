<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020/4/3 15:22
 */


namespace app\admin\model\system;


use app\admin\AdminLoginSession;
use app\common\BaseModel;
use app\common\SdException;

/**
 * Class Role
 * @property $id
 * @property $role
 * @property $pid
 * @property $describe
 * @property $administrators_id
 * @property $assign_table
 * @property $open_table
 * @property $open_id
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Role extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'role' => 'varchar',
        'pid' => 'int',
        'describe' => 'varchar',
        'administrators_id' => 'int',
        'assign_table' => 'int',
        'open_table' => 'int',
        'open_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * 返回可用角色的选择数据
     * @param string|null $table
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/10
     */
    public static function selectData(string $table = null): array
    {
        $where = $table ? ['assign_table' => $table] : [];
        if (AdminLoginSession::isAdmin()) {
            $where['administrators_id'] = AdminLoginSession::getId();
        }else{
            if (AdminLoginSession::getTable() == $table) {
                $where = array_merge($where, ['open_id' => AdminLoginSession::getId(), 'open_table' => AdminLoginSession::getTable()]);
            }
        }
        return self::where($where)->column('role', 'id');
    }
}
