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
    
    let confirm_tip = {icon:3,title:'{:lang("warning")}',btn:['{:lang("confirm")}', '{:lang("cancel")}']}
    
</script>
<script src="{$this->getRoot()}admin_static/layui/layui.js"></script>
<script src="{$this->getRoot()}admin_static/js/custom.js"></script>

<script>
    layui.config({
        base: '{$this->getRoot()}admin_static/layui/dist/'
    });
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
    layui.use('notice',function () {
        window.layNotice = layui.notice;
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
                    notice.success('{$this->lang('success')}', function(){
                        location.reload();
                    });
                } else {
                    notice.warning(res.msg);
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
