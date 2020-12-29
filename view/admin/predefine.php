<script>
    const Thumbnail = <?= (int)env("THUMBNAIL") ?>;

    // 以下为一般网络路径设置
    const DEBUG = "<?=env('APP_DEBUG')?>";
    const ROOT = "__PUBLIC__";
    const EDITOR_UPLOAD = '<?=config("admin.editor_upload")?>';
    const UPLOAD_URL = '<?=admin_url("image")?>';
    const UPLOAD_FILE_URL = '<?=admin_url("file-upload")?>';
    const RESOURCE_URL = '<?=url("system.system/resource")?>';

    // 以下为表格的多语言设置
    const PAGE_TO = "<?=lang('page_to')?>";
    const PAGE_PAGE = "<?=lang('page_page')?>";
    const PAGE_TOTAL = function (num) {
        return "<?=lang('page_total')?>".replace(1, num);
    };
    const CONFIRM = "<?=lang('confirm')?>";
    const PAGE_ARTICLE = "<?=lang('page_article')?>";
    const FILTER_COLUMN = "<?=lang('Filter column')?>";
    const EXPORT = "<?=lang('Export')?>";
    const PRINT = "<?=lang('print')?>";
    const LOADING = "<?=lang('loading')?>";

    // layui的多语言设置

    const L_LANG = {
        confirm: "<?=lang('confirm')?>",
        clear: "<?=lang('clear')?>",
        upload_exception: "<?=lang('layui upload_exception')?>",
        upload_exception_1: "<?=lang('layui upload_exception_1')?>",
        upload_exception_json: "<?=lang('layui upload_exception_json')?>",
        file_format_error: "<?=lang('layui file_format_error')?>",
        video_format_error: "<?=lang('layui video_format_error')?>",
        audio_format_error: "<?=lang('layui audio_format_error')?>",
        image_format_error: "<?=lang('layui image_format_error')?>",
        max_upload: "<?=lang('layui max_upload')?>",
        file_exceed: "<?=lang('layui file_exceed')?>",
        file_a: "<?=lang('layui file_a')?>",
        shrink: "<?=lang('layui shrink')?>",
        require: "<?=lang('layui require')?>",
        phone: "<?=lang('layui phone')?>",
        email: "<?=lang('layui email')?>",
        link: "<?=lang('layui link')?>",
        number: "<?=lang('layui number')?>",
        date: "<?=lang('layui date')?>",
        id_card: "<?=lang('layui id_card')?>",
        select: "<?=lang('layui select')?>",
        unnamed: "<?=lang('layui unnamed')?>",
        no_data: "<?=lang('layui no data')?>",
        no_matching_data: "<?=lang('layui No matching data')?>",
        request_exception: "<?=lang('layui require exception')?>",
        response_error: "<?=lang('layui response error')?>",
        upload_failed: "<?=lang('layui upload failed')?>",
    }

    let confirm_tip = {icon:3,title:'{:lang("warning")}',btn:['{:lang("confirm")}', '{:lang("cancel")}']}

</script>
