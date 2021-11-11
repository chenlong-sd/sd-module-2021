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
use app\common\Enum;
use app\common\SdException;
use sdModule\layui\Layui;

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

    /**
     * 数据权限设置
     * @param int $id
     * @param array $request
     * @throws SdException
     */
    public static function dataAuthSet(int $id, array $request)
    {
        $data = [];
        foreach ($request as $name => $value) {
            if (preg_match('/^data_auth_table_/', $name)){
                $table = strtr($name, ['data_auth_table_' => '']);
                $data[$table] = [
                    'administrators_id' => $id,
                    'table_names'       => $table,
                    'auth_id'           => $value,
                    'create_time'       => datetime(),
                    'update_time'       => datetime(),
                ];
            }
        }

        $have = DataAuth::where(['administrators_id' => $id])->column('table_names', 'id');
        if (($update = data_only($data, $have))){
            foreach ($update as $name => $value){
                if (!DataAuth::update($value, ['id' => array_search($name,  $have)])){
                    throw new SdException('权限更新失败');
                }
            }
        }

        if (($insert_into = data_except($data, $have)) && !DataAuth::insertAll($insert_into)) {
            throw new SdException('权限新增失败！');
        }
    }
}