<?php
/**
 * datetime: 2021/11/11 11:50
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\makeBaseCURD\item;

use sdModule\common\Sc;
use sdModule\layui\Layui;
use sdModule\makeBaseCURD\CURD;

class Enum extends Item
{
    /**
     * 初始化
     * Item constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD)
    {
        $this->CURD = $CURD;
        $this->replace = [
            'Table'         => parse_name($this->CURD->table, 1),
            'date'          => date('Y-m-d H:i:s'),
            'use'           => [],
            'map'           => [],
            'const'         => [],
            'namespace'     => $this->CURD->getNamespace($this->CURD->config('namespace.enum')),
            'describe'      => $this->CURD->pageName ?: $this->CURD->tableComment
        ];
    }

    /**
     * @param string $field
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/11
     */
    public function setField(string $field): Enum
    {
        $this->replace['Table'] .= "Enum" . parse_name($field, 1);
        $this->enumFieldSet($field);
        return $this;
    }

    /**
     * 枚举字段设置
     * @param string $field
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/11
     */
    private function enumFieldSet(string $field)
    {
        $colors = ['red', 'orange', 'green', 'cyan', 'blue', 'black', 'gray', 'rim'];
        shuffle($colors);
        foreach ($this->CURD->data[$field]['join'] as $key => $des) {
            $constName = strtoupper(Sc::pinyin()->getPinyin($des));
            $this->replace['const'][] = "const $constName = $key;";
            $customColor = '';
            if (!$color = next($colors)) {
                $color = "customColor";
                $customColor = "\"{$this->getRandColor()}\", ";
            }
            $this->useAdd(Layui::class);
            $this->replace['map'][] = "self::$constName => Layui::tag()->$color($customColor\"$des\"),";
        }
    }

    /**
     * @return mixed 创建
     */
    public function make()
    {
        if ($this->replace['map']) $this->mapMethodCode();
        if ($this->replace['const']) $this->replace['const'] = implode($this->CURD->indentation(1), $this->replace['const']);

        $file_content = file_get_contents($this->CURD->config('template.enum'));

        return "<?php\r\n" . strtr($file_content, $this->replaceHandle());
    }


    /**
     * 获取随机颜色
     * @return string
     */
    private function getRandColor(): string
    {
        $base_color = array_merge(range(0, 9), range('A', 'F'));
        shuffle($base_color);
        return implode(array_slice($base_color, 0, 6));
    }

    /**
     * 值映射的代码
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/11
     */
    private function mapMethodCode()
    {
        $map = implode($this->CURD->indentation(3), $this->replace['map']);

        $this->replace['map'] = <<<CODE

    /**
     * 设置描述映射
     * @return array
     */
    protected static function map(): array
    {
        // TODO 常量名字取的拼音，需要请更改为对应英语
        return [
            $map
        ];
    }

CODE;
    }
}