{extend name="frame"}

<?php

// ======================================
// 此为自定义页面的继承模板文件，复制重命名文件即可
// 更多的模块重写，查看frame.php文件
// ======================================

?>

{block name="meta"}{:token_meta()}

<link rel="stylesheet" href="__PUBLIC__/admin_static/codeMirror/codemirror.css">
<link rel="stylesheet" href="__PUBLIC__/admin_static/codeMirror/darcula.css">

{/block}


{block name="body"}
<!-- 导航面包屑 -->

        <textarea name="s" id="tttt"></textarea>


{/block}

{block name="js"}
<script src="__PUBLIC__/admin_static/codeMirror/codemirror.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/php.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/htmlmixed.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/xml.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/css.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/clike.js"></script>
<script src="__PUBLIC__/admin_static/codeMirror/javascript.js"></script>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById('tttt'), {
        lineNumbers: true,
        theme: 'darcula',
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
    }).setValue('\<\?php\n\n// 你猜这个是做什么的？\n\n$a = "Hello world!";\necho $a;');
</script>


{/block}