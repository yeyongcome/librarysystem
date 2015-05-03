//双击选项添加到右边
$(".options_list").dblclick(function(){
      add_right();
});
$(".option-select-list").dblclick(function(){
      del_right();
});

$("#select_role option").each(function(){
    if($(this).val() == $.trim($("#role_type").val())){
        $(this).attr("selected","selected");
    }
});

//左边选中部分添加到右边
function add_right(){
    //检查是否已经选择角色
    if($('#select_role').val() == '0'){  
        $('#select_role').css('border','2px solid red');
        setTimeout("$('#select_role').css('border','1px solid #ABADB3')",1300);
        web_notice('请选择角色',1);
        return false;
    }
    var option_selected = $('#all_user_list option:selected');
    //检查是否已经选择添加的人员
    if(option_selected.length == 0){
        web_notice('请选择要添加的人员！',1);
        return false;
    }
    option_selected.each(function(){
        var all_val = $(this).val();
        //检查要添加的人员是否已经存在
        $("#now_user_list option").each(function(){
            if($(this).val() == all_val){
                //存在则删除
                $(this).remove();
            }
        });
    });
    option_selected.appendTo('#now_user_list');  
}

//删除右边选中的元素
function del_right(){
    //检查是否已经选择人员
    var option_del = $('#now_user_list option:selected');
    if(option_del.length == 0){
        web_notice('请选择要删除的人员',1);
        return false;
    }
    //检查左边是否存在已选人员
    option_del.each(function(){
        var all_val = $(this).val();
        $("#all_user_list option").each(function(){
            //存在已选的人员则删除
            if($(this).val() == all_val){
                $(this).remove();
            }
        });
    });
    option_del.appendTo('#all_user_list');
}

//确定修改
function save_role(){
    var obj = document.getElementById('now_user_list');
    var length = obj.options.length;
    var all_value = '';
    for(var i = 0;i < length;i++){
        if(i == 0){
            all_value = obj.options[i].value;
        }else{
            all_value += '+'+obj.options[i].value;
        }
    }
    var role_type = $("#role_type").val();
    if(role_type == ''){
        return false;
    }
    $.ajax({
        url:$("#model_url").val()+'/set_user',
        type:"POST",
        dataType:"TEXT",
        data:{
            all_value:all_value,
            role_type:role_type
        },
        success:function(data){
            if(data > 0){
               web_notice('修改成功',0);
               setTimeout("window.location.href='"+$("#model_url").val()+"/link_user?role_id="+role_type+"'",1300);
            }else{
               web_notice('修改失败',1);
            }
        }
    });
}