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
</style>
{/block}
{block name="body"}

<div style="padding: 15px;background-color: rgba(253,152,1,0.53);overflow:hidden;">

    <div class="cross_box_s"></div>

</div>

{/block}

{block name="js"}

<script>
    var $ = layui.jquery, white = [], black = [],record = [], white_count_record = {}, black_count_record = {};
    var row = 19, line = 19;
    box_init(row, line);

    $(document).on('click', '.cross_box', function () {
        let white_count = $('.cross_select_white').length;
        let black_count = $('.cross_select_black').length;
        let is_white = (white_count + black_count) % 2 === 1;
        $(this).find('.cross').addClass(is_white ? 'cross_select_white' : 'cross_select_black');
        let index = $(this).index() + 1
        let x = index % row;
        let y = Math.ceil(index / row);
        record.push({x:x,y:y});

        if (is_white) {
            white_count_record["x_" + x] = (white_count_record["x_" + x] ? white_count_record["x_" + x] : 0) + 1;
            white_count_record["y_" + y] = (white_count_record["y_" + y] ? white_count_record["y_" + y] : 0) + 1;
            if (white_count_record["x_" + x] >= 5 || white_count_record["y_" + y] >= 5) {
                layer.alert('赢了');
            }
        }else{
            black_count_record["x_" + x] = (black_count_record["x_" + x] ? black_count_record["x_" + x] : 0) + 1;
            black_count_record["y_" + y] = (black_count_record["y_" + y] ? black_count_record["y_" + y] : 0) + 1;
            if (black_count_record["x_" + x] >= 5 || black_count_record["y_" + y] >= 5) {
                layer.alert('赢了');
            }
        }


    })

    function box_init(row, line)
    {
        let num = row * line,
            box      = "<div class=\"cross_box\"><div class=\":class\"></div></div>",
            box_html = '';
        for (let i = 1; i <= num; i++){
            let class_ = 'cross';
            if (i <= row) class_ += " cross_down";
            if (i % line === 1) class_ += " cross_right";
            if (i % line === 0) class_ += " cross_left";
            if (i > line * (row - 1)) class_ += " cross_up";
            if (i === Math.ceil(num/2)) class_ += " cross_tag";

            box_html += box.replace(':class', class_);
        }
        $('.cross_box_s').html(box_html).css({
            width:row * 42 + 'px',
            height:line * 42 + 'px',
        });
    }
</script>



{/block}