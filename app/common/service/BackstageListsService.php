<?php
/**
 * Date: 2021/1/21 21:21
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\service;


use app\common\BaseModel;
use app\common\SdException;
use think\db\Query;
use think\exception\HttpResponseException;
use think\facade\Env;
use think\facade\Log;
use think\helper\Arr;
use think\Paginator;
use think\response\Json;

class BackstageListsService
{
    /**
     * @var Query
     */
    private $model;

    /**
     * @var bool 是否分页显示
     */
    private $pagination = true;

    /**
     * @var string
     */
    private $alias = 'i';

    /**
     * @var callable 返回处理回调
     */
    private $returnHandle;

    /**
     * @var array 统计行数据
     */
    private $totalRow = [];

    /** @var array  表达式替换 */
    private $exprArr = [
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
     * @var callable
     */
    private $each = null;

    /**
     * 自定义的查询处理
     * @var null|callable
     */
    private $customSearch = null;
    /**
     * @var callable
     */
    private $customSort = null;

    /**
     * @param BaseModel|Query|string $model
     * @return $this
     */
    public function setModel($model): BackstageListsService
    {
        if (is_string($model)) {
            $model = app($model);
        }

        if (!$model->getOptions('alias')) {
            $model = $model->alias($this->alias);
        }

        $this->alias = current($model->getOptions('alias'));
        $this->model = $model;
        return $this;
    }


    /**
     * 设置where条件
     * @return BackstageListsService
     */
    private function setWhere(): BackstageListsService
    {
        $search = data_filter(request()->get('search', []));
        // 自定义的查询处理
        if (is_callable($this->customSearch)) {
            $exceptParam = call_user_func($this->customSearch, $search, $this->model);
            $search      = Arr::except($search, $exceptParam);
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
        $expr       = '=';
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
                $this->model->where($field, $expr, "%{$value}%");
                break;
            case 'LEFT LIKE':
                $this->model->where($field, 'LIKE', "%{$value}");
                break;
            case 'RIGHT LIKE':
                $this->model->where($field, 'LIKE', "{$value}");
                break;
            case 'NULL':
                $this->model->whereNull($field);
                break;
            case '~':
                $this->model->where($field, 'BETWEEN', explode($expr, $value));
                break;
            default:
                $this->model->where($field, $expr, $value);
        }
    }

    /**
     * 检测是否分页
     * @return bool
     */
    private function hasPagination(): bool
    {
        $limit = request()->get('limit', null);
        return $limit && $this->pagination;
    }

    /**
     * 获取列表数据
     * @param bool $viewSql 是否查看sql
     * @return Json
     * @throws SdException
     */
    public function getListsData(bool $viewSql = false): Json
    {
        try {
            $this->getNewModel();
            $this->viewSql($viewSql);
            $totalRow = $this->totalRawHandle();

            if ($this->hasPagination()) {
                $result = $this->model->paginate(request()->get('limit', 10));
            }else{
                $result = $this->model->select();
            }
        } catch (\Throwable $exception) {
            if ($exception instanceof HttpResponseException) {
                throw $exception;
            }

            if (!$exception instanceof SdException) {
                Log::write($exception->getMessage() . ".{$exception->getFile()}({$exception->getLine()})", 'error');
                throw new SdException(Env::get('APP_DEBUG', false) ? $exception->getMessage() : "fail");
            }
            throw new SdException($exception->getMessage());
        }

        return $this->returnHandle($result, $totalRow ?? []);
    }

    /**
     * 统计字段处理
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/21
     */
    private function totalRawHandle(): array
    {
        if (!$this->totalRow) return [];

        $totalField = [];
        foreach ($this->totalRow as $fun => $field){
            // 如果 fun是字符串，则判定为是自定义的字段对应统计函数，
            is_numeric($fun) ? $fun = 'sum' : list($fun, $field) = [$field, $fun];
            $alias = strpos($field, '.') === false ? $field : preg_replace('/^.+\./', '', $field);
            $totalField[] = "$fun($field) $alias";
        }

        return (clone $this->model)->allowEmpty()->cache(30)->setOption('field', [])
            ->field(implode(',', $totalField))->find()->toArray();
    }

    /**
     * 获取新的处理后的 model
     * @param BaseModel|Query|string $model
     * @return Query
     * @throws SdException
     */
    public function getNewModel($model = null): Query
    {
        if ($model !== null) {
            $this->setModel($model);
        }

        try {
            $this->setWhere()->listsSort();

        } catch (\Throwable $exception) {
            if (!$exception instanceof SdException) {
                Log::write($exception->getMessage() . ".{$exception->getFile()}({$exception->getLine()})");
                throw new SdException(Env::get('APP_DEBUG', false) ? $exception->getMessage() : "fail");
            }
            throw new SdException($exception->getMessage());
        }

        return $this->model;
    }

    /**
     * 返回数据处理
     * @param $data
     * @param array $totalRow
     * @return Json
     */
    private function returnHandle($data, array $totalRow = []): Json
    {
        is_callable($this->each) and $data->each($this->each);
        if (is_callable($this->returnHandle)) {
            return call_user_func($this->returnHandle,  $data, $totalRow, $this->model);
        }
        $code  = 0;
        $msg   = lang('success');
        $count = $data instanceof Paginator ? $data->total() : count($data);
        $data  = $data instanceof Paginator ? $data->all() : $data;

        $result = compact('code', 'msg', 'count', 'data');
        if ($totalRow) {
            $result['totalRow'] = $totalRow;
        }

        return json($result);
    }

    /**
     * 查看sql
     * @param bool $viewSql
     * @return void
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function viewSql(bool $viewSql): void
    {
        $page  = request()->get('page', 1);
        $limit = request()->get('limit', 10);
        if ($viewSql) {
            $sql = $this->hasPagination()
                ? $this->model->page($page, $limit)->fetchSql()->select()
                : $this->model->fetchSql()->select();
            throw new SdException($sql);
        }
    }

    /**
     * 处理排序字段
     * @return BackstageListsService
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/18
     */
    private function listsSort(): BackstageListsService
    {
        if ($sort = request()->get('sort')) {
            $sortArr   = array_pad(explode(',', $sort), -3, 0);
            $sortAlias = $sortArr[0] ?: $this->alias;
            $sortField = $sortArr[1] ?: $this->model->getPk();
            $sortType  = $sortArr[2] ?: 'DESC';

            if (is_callable($this->customSort)) {
                call_user_func($this->customSort, [$sortField, $sortType], $this->model);
            }else{
                $this->model->order(implode('.', [$sortAlias, $sortField]), $sortType);
            }
        }else{
            $this->model->order("{$this->alias}.{$this->model->getPk()}", 'DESC');
        }
        return $this;
    }

    /**
     * 设置统计行
     * @param array $fieldLists
     * @example ['price']   ['number' => 'count']
     * @return $this
     */
    public function setTotalRow(array $fieldLists): BackstageListsService
    {
        $this->totalRow = $fieldLists;
        return $this;
    }

    /**
     * 设置返回的参数回调
     * @param callable $returnHandle
     * @return BackstageListsService
     */
    public function setReturnHandle(callable $returnHandle): BackstageListsService
    {
        $this->returnHandle = $returnHandle;
        return $this;
    }

    /**
     * 设置是否分页
     * @param bool $pagination
     * @return BackstageListsService
     */
    public function setPagination(bool $pagination): BackstageListsService
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * 设置循环处理数据的函数
     * @param callable $each
     * @example function($data) { $data->example = 1; }
     * @return BackstageListsService
     */
    public function setEach(callable $each): BackstageListsService
    {
        $this->each = $each;
        return $this;
    }

    /**
     * 自定义的查询处理, 函数返回已经处理过得字段， 函数带参数为 function(搜索的参数， 当前查询的对象)
     * @param callable|null $customSearch
     * @example function ($search, $model) {
     *      if($search['data']){
     *          $model->where('data', '>', '1');
     *      }
     *      return ['data'];
     *   }
     * @return BackstageListsService
     */
    public function setCustomSearch(callable $customSearch): BackstageListsService
    {
        $this->customSearch = $customSearch;
        return $this;
    }

    /**
     * 自定义排序， 函数带参数为 function([排序的参数, 排序方式]， 当前查询的对象)
     * @param callable $customSort
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/18
     */
    public function setCustomSort(callable $customSort): BackstageListsService
    {
        $this->customSort = $customSort;
        return $this;
    }
}
