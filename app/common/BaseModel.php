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
use think\Model;

/**
 * Trait BaseModel
 * @mixin Model
 * @package app\common
 */
trait BaseModel
{
    use Lang;

    /**
     * 根据ID获取数据
     * @param int $id
     * @return self
     * @throws SdException
     */
    public static function getDataById(int $id)
    {
        return self::getDataByWhere([strtr(config('admin.primary_key'), ['{table}' => '']) => $id], true);
    }

    /**
     * 根据条件获取数据
     * @param array $where
     * @param bool $is_one
     * @return mixed|Collection|Model|self
     * @throws SdException
     */
    public static function getDataByWhere(array $where, bool $is_one = false)
    {
        try {
            return $is_one ? self::addSoftDelWhere($where)->findOrEmpty() : self::addSoftDelWhere($where)->select();
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 静态添加软删除条件
     * @param array $where
     * @param string $alias
     * @return mixed|Collection|Model|self
     */
    public static function addSoftDelWhere(array $where = [], $alias = 'i')
    {
        $query = self::alias($alias);

        if ($soft_delete_where = self::getSoftDeleteData($alias)){
            $query->where($soft_delete_where);
        }

        if ($where){
            $query->where($where);
        }
        return $query;
    }

    /**
     * 软删除
     * @param array $where
     * @return int|string
     * @throws \Throwable
     */
    public static function softDelete(array $where)
    {
        try {
            return self::where($where)->update(self::getSoftDeleteData(null, false));
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * 获取软删除数据
     * @param string|null $alias 别名
     * @param bool $is_default 是否是取默认值
     * @return array
     */
    public static function getSoftDeleteData(string $alias = null, bool $is_default = true)
    {
        $check   = config('admin.soft_delete');
        $field   = $check['field'] ?? '';
        $default = $check['default'] ?? '';
        $value   = empty($check['value']) || $check['value'] === 'timestamp' ? time() : $check['value'];

        return $check ? [
            ($alias ? $alias . '.' : '') . $field => $is_default ? $default : $value
        ] : [];
    }

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
            $data = DataAuth::where(['delete_time' => 0])
                ->where("administrators_id = {$admin_id} OR role_id In ({$role_id})")
                ->column('auth_id', 'table_names');
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }

        return empty($data[$table]) ? [] : explode(',', $data[$table]);
    }
}

