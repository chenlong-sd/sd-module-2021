/* =====================
* 自定义的一些
* ===========================*/


var custom;
custom = {
    /**
     * 自定义百度编辑器上传路径锁
     */
    lock_editor_custom_url: false
    /**
     * 自定义加载层
     * @param msg   文字提示或关闭后的回调函数
     * @param closeCallback 关闭后的回调函数
     * @returns {*}
     */
    , loading: function (msg, closeCallback) {
        if (typeof msg == 'function') {
            closeCallback = msg;
        }
        msg = typeof msg == 'string' ? msg : '该操作可能需要一些时间，请稍候...';
        return layer.msg(msg, {
            icon: 16
            , time: 0
            , shade: 0.1
        }, function () {
            typeof closeCallback == 'function' && closeCallback();
        });
    }
    /**
     * 自定义 frame 层
     * @param url   路径
     * @param title 标题
     * @param param 其他参数，所有参数以这个优先
     * @param parent_window 窗口对象
     * @returns {*}
     */
    , frame: function (url, title, param, parent_window) {
        let layer1  = parent_window ? parent_window.layer : layer;
        let window1 = parent_window ? parent_window : window;
        let frame = {
            type: 2,
            content: url
            , area: ['85%', '90%']
            , maxmin: true
            , shade: false
            , title: title
            , moveOut: true
            , skin: 'demo-class'
            , zIndex: layer1.zIndex //重点1
            , success: function(layero, index){
                let iframeWin = window1.frames[layero.find('iframe').attr('id')];
                iframeWin.closeLayerIndex = index;
                if (layero.find('.layui-layer-setwin').find('[id^=sc-window-tab]').length < 1) {
                    layero.find('.layui-layer-setwin').prepend('<a id="sc-window-tab'+ layer1.zIndex +'" href="#"><i class="layui-icon layui-icon-up"></i></a>');
                    layui.jquery(layero).on('click', '#sc-window-tab' + layer1.zIndex, function () {
                        let hf = /\?/.test(iframeWin.location.href) ? "&__sc_tab__=1" : "?__sc_tab__=1";
                        custom.openTabsPage(layero.find('iframe').attr('src') + hf + '#' + layero.attr('times'), layero.find('.layui-layer-title').text());
                        layer1.close(index);
                    });
                    layui.jquery(iframeWin.document).on('click', function (){
                        layero.css('z-Index', ++layer1.zIndex);
                    });
                }
                layer1.setTop(layero); //重点2
            }
        };

        if (window1.layui.jquery('.layui-layer-iframe').length > 0) {
            frame.offset = [parseInt(Math.random()*(21),10) + '%', parseInt(Math.random()*(21),10) + '%'];
        }

        if (param && typeof param == 'object') {
            for (let i in param) {
                frame[i] = param[i]
            }
        }

        return layer1.open(frame);
    }
    /**
     * 在父级打开弹窗
     * @param url
     * @param title
     * @param param
     * @returns {*}
     */
    , parentFrame(url, title, param) {
        return this.frame(url, title, param, parent);
    }
    /**
     * 在顶层打开弹窗
     * @param url
     * @param title
     * @param param
     * @returns {*}
     */
    , topFrame(url, title, param) {
        return this.frame(url, title, param, top);
    }
    /**
     * 打开新的标签页
     * @param url 地址
     * @param title 标题
     */
    , openTabsPage(url, title) {
        top.layui.index.openTabsPage(url, title);
    }
    /**
     * 关闭当前标签页
     */
    , closeTabsPage() {
        let url = window.location.href.replace(location.origin, '');
        top.layui.jquery('#LAY_app_tabsheader').find("li[lay-id='" + url + "']>i").click();
    },
    /**
     * 渲染百度编辑器
     * @param UE
     * @param id 元素ID
     * @param config 自定义配置
     * @returns {*}
     */
    editorRender(UE, id, config) {
        let UEditorConfig = {
            toolbars: [
                ['fullscreen', 'source', 'undo', 'redo', 'bold', 'indent', 'italic', 'underline', 'strikethrough', 'fontborder', 'horizontal', 'justifyleft', 'justifyright', 'justifycenter',
                    'justifyjustify', 'forecolor', 'backcolor', 'lineheight', 'touppercase', 'tolowercase', '|', 'removeformat', 'formatmatch', '|',
                    'inserttable', 'mergeright', 'mergedown', 'deletetable', 'insertrow', 'insertcol'],
                ['date', 'time', 'fontfamily', 'fontsize', 'paragraph', 'simpleupload', 'insertimage', 'link', 'background', 'spechars', 'imagenone', 'imageleft', 'imageright', 'imagecenter',]
            ]
            , initialFrameWidth: '100%'
            , initialFrameHeight: 300
            , zIndex: 100
        };

        let c_config = config ? setUEditorConfig(config) : UEditorConfig;
        let sc_ue = UE.getEditor(id, c_config);
        if (EDITOR_UPLOAD && !this.lock_editor_custom_url) {
            editorRedirect(EDITOR_UPLOAD);
            this.lock_editor_custom_url = true;
        }

        /**
         * 重定向百度编辑器上传地址
         * @param url
         */
        function editorRedirect(url) {
            UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
            UE.Editor.prototype.getActionUrl = function (action) {
                if (action == 'uploadimage' || action == 'uploadfile' || action == 'uploadvideo') {
                    return url;  //此处改需要把图片上传到哪个Action（Controller）中
                } else {
                    return this._bkGetActionUrl.call(this, action);
                }
            };
        }

        /**
         * 设置百度编辑器的自定义配置
         * @param config
         * @returns {*}
         */
        function setUEditorConfig(config) {
            let c = UEditorConfig;
            for (let i in c) {
                if (!config.hasOwnProperty(i)) {
                    config[i] = c[i];
                }
            }
            return config;
        }

        return sc_ue;
    }
    /**
     * 批量上传文件，配合layui批量上传使用
     * @param {jQuery} $
     * @param {string} name name值，事件元素对象的id值为name，展示图片的id值为name 加上 -show
     * @param {Object} upload   layui上传对象
     * @returns {{init: init, push: push}}
     */
    , moreUpload: function ($, name, upload) {
        let show_id     = '#' + name.replace(/\[/, '-').replace(/\]/, '') + '-show';
        let event_id    = '#' + name.replace(/\[/, '-').replace(/\]/, '');
        let className   = event_id.substr(1);
        let upload_file = {};
        let url_prefix  = ROOT;
        let current_v   = 0;

        /**
         * 初始化内容
         * @param {Array}  file_url 文件路径
         * @param {string} prefix
         */
        function init(file_url, prefix) {
            let file_arr = file_url ? file_url.filter((v) => Boolean(v)) : [];
            for (let i = 0; i < file_arr.length; i++) {
                push(file_arr[i]);
            }
            url_prefix = prefix ? prefix : url_prefix;
        }

        /**
         * 设置表单值
         */
        function setVal() {
            let v = [];
            $(show_id).find('.sc-item').each(function (i, e) {
                v.push(upload_file[e.getAttribute('data-current')]);
            });
            $('input[name="' + name + '"]').val(v.join(','));
        }

        /**
         * 渲染页面html
         * @param {string|void} push_url 追加的新的图片地址
         */
        function render(push_url) {
            let url = /^http.*$/.test(push_url) ? push_url : url_prefix + '/' + push_url;
            $(show_id).append(make_html(url, current_v));
            setVal();

            function make_html(url, current) {
                return `<div class="sc-item" data-current="${current}" draggable="true" style="width: 125px;border-radius: 5px;overflow: hidden;border: 1px solid grey;padding: 5px;margin-right: 10px;display: inline-block">` +
                `      <img src="${/^http.*$/.test(push_url) ? url : custom.thumbnailUrl(url)}"  draggable="false"  alt="" layer-src="${url}" onerror="${url}" width="100%" class="ad layui-upload-img">` +
                `      <div style="margin-top: 2px">` +
                `          <button type="button" class="sc-del${className} layui-btn layui-btn-fluid layui-btn-danger layui-btn-sm">` +
                `              <i class="layui-icon layui-icon-delete"></i>` +
                `          </button>` +
                `      </div>` +
                `   </div>`;
            }
        }

        /**
         * 追加路径
         * @param {string} url
         */
        function push(url) {
            upload_file[++current_v] = url;
            render(url);
        }

        /**
         * 拖放事件处理
         * @param {Object} elem
         */
        function drag(elem) {
            let tmp_over;
            elem.on('dragend', function (e) {
                if (!$(e.target).hasClass('sc-item')) return;

                if (tmp_over.hasClass('sc-item')) {
                    $(show_id).find(tmp_over).index() > $(show_id).find(e.target).index()
                        ? tmp_over.after(e.target)
                        : tmp_over.before(e.target);
                }else{
                    elem.append(e.target);
                }

                setVal();
            }).on('dragover', function (e) {
                if ($(e.target).hasClass('sc-item')) {
                    tmp_over = $(e.target);
                } else if($(e.target).parents('.sc-item')){
                    tmp_over = $(e.target).parents('.sc-item');
                }
            });
        }

        /**
         * 图片放大
         * @param url
         */
        function fd(url) {
            layer.photos({photos:{"data": [{"src": url}]}})
        }

        /**
         * 删除处理
         */
        function del() {
            $(document).off('click', 'button.sc-del' + className).on('click', 'button.sc-del' + className, function () {
                let current = $(this).parents('.sc-item').data('current');
                delete upload_file[current];
                $(this).parents('.sc-item').remove();
                setVal();
            }).off('click', '.ad').on('click', '.ad', function () {
                let url = $(this).attr('layer-src');
                fd(url);
            });
        }

        drag($(show_id));
        del();

        $('#' + name + '-select').on('click', function () {
            custom.frame(RESOURCE_URL + '?type=checkbox&vars=' + name, '资源选择');
        });
        let load;
        upload.render({
            elem: event_id
            , url: UPLOAD_URL
            , multiple: true
            , field: 'limit_images'
            , before: function (obj) {
                load = custom.loading('图片上传中...');
            }
            , done: function (res) {
                layer.close(load);
                if (res.code === 202) {
                    return layNotice.warning(res.msg);
                } else {
                    push(res.data);
                }
            },error(){
                layer.close(load);
            }
        });

        let exports = {init(file_url, prefix){
                init(file_url, prefix);
                return exports;
            },push};

        return exports;
    }

    /**
     * layui 图片弹出层加放大功能
     * @param layer
     * @param $
     * @param class_name
     */
    , enlarge: (layer, $, class_name) => {
        layer.photos({photos: class_name});
        $(document).on("mousewheel DOMMouseScroll", ".layui-layer-phimg img", function (e) {
            let delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) || // chrome & ie
                (e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1)); // firefox
            let imagep = $(".layui-layer-phimg").parent().parent();
            let image = $(".layui-layer-phimg").parent();
            let h = image.height();
            let w = image.width();
            if (delta > 0) {
                h = h * 1.05;
                w = w * 1.05;
            } else if (delta < 0) {
                if (h > 100) {
                    h = h * 0.95;
                    w = w * 0.95;
                }
            }
            imagep.css("top", (window.innerHeight - h) / 2);
            imagep.css("left", (window.innerWidth - w) / 2);
            image.height(h);
            image.width(w);
            imagep.height(h);
            imagep.width(w);
        });
    },
    /**
     * 网络图片展示处理
     * @param e
     * @param src
     */
    imageError: function (e, src) {
        if (/^http.*$/.test(src)) e.src = src
    },

    /**
     * 缩略图处理
     * @param {string} path 图片路径
     * @returns {string|*}
     */
    thumbnailUrl(path) {
        if (!Thumbnail || path.length > 255 || /^http.*$/.test(path)) return path;

        let arr = path.split('.');
        let suffix = arr.pop();
        arr.join('.')
        return arr.join('.') + '_thumbnail.' + suffix;
    },
    /**
     * 表格图片展示
     * @param url 路径
     * @param alt 加载失败提示
     * @returns {string}
     */
    tableImageShow: function (url, alt) {
        let show_url, layer_url;
        url += '';
        if (/^http.*$/.test(url)) {
            show_url = url;
            layer_url = url;
        } else {
            let info = url.split('.');
            layer_url = ROOT + '/' + url;
            show_url = Thumbnail ? ROOT + '/' + info[0] + '_thumbnail.' + info[1] : layer_url;
        }

        return url ? '<div class="layer-photos-demo" style="width: 100%;">' +
            '  <img layer-pid="" style="width: 100%;" layer-src="' + layer_url + '"  src="' + show_url + '" alt="' + (alt ? alt : url) + '"/>' +
            '</div>' : '——';
    },
    /**
     * 图片（单）上传
     * @param $
     * @param upload
     * @param name
     * @returns {{defaults: defaults, name}}
     */
    upload: function ($, upload, name) {
        let load;
        let up = upload.render({
            elem: "#" + name
            , url: UPLOAD_URL
            , field: 'limit_image'
            , before: function (obj) {
                load = custom.loading('图片上传中...')
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#' + name + '_show').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                layer.close(load)
                //如果上传失败
                if (res.code === 202) {
                    return layNotice.warning(res.msg);
                }
                //上传成功
                $('input[name=' + name + ']').val(res.data);
            }
            , error: function () {
                layer.close(load)
                //演示失败状态，并实现重传
                let demoText = $('#' + name + '_tip');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    up.upload();
                });
            }
        });

        $('#' + name + '-select').on('click', function () {
            custom.frame(RESOURCE_URL + '?type=radio&vars=' + name, '资源选择');
        });

        let exports = {
            name: name,
            defaults: (defaults) => {
                $('#' + name + '_show').attr('src', /^http.*$/.test(defaults) ? defaults : ROOT + '/' + defaults);
                $('input[name=' + name + ']').val(defaults);
                return exports;
            }
        };
        return exports;
    },
    /**
     * 文件上传
     * @param $
     * @param upload
     * @param name
     * @param type
     * @returns {{defaults: defaults, name: *}}
     */
    fileUpload($, upload, name, type) {
        let load;
        let FileData = [];
        let accept = {image: 1, audio: 1, video: 1}
        upload.render({
            elem: "#" + name
            , url: UPLOAD_FILE_URL
            , field: "limit_" + type
            , multiple: true
            , accept: accept.hasOwnProperty(type) ? type : 'file'
            , before: function (obj) {
                load = custom.loading('文件上传中, 请稍候...');
            }
            , done: function (res) {
                layer.close(load)
                //如果上传失败
                if (res.code === 202) {
                    return layNotice.warning(res.msg);
                }
                //上传成功
                $('input[name=' + name + ']').val(successValue(res.data.id));
                html(res.data);
            },
            error() {
                layer.close(load)
            }
        });

        /**
         * 成功后的值拼装
         * @param data
         * @returns {*}
         */
        function successValue(data) {
            FileData.push(data);
            return FileData;
        }

        /**
         * html 回显
         * @param data
         * @returns {string}
         */
        function html(data) {
            let htmlString = '' +
                '<tr>\n' +
                '   <td>' + data.tag + '</td>\n' +
                '   <td width="20"><div class="' + name + '-xc-del  layui-btn layui-btn-xs layui-btn-danger"><i class="layui-icon layui-icon-delete "></i></div></td>\n' +
                '</tr>';
            $('table.' + name + '-table-xc>tbody').append(htmlString);
        }

        // 删除指定文件
        $(document).on('click', 'td>div.' + name + '-xc-del', function () {
            let index = $('td>div.' + name + '-xc-del').index(this);
            $(this).parents('tr').remove();
            FileData.splice(index, 1);
            $('input[name=' + name + ']').val(FileData);
        });

        return {
            name: name,
            defaults: (defaults) => {
                for (let defaultsKey in defaults) {
                    if (defaults.hasOwnProperty(defaultsKey)) {
                        html(defaults[defaultsKey]);
                        successValue(defaults[defaultsKey].id);
                    }
                }
                $('input[name=' + name + ']').val(FileData);
            }
        };
    },
    /**
     * 视频上传
     * @param name
     * @returns {{defaults: defaults, name}}
     */
    videoUpload(name){
        let load;
        upload.render({
            elem: "#" + name
            , url: UPLOAD_FILE_URL
            , field: 'limit_video'
            , accept: 'video'
            , before: function (obj) {
                load = custom.loading('视频上传中, 请稍候...');
            }
            , done: function (res) {
                layer.close(load)
                //如果上传失败
                if (res.code === 202) {
                    return notice.warning(res.msg);
                }
                //上传成功
                $('input[name=' + name + ']').val(res.data);
                $('#' + name + '_show').attr('src', /^http.*$/.test(res.data) ? res.data : ROOT + '/' + res.data);
            },
            error() {
                layer.close(load)
            }
        });

        return  {
            name: name,
            defaults: (defaults) => {
                $('#' + name + '_show').attr('src', /^http.*$/.test(defaults) ? defaults : ROOT + '/' + defaults);
                $('input[name=' + name + ']').val(defaults);
            }
        };
    },
    /**
     * 数子集判断
     * @param {Array} array         子集数组
     * @param {Array} arrayStack    要查询的数组
     */
    arraySub(array, arrayStack){
        if (!(array instanceof Array) || !(arrayStack instanceof Array) || ((arrayStack.length < array.length))) {
            return false;
        }
        let len = array.length;
        for (let i = 0 ;i < len;i++) {
            if(arrayStack.indexOf(array[i]) < 0) return false;
        }
        return true;
    },
    /**
     * 数组交集
     * @param {Array} array1
     * @param {Array} array2
     */
    arrayBeMixed(array1, array2){
        if (!(array1 instanceof Array) || !(array2 instanceof Array)) {
            return false;
        }
        let len = array1.length;
        for (let i = 0 ;i < len;i++) {
            if(array2.indexOf(array1[i]) >= 0) return true;
        }
        return false;
    }
};


