<?php
/**
 * Date: 2021/5/31 16:30
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui;

/**
 * Class Dom
 * @package sdModule\common\helper
 * @author chenlong <vip_chenlong@163.com>
 * @date 2021/5/31
 */
class Dom
{
    /**
     * @var string 元素标签
     */
    private $tag = 'div';

    /**
     * @var array 元素标签属性
     */
    public $attr = [];

    /**
     * @var string  元素标签ID属性
     */
    public $id = '';

    /**
     * @var array 元素标签class属性
     */
    public $class = [];

    /**
     * @var array 元素标签data-*属性
     */
    public $data = [];

    /**
     * @var array|Dom[] 内容
     */
    public $content = [];

    /**
     * @var bool 是否是单标签
     */
    public $isSingleLabel = false;

    /**
     * Dom constructor.
     * @param string $tag
     */
    public function __construct(string $tag = 'div')
    {
        $this->tag = $tag;
    }

    /**
     * 创建标签
     * @param string $tag
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public static function create(string $tag = 'div'): Dom
    {
        return new self($tag);
    }

    /**
     * 添加元素属性
     * @param array|string $attr
     * @param string|null $value
     * @return Dom
     */
    public function addAttr($attr, string $value = null): Dom
    {
        is_array($attr)
            ? $this->attr = array_merge($this->attr, $attr)
            : $this->attr[$attr] = $value;
        return $this;
    }

    /**
     * 设置ID
     * @param string $id
     * @return Dom
     */
    public function setId(string $id): Dom
    {
        $this->id = $id;
        return $this;
    }

    /**
     * 添加Class
     * @param array|string $class
     * @return Dom
     */
    public function addClass($class): Dom
    {
        is_array($class)
            ? $this->class = array_merge($this->class, $class)
            : $this->class[] = $class;
        return $this;
    }

    /**
     * 添加data数据
     * @param array|string $data
     * @param string|null $value
     * @return Dom
     */
    public function addData($data, string $value = null): Dom
    {
        is_array($data)
            ? $this->data = array_merge($this->data, $data)
            : $this->data[$data] = $value;
        return $this;
    }

    /**
     * 添加内容
     * @param string|Dom $content
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function addContent($content): Dom
    {
        $this->content[] = $content;
        return $this;
    }

    /**
     * 替换属性设置
     * @param string $attr
     * @param $value
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function replaceSet(string $attr, $value): Dom
    {
        $this->$attr = $value;
        return $this;
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function __toString(): string
    {
        $attr = $this->domAttrMake();

        if ($this->isSingleLabel) {
            return sprintf('<%s %s/>', $this->tag, $attr);
        }

        return sprintf('<%s %s>%s</%1$s>', $this->tag, $attr, implode(' ', $this->content));
    }

    /**
     * 设置单标签
     * @param bool $isSingleLabel
     * @return Dom
     */
    public function setIsSingleLabel(bool $isSingleLabel = true): Dom
    {
        $this->isSingleLabel = $isSingleLabel;
        return $this;
    }

    /**
     * 属性字符串构建
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    private function domAttrMake(): string
    {
        $attrArr = [];
        $this->id and $attrArr[] = "id=\"{$this->id}\"";
        $attr  = $this->attrMake() and $attrArr = array_merge($attrArr, $attr);
        $data  = $this->dataMake() and $attrArr = array_merge($attrArr, $data);
        $class = implode(' ', $this->class) and $attrArr[] = "class=\"{$class}\"";

        return implode(' ', $attrArr);
    }

    /**
     * 属性构建
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    private function attrMake(): array
    {
        $attrArr = [];
        foreach ($this->attr as $attr => $value) {
            if (($attr == 'id' && $this->id) || ($attr == 'class' && $this->class)) {
                continue;
            }
            $attrArr[] = "{$attr}=\"{$value}\"";
        }
        return $attrArr;
    }

    /**
     * data 构建
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    private function dataMake(): array
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            $data[] = "data-{$key}=\"{$value}\"";
        }
        return $data;
    }
}
