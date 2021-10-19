<?php
/**
 * Date: 2020/9/26 16:49
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class UEditor extends UnitBase
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
        $inputDiv = Dom::create();
        $input    = Dom::create('script')->setId("{$this->name}-ue")
            ->addAttr($attr)->addAttr([
                'name' => $this->name,
                'type' => 'text/plain',
            ]);
        if ($this->label) {
            $itemDom->addContent($this->getLabel($this->label));
            $inputDiv->addClass('layui-input-block');
        }else{
            $inputDiv->addClass('layui-input-inline');
            return $inputDiv->addContent($input);
        }

        return $itemDom->addContent($inputDiv->addContent($input));
    }

    /**
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getJs(): string
    {
        $content = html_entity_decode($this->default);
        $config  = json_encode($this->config);

        return <<<JS
    let ue_{$this->name} = custom.editorRender(UE, "{$this->name}-ue", $config);
    ue_{$this->name}.ready(()=>{
          ue_{$this->name}.setContent('{$content}');
    });
JS;;

    }
}
