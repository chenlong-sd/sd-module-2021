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
     * @param bool $is_parent 是否在负极
     * @return bool|string
     * @throws \app\common\SdException
     */
    public static function openPage($url, string $title, array $config = [], $is_parent = false)
    {
        $url_ = self::pageUrlHandle($url);
        if (!access_control($url_)) return false;
        $window   = $is_parent ? 'parent' : '';
        $config   = json_encode($config, JSON_UNESCAPED_UNICODE);

        return sprintf("custom.frame(%s, '%s', %s, %s);", $url_, self::pageTitleHandle($title), $config, $window);
    }

    /**
     * 打开 tab 页面
     * @param $url
     * @param string $title
     * @return bool|string
     * @throws \app\common\SdException
     */
    public static function openTabs($url, string $title)
    {
        $url_ = self::pageUrlHandle($url);
        if (!access_control($url_)) return false;
        return sprintf("custom.openTabsPage(%s + '&__sc_tab__=1', '%s')", $url_, self::pageTitleHandle($title));
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
                $v_arr = explode('->', $value);
                $field = $v_arr[0];
                $alias = $v_arr[1] ?? $v_arr[0];
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
    private static function pageTitleHandle(string $title)
    {
        return preg_replace_callback('/\{\w+\}/', function ($v) {
            $var = strtr(current($v), ['{' => '', '}' => '']);
            return "'+ obj.data.{$var} +'";
        }, $title);
    }


    /**
     * ajax请求的js代码
     * @param string $url 路径
     * @param string $type get|post
     * @param string $tip 可用使用行数据的变量值， 例：编辑【{title}】, {title}会替换为该行的title字段
     * @param string $title
     * @return string
     * @throws \app\common\SdException
     */
    public static function ajax(string $url, $type = 'get', string $tip = '' ,string $title = '警告')
    {
        if (!access_control($url)) return false;

        $tip     = $tip ? self::pageTitleHandle($tip) : lang('Confirm this operation');
        $success = lang('success');
        return <<<JS
        ScXHR.confirm('{$tip}',{title:"{$title}",icon:3}).ajax({url:"{$url}",type:"{$type}",data:obj.data,success(res){
                layer.close(window.load___);
                if (res.code === 200) {
                    layNotice.success('{$success}');
                    table.reload('sc');
                } else {
                    layNotice.warning(res.msg);
                } 
            }
        });
JS;
    }

    /**
     * ajax请求的js代码
     * @param string $url
     * @param string $type
     * @param string $tip
     * @return string
     * @throws \app\common\SdException
     */
    public static function batchAjax(string $url, $type = 'get', string $tip = '')
    {
        if (!access_control($url)) return false;

        $tip     = $tip ?: lang('Confirm this operation');
        $success = lang('success');
        $batch_handle = self::batchDataHandle('aj');

        return <<<JS

       function aj(id) {
          ScXHR.confirm("{$tip}",{icon:3}).ajax({url:"{$url}",type:"{$type}",data:{id:id},success(res){
                layer.close(load);
                if (res.code === 200) {
                    layNotice.success('{$success}');
                    table.reload('sc');
                } else {
                    layNotice.warning(res.msg);
                } 
            }
          });
       }
        
       {$batch_handle}
JS;
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
