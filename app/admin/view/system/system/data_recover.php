{extend name="frame"}

<?php

// ======================================
// 此为自定义页面的继承模板文件，复制重命名文件即可
// 更多的模块重写，查看frame.php文件
// ======================================

?>

{block name="meta"}{:token_meta()}

<style>
    .dw{
        position: fixed;
        bottom: 10px;
        width: 60%;
        height: 18px;
        z-index: 111;
        margin-left: -30%;
        left: 50%;
    }
</style>

{/block}


{block name="body"}
<!-- 导航面包屑 -->
<hr>
<div class="layui-container" style="margin-bottom: 300px">
    <div class="layui-row"></div>
    <div class="dw">
        <div class="layui-progress  layui-progress-big dw"  lay-filter="progress1">
            <div class="layui-progress-bar  layui-bg-blue"></div>
        </div>
    </div>

</div>


{/block}

{block name="js"}

<script>

    let lIndex = 0, current = 0,all = 1;
    layui.jquery.ajax({type:'post'});
    let s = setInterval(function () {
        layui.jquery.ajax({
            type: 'get', success(res) {
                if (!res.data) return;
                if (res.data.substr(-7) === '--end--') {
                    clearInterval(s);
                }
                /// 所有表数量
                let all_string = res.data.match(/总量数：([0-9]+)/);
                if (all_string) {
                    all = all_string[1];
                }

                // 已备份数量
                let current_string = res.data.match(/r:([0-9]+)/g);
                if (current_string) {
                    current = current_string.pop().match(/[0-9]+/)[0];
                }

                layui.element.progress('progress1', `${current/all*100}%`);
                layui.jquery('.layui-row').append(res.data.replace(/\r\n/g, '<br>'));
                window.scrollTo(0, document.documentElement.scrollHeight);
            }
        });
    }, 200);

</script>


{/block}