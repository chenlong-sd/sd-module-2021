<?php
/**
 *
 * FormMake.php
 * User: ChenLong
 * DateTime: 2020/5/25 13:29
 */


namespace sdModule\layui\formMake;

use sdModule\layui\formMake\unit\Unit;

/**
 * @method static array makeUnitText(string $label)
 * @method static array makeUnitSelect(string $label, array $data)
 * @method static array makeUnitImage(string $label)
 * @method static array makeUnitImages(string $label)
 * @method static array makeUnitPassword(string $label)
 * @method static array makeUnitRadio(string $label, array $data)
 * @method static array makeUnitCheckbox(string $label, array $data)
 * @method static array makeUnitTextarea(string $label)
 * @method static array makeUnitTime(string $label, array $data)
 * @method static array makeUnitUEditor(string $label)
 * Class FormMake
 * @package sdModule\layui\formMake
 * @author  chenlong <vip_chenlong@163.com>
 */
class FormMake
{

    /**
     * @var string 表单内容
     */
    private $content = '';
    /**
     * @var string 表单的js代码
     */
    private $js;
    /**
     * @var string 页面默认js代码
     */
    private $default_js;
    /**
     * @var string 创建表单元素的命名空间
     */
    private $unit_namespace;
    /**
     * @var int 表单占页面宽度比例
     */
    private $lately;
    /**
     * @var string 表单风格
     */
    private $plan;

    /**
     * @var string 页面名字
     */
    private $title;


    /**
     * @param string $label
     * @param string $type
     * @param array  $data
     * @return array
     */
    public static function makeUnit(string $label, string $type, $data = [])
    {
        return compact('label', 'type', 'data');
    }

    /**
     * @param string $title 页面名字
     * @return FormMake
     */
    public static function title(string $title)
    {
        $instance = new self();
        $instance->unit_namespace = 'sdModule\\layui\\formMake\\unit\\';
        $instance->title = $title;
        return $instance;
    }

    /**
     * @param array $form_data 表单数据
     * @param array $default   默认值
     * @param int   $lately    页面比列
     * @param bool  $plan      方框风格
     * @return string
     */
    public function make(array $form_data, $default = [], $lately = 6, $plan = false)
    {
        $this->lately = $lately;
        $this->plan = $plan ? 'layui-form-pane' : '';
        foreach ($form_data as $field => $datum) {
            $unit = $this->unit_namespace . parse_name($datum['type'], 1);
            if (!class_exists($unit)) continue;

            /** @var Unit $unit_class */
            $unit_class = new $unit();
            property_exists($unit_class, 'data') and $unit_class->data = $datum['data'] ?? [];

            $this->content .= $unit_class->htmlCode($datum['label'], $field);
            $this->js .= $unit_class->jsCode($field);
            if (method_exists($unit_class, 'defaultJsCode')) {
                $this->default_js .= $unit_class->defaultJsCode($field, $default);
            }
        }

        return $this->html($default);
    }


    public function form()
    {
        return <<<HTML
    <form class="layui-form {$this->plan}" action="" lay-filter="sc" >
    {$this->content} 
    <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">{$this->lang('submit')}</button>
      <button type="reset" class="layui-btn layui-btn-primary">{$this->lang('reset')}</button>
    </div>
  </div></form>
HTML;
    }

    public function js($default)
    {
        return <<<HTML
    <script>
        {$this->js}
        {$this->defaultJs($default)}
        {$this->submitJsCode()}
    </script>
HTML;
    }

    public function defaultJs($default)
    {
        $content = json_encode($default, JSON_UNESCAPED_UNICODE);
        return <<<JSR
        (window.formVal = function(){
            layui.form.val('sc', $content);
            {$this->default_js}
            return false;
        })();
JSR;
    }

