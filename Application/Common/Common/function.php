<?php
//公用函数文件
function get_time_sql($start_time,$end_time){
    //开始时间不为空且结束时间为空
    if(!empty($start_time) && empty($end_time)){
        $where = " create_time > ".strtotime($start_time)." or create_time = ".strtotime($start_time);
        //开始时间为空且结束时间不为空
    }else if(empty($start_time) && !empty($end_time)){
        $where = " create_time < ".strtotime($end_time)." or create_time = ".strtotime($end_time);
        //开始时间和结束时间都不为空
    }else if(!empty($start_time) && !empty($end_time)){
        $where = " (create_time > ".strtotime($start_time)." or create_time = ".strtotime($start_time).") and (create_time < ".strtotime($end_time)." or create_time = ".strtotime($end_time).") ";
    }
    return $where;
}

/*
    得到访问ip
*/
function get_ip()
{
    if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) {
        $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
    } elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) {
        $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
    } elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) {
        $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("REMOTE_ADDR")) {
        $ip = getenv("REMOTE_ADDR");
    } else {
        $ip = "Unknown";
    }
    if($ip == "::1"){
        $ip = "localhost";
    }
    return $ip;
}

//保存log
function savelog($user){
    $data['operate_id']   = $user['operate_id'];
    $data['content']      = $user['content'];
    $data['ip']           = get_ip();
    $data['operate_name'] = $user['operate_name'];
    $data['create_time']  = time();
    $result = M('operate_log')->add($data);
}

