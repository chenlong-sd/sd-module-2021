<?php


namespace sdModule\common\helper;

/**
 * 无限极数据处理
 * Class Infinite
 * @deprecated 4.0 废弃 新的采用 Tree
 * @see Tree 4.0 以后的重构
 * @package app\common\controller
 */
class Infinite
{
    /** @var string 主键 */
    protected $primaryKey = 'id';

    /** @var string 父级字段名 */
    protected $parents = 'pid';

    /** @var string 自己字段名称 */
    protected $childrenKey = 'children';

    /** @var int 返回的级数，0为全部 */
    protected $series = 0;

    /**
     * @var string 多于返回的级数的数据处理
     * @example merge | del  (合并 或 删除，默认合并）
     */
    protected $surplusHandle = 'merge';

    /**
     * 对每个元素的额外处理函数，参数就是元素自身,返回新的元素
     * @var \Closure|null
     */
    protected $call = null;

    /**
     * 处理完之后再进行处理的匿名函数
     * @var \Closure|null
     */
    protected $afterCall = null;

    /** @var bool 是否记录传承链接，连接字段为 inherit */
    protected $inherit = false;

    /** @var bool 返回值是否以主键为键 */
    protected $keyIsPrimary = false;

    /**
     * 要处理的数据
     * @var array
     */
    private $data;

    /**
     * Infinite constructor.
     * @param array $data 包含id, pid 的二维数组，可以设置
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * 无限极分类数据
     * @param int|array $appointFieldValue 指定某个id的子集或某个字段值的子集(数组)，如：['title' => '视频']
     * @param bool      $isTwo        是否把结果转换成二维数组
     * @return array
     */
    public function handle($appointFieldValue = 0, $isTwo = false): array
    {
        $Infinite = [];

//        把所有的键名改为主键
        $newData = array_column($this->data, null, $this->primaryKey);

//       重组成无限极
        foreach ($newData as $key => $value) {
            if ($this->call instanceof \Closure){
                $newData[$key] = call_user_func($this->call, $value);
            }

            $this->recordInherit($newData, $key);
            $Infinite = array_merge($Infinite, $this->treeValue($newData, $key, $appointFieldValue));
        }

//        对多余的级数进行处理
        if ($this->surplusHandle == 'merge') {
            if ($this->series > 1) {
                $this->finiteHandle($Infinite, $this->series - 1);
            } else if ($this->series == 1) {
                $isTwo = true;
            }
        } elseif ($this->surplusHandle == 'del') {
            $this->finiteHandle($Infinite, $this->series);
        }

        if ($this->afterCall) {
            $Infinite = $this->afterHandleCall($Infinite);
        }

        if ($isTwo) {
//            再次重组成二维数组
            $this->recombination($Infinite, $result);
            return $result ?: [];
        }

        return $Infinite;
    }

    /**
     * 把无限极的数组类型转换成二维
     * @param array $data
     * @return mixed
     */
    public function reveres(array $data = [])
    {
        $this->recombination($data ?: $this->data, $newData);

        return $newData;
    }

