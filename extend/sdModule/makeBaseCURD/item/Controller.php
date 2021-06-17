<?php


namespace sdModule\makeBaseCURD\item;


use sdModule\makeBaseCURD\CURD;

class Controller implements Item
{
    /***
     * @var array 替换数据
     */
    private $replace;

    /**
     * @var array 字段详情信息
     */
    private $field_info;


    /**
     * @var CURD
     */
    private $CURD;

    /**
     * 模块文件创建
     * Controller constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD       = $CURD;
        $this->field_info = $this->CURD->fieldInfo;
        $this->replace    = [
            'table_name'   => $this->CURD->table,
            'page_name'    => $this->CURD->pageName ?: $this->CURD->tableComment,
            'Table'        => parse_name($this->CURD->table, 1),
            'date'         => date('Y-m-d H:i:s'),
            'search_form'  => [],
            'list_join'    => [],
            'use'          => '',
            'quick_search' => [],
            'namespace'    => $this->CURD->getNamespace($this->CURD->config('namespace.controller')),
        ];
    }

    /**
     * @return mixed|void
     */
    public function make()
    {
        $file_content = file_get_contents($this->CURD->config('template.controller'));
        $this->fieldHandle();

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 字段处理
     */
    private function fieldHandle()
    {
        foreach ($this->CURD->data as $field => $datum) {
            if (empty($datum['show_type'])) {
                continue;
            }
            $this->selectFieldHandle($field, $datum);
        }
    }

    /**
     * 替换字符串处理
     * @return array
     */
    private function replaceHandle(): array
    {
        $replace = [];

        foreach ($this->replace as $key => $value) {
            $replace["//=={{$key}}==//"] = '';
            if ($key === 'list_join' && $value) {
                foreach ($value as $v) {
                    $replace["//=={{$key}}==//"] .= "{$this->CURD->indentation(3)}->join($v)";
                }
            }else{
                $replace["//=={{$key}}==//"] = is_array($value) ? implode($this->warp($key), $value) : $value;
            }
        }
        return $replace;
    }

    /**
     * 缩进补齐单位
     * @param $type
     * @return string
     */
    private function warp($type): string
    {
        $warp = [
            'use'        => "\r\n",
            'list_field' => ",",
            'list_join'  => $this->CURD->indentation(4),
        ];

        return $warp[$type] ?? $this->CURD->indentation(3);
    }

    /**
     * 加载类
     * @param $useClass
     */
    private function useAdd($useClass)
    {
        $use = "use {$useClass};";
        $this->replace['use'] = $this->replace['use'] ?: [];
        in_array($use, $this->replace['use'] ?? []) or $this->replace['use'][] = $use;
    }

    /**
     * 查询字段处理
     * @param string $field
     * @param array $datum
     */
    private function selectFieldHandle(string $field, array $datum)
    {
        if (empty($datum['show_type'])) {
            return;
        }

        if (is_array($datum['join']) || empty($datum['join'])) {
            $this->replace['list_field'][$field] = "i.{$field}";
        }else if(strpos($datum['join'], ':') !== false
            && strpos($datum['join'], '=') !== false){

            list($table, $joinData) = explode(':', $datum['join']);
            list($value, $title)    = explode('=', $joinData);

            $table = $table ?: $this->CURD->table;
            $this->joinTable($table, $field, $value, $table === $this->CURD->table);

            $table_pre = $table === $this->CURD->table ? 'parent' : $table;
            $this->replace['list_field'][$field] = "{$table}.{$title} {$table_pre}_{$title},i.{$field}";
        }
    }

    /**
     * 数据查询关联表处理
     * @param string $table join 表
     * @param string $field 字段
     * @param string $join_field 关联字段
     * @param bool $isParent
     */
    private function joinTable(string $table, string $field, string $join_field, bool $isParent)
    {
        $where = '';
        $joinStr = $isParent === true
            ? sprintf('i.pid = %s.%s %s', $table, $join_field, $where)
            : sprintf('i.%s = %s.%s %s', $field, $table, $join_field, $where);

        $this->replace['list_join'][$table . '-' .$field] = "'{$table}', '{$joinStr}', 'left'";
    }
}

