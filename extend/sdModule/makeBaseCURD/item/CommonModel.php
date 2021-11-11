<?php
/**
 * Date: 2020/10/20 11:12
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\makeBaseCURD\item;


use app\common\BaseModel;
use sdModule\layui\Layui;
use sdModule\makeBaseCURD\CURD;

class CommonModel extends Item
{
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
            'namespace' => $this->CURD->getNamespace($this->CURD->config('namespace.common_model')),
            'describe'  => $this->CURD->pageName ?: $this->CURD->tableComment
        ];

        $this->setSchema();
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
    private function setSchema()
    {
        $property = [];
        foreach ($this->CURD->fieldInfo as $field => $data) {
            $this->replace['schema'] .= "'{$field}' => '{$data['data_type']}'," . $this->CURD->indentation(2);
            $property[] = " * @property \${$field}";
        }
        $this->replace['property'] = implode("\r\n", $property);
    }

}
