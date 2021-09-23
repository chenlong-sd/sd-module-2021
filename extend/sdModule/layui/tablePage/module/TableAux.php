<?php
/**
 * Date: 2020/12/17 9:07
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage\module;


class TableAux
{

    /**
     * 表格列数据
     * @param string $field 字段
     * @param string $title 字段label
     * @return TableColumn
     */
    public static function column(string $field = '', string $title = ''): TableColumn
    {
        return new TableColumn($field, $title);
    }


    /**
     * 打开页面
     * @param $url
     * @param string $title 标题，可用使用行数据的变量值， 例：编辑【{title}】, {title}会替换为该行的title字段
     * @param array $config 弹窗的其他配置项，如宽高
     * @param bool $isParent 是否在负极
     * @return false|OpenPage
     * @throws \app\common\SdException
     */
    public static function openPage($url, string $title, array $config = [], $isParent = false)
    {
        $power = access_control(is_array($url) ? current($url) : $url);
        $url_  = self::pageUrlHandle($url);
        $window   = $isParent ? 'parent' : '';
        $config   = json_encode($config, JSON_UNESCAPED_UNICODE);

        $pageCode = sprintf("custom.frame(%s, '%s', %s, %s);", $url_, self::pageTitleHandle($title), $config, $window);
        return new OpenPage($pageCode, $power);
    }

    /**
     * 打开 tab 页面
     * @param $url
     * @param string $title
     * @return false|OpenPage
     * @throws \app\common\SdException
     */
    public static function openTabs($url, string $title)
    {
        $power = access_control(is_array($url) ? current($url) : $url);
        $url_ = self::pageUrlHandle($url);
        $pageCode = sprintf("custom.openTabsPage(%s + '%s__sc_tab__=1', '%s')", $url_, strpos($url_, '?') === false ? '?' : '&', self::pageTitleHandle($title));

        return new OpenPage($pageCode, $power);
    }

    /**
     * 路径参数处理
     * @param array|string $url, 是数组时，后面的参数从该行获取并拼接到链接中
     * @return string
     */
    private static function pageUrlHandle($url): string
    {
        if (is_array($url)) {
            $url_ = array_shift($url);
            $url_ = strpos($url_, '?') !== false ? "'{$url_}&id=' + obj[primary]" : "'{$url_}?id=' + obj[primary]";
            foreach ($url as $value) {
                $vArr = explode('->', $value);
                $field = $vArr[0];
                $alias = $vArr[1] ?? $vArr[0];
                $url_ .= " + '&{$alias}=' + obj.{$field}";
            }
        }else{
            $url_ = "'{$url}'";
        }
        return $url_;
    }

    /**
     * 打开page页面的参数的标题变量替换
     * @param string $title
     * @return string|string[]|null
     */
    public static function pageTitleHandle(string $title)
    {
        return preg_replace_callback('/\{\w+\}/', function ($v) {
            $var = strtr(current($v), ['{' => '', '}' => '']);
            return "'+ obj.{$var} +'";
        }, $title);
    }

    /**
     * ajax请求的js代码
     * @param string $url
     * @param string $tip
     * @param string $type
     * @return false|Ajax
     * @throws \app\common\SdException
     */
    public static function ajax(string $url, string $tip = '', string $type = 'get')
    {
        $tip     = $tip ? self::pageTitleHandle($tip) : lang('Confirm this operation');
        return (new Ajax($url))->method($type)->setConfig(['icon' => 3])->setTip($tip)->dataCode('obj');
    }

    /**
     * ajax请求的js代码
     * @param string $url
     * @param string $type
     * @return false|Ajax
     * @throws \app\common\SdException
     */
    public static function batchAjax(string $url, $type = 'get')
    {
        return (new Ajax($url))->method($type)->setBatch('id')->dataCode('{id:id}');
    }

    /**
     * 跳转
     * @param string|array $url
     * @return string
     * @throws \app\common\SdException
     */
    public static function jump($url): string
    {
        if (!access_control($url)) return 'false';
        $url = self::pageUrlHandle($url);
        return "location.href = {$url}";
    }


    /**
     * 获取批量选择数据处理
     * @param string $function_name
     * @return string
     */
    public static function batchDataHandle(string $function_name)
    {
        $please = lang('please select data');

        return <<<JS
            let checkStatus = table.checkStatus('sc');
            if (checkStatus.data.length) {
                let id = [];
                for (let i in checkStatus.data) {
                    if (checkStatus.data.hasOwnProperty(i) && checkStatus.data[i].hasOwnProperty(primary)) {
                        id.push(checkStatus.data[i][primary])
                    }
                }
                {$function_name}(id);
            }else{
                notice.warning('{$please}');
            }
JS;
    }

    /**
     * 增加搜索条件
     * @param array $search
     * @return string
     */
    public static function searchWhere(array $search)
    {
        $search = json_encode($search, JSON_UNESCAPED_UNICODE);
        return <<<JS
        table.reload('sc', {
            where: {
                search: {$search}
            }
            , page: {
                curr: 1
            }
        });
JS;
    }
}
