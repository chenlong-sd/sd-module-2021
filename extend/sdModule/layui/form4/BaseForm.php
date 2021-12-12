<?php
/**
 * datetime: 2021/11/18 13:50
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\formUnitT;
use sdModule\layui\form4\formUnit\unitProxy\GroupProxy;
use sdModule\layui\form4\formUnit\unitProxy\PasswordProxy;

/**
 * Class BaseForm
 * @package sdModule\layui\form4
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
abstract class BaseForm
{
    /**
     * @var array|formUnitT[] 表单组件
     */
    protected $unit;

    /**
     * @var string 表单场景
     */
    protected $scene = '';

    /**
     * @var array 页面js
     */
    protected $js = [];

    /**
     * @var array 要加载的js
     */
    protected $loadJs = [];

    /**
     * @var string
     */
    protected $successHandle;

    /**
     * @var string
     */
    protected $failHandle;

    /**
     * @var
     */
    protected $submitElement;
    /**
     * @var bool
     */
    protected $isPane = false;
    /**
     * @var int
     */
    protected $md = 0;

    /**
     * Form constructor.
     * @param array|formUnitT[] $unitIS
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function __construct(array $unitIS, array $default = [])
    {
        $this->unit    = $unitIS;
        array_map(function ($v) use ($default) {
            (isset($default[$v->getName()]) && !$v instanceof PasswordProxy) and $v->defaultValue($default[$v->getName()]);
            if ($v instanceof GroupProxy){
                array_map(function ($vg) use ($default) {
                    (isset($default[$vg->getName()]) && !$vg instanceof PasswordProxy) and $vg->defaultValue($default[$vg->getName()]);
                }, $v->getChildren());
            }
        }, $this->unit);

        $this->refactor();
        $this->setControlShowJs();
        $this->optionsRender();
        $this->defaultSubmitElement();
    }

    /**
     * @param string $scene
     * @return BaseForm
     */
    public function setScene(string $scene): BaseForm
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 设置表单占页面宽度
     * @param int $md
     * @return BaseForm
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function setMd(int $md): BaseForm
    {
        $this->md = $md;
        return $this;
    }

    /**
     * 设置边框风格
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function setPane(): BaseForm
    {
        $this->isPane = true;
        return $this;
    }

    /**
     * @param string $js
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function addJs(string $js): BaseForm
    {
        $this->js[] = $js;
        return $this;
    }

    /**
     * @param string $successHandle
     * @return BaseForm
     */
    public function setSuccessHandle(string $successHandle): BaseForm
    {
        $this->successHandle = $successHandle;
        return $this;
    }

    /**
     * @param string $failHandle
     * @return BaseForm
     */
    public function setFailHandle(string $failHandle): BaseForm
    {
        $this->failHandle = $failHandle;
        return $this;
    }

    /**
     * @param Dom|string $submitElement
     * @return BaseForm
     */
    public function setSubmitElement($submitElement = ''): BaseForm
    {
        $this->submitElement = $submitElement;
        return $this;
    }

    /**
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function setSearchSubmitElement(): BaseForm
    {
        $submitElement = Dom::create()->addClass('layui-inline')
            ->addContent(Dom::create('button')->addAttr([
                'class'      => 'layui-btn',
                'lay-submit' => '',
                'lay-filter' => 'sc-form',
            ])->addContent('搜索'))
            ->addContent(Dom::create('button')->addAttr([
                'class' => 'layui-btn layui-btn-normal',
                'type'  => 'reset',
            ])->addContent('重置'));

        if ($this->unit) {
            $this->unit[0]->addChildrenItem(FormUnit::customize($submitElement));
        }
        return $this->setSubmitElement();
    }

    /**
     * 重构
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    private function refactor()
    {
        $newWhere = $newAssociationOptions = [];
        foreach ($this->unit as $unit) {
            // 记录展示条件，清空条件
            if ($unit->getShowWhere()) {
                ['field' => $field, 'value' => $value] = $unit->getShowWhere();
                $newWhere[$field][] = [
                    'id'    => $unit->getFormUnitId(),
                    'value' => $value
                ];
                $unit->clearShowWhere();
            }

            // 记录关联选项，清空选项
            if ($associationOptions = $unit->getAssociationOptions()) {
                // 重新构造关联选项为 ['field' => [1 => '选项一', 2 => '选项2'];
                $options = [];
                foreach ($associationOptions['options'] as $option){
                    $options[$option['parent_value']][$option['value']] = $option['title'];
                }

                $newAssociationOptions[$associationOptions['field']][$unit->getName()] = $options;
                // 清空原选项
                $unit->options([]);
                // 重新赋值选项数据
                foreach ($this->unit as $unitDefault){
                    if ($unitDefault->getName() == $associationOptions['field'] && $unitDefault->getDefaultValue()) {
                        empty($options[$unitDefault->getDefaultValue()]) or $unit->options($options[$unitDefault->getDefaultValue()]);
                    }
                }
            }
        }

        // 重新设置对应的显示条件和联动选项
        foreach ($this->unit as $unit) {
            if (isset($newWhere[$unit->getName()])) {
                $unit->showWhere('v', $newWhere[$unit->getName()]);
            }
            if (isset($newAssociationOptions[$unit->getName()])) {
                $unit->setAssociationOptions($newAssociationOptions[$unit->getName()]);
            }
        }
    }

    /**
     * @param array|string $loadJs
     * @return BaseForm
     */
    public function addLoadJs($loadJs): BaseForm
    {
        $this->loadJs = array_merge($this->loadJs, is_array($loadJs) ? $loadJs : [$loadJs]);
        return $this;
    }

    /**
     * 生成提交的js
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    protected function makeSubmitJs()
    {
        $successCode = $this->successHandle ?: <<<JS
                    parent.layer.close(window.closeLayerIndex);
                    try{
                        window.parent.notice.success(res.msg);
                    }catch (e) {
                        notice.success(res.msg);
                    }
                    if (window.parent.table){
                        window.parent.table.reload('sc');
                    }
JS;

        $failCode = $this->failHandle ?: <<<JS
                    notice.warning(res.msg);
JS;


        $this->js[] = <<<JS

    layui.form.on('submit(sc-form)', function (data) {
        if(window.submit_sc) return false;
        let form_data = new FormData();
        for (let field in data.field){
           form_data.append(field, data.field[field]);
        }
        $('.sc-file-upload').each(function (index, file){
             form_data.delete(file.name);
             if(file.files.length > 0) form_data.append(file.name, file.files[0]);
        });
        ScXHR.ajax({
            type: 'post'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: form_data
            , contentType: false
            , processData: false
            , success: function (res) {
                if (res.code === 200) {
                    $successCode
                } else {
                    $failCode
                }
            }
        });
        return false;
    });
JS;

    }

    /**
     * 默认提交表单的元素
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    private function defaultSubmitElement()
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
        $this->submitElement = $item->addContent($button);
    }

    /**
     * 设置控制展示的js
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    private function setControlShowJs()
    {
        $this->js[] = <<<JS

    window['sc4ControlShow'] = (whereObj, CurrentValue) => {
        if (typeof CurrentValue !== 'object') CurrentValue = [CurrentValue];
        CurrentValue = CurrentValue.map((v) => v + '');
        let where_value;
        for(var i = 0; i < whereObj.length; i++) {
           where_value = typeof whereObj[i].value !== 'object' ? [whereObj[i].value + ''] : whereObj[i].value.map((v) => v + '');
           if (custom.arrayBeMixed(where_value, CurrentValue)){
               layui.jquery(`#\${whereObj[i].id}`).show();
           }else{
               layui.jquery(`#\${whereObj[i].id}`).hide();
           }
        }
    };

JS;
    }

    /**
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    private function optionsRender()
    {
        $this->js[] = <<<JS

        window['sc4OptionsRender'] = (element, data) => {
            let optionsHtml = '<option></option>';
            for(let value in data) {
              optionsHtml += `<option value="\${value}">\${data[value]}</option>`;
            }
            element.html(optionsHtml);
        };
JS;

    }

    /**
     * 关闭页面的JS
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    protected function makeClosePageJs()
    {
        $close = request()->get('__sc_tab__') ? "custom.closeTabsPage()" : "parent.layer.close(window.closeLayerIndex);";
        $this->js[] = <<<JS
    layui.jquery('#close').click(function () {
        $close;
        return false;
    });
JS;
    }


}
