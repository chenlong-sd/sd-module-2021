<?php
/**
 *
 * Administrators.php
 * User: ChenLong
 * DateTime: 2020/4/2 13:33
 */


namespace app\admin\model\system;

use app\admin\enum\AdministratorsEnumStatus;
use app\common\BaseModel;
use app\common\SdException;

/**
 * Class Administrators
 * @property $id
 * @property $name
 * @property $account
 * @property $password
 * @property $error_number
 * @property $lately_time
 * @property $error_date
 * @property $role_id
 * @property $status
 * @property $create_id
 * @property $create_time
 * @property $update_time
 * @property $delete_time
 * @package app\admin\model\system
 * @author chenlong <vip_chenlong@163.com>
 */
class Administrators extends BaseModel
{

    protected $schema = [
        'id' => 'int',
        'name' => 'varchar',
        'account' => 'varchar',
        'password' => 'varchar',
        'error_number' => 'tinyint',
        'lately_time' => 'datetime',
        'error_date' => 'date',
        'role_id' => 'varchar',
        'status' => 'tinyint',
        'create_id' => 'int',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];


    /**
     * 分类值展示处理
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function getStatusAttr($value): string
    {
        return AdministratorsEnumStatus::create($value)->getDes();
    }
}