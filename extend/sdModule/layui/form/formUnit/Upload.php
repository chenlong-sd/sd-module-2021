<?php
/**
 * Date: 2020/10/12 12:57
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use think\facade\Db;

class Upload extends UnitBase
{
    public ?string $default = '';

    public function getHtml(string $attr)
    {
        return <<<HTML
        <div class="layui-upload">
            <input type="hidden" name="{$this->name}">
            <div class="layui-btn-group">
                <button type="button" class="layui-btn" id="{$this->name}">
                    <i class="layui-icon layui-icon-upload"></i>选择文件
                </button>
            </div>
            <div class="layui-upload-list">
                <table class="layui-table {$this->name}-table-xc">
                    <tbody></tbody>
                </table>
            </div>
        </div>
HTML;
    }

    /**
     * @return mixed|string
     */
    public function getJs()
    {
        return <<<JS
    window.{$this->name} = custom.fileUpload(layui.jquery, layui.upload, '{$this->name}', "{$this->options['type']}");
    defaultData.{$this->name} = function(){
         {$this->name}.defaults({$this->getData()});
    };
JS;
    }

    /**
     * 获取对应的默认值
     * @return false|string
     */
    private function getData()
    {
        try {
            $data = Db::name('resource')->whereIn('id', $this->default)
                ->field('tag,id')->select();
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $exception) {
            return json_encode([]);
        }
    }
}
