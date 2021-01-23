<?php
/**
 *
 * Role.php
 * User: ChenLong
 * DateTime: 2020/4/3 15:22
 */


namespace app\admin\model\system;


use app\common\BaseModel;
use app\common\SdException;
use sdModule\layui\defaultForm\FormData;
use sdModule\layui\Layui;
use sdModule\layui\TablePage;
use sdModule\layuiSearch\generate\TimeRange;
use sdModule\layuiSearch\SearchForm;
use think\Model;

/**
 * Class Role
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
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'int',
    ];

    /**
     * 获取创建角色的管理员
     * @param $role_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCreateAdministrators($role_id)
    {
        return self::find($role_id)->administrators_id;
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
                    'role_id'     => $id,
                    'table_names' => $table,
                    'auth_id'     => $value,
                    'create_time' => datetime(),
                    'update_time' => datetime(),
                ];
            }
        }

        $have = DataAuth::where(['role_id' => $id])->column('table_names', 'id');
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
