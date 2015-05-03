# Host: localhost  (Version: 5.6.12-log)
# Date: 2015-04-14 12:02:22
# Generator: MySQL-Front 5.3  (Build 4.175)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "book_info"
#

DROP TABLE IF EXISTS `book_info`;
CREATE TABLE `book_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图书id',
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '书名',
  `type_id` int(11) DEFAULT NULL COMMENT '类型id',
  `is_checkout` tinyint(3) DEFAULT '0' COMMENT '是否借出（0表示未借出，1表示借出，默认是0）',
  `is_del` tinyint(3) DEFAULT '0' COMMENT '是否删除（0表示未删除，1表示删除）',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间、',
  `is_blue` tinyint(3) DEFAULT NULL COMMENT '是否是蓝标（不能借出）',
  `borrow_num` int(11) DEFAULT '0' COMMENT '历史借阅次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='图书信息表';

#
# Data for table "book_info"
#

INSERT INTO `book_info` VALUES (2,'C语言',3,0,0,1428141165,1,0),(3,'C++基础',3,0,0,1428141188,0,0),(4,'新概念必修1',5,0,0,1428142384,1,0),(6,'新概念必修3',5,0,0,1428142392,0,0),(7,'高等数学1',5,0,0,1428142406,0,1),(8,'高等数学2',5,0,0,1428142409,0,1),(9,'高等数学3',5,1,0,1428142412,0,2),(10,'大学体育选修1',6,0,0,1428142449,1,0),(11,'大学体育选修2',6,1,0,1428142452,0,1),(12,'大学体育选修3',6,0,0,1428142454,0,1),(13,'大学体育选修4',6,0,0,1428142457,0,5);

#
# Structure for table "book_type"
#

DROP TABLE IF EXISTS `book_type`;
CREATE TABLE `book_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '类型名字',
  `is_del` tinyint(3) DEFAULT NULL COMMENT '是否删除（0表示未删除）',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='图书类别';

#
# Data for table "book_type"
#

INSERT INTO `book_type` VALUES (3,'计算机语言',0,1428139776),(4,'高等数学',0,1428142346),(5,'大学英语',0,1428142353),(6,'大学体育',0,1428142360);

#
# Structure for table "borrow"
#

DROP TABLE IF EXISTS `borrow`;
CREATE TABLE `borrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '借阅id',
  `book_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '借出时间',
  `back_time` int(11) DEFAULT NULL COMMENT '归还时间(包括超期归还和未超期归还)',
  `is_back` tinyint(3) DEFAULT '0' COMMENT '是否归还（0表示为未归还，1表示归还，默认是0）',
  `is_past` tinyint(3) DEFAULT '0' COMMENT '是否超期（0表示未超期，1表示超期）',
  `pass_time` int(11) DEFAULT '0' COMMENT '超期时间',
  `money` float(5,2) DEFAULT '0.00' COMMENT '应交金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='借书表';

#
# Data for table "borrow"
#

INSERT INTO `borrow` VALUES (8,13,16,1429414651,1428831028,1,0,0,0.00),(9,12,16,1428414983,1428979545,1,1,5,1.00),(10,11,16,1428415011,NULL,0,1,5,1.00),(12,9,30,1428412692,NULL,0,1,5,1.00),(13,8,30,1428982695,1428982725,1,0,0,0.00),(14,7,17,1428983500,1428983542,1,0,0,0.00),(15,13,17,1428983796,1428983823,1,0,0,0.00),(16,13,17,1428983883,1428983897,1,0,0,0.00);

#
# Structure for table "feedback_info"
#

DROP TABLE IF EXISTS `feedback_info`;
CREATE TABLE `feedback_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '反馈信息表',
  `user_id` int(11) DEFAULT NULL COMMENT '反馈人id',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '反馈内容（可以是建议，软件交互不好等）',
  `back_user_id` char(11) DEFAULT NULL COMMENT '回复人id',
  `back_content` varchar(500) DEFAULT NULL COMMENT '回复人回复内容（后台人员）',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：表示未回复，1：已回复，2：表示删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='反馈信息表';

#
# Data for table "feedback_info"
#

INSERT INTO `feedback_info` VALUES (1,16,'软件不好用啊！',NULL,NULL,1419515878,0),(2,16,'22222软件不好用啊！',NULL,NULL,1419516011,0),(3,16,'22222软件不好用啊！',NULL,NULL,1423052359,0);

#
# Structure for table "identify_info"
#

DROP TABLE IF EXISTS `identify_info`;
CREATE TABLE `identify_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '验证码信息表',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '邮箱或者手机号',
  `indentify` char(10) DEFAULT NULL COMMENT '验证码',
  `create_time` varchar(255) DEFAULT NULL COMMENT '产生时间（过期时间 1 分钟）',
  `type` varchar(255) DEFAULT NULL COMMENT '验证类型（0表示 忘记密码）',
  `is_verify` tinyint(2) DEFAULT '0' COMMENT '是否验证过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='验证码信息表';

#
# Data for table "identify_info"
#

