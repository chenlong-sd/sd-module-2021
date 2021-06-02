<?php
/**
 * Date: 2020/12/8 10:14
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form\formUnit;


use sdModule\layui\Dom;

class AuxTitle  extends UnitBase
{
    /**
     * @param array $attr
     * @return Dom
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/2
     */
    public function getHtml(array $attr): Dom
    {
        switch ($this->label) {
            case '__':
                $html = Dom::create()->addContent($this->name);
                break;
            case 'grey':
                $html = Dom::create('blockquote')->addClass('layui-elem-quote')->addContent($this->name);
                break;
            case 'white':
                $html = Dom::create('blockquote')->addClass(['layui-elem-quote', 'layui-quote-nm'])->addContent($this->name);
                break;
            case 'line':
                $html = Dom::create('fieldset')->addClass(['layui-elem-field', 'layui-field-title'])
                    ->addContent(
                        Dom::create('legend')->addContent($this->name)
                    )->addContent(
                        Dom::create()->addClass(['layui-field-box'])
                    );

                break;
            case 'h3':
                $html = Dom::create('h3')->addContent($this->name);
                break;
            default:
                $html = Dom::create('blockquote')->addClass('layui-elem-quote')->addContent($this->name);
        }

        return $html;
    }
}
