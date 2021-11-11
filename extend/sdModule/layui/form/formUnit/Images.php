<?php
/**
 * Date: 2020/9/26 16:14
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Images extends UnitBase
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
            ->addContent(Dom::create('input')->setSingleLabel()
                ->addAttr('name', $this->name)->addAttr('type', 'hidden')
                ->addAttr('value', $this->default)
            )
            ->addContent(Dom::create()->addClass('layui-btn-group')
                ->addContent(Dom::create('button')->addAttr([
                    'type' => 'button',
                    'class' => 'layui-btn',
                    'id' => $this->name
                ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-upload'))
                    ->addContent('选择图片')->addContent($this->systemResource())
            ))->addContent($this->getShortTip())->addContent(
                Dom::create('blockquote')->addClass('layui-elem-quote layui-quote-nm')
                    ->addAttr('style', 'margin-top: 10px;')
                    ->addContent(Dom::create()->addContent('图片预览：'))
                    ->addContent(Dom::create()->addAttr([
                        'class' => 'layui-upload-list',
                        'id' => "{$this->name}-show",
                        'style' => 'overflow: hidden'
                    ]))
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
    window.{$this->name} = custom.moreUpload(layui.jquery, '{$this->name}', layui.upload).init('{$this->default}'.split(','));
JS;

    }

    /**
     * 系统资源
     * @return Dom|string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    private function systemResource()
    {
        return env('SYSTEM_RESOURCE') ? Dom::create('button')->addAttr([
            'type' => 'button',
            'class' => 'layui-btn layui-btn-normal',
            'id' => "{$this->name}-select",
        ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-picture'))
            ->addContent('系统资源') : '';
    }
}