    public function html($default)
    {
        $base_dir = rtrim(strtr(dirname(request()->baseFile()), ['\\' => '/']), '/');
        $debug = env('APP_DEBUG');
        $editor_upload = config("admin.editor_upload");
        $upload_url = admin_url("image");
        $resource_url = url("system.system/resource");

        return <<<HTML

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link rel="stylesheet" href="{$base_dir}/admin_static/layui/css/layui.css" media="all" />
    <style>
        body{padding: 10px}
    </style>
    <script>
        const DEBUG = "{$debug}";
        const ROOT = "{$base_dir}";
        const EDITOR_UPLOAD = '{$editor_upload}';
        const UPLOAD_URL = '{$upload_url}';
        const RESOURCE_URL = '{$resource_url}';
        
        // 以下为表格的多语言设置
        const PAGE_TO = "{$this->lang('page_to')}";
        const PAGE_PAGE = "{$this->lang('page_page')}";
        const PAGE_TOTAL = function (num) {
            return "{$this->lang('page_total')}".replace(1, num);
        };
        const CONFIRM = "{$this->lang('confirm')}";
        const PAGE_ARTICLE = "{$this->lang('page_article')}";
        const FILTER_COLUMN = "{$this->lang('Filter column')}";
        const EXPORT = "{$this->lang('Export')}";
        const PRINT = "{$this->lang('print')}";
        const LOADING = "{$this->lang('loading')}";
        
        // layui的多语言设置
        const L_LANG = {
            confirm: "{$this->lang('confirm')}",
            clear: "{$this->lang('clear')}",
            upload_exception:"{$this->lang('layui upload_exception')}",
            upload_exception_1:"{$this->lang('layui upload_exception_1')}",
            upload_exception_json:"{$this->lang('layui upload_exception_json')}",
            file_format_error:"{$this->lang('layui file_format_error')}",
            video_format_error:"{$this->lang('layui video_format_error')}",
            audio_format_error:"{$this->lang('layui audio_format_error')}",
            image_format_error:"{$this->lang('layui image_format_error')}",
            max_upload:"{$this->lang('layui max_upload')}",
            file_exceed:"{$this->lang('layui file_exceed')}",
            file_a:"{$this->lang('layui file_a')}",
            shrink:"{$this->lang('layui shrink')}",
            require:"{$this->lang('layui require')}",
            phone:"{$this->lang('layui phone')}",
            email:"{$this->lang('layui email')}",
            link:"{$this->lang('layui link')}",
            number:"{$this->lang('layui number')}",
            date:"{$this->lang('layui date')}",
            id_card:"{$this->lang('layui id_card')}",
            select:"{$this->lang('layui select')}",
            unnamed:"{$this->lang('layui unnamed')}",
            no_data:"{$this->lang('layui no data')}",
            no_matching_data:"{$this->lang('layui No matching data')}",
            request_exception:"{$this->lang('layui require exception')}",
            response_error:"{$this->lang('layui response error')}",
            upload_failed:"{$this->lang('layui upload failed')}",
        }
    </script>
</head>
<body>
<!-- 主体部分 -->
<div class="layui-row">
    <div class="layui-col-md{$this->lately}">
        <div class="layui-card">
            <div class="layui-card-header">{$this->title}</div>
            <div class="layui-card-body">
                {$this->form()}
            </div>
        </div>
    </div>
</div>

</body>
<script type="text/javascript" src="{$base_dir}/admin_static/layui/layui.all.js"></script>

<script type="text/javascript" src="{$base_dir}/admin_static/editor/ueditor.config.js"></script>
<script type="text/javascript" src="{$base_dir}/admin_static/editor/ueditor.all.js"></script>

<!-- js 的一些配置 -->
<script>
    layui.config({
        base: '{$base_dir}/admin_static/layui/dist/'
    });
    let local = window.localStorage['layuiAdmin'];
    let alias = 'black';
    if (local != '{}') {
        alias = eval('(' + window.localStorage['layuiAdmin'] + ')').theme.color.alias;
        alias = alias === 'default' ? 'black' : alias;
    }
    layer.config({
        extend:alias +'/style.css'
        ,skin:'demo-class'
    });

    layui.use('notice',function () {
        window.layNotice = layui.notice;
    });

</script>

<script type="text/javascript" src="{$base_dir}/admin_static/js/custom.js"></script>

<!-- js 部分-->
{$this->js($default)}
</html>

HTML;

    }

    /**
     * @param $key
     * @return mixed|string
     */
    public function lang($key)
    {
        return lang($key);
    }


    /**
     * @param $method
     * @param $var
     * @return array|void
     */
    public static function __callStatic($method, $var)
    {
        if (substr($method, 0, 8) === 'makeUnit') {
            return ['label' => $var[0], 'data' => $var[1] ?? '', 'type' => substr($method, 8)];
        }
    }

    /**
     * 提交js代码
     * @return string
     */
    private function submitJsCode()
    {
        return <<<JSCODE

        layui.form.on('submit(formDemo)', function (data) {
            let load = custom.loading();
            console.log(data)
            layui.jquery.ajax({
                type: 'post'
                , data: data.field
                , success: function (res) {
                    layer.close(load);
                    if (res.code === 200) {
                        layNotice.success('{$this->lang('success')}');
                    } else {
                        layNotice.warning(res.msg);
                    }
                },
                error: function (err) {
                    layer.close(load);
                }
            });
            return false;
        })

JSCODE;

    }
}