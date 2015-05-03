<?php
    return array(
        /* 默认设定 */
        'DEFAULT_MODULE'    =>  'Home',  // 默认模块
        'MODULE_ALLOW_LIST' => array('Home'),
        'MODULE_DENY_LIST'  => array('Common','Runtime'),
        
        'DEFAULT_FILTER'    => '', //全局过滤函数

         //分布式session保存（session采用数据库保存）
        'SESSION_OPTIONS'=>array(
            'type'       => 'db', 
            'expire'     => 86400,
        ),
        'SESSION_TABLE'  => 'session_info', 
        'EXPIRE'         => 86400,//cookie过期时间
        
        //采用主从数据库配置
        'DB_RW_SEPARATE' => false,//数据库读写是否分离 主从式有效
        'DB_MASTER_NUM'  => 1,    //读写分离后 主服务器数量
        'LINE_STATUS'    => 2,    //导航栏默认状态1,2
        'ADMIN_TYPE'     => '3',  //超级管理员(用户组)  
        'DEFAULT_TYPE'   => '4',  //后台人员默认用户组
        'LIST_ROWS'      => 8     //后台分页
    );
?>