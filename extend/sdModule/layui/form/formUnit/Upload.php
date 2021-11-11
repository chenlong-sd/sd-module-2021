<?php
/**
 * Date: 2020/10/12 12:57
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;
use think\facade\Db;

class Upload extends UnitBase
{

    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getHtml(array $attr): Dom
    {
        $itemDom  = $this->getItem();
        $inputDiv = Dom::create()->addClass('layui-input-block');

        $uploadBox = Dom::create()->addClass('layui-upload')
            ->addContent(Dom::create('input')->setSingleLabel()->addAttr('name', $this->name)->addAttr('type', 'hidden'))
            ->addContent(Dom::create()->addClass('layui-btn-group')->addContent(
                Dom::create('button')->addAttr([
                    'type' => 'button',
                    'class' => 'layui-btn',
                    'id' => $this->name
                ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-upload'))
                    ->addContent('选择文件')
            ))->addContent($this->getShortTip())->addContent(
                Dom::create()->addClass('layui-upload-list')->addContent(
                    Dom::create('table')->addClass("layui-table {$this->name}-table-xc")
                    ->addContent(Dom::create('tbody'))
                )
            );

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
        }

        return $itemDom->addContent($inputDiv->addContent($uploadBox));
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getJs(): string
    {
        return <<<JS
    window.{$this->name} = custom.fileUpload(layui.jquery, layui.upload, '{$this->name}', "{$this->config['type']}").defaults({$this->getData()});
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
