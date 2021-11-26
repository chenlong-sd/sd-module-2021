<?php
/**
 * datetime: 2021/11/19 16:40
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\Image, UnitI};

/**
 * Class Image
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/19
 */
class ImageEntity extends Image implements UnitI
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
                    'type'  => 'button',
                    'class' => 'layui-btn',
                    'id'    => $this->name
                ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-upload'))
                    ->addContent('选择图片')
                    ->addContent($this->systemResource())
            ))->addContent($this->getShortTipElement($this->shortTip))->addContent(
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
        if ($this->isOpenSystemResource) {
            return Dom::create('button')->addAttr([
                'type'  => 'button',
                'class' => 'layui-btn layui-btn-normal',
                'id'    => "$this->name-select",
            ])->addContent(Dom::create('i')->addClass('layui-icon layui-icon-picture'))
                ->addContent('系统资源');
        }

        return '';
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getJs(): string
    {
        return <<<JS
    window.$this->name = custom.upload(layui.jquery, layui.upload, '$this->name').defaults('$this->defaultValue');
JS;
    }
}
