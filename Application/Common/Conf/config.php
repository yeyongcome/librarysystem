<?php
    return array(
        /* 默认设定 */
        'DEFAULT_MODULE'    =>  'Home',  // 默认模块
        'MODULE_ALLOW_LIST' => array('Home'),
        'MODULE_DENY_LIST'  => array('Common','Runtime'),

        'DEFAULT_FILTER'    => '', //全局过滤函数
        
        /* 数据库配置 */
        'DB_TYPE'    => 'mysql',    // 数据库类型
        'DB_HOST'    => 'localhost',// 需修改为目标服务器地址
        'DB_NAME'    => 'librarysystem',// 数据库名
        'DB_USER'    => 'root',     // 需修改为目标用户名
        'DB_PWD'     => 'sanyue%',  // 需修改为目标密码
        'DB_PORT'    => '3306',     // 端口
        'DB_PREFIX'  => '',         // 数据库表前缀

        //分布式session保存（session采用数据库保存）
        'SESSION_OPTIONS'=>array(
            'type'       => 'db', 
            'expire'     => 86400,
        ),
        'SESSION_TABLE'  => 'session_info', 
        'EXPIRE'         => 86400,  //cookie过期时间
        'S_PASSTIME'       => 2,
        'T_PASSTIME'       => 4,
        //采用主从数据库配置
        'DB_RW_SEPARATE' => false,//数据库读写是否分离 主从式有效
        'DB_MASTER_NUM'  => 1,    //读写分离后 主服务器数量
        'LINE_STATUS'    => 2,    //导航栏默认状态1,2
        'DEFAULT_TYPE'   => '4',  //后台人员默认用户组
        'LIST_ROWS'      => 9     //后台分页
    );
?>