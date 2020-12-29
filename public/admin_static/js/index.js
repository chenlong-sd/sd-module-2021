layui.define(['layer', 'form','table', 'element'], function(exports){
    var layer = layui.layer
        ,form = layui.form
        ,$=  layui.$;
    var table = layui.table,
        element = layui.element;
//监听提交
    var frame = parent.layer.getFrameIndex(window.name);

    form.on('submit(demo1)', function(data){
        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
       login(url('add_ajax'),data.field,index,frame);
        return false;
    });






    function login(url,data,index='',frame='') {

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
                    time: 1000 //2秒关闭（如果不配置，默认是3秒）
                }, function(){
                    parent.layer.close(frame);
                    parent.location.reload();
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
                                if(result.responseJSON.url){
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
                                if(result.responseJSON.url){
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










});