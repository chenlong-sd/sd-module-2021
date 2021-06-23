{extend name="frame"}
{block name="meta"}{:token_meta()}{/block}
{block name="head"}
<style>
    .cross {
        background: #ddd;
        height: 42px;
        position: relative;
        width: 2px;
        left: 20px;
    }

    .cross:after {
        background: #ddd;
        content: "";
        height: 2px;
        left: -20px;
        position: absolute;
        top: 20px;
        width: 42px;
    }
    .cross_left:after{width: 22px;}
    .cross_right:after{width: 22px; left: 0;}
    .cross_up{height: 22px;}
    .cross_down{height: 22px;top:20px}
    .cross_down:after{top:0}
    .cross_down:before{top:-19px!important;}
    .cross_tag:before{
        background: #ddd;
        content: "";
        height: 10px;
        left: -4px;
        position: absolute;
        top: 16px;
        width: 10px;
    }
    .cross_select_black:before{
        background: #fff;
        content: "";
        height: 40px;
        left: -20px;
        position: absolute;
        z-index: 100;
        top: 1px;
        width: 40px;
        border-radius: 50%;
        box-shadow: inset 15px 15px 20px #000;
    }
    .cross_select_white:before{
        background: #fff;
        content: "";
        height: 40px;
        left: -20px;
        position: absolute;
        z-index: 100;
        top: 1px;
        width: 40px;
        border-radius: 50%;
        box-shadow: inset 5px 5px 20px #ddd;
    }
    .cross_box{
        display: inline-block;
        width: 42px;
        height: 42px;
        float: left;
    }
    .cross_box_s{
        overflow: hidden;
    }
    .win{
        position: fixed;
        top: 30%;
        left: 50%;
        width: 200px;
        color: white;
        font-family: cursive;
        font-size: 45px;
        text-align: center;
        margin-left: -100px;
        z-index: 999;
        box-shadow: 0 0 20px red;
        background-color: rgba(0,0,0,.6);
        border-radius: 5px;
        display: none;
        user-select: none;
    }
</style>
{/block}
{block name="body"}

<div style="padding: 15px;background-color: rgba(253,152,1,0.53);overflow:hidden;">

    <div class="cross_box_s"></div>
    <div class="win">黑棋胜</div>
</div>

{/block}

{block name="js"}

<script>
    var $ = layui.jquery,
        white_x = {}, // 以 X 轴为键存坐标信息
        white_y = {}, // 以 Y 轴为键存坐标信息
        black_x = {}, // 以 X 轴为键存坐标信息
        black_y = {}, // 以 Y 轴为键存坐标信息
        row = 19,
        line = 19,
        is_end = false;


    box_init(row, line);


    $(document).on('click', '.cross_box', function () {
        if ($(this).find('.cross').hasClass('occupy') || is_end) return false;
        let white_count = $('.cross_select_white').length;
        let black_count = $('.cross_select_black').length;
        let is_white = (white_count + black_count) % 2 === 1;
        $(this).find('.cross').addClass(is_white ? 'cross_select_white' : 'cross_select_black').addClass('occupy');

        // 记录坐标信息
        let x = $(this).data('x');
        let y = $(this).data('y');

        if (is_white) {
            white_x[y] ? white_x[y].push(x) : white_x[y] = [x];
            white_y[x] ? white_y[x].push(y) : white_y[x] = [y];
            white_x[y].sort((a, b) => a - b);
            white_y[x].sort((a, b) => a - b);
            winCheckRowAndLine(white_x);
            winCheckRowAndLine(white_y);
            winCheckFork(white_x);
            if (is_end) {
                $('.win').text('白棋胜').show();
            }
        }else{
            black_x[y] ? black_x[y].push(x) : black_x[y] = [x];
            black_y[x] ? black_y[x].push(y) : black_y[x] = [y];
            black_x[y].sort((a, b) => a - b);
            black_y[x].sort((a, b) => a - b);
            winCheckRowAndLine(black_x);
            winCheckRowAndLine(black_y);
            winCheckFork(black_x);
            if (is_end) {
                $('.win').show();
            }
        }
    });

    //横竖检查是否赢
    function winCheckRowAndLine(obj) {
        let li = 1;
        for (let objKey in obj) {
            let obj2 = obj[objKey];
            for (let k2 in obj2) {
                if (!obj2.hasOwnProperty(k2 * 1 - 1) || obj2[k2] - 1 !== obj2[k2 * 1 - 1]) {
                    li = 1;
                    continue;
                }
                li++;
                if (li >= 5) {
                   return is_end = true;
                }
            }
        }
    }

    // 分叉判断赢
    function winCheckFork(obj) {
        for (let objKey in obj) {
            if (!obj.hasOwnProperty(objKey * 1 + 1)) continue;
            let obj2 = obj[objKey];
            for (let k2 in obj2) {
                if (thorough(obj, objKey, 1, obj2[k2], 1) >= 5 || thorough(obj, objKey, 1, obj2[k2], -1) >= 5){
                    return is_end = true;
                }
            }
        }

        /**
         * 分叉深入判断
         * @param obj 行数据
         * @param col 当前行
         * @param number 连续数量
         * @param value 当前值
         * @param fx 方向 -1 1
         */
        function thorough(obj, col, number, value, fx = 1) {
            col   = col * 1 + 1;
            value = value * 1 + fx;
            if (!obj.hasOwnProperty(col) || number >= 5){
                return number;
            }
            if (obj[col].indexOf(value) >= 0){
                number++;
            }
            return thorough(obj, col, number, value, fx);
        }
    }



    /**
     * 棋面初始化
     * @param row  行
     * @param line 列
     */
    function box_init(row, line)
    {
        let num = row * line,
            box      = "<div data-x=':x' data-y=':y' class=\"cross_box\"><div class=\":class\"></div></div>",
            box_html = '';
        for (let x = 1; x <= row; x++){
            for (let y = 1; y <= line; y++) {
                let class_ = 'cross';
                if (y === 1) class_ += " cross_right";
                if (y === line) class_ += " cross_left";
                if (((x -1) * line + y) === Math.ceil(num / 2)) class_ += " cross_tag";
                if (x === row) class_ += " cross_up";
                if (x === 1) class_ += " cross_down";

                box_html += box.replace(':class', class_).replace(':x', x).replace(':y', y);
            }
        }
        $('.cross_box_s').html(box_html).css({
            width:row * 42 + 'px',
            height:line * 42 + 'px',
        });
    }
</script>



{/block}