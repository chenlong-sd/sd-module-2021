layui.define(['layer', 'form','table', 'element'], function(exports){
    var layer = layui.layer
        ,form = layui.form
        ,$=  layui.$;
    var table = layui.table,
        element = layui.element;



    table.render({
        elem: '#demo'
        ,with:'100%'
        ,url: url('ad_index_ajax') //数据接口
        ,title: '用户表'
        ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
            layout: ['limit', 'count', 'prev', 'page', 'next', 'skip','refresh'] //自定义分页布局
            ,curr: 1 //设定初始在第 5 页
            ,limits:[15,25,35,45,55]//分页的选项
            ,limit:15//数据
        }
        ,toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
        ,cols: [[ //表头
            {type: 'checkbox', fixed: 'center'}
            ,{field: 'id', title: 'ID', sort: true, align:'center',fixed: 'center', totalRowText: '合计：'}
            ,{field: 'name',  align:'left',title: '标题',edit: 'text'}
            ,{field: 'sort', title: '排序', sort: true, align:'center', unresize: true,edit: 'text'}
            ,{field: 'icon', title: '图标', sort: true, align:'center', totalRow: true,edit: 'text'}
            ,{field: 'url', title: '路径', sort: true, align:'center', totalRow: true,edit: 'text'}
            ,{field: 'status', title: '是否审核', templet: '#switchTpl',sort: true, align:'center',totalRow: true }
            ,{field: 'leve',  align:'center',title: '等级',sort: true}
            ,{fixed: 'right',  title: '操作',align:'center', toolbar: '#barDemo'}
        ]]
        ,parseData: function(res){ //res 即为原始返回的数据
        }
    });





    //监听头工具栏事件
    table.on('toolbar(test)', function(obj){
        var checkStatus = table.checkStatus(obj.config.id)
            ,data = checkStatus.data; //获取选中的数据
        switch(obj.event){
            case 'add':
                layer.open({
                    type: 2,
                    area: ['700px', '500px'],
                    fixed: false, //不固定
                    maxmin: true,
                    title:'添加',
                    content: url('add')
                });
                break;
            case 'update':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else if(data.length > 1){
                    layer.msg('只能同时编辑一个');
                } else {
                    layer.alert('编辑 [id]：'+ checkStatus.data[0].id);
                }
                break;
            case 'delete':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else {
                    layer.msg('删除');
                }
                break;
        };
    });






//滑块
    form.on('switch(sexDemo)', function(obj){
        if( this.value != 2 && this.value != 4){
            var data ={};
            data[this.name] = obj.elem.checked;
            data['id'] = this.value;
            login('edit',data);
        }else{
            if(obj.elem.checked == false){
                layer.msg('此栏目不允许修改',{
                    time: 1000
                },function(){
                    var selectIfKey=obj.othis;
                    // 获取当前所在行
                    var parentTr = selectIfKey.parents("tr");
                    // 获取当前所在行的索引
                    var switchIfNull=$(parentTr).find("td:eq(6)").find("div:eq(1)");
                    switchIfNull.prop("class","layui-unselect layui-form-switch layui-form-onswitch");//F的样式
                    switchIfNull.find("em").text("审核");
                });
            }
        }
    });







    //监听行工具事件
    table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        switch(layEvent){
            case 'del':
                layer.confirm('真的删除行么', function(index){
                    layer.close(index);
                });
                break;
        }
    });









    table.on('edit(test)', function(obj){
        var value = obj.value //得到修改后的值
            ,data = obj.data //得到所在行所有键值
            ,field = obj.field; //得到字段
        var date ={};
        date['id']=data.id;
        date[field]=value;
        login('index_edit',date)
    });







    function login(url,data,location =false,index='') {
        $.ajax({
            //几个参数需要注意一下
            type: "POST",//方法类型
            dataType: "json",//预期服务器返回的数据类型
            url: url ,//url
            data: data,
            success: function (result) {
                layer.close(index);
                //location.href=result.url;
                layer.msg(result.msg, {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    //消失之后的回调
                    result.url && location ? location.href=result.url : location.reload();
                });
            },
            error : function(result) {
                layer.close(index);
                if(result.responseJSON){
                    switch (typeof result.responseJSON.msg) {
                        case "string":
                            layer.msg(result.responseJSON.msg, {
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){
                                //消失之后的回调
                                if(result.responseJSON.url && location){
                                    location.href=result.responseJSON.url;
                                }
                            });
                            break;
                        case "object":
                            var alert='';
                            layui.each(result.responseJSON.msg, function (item ,vales) {
                                alert= alert + vales+',';
                            });
                            alert = alert.substr(0, alert.length - 1);
                            layer.msg(alert, {
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){
                                //消失之后的回调
                                if(result.responseJSON.url && location){
                                    location.href=result.responseJSON.url;
                                }
                            });
                            break;
                    }
                }else{
                    layer.msg('小伙子出错了(具体在哪我也母鸡呀)', function(){
                        //消失之后的回调
                        //location.reload();
                    });
                }
            }
        },'json');
    }



    exports('index_table', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});