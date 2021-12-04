<?php

namespace sdModule\common\helper;

class Tree
{
    /**
     * @var string 节点字段
     */
    private $node = 'id';

    /**
     * @var string 父节点字段
     */
    private $parentNode = 'pid';

    /**
     * @var string 子节点字段
     */
    private $childrenNode = 'children';

    /**
     * @var array 要处理的数据
     */
    private $data;

    /**
     * @var int 返回的层数，为0表示全部返回
     */
    private $level = 0;

    /**
     * @var null|callable 数据的循环处理
     */
    private $each = null;

    /**
     * @var array 按条件匹配返回
     */
    private $where = [];

    /**
     * @var bool|string 传承链记录
     */
    private $inheritedChain;

    /**
     * @var bool 初始数据是否是属性数据
     */
    private $initDataIsTree;

    /**
     * @var string|null 数据键的节点字段，默认无
     */
    private $keyNode;

    /**
     * Tree constructor.
     * @param array $data
     * @param bool $currentIsTreeData 初始数据是否是属性数据
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/29
     */
    public function __construct(array $data, bool $currentIsTreeData = false)
    {
        $this->data = $data;
        $this->initDataIsTree = $currentIsTreeData;
    }

    /**
     * 获取树数据
     * @return array
     */
    public function getTreeData(): array
    {
        $this->dataHandle();

        return $this->data;
    }

    /**
     * 获取线性数据
     * @return array
     */
    public function getLineData(): array
    {
        $this->dataHandle();
        $this->toLineData();
        ksort($this->data);

        return $this->data;
    }

    /**
     * 设置主要节点名称， 默认 id
     * @param string $node
     * @return Tree
     */
    public function setNode(string $node): Tree
    {
        $this->node = $node;
        return $this;
    }

    /**
     * 设置父节点的名称 默认 pid
     * @param string $parentNode
     * @return Tree
     */
    public function setParentNode(string $parentNode): Tree
    {
        $this->parentNode = $parentNode;
        return $this;
    }

    /**
     * 设置子节点的名称 默认 children
     * @param string $childrenNode
     * @return Tree
     */
    public function setChildrenNode(string $childrenNode): Tree
    {
        $this->childrenNode = $childrenNode;
        return $this;
    }


    /**
     * 设置返回的层数, 默认全部
     * @param int $level
     * @return Tree
     */
    public function setLevel(int $level): Tree
    {
        $this->level = $level;
        return $this;
    }

    /**
     * 设置循环处理 默认无
     * @param callable|null $each
     * @return Tree
     */
    public function setEach(callable $each): Tree
    {
        $this->each = $each;
        return $this;
    }

    /**
     * 设置指定条件的数据 默认 无
     * @param array $where 条件：  ['id' = 2]
     * @return Tree
     */
    public function setWhere(array $where): Tree
    {
        $this->where = $where;
        return $this;
    }

    /**
     * 设置记录父级链
     * @param bool|string $inheritedChain 记录传承连的字段。传 TRUE 默认为 node字段
     * @return Tree
     */
    public function setInheritedChain($inheritedChain): Tree
    {
        $this->inheritedChain = $inheritedChain === true ? $this->node : $inheritedChain;
        return $this;
    }


    /**
     * 数据键的节点字段，默认无
     * @param string|null $keyNode
     * @return Tree
     */
    public function setKeyNode(?string $keyNode): Tree
    {
        $this->keyNode = $keyNode;
        return $this;
    }


    /**
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    private function dataHandle()
    {
        // 初始数据不是数数据的话
        $this->initDataIsTree    or   $this->toTreeData();

        // 数据筛选
        $this->where             and $this->data = $this->dataFilter($this->data);

        // 指定返回树形数据的层数
        $this->level !== 0       and $this->data = $this->dataLevelHandle($this->data);

        // 传承链
        $this->inheritedChain     and $this->data = $this->recordParentHandle($this->data);

        // 自定义的循环处理
        is_callable($this->each) and $this->data = $this->dataEachHandle($this->data);
    }


    /**
     * 转成树形数据
     * @return array
     */
    private function toTreeData(): array
    {
        // 把数组的键转为节点字段的值
        $this->data = array_column($this->data, null, $this->node);

        $tree_data = [];
        foreach ($this->data as $key => &$datum) {
            // 如果在当前数组里面存在此数据的父级数据，则把此数据引用到父级数据的子节点上面
            // 比如 2 -> 1 的子节点， 3 -> 2 的子节点， 结果就是 1 -> 2 -> 3
            if (isset($this->data[$datum[$this->parentNode]])) {
                $this->keyNode
                    ? $this->data[$datum[$this->parentNode]][$this->childrenNode][$datum[$this->keyNode]] = &$datum
                    : $this->data[$datum[$this->parentNode]][$this->childrenNode][] = &$datum;
                continue;
            }

            // 如果在数组中找不到此数据的父节点数据，则表示该数据为顶级节点
            $this->keyNode
                ? $tree_data[$datum[$this->keyNode]] = &$datum
                : $tree_data[] = &$datum;
        }
        return $this->data = $tree_data;
    }


    /***
     * @param array $data
     * @param int $currentLevel
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    private function dataLevelHandle(array $data, int $currentLevel = 1): array
    {
        if ($this->level < $currentLevel) {
            return [];
        }

        foreach ($data as &$datum){
            if (!empty($datum[$this->childrenNode])){
                $datum[$this->childrenNode] = $this->dataLevelHandle($datum[$this->childrenNode], $currentLevel + 1);
            }
        }
        return $data;
    }

    /**
     * 对每个数据进行自定义的处理
     * @param array $data
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    private function dataEachHandle(array $data): array
    {
        foreach ($data as &$datum){
            $datum = call_user_func($this->each, $datum);
            if (!empty($datum[$this->childrenNode])) {
                $datum[$this->childrenNode] = $this->dataEachHandle($datum[$this->childrenNode]);
            }
        }

        return $data;
    }

    /**
     * 数据过滤
     * @param array $data
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    private function dataFilter(array $data): array
    {
        foreach ($data as $datum){
            if (!array_diff_assoc($this->where, $datum)){
                return [$datum];
            }
            if (!empty($datum[$this->childrenNode]) && ($filter = $this->dataFilter($datum[$this->childrenNode]))) {
                return $filter;
            }
        }
        return [];
    }

    /**
     * 转成线性数据
     * @param array $data
     */
    private function toLineData(array $data = [])
    {
        $handle_data = $data ?: $this->data;
        $data or $this->data = [];

        foreach ($handle_data as &$datum) {
            // 有子节点重复此操作
            if (!empty($datum[$this->childrenNode])) {
                $this->toLineData($datum[$this->childrenNode]);
            }
            // 删除子节点，记录此数据
            unset($datum[$this->childrenNode]);
            $this->data[$datum[$this->node]] = $datum;
        }
    }

    /**
     * 记录传承链的数据处理
     * @param array $data
     * @param array $parentsInheritedChain
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/28
     */
    private function recordParentHandle(array $data, array $parentsInheritedChain = []): array
    {
        foreach ($data as &$datum) {
            $datum['_inherited_chain_'] = array_merge($parentsInheritedChain,  [$datum[$this->inheritedChain]]);

            if (!empty($datum[$this->childrenNode])) {
                $datum[$this->childrenNode] = $this->recordParentHandle($datum[$this->childrenNode], $datum['_inherited_chain_']);
            }
        }
        return $data;
    }

}

