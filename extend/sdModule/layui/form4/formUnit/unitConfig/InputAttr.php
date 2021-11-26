<?php
/**
 * datetime: 2021/11/19 1:42
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit\unitConfig;

trait InputAttr
{
    /**
     * @var array 表单基础组件的属性设置
     */
    protected $inputAttr = [];


    /**
     * @param string|array $scene 场景名称|属性数组（此时表示全场景通用
     * @param array $inputAttr 属性数组
     * @return $this
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/19
     */
    public function inputAttr($scene, array $inputAttr = [])
    {
        if (is_array($scene)) {
            $this->inputAttr['-'] = $scene;
        }else{
            $this->inputAttr[$scene] = $inputAttr;
        }

        return $this;
    }

    /**
     * 获取当前场景下的表单属性
     * @param string $scene
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    protected function getCurrentSceneInputAttr(string $scene): array
    {
        return array_merge($this->inputAttr['-'] ?? [], $this->inputAttr[$scene] ?? []);
    }

}
