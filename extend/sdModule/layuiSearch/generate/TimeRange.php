<?php


namespace sdModule\layuiSearch\generate;

/**
 * 时间范围选择
 * Class TimeRange
 * @package sdModule\layuiSearch\generate
 */
class TimeRange extends FormVar implements FormDefine
{
    const TYPE_YEAR = 'year';
    const TYPE_MONTH = 'month';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';

    /**
     * @param string $type
     * @return LayuiForm
     */
    public function html($type = self::TYPE_DATE):LayuiForm
    {
        $range = 'time' . mt_rand(1000, 9999);
        $html = <<<HTM
        <div class="layui-inline">
           {$this->getLabel()}
          <div class="layui-input-inline">
            <input type="text" name="{$this->name}" class="layui-input" autocomplete="off"  id="$range" placeholder="{$this->placeholder}">
          </div>
        </div>
HTM;

        $js = <<<JS
        <script>
            layui.use('laydate', function(){
                var laydate = layui.laydate;
                laydate.render({
                    elem: '#$range'
                    ,type: '$type'
                    ,range: '~'
                });
            });
        </script>
JS;

        return LayuiForm::generate($html, $js);
    }
}

