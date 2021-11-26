<?php
/**
 * datetime: 2021/11/25 11:53
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitEntity;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\FormUnitT;
use sdModule\layui\form4\formUnit\unit\AuxTitle;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class AuxTitleEntity
 * @package sdModule\layui\form4\formUnit\unitEntity
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/25
 */
class AuxTitleEntity extends AuxTitle implements UnitI
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

        return $html->setId($this->formUnitId);
    }


}