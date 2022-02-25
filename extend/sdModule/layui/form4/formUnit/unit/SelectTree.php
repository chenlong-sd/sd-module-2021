<?php

namespace sdModule\layui\form4\formUnit\unit;

use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\unitConfig\{DefaultValue, JsConfig, Options, Placeholder, Required, ShortTip};

/**
 * 下拉数
 */
abstract class SelectTree extends BaseFormUnit
{
    use DefaultValue, Options, JsConfig, ShortTip, Required, Placeholder;


    public function __construct(string $name = '', string $label = '')
    {
        parent::__construct($name, $label);
        $this->placeholder = '请选择';
    }


    /**
     * 设置为单选模式
     * @param bool $allowEmpty 是否可以选空
     * @return SelectTree
     */
    public function setRadio(bool $allowEmpty = false): SelectTree
    {
        $this->jsConfig([
            'radio' => true,
            'tree'  => [
                'show'   => true,
                'strict' => false,
            ],
            'clickClose' => true,
            'model'      =>  [
                'label'  =>  [ 'type' =>  'text' ]
            ],
        ]);

        if ($allowEmpty) {
            $this->jsConfig([
                'radio'      => false,
                'clickClose' => false,
                'model'      =>  [
                    'label'  =>  [ 'type' =>  'text' ]
                ],
                'tree'  => [
                    'show'   => true,
                    'strict' => false,
                ],
                'function.on' => ['data', 'if(data.isAdd){
                            return data.change.slice(0, 1);
                        }'],
            ]);
        }

        return $this;
    }

    /**
     * 设置级联模式
     * @param bool $isStrict 是否严格遵守父子模式
     * @param int $indent 宽度间距
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/2/23
     */
    public function setCascader(bool $isStrict = true, int $indent = 200): SelectTree
    {
        return $this->jsConfig([
            'tree'  => [],
            'cascader' => [
                'show' => true,
                'indent' => $indent,
                'strict' => $isStrict
            ]
        ]);
    }

    /**
     * @param array $config
     * @return SelectTree
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/2/23
     */
    public function jsConfigTree(array $config): SelectTree
    {
        return $this->jsConfig([
            'tree' => $config
        ]);
    }
}
