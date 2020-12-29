{extend name="frame"}

<?php

// ======================================
// 此为自定义页面的继承模板文件，复制重命名文件即可
// 更多的模块重写，查看frame.php文件
// ======================================

?>

{block name="meta"}{:token_meta()}{/block}


{block name="body"}
<!-- 导航面包屑 -->
<hr>
<div class="layui-container">
    <div class="layui-row">

        <?php // 此处写html代码, 此页面自带的div可去除 ?>

    </div>
</div>


{/block}

{block name="js"}


<?php // 此处写js代码，需带script标签?>


{/block}