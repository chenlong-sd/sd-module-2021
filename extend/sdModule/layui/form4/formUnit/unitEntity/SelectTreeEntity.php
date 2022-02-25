<?php
/**
 * datetime: 2021/11/25 14:52
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\SelectTree;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class SelectsEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class SelectTreeEntity extends SelectTree implements UnitI
{
    use FormUnitT;

    /**
     * @param string $scene 表单场景
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElement(string $scene): Dom
    {
        $itemDom  = $this->getItemElement();
        $inputDiv = Dom::create();
        $input    = Dom::create()->addClass('xm-select-demo')->addAttr('style', 'min-width:180px')->setId($this->formUnitId . '-tree');;
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
            return $itemDom->addContent($inputDiv->addContent($input))
                ->addContent($this->getShortTipElement($this->shortTip));
        }

        $inputDiv->addClass('layui-inline');
        return $inputDiv->addContent($input);
    }

    /**
     * @return string[]
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function getLoadJs(): array
    {
        return [
            '/admin_static/layui/dist/xm-select.js'
        ];
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getJs(): string
    {
        $config     = json_encode($this->jsConfig, JSON_UNESCAPED_UNICODE);
        $init_value = $this->defaultValue ?: [];
        if ($this->defaultValue && !is_array($this->defaultValue)){
            $init_value = explode(',', $this->defaultValue);
        }

        $init_value = json_encode($init_value, JSON_UNESCAPED_UNICODE);
        $render_data = json_encode([
            'el'         =>  "#$this->formUnitId-tree",
            'filterable' =>  true,
            'tips'       => $this->placeholder,
            'searchTips' =>  "请输入搜索目标",
            'paging'     =>  true,
            'pageSize'   =>  100,
            'toolbar'    =>  [
                'show'       =>  true,
                'showIcon'  =>  false,
            ],
            'tree'       =>  [
                'show'  =>  true,
            ],
            'pageEmptyShow' =>  false,
            'autoRow'    =>  true,
            'name'       => "$this->name",
            'prop'       => [
                'value' => 'id'
            ],
        ], JSON_UNESCAPED_UNICODE);
        return <<<JS
    window['{$this->name}_config'] = $config;
    window['{$this->name}_render_data'] = $render_data;
        window['{$this->name}_render_data'].data = {$this->dataHandle()};
        window['{$this->name}_render_data'].initValue = {$init_value};

    for (let attr in window['{$this->name}_config']){
        if (attr === 'remote'){
            window['{$this->name}_render_data'].remoteSearch = true;
            window['{$this->name}_render_data'].remoteMethod = function(val, cb, show) {
                layui.jquery.ajax({
                    url:window['{$this->name}_config'][attr],
                    type:'get',
                    data: {
                        search: val,
                    },
                    success:function (res) {
                        cb(res.data.data, res.data.page)
                    }
                })
            };
        }else{
            if(attr.substr(0, 9) === 'function.'){
                window['{$this->name}_render_data'][attr.substr(9)] = new Function(... window['{$this->name}_config'][attr]);
            }else{
                window['{$this->name}_render_data'][attr] = window['{$this->name}_config'][attr];
            }
        }
    }
    
    window['{$this->name}_selectTree'] = xmSelect.render(window['{$this->name}_render_data']);
JS;
    }

    /**
     * 数据重新处理
     * @return false|string
     */
    private function dataHandle()
    {
        return json_encode($this->options, JSON_UNESCAPED_UNICODE);
    }
}