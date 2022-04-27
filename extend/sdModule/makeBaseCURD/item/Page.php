<?php
/**
 * Date: 2020/11/25 15:18
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use sdModule\layui\form4\FormUnit;
use sdModule\makeBaseCURD\CURD;

class Page extends Item
{
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
            'quick_search'  => [],
            'use'           => '',
            'namespace'     => $this->CURD->getNamespace($this->CURD->config('namespace.page')),
            'describe'      => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->formData();
        $this->tablePage();
        $this->searchHandle();

        if ($this->replace['search_form']) {
            $this->listSearchFormDataMethodCode();
        }
    }

    /**
     * 创建文件
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function make(): string
    {
        $file_content = file_get_contents($this->CURD->config('template.page'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 列表搜索表单函数的代码
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/11
     */
    private function listSearchFormDataMethodCode()
    {
        $form = implode($this->CURD->indentation(4), $this->replace['search_form']);
        $this->replace['search_form'] = <<<CODE
    /**
     * 创建列表搜索表单的数据
     * @return Form
     */
    public function listSearchFormData(): Form
    {
        \$form_data = [
            FormUnit::group(
                $form
            ),
        ];
        
        return Form::create(\$form_data)->setSearchSubmitElement();
    }

CODE;
    }

    /**
     * 表单数据
     * @throws \app\common\SdException
     */
    private function formData()
    {
        $primary_key = $this->CURD->getTablePrimary($this->CURD->table);
        $this->useAdd(FormUnit::class);
        foreach ($this->CURD->data as $field => $item) {
            $type = $item['type'] === 'editor' ? "uEditor" : $item['type'];
            if ($field === $primary_key){
                $this->replace['form_data'][] = "FormUnit::hidden('{$field}'),";
                continue;
            }

            if (empty($type)){
                continue;
            }

            $select_data = '';
            if ($item['join'] && is_array($item['join'])){
                $field       = parse_name($field, 1);
                $this->useAdd($this->CURD->getNamespace($this->CURD->config('namespace.enum')) . "\\{$this->replace['Table']}Enum$field");
                $select_data = "->options({$this->replace['Table']}Enum$field::getAllMap(true))";
            }elseif (in_array($type, ['date', 'time', 'month', 'range'])){
                $select_data = $type === 'range' ? "->dateType('date', '~')" : "->dateType('$type')";
                $type        = 'time';
            }else if (strpos($item['join'], ':') !== false
                && strpos($item['join'], '=') !== false){

                list($table, $joinData) = explode(':', $item['join']);
                list($value, $title)    = explode('=', $joinData);

                $table = parse_name($table ?: $this->CURD->table, 1);
                if ($table != parse_name($this->CURD->table, 1)){
                    if (in_array($table, ['Administrators', 'Role'])) {
                        $this->useAdd($this->CURD->config('namespace.model') . '\\system\\' . $table);
                    }else{
                        class_exists($this->CURD->config('namespace.model') . '\\' . $table)
                            ? $this->useAdd($this->CURD->config('namespace.model') . '\\' . $table)
                            : $this->useAdd($this->CURD->config('namespace.common_model') . '\\' . $table);
                    }
                }else{
                    $this->useModel();
                    $table = 'MyModel';
                }
                $select_data = "->options({$table}::column('{$title}', '{$value}'))";
            }
            $field = parse_name($field);
            $this->replace['form_data'][] = "FormUnit::$type('$field', '{$item['label']}')$select_data,";
        }
    }

    /**
     * table_page 数据
     */
    private function tablePage()
    {
        $this->replace['table_page'][] = "Column::checkbox(),";
        foreach ($this->CURD->data as $field => $item) {
            if (empty($item['show_type'])) {
                continue;
            }
            if(is_string($item['join']) && strpos($item['join'], ':') !== false
                && strpos($item['join'], '=') !== false) {

                list($table, $joinData) = explode(':', $item['join']);
                list($value, $title)    = explode('=', $joinData);
                $field                  = ($table ?: 'parent') . '_' . $title;
            }
            $image = $item['show_type'] === 'image' ? "->showImage()" : '';
            $this->replace['table_page'][] = "Column::normal('{$item['label']}', '$field')$image,";
        }
    }

    /**
     * 搜索处理
     */
    private function searchHandle()
    {
        foreach ($this->CURD->data as $field => $datum){
            if ($datum['show_type'] == 'image' || empty($datum['quick_search'])){
                continue;
            }

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
        $type = $this->CURD->fieldInfo[parse_name($field)]['data_type'] ?? null;

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
        $replace = [$alias, $field, $this->CURD->fieldInfo[$field]['data_type'], $placeholder];
        $this->replace['search_form'][] = sprintf("FormUnit::time('%s.%s_~',)->dateType('%s', '~')->placeholder('%s'),", ...$replace);
    }

    /**
     * @param string $field
     * @param string $placeholder
     * @param array|string $data
     * @param string $alias
     */
    private function selectSearch(string $field, string $placeholder, $data, string $alias)
    {
        $replace = [$alias, $field, $placeholder, $data];
        $this->replace['search_form'][] = sprintf("FormUnit::select('%s.%s')->placeholder('%s')->options(%s),", ...$replace);
    }

    /**
     * @param string $field
     * @param string $placeholder
     * @param string $alias
     */
    private function Text(string $field, string $placeholder, string $alias)
    {
        $replace = [$alias, $field, $placeholder];
        $this->replace['search_form'][] .= sprintf("FormUnit::text('%s.%s')->placeholder('%s'),", ...$replace);
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
     * 多属性字段获取
     * @param string $field
     * @return string
     */
    private function attrFieldGet(string $field): string
    {
        $field = parse_name($field, 1);
        $this->useAdd($this->CURD->getNamespace($this->CURD->config('namespace.enum')) . '\\'  . $this->replace['Table'] . 'Enum' .parse_name($field, 1));
        return $this->replace['Table'] . 'Enum' .parse_name($field, 1) . "::getMap(true)";
    }

}
