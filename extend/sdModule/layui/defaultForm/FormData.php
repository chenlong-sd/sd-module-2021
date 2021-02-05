<?php
/**
 * Date: 2020/9/26 15:44
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm;


class FormData
{

    const SELECT    = 'select';
    const TEXT      = 'text';
    const HIDDEN    = 'hidden';
    const PASSWORD  = 'password';
    const RADIO     = 'radio';
    const CHECKBOX  = 'checkbox';
    const IMAGE     = 'image';
    const UPLOAD    = 'upload';
    const IMAGES    = 'images';
    const TEXTAREA  = 'textarea';
    const TIME      = 'time';
    const U_EDITOR  = 'u_editor';
    const SELECTS   = 'selects';
    const TEXT_SHORT= 'textShort';
    const TAG       = 'tag';
    const SWITCH    = 'switch_sc';
    const AUX_TITLE = 'aux_title';

    /**
     * 返回生成HTML表单的数据
     * @param string $name
     * @param string $label
     * @param string $type
     * @param array $select_data
     * @param string $placeholder
     * @return UnitData
     */
    public static function generate(string $name, string $label, string $type = self::TEXT, array $select_data = [], string $placeholder = '')
    {
        return UnitData::_self($name, $label, $type, $placeholder, $select_data);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $placeholder
     * @return UnitData
     */
    public static function text(string $name, string $label = '', string $placeholder = '')
    {
        return self::generate($name, $label, self::TEXT, [], $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $placeholder
     * @param string $tip
     * @return UnitData
     */
    public static function textShort(string $name, string $label = '', string $placeholder = '', string $tip = '')
    {
        return self::generate($name, $label, self::TEXT_SHORT, ['tip' => $tip], $placeholder);
    }

    /**
     * @param string $name
     * @return UnitData
     */
    public static function hidden(string $name)
    {
        return self::generate($name, '', self::HIDDEN);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $select_data
     * @param string $placeholder
     * @return UnitData
     */
    public static function select(string $name, string $label = '', array $select_data = [],  string $placeholder = '')
    {
        return self::generate($name, $label, self::SELECT, $select_data, $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $select_data
     * @param string $placeholder
     * @return UnitData
     */
    public static function selects(string $name, string $label = '', array $select_data = [],  string $placeholder = '')
    {
        return self::generate($name, $label, self::SELECTS, $select_data, $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $type date|datetime|year|month|time
     * @param bool|string $range
     * @param string $placeholder
     * @return UnitData
     */
    public static function time(string $name, string $label = '', string $type = 'datetime', $range = false, string $placeholder = '')
    {
        return self::generate($name, $label, self::TIME, compact('type', 'range'), $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $select_data
     * @return UnitData
     */
    public static function radio(string $name, string $label = '', array $select_data = [])
    {
        return self::generate($name, $label, self::RADIO, $select_data);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $select_data
     * @return UnitData
     */
    public static function checkbox(string $name, string $label = '', array $select_data = [])
    {
        return self::generate($name, $label, self::CHECKBOX, $select_data);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $placeholder
     * @return UnitData
     */
    public static function textarea(string $name, string $label = '', string $placeholder = '')
    {
        return self::generate($name, $label, self::TEXTAREA, [], $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $placeholder
     * @return UnitData
     */
    public static function password(string $name, string $label = '', string $placeholder = '')
    {
        return self::generate($name, $label, self::PASSWORD, [], $placeholder);
    }

    /**
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function image(string $name, string $label = '')
    {
        return self::generate($name, $label, self::IMAGE);
    }

    /**
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function images(string $name, string $label = '')
    {
        return self::generate($name, $label, self::IMAGES);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $type
     * @return UnitData
     */
    public static function upload(string $name, string $label = '', string $type = 'file')
    {
        return self::generate($name, $label, self::UPLOAD, ['type' => $type]);
    }

    /**
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function uEditor(string $name, string $label = '')
    {
        return self::generate($name, $label, self::U_EDITOR);
    }

    /**
     * @param string $name
     * @param string $label
     * @return UnitData
     */
    public static function tag(string $name, string $label = '')
    {
        return self::generate($name, $label, self::TAG);
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $value on|off  eg:   1|2
     * @param $open_value
     * @return UnitData
     */
    public static function switch_(string $name, string $label = '', array $value = [], $open_value = null)
    {

        $v = array_pad(array_keys($value), 2, 0);
        if($open_value !== null && $v[0] != $open_value) {
            $v = array_reverse($v);
        }
        $title = [];
        foreach ($v as $v_){
            $title[] = $value[$v_] ?? '';
        }

        return self::generate($name, $label, self::SWITCH, [
            'title' => implode('|', $title),
            'value' => $v
        ]);
    }

    /**
     * @param array $unit
     * @return \Closure
     */
    public static function build(...$unit)
    {
        return fn() => $unit;
    }

    /**
     * @param mixed ...$unit
     * @return array
     */
    public static function table(...$unit)
    {
        return $unit;
    }

    /**
     * 辅助标题
     * @param $title
     * @param bool|string $is_custom bool | grey | white | line | h3
     * @return UnitData
     */
    public static function auxTitle($title, $is_custom = "grey")
    {
        $is_custom = $is_custom === true ? '__' : $is_custom;
        return UnitData::_self($title, $is_custom, self::AUX_TITLE, '', []);
    }

    public static function custom($name, $label = '', $html = '')
    {
        return self::generate($name, $label, 'custom', ['html' => $html]);
    }
}
