<?php
/**
 * datetime: 2021/11/18 15:49
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form4\formUnit;

use sdModule\layui\Dom;

trait FormUnitT
{

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getShowWhere(): array
    {
        return $this->showWhere ?? [];
    }

    /**
     * @return string|null
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getFormUnitId(): ?string
    {
        return $this->formUnitId;
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function getLabel(): string
    {
        return $this->label ?? '';
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder ?? '';
    }

    /**
     * @param string $scene
     * @return Dom
     * @throws \Exception
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getElementS(string $scene): Dom
    {
        if (in_array($scene, $this->removeScene)) {
            return Dom::create();
        }
        return $this->getElement($scene);
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    public function getJsConfig(): array
    {
        return $this->jsConfig ?? [];
    }

    /**
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function clearShowWhere()
    {
        $this->showWhere = [];
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * 获取用户设置的关联选项
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    public function getAssociationOptions(): array
    {
        return $this->getOptions() && !empty($this->associatedField)
            ? [
                'field' => $this->associatedField,
                'options' => $this->getOptions()
            ] : [];
    }

    /**
     * 设置实际的关联选项
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/21
     */
    public function setAssociationOptions(array $associationOptions)
    {
        $this->associationOptions = $associationOptions;
    }

    /**
     * @return mixed|null
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/20
     */
    public function getDefaultValue()
    {
        return $this->defaultValue ?? '';
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getJs(): string
    {
        return '';
    }

    /**
     * @return string
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getCss(): string
    {
        return '';
    }

    /**
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/11/18
     */
    public function getLoadJs(): array
    {
        return [];
    }
}
