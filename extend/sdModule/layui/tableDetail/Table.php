<?php
/**
 * Date: 2020/12/4 17:57
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tableDetail;

/**
 * Class Table
 * @method Table data(array $data) 设置数据
 * @method Table field(array $field) 设置数据字段对应的描述label,同时会根据此来做展示顺序及行,二维数组
 * @example $table->field([
 *         [
 *          'title' => '标题'，
 *          'name' => '名字',
 *          'name(3:2)'  => '名字', // 表示内容合并3行2列
 *          'name(1:2|3:2)'  => '名字', // 表示内容合并3行2列,面熟 label 合并1行2列
 *          ]
 * ]);
 * @method Table imageField(array $field) 设置展示图片的字段,  ['cover', 'banner'],自动判断多图，多图以逗号隔开
 * @method Table customField(array $field) 设置自定义html的字段, ['title' => '<p>{var}</p>'],{var} 为替换符号，会替换为当前字段值
 * @method Table fieldAttr(array $attr) 设置字段对应的td的HTML属性
 * @method Table fieldContentAttr(array $attr) 设置字段内容对应的td的HTML属性
 * @package sdModule\layui\tableDetail
 */
class Table
{
    /**
     * 是否是多行数据模式
     * @var bool
     */
    private $line_mode = false;
    /**
     * 数据
     * @var array
     */
    private $data = [];
    /**
     * 字段数据
     * @var array
     */
    private $field = [];
    /**
     * 内容标题
     * @var string
     */
    private $title = '';
    /**
     * 图片字段合集
     * @var array
     */
    private $image_field = [];
    /**
     * 自定义字段
     * @var array
     */
    private $custom_field = [];
    /**
     * 字段属性
     * @var array
     */
    private $field_attr = [];
    /**
     * 显示内容属性
     * @var array
     */
    private $field_content_attr = [];
    /**
     * 表集合
     * @var array
     */
    private $table = [];
    /**
     * 根路径
     * @var string
     */
    private $root = '';

    /**
     * 创建
     * @param string $title 表格标题
     * @param bool $line_mode 表格数据是否为列模式，
     * @return Table
     */
    public static function create(string $title, bool $line_mode = false)
    {
        $table = new self();
        $table->title = $title;
        $table->line_mode = $line_mode;
        $table->root = strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']);
        return $table;
    }

    /**
     * 完成构建
     * @return $this
     */
    public function complete()
    {
        $this->fieldHandle();

        return $this;
    }


    /**
     * 获取table
     * @return array
     */
    public function getTable(): array
    {
        return $this->table;
    }

    public function __call($method, $vars): Table
    {
        $method = parse_name($method);
        $this->$method = current($vars);
        return $this;
    }

    /**
     * 字段处理
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    private function fieldHandle()
    {
        $this->table = [];
        if ($this->line_mode) {
            $this->table[] = $this->multipleColumnsRowHandle([], true);
            foreach ($this->data as $row){
                $this->table[] = $this->multipleColumnsRowHandle($row);
            }
        }else{
            foreach ($this->field as $tr) {
                $this->table[] = $this->trHandel($tr);
            }
        }
    }

    /**
     * tr 处理
     * @param array $trField
     * @return array
     */
    private function trHandel(array $trField): array
    {
        $tr = [];
        foreach ($trField as $field => $title){
            [
                'field'         => $field,
                'content_attr'  => $content_attr,
                'field_attr'    => $field_attr
            ] = $this->mergeHandle($field);

            $content = $this->data[$field] ?? '——';

            if (!empty($this->data[$field]) && in_array($field, $this->image_field)){
                $img_html = '';
                foreach (explode(',', strtr($this->data[$field], ['，' => ','])) as $image){
                    $url = preg_match('/^http/', $image) ? $image : $this->root . $image;
                    $img_html .= "<div class='img-table layui-inline'><img src='{$url}'/></div>";
                }
                $content = $img_html;
            } elseif (isset($this->custom_field[$field])) {
                $content = strtr($this->custom_field[$field], ['{var}' => $content]);
            }

            if (isset($this->field_attr[$field])) {
                $field_attr .= " {$this->field_attr[$field]}";
            }

            if (isset($this->field_content_attr[$field])){
                $content_attr .= " {$this->field_content_attr[$field]}";
            }

            if (isset($this->field_attr['-'])) {
                $field_attr = $this->mergeCss($field_attr, $this->field_attr['-']);
            }

            if (isset($this->field_content_attr['-'])) {
                $content_attr .= " {$this->field_content_attr['-']}";
            }

            $tr[] = compact('field', 'field_attr', 'content_attr', 'content', 'title');
        }

        return $tr;
    }

