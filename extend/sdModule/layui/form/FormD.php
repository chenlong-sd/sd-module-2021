<?php
/**
 * Date: 2020/9/25 18:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;

use app\common\SdException;
use sdModule\layui\form\formUnit\AuxTitle;
use sdModule\layui\form\formUnit\Selects;
use sdModule\layui\form\formUnit\UEditor;
use sdModule\layui\form\formUnit\UnitBase;

/**
 * Class Form
 * @package sdModule\layui\defaultForm
 * @deprecated
 */
class FormD
{
    private const UNIT_CLASS = "\\sdModule\\layui\\form\\formUnit\\%s";

    private const FORM_TEMPLATE   = '<div class="%s" %s>%s<div class="%s" >%s</div>%s</div>';
    private const ITEM_TEMPLATE   = '<div class="layui-form-item">%s</div>';
    private const LABEL_TEMPLATE  = '<label class="layui-form-label">%s</label>';
    private const TIP_TEMPLATE    = '<div class="layui-form-mid layui-word-aux">%s</div>';
    private const SCRIPT_TEMPLATE = '<script type="text/javascript" src="%s"></script>';
    private const SUBMIT_TEMPLATE = '<div class="layui-form-item"><div class="layui-input-block sc-submit"><button  class="layui-btn" lay-submit lay-filter="sc-form">%s</button><button id="close" class="layui-btn layui-btn-primary">%s</button></div></div>';

    /**
     * @var array 创建表单的数据
     */
    private array $form_data = [];
    /**
     * @var array 表单默认值数据
     */
    private array $default_data = [];
    /**
     * @var array 表单元素
     */
    private array $unit = [];
    /**
     * @var array js代码
     */
    private array $js = [];
    /**
     * @var array 加载的js
     */
    private array $load_js = [];
    /**
     * @var int 页面占比1 - 12
     */
    private int $custom_md = 0;

    /**
     * @var array 验证规则
     */
    private array $verify_rule = [];

    /**
     * @var array 短表单及提示语， key = name, value = 提示， eg: ['city' => '请选择城市']
     */
    private array $short_from_and_tip = [];

    /**
     * @var string 场景
     */
    private string $scene;

    /**
     * @var string 风格
     */
    private string $skin = '';

    /**
     * @var bool 不需要submit
     */
    private bool $noSubmit = false;

    /**
     * 创建表单
     * @param array $form_data  创建表单数据
     * @param string $scene     场景值
     * @return Form
     */
    public static function create(array $form_data, string $scene = '')
    {
        $form = new self();
        $form->form_data    = $form_data;
        $form->scene        = $scene;
        return $form;
    }

    /**
     * 设置表单风格
     * @param bool $pane
     * @return $this
     */
    public function setSkinToPane($pane = true)
    {
        $this->skin = $pane ? "layui-form-pane" : "";
        return $this;
    }

    /**
     * 获取表单风格
     * @return string
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * 完成创建
     * @return $this
     * @throws SdException
     * @throws \ReflectionException
     */
    public function complete()
    {
        $this->makeUnitCode();
        $this->form_data = $this->fieldMerge();
        return $this;
    }

    /**
     * 设置占用列
     * @param int $custom_md
     * @return Form
     */
    public function setCustomMd(int $custom_md = 12)
    {
        if (in_array($custom_md, range(1, 12))){
            $this->custom_md = $custom_md;
        }
        return $this;
    }

    /**
     * 设置短标签
     * @param array $tips key = name, value = 提示， eg: ['city' => '请选择城市']
     * @return Form
     */
    public function setShortFrom(array $tips)
    {
        $this->short_from_and_tip = $tips;
        return $this;
    }

    /**
     * 引入外部js
     * @param $js_path
     * @return $this
     */
    public function importJs($js_path)
    {
        $this->load_js[] = $this->assets($js_path);
        return $this;
    }

    /**
     * 获取html
     * @throws SdException
     * @throws \ReflectionException
     */
    private function makeUnitCode()
    {
        foreach ($this->form_data as $build_index => $item) {
            if ($item instanceof \Closure){                 // 处理行内标签
                foreach (call_user_func($item) as $items) {
                    $this->codeGenerate($items, $build_index);
                }
            }elseif(is_array($item)){                       // 处理表格表单
                $this->tableCode($item, $build_index);
            }else{
                $this->codeGenerate($item);
            }
        }
    }

    /**
     * @param $item
     * @param $build_index
     * @throws SdException
     * @throws \ReflectionException
     */
    private function tableCode($item, $build_index)
    {
        $code = [];                                 // 整个表格的代码
        foreach ($item as $items) {
            $line = [];                             // 一行的代码
            foreach ($items as $name => $item_){
                $attr = '';
                if (is_array($item_)){
                    list($item_, $attr) = $item_;
                }

                if ($item_ instanceof UnitData){
                    $item_ = $this->codeGenerate($item_, "T_{$build_index}");
                    $item_[array_key_first($item_)] = [current($item_), $attr];
                    $line = array_merge($line, $item_);
                }else{
                    $line[] = [$item_, $attr];
                }
            }
            $code[] = $line;
        }
        $key = is_string($build_index) ? $build_index : "T_{$build_index}";
        $this->unit[$key] = $code;
    }

