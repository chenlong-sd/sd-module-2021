<?php


namespace sdModule\makeBaseCURD;

use app\common\ResponseJson;
use app\common\SdException;
use sdModule\makeBaseCURD\item\CommonModel;
use sdModule\makeBaseCURD\item\Controller;
use sdModule\makeBaseCURD\item\Enum;
use sdModule\makeBaseCURD\item\ItemI;
use sdModule\makeBaseCURD\item\Model;
use sdModule\makeBaseCURD\item\Page;
use sdModule\makeBaseCURD\item\Service;
use sdModule\makeBaseCURD\item\Validate;
use think\facade\App;
use think\facade\Db;

/**
 * 创建基础增删查改的类
 * Class CURD
 * @package sdModule\makeBaseCURD
 */
class CURD
{
    const CONTROLLER = 1;
    const MODEL = 2;
    const VALIDATE = 4;
    const PAGE = 8;
    const SERVICE = 16;

    /**
     * @var array 配置数据
     */
    private $config = [];

    /**
     * @var string|null 表名
     */
    public $table;
    /**
     * @var string|null 页面名字
     */
    public $pageName;

    /**
     * @var array|null 创建模块
     */
    public $makeModule;
    /**
     * @var null|array  字段数据
     */
    public $data;

    /**
     * @var array 表字段信息
     */
    public $fieldInfo;

    /**
     * @var string 表注释
     */
    public $tableComment;

    /**
     * @var string 创建子目录
     */
    public $childrenDir = '';

    /**
     * @var array|mixed 控制器可创建的方法
     */
    public $accessible;

    /**
     * @var bool 是否允许创建admin模块的model
     */
    public $isMakeAdminModel = false;

    /**
     * 开始工作
     * @return \think\response\Json|void
     * @throws SdException
     */
    public static function work()
    {
        $CURD = new self();
        $CURD->loadConfig();

        return $CURD->requestHandle();
    }

    /**
     * 请求处理
     * @return \think\response\Json|void
     * @throws SdException
     */
    private function requestHandle()
    {
        // 请求表的详细信息
        if (request()->get('table_name') !== null) {
            $tableFieldInfo = $this->getFieldInfo(request()->get('table_name'));
            return ResponseJson::mixin($tableFieldInfo ?: '请确认表名，没有查到该表信息');
        }

        // 检查该数据表关联的文件信息
        if (request()->get('table') !== null) {
            $this->fileCheck(request()->get('table'), request()->get('make'));
            return ResponseJson::success();
        }

        if (request()->isPost()) {
            return ResponseJson::success($this->run());
        }

        require_once __DIR__ . '/base.php';
    }

    /**
     * @return string
     * @throws SdException
     */
    private function run(): string
    {
        $this->data        = request()->post();
        $this->table       = $this->data['table_name'] ?? '';
        $this->pageName    = $this->data['page_name'] ?? '';
        $this->makeModule  = array_sum($this->data['make'] ?? []);
        $this->childrenDir = $this->data['children_dir'] ?? '';
        $this->accessible  = $this->data['accessible'] ?? [];

        unset($this->data['table_name'], $this->data['page_name'], $this->data['make'], $this->data['children_dir'], $this->data['accessible']);

        $this->fieldInfo    = array_column($this->getTableInfo($this->table), null, 'column_name');
        $this->tableComment = $this->getTableComment($this->table);

        $this->data = array_map(function($v){
            $v['join'] = json_decode($v['join'], true) ?: $v['join'];
            $v['join'] = is_array($v['join']) ? $this->joinDataHandle($v['join']) : $v['join'];
            return $v;
         }, $this->data);

        $this->fileGenerate();
        return '';
    }

    /**
     * join 关联处理
     * @param $data
     * @return array
     */
    private function joinDataHandle($data): array
    {
        $new_data = [];
        foreach ($data as $item) {
            list($key, $value) = explode('=', $item);
            $new_data[$key] = $value;
            // 有状态字段， 可能会有替换显示，允许创建admin模块的model
            $this->isMakeAdminModel = true;
        }
        return $new_data;
    }

