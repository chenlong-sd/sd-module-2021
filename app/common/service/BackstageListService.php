<?php
/**
 * Date: 2021/1/15 11:01
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\service;

use app\common\BaseModel;
use app\common\BasePage;
use app\common\controller\Admin;
use app\common\SdException;
use think\db\Query;
use think\helper\Str;
use think\Model;
use think\Request;
use think\response\Json;

/**
 * @property        callable        $each           需要对每个元素进行处理后返回新元素的匿名函数
 * @property        array           $join           表连接的二维数组
 * @property        array           $with           TP 关联关系查询
 * @property        string          $field          查询字段,默认全部
 * @property        array|string    $where          额外查询条件
 * @property        string          $group          分组查询字段
 * @property        callable        $listSearchParamHandle  搜索字段处理
 * @property        callable        $custom_return  自定义的返回数据格式的函数或类函数,默认为layui数据表格的格式
 * @method          self   setAlias(string $alias) 设置表别名
 * @method          self   setEach(callable $callback)   设置数据处理
 * @method          self   setJoin(array $join)    设置关联
 * @method          self   setWith(array $whit)    设置TP关联
 * @method          self   setField(string $field) 设置查询字段
 * @method          self   setWhere($where)        设置查询条件
 * @method          self   setGroup(string $group) 设置分组条件
 * @method          self   setPrimary(string $primary) 设置主键
 * @method          self   setMore(callable $more) 设置更多的操作，匿名函数，带一个参数为当前的查询Model
 * @method          self   setCustomReturn(callable $return)设置返回格式
 * @method          BasePage getPage()
 * Class BackstageListService
 * @package app\common\service
 */
class BackstageListService
{
    /**
     * @var Model|Query|BaseModel
     */
    private $query;

