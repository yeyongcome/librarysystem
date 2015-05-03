/**
 * 检查窗口大小，改变导航的长度，适应窗口变化
 * 原则：将最早进入的并且未使用的导航删除(隐藏)
 */
function set_tpline_visible(){
    //得到多余导航栏的个数
    var more_visible_length = $(".top-line").length - Math.floor((parseInt($("#right").css("width")) -110)/105);
    if(more_visible_length >= 0){
        //大于0证明已经多余
        set_tpline(more_visible_length);
    }else{
        //如果小于 0 则可以全部显示
        $(".top-line").css("display","block");
        var selected_menu = $.trim($("#selected_menu").val());
        change_topline_color(selected_menu);
    }   
    //重新设置导航栏布局
    set_topline_left();
}

/**
 * 改变选中导航选项卡的css样式。
 * @selected_menu：选中导航选项卡的url
 */
function change_topline_color(selected_menu){
    //添加选中的导航栏的class
    $(".top-line").each(function(){
        if($(this).attr('value') == selected_menu){
            $(this).addClass('selected');
            $(this).siblings().removeClass('selected');
            return false;
        }
    });
}

/**
 * 控制提示窗口的显示
 */
function web_notice(content,status){
    $("#web-notice").css('display','block');
    $(".notice-content").html(content);
    if(status == 1){
        $("#web-notice").css('background-color','#FF6666');
    }else{
        $("#web-notice").css('background-color','#86DB00');
    }
    setTimeout("$('#web-notice').css('display','none')",1300);
}

$(".notice-delete").click(function(){
    $(this).parent().css('display','none');
});
/**
 * 动态检查导航，隐藏多余的导航栏。
 * @more_visible_length：多余导航栏的个数
 */
function set_tpline(more_visible_length){
    var is_status = 0;
    $(".top-line").css("display","block");
    //得到选中的导航栏
    var selected_menu = $.trim($("#selected_menu").val());
    $(".top-line").each(function(){  
        //添加选中的导航栏的class
        if($(this).attr('value') == selected_menu){
            $(this).addClass('selected');
            $(this).siblings().removeClass('selected');
        }
        //判断隐藏导航栏的数目
        if(is_status < more_visible_length){
            if(!$(this).hasClass("selected")){
                //隐藏符合条件的导航栏
                $(this).css("display","none");
                is_status++;
            }
        } 
    });
}

/**
 * 设置导航栏布局
 * 有is_del_line决定的导航布局来定left的大小
 */
function set_topline_left(){
    var lefts = 1;
    //判断页面布局，来确定导航栏的位置
    if($("#is_del_line").val() == 1){
        var longs = 120;
    }else{
        var longs = -35;
    }
    //重新排列没有隐藏的导航栏
    $(".top-line:visible").each(function(){
        $(this).css("left",longs + lefts * 108 + "px");
        lefts++;
    });
}

/**
 * 显示左边大导航，使二级导航跟随选项块
 * @index：导航编号，index > 2时二级导航翻转显示
 */
function showtips(index){
    var top_long = 0;
    for(var i = 0;i <= index;i++){
        var obj = $('.left-line:eq('+i+')');
        var j = i;
        if(j == index){
            top_long += parseInt(obj.css("margin-top")) + 28;
        }else{
            top_long += parseInt(obj.css("margin-top")) + 70;
        }  
    }
    
    $("#tpiss").css('display','block');

    if(index > 3){
        top_long -= 206;
        top_long = top_long+'px';
        $("#tpiss").css("top",top_long);
        $(".lefttips:eq(1)").css("display",'block');
        $(".lefttips:eq(0)").css("display",'none');
    }else{
        top_long = top_long+'px';
        $("#tpiss").css("top",top_long);
        $(".lefttips:eq(1)").css("display",'none');
        $(".lefttips:eq(0)").css("display",'block');
    }
    $(".tips-line-content").html($("#ids"+$('.line-img :eq('+index+')').attr("myid")).html());
    /*$("#tpiss").animate({top:top_long},150,callback=function(){
        $(".tips-line-content").html($("#ids"+$('.line-img :eq('+index+')').attr("myid")).html());
    }); */
    var children_color = $("#children_color").val();
    setlincolor(children_color);
}

//显示退出按钮
$("#right-right").mouseover(function(){
    $("#loginout").css("display","block");
    $("#loginout").css("margin-top","13px");
    $("#top-user_photo").css("margin-left","10px");
});

//隐藏退出按钮
$("#right-right").mouseout(function(){
    $("#loginout").css("display","none");
    $("#top-user_photo").css("margin-left","45px");
});

/**
 * 改变左导航颜色
 * 此方法一般需要在{隐藏滑动块模式}下启用
 * num是全局变量第几个导航块
 */
function change_leftline_color(num){
    if($("#is_del_line").val() == 1){
        $(".left-line :eq("+num+")").css("background-color","#2E5C2E");
    }else{
        $(".left-line :eq("+num+")").css("background-color","#484848");
    }
    $(".left-line :eq("+num+")").siblings().css("background-color","#2F2F2F");
}

/**
 * 完成关闭滑动导航的页面布局操作
 */
