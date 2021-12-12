<?php
/**
 * datetime: 2021/12/10 19:57
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\module;

use sdModule\layui\Dom;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\FormUnitT;

class OpenForm
{
    private $formUnit;
    /**
     * @var Dom
     */
    private $submit;
    /**
     * @var array
     */
    private $config = [];
    /**
     * @var Dom
     */
    private $form;
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var array
     */
    private $rowParameter = [];

    /**
     *
     * OpenForm constructor.
     * @param BaseFormUnitProxy[]|BaseFormUnit[]|FormUnitT[] $formUnit
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function __construct(array $formUnit)
    {
        $this->formUnit = $formUnit;

        $this->defaultForm();
        $this->defaultSubmit();
    }

    /**
     * 设置提交按钮
     * @param Dom $submit
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function setSubmit(Dom $submit): OpenForm
    {
        $this->submit = $submit;
        return $this;
    }

    /**
     * 设置弹窗配置
     * @param array $config
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function setPopupsConfig(array $config): OpenForm
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 设置表单元素
     * @param Dom $form
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function setForm(Dom $form): OpenForm
    {
        $this->form = $form;
        return $this;
    }

    /**
     * 设置请求路径
     * @param string $url
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function setRequestUrl(string $url): OpenForm
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 设置请求带的行参数
     * @param array $param
     * @return OpenForm
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function setRowParameter(array $param): OpenForm
    {
        $this->rowParameter = $param;
        return $this;
    }

    /**
     * 默认提交按钮
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    private function defaultSubmit()
    {
        $this->submit = Dom::create('button')
            ->addClass('layui-btn')->addAttr([
                'type' => 'submit',
                'lay-submit' => '',
                'lay-filter' => 'page',
                'style' => 'margin:auto;display:block;'
            ])->addContent(' 提 交 ');
    }

    /**
     * 默认表单
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    private function defaultForm()
    {
        $this->form = Dom::create()->addClass('layui-form layui-form-pane')
            ->addAttr('style', 'margin:15px');
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public function __toString()
    {
        $this->formUnit[] = FormUnit::customize($this->submit);
        $form = $this->form->addContent(implode(array_map(function ($v) {return $v->getElement('page');}, $this->formUnit)));

        $this->rowParameter = json_encode($this->rowParameter);
        $this->config       = $this->config ? json_encode($this->config) : '{}';
        return <<<JS

        let rowParam = $this->rowParameter;
        let popupsConfig = {
            type: 1
            ,resize: false
            ,shadeClose: true
            ,shade: 0
            ,area: '350px'
            ,title: '表单信息'
            ,content:`$form`
            ,success: function(layero, index){
                layero.find('.layui-layer-content').css('overflow', 'visible');
                let submitData = {};
                if (rowParam.length > 0){
                    for(var i = 0; i < rowParam.length; i++) {
                       if(obj.hasOwnProperty(rowParam[i])){
                           submitData[rowParam[i]] = obj[rowParam[i]];
                       }
                    }
                }
                
                form.render().on('submit(page)', function(data){
                    ScXHR.ajax({
                        url:`$this->url`,
                        type:'post',
                        data: Object.assign(data.field, submitData),
                        success:function(res) {
                            if (res.code === 200){
                                notice.success(res.msg);
                                layer.close(index);
                            }else{
                                notice.warning(res.msg);
                            }
                        }
                    });
                });
            }
        };
        let customizeConfig = $this->config;
        
        layer.open(Object.assign(popupsConfig, customizeConfig));
JS;
    }
}
