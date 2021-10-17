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
    .dw1{
        position: fixed;
        bottom: 50px;
        width: 60%;
        height: 7px;
        z-index: 111;
        margin-left: -30%;
        left: 50%;
        display: none;
    }
</style>

{/block}


{block name="body"}
<!-- 导航面包屑 -->
<hr>
<div class="layui-container" style="margin-bottom: 300px">
    <div class="layui-row"></div>
    <div class="dw1">
        <div class="layui-progress dw1"  lay-filter="progress">
            <div class="layui-progress-bar  layui-bg-orange"></div>
        </div>
    </div>
    <div class="dw">
        <div class="layui-progress  layui-progress-big dw"  lay-filter="progress1">
            <div class="layui-progress-bar  layui-bg-blue"></div>
        </div>
    </div>

</div>


{/block}

{block name="js"}

<script>
    let load = [
        '数据备份中请不要关闭页面和刷新页面 >',
        '数据备份中请不要关闭页面和刷新页面 >>',
        '数据备份中请不要关闭页面和刷新页面 >>>',
        '数据备份中请不要关闭页面和刷新页面 >>>>',
        '数据备份中请不要关闭页面和刷新页面 >>>>>',
    ];

    let lIndex = 0, current = 0,all = 1;
    layui.jquery.ajax({type:'post', data: {name:"{$Request.get.name}"}});
    let s = setInterval(function () {
        layui.jquery.ajax({
            type: 'get', success(res) {
                if (!res.data) return;
                if (res.data.substr(-7) === '--end--') {
                    clearInterval(s);
                }
                /// 所有表数量
                let all_string = res.data.match(/本次备份数据表数量：([0-9]+)/);
                if (all_string) {
                    all = all_string[1];
                }

                // 已备份数量
                let current_string = res.data.match(/当前已完成备份数据表数量：([0-9]+)/g);
                if (current_string) {
                    current = current_string.pop().match(/[0-9]+/)[0];
                }

                // 数据展示
                let data_string = res.data.match(/数据已备份至：([0-9]+)\/([0-9]+)$/)
                if (data_string){
                    layui.jquery('.dw1').show();
                    layui.element.progress('progress', `${data_string[1]/data_string[2]*100}%`);
                }else{
                    layui.jquery('.dw1').hide();
                    layui.element.progress('progress', `0%`);
                }

                if (all <= 1) {
                    layui.jquery('.dw').hide();
                }else{
                    layui.jquery('.dw').show();
                    layui.element.progress('progress1', `${current/all*100}%`);
                }

                layui.jquery('.layui-row').append(res.data.replace(/\r\n/g, '<br>'));
                window.scrollTo(0, document.documentElement.scrollHeight);
            }
        });
    }, 200);

</script>


{/block}