<?php
/**
 * Date: 2020/11/22 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;

use sdModule\layui\Dom;

/**
 * 下拉多选
 * Class Selects
 * @package sdModule\layui\defaultForm\formUnit
 */
class Selects extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create();
        $input    = Dom::create()->addAttr($attr)->addClass('xm-select-demo')->setId("{$this->name}-selects");;
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass($this->shortTip ? 'layui-inline' : 'layui-input-block');
        }else{
            $inputDiv->addClass('layui-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input));
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    public function getJs(): string
    {
        $config = json_encode($this->config, JSON_UNESCAPED_UNICODE);
        $init_value = $this->default ?: [];
        if ($this->default && !is_array($this->default)){
            $init_value = explode(',', $this->default);
        }

        $init_value = json_encode($init_value, JSON_UNESCAPED_UNICODE);
        return <<<JS
    let {$this->name}_config = {$config};
    let {$this->name}_render_data = {
        el: "#{$this->name}-selects",
        initValue:{$init_value},
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
        name:"{$this->name}"
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

