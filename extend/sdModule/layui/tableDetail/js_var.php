<?php
// =====================================================
// 页面的js变量设置，包含默认的路径和语言设置
// =====================================================

$base_dir = rtrim($this->root, '/');
$debug = env('APP_DEBUG');
$editor_upload = config("admin.editor_upload");
$upload_url = admin_url("image");
$resource_url = url("system.system/resource");

return <<<JS_VAR

<script>
    const DEBUG = "{$debug}";
    const ROOT = "{$base_dir}";
    const EDITOR_UPLOAD = '{$editor_upload}';
    const UPLOAD_URL = '{$upload_url}';
    const RESOURCE_URL = '{$resource_url}';
    
    // 以下为表格的多语言设置
    const PAGE_TO = "{$this->lang('page_to')}";
    const PAGE_PAGE = "{$this->lang('page_page')}";
    const PAGE_TOTAL = function (num) {
        return "{$this->lang('page_total')}".replace(1, num);
    };
    const CONFIRM = "{$this->lang('confirm')}";
    const PAGE_ARTICLE = "{$this->lang('page_article')}";
    const FILTER_COLUMN = "{$this->lang('Filter column')}";
    const EXPORT = "{$this->lang('Export')}";
    const PRINT = "{$this->lang('print')}";
    const LOADING = "{$this->lang('loading')}";
    
    // layui的多语言设置
    const L_LANG = {
        confirm: "{$this->lang('confirm')}",
        clear: "{$this->lang('clear')}",
        upload_exception:"{$this->lang('layui upload_exception')}",
        upload_exception_1:"{$this->lang('layui upload_exception_1')}",
        upload_exception_json:"{$this->lang('layui upload_exception_json')}",
        file_format_error:"{$this->lang('layui file_format_error')}",
        video_format_error:"{$this->lang('layui video_format_error')}",
        audio_format_error:"{$this->lang('layui audio_format_error')}",
        image_format_error:"{$this->lang('layui image_format_error')}",
        max_upload:"{$this->lang('layui max_upload')}",
        file_exceed:"{$this->lang('layui file_exceed')}",
        file_a:"{$this->lang('layui file_a')}",
        shrink:"{$this->lang('layui shrink')}",
        require:"{$this->lang('layui require')}",
        phone:"{$this->lang('layui phone')}",
        email:"{$this->lang('layui email')}",
        link:"{$this->lang('layui link')}",
        number:"{$this->lang('layui number')}",
        date:"{$this->lang('layui date')}",
        id_card:"{$this->lang('layui id_card')}",
        select:"{$this->lang('layui select')}",
        unnamed:"{$this->lang('layui unnamed')}",
        no_data:"{$this->lang('layui no data')}",
        no_matching_data:"{$this->lang('layui No matching data')}",
        request_exception:"{$this->lang('layui require exception')}",
        response_error:"{$this->lang('layui response error')}",
        upload_failed:"{$this->lang('layui upload error')}",
    }
    let confirm_tip = {icon:3,title:'{:lang("warning")}',btn:['{:lang("confirm")}', '{:lang("cancel")}']}
    
</script>
<script src="{$this->getRoot()}admin_static/layui/layui.all.js"></script>
<script src="{$this->getRoot()}admin_static/js/custom.js"></script>

<script>
let local = window.localStorage['layuiAdmin'];
    let alias = 'black';
    if (local != '{}') {
        alias = eval('(' + window.localStorage['layuiAdmin'] + ')').theme.color.alias;
        alias = alias === 'default' ? 'black' : alias;
    }
    layer.config({
        extend:alias +'/style.css'
        ,skin:'demo-class'
    });
function sc_event(url, data){
    layer.confirm('{$this->lang('Confirm this operation')}', {icon:3}, function (index) {
        let load = custom.loading();
        layui.jquery.ajax({
            url:url
            , type: 'post'
            , headers: {
                'X-CSRF-TOKEN': layui.jquery('meta[name="csrf-token"]').attr('content')
            }
            , data: data
            , success: function (res) {
                layer.close(load);
                if (res.code === 200) {
                    layer.msg('{$this->lang('success')}', function(){
                        location.reload();
                    });
                } else {
                    layer.alert(res.msg);
                }
            },
            error: function (err) {
                layer.close(load);
            }
        });
    });
}

</script>
JS_VAR;
