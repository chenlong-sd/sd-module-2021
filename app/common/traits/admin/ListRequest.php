<?php


namespace app\common\traits\admin;


use app\common\BaseModel;
use app\common\BasePage;
use app\common\controller\Admin;
use app\common\SdException;
use think\db\Query;
use think\Model;
use think\model\Collection;
use think\Request;

/**
 * 数据列表
 * @property-read   Request         $request
 * @property        callable        $each           需要对每个元素进行处理后返回新元素的匿名函数
 * @property        array           $join           表连接的二维数组
 * @property        array           $with           TP 关联关系查询
 * @property        string          $field          查询字段,默认全部
 * @property        array|string    $where          额外查询条件
 * @property        string          $group          分组查询字段
 * @property        callable        $custom_return  自定义的返回数据格式的函数或类函数,默认为layui数据表格的格式
 * @method          Admin   setAlias(string $alias) 设置表别名
 * @method          Admin   setEach(callable $callback)   设置数据处理
 * @method          Admin   setJoin(array $join)    设置关联
 * @method          Admin   setWith(array $whit)    设置TP关联
 * @method          Admin   setField(string $field) 设置查询字段
 * @method          Admin   setWhere($where)        设置查询条件
 * @method          Admin   setGroup(string $group) 设置分组条件
 * @method          Admin   setMore(callable $more) 设置更多的操作，匿名函数，带一个参数为当前的查询Model
 * @method          Admin   setCustomReturn(callable $return)设置返回格式
 * @method          BasePage getPage()
 * Trait ListRequest
 * @package app\common\controller
 * @author chenlong <vip_chenlong@163.com>
 */
trait ListRequest
{
    /**
     * @var Model|Query
     */
    private $query;

    /** @var array  表达式替换 */
    private array $expr_arr = [
        '_=' => '=',
        '_<' => '<',
        '_>' => '>',
        '<=' => '<=',
        '>=' => '>=',
        '%%' => 'LIKE',
        '_%' => 'RIGHT LIKE',
        '%_' => 'LEFT LIKE',
        '_I' => 'IN',
        '_N' => 'NULL',
        '_B' => 'BETWEEN',
        '>t' => '> TIME',
        '>T' => '>= TIME',
        '<t' => '< TIME',
        '<T' => '<= TIME',
        '<>' => '<>',
        '_~' => '~'
    ];
    /**
     * @var string  当前表别名
     */
    private string $alias = 'i';

    /**
     * @var bool|string|array 查询字段,默认全部
     */
    private $field = true;

    /**
     * @var array 默认排序字段
     */
    private array $default_sort = ['i.id', 'DESC'];
    /**
     * @var array
     */
    private array $sort = [];

    /**
     * @var bool 是否分页,默认要
     */
    private bool $whether_to_page = true;

    /**
     * 设置排序字段，可调用多次设置多个字段排序
     * @param string $field 字段
     * @param string $mode 类型：ASC|DESC
     * @return $this
     */
    public function setSort(string $field, string $mode = 'DESC')
    {
        $this->sort[] = [$field, $mode];
        return $this;
    }

