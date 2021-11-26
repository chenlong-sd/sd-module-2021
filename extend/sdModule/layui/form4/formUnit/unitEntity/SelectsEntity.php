<?php
/**
 * datetime: 2021/11/25 14:52
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Selects;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class SelectsEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class SelectsEntity extends Selects implements UnitI
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
        $input    = Dom::create()->addClass('xm-select-demo')->setId("$this->name-selects");;
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
        return <<<JS
    let {$this->name}_config = $config;
    let {$this->name}_render_data = {
        el: "#$this->name-selects",
        initValue:$init_value,
        filterable: true,
        searchTips: "请输入搜索目标",
        paging: true,
	    pageSize: 100,
	    toolbar: {
		    show: true,
		    showIcon: false,
	    },
	    pageEmptyShow: false,
	    autoRow: true,
        data:{$this->dataHandle()},
        name:"$this->name"
    };
    
    for (let attr in {$this->name}_config){
        if (attr === 'remote'){
            {$this->name}_render_data.remoteSearch = true;
            {$this->name}_render_data.remoteMethod = function(val, cb, show) {
                layui.jquery.ajax({
                    url:{$this->name}_config[attr],
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
            {$this->name}_render_data[attr] = {$this->name}_config[attr];
        }
    }
    
    var {$this->name}_selects = xmSelect.render({$this->name}_render_data);
JS;
    }

    /**
     * 数据重新处理
     * @return false|string
     */
    private function dataHandle()
    {
        $new_data = [];
        foreach ($this->options as $value => $name) {
            $new_data[] = compact('name', 'value');
        }
        return json_encode($new_data, JSON_UNESCAPED_UNICODE);
    }
}