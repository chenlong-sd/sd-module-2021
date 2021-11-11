<?php
/**
 * datetime: 2021/11/10 15:31
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\makeBaseCURD\item;

use sdModule\makeBaseCURD\CURD;

class Service extends Item
{

    /**
     * 初始化
     * Item constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD = $CURD;

        $this->replace    = [
            'table_name'   => $this->CURD->table,
            'page_name'    => $this->CURD->pageName ?: $this->CURD->tableComment,
            'Table'        => parse_name($this->CURD->table, 1),
            'date'         => date('Y-m-d H:i:s'),
            'search_form'  => [],
            'list_join'    => [],
            'list_field'   => [],
            'use'          => '',
            'quick_search' => [],
            'namespace'    => $this->CURD->getNamespace($this->CURD->config('namespace.service')),
            'describe'     => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->useModel();

        $this->fieldHandle();
        $this->replace['list_field'] = implode(',', $this->replace['list_field']);
        $this->replace['list_join']  = implode($this->CURD->indentation(3), $this->replace['list_join']);
    }

    /**
     * @return mixed 创建
     */
    public function make()
    {
        $file_content = file_get_contents($this->CURD->config('template.service'));

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
     * 查询字段处理
     * @param string $field
     * @param array $datum
     */
    private function selectFieldHandle(string $field, array $datum)
    {
        if (empty($datum['show_type'])) {
            return;
        }

        // 如果join是数组 ，说明是一个状态值的展示，又有会有获取器替换真值，所以再次查询真值取别名 $field_true
        if (is_array($datum['join'])) {
            $this->replace['list_field'][] = "i.$field {$field}_true";
        }else if(strpos($datum['join'], ':') !== false
            && strpos($datum['join'], '=') !== false){

            list($table, $joinData) = explode(':', $datum['join']);
            list($value, $title)    = explode('=', $joinData);

            $table = $table ?: $this->CURD->table;
            $this->joinTable($table, $field, $value, $table === $this->CURD->table);

            $table_pre = $table === $this->CURD->table ? 'parent' : $table;
            $this->replace['list_field'][] = "$table.$title {$table_pre}_$title,i.$field";
            return;
        }

        $this->replace['list_field'][] = "i.$field";
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

        $this->replace['list_join'][$table . '-' .$field] = "->join('$table', '$joinStr', 'left')";
    }
}