    /** @var array  表达式替换 */
    private array $exprArr = [
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
     * @var string 主键
     */
    private string $primary = 'id';

    /**
     * @var array 默认排序字段
     */
    private array $defaultSort = ['i.id', 'DESC'];
    /**
     * @var array
     */
    private array $sort = [];

    /**
     * @var bool 是否分页,默认要
     */
    private bool $whetherToPage = true;

    private Request $request;

    /**
     * BackstageListService constructor.
     */
    public function __construct()
    {
        $this->request = \request();
    }

    /**
     * 设置排序字段，可调用多次设置多个字段排序
     * @param string $field 字段
     * @param string $mode 类型：ASC|DESC
     * @return $this
     */
    public function setSort(string $field, string $mode = 'DESC'): BackstageListService
    {
        $this->sort[] = [$field, $mode];
        return $this;
    }

    /**
     * 搜索参数处理
     * @param callable $listSearchParamHandle
     * @return $this
     */
    public function listSearchParamHandle(callable $listSearchParamHandle): BackstageListService
    {
        $this->listSearchParamHandle = $listSearchParamHandle;
        return $this;
    }

    /**
     * 设置不分页
     * @return $this
     */
    final public function setNoPage(): BackstageListService
    {
        $this->whetherToPage = false;
        return $this;
    }


    /**
     * 请求列表数据
     * @param bool $fetch_sql 是否是查看 sql
     * @return mixed|string|\think\Collection|Json
     * @throws SdException
     */
    final public function listsRequest(bool $fetch_sql = false)
    {
        $this->query = $this->query::addSoftDelWhere([], $this->alias)->field($this->field);

        $this->tableJoin()->listsSort($this->request->get('sort', ''))
            ->listsSearch()->quickSearch();

        if (!empty($this->with))  $this->query->with($this->with);
        if (!empty($this->where)) $this->query->where($this->where);
        if (!empty($this->group)) $this->query->group($this->group);
        if (!empty($this->more))  call_user_func($this->more, $this->query);

        if ($data_auth = BaseModel::dataAuthWhere(Str::snake($this->query->getName()))) {
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
     * 设置model
     * @param $model
     * @return $this
     */
    public function setModel($model): BackstageListService
    {
        $this->query = is_string($model) ? app($model) : $model;
        return $this;
    }

    /**
     * 是否分页显示
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getPageListData(): array
    {
        $limit = $this->request->get('limit', null);
        $data  = $this->hasPagination() ? $this->query->paginate($limit) : $this->query->select();

        return empty($this->each) ? $data->toArray() : $data->each($this->each)->toArray();
    }


    /**
     * @return $this
     * @throws SdException
     */
    private function tableJoin(): BackstageListService
    {
        if (empty($this->join)) return $this;

        foreach ($this->join as $key => $item) {
            if (is_string($key)) {
                $joinExpression = $item;
            }else{
                $joinExpression = soft_delete_join($item);
                $joinExpression = data_auth_join($joinExpression);
            }
            $this->query->join(...$joinExpression);
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
     * 检测是否分页
     * @return bool
     */
    private function hasPagination(): bool
    {
        $limit = $this->request->get('limit', null);
        return $limit && $this->whetherToPage;
    }

    /**
     * 处理排序字段
     * @param string $sort 排序类型|字段,排序类型|别名,字段,排序类型
     * @return $this
     */
    private function listsSort(string $sort): BackstageListService
    {
        if ($sort) {
            $sortArr   = array_pad(explode(',', $sort), -3, 0);
            $sortAlias = $sortArr[0] ?: $this->alias;
            $sortField = $sortArr[1] ?: $this->primary;
            $sortType  = $sortArr[2] ?: 'DESC';

            $this->query->order(implode('.', [$sortAlias, $sortField]), $sortType);
        }else if ($this->sort){
            foreach ($this->sort as $sort){
                $this->query->order(...$sort);
            }
        }else{
            $this->query->order(...$this->defaultSort);
        }
        return $this;
    }

    /**
     * 处理搜索字段
     * @return $this
     */
    private function listsSearch(): BackstageListService
    {
        $search = Admin::filter($this->request->get('search', []));

        if(!empty($this->listSearchParamHandle) && is_callable($this->listSearchParamHandle)){
            $search = call_user_func($this->listSearchParamHandle, $search);
        }

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
    private function ruleAnalysis(string $field): array
    {
        $expr        = '=';
        $exprString = substr($field, -2);

        if (isset($this->exprArr[$exprString])) {
            $expr  = $this->exprArr[$exprString];
            $field = substr($field, 0, -2);
        }

        if (strpos($field, '.') === false) {
            $field = implode('.', [$this->alias, $field]);
        }

        return [$field, $expr];
    }


    /**
     * 搜索规则匹配
     * @param string $field
     * @param string $expr
     * @param $value
     */
    private function ruleMatch(string $field, string $expr, $value)
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
     * @return mixed|Json
     */
    private function returnListData($data): Json
    {
        if (!empty($this->custom_return)) {
            return call_user_func($this->custom_return, $data);
        }

        $code  = 0;
        $msg   = lang('success');
        $count = $data['total'] ?? count($data);
        $data  = $data['data'] ?? $data;

        return json(compact('code', 'msg', 'count', 'data'));
    }

    /**
     * 快捷搜索处理
     * @return $this
     */
    private function quickSearch(): BackstageListService
    {
        $quickSearch = trim($this->request->get('quick_search', ''));

        if (!$quickSearch || !$this->getPage()->setQuickSearchField()) return $this;

        $field = array_keys($this->getPage()->setQuickSearchField());

        list($field_1, $expr_1) = $this->ruleAnalysis($field[0]);
        $fieldRaw = "{$field_1} {$expr_1} :search_1";

        $quickSearch = $this->quickSearchValueMatch($expr_1, $quickSearch);

        if (!empty($field[1])){
            list($field_2, $expr_2) = $this->ruleAnalysis($field[1]);
            $fieldRaw .= " OR {$field_2} {$expr_2} :search_2";
            $this->query->whereRaw($fieldRaw, [
                'search_1' => $this->quickSearchValueMatch($expr_1, $quickSearch),
                'search_2' => $this->quickSearchValueMatch($expr_2, $quickSearch)
            ]);
        }else{
            $this->query->whereRaw($fieldRaw, ['search_1' => $this->quickSearchValueMatch($expr_1, $quickSearch)]);
        }

        return $this;
    }


    /**
     * 快捷搜索匹配
     * @param $expr
     * @param $value
     * @return string
     */
    private function quickSearchValueMatch($expr, $value): string
    {
        return $expr === '=' ? $value : "%{$value}%";
    }

    /**
     * @param $method
     * @param $vars
     * @return $this
     */
    public function __call($method, $vars): BackstageListService
    {
        if (substr($method, 0, 3) === 'set') {
            $property = parse_name(substr($method, 3), 0, false);
            $this->$property = $vars[0] ?? $vars;
        }
        return $this;
    }
}