    /**
     * 模块文件创建
     * @param ItemI|null $item
     * @return mixed|void
     * @throws SdException
     */
    private function fileGenerate(ItemI $item = null)
    {
        if ($item !== null) {
            return $item->make();
        }

        // 创建枚举
        foreach ($this->data as $field => $datum) {
            if (!empty($datum['join']) && is_array($datum['join'])) {
                $path = strtr($this->config('file_path.enum'), [
                    '{:class}' => '{:class}Enum' . parse_name($field, 1),
                ]);
                $this->moduleFileCreate((new Enum($this))->setField($field), $path);
            }
        }

        if (($this->makeModule & self::CONTROLLER) > 0)
            $this->moduleFileCreate(new Controller($this), $this->config("file_path." . self::CONTROLLER));

        if (($this->makeModule & self::MODEL) > 0) {
            $this->moduleFileCreate(new CommonModel($this), $this->config("file_path." . self::MODEL . ".common"));

            // 创建admin模块的model
            if ($this->isMakeAdminModel) {
                $this->moduleFileCreate(new Model($this), $this->config("file_path." . self::MODEL . ".admin"));
            }
        }

        if (($this->makeModule & self::VALIDATE) > 0)
            $this->moduleFileCreate(new Validate($this), $this->config("file_path." . self::VALIDATE));

        if (($this->makeModule & self::PAGE) > 0)
            $this->moduleFileCreate(new Page($this), $this->config("file_path." . self::PAGE));

        if (($this->makeModule & self::SERVICE) > 0)
            $this->moduleFileCreate(new Service($this), $this->config("file_path." . self::SERVICE));

    }

    /**
     * 模块文件创建
     * @param ItemI $model
     * @param string $path
     * @throws SdException
     */
    public function moduleFileCreate(ItemI $model, string $path)
    {
        // 子文件夹判断
        $children_dir   = $this->childrenDir ? strtr($this->childrenDir, ['\\' => '/']) . '/' : '';
        // 文件名替换
        $replace_pairs  = ['{:class}' => $children_dir . parse_name($this->table, 1)];
        // 文件内容获取
        $content        = $this->fileGenerate($model);
        // 文件路径获取
        $file_path      = $this->dirMake(strtr($path, $replace_pairs));

        // 写入代码到文件
        file_put_contents($file_path, $content);
    }

    /**
     * 创建文件夹并返回路径
     * @param $url
     * @return mixed
     */
    private function dirMake($url)
    {
        if (!is_dir(dirname($url))){
            mkdir(dirname($url), 0755, true);
        }
        return $url;
    }

    /**
     * 获取配置项
     * @param string $key 配置名
     * @param null $default 默认值
     * @return array|mixed|null
     */
    public function config(string $key, $default = null)
    {
        $value = $this->config;
        foreach (explode('.', $key) as $subKey) {
            if (!is_array($value)) {
                break;
            }
            $value = empty($value[$subKey]) ? $default : $value[$subKey];
        }
        return $value;
    }

    /**
     * 加载配置项
     */
    private function loadConfig()
    {
        $this->config = require_once __DIR__ . '/_config.php';
    }

