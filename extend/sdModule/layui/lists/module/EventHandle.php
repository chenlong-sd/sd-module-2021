<?php
/**
 * datetime: 2021/9/20 9:42
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\lists\module;


use sdModule\layui\Dom;
use sdModule\layui\form4\FormUnit;
use sdModule\layui\form4\formUnit\BaseFormUnit;
use sdModule\layui\form4\formUnit\BaseFormUnitProxy;
use sdModule\layui\form4\formUnit\FormUnitT;

/**
 * Class EventHandle
 * @package sdModule\layui\lists\module
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/20
 */
class EventHandle
{
    use EventHandleParamHandle;

    /**
     * 打开页面
     * @param array|string $url 是数组时，后面的参数从该行获取并拼接到链接中
     * @example ['/test', 'id', 'title' => 'title_alias']
     * @param string $title 标题，可用使用行数据的变量值， 例：编辑【{title}】, {title}会替换为该行的title字段
     * @return OpenPage
     * @throws \app\common\SdException
     */
    public static function openPage($url, string $title): OpenPage
    {
        return new OpenPage($url, $title);
    }

    /**
     * ajax请求的js代码
     * @param string $url 请求地址
     * @param string $tip 请求提示
     * @param string $type 请求类型
     * @return Ajax
     * @throws \app\common\SdException
     */
    public static function ajax(string $url, string $tip = '', string $type = 'post'): Ajax
    {
        $tip     = $tip ?: lang('Confirm this operation');
        return (new Ajax($url))->method($type)->setConfig(['icon' => 3])->setTip($tip);
    }

    /**
     * 页面跳转
     * @param string|array $url
     * @return string
     * @throws \app\common\SdException
     */
    public static function jump($url): string
    {
        if (!access_control($url)) return 'false';
        $url = self::url($url);
        return "location.href = {$url};";
    }

    /**
     * 增加搜索条件
     * @param array $search
     * @return string
     */
    public static function addSearch(array $search): string
    {
        $search = json_encode($search, JSON_UNESCAPED_UNICODE);
        return <<<JS
        table.reload('sc', {
            where: {
                search: $search
            }
            , page: {
                curr: 1
            }
        }, true);
JS;
    }


    /**
     * @param BaseFormUnitProxy[] $formUnit
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/12/10
     */
    public static function openForm(...$formUnit)
    {
        return new OpenForm($formUnit);
    }
}

