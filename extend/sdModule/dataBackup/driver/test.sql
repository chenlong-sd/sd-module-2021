/** 数据备份时间  2020-11-03 12:14:00 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 数据备份开始 

-- sd_administrators数据结构备份开始 

CREATE TABLE `sd_administrators` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `error_number` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码错误次数',
  `lately_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '最近登录',
  `error_date` date DEFAULT NULL COMMENT '错误日期',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=冻结',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='管理员';

-- sd_administrators数据结构备份完成 

-- sd_administrators 数据备份开始。

INSERT INTO `sd_administrators` (`id`,`name`,`account`,`password`,`error_number`,`lately_time`,`error_date`,`role_id`,`status`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, '超管', 'admin', '$2y$10$d/uL2UnR81Hw3ZuXv4WYS.1UXZv4YchW5mFrjcPk/En1Byjp3QHES', 0, '2020-10-22 18:26:03', '2020-07-09', 0, 1, '2020-10-22 18:26:03', '2020-10-22 18:26:03', 0);
INSERT INTO `sd_administrators` (`id`,`name`,`account`,`password`,`error_number`,`lately_time`,`error_date`,`role_id`,`status`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, '测试账号二', 'test', '$2y$10$O1GwC2Wf9U5Y7/M5ezCXBeQraiaBS8WKQHUYgTUPCM1CuL8yQr1fG', 0, '2020-06-18 15:05:28', null, 2, 2, '2020-06-18 15:05:28', '2020-06-18 15:05:28', 0);
INSERT INTO `sd_administrators` (`id`,`name`,`account`,`password`,`error_number`,`lately_time`,`error_date`,`role_id`,`status`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, '测试账号一', 123456, '$2y$10$KGqBvetaG89/QBz.xFmkju2GDiIhSxPQPUM4ZufcKPnpLEn.JTO3u', 0, '2020-06-18 15:04:49', '2020-05-12', 2, 1, '2020-06-18 15:04:49', '2020-06-18 15:04:49', 0);

-- sd_administrators 数据备份结束。

-- sd_category数据结构备份开始 

CREATE TABLE `sd_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sign` varchar(16) NOT NULL DEFAULT '' COMMENT '分类标识',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '标识ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=停用',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分类表';

-- sd_category数据结构备份完成 

-- sd_category 数据备份开始。

-- sd_log数据结构备份开始 

CREATE TABLE `sd_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '请求方式：1=GET,2=POST',
  `route_id` int(11) NOT NULL DEFAULT '0' COMMENT '路由ID',
  `administrators_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作管理员',
  `param` varchar(2048) NOT NULL DEFAULT '' COMMENT '请求参数',
  `route` varchar(32) NOT NULL DEFAULT '' COMMENT '路由地址',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `administrators_id` (`administrators_id`) USING BTREE,
  KEY `route_id` (`route_id`) USING BTREE,
  KEY `route` (`route`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='后台操作日志';

-- sd_log数据结构备份完成 

-- sd_log 数据备份开始。

INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, 2, 0, 0, '{"account":"123123","password":"12312312","captcha":"123123"}', 'System.Index/login', '2020-07-08 10:52:43', '2020-07-08 10:52:43', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"6whd"}', 'System.Index/login', '2020-07-08 10:56:30', '2020-07-08 10:56:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"cow6"}', 'System.Index/login', '2020-07-08 10:56:47', '2020-07-08 10:56:47', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 4, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"zrfs"}', 'System.Index/login', '2020-07-08 10:56:53', '2020-07-08 10:56:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 5, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"zrfs"}', 'System.Index/login', '2020-07-08 10:58:35', '2020-07-08 10:58:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 6, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"j3j3"}', 'System.Index/login', '2020-07-08 10:58:42', '2020-07-08 10:58:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 7, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"j3j3"}', 'System.Index/login', '2020-07-08 10:58:45', '2020-07-08 10:58:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 8, 2, 0, 0, '{"account":"admin","password":"4545545","captcha":"lcpa"}', 'System.Index/login', '2020-07-08 10:58:53', '2020-07-08 10:58:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 9, 2, 0, 0, '{"account":"admin","password":"12458","captcha":"nc2s"}', 'System.Index/login', '2020-07-08 11:01:18', '2020-07-08 11:01:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 10, 2, 0, 0, '{"account":"admin","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:01:32', '2020-07-08 11:01:32', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 11, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:07:51', '2020-07-08 11:07:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 12, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:16', '2020-07-08 11:08:16', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 13, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:33', '2020-07-08 11:08:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 14, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:36', '2020-07-08 11:08:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 15, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:41', '2020-07-08 11:08:41', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 16, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:41', '2020-07-08 11:08:41', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 17, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:42', '2020-07-08 11:08:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 18, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:42', '2020-07-08 11:08:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 19, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:08:42', '2020-07-08 11:08:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 20, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:10:58', '2020-07-08 11:10:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 21, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:11:00', '2020-07-08 11:11:00', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 22, 2, 0, 0, '{"account":"adm","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:13:17', '2020-07-08 11:13:17', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 23, 2, 0, 0, '{"account":"admin","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:13:20', '2020-07-08 11:13:20', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 24, 2, 0, 0, '{"account":"admin","password":"121212","captcha":"nzir"}', 'System.Index/login', '2020-07-08 11:18:17', '2020-07-08 11:18:17', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 25, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"bvxh"}', 'System.Index/login', '2020-07-08 11:18:28', '2020-07-08 11:18:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 26, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 11:20:21', '2020-07-08 11:20:21', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 27, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 11:20:23', '2020-07-08 11:20:23', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 28, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 11:20:48', '2020-07-08 11:20:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 29, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 11:21:29', '2020-07-08 11:21:29', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 30, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"bxis"}', 'System.Index/login', '2020-07-08 11:21:37', '2020-07-08 11:21:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 31, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:01', '2020-07-08 14:28:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 32, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:05', '2020-07-08 14:28:05', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 33, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:41', '2020-07-08 14:28:41', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 34, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:42', '2020-07-08 14:28:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 35, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:42', '2020-07-08 14:28:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 36, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:43', '2020-07-08 14:28:43', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 37, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:43', '2020-07-08 14:28:43', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 38, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:44', '2020-07-08 14:28:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 39, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:44', '2020-07-08 14:28:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 40, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:45', '2020-07-08 14:28:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 41, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:45', '2020-07-08 14:28:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 42, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:45', '2020-07-08 14:28:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 43, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:52', '2020-07-08 14:28:52', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 44, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:53', '2020-07-08 14:28:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 45, 2, 0, 0, '{"account":"as","password":"asdasd","captcha":"sanm"}', 'System.Index/login', '2020-07-08 14:28:53', '2020-07-08 14:28:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 46, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:10', '2020-07-08 14:29:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 47, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:11', '2020-07-08 14:29:11', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 48, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:11', '2020-07-08 14:29:11', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 49, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:12', '2020-07-08 14:29:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 50, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:12', '2020-07-08 14:29:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 51, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:12', '2020-07-08 14:29:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 52, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:12', '2020-07-08 14:29:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 53, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:13', '2020-07-08 14:29:13', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 54, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:13', '2020-07-08 14:29:13', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 55, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:13', '2020-07-08 14:29:13', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 56, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:14', '2020-07-08 14:29:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 57, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:15', '2020-07-08 14:29:15', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 58, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:15', '2020-07-08 14:29:15', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 59, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:33', '2020-07-08 14:29:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 60, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:34', '2020-07-08 14:29:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 61, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:35', '2020-07-08 14:29:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 62, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:35', '2020-07-08 14:29:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 63, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:35', '2020-07-08 14:29:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 64, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:36', '2020-07-08 14:29:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 65, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:36', '2020-07-08 14:29:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 66, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:38', '2020-07-08 14:29:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 67, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:39', '2020-07-08 14:29:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 68, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:39', '2020-07-08 14:29:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 69, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:39', '2020-07-08 14:29:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 70, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:44', '2020-07-08 14:29:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 71, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:29:53', '2020-07-08 14:29:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 72, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:22', '2020-07-08 14:30:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 73, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:23', '2020-07-08 14:30:23', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 74, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:23', '2020-07-08 14:30:23', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 75, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:24', '2020-07-08 14:30:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 76, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:35', '2020-07-08 14:30:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 77, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:35', '2020-07-08 14:30:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 78, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:36', '2020-07-08 14:30:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 79, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:36', '2020-07-08 14:30:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 80, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:36', '2020-07-08 14:30:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 81, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:37', '2020-07-08 14:30:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 82, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:55', '2020-07-08 14:30:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 83, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:55', '2020-07-08 14:30:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 84, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:56', '2020-07-08 14:30:56', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 85, 2, 0, 0, '{"account":"ad","password":"asd","captcha":"qqq"}', 'System.Index/login', '2020-07-08 14:30:58', '2020-07-08 14:30:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 86, 2, 0, 0, '{"account":"adm","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:31:24', '2020-07-08 14:31:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 87, 2, 0, 0, '{"account":"adm","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:31:25', '2020-07-08 14:31:25', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 88, 2, 0, 0, '{"account":"adm","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:31:26', '2020-07-08 14:31:26', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 89, 2, 0, 0, '{"account":"adm","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:31:31', '2020-07-08 14:31:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 90, 2, 0, 0, '{"account":"21312","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:33:14', '2020-07-08 14:33:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 91, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:33:17', '2020-07-08 14:33:17', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 92, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:08', '2020-07-08 14:34:08', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 93, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:17', '2020-07-08 14:34:17', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 94, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:26', '2020-07-08 14:34:26', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 95, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:28', '2020-07-08 14:34:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 96, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:29', '2020-07-08 14:34:29', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 97, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:29', '2020-07-08 14:34:29', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 98, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:30', '2020-07-08 14:34:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 99, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:30', '2020-07-08 14:34:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 100, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:31', '2020-07-08 14:34:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 101, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:31', '2020-07-08 14:34:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 102, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:32', '2020-07-08 14:34:32', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 103, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:33', '2020-07-08 14:34:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 104, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:33', '2020-07-08 14:34:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 105, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:34', '2020-07-08 14:34:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 106, 2, 0, 0, '{"account":"213","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 14:34:34', '2020-07-08 14:34:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 107, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"gm6v"}', 'System.Index/login', '2020-07-08 14:39:50', '2020-07-08 14:39:50', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 108, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 15:57:22', '2020-07-08 15:57:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 109, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 15:57:27', '2020-07-08 15:57:27', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 110, 2, 0, 0, '{"account":"123","password":"123","captcha":"123"}', 'System.Index/login', '2020-07-08 15:57:34', '2020-07-08 15:57:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 111, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"yd4r"}', 'System.Index/login', '2020-07-08 16:07:08', '2020-07-08 16:07:08', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 112, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"axlx"}', 'System.Index/login', '2020-07-09 09:10:24', '2020-07-09 09:10:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 113, 2, 0, 0, '{"account":"admin","password":"1q23123","captcha":"ihxk"}', 'System.Index/login', '2020-07-09 15:24:33', '2020-07-09 15:24:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 114, 2, 0, 0, '{"account":"admin","password":"1q23123","captcha":"7ffm"}', 'System.Index/login', '2020-07-09 15:24:41', '2020-07-09 15:24:41', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 115, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"r58j"}', 'System.Index/login', '2020-07-09 15:24:57', '2020-07-09 15:24:57', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 116, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_images":{"label":"展示', 'System.System/aux', '2020-07-09 15:57:55', '2020-07-09 15:57:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 117, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_images":{"label":"展示', 'System.System/aux', '2020-07-09 15:59:08', '2020-07-09 15:59:08', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 118, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_images":{"label":"展示', 'System.System/aux', '2020-07-09 16:00:11', '2020-07-09 16:00:11', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 119, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:03:35', '2020-07-09 16:03:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 120, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:05:14', '2020-07-09 16:05:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 121, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_images":{"label":"展示', 'System.System/aux', '2020-07-09 16:11:02', '2020-07-09 16:11:02', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 122, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_images":{"label":"展示', 'System.System/aux', '2020-07-09 16:13:49', '2020-07-09 16:13:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 123, 2, 0, 1, '{"table_name":"test","page_name":"","id":{"label":"ID","type":"","show_type":"text","join":""},"title":{"label":"标题","type":"text","quick_search":"1","show_type":"text","join":""},"cover":{"label":"封面","type":"image","show_type":"image","join":""},"show_i', 'System.System/aux', '2020-07-09 16:16:55', '2020-07-09 16:16:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 124, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-07-09 16:19:24', '2020-07-09 16:19:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 125, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:20:01', '2020-07-09 16:20:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 126, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:24:05', '2020-07-09 16:24:05', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 127, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:24:10', '2020-07-09 16:24:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 128, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:42', '2020-07-09 16:25:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 129, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:51', '2020-07-09 16:25:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 130, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:52', '2020-07-09 16:25:52', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 131, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:52', '2020-07-09 16:25:52', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 132, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:53', '2020-07-09 16:25:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 133, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:25:53', '2020-07-09 16:25:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 134, 2, 0, 1, '{"id":"2","title":"asdasd","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:26:01', '2020-07-09 16:26:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 135, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:32:16', '2020-07-09 16:32:16', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 136, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:32:30', '2020-07-09 16:32:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 137, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:32:44', '2020-07-09 16:32:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 138, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:34:18', '2020-07-09 16:34:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 139, 2, 0, 1, '{"id":"2","title":"123123","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200506\/fd85...","intro":"123123","status":"2","administrators_id":"1","pid":"","content":"<p><img src=\"http:\/\/192.168.5..."}', 'Test/update', '2020-07-09 16:35:04', '2020-07-09 16:35:04', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 140, 2, 0, 1, '{"role_id":"3"}', 'System.Power/set', '2020-07-09 17:58:08', '2020-07-09 17:58:08', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 141, 2, 8, 1, '{"role":"","describe":""}', 'System.Role/create', '2020-07-09 18:31:45', '2020-07-09 18:31:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 142, 2, 8, 1, '{"role":"","describe":""}', 'System.Role/create', '2020-07-09 18:34:34', '2020-07-09 18:34:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 143, 2, 8, 1, '{"role":"","describe":""}', 'System.Role/create', '2020-07-09 18:34:37', '2020-07-09 18:34:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 144, 2, 8, 1, '{"role":"","describe":""}', 'System.Role/create', '2020-07-09 18:36:34', '2020-07-09 18:36:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 145, 2, 8, 1, '{"role":"","describe":""}', 'System.Role/create', '2020-07-09 18:36:38', '2020-07-09 18:36:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 146, 2, 0, 0, '{"account":"admin","password":"123456","captcha":"u46j"}', 'System.Index/login', '2020-07-10 09:23:28', '2020-07-10 09:23:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 147, 2, 0, 1, '{"role_id":"3"}', 'System.Power/set', '2020-07-10 10:05:36', '2020-07-10 10:05:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 148, 2, 0, 1, '{"title":"","route":"","pid":"","weigh":"123123","icon":""}', 'System.Route/create', '2020-07-10 11:26:07', '2020-07-10 11:26:07', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 149, 2, 0, 1, '{"title":"","route":"","pid":"","weigh":"123123","icon":""}', 'System.Route/create', '2020-07-10 11:27:02', '2020-07-10 11:27:02', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 150, 2, 0, 1, '{"title":"","route":"","pid":"","weigh":"123123","icon":""}', 'System.Route/create', '2020-07-10 11:27:07', '2020-07-10 11:27:07', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 151, 2, 0, 1, '{"title":"","route":"","pid":"","weigh":"123123","icon":""}', 'System.Route/create', '2020-07-10 11:27:33', '2020-07-10 11:27:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 152, 2, 0, 1, '{"title":"","route":"","pid":"","weigh":"123","icon":""}', 'System.Route/create', '2020-07-10 11:31:55', '2020-07-10 11:31:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 153, 2, 0, 1, '{"title":"","route":"","type":"1","pid":"","weigh":"123","icon":""}', 'System.Route/create', '2020-07-10 11:31:58', '2020-07-10 11:31:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 154, 2, 0, 0, '{"account":"admin","captcha":"dcdb"}', 'System.Index/login', '2020-08-06 18:48:14', '2020-08-06 18:48:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 155, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200806\/5593...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"2","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-08-06 18:52:26', '2020-08-06 18:52:26', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 156, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200806\/5593...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"2","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-08-06 18:52:46', '2020-08-06 18:52:46', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 157, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200806\/9fef...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"2","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-08-06 18:55:18', '2020-08-06 18:55:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 158, 2, 0, 0, '{"account":"admin","captcha":"bvgd"}', 'System.Index/login', '2020-08-07 11:41:56', '2020-08-07 11:41:56', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 159, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200807\/74dd...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"2","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-08-07 11:42:22', '2020-08-07 11:42:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 160, 2, 0, 0, '{"account":"admin","captcha":"buca"}', 'System.Index/login', '2020-08-10 18:14:05', '2020-08-10 18:14:05', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 161, 2, 0, 0, '{"account":"admin","captcha":"k8rf"}', 'System.Index/login', '2020-09-16 18:30:07', '2020-09-16 18:30:07', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 162, 2, 0, 0, '{"account":"admin","captcha":"qczl"}', 'System.Index/login', '2020-09-25 12:35:44', '2020-09-25 12:35:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 163, 2, 0, 0, '{"account":"admin","captcha":"pqdw"}', 'System.Index/login', '2020-09-25 12:35:55', '2020-09-25 12:35:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 164, 2, 0, 0, '{"account":"admin","captcha":"y4ip"}', 'System.Index/login', '2020-09-26 17:04:14', '2020-09-26 17:04:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 165, 2, 0, 0, '{"account":"admin","captcha":"vdcz"}', 'System.Index/login', '2020-09-28 14:03:57', '2020-09-28 14:03:57', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 166, 2, 0, 0, '{"account":"admin","captcha":"nj35"}', 'System.Index/login', '2020-09-29 09:03:08', '2020-09-29 09:03:08', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 167, 2, 0, 0, '{"account":"admin","captcha":"kncv"}', 'System.Index/login', '2020-10-09 12:19:24', '2020-10-09 12:19:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 168, 2, 0, 0, '{"account":"admin","captcha":"tdfz"}', 'System.Index/login', '2020-10-09 16:04:49', '2020-10-09 16:04:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 169, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:03:58', '2020-10-13 11:03:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 170, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:04:37', '2020-10-13 11:04:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 171, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:05:46', '2020-10-13 11:05:46', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 172, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:07:11', '2020-10-13 11:07:11', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 173, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:07:48', '2020-10-13 11:07:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 174, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:09:32', '2020-10-13 11:09:32', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 175, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:09:42', '2020-10-13 11:09:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 176, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:09:49', '2020-10-13 11:09:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 177, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 11:09:53', '2020-10-13 11:09:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 178, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"2"}', 'System.Index/login', '2020-10-13 12:51:15', '2020-10-13 12:51:15', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 179, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:52:23', '2020-10-13 12:52:23', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 180, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:53:38', '2020-10-13 12:53:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 181, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:53:44', '2020-10-13 12:53:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 182, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:53:50', '2020-10-13 12:53:50', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 183, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:55:18', '2020-10-13 12:55:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 184, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 12:55:28', '2020-10-13 12:55:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 185, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1"}', 'System.Index/login', '2020-10-13 12:56:30', '2020-10-13 12:56:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 186, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 12:56:35', '2020-10-13 12:56:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 187, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1"}', 'System.Index/login', '2020-10-13 12:56:44', '2020-10-13 12:56:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 188, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":""}', 'System.Index/login', '2020-10-13 12:57:16', '2020-10-13 12:57:16', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 189, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1"}', 'System.Index/login', '2020-10-13 12:57:59', '2020-10-13 12:57:59', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 190, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1"}', 'System.Index/login', '2020-10-13 12:58:12', '2020-10-13 12:58:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 191, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"2"}', 'System.Index/login', '2020-10-13 12:58:18', '2020-10-13 12:58:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 192, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"2"}', 'System.Index/login', '2020-10-13 12:58:45', '2020-10-13 12:58:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 193, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"2"}', 'System.Index/login', '2020-10-13 12:59:04', '2020-10-13 12:59:04', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 194, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1"}', 'System.Index/login', '2020-10-13 12:59:15', '2020-10-13 12:59:15', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 195, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 13:29:44', '2020-10-13 13:29:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 196, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 13:32:01', '2020-10-13 13:32:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 197, 2, 0, 0, '{"avatar":"\/upload_resource\/20200807\/74d...","file":"","excel":"1,2"}', 'System.Index/login', '2020-10-13 13:33:01', '2020-10-13 13:33:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 198, 2, 0, 0, '{"account":"admin","captcha":"syxy"}', 'System.Index/login', '2020-10-15 18:03:29', '2020-10-15 18:03:29', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 199, 2, 0, 0, '{"account":"admin","captcha":"raach"}', 'System.Index/login', '2020-10-16 09:10:56', '2020-10-16 09:10:56', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 200, 2, 0, 0, '{"account":"admin","captcha":"rach"}', 'System.Index/login', '2020-10-16 09:10:58', '2020-10-16 09:10:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 201, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 09:58:35', '2020-10-16 09:58:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 202, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 10:04:51', '2020-10-16 10:04:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 203, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的123123"}', 'System.Role/update', '2020-10-16 10:05:09', '2020-10-16 10:05:09', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 204, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:05:31', '2020-10-16 10:05:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 205, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:05:46', '2020-10-16 10:05:46', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 206, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:06:32', '2020-10-16 10:06:32', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 207, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:12:12', '2020-10-16 10:12:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 208, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:12:35', '2020-10-16 10:12:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 209, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:13:01', '2020-10-16 10:13:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 210, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:13:38', '2020-10-16 10:13:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 211, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:17:58', '2020-10-16 10:17:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 212, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:18:12', '2020-10-16 10:18:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 213, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:18:23', '2020-10-16 10:18:23', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 214, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:20:21', '2020-10-16 10:20:21', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 215, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:20:33', '2020-10-16 10:20:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 216, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:20:49', '2020-10-16 10:20:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 217, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:21:10', '2020-10-16 10:21:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 218, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:25:30', '2020-10-16 10:25:30', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 219, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:26:10', '2020-10-16 10:26:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 220, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:26:38', '2020-10-16 10:26:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 221, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:29:50', '2020-10-16 10:29:50', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 222, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 10:30:40', '2020-10-16 10:30:40', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 223, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 11:03:35', '2020-10-16 11:03:35', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 224, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 11:04:37', '2020-10-16 11:04:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 225, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 11:05:00', '2020-10-16 11:05:00', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 226, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 11:05:39', '2020-10-16 11:05:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 227, 2, 8, 1, '{"role":"enen","describe":"123"}', 'System.Role/create', '2020-10-16 11:05:44', '2020-10-16 11:05:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 228, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:05:51', '2020-10-16 11:05:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 229, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:05:55', '2020-10-16 11:05:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 230, 2, 8, 1, '{"role":"qwe","describe":"qwe"}', 'System.Role/create', '2020-10-16 11:06:27', '2020-10-16 11:06:27', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 231, 2, 8, 1, '{"role":"qwe","describe":"qwe"}', 'System.Role/create', '2020-10-16 11:08:50', '2020-10-16 11:08:50', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 232, 2, 8, 1, '{"role":"qwe","describe":"qwe"}', 'System.Role/create', '2020-10-16 11:08:55', '2020-10-16 11:08:55', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 233, 2, 8, 1, '{"role":"qwe","describe":"qwe"}', 'System.Role/create', '2020-10-16 11:09:56', '2020-10-16 11:09:56', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 234, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:10:20', '2020-10-16 11:10:20', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 235, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:10:33', '2020-10-16 11:10:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 236, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的123123"}', 'System.Role/update', '2020-10-16 11:11:12', '2020-10-16 11:11:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 237, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:11:24', '2020-10-16 11:11:24', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 238, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的www"}', 'System.Role/update', '2020-10-16 11:13:28', '2020-10-16 11:13:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 239, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 11:13:33', '2020-10-16 11:13:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 240, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 13:19:16', '2020-10-16 13:19:16', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 241, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的"}', 'System.Role/update', '2020-10-16 13:19:48', '2020-10-16 13:19:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 242, 2, 9, 1, '{"id":"3","role":"测试角色2","describe":"嗯嗯你的1111"}', 'System.Role/update', '2020-10-16 13:19:54', '2020-10-16 13:19:54', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 243, 2, 0, 0, '{"account":"admin","captcha":"fdrk"}', 'System.Index/login', '2020-10-19 15:50:14', '2020-10-19 15:50:14', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 244, 2, 0, 0, '{"account":"admin","captcha":"wruv"}', 'System.Index/login', '2020-10-20 15:03:28', '2020-10-20 15:03:28', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 245, 2, 9, 1, '{"id":"3","role":"测试角色222","describe":"嗯嗯你的1111"}', 'System.Role/update', '2020-10-20 17:00:38', '2020-10-20 17:00:38', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 246, 2, 9, 1, '{"id":"3","role":"测试角色","describe":"嗯嗯你的1111"}', 'System.Role/update', '2020-10-20 17:00:44', '2020-10-20 17:00:44', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 247, 2, 9, 1, '{"id":"3","role":"测试角色333","describe":"嗯嗯你的1111"}', 'System.Role/update', '2020-10-20 17:00:48', '2020-10-20 17:00:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 248, 2, 9, 1, '{"id":"3","role":"测试角色333订单的","describe":"嗯嗯你的1111"}', 'System.Role/update', '2020-10-20 18:19:27', '2020-10-20 18:19:27', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 249, 2, 8, 1, '{"id":"","role":"次次次","describe":"订单"}', 'System.Role/create', '2020-10-20 18:20:36', '2020-10-20 18:20:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 250, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200807\/74dd...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-20 19:15:40', '2020-10-20 19:15:40', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 251, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200807\/74dd...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-20 19:15:51', '2020-10-20 19:15:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 252, 2, 0, 0, '{"account":"admin","captcha":"rs5t"}', 'System.Index/login', '2020-10-21 13:44:16', '2020-10-21 13:44:16', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 253, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-21 13:45:25', '2020-10-21 13:45:25', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 254, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/ecc2...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"2","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-21 13:45:31', '2020-10-21 13:45:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 255, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20201013\/c9a2...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-21 14:26:49', '2020-10-21 14:26:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 256, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20201013\/c9a2...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-21 14:27:22', '2020-10-21 14:27:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 257, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-21 14:27:34', '2020-10-21 14:27:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 258, 2, 0, 0, '{"account":"admin","captcha":"byu2l"}', 'System.Index/login', '2020-10-22 09:27:22', '2020-10-22 09:27:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 259, 2, 0, 0, '{"account":"admin","captcha":"by2l"}', 'System.Index/login', '2020-10-22 09:27:27', '2020-10-22 09:27:27', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 260, 2, 0, 0, '{"account":"admin","captcha":"dyk2"}', 'System.Index/login', '2020-10-22 09:27:34', '2020-10-22 09:27:34', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 261, 2, 0, 0, '{"account":"admin","captcha":"dyk2"}', 'System.Index/login', '2020-10-22 09:28:49', '2020-10-22 09:28:49', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 262, 2, 0, 0, '{"account":"admin","captcha":"62mt"}', 'System.Index/login', '2020-10-22 09:33:22', '2020-10-22 09:33:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 263, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 09:40:50', '2020-10-22 09:40:50', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 264, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 09:41:01', '2020-10-22 09:41:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 265, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 09:43:09', '2020-10-22 09:43:09', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 266, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 12:16:27', '2020-10-22 12:16:27', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 267, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/a698...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 12:16:58', '2020-10-22 12:16:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 268, 2, 0, 1, '{"id":"4","title":"asdsadasd","cover":"upload_resource\/20200507\/99e5...","file":"","show_images":"upload_resource\/20200507\/99e5...","intro":"asdasdasd","status":"1","administrators_id":"1","pid":"3","content":"<p>asdasd<\/p>"}', 'Test/update', '2020-10-22 12:17:05', '2020-10-22 12:17:05', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 269, 2, 0, 0, '{"account":"admin","captcha":"aaxt"}', 'System.Index/login', '2020-10-22 14:24:51', '2020-10-22 14:24:51', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 270, 2, 0, 1, '{"id":"2"}', 'Test/del', '2020-10-22 15:00:12', '2020-10-22 15:00:12', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 271, 2, 0, 1, '{"id":"1"}', 'Test/del', '2020-10-22 15:15:10', '2020-10-22 15:15:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 272, 2, 29, 1, '[]', 'Test/create', '2020-10-22 15:39:58', '2020-10-22 15:39:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 273, 2, 0, 0, '{"account":"admin","captcha":"kkdj"}', 'System.Index/login', '2020-10-22 17:20:10', '2020-10-22 17:20:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 274, 2, 0, 0, '{"account":"admin","captcha":"vl7t"}', 'System.Index/login', '2020-10-22 18:26:03', '2020-10-22 18:26:03', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 275, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:03:04', '2020-10-26 15:03:04', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 276, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:04:58', '2020-10-26 15:04:58', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 277, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:20:54', '2020-10-26 15:20:54', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 278, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:21:37', '2020-10-26 15:21:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 279, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:21:42', '2020-10-26 15:21:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 280, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:24:22', '2020-10-26 15:24:22', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 281, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:25:47', '2020-10-26 15:25:47', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 282, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:27:33', '2020-10-26 15:27:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 283, 2, 0, 0, '{"qwe":"阿萨德"}', 'System.Index/login', '2020-10-26 15:27:45', '2020-10-26 15:27:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 284, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 15:27:53', '2020-10-26 15:27:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 285, 2, 0, 0, '{"qwe":""}', 'System.Index/login', '2020-10-26 17:23:36', '2020-10-26 17:23:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 286, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:31:18', '2020-10-26 17:31:18', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 287, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:31:36', '2020-10-26 17:31:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 288, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:43:32', '2020-10-26 17:43:32', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 289, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:58:31', '2020-10-26 17:58:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 290, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:59:01', '2020-10-26 17:59:01', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 291, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 17:59:57', '2020-10-26 17:59:57', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 292, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 18:00:10', '2020-10-26 18:00:10', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 293, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 18:14:59', '2020-10-26 18:14:59', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 294, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 18:29:45', '2020-10-26 18:29:45', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 295, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 18:30:33', '2020-10-26 18:30:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 296, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 20:56:13', '2020-10-26 20:56:13', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 297, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 21:00:17', '2020-10-26 21:00:17', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 298, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 21:00:25', '2020-10-26 21:00:25', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 299, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-26 21:03:33', '2020-10-26 21:03:33', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 300, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-27 09:44:36', '2020-10-27 09:44:36', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 301, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-27 09:44:37', '2020-10-27 09:44:37', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 302, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-27 09:44:39', '2020-10-27 09:44:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 303, 2, 0, 0, '{"qwe":"","qwes":""}', 'System.Index/login', '2020-10-27 09:44:41', '2020-10-27 09:44:41', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 304, 2, 0, 0, '{"qwe":"uiy","qwes":""}', 'System.Index/login', '2020-10-27 09:45:25', '2020-10-27 09:45:25', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 305, 2, 0, 0, '{"qwe":"uiy,678,yui,768","qwes":""}', 'System.Index/login', '2020-10-27 09:45:31', '2020-10-27 09:45:31', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 306, 2, 0, 0, '{"qwe":"uiy,678,768","qwes":""}', 'System.Index/login', '2020-10-27 09:45:39', '2020-10-27 09:45:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 307, 2, 0, 0, '{"qwe":"uiy,678,768","qwes":"非官方大哥"}', 'System.Index/login', '2020-10-27 12:09:42', '2020-10-27 12:09:42', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 308, 2, 0, 0, '{"qwe":"uiy,678,768,电饭锅,dfgfdg,...","qwes":"非官方大哥"}', 'System.Index/login', '2020-10-27 12:09:53', '2020-10-27 12:09:53', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 309, 2, 0, 0, '{"qwe":"uiy","qwes":"非官方大哥"}', 'System.Index/login', '2020-10-27 12:10:20', '2020-10-27 12:10:20', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 310, 2, 0, 0, '{"qwe":"","qwes":"非官方大哥"}', 'System.Index/login', '2020-10-27 12:10:26', '2020-10-27 12:10:26', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 311, 2, 0, 0, '{"qwe":"123123","qwes":""}', 'System.Index/login', '2020-10-27 12:10:48', '2020-10-27 12:10:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 312, 2, 0, 0, '', 'System.Index/login', '2020-10-27 12:11:39', '2020-10-27 12:11:39', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 313, 2, 0, 0, '', 'System.Index/login', '2020-10-27 12:11:48', '2020-10-27 12:11:48', 0);
INSERT INTO `sd_log` (`id`,`method`,`route_id`,`administrators_id`,`param`,`route`,`create_time`,`update_time`,`delete_time`) VALUES ( 314, 2, 0, 0, '{"qwe":"电饭锅","qwes":"电饭锅"}', 'System.Index/login', '2020-10-27 12:12:40', '2020-10-27 12:12:40', 0);

-- sd_log 数据备份结束。

-- sd_power数据结构备份开始 

CREATE TABLE `sd_power` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限路由',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`,`route_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='权限记录';

-- sd_power数据结构备份完成 

-- sd_power 数据备份开始。

INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, 1, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, 3, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, 1, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 4, 3, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 5, 5, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 6, 1, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 7, 3, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 8, 15, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 9, 1, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 10, 3, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 11, 5, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 1589452573);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 12, 1, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 0);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 13, 3, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 0);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 14, 5, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 0);
INSERT INTO `sd_power` (`id`,`route_id`,`role_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 15, 28, 2, '2020-05-14 18:36:13', '2020-05-14 18:36:13', 0);

-- sd_power 数据备份结束。

-- sd_resource数据结构备份开始 

CREATE TABLE `sd_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '类型：1=虚拟文件夹，2=文件',
  `tag` varchar(32) NOT NULL DEFAULT '' COMMENT '标签',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT 'md5值',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`) USING HASH,
  KEY `tag` (`tag`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='资源表';

-- sd_resource数据结构备份完成 

-- sd_resource 数据备份开始。

INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, 2, '白蛇缘起.jpg', 0, 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg', 'a7dec757070da2def0ddba58942ce0db', '2020-05-06 17:38:21', '2020-05-06 17:38:21', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, 2, '窗纸.jpg', 0, 'upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 'ae8e42485391dfcb013c755af3df8a31', '2020-05-06 17:39:14', '2020-05-06 17:39:14', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, 2, 'xub5zyafowl.jpg', 0, 'upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg', '8a14d31041d97c9f7a42d64d57554c11', '2020-05-07 11:47:54', '2020-05-07 11:47:54', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 4, 2, 'preview.jpg', 0, 'upload_resource/20200507/ecc2de76468d8d5193f5d42a3d12db81.jpg', '7b63fc58d95539756d00cab4103b651d', '2020-05-07 11:56:56', '2020-05-07 11:56:56', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 5, 2, '8651a12b2.jpg', 0, 'upload_resource/20200507/2927d1fef03f197505d4f52f973be2e2.jpg', '58302a3ebf1b46be59652524836ec039', '2020-05-07 11:57:00', '2020-05-07 11:57:00', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 6, 2, 'upload_279h87jbc9l0hkl54djjjh42d', 0, 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', '92586f2f494dfad1fd9542b82ed536a7', '2020-05-07 15:35:30', '2020-05-07 15:35:30', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 7, 2, '22.jpg', 0, 'upload_resource/20200507/2cb1b1f356f3a447c4bb361578e93735.jpg', '45139b4d0f77bd20cc00c61b254d3c01', '2020-05-07 16:20:40', '2020-05-07 16:20:40', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 8, 2, '水.jpg', 0, 'upload_resource/20200806/55939371c868085bbf2828cb963c9d0e.jpg', 'a8e5a824707edb64ea52991e71e58406', '2020-08-06 18:52:23', '2020-08-06 18:52:23', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 9, 2, 'demo2.jpg', 0, 'upload_resource/20200806/9fef243e7b26690137b7ef55eda972bc.jpg', '9886e90f014d462b560dcec9c327bdb7', '2020-08-06 18:55:15', '2020-08-06 18:55:15', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 10, 2, '005.jpg', 0, 'upload_resource/20200807/74dd9908a44e7ccfc5b18cc80dd283dd.jpg', 'b095c934f585852129810a658d2707dd', '2020-08-07 11:42:13', '2020-08-07 11:42:13', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 11, 2, '', 0, 'upload_resource/202008/20/a9b76a0fc7c3f0551008c76542e1c0ee.jpeg', 'a9b76a0fc7c3f0551008c76542e1c0ee', '2020-08-20 12:03:24', '2020-08-20 12:03:24', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 12, 2, '', 0, 'upload_resource/202008/20/da2dde34776de4c4c1d17a2cbba38667.jpeg', 'da2dde34776de4c4c1d17a2cbba38667', '2020-08-20 12:05:16', '2020-08-20 12:05:16', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 13, 2, 'avatar.jpg', 0, 'upload_resource/20201013/c9a277a3026b4a2b1625d0a016357761.jpg', '37ebef5ed0bde4d9ad470af8efb9f9bc', '2020-10-13 10:07:11', '2020-10-13 10:07:11', 0);
INSERT INTO `sd_resource` (`id`,`type`,`tag`,`pid`,`path`,`md5`,`create_time`,`update_time`,`delete_time`) VALUES ( 14, 2, '蓝天白云.jpg', 0, 'upload_resource/20201022/a52fa02852f28bf133a7b825084c0447.jpg', '9b8528c2709ba157ec7a3a75c8c94f14', '2020-10-22 12:14:54', '2020-10-22 12:14:54', 0);

-- sd_resource 数据备份结束。

-- sd_role数据结构备份开始 

CREATE TABLE `sd_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级角色',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `administrators_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建角色的管理员',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色';

-- sd_role数据结构备份完成 

-- sd_role 数据备份开始。

INSERT INTO `sd_role` (`id`,`role`,`pid`,`describe`,`administrators_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, '测试角色', 0, '测试角色的描述
tip：字数不要超过255哟', 1, '2020-05-14 12:50:48', '2020-05-14 12:50:48', 0);
INSERT INTO `sd_role` (`id`,`role`,`pid`,`describe`,`administrators_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, '测试角色333订单的', 0, '嗯嗯你的1111', 1, '2020-10-20 18:19:27', '2020-10-20 18:19:27', 0);
INSERT INTO `sd_role` (`id`,`role`,`pid`,`describe`,`administrators_id`,`create_time`,`update_time`,`delete_time`) VALUES ( 28, '次次次', 0, '订单', 1, '2020-10-20 18:20:36', '2020-10-20 18:20:36', 0);

-- sd_role 数据备份结束。

-- sd_route数据结构备份开始 

CREATE TABLE `sd_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '权限标题',
  `route` varchar(32) NOT NULL DEFAULT '' COMMENT '权限路由',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级菜单',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1=菜单，2=操作',
  `weigh` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='权限路由';

-- sd_route数据结构备份完成 

-- sd_route 数据备份开始。

INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, '系统设置', '', 0, 1, 0, 'layui-icon-set', '2020-04-23 13:05:17', '2020-04-23 13:05:17', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, '角色管理', 'System.Role/index', 1, 1, 1, '', '2020-06-18 14:47:49', '2020-06-18 14:47:49', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, '管理员管理', 'System.Administrators/index', 1, 1, 2, '', '2020-06-18 14:49:17', '2020-06-18 14:49:17', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 4, '路由管理', 'System.Route/index', 1, 1, 3, '', '2020-06-18 14:50:06', '2020-06-18 14:50:06', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 5, '新增管理员', 'System.Administrators/create', 3, 2, 2, 'layui-icon-heart-fill', '2020-06-18 14:49:35', '2020-06-18 14:49:35', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 7, '测试表', 'Test/index', 1, 1, 9, '', '2020-06-18 14:50:31', '2020-06-18 14:50:31', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 8, '新增角色', 'System.Role/create', 2, 2, 1, '', '2020-06-18 14:48:10', '2020-06-18 14:48:10', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 9, '修改角色', 'System.Role/update', 2, 2, 0, '', '2020-06-18 14:48:22', '2020-06-18 14:48:22', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 10, '删除角色', 'System.Role/del', 2, 2, 2, '', '2020-06-18 14:53:47', '2020-06-18 14:53:47', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 11, '权限设置', 'System.Power/power', 2, 2, 3, '', '2020-05-14 13:09:07', '2020-05-14 13:09:07', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 12, '修改管理员', 'System.Administrators/update', 3, 2, 2, '', '2020-06-18 14:49:45', '2020-06-18 14:49:45', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 13, '删除管理员', 'System.Administrators/del', 3, 2, 3, '', '2020-05-14 13:11:05', '2020-05-14 13:11:05', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 14, '角色数据', 'System.Role/index', 2, 2, 1, '', '2020-06-18 14:49:02', '2020-06-18 14:49:02', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 16, '后台操作日志', 'System.Log/index', 1, 1, 4, '', '2020-06-18 14:50:23', '2020-06-18 14:50:23', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 28, '管理员数据', 'System.Administrators/index', 3, 2, 1, '', '2020-06-18 14:49:55', '2020-06-18 14:49:55', 0);
INSERT INTO `sd_route` (`id`,`title`,`route`,`pid`,`type`,`weigh`,`icon`,`create_time`,`update_time`,`delete_time`) VALUES ( 29, '新增', 'Test/create', 7, 2, 26, '', '2020-06-18 14:56:35', '2020-06-18 14:56:35', 0);

-- sd_route 数据备份结束。

-- sd_test数据结构备份开始 

CREATE TABLE `sd_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `show_images` varchar(2048) NOT NULL DEFAULT '' COMMENT '展示图',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=冻结',
  `administrators_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级',
  `content` text NOT NULL COMMENT '详情',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='测试表';

-- sd_test数据结构备份完成 

-- sd_test 数据备份开始。

INSERT INTO `sd_test` (`id`,`title`,`cover`,`show_images`,`intro`,`status`,`administrators_id`,`pid`,`content`,`create_time`,`update_time`,`delete_time`) VALUES ( 1, '你好你好', 'upload_resource/20200429/6fba04f2044bb0c5eb969003f95c5d93.jpg', 'upload_resource/20200429/7f82a960b6c217b6f8246ed3d20d9e3e.jpg,upload_resource/20200429/306d40a286538033d5d4f4e40e50f620.jpg,upload_resource/20200429/da68c56a9c4a7d68c6268c44258d6145.jpg', '年后冬奥会打算', 1, 1, 0, '<p>你好安神补脑对啊稍等<img src="http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200429/1588162802249051.jpg" title="1588162802249051.jpg" alt="xub5zyafowl.jpg"/></p>', '2020-04-29 20:20:07', '2020-04-29 21:43:42', 1603350910);
INSERT INTO `sd_test` (`id`,`title`,`cover`,`show_images`,`intro`,`status`,`administrators_id`,`pid`,`content`,`create_time`,`update_time`,`delete_time`) VALUES ( 2, 123123, 'upload_resource/20200507/ecc2de76468d8d5193f5d42a3d12db81.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 123123, 2, 1, 1, '<p><img src="http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200430/1588213392322248.jpg" title="1588213392322248.jpg" alt="白蛇缘起.jpg"/></p>', '2020-04-30 10:23:50', '2020-05-19 11:37:57', 0);
INSERT INTO `sd_test` (`id`,`title`,`cover`,`show_images`,`intro`,`status`,`administrators_id`,`pid`,`content`,`create_time`,`update_time`,`delete_time`) VALUES ( 3, '测试一下图片', 'upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200507/2927d1fef03f197505d4f52f973be2e2.jpg,upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', '你你你', 1, 1, 0, '<p>aS大萨达</p>', '2020-05-11 10:36:03', '2020-05-11 10:36:03', 0);
INSERT INTO `sd_test` (`id`,`title`,`cover`,`show_images`,`intro`,`status`,`administrators_id`,`pid`,`content`,`create_time`,`update_time`,`delete_time`) VALUES ( 4, 'asdsadasd', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20201022/a52fa02852f28bf133a7b825084c0447.jpg,upload_resource/20200806/55939371c868085bbf2828cb963c9d0e.jpg,upload_resource/20200807/74dd9908a44e7ccfc5b18cc80dd283dd.jpg', 'asdasdasd', 1, 1, 3, '<p>asdasd</p>', '2020-05-11 12:05:34', '2020-10-22 12:17:05', 0);

-- sd_test 数据备份结束。

-- sd_user数据结构备份开始 

CREATE TABLE `sd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(128) NOT NULL DEFAULT '' COMMENT '头像',
  `account` varchar(32) NOT NULL DEFAULT '' COMMENT '账号',
  `id_card` char(18) NOT NULL DEFAULT '' COMMENT '身份证号',
  `wx_openid` char(32) NOT NULL DEFAULT '' COMMENT '微信openid',
  `invite_code` varchar(8) NOT NULL DEFAULT '' COMMENT '邀请码',
  `zfb_user_id` char(16) NOT NULL DEFAULT '' COMMENT '支付宝user_id',
  `vip_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额，分',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1=正常，2=封禁，3=注销',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- sd_user数据结构备份完成 

-- sd_user 数据备份开始。

-- 数据份结束 

