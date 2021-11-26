<?php
/**
 * datetime: 2021/11/25 15:48
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\Video;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class VideoEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class VideoEntity extends Video implements UnitI
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
            ->addContent(Dom::create('input')->setSingleLabel()->addAttr('name', $this->name)
                ->addAttr('type', 'hidden')->addAttr('value', ''))
            ->addContent(Dom::create()->addClass('layui-btn-group')->addContent(
                Dom::create('button')->addAttr([
                    'type' => 'button',
                    'class' => 'layui-btn',
                    'id' => $this->name
                ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-upload'))
                    ->addContent('选择视频')
            )->addContent($this->systemResource())
            )->addContent($this->getShortTipElement($this->shortTip))->addContent(
                Dom::create()->addClass('layui-upload-list')->addContent(
                    Dom::create('video')->addAttr([
                        'class' => 'layui-upload-img',
                        'controls' => 'controls',
                        'src' => '',
                        'style' => 'max-width: 500px;',
                        'id' => "{$this->name}_show"
                    ])
                )->addContent(Dom::create('p')->setId("{$this->name}_tip"))
            );

        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
        }

        return $itemDom->addContent($inputDiv->addContent($uploadBox));
    }

    /**
     * 系统资源
     * @return Dom|string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    private function systemResource()
    {
        return $this->isOpenSystemResource ? Dom::create('button')->addAttr([
            'type' => 'button',
            'class' => 'layui-btn layui-btn-normal',
            'id' => "{$this->name}-select",
        ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-picture'))
            ->addContent('系统资源') : '';
    }

    /**
     * @return mixed|string
     */
    public function getJs(): string
    {
        return <<<JS
    window['$this->name'] = custom.videoUpload('{$this->name}').defaults('$this->defaultValue');
JS;

    }
}