<?php


namespace sdModule\layuiSearch;

use think\facade\View;

/**
 * layui数据表格搜索
 * Class Search
 * @package app\common\custom
 */
class Form
{
    /**
     * @param array $data 创建表单的数据
     * @param int $lattice  一行包含的表单元素个数
     * @return string
     */
    public static function CreateHTML(array $data, int $lattice = 4)
    {
        $js = <<<CLJS
        <script>
            layui.jquery('#closefrom').click(function () {
               layui.jquery('#search-sd').toggleClass('layui-hide')  
               return false;
            });
           
        </script>
CLJS;
        foreach ($data as $index => $value) {
            $js .= $value->js;
        }
        $html = self::htmlMake($data, $lattice);
        $js and View::assign('searchJs', $js);

        $lang = [
            'reset' => lang('reset'),
            'search' => lang('search'),
            'close' => lang('close'),
        ];


        return !$html ? '' :<<<HTML
        <blockquote id="search-sd" style="position:relative;z-index: 999"
         class="layui-hide layui-elem-quote layui-anim layui-anim-upbit">
            <form class="layui-form"  lay-filter="sd" action="">
                {$html}
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit  lay-filter="search">{$lang['search']}</button>
                        <button type="reset" class="layui-btn layui-btn-normal">{$lang['reset']}</button>
                        <button id="closefrom" class="layui-btn layui-btn-primary">{$lang['close']}</button>
                    </div>
                </div>
            </form>
        </blockquote>
HTML;
    }

    /**
     * html 组建
     * @param     $data
     * @param int $number
     * @return string
     */
    private static function htmlMake($data, $number = 4)
    {
        $div = ' <div class="layui-form-item">{:form}</div>';
        $html = $lattice = '';
        foreach ($data as $index => $value) {
            $lattice .= $value->html;
            if ($index > 0 && (($index + 1) % $number === 0)) {
                $html .= strtr($div, ['{:form}' => $lattice]);
                $lattice = '';
            }
        }

        $lattice and $html .= strtr($div, ['{:form}' => $lattice]);
        return $html;
    }
}


