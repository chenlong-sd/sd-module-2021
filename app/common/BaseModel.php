<?php
/**
 *
 * BaseModel.php
 * User: ChenLong
 * DateTime: 2020/5/13 14:51
 */


namespace app\common;

use app\admin\model\system\DataAuth;
use app\common\traits\Lang;
use think\Collection;
use think\db\Query;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * Trait BaseModel
 * @mixin Model
 * @method Query join($join, string $condition = null, string $type = 'INNER', array $bind = [])
 * @package app\common
 */
class BaseModel extends Model
{
    use Lang, SoftDelete;

    protected $defaultSoftDelete = 0;

    /**
     * 数据权限的条件数据
     * @param string $table
     * @return array|string[]
     * @throws SdException
     */
    public static function dataAuthWhere(string $table)
    {
        if (!env('APP.DATA_AUTH') || ($admin_id = admin_session('id')) == 1){
            return [];
        }
        $role_id  = admin_session('role_id');

        try {
            $data = DataAuth::where("administrators_id = {$admin_id} OR role_id In ({$role_id})")
                ->column('auth_id', 'table_names');
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }

        return empty($data[$table]) ? [] : explode(',', $data[$table]);
    }
}