    /**
     * 代码生成
     * @param UnitData $unit_item 表单项目数据
     * @param null|int $build_index 组合表单的索引
     * @return mixed|string|void
     * @throws SdException
     * @throws \ReflectionException
     */
    private function codeGenerate(UnitData $unit_item, $build_index = null)
    {
        if (in_array($this->scene, $unit_item->get('removeScene'))) {
            return;
        }

        $unit = $this->getUnitInstance($unit_item);
        $name = $unit_item->get('name');

        $unit->setData($unit_item->get('options'))
            ->setPreset($unit_item->get('defaultValue'))
            ->setConfig($unit_item->get('config'));

        if (property_exists($unit, 'default') && isset($this->default_data[$name])) {
            $unit->default = $this->default_data[$name];
        }

        $this->js[$name]   = $unit->getJs();

        if ($unit instanceof UEditor){
            $this->load_js['u-e_config'] = sprintf(self::SCRIPT_TEMPLATE, $this->assets("/admin_static/editor/ueditor.config.js"));
            $this->load_js['u-e_all']    = sprintf(self::SCRIPT_TEMPLATE, $this->assets("/admin_static/editor/ueditor.all.js"));
        }elseif ($unit instanceof Selects){
            $this->load_js['xm-selects'] = sprintf(self::SCRIPT_TEMPLATE, $this->assets("/admin_static/layui/dist/xm-select.js"));
        }

        $attr = $unit_item->get('inputAttr')[$this->scene] ?? '';
        $common_attr = $unit_item->get('inputAttr')['-'] ?? '' . ($unit_item->get('inputAttr')['*'] ?? '');
        $attr = implode(' ', [$attr, $common_attr]);

        if ($build_index !== null){
            if (is_string($build_index)){
                return [$name => $unit->getHtml($attr)];
            }

          $this->unit[$build_index][$name] = $unit->getHtml($attr);
        }else{
            $this->unit[$name] = $unit->getHtml($attr);
        }
    }

    /**
     * 获取表单元素的类
     * @param UnitData $unit_item
     * @return object|UnitBase
     * @throws SdException
     * @throws \ReflectionException
     */
    private function getUnitInstance(UnitData $unit_item)
    {
        $class_name = sprintf(self::UNIT_CLASS, parse_name($unit_item->get('type'), 1));
        if (!class_exists($class_name)){
            throw new SdException("表单类型【{$unit_item->get('type')}】不存在");
        }

        $unit = new \ReflectionClass($class_name);
        return $unit->newInstance($unit_item->get('name'), $unit_item->get('label'), $unit_item->get('placeholder'));
    }

    /**
     * 获取html内容
     * @return string
     */
    public function getHtml()
    {
        $html = '';
        foreach ($this->unit as $name => $item) {
            if (!is_array($item)){
                $html .= $this->itemHtml($name, $item);
                continue;
            }

            $html_item = '';
            if (is_string($name)){
                $html_item = $this->tableHtml($name, $item);
            }else{
                foreach ($item as $names => $items) {
                    $html_item .= $this->itemHtml($names, $items, true);
                }
            }

            $html .= sprintf(self::ITEM_TEMPLATE, $html_item);
        }

        return $this->noSubmit ? $html : $html . sprintf(self::SUBMIT_TEMPLATE, lang('submit'), lang('close'));
    }

    /**
     * tableForm的HTML代码创建
     * @param string $name
     * @param array $item
     * @return string
     */
    private function tableHtml(string $name, array $item)
    {
        $html_item = "<table class='layui-table'>";
        foreach ($item as $names => $items) {
            $html_item .= "<tr>";
            foreach ($items as $name_ => $item_){
                $style = isset($this->form_data[$name_]) ? '' : 'style="background-color:#f2f2f2"';
                list($item_, $attr) = $item_;
                $style = preg_match('/style="/', $attr) ? $attr : implode(" ",  [$attr, $style]);

                $html_item .= sprintf("<td %s>%s</td>", $style, $item_);
            }
            $html_item .= "</tr>";
        }
        $html_item .= "</table>";
        $label    = preg_match('/^T_.*/', $name) ? '' : $name;
        $is_label = preg_match('/^N_.*/', $name);

        return sprintf(self::FORM_TEMPLATE, 'layui-form-item', '', $is_label ? "" : sprintf(self::LABEL_TEMPLATE, $label), $is_label ? "" : 'layui-input-block', $html_item, '');
    }


    /**
     * 设置js代码
     * @param string $js
     * @return Form
     */
    public function setJs(string $js): Form
    {
        $this->js['custom-sc'] = $js;
        return $this;
    }

    /**
     * 设置没有提交
     * @return $this
     */
    public function setNoSubmit(): Form
    {
        $this->noSubmit = true;
        return $this;
    }


