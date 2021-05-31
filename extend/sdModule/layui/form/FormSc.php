<?php
/**
 * Date: 2021/5/31 18:20
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;


use sdModule\layui\Dom;

class FormSc
{
    /**
     * @var UnitData[]
     */
    private array $formData;


    /**
     * FormSc constructor.
     * @param UnitData[] $formData
     */
    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    /**
     * @param array|UnitData[] $fromData
     * @return FormSc
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public static function create(array $fromData): FormSc
    {
        return new self($fromData);
    }

    private function makeUnitHtml()
    {
        $data = '';
        foreach ($this->formData as $unitData) {




        }
    }

}