INSERT INTO `identify_info` VALUES (8,'1585856656@qq.com','475748','1423205146','0',1);

#
# Structure for table "operate_log"
#

DROP TABLE IF EXISTS `operate_log`;
CREATE TABLE `operate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作日志表',
  `operate_id` char(32) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '操作内容',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '操作人ip 地址',
  `operate_name` varchar(255) NOT NULL DEFAULT '' COMMENT '操作人姓名',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COMMENT='操作日志表';

#
# Data for table "operate_log"
#

INSERT INTO `operate_log` VALUES (26,'1','退出系统','localhost','赵亚飞',1427165816),(27,'1','退出系统','localhost','admin1',1427166419),(28,'16','登陆系统成功','localhost','admin2',1427166866),(30,'17','登陆系统成功','localhost','admin3',1427167215),(31,'17','退出系统','localhost','admin3',1427167254),(39,'18','退出系统','localhost','admin4',1427168060),(40,'18','登陆系统成功','localhost','admin4',1427168122),(41,'18','登陆系统成功','localhost','admin4',1427459707),(42,'18','登陆系统成功','localhost','admin4',1428138565),(43,'18','退出系统','localhost','admin4',1428145175),(44,'18','登陆系统成功','localhost','admin4',1428145474),(45,'18','登陆系统成功','localhost','admin4',1428406799),(46,'18','退出系统','localhost','admin4',1428411420),(47,'16','登陆系统成功','localhost','admin2',1428411433),(48,'18','登陆系统成功','localhost','admin4',1428828723),(49,'17','登陆系统成功','localhost','admin3',1428828725),(50,'17','登陆系统成功','localhost','admin3',1428828772),(51,'17','退出系统','localhost','admin3',1428828779),(52,'16','登陆系统成功','localhost','admin2',1428828986),(53,'16','退出系统','localhost','admin2',1428828990),(54,'18','登陆系统成功','localhost','admin4',1428829116),(55,'18','退出系统','localhost','admin4',1428829189),(56,'16','登陆系统成功','localhost','admin2',1428829196),(57,'16','退出系统','localhost','admin2',1428830216),(58,'18','登陆系统成功','localhost','admin4',1428830237),(59,'18','退出系统','localhost','admin4',1428830294),(60,'16','登陆系统成功','localhost','admin2',1428830638),(61,'16','退出系统','localhost','admin2',1428831318),(62,'18','登陆系统成功','localhost','admin4',1428831326),(63,'18','登陆系统成功','localhost','admin4',1428919456),(64,'18','登陆系统成功','localhost','admin4',1428976742),(65,'18','退出系统','localhost','admin4',1428982647),(66,'30','登陆系统成功','localhost','zhaoyafei',1428982665),(67,'30','退出系统','localhost','zhaoyafei',1428983387),(68,'30','登陆系统成功','localhost','zhaoyafei',1428983393),(69,'30','退出系统','localhost','zhaoyafei',1428983473),(70,'17','登陆系统成功','localhost','admin3',1428983491);

#
# Structure for table "role_node_rela"
#

DROP TABLE IF EXISTS `role_node_rela`;
CREATE TABLE `role_node_rela` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色节点关联表id',
  `role_id` int(11) DEFAULT NULL COMMENT '角色id',
  `node_id` varchar(20) DEFAULT NULL COMMENT '节点id',
  PRIMARY KEY (`id`),
  KEY `id_role` (`role_id`),
  KEY `id_node` (`node_id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

#
# Data for table "role_node_rela"
#

INSERT INTO `role_node_rela` VALUES (113,1,'10'),(115,1,'11'),(119,1,'12'),(120,1,'1'),(121,1,'2'),(128,2,'1'),(129,2,'2'),(130,2,'3'),(131,2,'7'),(132,2,'8'),(133,2,'9'),(134,2,'10'),(135,2,'11'),(136,2,'12'),(137,2,'13'),(138,2,'17'),(140,3,'1'),(141,3,'2'),(142,3,'3'),(143,3,'7'),(144,3,'8'),(145,3,'9'),(146,3,'10'),(147,3,'11'),(148,3,'12'),(149,3,'17'),(150,3,'18'),(151,3,'13'),(152,4,'1'),(153,4,'2'),(154,4,'3'),(155,4,'4'),(156,4,'5'),(158,4,'10'),(159,4,'11'),(160,4,'13'),(161,4,'14'),(162,4,'15'),(163,4,'16'),(164,4,'17'),(166,4,'19'),(167,4,'20');

#
# Structure for table "session_info"
#

DROP TABLE IF EXISTS `session_info`;
CREATE TABLE `session_info` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "session_info"
#

