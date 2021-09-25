<?php
/**
 * Date: 2020/10/20 11:12
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use app\common\BaseModel;
use sdModule\layui\Layui;
use sdModule\makeBaseCURD\CURD;

class CommonModel implements Item
{
    /**
     * @var CURD
     */
    private $CURD;
    /**
     * @var array 替换值
     */
    private $replace;

    /**
     * CommonModel constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD    = $CURD;
        $this->replace = [
            'Table'     => parse_name($this->CURD->table, 1),
            'date'      => datetime(),
            'schema'    => '',
            'attr'      => '',
            'use'       => '',
            'property'  => '',
            'namespace'    => $this->CURD->getNamespace($this->CURD->config('namespace.common_model')),
            'describe'        => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->getSchema();
        $this->getAttr();
    }

    /**
     * 创建
     * @return mixed|void
     */
    public function make()
    {
        $file_content = file_get_contents($this->CURD->config('template.common_model'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }

    /**
     * 获取字段
     */
    private function getSchema()
    {
        $property = [];
        foreach ($this->CURD->fieldInfo as $field => $data) {
            $this->replace['schema'] .= "'{$field}' => '{$data['data_type']}'," . $this->CURD->indentation(2);
            $property[] = " * @property \${$field}";
        }
        $this->replace['property'] = implode("\r\n", $property);
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
     * 获取属性替换
     */
    private function getAttr()
    {
        $colors = ['red', 'orange', 'green', 'cyan', 'blue', 'black', 'gray', 'rim'];
        shuffle($colors);
        foreach ($this->CURD->data as $field => $data) {
            if (empty($data['join']) || !is_array($data['join'])){
               continue;
            }
            $field = parse_name($field, 1);
            $this->useAdd(Layui::class);

            $attr = [
                'tag'    => '',
                'no_tag' => ''
            ];
            reset($colors);
            foreach ($data['join'] as $val => $label) {
                $color = next($colors) ?: "customColor" . $this->getRandColor();
                $attr['tag']    .= sprintf("'%s' => Layui::tag()->%s('%s'),%s", $val, $color, $label, $this->CURD->indentation(4));
                $attr['no_tag'] .= sprintf("'%s' => '%s',%s", $val, $label, $this->CURD->indentation(4));
            }

            $this->replace['attr'] .= <<<CODE

    /**
     * {$data['label']}返回值处理
     * @param bool \$tag
     * @return array
     */   
    public static function get{$field}Sc(bool \$tag = true): array
    {
        return \$tag === true 
            ? [
                {$attr['tag']}
            ]
            : [
                {$attr['no_tag']}
            ];
    }

CODE;

        }
    }

    /**
     * 获取随机颜色
     * @return string
     */
    private function getRandColor()
    {
        $base_color = array_merge(range(0, 9), range('A', 'F'));
        shuffle($base_color);
        return implode(array_slice($base_color, 0, 6));
    }

    /**
     * @param string $useClass
     */
    private function useAdd(string $useClass)
    {
        $use = "use {$useClass};";
        $this->replace['use'] = $this->replace['use'] ?: [];
        in_array($use, $this->replace['use'] ?? []) or $this->replace['use'][] = $use;
    }
}
