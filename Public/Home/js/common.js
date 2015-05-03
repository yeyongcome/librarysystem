$(document).ready(function(){
	$(".checkbox-select").attr("checked",false);
	$("#all-checkbox").attr("checked",false);
	$(".text-input").focus(function(){
    	$(this).addClass("selfocus");
    	$("#tips-div").html('');
	});
	$(".text-input").blur(function(){
	    $(this).removeClass("selfocus");
	});
});

//得到市
function get_city(){
    var province_id = $("#add_province").val();
    $.ajax({
        url:$("#root_url").val()+'/index.php/Public/get_city',
        type:"POST",
        dataType:"TEXT",
        data:{
            province_id:province_id
        },
        success:function(data){
            $("#add_city").html(data);
        }
    });
}

//得到区
function get_area(city_id){
    var city_id = $("#add_city").val();
    $.ajax({
        url:$("#root_url").val()+'/index.php/Public/get_area',
        type:"POST",
        dataType:"TEXT",
        data:{
            city_id:city_id
        },
        success:function(data){
            $("#add_area").html(data);
        }
    });
}
//关闭弹出层
$(".bcanael").click(function(){
    parent.layer.close(parent.layer.getFrameIndex(window.name));
});

/*全选、不全选*/
$("#all-checkbox").click(function(){
	if($("#all-checkbox:checked").length > 0){
		$(".checkbox-select").attr("checked",true);
	}else{
		$(".checkbox-select").attr("checked",false);
	}
});

$(".checkbox-select").click(function(){
	var checked_num = $(".checkbox-select:checked").length;
	var all_num = $(".checkbox-select").length;
	if(checked_num == all_num){
		$("#all-checkbox").attr("checked",true);
	}else{
		$("#all-checkbox").attr("checked",false);
	}
});

/**
 * 异步分页调用方法
 * id:分页页数，PageNum：为存储分页临时变量
 */
function setPage(id){
    $("#PageNum").attr("value",id);
    search_data();
}

 //时间格式化
function gettime(search_time){
    var search_time = $.trim(search_time);
    if(search_time == '' || search_time == null){
        return '';
    }
    var new_str = search_time.replace(/:/g,'-');
    new_str = new_str.replace(/ /g,'-');
    var arr = new_str.split("-");
    if(new_str.length == 10){
        arr[3] = '00';
        arr[4] = '00';
        arr[5] = '00';
    }
    var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
    return datum.getTime();
}

