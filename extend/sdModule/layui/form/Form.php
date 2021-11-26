<?php
/**
 * Date: 2021/5/31 18:20
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;


use sdModule\common\Sc;
use sdModule\layui\Dom;
use sdModule\layui\form\formUnit\Select;
use sdModule\layui\form\formUnit\Selects;
use sdModule\layui\form\formUnit\UEditor;
use sdModule\layui\form\formUnit\UnitBase;
use think\helper\Str;

/**
 * Class Form
 * @deprecated
 * @package sdModule\layui\form
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class Form
{
    /**
     * @var UnitData[] 表单元素数据
     */
    public $formData;

    /**
     * @var string 场景
     */
    public $scene;

    /**
     * @var array|Dom[] 表单元素
     */
    public $unit = [];

    /**
     * @var array 表单元素的js
     */
    public $unitJs = [];

    /**
     * @var array 表单元素的js
     */
    public $unitCss = [];

    /**
     * @var array 加载的外部js
     */
    public $loadJs = [];

    /**
     * @var array 默认数据
     */
    public $defaultData = [];

    /**
     * @var string|Dom 提交按钮的html
     */
    public $submitHtml = '';

    /**
     * @var string 提交的js
     */
    public $submitJs = '';

    /**
     * @var string 表单风格
     */
    public $skin = '';

    /**
     * @var int|null 页面占比 1- 12
     */
    public $md = null;

    /**
     * @var array 提示语
     */
    private $tip = [];

    /**
     * @var string 成功后处理的代码
     */
    private $successHandle = '';
    /**
     * @var array 表单显示条件
     */
    private $formShowWhere = [];

    /**
     * @var array 联动选项
     */
    private $linkage = [];


    /**
     * Form constructor.
     * @param UnitData[] $formData
     * @param string $scene
     */
    public function __construct(array $formData, string $scene = 'normal')
    {
        $this->formData   = $formData;
        $this->scene      = $scene;
        $this->submitHtml = $this->defaultSubmit();
        $this->unitJs[]   = $this->closePageJs();
    }

    /**
     * @param array|UnitData[] $fromData
     * @param string $scene 单签表单所在场景值
     * @return Form
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public static function create(array $fromData, string $scene = 'normal'): Form
    {
        return new self($fromData, $scene);
    }

    /**
     * 创建表单元素html
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function makeUnitHtml()
    {
        $this->extractDisplayConditions();

        foreach ($this->formData as $unitData) {
            if (in_array($this->scene, $unitData->get('removeScene', []))) {
                continue;
            }
            $unit = $this->makeCode($unitData);

            if ($childrenItemData = ($unitData->get('childrenItem', []))) {
                foreach ($childrenItemData as $itemDatum) {
                    $childrenUnit = $this->makeCode($itemDatum);
                    $this->unitJs[]  = $childrenUnit->getJs();
                    $this->unitCss[] = $childrenUnit->getCss();
                    $unit->addChildrenItem($childrenUnit, $this->formAttrHandle($itemDatum));
                }
            }

            $this->unitJs[]  = $unit->getJs();
            $this->unitCss[] = $unit->getCss();

            $attr = $this->formAttrHandle($unitData);
            $dom  = $unit->getHtml($attr);
            if (isset($attr['pane'])) {
                $dom->addAttr('pane', '');
            }
            $this->unit[] = $dom;
        }
        $this->unit[] = $this->submitHtml;
    }

    /**
     * 提取显示条件
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/8/9
     */
    private function extractDisplayConditions()
    {
        foreach ($this->formData as $unitData) {
            /** @var UnitData[] $childrenItemData */
            if ($childrenItemData = $unitData->get('childrenItem', [])) {
                foreach ($childrenItemData as $itemDatum) {
                    $itemDatum->get('boxId') or $itemDatum->setBoxId($itemDatum->get('name', '') . 'sc-box-form-rand' . mt_rand(0, 99999));
                    if ($showWhere = $itemDatum->get('showWhere', [])){
                        $this->formShowWhere[$showWhere['field']][] = [
                            'value'  => $showWhere['value'],
                            'box_id' => $itemDatum->get('boxId', '')
                        ];
                    }
                    if ($linkage = $itemDatum->get('linkage', [])){
                        $this->linkage[$linkage['field']][] = [
                            'field'   => $itemDatum->get('name', ''),
                            'options' => $linkage['options']
                        ];
                    }
                }
            }
            $unitData->get('boxId') or $unitData->setBoxId($unitData->get('name', '') . 'sc-box-form-rand' . mt_rand(0, 99999));

            if ($linkage = $unitData->get('linkage', [])){
                $this->linkage[$linkage['field']][] = [
                    'field'   => $unitData->get('name', ''),
                    'options' => $linkage['options']
                ];
            }

            if ($showWhere = $unitData->get('showWhere', [])) {
                $this->formShowWhere[$showWhere['field']][] = [
                    'value'  => $showWhere['value'],
                    'box_id' => $unitData->get('boxId', '')
                ];
            }
        }
    }

    /**
     * 表单元素属性处理
     * @param UnitData $unitData
     * @return array
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/9
     */
    private function formAttrHandle(UnitData $unitData): array
    {
        $inputAttr   = $unitData->get('inputAttr', []);
        $currentAttr = array_merge($inputAttr['-'] ?? [], $inputAttr[$this->scene] ?? []);

        if ($this->skin && in_array($unitData->get('formUnitType', ''), ['radio', 'checkbox', 'switch_sc', 'slider'])) {
            $currentAttr['pane'] = '';
        }

        $required = $unitData->get('required', '');
        $required !== true and $required = (array)$required;

        if ($required === true || in_array($this->scene, $required)) {
            $currentAttr['lay-verify'] = 'required';
        }
        return $currentAttr;
    }

    /**
     * 创建代码Dom和js数据
     * @param UnitData $unitData
     * @return UnitBase
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function makeCode(UnitData $unitData): UnitBase
    {
        /** @var UnitBase $unit */
        $unit = Sc::reflex()->getInstance($this->getUnitClassname($unitData->get('formUnitType', '')), [
            'name'          => $unitData->get('name', ''),
            'label'         => $unitData->get('label', ''),
            'placeholder'   => $unitData->get('placeholder', ''),
        ]);

        $default = $this->defaultData[$unitData->get('name', '')] ?? $unitData->get('defaultValue' , "");
        $required = $unitData->get("required", '');
        $required !== true and $required = (array)$required;

        $unit->setDefault($default)->setOption($unitData->get('options', []))
            ->setConfig($unitData->get("config", []))
            ->setRequired($required === true || in_array($this->scene, $required))
            ->setShowWhere($this->formShowWhere[$unitData->get('name', '')] ?? [])
            ->setBoxId($unitData->get('boxId', ''));

        if ($unit instanceof Selects) {
            $this->loadJs('Selects', '/admin_static/layui/dist/xm-select.js');
        } elseif ($unit instanceof UEditor) {
            $this->loadJs('u_editor_config', '/admin_static/editor/ueditor.config.js');
            $this->loadJs('u_editor', '/admin_static/editor/ueditor.all.js');
        }
        if (isset($this->tip[$unitData->get('name', '')])) {
            $unit->setShortTip($this->tip[$unitData->get('name', '')]);
        }

        if ($unit instanceof Select && !empty($this->linkage[$unitData->get('name')])) {
            $unit->linkageOptions = $this->linkage[$unitData->get('name')];
        }

        return $unit;
    }

    /**
     * 加载外部js
     * @param string $uniqueKey js 唯一标识，避免重复加载
     * @param string $url js 路径
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function loadJs(string $uniqueKey, string $url): Form
    {
        $this->loadJs[$uniqueKey] = Dom::create('script')->addAttr('src', $this->assets($url));
        return $this;
    }

    /**
     * 搜索的提交html
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public static function searchSubmit(): Dom
    {
        return Dom::create('div')->addClass('layui-inline')
            ->addContent(Dom::create('button')->addAttr([
                'class'      => 'layui-btn',
                'lay-submit' => '',
                'lay-filter' => 'sc-form',
            ])->addContent('搜索'))
            ->addContent(Dom::create('button')->addAttr([
                'class' => 'layui-btn layui-btn-normal',
                'type'  => 'reset',
            ])->addContent('重置'));
    }

    /**
     * 默认提交
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    private function defaultSubmit(): Dom
    {
        $item   = Dom::create()->addClass('layui-form-item');
        $button = Dom::create()->addClass('layui-input-block sc-submit')
            ->addContent(Dom::create('button')->addAttr([
                'class'      => 'layui-btn',
                'lay-submit' => '',
                'lay-filter' => 'sc-form',
            ])->addContent('立即提交'))
            ->addContent(Dom::create('button')->addAttr([
                'id'    => 'close',
                'class' => 'layui-btn layui-btn-primary'
            ])->addContent('关闭页面'));
        return $item->addContent($button);
    }

    /**
     * 获取表单元素的类名
     * @param string $unitName
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function getUnitClassname(string $unitName): string
    {
        return "sdModule\\layui\\form\\formUnit\\" . Str::studly($unitName);
    }

    /**
     * 资源路径组合
     * @param $src
     * @return string
     */
    private function assets($src): string
    {
        return rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']), '/') . $src;
    }

    /**
     * 完成表单构建
     * @return $this
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function complete(): Form
    {
        $this->makeUnitHtml();
        return $this;
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getSkin(): string
    {
        return $this->skin;
    }

    /**
     * @return null
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getCustomMd(): ?int
    {
        return $this->md;
    }

    /**
     * 获取表单html
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getHtml(): string
    {
        return implode($this->unit);
    }

    /**
     * 获取加载的js
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getLoadJs(): string
    {
        return implode($this->loadJs);
    }

    /**
     * 获取js代码
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getJs(): string
    {
        echo implode($this->unitJs);
        return $this->submitJs ?: $this->defaultSubmitJs();
    }

    /**
     * 获取单元js
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getUnitJs(): string
    {
        return implode($this->unitJs);
    }

    /**
     * 获取单元css
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getUnitCss(): string
    {
        return implode($this->unitCss);
    }

    /**
     * @param array $defaultData
     * @return Form
     */
    public function setDefaultData(array $defaultData): Form
    {
        $this->defaultData = $defaultData;
        return $this;
    }

    /**
     * @param string $submitJs
     * @return Form
     */
    public function setSubmitJs(string $submitJs): Form
    {
        $this->submitJs = $submitJs;
        return $this;
    }

    /**
     * 关闭页面的JS
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    private function closePageJs(): string
    {
        $close = request()->get('__sc_tab__') ? "custom.closeTabsPage()" : "parent.layer.close(window.closeLayerIndex);";
        return <<<JS
    $('#close').click(function () {
        {$close};
        return false;
    });
JS;

    }

    /**
     * @param array $tip
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function setShortForm(array $tip = []): Form
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * 提交的js代码
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    private function defaultSubmitJs(): string
    {
        $success = lang("success");
        $successCode = $this->successHandle ?: <<<SUC
                    parent.layer.close(window.closeLayerIndex);
                    try{
                        window.parent.notice.success('{$success}');
                    }catch (e) {
                        notice.success('{$success}');
                    }
                    if (window.parent.table){
                        window.parent.table.reload('sc');
                    }
SUC;

        return <<<JS

    layui.form.on('submit(sc-form)', function (data) {
        if(window.submit_sc) return  false;
        let form_data = new FormData();
        for (let field in data.field){
           form_data.append(field, data.field[field]);
        }
        $('.sc-file-upload').each(function (index, file){
             form_data.delete(file.name);
             if(file.files.length > 0) form_data.append(file.name, file.files[0]);
        });
        let load = custom.loading();
        $.ajax({
            type: 'post'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: form_data
            , contentType: false
            , processData: false
            , success: function (res) {
                layer.close(load);
                if (res.code === 200) {
                    $successCode
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
     * @param string $submitHtml
     * @return Form
     */
    public function setSubmitHtml(string $submitHtml = ''): Form
    {
        $this->submitHtml = $submitHtml;
        return $this;
    }

    /**
     * 设置边框风格
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function setSkinToPane(): Form
    {
        $this->skin = 'layui-form-pane';
        return $this;
    }

    /**
     * @param int|null $md
     * @return Form
     */
    public function setMd(?int $md): Form
    {
        if ($md > 12 || $md < 1) {
            return $this;
        }
        $this->md = $md;
        return $this;
    }

    /**
     * @param string $customJs
     * @return Form
     */
    public function setJs(string $customJs): Form
    {
        $this->unitJs[] = $customJs;
        return $this;
    }

    /**
     * 设置成功后的处理代码
     * @param string|callable $successHandle
     * @example setSuccessHandle('location.reload();')
     * @example setSuccessHandle(function(){
     *      return 'location.reload();';
     * })
     */
    public function setSuccessHandle($successHandle): Form
    {
        $this->successHandle = is_callable($successHandle) ? call_user_func($successHandle) : $successHandle;
        return $this;
    }
}