var notice = {
    /**
     * 成功
     * @param tip
     * @param call
     */
    success: function (tip, call) {
        layNotice.success(tip);
        if (typeof call === 'function') {
            setTimeout(call, 2000);
        }
    },
    /**
     * 警告
     * @param tip
     * @param call
     */
    warning: (tip, call) => {
        layNotice.warning(tip);
        if (typeof call === 'function') {
            setTimeout(call, 2000);
        }
    },
    /**
     * 错误
     * @param tip
     * @param call
     */
    error: (tip, call) => {
        layNotice.error(tip);
        if (typeof call === 'function') {
            setTimeout(call, 2000);
        }
    }
};


/**
 * 异步请求
 * @type {{confirm(*, *=): ScXHR, ajax(*=): void}}
 */
let ScXHR = (() => {

    let tip, config = confirm_tip, load;

    return {
        /**
         * 设置提示框
         * @param confirm
         * @param conf
         * @returns {ScXHR}
         */
        confirm(confirm, conf) {
            tip = confirm;
            if (conf && typeof conf === "object") {
                config = conf;
            }
            return this;
        },
        /**
         * 异步请求
         * @param param
         */
        ajax(param) {
            let success = typeof param.success === 'function' ? param.success : ()=>{};
            param.success = function (res) {
                layer.close(load);
                success(res);
            };
            if (tip) {
                layer.confirm(tip, config, function (index) {
                    load = custom.loading();
                    layui.jquery.ajax(param);
                    layer.close(index);
                });
            } else {
                load = custom.loading();
                layui.jquery.ajax(param);
            }
        }
    }
})();


