<?php
/**
 *
 * BaseModel.php
 * User: ChenLong
 * DateTime: 2020/5/13 14:51
 */


namespace app\common;

use app\admin\AdminLoginSession;
use app\admin\model\system\DataAuth;
use app\admin\service\system\AdministratorsService;
use app\common\traits\Lang;
use think\Collection;
use think\db\Query;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * Trait BaseModel
 * @mixin Model
 * @method Query join($join, string $condition = null, string $type = 'INNER', array $bind = [])
 * @method Query primevalJoin($join, string $condition = null, string $type = 'INNER', array $bind = [])
 * @package app\common
 */
abstract class BaseModel extends Model
{
    use Lang, SoftDelete;

    protected $defaultSoftDelete = 0;

    protected static function onBeforeWrite(Model $model)
    {
        $model[$model->getPk()] or $model->setAttr('create_time', datetime());
        $model->setAttr('update_time', datetime());
    }

    /**
     * 数据权限的条件数据
     * @param string $table
     * @return array|string[]
     * @throws SdException
     */
    public static function dataAuthWhere(string $table): array
    {
        $admin_id = AdminLoginSession::getId();
        if (!env('APP.DATA_AUTH') || AdministratorsService::isSuper()){
            return [];
        }
        $role_id  = AdminLoginSession::getRoleId();

        try {
            $data = DataAuth::where("administrators_id = {$admin_id} OR role_id In ({$role_id})")
                ->column('auth_id', 'table_names');
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }

        return empty($data[$table]) ? [] : explode(',', $data[$table]);
    }
}

