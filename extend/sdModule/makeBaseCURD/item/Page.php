<?php
/**
 * Date: 2020/11/25 15:18
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use sdModule\layui\defaultForm\FormData;
use sdModule\layuiSearch\generate\TimeRange;
use sdModule\layuiSearch\SearchForm;
use sdModule\makeBaseCURD\CURD;

class Page implements Item
{
    /**
     * @var CURD
     */
    private CURD $CURD;

    private array $replace;

    /**
     * 初始化
     * Item constructor.
     * @param CURD $CURD
     * @throws \app\common\SdException
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD = $CURD;
        $this->replace = [
            'Table'         => parse_name($this->CURD->table, 1),
            'date'          => date('Y-m-d H:i:s'),
            'search_form'   => [],
            'form_data'     => [],
            'page_name'     => $this->CURD->page_name ?: $this->CURD->table_comment,
            'quick_search'  => [],
            'use'           => '',
            'namespace'    => $this->CURD->config('namespace.page'),
        ];

        $this->formData();
        $this->tablePage();
        $this->searchHandle();
    }

    /**
     * @return mixed 创建
     */
    public function make()
    {
        $file_content = file_get_contents($this->CURD->config('template.page'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 替换字符串处理
     * @return array
     */
    private function replaceHandle()
    {
        $replace = [];
        foreach ($this->replace as $key => $value) {
            $replace["//=={{$key}}==//"] = is_array($value)
                ? implode($key  === "use" ? "\r\n" : $this->CURD->indentation(3), $value)
                : $value;
        }
        return $replace;
    }

    /**
     * @param $useClass
     */
    private function useAdd($useClass)
    {
        $use = "use {$useClass};";
        $this->replace['use'] = $this->replace['use'] ?: [];
        in_array($use, $this->replace['use'] ?? []) or $this->replace['use'][] = $use;
    }

    /**
     * 表单数据
     * @throws \app\common\SdException
     */
    private function formData()
    {
        $primary_key = $this->CURD->getTablePrimary($this->CURD->table);
        $this->useAdd(FormData::class);
        foreach ($this->CURD->data as $field => $item) {
            $type = $item['type'] === 'editor' ? "u_editor" : $item['type'];
            if ($field === $primary_key){
                $this->replace['form_data'][] = "FormData::hidden('{$field}'),";
                continue;
            }

            if (empty($type)){
                continue;
            }

            $select_data = '';
            if ($item['join'] && is_array($item['join'])){
                $field       = parse_name($field, 1);
                $this->useAdd($this->CURD->config('namespace.model') . '\\'  . $this->replace['Table'] . ' as MyModel');
                $select_data = ", MyModel::get{$field}Sc(false)";
            }elseif (in_array($type, ['date', 'time', 'month', 'range'])){
                $select_data = $type === 'range' ? ", 'date', true" : ", '{$type}'";
                $type        = 'time';
            }else if (strpos($item['join'], ':') !== false
                && strpos($item['join'], '=') !== false){

                list($table, $joinData) = explode(':', $item['join']);
                list($value, $title)    = explode('=', $joinData);

                $table = parse_name($table ?: $this->CURD->table, 1);
                if ($table != parse_name($this->CURD->table, 1)){
                    $system = in_array($table, ['Administrators', 'Role']) ? 'system\\' : '';
                    $this->useAdd($this->CURD->config('namespace.model') . '\\' . $system . $table);
                }else{
                    $this->useAdd($this->CURD->config('namespace.model') . '\\' .  $this->replace['Table'] . ' as MyModel');
                    $table = 'MyModel';
                }
                $select_data = ", {$table}::addSoftDelWhere()->column('{$title}', '{$value}')";
            }
            $field = parse_name($field);
            $this->replace['form_data'][] = "FormData::{$type}('{$field}', '{$item['label']}'$select_data),";
        }
    }

    /**
     * table_page 数据
     */
    private function tablePage()
    {
        $this->replace['table_page'][] = "TableAux::column(['type' => 'checkbox']),";
        foreach ($this->CURD->data as $field => $item) {
            if (empty($item['show_type'])) {
                continue;
            }
            $this->quickSearch($field, $item);
            if(is_string($item['join']) && strpos($item['join'], ':') !== false
                && strpos($item['join'], '=') !== false) {

                list($table, $joinData) = explode(':', $item['join']);
                list($value, $title)    = explode('=', $joinData);
                $field                  = ($table ?: 'parent') . '_' . $title;
            }
            $image = $item['show_type'] === 'image' ? ", '@image'" : '';
            $this->replace['table_page'][] = "TableAux::column('{$field}', '{$item['label']}'{$image}),";
        }
    }

    /**
     * 快捷搜索的创建
     * @param string $field
     * @param array $datum
     */
    private function quickSearch(string $field, array $datum)
    {
        if (empty($datum['quick_search'])) {
            return;
        }
        if (!empty($datum['join']) && is_string($datum['join']) && strpos($datum['join'], ':') !== false){
            $table = explode(':', $datum['join'])[0] ?: $this->CURD->table;
            $field = explode('=', $datum['join'])[1];
            $this->replace['quick_search'][$field] = "'{$table}.{$field}%%' => '{$datum['label']}',";

        }elseif (in_array($this->CURD->field_info[$field]['data_type'], ['int', 'tinyint', 'smallint', 'bigint']) ) {
            $this->replace['quick_search'][$field] = "'$field' => '{$datum['label']}',";

        }else{
            $this->replace['quick_search'][$field] = "'$field%%' => '{$datum['label']}',";
        }
    }


    /**
     * 搜索处理
     */
    private function searchHandle()
    {
        foreach ($this->CURD->data as $field => $datum){
            if ($datum['show_type'] == 'image' || !$datum['show_type']){
                continue;
            }

            $this->useAdd(SearchForm::class);
            if (!is_array($datum['join']) && strpos($datum['join'], ':') !== false
                && strpos($datum['join'], '=') !== false) {
                $this->search(explode('=', $datum['join'])[1], $datum, explode(':', $datum['join'])[0]);
            }else{
                $this->search($field, $datum, 'i');
            }
        }
    }


    /**
     * @param string $field
     * @param array $data
     * @param string $alias
     */
    private function search(string $field, array $data, string $alias = 'i')
    {
        $alias = $alias ?: $this->CURD->table;
        $type = $this->CURD->field_info[parse_name($field)]['data_type'] ?? null;

        switch (true) {
            case is_array($data['join']):
                $this->selectSearch($field, $data['label'], $this->attrFieldGet($field), $alias);
                break;
            case empty($type):
                $this->TextLike($field, $data['label'], $alias);
                break;
            case in_array($type, ['date', 'datetime']):
                $this->timeRangeSearch($field, $data['label'], $alias);
                break;
            case in_array($type, ['int', 'tinyint', 'smallint' , 'bigint']):
                $this->Text($field, $data['label'], $alias);
                break;
            default:
                $this->TextLike($field, $data['label'], $alias);
        }
    }


    /**
     * @param string $field
     * @param string $placeholder
     * @param string $alias
     */
    private function timeRangeSearch(string $field, string $placeholder, string $alias)
    {
        $this->useAdd(TimeRange::class);

        $rangeSign = [
            'date'      => 'TimeRange::TYPE_DATE',
            'datetime'  => 'TimeRange::TYPE_DATETIME'
        ];

        $replace = [$alias, $field, $placeholder, $this->searchLabel(), $rangeSign[$this->CURD->field_info[$field]['data_type']]];
        $this->replace['search_form'][] = sprintf("SearchForm::TimeRange(\"%s.%s_~\", \"%s\")%s->html(%s),", ...$replace);
    }

    /**
     * @param string $field
     * @param string $placeholder
     * @param array|string $data
     * @param string $alias
     */
    private function selectSearch(string $field, string $placeholder, $data, string $alias)
    {
        $replace = [$alias, $field, $placeholder, $this->searchLabel(), $data];
        $this->replace['search_form'][] = sprintf("SearchForm::Select('%s.%s', \"%s\")%s->html(%s),", ...$replace);
    }

    /**
     * @param string $field
     * @param string $placeholder
     * @param string $alias
     */
    private function Text(string $field, string $placeholder, string $alias)
    {
        $replace = [$alias, $field, $placeholder, $this->searchLabel()];
        $this->replace['search_form'][] .= sprintf("SearchForm::Text('%s.%s', \"%s\")%s->html(),", ...$replace);
    }

    /**
     * @param string $field
     * @param string $placeholder
     * @param string $alias
     */
    private function TextLike(string $field, string $placeholder, string $alias)
    {
        $this->Text($field .'%%', $placeholder, $alias);
    }


    /**
     * 搜索是否需要label
     * @return string
     */
    private function searchLabel()
    {
        return $this->CURD->config('list_search_label') ? "->label(true)" : "";
    }

    /**
     * 多属性字段获取
     * @param $field
     * @return string
     */
    private function attrFieldGet(string $field)
    {
        $field = parse_name($field, 1);
        $this->useAdd($this->CURD->config('namespace.model') . '\\'  . $this->replace['Table'] . ' as MyModel');
        return "MyModel::get{$field}Sc(false)";
    }

}