function del_line_block(){
    $("#tpiss").css('display','none'); //关闭导航块
    $("#foots-div").css("margin-left","55px");
    $("#del-line").parent().css("display","none");
    $("#ret-line").css("display","block");
    $("#right").css("margin-left",'65px');
    $("#right").css("width",$(window).width()-75);
}

/**
 * 添加顶部导航块的选中颜色
 * children_color是选中导航的的url
 */
function setlincolor(children_color){
    $("#tpiss").find(".divline").each(function(){
        if($(this).attr("name") == children_color){    
            $(this).css("background-color","#F47C20");
            return false;
        }
    });
}

/** 
 * 在{隐藏滑动块模式}下关闭导航块
 * 鼠标在right，right-line-top，移动时关闭滑动块。
 */
$("#right,#right-line-top").mousemove(function(){
    if($("#is_del_line").val() == 2){
        change_leftline_color(num); //改变左导航颜色
        $("#tpiss").css('display','none'); //关闭滑动块 
    }
});

/**
 * 改变session，来记录改变后的状态：1，2
 * status是状态值，隐藏滑动块(2),不隐藏滑动块(1);
 */
function change_session(status){
    /*if($("#is_del_line").val() == 2){
        var str = '';
        if($(".top-line").length == 10){
            if($(".top-line :eq(0)").hasClass('selected')){
                var str = $(".top-line :eq(1)").attr('value');
                $(".top-line :eq(1)").remove();
            }else{
                var str = $(".top-line :eq(0)").attr('value');
                $(".top-line :eq(0)").remove();
            }
        //11的情况
        }else if($(".top-line").length == 11){
            if($(".top-line :eq(0)").hasClass('selected')){
                var str = $(".top-line :eq(1)").attr('value') + '[+]' + $(".top-line :eq(2)").attr('value');
                $(".top-line :eq(1)").remove();
                $(".top-line :eq(2)").remove();
            }else{
                var str = $(".top-line :eq(0)").attr('value');
                if($(".top-line :eq(1)").hasClass('selected')){
                    str += '[+]' + $(".top-line :eq(2)").attr('value');
                    $(".top-line :eq(2)").remove();
                }else{
                    str += '[+]' + $(".top-line :eq(1)").attr('value');
                    $(".top-line :eq(1)").remove();
                }  
                $(".top-line :eq(0)").remove();
            }
        }
    }*/
    $.ajax({
        url:$("#root_url").val()+"/index.php/Public/change_session",
        type:'post',
        dataType:'text',
        data:({
            status:status
        }),
        success:function(){}
    });
}

/**
 * 顶部导航块的选中颜色（暂时不用）
 */
/*function set_top(){
    var lefts = 1;
    if($("#is_del_line").val() == 1){
        var longs = 120;
    }else{
        var longs = -35;
    }
    var selected_menu = $.trim($("#selected_menu").val());
    $(".top-line").each(function(){
        $(this).css("left",longs + lefts * 108 + "px");
        lefts++;
        if($(this).attr('value') == selected_menu){
            $(this).addClass('selected');
            $(this).siblings().removeClass('selected');
        }
    });
}*/

/**
 * 删除选中的导航块
 * @selete_url：要删除导航的url
 * @num       ：删除后网页要跳转的页面 
 * @status    ：删除的是否是选中的页面
 */
function delete_selete_url(selete_url,num,status){
    $.ajax({
        url:$("#root_url").val()+"/index.php/Public/delete_selete_url",
        type:'post',
        dataType:'text',
        data:({
            selete_url:selete_url
        }),
        success:function(){ 
            if(status == 1){
                if(num == -1){
                    window.location.href= $("#root_url").val()+"/index.php/Index/index";
                }else{
                    $(".top-line-text:eq("+num+")").click();
                } 
            }
        }
    });
}

/**
 * setcolor：onmouseover触发导航块颜色变化
 * delcolor：onmouseout触发导航块颜色变化
 */
function setcolor(obj){
    obj.css("background-color","#4CDB00");
}
function delcolor(obj){
    obj.css("background-color","#4BBD00");
    var children_color = $("#children_color").val();
    setlincolor(children_color);
}

/**
 * 顶部导航块跳转页面
 */
$(".top-line-text").click(function(){
    window.location.href = $("#root_url").val()+"/index.php/"+$(this).parent().attr('value');
});

/**
 * 顶部导航块的点击事件
 * 有点击位置决定关闭导航后显示哪一个选项卡
 */
$(".top-line-delete").click(function(){
    var obj = $(this).parent();
    var select_url = obj.attr('value');
    if(obj.hasClass('selected')){
        var before = obj.prevUntil().length;
        if(before > 0){
            delete_selete_url(obj.attr('value'),before-1,1);
            return false;
        }
        var after = obj.nextUntil().length;
        if(after > 0){
            delete_selete_url(obj.attr('value'),1,1);
            return false;
        }
        delete_selete_url(obj.attr('value'),-1,1);

    }else{
        delete_selete_url(obj.attr('value'),0,0);
        obj.remove();
        set_tpline_visible();//检查导航,重新设置
    }
}); 