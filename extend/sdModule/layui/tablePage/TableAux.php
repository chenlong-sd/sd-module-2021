<?php
/**
 * Date: 2020/12/17 9:07
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\tablePage;


class TableAux
{

    /**
     * 表格列数据
     * @param string|array $field 字段,或数组（直接返回数组信息
     * @param string $title 字段label
     * @param string|\Closure $templet 模板id或匿名函数返回js代码
     * @param array $params 有以下参数， 详情参考 <https://www.layui.com/doc/modules/table.html#cols>
     * width，minWidth，type，LAY_CHECKED，fixed，hide，totalRow，totalRowText，sort，unresize，edit，style，event
     * align，colspan，rowspan，templet，toolbar
     * @return array
     */
    public static function column($field, $title = '', $templet = '', array $params = [])
    {
        if (is_array($field)) {
            return $field;
        }

        return array_merge(compact('field', 'title', 'templet'), $params);
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
        $url_ = self::pageUrlHandle($url);
        if (!access_control($url_)) return false;
        $window   = $isParent ? 'parent' : '';
        $config   = json_encode($config, JSON_UNESCAPED_UNICODE);

        $pageCode = sprintf("custom.frame(%s, '%s', %s, %s);", $url_, self::pageTitleHandle($title), $config, $window);

        return new OpenPage($pageCode);
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
        $url_ = self::pageUrlHandle($url);
        if (!access_control($url_)) return false;
        $pageCode = sprintf("custom.openTabsPage(%s + '&__sc_tab__=1', '%s')", $url_, self::pageTitleHandle($title));

        return new OpenPage($pageCode);
    }

    /**
     * 路径参数处理
     * @param array|string $url, 是数组时，后面的参数从该行获取并拼接到链接中
     * @return string
     */
    private static function pageUrlHandle($url)
    {
        if (is_array($url)) {
            $url_ = array_shift($url);
            $url_ = strpos($url_, '?') !== false ? "'{$url_}&id=' + obj.data[primary]" : "'{$url_}?id=' + obj.data[primary]";
            foreach ($url as $value) {
                $vArr = explode('->', $value);
                $field = $vArr[0];
                $alias = $vArr[1] ?? $vArr[0];
                $url_ .= " + '&{$alias}=' + obj.data.{$field}";
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
            return "'+ obj.data.{$var} +'";
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
    public static function ajax(string $url, string $tip, string $type = 'get')
    {
        if (!access_control($url)) return false;

        $tip     = $tip ? self::pageTitleHandle($tip) : lang('Confirm this operation');
        return (new Ajax($url))->method($type)->setConfig(['icon' => 3])->setTip($tip)->dataCode('obj.data');
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
        if (!access_control($url)) return false;

        return (new Ajax($url))->method($type)->setBatch('id')->dataCode('{id:id}');
    }

    /**
     * 跳转
     * @param $url
     * @return string
     * @throws \app\common\SdException
     */
    public static function jump(string $url)
    {
        if (!access_control($url)) return false;

        return "location.href = '{$url}'";
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
