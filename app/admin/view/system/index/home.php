{extend name="frame"}

{block name="meta"}{:token_meta()}

<style>
    body{padding: 5px}
    .layui-row>div{padding: 5px}
    #quick{
        table-layout: fixed;
        background-image: linear-gradient(to right, #cbf7f56b , #00abff52);
    }
    #quick td{
        height: 5vw;
        text-align: center;
        padding: 0;
        border: none;
    }
    #quick tr:hover{
        background-color: rgba(1,1,1, 0);
    }
    #quick td.i-have:hover{
        background-image: linear-gradient(to top, #11111115 , #00000010, #11111115);
        cursor: pointer;
        border-radius: 5px;
    }
</style>

{/block}


{block name="body"}
<!-- 导航面包屑 -->

<div class="layui-row">
    <div class="layui-col-md9">
        <div class="layui-card">
            <div class="layui-card-header"><b>快捷入口</b> -
                <button class="i-set layui-btn layui-btn-sm layui-btn-primary"><i class="layui-icon layui-icon-set"></i>设置快捷入口</button>
            </div>
            <div class="layui-card-body">
                <table id="quick" class="layui-table"></table>
            </div>
        </div>
    </div>
    <div class="layui-col-md3">
        <div class="layui-card">
            <div class="layui-card-header">系统信息</div>
            <div class="layui-card-body">
                <table class="layui-table">
                    <tr><td width="70">PHP版本</td><td><?= phpversion() ?></td></tr>
                    <tr><td>mysql版本</td><td><?= current(\think\facade\Db::query('SELECT VERSION() AS ver'))['ver'] ?></td></tr>
                    <tr><td>系统信息  </td><td><?= php_uname() ?></td></tr>
                    <tr><td>本机IP   </td><td><?= $_SERVER['REMOTE_ADDR'] ?></td></tr>
                    <tr><td>上传限制  </td><td><?= ini_get('upload_max_filesize') ?></td></tr>
                    <tr><td>空间剩余  </td>
                        <td>
                            <?php $space = disk_free_space(".") / (1024*1024);
                            echo $space > 1024 ? round($space / 1024, 3) . ' G' : $space . ' M'
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{/block}

{block name="js"}

<script>
    let $ = layui.jquery;
    $('.i-sc').on('click', function () {
        let s = $(this).attr('data-href');
        parent.layui.jquery('a[lay-href="' + s + '"]').click()
            .parents('.layui-nav-item').addClass('layui-nav-itemed')
            .siblings('.layui-nav-itemed').removeClass('layui-nav-itemed');
    });

    let tHtml = '',notCoordinate = [];
    let node = <?= json_encode($route_data, JSON_UNESCAPED_UNICODE) ?>;
    for (let i = 0; i < 9; i++) {
        tHtml += '<tr>';
        for (let j = 0; j < 9; j++) {
            let coordinate = i * 9 + j + 1;
            if (node.haveCoordinate.hasOwnProperty(coordinate)) {
                tHtml += `<td class="i-drop i-have" data-coordinate="${coordinate}"  draggable="true">
                        <p><i class="layui-icon ${node.haveCoordinate[coordinate].icon ? node.haveCoordinate[coordinate].icon : 'layui-icon-star'}"></i></p>
                        <div class="i-title" data-id="${node.haveCoordinate[coordinate].id}" data-route="${node.haveCoordinate[coordinate].route}">${node.haveCoordinate[coordinate].title}</div>
                      </td>`;
            }else if(node.notCoordinate.length > 0){
                let currentNode = node.notCoordinate.shift();
                tHtml += `<td class="i-drop i-have" data-coordinate="${coordinate}" draggable="true">
                        <p><i class="layui-icon ${currentNode.icon ? currentNode.icon : 'layui-icon-star'}"></i></p>
                        <div class="i-title" data-id="${currentNode.id}" data-route="${currentNode.route}" >${currentNode.title}</div>
                      </td>`;
                notCoordinate.push({id:currentNode.id, coordinate:coordinate});
            }else{
                tHtml += `<td class="i-drop" data-coordinate="${coordinate}" draggable="true"></td>`;
            }
        }
        tHtml += '</tr>';
    }

    if (notCoordinate.length > 0) {
        layui.jquery.ajax({
            url:'{:admin_url("quick-entrance-coordinate-set")}',
            type:'post',
            data: {data:notCoordinate}
        });
    }

    let startHtml,dropHtml;
    layui.jquery('#quick').html(tHtml).on('dragstart', 'td', function (e) {
        if (!$(e.target).html()){
            e.preventDefault();
        }else{
            startHtml = $(e.target);
        }
    }).on('drop', 'td', function (e) {
        e.preventDefault();
        dropHtml = '';
        if ($(e.target).hasClass('i-drop')) {
            dropHtml = $(e.target);
        }else if($(e.target).parents('.i-drop')){
            dropHtml = $(e.target).parents('.i-drop');
        }
        if (!dropHtml) return;
        let data = [
            {id:startHtml.find('.i-title').data('id'), coordinate:dropHtml.data('coordinate')}
        ];
        if (dropHtml.find('.i-title').data('id')) {
            data.push({id:dropHtml.find('.i-title').data('id'), coordinate:startHtml.data('coordinate')});
        }
        layui.jquery.ajax({
            url:'{:admin_url("quick-entrance-coordinate-set")}',
            type:'post',
            data: {data:data}
        });

        let dropIHtml  = dropHtml.html();
        let startIHtml = startHtml.html();
        dropHtml.html(startIHtml);
        startHtml.html(dropIHtml);
        if (!dropHtml.hasClass('i-have')) {
            dropHtml.addClass('i-have');
        }
        if (!dropIHtml) {
            startHtml.removeClass('i-have');
        }
    }).on('dragover', 'td', function (e) {
        e.preventDefault();
    }).on('click', 'td.i-have', function (e) {
        custom.frame($(this).find('.i-title').data('route'), $(this).find('.i-title').html());
    });

    $('.i-set').on('click', function () {
       custom.frame("{:admin_url('quick-entrance')}", '设置快捷入口');
    });
</script>


{/block}