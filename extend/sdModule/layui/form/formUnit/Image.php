<?php
/**
 * Date: 2020/9/26 16:02
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class Image extends UnitBase
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
                    ->addContent('选择图片')
                    ->addContent($this->systemResource())
            ))->addContent($this->getShortTip())->addContent(
                Dom::create()->addClass('layui-upload-list')->addContent(
                    Dom::create('img')->addAttr([
                        'class' => 'layui-upload-img',
                        'alt' => '',
                        'src' => '',
                        'style' => 'max-width: 300px;min-height:100px',
                        'id' => "{$this->name}_show"
                    ])
                )->addContent(Dom::create('p')->setId("{$this->name}_tip"))
            );

        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
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
        return env('SYSTEM_RESOURCE') ? Dom::create('button')->addAttr([
            'type' => 'button',
            'class' => 'layui-btn layui-btn-normal',
            'id' => "{$this->name}-select",
        ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-picture'))
            ->addContent('系统资源') : '';
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getJs(): string
    {
        return <<<JS
    window.{$this->name} = custom.upload(layui.jquery, layui.upload, '{$this->name}').defaults('{$this->default}');
JS;
    }
}