    /**
     * 已存在的文件检查
     * @param string $table 表名
     * @param array $make_item 要创建的项目
     * @throws SdException
     */
    private function fileCheck(string $table, array $make_item)
    {
        $have_item = [];

        $this->childrenDir = request()->param('children_dir', '');
        $children_dir      = $this->childrenDir ? strtr($this->childrenDir, ['\\' => '/']) . '/' : '';
        $replace_pairs     = ['{:class}' => $children_dir . parse_name($table, 1)];
        $replace_pairs1    = ['{:table}' => $children_dir . parse_name($table)];
        foreach ($make_item as $item) {
            if (in_array($item, [self::CONTROLLER, self::VALIDATE, self::PAGE, self::SERVICE]) &&
                file_exists( $filename2 = strtr($this->config("file_path.{$item}"), $replace_pairs))) {
                $have_item[] = $filename2;
            } else if ($item == self::MODEL) {
                $filename  = strtr($this->config("file_path.{$item}.common"), $replace_pairs);
                $filename1 = strtr($this->config("file_path.{$item}.admin"), $replace_pairs);
                file_exists($filename)  and $have_item[] = $filename;
                file_exists($filename1) and $have_item[] = $filename1;

            }else {
                $strtr = strtr($this->config("file_path.{$item}"), $replace_pairs1);
                file_exists($strtr) and $have_item[] = $strtr;
            }
        }

        if ($have_item) {
            throw new SdException('以下文件已存在，确认覆盖吗？文件：<br/>' . implode('<br/>', $have_item));
        }
    }

