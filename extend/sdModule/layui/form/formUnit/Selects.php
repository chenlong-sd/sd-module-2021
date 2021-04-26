<?php
/**
 * Date: 2020/11/22 16:42
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;

/**
 * 下拉多选
 * Class Selects
 * @package sdModule\layui\defaultForm\formUnit
 */
class Selects extends UnitBase
{
    public ?string $default = '';

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        return "<div id='{$this->name}-selects' {$attr} class=\"xm-select-demo\"></div>";
    }

    /**
     * @return string
     */
    public function getJs()
    {
        $config = json_encode($this->config, JSON_UNESCAPED_UNICODE);
        if ($this->default){
            $init_value = explode(',', $this->default);
        }else{
            $init_value = $this->preset ?: [];
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
        foreach ($this->select_data as $value => $name) {
            $new_data[] = compact('name', 'value');
        }
        return json_encode($new_data, JSON_UNESCAPED_UNICODE);
    }
}

