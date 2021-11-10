<script>
    const Thumbnail = <?= (int)env("THUMBNAIL") ?>;

    // 以下为一般网络路径设置
    const DEBUG = "<?=env('APP_DEBUG')?>";
    const ROOT = "__PUBLIC__";
    const EDITOR_UPLOAD = '<?=config("admin.editor_upload")?>';
    const UPLOAD_URL = '<?=admin_url("image")?>';
    const UPLOAD_FILE_URL = '<?=admin_url("file-upload")?>';
    const RESOURCE_URL = '<?=url("system.system/resource")?>';

    let confirm_tip = {icon: 3, title: '{:lang("warning")}', btn: ['{:lang("confirm")}', '{:lang("cancel")}']}

</script>
