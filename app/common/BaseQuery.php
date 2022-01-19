<?php
/**
 * Date: 2021/1/21 18:26
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common;


use app\admin\AdminLoginSession;
use app\admin\model\system\DataAuth;
use think\Collection;
use think\db\Query;
use think\facade\Config;

class BaseQuery extends Query
{

    /**
     * @param mixed $join
     * @param string|null $condition
     * @param string $type
     * @param array $bind
     * @return $this|BaseQuery
     */
    public function join($join, string $condition = null, string $type = 'INNER', array $bind = [])
    {
        parent::join($join, $condition, $type, $bind);
        $joinLastKay = array_key_last($this->options['join']);
        $alias       = current(current($this->options['join'][$joinLastKay]));

        if (strpos($this->options['join'][$joinLastKay][2], "$alias.delete_time") === false) {
            $this->options['join'][$joinLastKay][2] .= sprintf(" AND %s.%s = %s", $alias, 'delete_time', 0);
        }

        return $this;
    }

    /**
     * 原生join 不加 delete_time
     * @param $join
     * @param string|null $condition
     * @param string $type
     * @param array $bind
     * @return BaseQuery
     */
    public function primevalJoin($join, string $condition = null, string $type = 'INNER', array $bind = [])
    {
        return parent::join($join, $condition, $type, $bind);
    }

    /**
     * @param null $data
     * @return Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/9
     */
    public function select($data = null): Collection
    {
        $this->dataAuthInject();
        return parent::select($data);
    }

    /**
     * @param null $data
     * @return BaseQuery|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/9
     */
    public function find($data = null)
    {
        $this->dataAuthInject();
        return parent::find($data);
    }

    /**
     * @param array|string $field
     * @param string $key
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/9
     */
    public function column($field, string $key = ''): array
    {
        $this->dataAuthInject();
        return parent::column($field, $key);
    }

    /**
     * @param bool $fetch
     * @return BaseQuery|\think\db\Fetch
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/9
     */
    public function fetchSql(bool $fetch = true)
    {
        $this->dataAuthInject();
        return parent::fetchSql($fetch);
    }

    /**
     * 数据权限条件注入
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/9
     */
    private function dataAuthInject()
    {
        // 得到自身表名
        $selfTable   = strtr($this->getTable(), [$this->prefix => '']);

        // 没有开启数据权限或不是后端或者是数据权限的查询不做处理
        if (!($openDataAuthTables = Config::get('admin.data_auth')) || !AdminLoginSession::getId() || $selfTable === 'data_auth') return;

        $openDataAuthTables = array_column($openDataAuthTables, 'table');

        $joinOptions = $this->getOptions('join') ?: [];

        // 收集所有join的表
        $tables = array_map(function ($v){
            return strtr(array_key_first($v), [$this->prefix => '']);
        }, array_column($joinOptions, 0));

        // 追加自身表
        $tables[] = $selfTable;

        // 表里面没有找到有数据权限的表，不做处理
        if (!$tables = array_intersect($openDataAuthTables, $tables)) return;

        $dataAuth = DataAuth::where('role_id', AdminLoginSession::getRoleId())
            ->whereIn('table_names', $tables)
            ->column('auth_id', 'table_names');


        // 设置自身表数据权限
        if (!empty($dataAuth[$selfTable])){
            $field = $this->getOptions('alias') ? current($this->getOptions('alias')) . '.' : '';
            $this->whereIn($field . $this->pk, $dataAuth[$selfTable]);

            // 设置后就删除对应的条件，避免重复设置
            unset($dataAuth[$selfTable]);
        }

        // 设置join的数据权限
        foreach ($joinOptions as $joinOption){
            $currentTable = strtr(array_key_first(current($joinOption)), [$this->prefix => '']);
            $currentAlias = current(current($joinOption));

            // 数据权限的条件添加
            if (!empty($dataAuth[$currentTable])){
                $this->whereIn("$currentAlias.id", $dataAuth[$currentTable]);

                // 设置后就删除对应的条件，避免重复设置
                unset($dataAuth[$currentTable]);
            }
        }
    }
}
