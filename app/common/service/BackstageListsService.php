<?php
/**
 * Date: 2021/1/21 21:21
 * User: chenlong <vip_chenlong@163.com>
 */

namespace app\common\service;


use app\common\BaseModel;
use app\common\SdException;
use think\db\Query;
use think\facade\Env;
use think\facade\Log;
use think\helper\Str;
use think\Paginator;

class BackstageListsService
{
    private Query $model;

    /**
     * @var bool 是否分页显示
     */
    private bool $pagination = true;

    /**
     * @var callable
     */
    private $listSearchParamHandle;
    /**
     * @var string
     */
    private string $alias = 'i';

    /**
     * @var callable 返回处理回调
     */
    private $returnHandle;

    /**
     * @var array 统计行数据
     */
    private array $totalRow = [];

    /**
     * @var array 快捷搜索的字段，最多支持两个，匹配方式参考：$this->exprArr
     * @example [
     *  'api_name%%' => '接口名',
     *  'test%%'     => '看看',
     * ]
     */
    private array $quickSearchField;

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
     * @var callable
     */
    private $each = null;

    /**
     * @param BaseModel|Query|string $model
     * @return $this
     * @throws \app\common\SdException
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
        $this->model = $this->dataAuth($model);
        return $this;
    }

    /**
     * 数据权限处理
     * @param BaseModel|Query $model
     * @return mixed
     * @throws \app\common\SdException
     */
    private function dataAuth($model)
    {
        if ($data_auth = BaseModel::dataAuthWhere(Str::snake($model->getName()))) {
            $model->whereIn("{$this->alias}.{$model->getPk()}", $data_auth);
        }
        return $model;
    }

    /**
     * 数据权限的 JOIN 处理
     * @param array $join
     * @throws SdException
     */
    private function dataAuthJoin(array &$join)
    {
        if (env('APP.DATA_AUTH')){
            $table = strtr(array_key_first(current($join)), [env('DATABASE.PREFIX') => '']);
            $alias = current(current($join));

            $primary = strtr(config('admin.primary'), ['{table}' => $table]);
            if ($where   = \app\common\BaseModel::dataAuthWhere($table)){
                $join[2] .= " AND {$alias}.$primary IN ($where)";
            }
        }
    }

    /**
     * 设置where条件
     * @return BackstageListsService
     */
    private function setWhere(): BackstageListsService
    {
        $search = data_filter(request()->get('search', []));
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
     * @return false|mixed|\think\response\Json
     * @throws SdException
     */
    public function getListsData(bool $viewSql = false)
    {
        try {
            $this->getNewModel();
            $this->viewSql($viewSql);
            if ($this->totalRow) {
                $totalField = [];
                foreach ($this->totalRow as $field){
                    $alias = strpos($field, '.') === false ? $field : preg_replace('/^.+\./', '', $field);
                    $totalField[] = "sum({$field}) $alias";
                }

                $totalRow = (clone $this->model)->allowEmpty()->cache(30)->setOption('field', [])->field(implode(',', $totalField))->find()->toArray();
            }

            if ($this->hasPagination()) {
                $result = $this->model->paginate(request()->get('limit', 10));
            }else{
                $result = $this->model->select();
            }
        } catch (\Throwable $exception) {
            if (!$exception instanceof SdException) {
                Log::write($exception->getMessage() . ".{$exception->getFile()}({$exception->getLine()})");
                throw new SdException(Env::get('APP_DEBUG', false) ? $exception->getMessage() : "fail");
            }
            throw new SdException($exception->getMessage());
        }

        return $this->returnHandle($result, $totalRow ?? []);
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
            $this->setWhere()->listsSort()->quickSearch();

            if ($joinOptions = $this->model->getOptions('join')) {
                foreach ($joinOptions as &$join) {
                    $this->dataAuthJoin($join);
                }
                $this->model->setOption('join', $joinOptions);
            }
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
     * @return false|mixed|\think\response\Json
     */
    private function returnHandle($data, array $totalRow = [])
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
     * @return BackstageListsService
     * @throws SdException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function viewSql(bool $viewSql): BackstageListsService
    {
        $page  = request()->get('page', 1);
        $limit = request()->get('limit', 10);
        if ($viewSql) {
            $sql = $this->hasPagination()
                ? $this->model->page($page, $limit)->fetchSql()->select()
                : $this->model->fetchSql()->select();
            throw new SdException($sql);
        }
        return $this;
    }

    /**
     * 处理排序字段
     * @return BackstageListsService
     */
    private function listsSort(): BackstageListsService
    {
        if ($sort = request()->get('sort')) {
            $sortArr   = array_pad(explode(',', $sort), -3, 0);
            $sortAlias = $sortArr[0] ?: $this->alias;
            $sortField = $sortArr[1] ?: $this->model->getPk();
            $sortType  = $sortArr[2] ?: 'DESC';

            $this->model->order(implode('.', [$sortAlias, $sortField]), $sortType);
        }else{
            $this->model->order("{$this->alias}.{$this->model->getPk()}", 'DESC');
        }
        return $this;
    }

    /**
     * 快捷搜索处理
     * @return BackstageListsService
     */
    private function quickSearch(): BackstageListsService
    {
        $quickSearch = trim(request()->get('quick_search', ''));

        if (!$quickSearch || !$this->quickSearchField) return $this;

        $field = array_keys($this->quickSearchField);

        list($firstField, $firstExpr) = $this->ruleAnalysis($field[0]);
        $fieldRaw = "{$firstField} {$firstExpr} :search_1";

        $quickSearch = $this->quickSearchValueMatch($firstExpr, $quickSearch);

        if (!empty($field[1])){
            list($LastField, $LastExpr) = $this->ruleAnalysis($field[1]);
            $fieldRaw .= " OR {$LastField} {$LastExpr} :search_2";
            $this->model->whereRaw($fieldRaw, [
                'search_1' => $this->quickSearchValueMatch($firstExpr, $quickSearch),
                'search_2' => $this->quickSearchValueMatch($LastExpr, $quickSearch)
            ]);
        }else{
            $this->model->whereRaw($fieldRaw, ['search_1' => $this->quickSearchValueMatch($firstExpr, $quickSearch)]);
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
     * 设置统计行
     * @param array $fieldLists
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
     * 设置收缩参数处理的回调
     * @param callable $listSearchParamHandle
     * @return BackstageListsService
     */
    public function setListSearchParamHandle(callable $listSearchParamHandle): BackstageListsService
    {
        $this->listSearchParamHandle = $listSearchParamHandle;
        return $this;
    }

    /**
     * @param bool $pagination
     * @return BackstageListsService
     */
    public function setPagination(bool $pagination): BackstageListsService
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @param array $quickSearchField
     * @return BackstageListsService
     */
    public function setQuickSearchField(array $quickSearchField): BackstageListsService
    {
        $this->quickSearchField = $quickSearchField;
        return $this;
    }

    /**
     * @param callable $each
     * @return BackstageListsService
     */
    public function setEach(callable $each): BackstageListsService
    {
        $this->each = $each;
        return $this;
    }
}
