<?php
/**
 * Date: 2021/5/31 18:20
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\form;


use sdModule\common\Sc;
use sdModule\layui\Dom;
use sdModule\layui\form\formUnit\Selects;
use sdModule\layui\form\formUnit\UnitBase;
use think\helper\Str;

class FormSc
{
    /**
     * @var UnitData[] 表单元素数据
     */
    private array $formData;

    /**
     * @var string 场景
     */
    private string $scene;

    /**
     * @var array 表单元素
     */
    private array $unit = [];

    /**
     * @var array 表单元素的js
     */
    private array $unitJs = [];
    /**
     * @var array 加载的外部js
     */
    private array $loadJs = [];


    /**
     * FormSc constructor.
     * @param UnitData[] $formData
     * @param string $scene
     */
    public function __construct(array $formData, string $scene = 'normal')
    {
        $this->formData = $formData;
        $this->scene    = $scene;
    }

    /**
     * @param array|UnitData[] $fromData
     * @param string $scene 单签表单所在场景值
     * @return FormSc
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/5/31
     */
    public static function create(array $fromData, string $scene = 'normal'): FormSc
    {
        return new self($fromData, $scene);
    }

    /**
     * 创建表单元素html
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function makeUnitHtml()
    {
        foreach ($this->formData as $unitData) {
            if ($unitData->get('removeScene') == $this->scene) {
                continue;
            }
            $unit = $this->makeCode($unitData);

            if ($childrenItemData = $unitData->get('childrenItem', [])) {
                foreach ($childrenItemData as $itemDatum) {
                    $unit->addChildrenItem($this->makeCode($itemDatum));
                }
            }
            $inputAttr      = $unitData->get('inputAttr');
            $this->unit[]   = $unit->getHtml(array_merge($inputAttr['-'] ?? [], $inputAttr[$this->scene] ?? []));
            $this->unitJs[] = $unit->getJs();
        }
    }

    /**
     * 创建代码Dom和js数据
     * @param UnitData $unitData
     * @return UnitBase
     * @throws \ReflectionException
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function makeCode(UnitData $unitData): UnitBase
    {
        /** @var UnitBase $unit */
        $unit = Sc::reflex()->getInstance($this->getUnitClassname($unitData->get('formUnitType')), [
            'name'          => $unitData->get('name', ''),
            'label'         => $unitData->get('label', ''),
            'placeholder'   => $unitData->get('placeholder', ''),
        ]);

        $unit->setDefault($unitData->get('defaultValue', ''));
        $unit->setOption($unitData->get('options', []));

        if ($unit instanceof Selects) {
            $this->loadJs['Selects'] = Dom::create('script')->addAttr('src', $this->assets('/admin_static/layui/dist/xm-select.js'));
        }

        return $unit;
    }

    /**
     * 获取表单元素的类名
     * @param string $unitName
     * @return string
     * @author chenlong <vip_chenlong@163.com>
     * @date 2021/6/1
     */
    private function getUnitClassname(string $unitName): string
    {
        return "sdModule\\layui\\form\\formUnit\\" . Str::studly($unitName);
    }

    /**
     * 资源路径组合
     * @param $src
     * @return string
     */
    private function assets($src)
    {
        return rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), ['\\' => '/']), '/') . $src;
    }


    public function complete()
    {
        $this->makeUnitHtml();
    }


    public function getSkin()
    {
        return '';
    }

    public function getCustomMd()
    {
        return 12;
    }

    public function getHtml()
    {
        return implode('', $this->unit);
    }

    public function loadJs()
    {
        return implode($this->loadJs);
    }

    public function getJs()
    {
        return implode($this->unitJs);
    }
}
