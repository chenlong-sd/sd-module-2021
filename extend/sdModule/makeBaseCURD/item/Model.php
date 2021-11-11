<?php
/**
 * Date: 2020/10/20 13:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use sdModule\makeBaseCURD\CURD;

/**
 * Class Model
 * @package sdModule\makeBaseCURD\item
 */
class Model extends Item
{

    public function __construct(CURD $CURD)
    {
        $this->CURD    = $CURD;
        $this->replace = [
            'Table'           => parse_name($this->CURD->table, 1),
            'date'            => datetime(),
            'schema'          => '',
            'attr'            => '',
            'use'             => '',
            'search_form'     => [],
            'namespace'       => $this->CURD->getNamespace($this->CURD->config('namespace.model')),
            'commonNamespace' => $this->CURD->getNamespace($this->CURD->config('namespace.common_model')),
            'describe'        => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->getAttr();
    }

    /**
     * 创建文件
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    public function make(): string
    {
        $file_content = file_get_contents($this->CURD->config('template.model'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 属性值获取
     */
    private function getAttr()
    {
        foreach ($this->CURD->data as $field => $item) {
            if (empty($item['join']) || !is_array($item['join'])){
                continue;
            }
            $label = $item[$field]['label'] ?? '';
            $field = parse_name($field, 1);

            $this->useAdd($this->CURD->getNamespace($this->CURD->config('namespace.enum')) . "\\{$this->replace['Table']}Enum$field");

            $this->replace['attr'] .= <<<CODE

    /**
     * {$label}展示处理
     * @param \$value
     * @return string
     * @throws \Exception
     */   
    public function get{$field}Attr(\$value): string
    {
        return {$this->replace['Table']}Enum$field::create(\$value)->getDes();
    }

CODE;
        }
    }
}
