<?php
/**
 * datetime: 2021/11/19 1:50
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

use sdModule\layui\Dom;

trait Required
{
    /**
     * @var bool 是否必填
     */
    protected $isRequired = false;

    /**
     * @param bool $isRequired
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function required(bool $isRequired = true)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @param string $label
     * @return Dom
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    protected function getLabelElement(string $label): Dom
    {
        return parent::getLabelElement($label)->addContent($this->requiredDom());
    }

    /**
     * 必选的Dom获取
     * @return Dom|string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/6/17
     */
    private function requiredDom()
    {
        return $this->isRequired ? Dom::create('span')
            ->addAttr([
                'style' => 'position: absolute;font-size: 25px;color: red;top: 14px;right: 3px;',
            ])->addContent('*') : '';
    }
}