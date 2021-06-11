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
    private bool $line_mode = false;

    private array $data = [];

    private array $field = [];

    private string $title = '';

    private array $image_field = [];

    private array $custom_field = [];

    private array $field_attr = [];

    private array $field_content_attr = [];

    private array $table = [];

    private string $root = '';

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
                $content = $row[$field] ?: '——';
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function isLineMode(): bool
    {
        return $this->line_mode;
    }

    /**
     * @param bool $line_mode
     * @return Table
     */
    public function setLineMode(bool $line_mode): Table
    {
        $this->line_mode = $line_mode;
        return $this;
    }
}
