<?php
/**
 * datetime: 2021/12/10 13:05
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\moduleSetProxy;

use sdModule\layui\lists\module\Ajax;
use sdModule\layui\lists\module\Column as ColumnAlias;

/**
 * Class Column
 * @method static Column checkbox(string $title = '')
 * @method static Column radio(string $title, string $field = '')
 * @method static Column normal(string $title, string $field = '')
 * @method static Column numbers(string $title, string $field = '')
 * @method static Column space(string $title, string $field = '')
 * @package sdModule\layui\lists\moduleSetProxy
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/12/10
 */
class Column implements \ArrayAccess
{

    /**
     * @var ColumnAlias
     */
    private $column;

    /**
     * 添加排序
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function addSort(): Column
    {
        $this->column->addSort();
        return $this;
    }

    /**
     * 设置为开关显示
     * @param string $field
     * @param array $valueMapping
     * @param Ajax|null $js_code
     * @return $this
     * @throws \app\common\SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function showSwitch(string $field = '', array $valueMapping = [], ?Ajax $js_code = null): Column
    {
        $this->column->showSwitch($field, $valueMapping, $js_code);
        return $this;
    }


    /**
     * 设置展示为图片
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public function showImage(): Column
    {
        $this->column->showImage();
        return $this;
    }

    /**
     * 更多参数配置
     * @param array|string $configuration
     * @author chenlong<vip_chenlong@163.com>
     * @see https://www.layui.com/doc/modules/table.html#cols
     * @example   width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     * @date 2021/9/18
     */
    public function moreConfiguration($configuration, $value = null): Column
    {
        $this->column->moreConfiguration($configuration, $value);
        return $this;
    }


    /**
     * 设置展示模板
     * @param string|callable $js_code
     * @return $this
     */
    public function setTemplate($js_code): Column
    {
        $this->column->setTemplate($js_code);
        return $this;
    }

    /**
     * 设置格式化输出
     * @param string $format 格式 {var} var 为字段名字 eg：  姓名：{name},年龄：{age}
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/4
     */
    public function setFormat(string $format): Column
    {
        $this->column->setFormat($format);
        return $this;
    }

    /**
     * 静态创建基本列配置
     * @param string $name 列类型
     * @param array  $arguments 参数
     * @return Column
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/18
     */
    public static function __callStatic($name, $arguments)
    {
        $column = new self();
        $column->column = ColumnAlias::$name(...$arguments);
        return $column;
    }

    /**
     * @param $name
     * @return mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function __get($name)
    {
        return $this->column->$name;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->column->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->column->offsetGet($offset);
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->column->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->column->offsetUnset($offset);
    }
}