    /**
     * 设置主键
     * @param string $primaryKey
     * @return Infinite
     */
    public function setPrimaryKye($primaryKey = ''): self
    {
        $primaryKey && is_string($primaryKey) and $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * 设置父级字段
     * @param string $parents 父级字段名称
     * @return Infinite
     */
    public function setParents($parents = ''): self
    {
        $parents && is_string($parents) and $this->parents = $parents;
        return $this;
    }

    /**
     * 设置子集的键值
     * @param string $childrenKey
     * @return Infinite
     */
    public function setChildrenKey($childrenKey = ''): self
    {
        $childrenKey && is_string($childrenKey) and $this->childrenKey = $childrenKey;
        return $this;
    }

    /**
     * 设置级数及剩余处理
     * @param int    $series 级数
     * @param string $handle 处理方式，取值：merge | del
     * @return Infinite
     */
    public function setSeries(int $series, $handle = ''): self
    {
        if (!empty($series)) {
            $this->series = $series;
        }

        if (!empty($handle) && in_array($handle, ['merge', 'del'])) {
            $this->surplusHandle = $handle;
        }

        return $this;
    }

    /**
     * 对每个数组的处理函数，
     * @param \Closure      $closure   对数组的处理闭包，默认处理成无限极之前，当$afterCall 值为布尔 true 或 闭包 时，在处理成无限极之后
     * @param bool|\Closure $afterCall 值为闭包时，在处理成无限极之后调用此闭包
     * @return Infinite
     */
    public function setCall(\Closure $closure, $afterCall = false): self
    {
        if ($afterCall === false) {
            $this->call = $closure;
        } else if ($afterCall === true) {
            $this->afterCall = $closure;
        } else if ($afterCall instanceof \Closure) {
            $this->call = $closure;
            $this->afterCall = $afterCall;
        }

        return $this;
    }

    /**
     * 设置是否记录传承关系链
     * @param bool|string $inherit
     * @return Infinite
     */
    public function setInherit($inherit = true)
    {
        $this->inherit = $inherit;
        return $this;
    }

    /**
     * 设置是否以主键为键的方式返回
     * @param bool $yesOrNo
     * @return Infinite
     */
    public function setKey(bool $yesOrNo = true)
    {
        $this->keyIsPrimary = $yesOrNo;
        return $this;
    }

    /**
     * 把无限极的分类重组成二维
     * @param array $data   原始数组
     * @param mixed $result 接收数组
     */
    protected function recombination($data, &$result): void
    {
        foreach ($data as $value) {
            if (!empty($value[$this->childrenKey])) {
                $temporary = $value;
                unset($temporary[$this->childrenKey]);
                $result[] = $temporary;
                $this->recombination($value[$this->childrenKey], $result);
            } else {
                $result[] = $value;
            }
        }
    }


    /**
     * 非二维事后处理
     * @param $data
     * @return mixed
     */
    protected function afterHandleCall($data)
    {
        foreach ($data as &$value) {
            if (!empty($value[$this->childrenKey])) {
                $value[$this->childrenKey] = $this->afterHandleCall($value[$this->childrenKey]);
            }
            $value = ($this->afterCall)($value);
        }

        return $data;
    }

    /**
     * 处理返回有限级数的数据
     * @param array $data   所有数据
     * @param int   $series 返回级数
     */
    protected function finiteHandle(&$data, $series): void
    {
        foreach ($data as $key => &$value) {
            if ($series > 1) {
                if (!empty($value[$this->childrenKey])) {
                    $newSeries = $series - 1;
                    $this->finiteHandle($value[$this->childrenKey], $newSeries);
                }
            } else {
                if ($this->surplusHandle == 'merge' && !empty($value[$this->childrenKey])) {
                    $merge = [];
                    $this->recombination($value[$this->childrenKey], $merge);
                    $value[$this->childrenKey] = $merge;
                } elseif ($this->surplusHandle == 'del') {
                    unset($value[$this->childrenKey]);
                }
            }
        }
    }

    /**
     * 记录传承关系
     * @param $data
     * @param $key
     */
    private function recordInherit(&$data, $key)
    {
        if (!$this->inherit) return false;

        $parentKey = $data[$key][$this->parents];
        $inherit = is_bool($this->inherit) ? $this->primaryKey : $this->inherit;

        if (!empty($data[$parentKey])) {

            if (empty($data[$parentKey]['inherit'])) {
                $data[$parentKey]['inherit'] = [
                    $data[$parentKey][$this->inherit]
                ];
            }
            $data[$key]['inherit'] = array_merge($data[$parentKey]['inherit'], [
                $data[$key][$inherit]
            ]);
        } else {
            $data[$key]['inherit'] = [
                $data[$key][$inherit]
            ];
        }
    }

    /**
     * 树形数据
     * @param $data
     * @param $key
     * @param $appointField
     * @return array
     */
    private function treeValue(&$data, $key, $appointField)
    {
        $parentKey = $data[$key][$this->parents];
        if (!empty($data[$parentKey])) {
            if ($this->keyIsPrimary) { // 键值为主键
                $data[$parentKey][$this->childrenKey][$key] = &$data[$key];
            } else {
                $data[$parentKey][$this->childrenKey][] = &$data[$key];
            }
        } else if(!$appointField) {
            return $this->keyIsPrimary ? [$key => &$data[$key]] : [&$data[$key]];
        }

//            指定某个主键下面的或某个字段完全匹配下面的
        if ($appointField && is_numeric($appointField) && $key == $appointField) {
            return $this->keyIsPrimary ? [$key => &$data[$key]] : [&$data[$key]];
        } elseif ($appointField && is_array($appointField)) {
            $k = array_search(current($appointField), $appointField);
            if ($data[$key][$k] == $appointField[$k]) {
                return $this->keyIsPrimary ? [$key => &$data[$key]] : [&$data[$key]];
            }
        }

        return [];
    }


}


