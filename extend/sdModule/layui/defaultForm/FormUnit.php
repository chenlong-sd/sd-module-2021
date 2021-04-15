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
 * @method static UnitData auxTitle(string $name, string $label = 'grey')
 * @method static UnitData tag(string $name, string $label = '')
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
     * @param string $name
     * @param string $label
     * @param array $value on|off  eg:   1|2
     * @param $open_value
     * @return UnitData
     */
    public static function switchSc(string $name, string $label = '', array $value = [], $open_value = null)
    {

        $v = array_pad(array_keys($value), 2, 0);
        if($open_value !== null && $v[0] != $open_value) {
            $v = array_reverse($v);
        }
        $title = [];
        foreach ($v as $v_){
            $title[] = $value[$v_] ?? '';
        }

        return UnitData::create($name, $label)->setUnitType('switch')->selectData([
                'title' => implode('|', $title),
                'value' => $v
            ]);
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

    /**
     * 自定义html
     * @param $name
     * @param string $label
     * @param string $html
     * @return UnitData
     */
    public static function custom($name, $label = '', $html = ''): UnitData
    {
        return UnitData::create($name, $label)->setUnitType('custom')->selectData(compact('html'));
    }
}
