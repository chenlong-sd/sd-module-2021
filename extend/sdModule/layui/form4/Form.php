<?php
/**
 * datetime: 2021/11/18 13:56
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4;

use sdModule\layui\Dom;
use sdModule\layui\form4\formUnit\unitProxy\CheckboxProxy;
use sdModule\layui\form4\formUnit\unitProxy\RadioProxy;
use sdModule\layui\form4\formUnit\unitProxy\SliderProxy;

class Form extends BaseForm
{

    /**
     * @return string
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getHtml(): string
    {
        $element = implode(array_map(function ($v) {
                $element = $v->getElements($this->scene);
                if ($this->getIsPane() && ($v instanceof RadioProxy || $v instanceof CheckboxProxy || $v instanceof SliderProxy)) {
                    $element->addAttr('pane', '');
                }
                return $element;
            }, $this->unit));

        return $element . ($element ? $this->submitElement : '');
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getJs(): string
    {
        $this->makeSubmitJs();
        $this->makeClosePageJs();

        return $this->getUnitJs();
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/26
     */
    public function getUnitJs(): string
    {
        return implode(';', array_map(function ($v) {return $v->getJs();}, $this->unit)) . implode(';', $this->js);
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function getLoadJs(): string
    {
        $loadJs = array_map(function ($v) {return $v->getLoadJs();}, $this->unit);

        if ($this->loadJs) $loadJs[] = $this->loadJs;
        if (!$loadJs) return '';

        $loadJs = array_unique(array_merge(...$loadJs));

        $host = rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']), '/');

        return implode(array_map(function ($v) use ($host){
            return Dom::create('script')->addAttr('src', $host . $v);
        }, $loadJs));
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function getCss(): string
    {
        return implode(array_map(function ($v) {return $v->getCss();}, $this->unit));
    }

    /**
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function getIsPane(): bool
    {
        return $this->isPane;
    }

    /**
     * @return int
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/25
     */
    public function getMd(): int
    {
        return $this->md;
    }
}