    /**
     * 搜索的提交html
     */
    public static function searchSubmit():string
    {
        return '<button  class="layui-btn" lay-submit lay-filter="sc-form">搜索</button>
                <button class="layui-btn layui-btn-normal" type="reset">重置</button>';
    }
    /**
     * 获取js代码
     * @return string
     */
    public function getJs()
    {
        return implode("\n\r", [
            implode("\n\r", $this->js),
            $this->getDefaultValueJs(),
            $this->submitJs()
        ]);
    }

    /**
     * 获取表单单元的js
     * @return string
     */
    public function getUnitJs()
    {
        return implode("\n\r", $this->js);
    }

    /**
     * 需要加载的js代码
     * @return string
     */
    public function loadJs()
    {
        return implode("\r\n", $this->load_js);
    }

    /**
     * 表单默认值代码
     * @return false|string
     */
    public function getDefaultValueJs()
    {
        $default = json_encode($this->default_data, JSON_UNESCAPED_UNICODE);
        return <<<JS
    layui.form.val('sd', {$default});
    for(let d in defaultData){
        if (defaultData.hasOwnProperty(d) && typeof defaultData[d] === 'function'){
            defaultData[d]();
        }
    }
JS;

    }

    /**
     * 表单元素的html
     * @param string $name 表单name
     * @param string $item 表单的子元素html
     * @param bool $inline 是否为行内元素
     * @return string
     */
    private function itemHtml($name, $item, $inline = false)
    {
        /** @var UnitData $unit */

        $unit = $this->form_data[$name];
        if (in_array($unit->get('type'), [UnitData::HIDDEN, UnitData::TEXT_SHORT, UnitData::AUX_TITLE])){
            return $item;
        }

        $item_class  = $inline ? 'layui-inline' : 'layui-form-item';
        $input_class = $unit->get('label') && !isset($this->short_from_and_tip[$name]) ? 'layui-input-block' : 'layui-input-inline';
        $label       = $unit->get('label') ? sprintf(self::LABEL_TEMPLATE, $unit->get('label')) : "";
        $tip         = isset($this->short_from_and_tip[$name]) ? sprintf(self::TIP_TEMPLATE, $this->short_from_and_tip[$name]) : "";
        $pane        = $this->skin && in_array($unit->get('type'), [UnitData::SWITCH, UnitData::CHECKBOX,  UnitData::RADIO])  ? 'pane' : '';

        return sprintf(self::FORM_TEMPLATE, $item_class, $pane ,$label, $input_class, $item, $tip);
    }

    /**
     * 资源路径组合
     * @param $src
     * @return string
     */
    private function assets($src)
    {
        return rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']), '/') . $src;
    }

    /**
     * 提交的js代码
     * @return string
     */
    private function submitJs()
    {
        $success = lang("success");

        $close = request()->get('__sc_tab__') ? "custom.closeTabsPage()" : "parent.layer.closeAll()";
        return <<<JS

    $('#close').click(function () {
        {$close};
        return false;
    });
    
    layui.form.on('submit(sc-form)', function (data) {
        if(window.submit_sc) return  false;
        let load = custom.loading();
        $.ajax({
            type: 'post'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: data.field
            , success: function (res) {
                layer.close(load);
                if (res.code === 200) {
                    parent.layer.close(window.closeLayerIndex);
                    try{
                        window.parent.notice.success('{$success}');
                    }catch (e) {
                        notice.success('{$success}');
                    }
                    if (window.parent.table){
                        window.parent.table.reload('sc');
                    }
                } else {
                    notice.warning(res.msg);
                }
            },
            error: function (err) {
                layer.close(load);
            }
        });
        return false;
    })
JS;

    }

    /**
     * 获取内容比例
     * @return int
     */
    public function getCustomMd(): int
    {
        return $this->custom_md;
    }

    /**
     * 字段合并
     * @param array $data
     * @return array
     */
    private function fieldMerge(array $data = null)
    {
        $inline_field = [];
        $data = $data ?: $this->form_data;
        foreach ($data as $key => $item) {
            if ($item instanceof \Closure) {
                $inline_field = array_merge(call_user_func([$this, 'fieldMerge'], $item()), $inline_field);
                unset($data[$key]);
            } elseif (is_array($item)) {
                foreach ($item as $items){
                    foreach ($items as $item_){
                        $item_ instanceof UnitData and $inline_field[] = $item_;
                    }
                }
                unset($data[$key]);
            } elseif ($item instanceof AuxTitle) {
                unset($data[$key]);
            }
        }
        $data = array_merge($inline_field, $data);

        $return_data = [];
        /** @var UnitData $datum */
        foreach ($data as $datum) {
            $return_data[$datum->get('name')] = $datum;
        }

        return $return_data;
    }

    /**
     * 设置默认值
     * @param array $default_data
     * @return Form
     */
    public function setDefaultData(array $default_data): Form
    {
        $this->default_data = $default_data;
        return $this;
    }
}
