<?php
/**
 * Date: 2020/9/26 16:49
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


class UEditor extends UnitBase
{
    public ?string $default = '';

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        return <<<HTML
                <script id="{$this->name}-ue" {$attr} name="{$this->name}" type="text/plain"></script>
HTML;

    }

    /**
     * @return mixed|string
     */
    public function getJs()
    {
        $content = html_entity_decode($this->default);
        return <<<JS
    let ue_{$this->name} = custom.editorRender(UE, "{$this->name}-ue");
    defaultData.ue_{$this->name} = function() {
        ue_{$this->name}.ready(()=>{
              ue_{$this->name}.setContent('{$content}');
        });
    };
JS;

    }
}