    /**
     * 请求列表数据
     * @param bool $fetch_sql 是否是查看 sql
     * @return mixed|string|\think\Collection|\think\response\Json
     * @throws SdException
     */
    final public function listsRequest(bool $fetch_sql = false)
    {
        $this->query = $this->getModel()::addSoftDelWhere([], $this->alias)->field($this->field);

        $this->tableJoin()->listsSort($this->request->get('sort'))
            ->listsSearch()->quickSearch();

        if (!empty($this->with))  $this->query->with($this->with);
        if (!empty($this->where)) $this->query->where($this->where);
        if (!empty($this->group)) $this->query->group($this->group);
        if (!empty($this->more))  call_user_func($this->more, $this->query);

        if ($data_auth = BaseModel::dataAuthWhere($this->getTableName())) {
            $this->query->whereIn("{$this->alias}.{$this->primary}", $data_auth);
        }

        try {
            if ($fetch_sql) return $this->seeSql();
            return $this->returnListData($this->getPageListData());
        } catch (\Exception $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * @return $this
     * @throws SdException
     */
    private function tableJoin()
    {
        if (empty($this->join)) return $this;

        foreach ($this->join as $key => $item) {
            if (is_string($key)) {
                $join_expression = $item;
            }else{
                $join_expression = soft_delete_join($item);
                $join_expression = data_auth_join($join_expression);
            }
            $this->query->join(...$join_expression);
        }
        return $this;
    }

    /**
     * 查看sql
     * @return string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function seeSql()
    {
        $page  = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        return $this->hasPagination()
            ? $this->query->page($page, $limit)->fetchSql()->select()
            : $this->query->fetchSql()->select();
    }

    /**
     * 是否分页显示
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getPageListData()
    {
        $limit = $this->request->get('limit', null);
        $data  = $this->hasPagination() ? $this->query->paginate($limit) : $this->query->select();

        return empty($this->each) ? $data->toArray() : $data->each($this->each)->toArray();
    }

    /**
     * 检测是否分页
     * @return bool
     */
    private function hasPagination()
    {
        $limit = $this->request->get('limit', null);
        return $limit && $this->whether_to_page;
    }


    /**
     * 处理排序字段
     * @param string $sort 排序类型|字段,排序类型|别名,字段,排序类型
     * @return $this
     */
    private function listsSort($sort)
    {
        if ($sort) {
            $sort_arr   = array_pad(explode(',', $sort), -3, 0);
            $sort_alias = $sort_arr[0] ?: $this->alias;
            $sort_field = $sort_arr[1] ?: $this->primary;
            $sort_type  = $sort_arr[2] ?: 'DESC';

            $this->query->order(implode('.', [$sort_alias, $sort_field]), $sort_type);
        }else if ($this->sort){
            foreach ($this->sort as $sort){
                $this->query->order(...$sort);
            }
        }else{
            $this->query->order(...$this->default_sort);
        }
        return $this;
    }

    /**
     * 处理搜索字段
     * @return $this
     */
    private function listsSearch()
    {
        $search = $this->filter($this->request->get('search', []));
        $this->listSearchParamHandle($search);

        foreach ($search as $field => $value) {
            list($field, $expr) = $this->ruleAnalysis($field);
            $this->ruleMatch($field, $expr, $value);
        }
        return $this;
    }


    /**
     * 规则解析
     * @param string  $field 带规则的字段
     * @return array
     */
    private function ruleAnalysis($field)
    {
        $expr        = '=';
        $expr_string = substr($field, -2);

        if (isset($this->expr_arr[$expr_string])) {
            $expr  = $this->expr_arr[$expr_string];
            $field = substr($field, 0, -2);
        }

        if (strpos($field, '.') === false) {
            $field = implode('.', [$this->alias, $field]);
        }

        return [$field, $expr];
    }


    /**
     * 搜索规则匹配
     * @param $field
     * @param $expr
     * @param $value
     */
    private function ruleMatch($field, $expr, $value)
    {
        switch ($expr) {
            case 'LIKE':
                $this->query->where($field, $expr, "%{$value}%");
                break;
            case 'LEFT LIKE':
                $this->query->where($field, 'LIKE', "%{$value}");
                break;
            case 'RIGHT LIKE':
                $this->query->where($field, 'LIKE', "{$value}");
                break;
            case 'NULL':
                $this->query->whereNull($field);
                break;
            case '~':
                $this->query->where($field, 'BETWEEN', explode($expr, $value));
                break;
            default:
                $this->query->where($field, $expr, $value);
        }
    }

    /**
     * 列表数据返回格式定义
     * @param $data
     * @return mixed|\think\response\Json
     * @see Admin::setCustomReturn() 此方法来设置 $this->customReturn
     */
    private function returnListData($data)
    {
        if (!empty($this->custom_return)) {
            $data = call_user_func($this->custom_return, $data);
        } else {
            $data = json([
                'code'  => 0,
                'msg'   => lang('success'),
                'count' => $this->whether_to_page ? $data['total'] ?? count($data) : 0,
                'data'  => $data['data'] ?? $data
            ]);
        }
        return $data;
    }

    /**
     * 快捷搜索处理
     * @return $this
     */
    private function quickSearch()
    {
        $quick_search = trim($this->request->get('quick_search', ''));

        if (!$quick_search || !$this->getPage()->setQuickSearchField()) return $this;

        $field = array_keys($this->getPage()->setQuickSearchField());

        list($field_1, $expr_1) = $this->ruleAnalysis($field[0]);
        $field_raw = "{$field_1} {$expr_1} :search_1";

        $quick_search = $this->quickSearchValueMatch($expr_1, $quick_search);

        if (!empty($field[1])){
            list($field_2, $expr_2) = $this->ruleAnalysis($field[1]);
            $field_raw .= " OR {$field_2} {$expr_2} :search_2";
            $this->query->whereRaw($field_raw, [
                'search_1' => $this->quickSearchValueMatch($expr_1, $quick_search),
                'search_2' => $this->quickSearchValueMatch($expr_2, $quick_search)
            ]);
        }else{
            $this->query->whereRaw($field_raw, ['search_1' => $this->quickSearchValueMatch($expr_1, $quick_search)]);
        }

        return $this;
    }


    /**
     * 快捷搜索匹配
     * @param $expr
     * @param $value
     * @return string
     */
    private function quickSearchValueMatch($expr, $value)
    {
        return $expr === '=' ? $value : "%{$value}%";
    }

    /**
     * 搜索参数处理
     * @param $search_data
     */
    public function listSearchParamHandle(&$search_data){}

    /**
     * 设置不分页
     * @return $this
     */
    final public function setNoPage()
    {
        $this->whether_to_page = false;
        return $this;
    }
}