/**
 * 发起请求，针对api的token
 * @param url 请求路径
 * @param method 请求方式， 默认get
 * @example
 *    scXhr('/xx/home', 'get').request({id:1,test:4}).response(function (res) {
 *       console.log(111,'asdasd', res);
 *    });
 * @returns {{request: (function(*, *): {response: response}), response: response}}
 */
/*function scXhr(url, method) {
    function send(param) {
        // TODO 发起请求, 以下为ajax示例
        $.ajax(param);
    }

    function loginOut() {
        // TODO refresh_token过期，退出登录，需重新登录
        console.log('重新登录');
    }

    function cacheToken(token) {
        // TODO 缓存token, 以下为浏览器的
        localStorage.setItem('Token', token);
    }

    function setHeader() {
        // TODO 设置请求头，以下为jquery的
        requestConfig.beforeSend = function (request) {
            for (const key in header_) {
                request.setRequestHeader(key, header_[key]);
            }
        }
    }

    /!**
     * 获取token
     * @param {boolean} is_refresh 是否是获取refresh_token
     * @returns {string}
     *!/
    function getToken(is_refresh) {
        // 以下为浏览器的缓存获取
        // return localStorage.getItem(is_refresh ? 'refresh_token' : 'token');
        // 以下为死数据测试
        return is_refresh
            ? 'eyJleHAiOjE2MDYyMTk2ODMsImp0aSI6Ijg3ZTkyZjk1MjViYThiMGY4NDM2NzQ5NjBiMDMxZjA0In0.ZTExNGIyYjUzMTcwZjM2NzBhODBmOGZlZmQ0OTYxYjQyNTMwZmVlYzA2ZDMyMDkyNzY2N2QyM2I5YzU3NjM3YQ'
            : 'eyJhbGciOiJzaGEyNTYiLCJ0eXAiOiJKV1QifQ.eyJpYXQiOjE2MDUzNTU2ODMsImV4cCI6MTYwNTM1NTc0MywiaXNzIjoiU0RfQ0wiLCJqdGkiOiJqdGk1ZmFmYzhhM2I3YTc0MzkiLCJyc2giOiI4N2U5MmY5NTI1YmE4YjBmODQzNjc0OTYwYjAzMWYwNCIsImlkIjoxfQ.YTc4ZTZlYmVlMjE5OGRiMTA1NjQ3MzI5ZTNkMjJmNWFmZWNhYThkNTBlYTE4MTYxMzE2NWI5MGVhMmY0ZDY0MQ';
    }

    // ===========初始化请求参数 start============
    let header_ = {
            "Token": getToken()
        },
        requestConfig = {
            url: url,
            method: method ? method : 'get',
        };

    // ===========初始化请求参数 end============

    /!**
     * 发起请求，并传入响应后的处理函数
     * @param success   成功后的回调函数
     * @param error     失败后的回调函数
     * @param complete  完成请求后的回调函数
     *!/
    function response(success, error, complete) {
        requestConfig.error = typeof error === 'function' ? error : () => false;
        requestConfig.complete = typeof complete === 'function' ? complete : () => false;
        requestConfig.success = (res) => {
            if (res.code === 203) {
                let rs = {};
                merge(rs, requestConfig);
                merge(header_, {"Refresh-Token": getToken(true)});
                rs.success = (res) => {
                    if (res.code === 205) {
                        loginOut()
                    } else {
                        merge(header_, {"Token": res.data.token});
                        delete header_["Refresh-Token"];
                        cacheToken(res.data.token);
                        response(success, error, complete);
                    }
                };
                execute(rs);
            } else {
                typeof success === 'function' ? success(res) : () => false;
            }
        }

        execute(requestConfig);
    }

    /!**
     * 设置请求参数及请求头
     * @param data
     * @param header
     * @returns {{response: response}}
     *!/
    function request(data, header) {
        requestConfig.data = data;
        header && typeof header === 'object' && merge(header_, header);

        return {response};
    }

    /!**
     * 执行请求
     * @param {object} param 请求参数
     *!/
    function execute(param) {
        setHeader();
        send(param);
    }

    /!**
     * 合并obj2到obj1
     * @param {object} obj1
     * @param {object} obj2
     *!/
    function merge(obj1, obj2) {
        for (const k in obj2) {
            obj1[k] = obj2[k];
        }
    }

    return {
        response,
        request
    }
}*/