    /**
     * 获取table的信息
     * @param string $table
     * @return array
     * @throws SdException
     */
    public function getTableInfo(string $table): array
    {
        $sql = "SELECT
            `column_name`, `data_type`, `column_comment`,`CHARACTER_MAXIMUM_LENGTH` length, `column_default`, `column_key`
             FROM 
             information_schema.columns 
             WHERE 
             table_schema = :table_schema 
             AND 
             `table_name` = :table_names
             ORDER BY ORDINAL_POSITION ASC
             ";

        try {
            return Db::query($sql, [
                'table_schema' => env('DATABASE.DATABASE'),
                'table_names'  => env('DATABASE.PREFIX') . $table
            ]);
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 获取表注释
     * @param string $table
     * @return string
     * @throws SdException
     */
    public function getTableComment(string $table): string
    {
        $sql = "SELECT table_comment FROM INFORMATION_SCHEMA.TABLES  WHERE TABLE_NAME = :table_names AND TABLE_SCHEMA = :schemas LIMIT 1";
        try {
            $comment = Db::query($sql, [
                'schemas'     => env('DATABASE.DATABASE'),
                'table_names' => env('DATABASE.PREFIX') . $table
            ]);
            return current(array_column($comment, 'table_comment'));
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 获取表主键
     * @param string $table
     * @return string
     * @throws SdException
     */
    public function getTablePrimary(string $table): string
    {
        $sql = "SELECT
                k.column_name
             FROM
                 information_schema.table_constraints t
             JOIN information_schema.key_column_usage k USING ( `constraint_name`,`table_schema`,`table_name`)
             WHERE
                 t.constraint_type = 'PRIMARY KEY'
             AND t.table_schema = :schemas
             AND t.table_name = :tables
             ";
        try {
            $column = Db::query($sql, [
                'schemas' => env('DATABASE.DATABASE'),
                'tables'  => env('DATABASE.PREFIX') . $table
            ]);
            return current(array_column($column, 'column_name'));
        } catch (\Throwable $exception) {
            throw new SdException($exception->getMessage());
        }
    }

    /**
     * 获取创建表格的详细信息
     * @param string $table
     * @return array
     * @throws SdException
     */
    public function getFieldInfo(string $table): array
    {
        $table_info = $this->getTableInfo($table);

        foreach ($table_info as &$item){
            $item = $this->writeDefaultFormType($item);
            $item = $this->listDefaultShowType($item);
            $item = $this->getJoinData($item);
            $item = $this->labelAndSelectData($item);
        }

        return $table_info;
    }

    /**
     * 数据写入式的默认表单
     * @param array $field_info
     * @return array
     */
    private function writeDefaultFormType(array $field_info): array
    {
        $default_form = [
            'char'      => 'text',
            'varchar'   => 'text',
            'datetime'  => 'datetime',
            'date'      => 'date',
            'text'      => 'editor',
            'int'       => 'text',
            'tinyint'   => 'select',
            'string'    => 'text'
        ];

        $except = ['id', 'delete_time', 'create_time', 'update_time'];

        if (in_array($field_info['column_name'] ,$except)){
            $field_info['form_type'] = '';
            return $field_info;
        }

        switch (true) {
            case strpos($field_info['column_name'],  'images') !== false:
                $field_info['form_type'] = 'images';
                break;

            case strpos($field_info['column_name'], 'password') !==  false:
                $field_info['form_type'] = 'password';
                break;

            case (bool)preg_match('/(img)|(image)|(avatar)|(cover)/', $field_info['column_name']):
                $field_info['form_type'] = 'image';
                break;

            case in_array($field_info['data_type'], ['char', 'var_char']) && $field_info['length'] > 255:
                $field_info['form_type'] = 'textarea';
                break;

            case $field_info['data_type'] == 'tinyint'
                && count(explode(',', strtr($field_info['column_comment'], ['，' => ',']))) <= 3:
                $field_info['form_type'] = 'radio';
                break;

            case isset($default_form[$field_info['data_type']]):

                $field_info['form_type'] = $default_form[$field_info['data_type']];
                break;

            default:
                $field_info['form_type'] = '';
        }

        return $field_info;
    }


    /**
     * 列表默认展示类型
     * @param array $field_info
     * @return array
     */
    private function listDefaultShowType(array $field_info): array
    {
        if (in_array($field_info['form_type'], ['images', 'editor', 'textarea', 'password'])) {
            $field_info['show_type'] = '';
        }elseif($field_info['form_type'] == 'image'){
            $field_info['show_type'] = 'image';
        }else{
            $field_info['show_type'] = 'text';
        }
        return $field_info;
    }

    /**
     * 获取关联数据
     * @param array $field_info
     * @return array
     * @throws SdException
     */
    private function getJoinData(array $field_info): array
    {
        if (substr($field_info['column_name'], -3) !== '_id' && $field_info['column_name'] !== 'pid'){
            return $field_info;
        }

        $table     = $field_info['column_name'] === 'pid' ? '' : substr($field_info['column_name'], 0, -3);
        $labelData = $this->getTableInfo($table ?: request()->get('table_name'));

        if (!$labelData) return $field_info;

        $primary = '';
        foreach ($labelData as $item) {
            if (in_array($item['data_type'], ['char', 'varchar'])) {
                empty($showField) and $showField = $item['column_name'];
            }
            if ($item['column_key'] == 'PRI' && empty($primary)) {
                $primary = $item['column_name'];
            }
        }

        $field_info['form_type'] = 'select';
        $field_info['join']      = empty($showField) ? '' : "{$table}:{$primary}={$showField}";

        return $field_info;
    }

    /**
     * 表单label和选择值处理
     * @param array $field_info
     * @return array
     */
    private function labelAndSelectData(array $field_info): array
    {
        $comment = strtr($field_info['column_comment'], ['：' => ':', '，' => ',']);
        $join = [];
        if (strpos($comment, ':') !== false) {
            list($field_info['column_comment'], $select_data) = explode(':', $comment);
            $arr = explode(',', $select_data);

            foreach ($arr as $item) {
                $join[] = strpos($item, '=') !== false
                    ? $item
                    : substr_replace($item, '=', 1, 0);
            }
        }

        $join and $field_info['join'] = $join;
        return $field_info;
    }

    /**
     * 缩进
     * @param int $number 缩进数量
     * @param string $before 缩进之前
     * @return string
     */
    public function indentation(int $number, string $before = "\r\n"): string
    {
        return $before . str_pad('', $number * 4, ' ');
    }

    /**
     * 获取命名空间
     * @param string $baseNamespace
     * @return string
     */
    public function getNamespace(string $baseNamespace): string
    {
        $childrenNamespace = $this->childrenDir ? "\\{$this->childrenDir}" : "";
        return $baseNamespace . $childrenNamespace;
    }
}

