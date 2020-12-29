<?php


namespace sdModule\layuiSearch;


use sdModule\layuiSearch\generate\{Select, Selects, Text, Time, TimeRange};
use sdModule\common\StaticCallGetInstance;

/**
 * 搜索表单html创建
 * Class SearchForm
 * @example
 * @method static Text Text(string $name, string $placeholder = '')
 * @method static Select Select(string $name, string $placeholder = '')
 * @method static Selects Selects(string $name, string $placeholder = '')
 * @method static Time Time(string $name, string $placeholder = '')
 * @method static TimeRange TimeRange(string $name, string $placeholder = '')
 * @package sdModule\layuiSearch
 */
class SearchForm extends StaticCallGetInstance
{
    protected function getNamespace(): string
    {
        return "sdModule\\layuiSearch\\generate\\";
    }

}