    /**
     * 合并 Css
     * @param string $attr1
     * @param string $attr2
     * @return string
     */
    private function mergeCss(string $attr1, string $attr2): string
    {
        if (strpos($attr1, 'style=') !== false && strpos($attr2, 'style=') !== false ) {
            preg_match('/style=("|\')(.*)("|\')/', $attr2, $match1);
            $attr2 = strtr($attr2, [current($match1) => '']);
            $attr1 = preg_replace('/(style=)("|\')(.*)("|\')/', '${1}${2}' . ($match1[2] ?? '') . ';${3}${4}', $attr1);
        }

        return "{$attr1} {$attr2}";
    }

    /**
     * 字段合并处理
     * @param string $field
     * @return array
     */
    private function mergeHandle(string $field): array
    {
        $content_attr = $field_attr = '';

        if (preg_match('/\(([1-9]|\||:)+\)/', $field, $match)){
            $field = substr($field, 0, strpos($field, '('));
            $merge = strtr(current($match), ['(' => '', ')' => '']);
            $merge = explode('|', $merge);

            if (count($merge) <= 1) {
                list($line, $row) = array_pad(explode(':', $merge[0]), 2, 1);
                $content_attr = "colspan=\"{$line}\"  rowspan=\"{$row}\"";
                $field_attr   = "";
            }else{
                list($line, $row) = array_pad(explode(':', $merge[1]), 2, 1);
                $content_attr = "colspan=\"{$line}\"  rowspan=\"{$row}\"";
                list($line, $row) = array_pad(explode(':', $merge[0]), 2, 1);
                $field_attr   = "colspan=\"{$line}\"  rowspan=\"{$row}\"";
            }
        }

        return compact('field', 'content_attr', 'field_attr');
    }

    /**
     * 多列数据的行处理
     * @param array $row
     * @param bool $is_title
     * @return array
     */
    private function multipleColumnsRowHandle(array $row, bool $is_title = false): array
    {
        $tr = [];
        foreach ($this->field as $field => $title){
            if ($is_title){
                $field_attr  = $this->field_attr[$field] ?? '';
                $field_attr .= $this->field_attr['-'] ?? '';
                $content = $title;
                $content_attr = '';
            }else{
                $content_attr  = $this->field_content_attr[$field] ?? '';
                $content_attr .= $this->field_content_attr['-'] ?? '';
                $field_attr = '';
                $content = ($row[$field] || $row[$field] == 0) ? $row[$field] : '——';
                if (!empty($row[$field]) && in_array($field, $this->image_field)){
                    $img_html = '';
                    foreach (explode(',', strtr($row[$field], ['，' => ','])) as $image){
                        $url = preg_match('/^http/', $image) ? $image : $this->root . $image;
                        $img_html .= "<div class='img-table layui-inline'><img src='{$url}'/></div>";
                    }
                    $content = $img_html;
                }elseif (!empty($this->custom_field[$field])){
                    $content = strtr($this->custom_field[$field], ['{var}' => $content]);
                }
                $field = '';
            }
            $tr[] = compact('field', 'field_attr', 'content_attr', 'content', 'title');
        }
        return $tr;
    }

    /**
     * 获取标题
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 判断是否是多行数据
     * @return bool
     */
    public function isLineMode(): bool
    {
        return $this->line_mode;
    }

    /**
     * 设置多行数据
     * @param bool $line_mode
     * @return Table
     */
    public function setLineMode(bool $line_mode): Table
    {
        $this->line_mode = $line_mode;
        return $this;
    }
}
