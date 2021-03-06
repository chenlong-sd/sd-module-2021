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
class Model implements Item
{
    /**
     * @var CURD
     */
    private $CURD;

    /**
     * @var array 替换值
     */
    private $replace;

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
            $this->replace['attr'] .= <<<CODE

    /**
     * {$label}展示处理
     * @param \$value
     * @return string
     */   
    public function get{$field}Attr(\$value)
    {
        \$field = self::get{$field}Sc();
        
        return \$field[\$value] ?? \$value;
    }

CODE;
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
                ? implode($key  === "use" ? "\r\n" : $this->CURD->indentation(3), $value)
                : $value;
        }
        return $replace;
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
}
