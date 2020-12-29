<?php
/**
 * Date: 2020/11/4 16:49
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui;


use sdModule\common\StaticCallGetInstance;
use sdModule\layui\item\Button;
use sdModule\layui\item\Tag;

/**
 * Class Layui
 * @method static Tag tag()
 * @method static Button button(string $title = '', string $icon_class = '')
 * @package sdModule\layui
 */
class Layui extends StaticCallGetInstance
{

    public function getNamespace(): string
    {
        return "sdModule\\layui\\item\\";
    }

}

