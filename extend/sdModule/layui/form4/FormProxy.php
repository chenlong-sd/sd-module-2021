<?php
/**
 * datetime: 2021/11/18 13:57
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4;

use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\UnitI;

/**
 * Class FormProxy
 * @mixin BaseForm
 * @package sdModule\layui\form4
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
class FormProxy
{
    /**
     * @var Form
     */
    private $form;

    /**
     * FormProxy constructor.
     * @param BaseFormUnitProxy[]|UnitI[] $unitIS
     * @param array|\ArrayAccess $defaultValue
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function __construct(array $unitIS, $defaultValue)
    {
        $this->form = new Form($unitIS, $defaultValue);
    }

    /**
     * @param array $unitIS
     * @param array|\ArrayAccess $defaultValue
     * @return FormProxy
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public static function create(array $unitIS, $defaultValue = []): FormProxy
    {
        return new self($unitIS, $defaultValue);
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function __call($name, $arguments)
    {
        $result = $this->form->$name(...$arguments);

        return substr($name, 0, 3) === 'get' ? $result : $this;
    }
}
