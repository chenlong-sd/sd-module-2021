<?php
/**
 * datetime: 2021/11/25 15:36
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Upload;
use sdModule\layui\form4\formUnit\UnitI;
use think\facade\Db;

class UploadEntity extends Upload implements UnitI
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
            ))->addContent($this->getShortTipElement($this->shortTip))->addContent(
                Dom::create()->addClass('layui-upload-list')->addContent(
                    Dom::create('table')->addClass("layui-table {$this->name}-table-xc")
                        ->addContent(Dom::create('tbody'))
                )
            );

        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
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
    window["$this->name"] = custom.fileUpload(layui.jquery, layui.upload, '$this->name', "$this->type").defaults({$this->getData()});
JS;
    }

    /**
     * 获取对应的默认值
     * @return false|string
     */
    private function getData()
    {
        try {
            $data = Db::name('resource')->whereIn('id', $this->defaultValue)->field('tag,id')->select();
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $exception) {
            return json_encode([]);
        }
    }

}
