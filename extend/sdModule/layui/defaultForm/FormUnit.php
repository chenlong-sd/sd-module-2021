<?php
/**
 * Date: 2020/9/26 15:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm;


use think\helper\Str;

/**
 * Class FormUnit
 * @method static UnitData select(string $name, string $label = '')
 * @method static UnitData text(string $name, string $label = '')
 * @method static UnitData hidden(string $name, string $label = '')
 * @method static UnitData password(string $name, string $label = '')
 * @method static UnitData radio(string $name, string $label = '')
 * @method static UnitData checkbox(string $name, string $label = '')
 * @method static UnitData image(string $name, string $label = '')
 * @method static UnitData upload(string $name, string $label = '')
 * @method static UnitData images(string $name, string $label = '')
 * @method static UnitData textarea(string $name, string $label = '')
 * @method static UnitData time(string $name, string $label = '')
 * @method static UnitData uEditor(string $name, string $label = '')
 * @method static UnitData selects(string $name, string $label = '')
 * @method static UnitData auxTitle(string $title, string $type = 'grey')
 * @method static UnitData tag(string $name, string $label = '')
 * @method static UnitData switchSc(string $name, string $label = '')
 * @method static UnitData custom(string $name, string $label = '')
 * @package sdModule\layui\defaultForm
 */
class FormUnit
{
    /**
     * @param $method_name
     * @param $param
     * @return UnitData
     */
    public static function __callStatic($method_name, $param): UnitData
    {
        $unit = UnitData::create(...$param)->setUnitType(Str::snake($method_name));
        if ($method_name == 'time') {
            $unit->setTime();
        } elseif ($method_name == 'upload') {
            $unit->uploadType();
        }
        return $unit;
    }

    /**
     * 行内表单
     * @param array $unit
     * @return \Closure
     */
    public static function build(...$unit): \Closure
    {
        return fn() => $unit;
    }

    /**
     * 表格形式的表单
     * @param mixed ...$unit
     * @return array
     */
    public static function table(...$unit): array
    {
        return $unit;
    }
}
