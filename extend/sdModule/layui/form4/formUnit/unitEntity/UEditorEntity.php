<?php
/**
 * datetime: 2021/11/19 2:02
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\{FormUnitT, unit\UEditor, UnitI};

/**
 * Class UEditor
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/19
 */
class UEditorEntity extends UEditor implements UnitI
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
        $inputDiv = Dom::create();
        $input    = Dom::create('script')->setId("$this->name-ue")
            ->addAttr([
                'name' => $this->name,
                'type' => 'text/plain',
            ]);
        if ($this->label) {
            $itemDom->addContent($this->getLabelElement($this->label));
            $inputDiv->addClass('layui-input-block');
            return $itemDom->addContent($inputDiv->addContent($input));
        }

        return $inputDiv->addClass('layui-input-inline')->addContent($input);
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getJs(): string
    {
        $content = html_entity_decode($this->defaultValue);
        $config  = json_encode($this->jsConfig);

        return <<<JS
    let ue_$this->name = custom.editorRender(UE, "$this->name-ue", $config);
    ue_$this->name.ready(()=>ue_$this->name.setContent('$content'));
JS;
    }

    /**
     * @return string[]
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getLoadJs(): array
    {
        return [
            '/admin_static/editor/ueditor.config.js',
            '/admin_static/editor/ueditor.all.js'
        ];
    }
}