/*!40000 ALTER TABLE `session_info` DISABLE KEYS */;
INSERT INTO `session_info` VALUES ('pg4p5e9a1qqoptl3l4pqqf6sr1',1429070364,X'69735F64656C5F6C696E657C693A323B69647C733A323A223137223B747970657C733A313A2233223B757365725F6E616D657C733A363A2261646D696E33223B'),('mb456ian1ubgmf4gq3te6nmfj5',1429068244,X'69735F64656C5F6C696E657C693A323B69647C693A313B747970657C693A313B757365725F6E616D657C733A363A2261646D696E31223B'),('jq3dgt8t652mo7iuvfvqv7rc85',1429020519,X'69735F64656C5F6C696E657C693A323B69647C693A313B747970657C693A313B757365725F6E616D657C733A363A2261646D696E31223B'),('tf9sp0cb4resgsuas6reka8ak7',1429020890,X'69735F64656C5F6C696E657C693A323B69647C733A323A223138223B747970657C733A313A2234223B757365725F6E616D657C733A363A2261646D696E34223B');
/*!40000 ALTER TABLE `session_info` ENABLE KEYS */;

#
# Structure for table "user_info"
#

DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户信息表',
  `user_name` varchar(45) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '用户密码',
  `real_name` varchar(45) DEFAULT NULL COMMENT '用户的真实姓名',
  `gender` tinyint(1) DEFAULT '0' COMMENT '性别：0 表示男，1 表示女',
  `phone` char(11) DEFAULT NULL COMMENT '11位手机号（不是电话）',
  `user_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户类别（1 游客，2 表示学生，3 表示老师，4 表示管理员）',
  `avatar` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` varchar(45) DEFAULT '0' COMMENT '0 表示正常，1 表示删除，2 表示禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

#
# Data for table "user_info"
#

INSERT INTO `user_info` VALUES (1,'admin1','c81e728d9d4c2f636f067f89cc14862c','admin',1,'18336073039',1,NULL,1422446862,'2'),(16,'admin2','c4ca4238a0b923820dcc509a6f75849b','赵亚飞',0,'18336070719',2,NULL,1422747790,'0'),(17,'admin3','c4ca4238a0b923820dcc509a6f75849b',NULL,1,'18336073037',3,NULL,1422647831,'0'),(18,'admin4','c4ca4238a0b923820dcc509a6f75849b',NULL,1,'18336073038',4,NULL,1422547803,'2'),(20,'2585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073040',0,NULL,1422846861,'0'),(22,'2485856656@qq.com','c81e728d9d4c2f636f067f89cc14862c',NULL,0,'18336073042',0,NULL,1422847790,'2'),(23,'6585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073043',0,NULL,1423847790,'2'),(24,'7585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073045',0,NULL,1419847790,'0'),(25,'8585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073046',0,NULL,1418847790,'0'),(26,'9585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073047',0,NULL,1417847790,'2'),(27,'1485856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073048',0,NULL,1416847790,'2'),(28,'5585856656@qq.com','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'18336073044',0,NULL,1415847790,'1'),(30,'zhaoyafei','c4ca4238a0b923820dcc509a6f75849b','赵亚飞',0,'18336073038',2,NULL,1428982453,'0'),(31,'zhaoyafei1','c4ca4238a0b923820dcc509a6f75849b',NULL,0,'qwewq',3,NULL,1428982507,'0');

#
# Structure for table "user_node_info"
#

DROP TABLE IF EXISTS `user_node_info`;
CREATE TABLE `user_node_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限节点id',
  `name` varchar(20) DEFAULT NULL COMMENT '权限节点名称',
  `url` varchar(100) DEFAULT NULL COMMENT '权限对应的URL地址',
  `parent_id` varchar(255) DEFAULT '0' COMMENT '父节点id',
  `depth` int(11) DEFAULT NULL COMMENT '权限层级，导航为第1级，左边子导航为第2级',
  `type` varchar(10) DEFAULT NULL COMMENT '权限分类(menu,button)',
  `description` varchar(50) DEFAULT NULL COMMENT '描述',
  `sort_factor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='权限信息表';

#
# Data for table "user_node_info"
#

INSERT INTO `user_node_info` VALUES (1,'个人信息','Index','0',1,'menu','个人',2),(2,'欢迎进入','Index/index','1',2,'menu',NULL,1),(3,'密码修改','Index/resetpwd','1',2,'menu',NULL,3),(4,'用户信息','User','0',1,'menu','用户',4),(5,'信息设置','User/index','4',2,'menu',NULL,1),(7,'我的借阅','Borrowing','0',1,'menu','借阅',3),(8,'借阅信息','Borrowing/index','7',2,'menu',NULL,1),(9,'历史借阅','Borrowing/history','7',2,'menu',NULL,2),(10,'图书信息','Bookinfo','0',1,'menu','图书',1),(11,'图书查询','Bookinfo/index','10',2,'menu',NULL,1),(12,'借阅排行','Bookinfo/borrow_info','10',2,'menu',NULL,2),(13,'超期查询','Bookinfo/back_info','10',2,'menu',NULL,3),(14,'管理员设置','Setting','0',1,'menu','设置',5),(15,'登陆日志','Setting/log','14',2,'menu',NULL,2),(16,'超期归还','Setting/index','14',2,'menu',NULL,1),(17,'信息设置','Index/setinfo','1',2,'menu',NULL,2),(19,'添加图书','Setting/add_book','14',2,'menu',NULL,3),(20,'添加类别','Setting/add_type','14',2,'menu',NULL,4);
