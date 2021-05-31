<?php
/**
 * Date: 2021/5/31 18:29
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;


class UnitSc
{
    public string $label;
    public string $name;
    public string $default;
    public string $formType = 'text';
    public array $removeScene = [];

    public function __construct(string $name = '', string $label = '')
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * 默认值
     * @param string $default
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function default(string $default): UnitSc
    {
        $this->default = $default;
        return $this;
    }

    /**
     * 表单类型
     * @param string $formType
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function setFormType(string $formType): UnitSc
    {
        $this->formType = $formType;
        return $this;
    }

    /**
     * 删除对应场景下的该表单
     * @param $scene
     * @return $this
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public function removeScene($scene): UnitSc
    {
        is_array($scene)
            ? $this->removeScene   = array_merge($this->removeScene, $scene)
            : $this->removeScene[] = $scene;
        return $this;
    }


}
