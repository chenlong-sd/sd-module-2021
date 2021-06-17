<?php
/**
 * Date: 2020/10/20 15:17
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use sdModule\makeBaseCURD\CURD;

class Validate implements Item
{
    /**
     * @var array
     */
    private $replace = [];

    /**
     * @var string
     */
    private $primary_key;

    /**
     * @var CURD
     */
    private $CURD;

    public function __construct(CURD $CURD)
    {
        $this->CURD = $CURD;
        $this->primary_key = $this->CURD->getTablePrimary($this->CURD->table);

        $this->replace = [
            'Table' => parse_name($this->CURD->table, 1),
            'date'  => datetime(),
            'use'   => '',
            'rule'  => [
                "'{$this->primary_key}|{$this->CURD->tableComment}' => 'require|number',"
            ],
            'scene' => [
                'add'  => [],
                'edit' => [$this->primary_key]
            ],
            'namespace' => $this->CURD->getNamespace($this->CURD->config('namespace.validate')),
        ];

        $this->fieldRuleHandle();
    }

    /**
     * 创建文件
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function make(): string
    {
        $file_content = file_get_contents($this->CURD->config('template.validate'));

        $this->replace['scene']['add']  = "'add' => ['" . implode('\', \'', $this->replace['scene']['add']) . "'],";
        $this->replace['scene']['edit'] = "'edit' => ['" . implode('\', \'', $this->replace['scene']['edit']) . "'],";

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 字段规则处理
     */
    private function fieldRuleHandle()
    {
        $tableInfo    = $this->CURD->fieldInfo;
        $tableComment = array_column($tableInfo, 'column_comment', 'column_name');
        $fieldType    = array_column($tableInfo, 'data_type', 'column_name');

        foreach ($this->CURD->data as $field => $makeDatum) {
            if (!$makeDatum['type']) continue;
            $title = $makeDatum['label'] ?: $tableComment[$field];
            $verifyRule = 'require';

            if (in_array($fieldType[$field], ['tinyint', 'int', 'smallint', 'bigint'])) $verifyRule .= '|number';

            if (in_array($fieldType[$field], ['float', 'decimal', 'double'])) $verifyRule .= '|float';

            if (preg_match('/([a-zA-Z_]*phone)$|([a-zA-Z_]*tel)$/', $field)) $verifyRule .= '|mobile';

            if ($makeDatum['join'] && is_array($makeDatum['join'])) {
                $verifyRule .= '|in:' . implode(',', array_keys($makeDatum['join']));
            }
            $this->replace['scene']['add'][] = $field;
            $this->replace['scene']['edit'][] = $field;
            $this->replace['rule']["{$field}|{$title}"] = "'{$field}|{$title}' => '$verifyRule',";
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
            $replace["//=={{$key}}==//"] = is_array($value)
                ? implode($key  === "use" ? "\r\n" : $this->CURD->indentation(2), $value) : $value;

        }
        return $replace;
    }

}
