<?php
/**
 * datetime: 2021/11/18 11:58
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit;

use app\common\SdException;

/**
 * Class BaseFormUnitProxy
 * @mixin BaseFormUnit
 * @package sdModule\layui\form4\formUnit
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/11/18
 */
abstract class BaseFormUnitProxy
{
    /**
     * @var object
     */
    protected $unit;

    /**
     * 设置代理表单
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    abstract protected function proxyUnit(): string;

    /**
     * UnitProxyI constructor.
     * @param string $name
     * @param string $label
     * @throws SdException
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function __construct(string $name = '', string $label = '')
    {
        try {
            $this->unit = (new \ReflectionClass($this->proxyUnit()))->newInstance($name, $label);
        } catch (\Exception $exception) {
            throw new SdException("表单{$this->proxyUnit()}不存在");
        }
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
        $result = $this->unit->$name(...$arguments);

        return substr($name, 0, 3) === 'get' ? $result : $this;
    }
}
