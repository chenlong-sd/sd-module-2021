/*
 Navicat Premium Data Transfer

 Source Server         : 本机数据库
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : sd_module

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 25/11/2020 10:26:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sd_administrators
-- ----------------------------
DROP TABLE IF EXISTS `sd_administrators`;
CREATE TABLE `sd_administrators`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `error_number` tinyint(2) NOT NULL DEFAULT 0 COMMENT '密码错误次数',
  `lately_time` datetime(0) NULL DEFAULT NULL COMMENT '最近登录',
  `error_date` date NULL DEFAULT NULL COMMENT '错误日期',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=正常，2=冻结',
  `role_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色',
  `create_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建人',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_administrators
-- ----------------------------
INSERT INTO `sd_administrators` VALUES (1, '超管', 'admin', '$2y$10$d/uL2UnR81Hw3ZuXv4WYS.1UXZv4YchW5mFrjcPk/En1Byjp3QHES', 0, '2020-11-25 09:01:51', '2020-07-09', 1, '0', 1, '2020-11-25 09:01:51', '2020-11-25 09:01:52', 0);

-- ----------------------------
-- Table structure for sd_administrators_role
-- ----------------------------
DROP TABLE IF EXISTS `sd_administrators_role`;
CREATE TABLE `sd_administrators_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `adminisrators_id`(`administrators_id`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sd_category
-- ----------------------------
DROP TABLE IF EXISTS `sd_category`;
CREATE TABLE `sd_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sign` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类标识',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '标识ID',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=正常，2=停用',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sd_log
-- ----------------------------
DROP TABLE IF EXISTS `sd_log`;
CREATE TABLE `sd_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `method` tinyint(1) NOT NULL DEFAULT 1 COMMENT '请求方式：1=GET,2=POST',
  `route_id` int(11) NOT NULL DEFAULT 0 COMMENT '路由ID',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '操作管理员',
  `param` json NOT NULL COMMENT '请求参数',
  `route` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `administrators_id`(`administrators_id`) USING BTREE,
  INDEX `route_id`(`route_id`) USING BTREE,
  INDEX `route`(`route`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台操作日志' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for sd_power
-- ----------------------------
DROP TABLE IF EXISTS `sd_power`;
CREATE TABLE `sd_power`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL DEFAULT 0 COMMENT '权限路由',
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `role_id`(`role_id`, `route_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sd_resource
-- ----------------------------
DROP TABLE IF EXISTS `sd_resource`;
CREATE TABLE `sd_resource`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '类型：1=虚拟文件夹，2=文件',
  `tag` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '上级',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `md5` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'md5值',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `md5`(`md5`) USING BTREE,
  INDEX `tag`(`tag`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '资源表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sd_role
-- ----------------------------
DROP TABLE IF EXISTS `sd_role`;
CREATE TABLE `sd_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色名',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级角色',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色描述',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建角色的管理员',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_role
-- ----------------------------
INSERT INTO `sd_role` VALUES (1, '测试角色1', 0, '测试角色的描述\ntip：字数不要超过255哟', 1, '2020-05-14 12:50:48', '2020-05-14 12:50:48', 0);
INSERT INTO `sd_role` VALUES (2, '测试角色2', 0, 'sc-model', 1, '2020-10-20 18:19:27', '2020-10-20 18:19:27', 0);


-- ----------------------------
-- Table structure for sd_route
-- ----------------------------
DROP TABLE IF EXISTS `sd_route`;
CREATE TABLE `sd_route`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标题',
  `route` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限路由',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级菜单',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1=菜单，2=操作',
  `weigh` int(11) NOT NULL DEFAULT 0 COMMENT '排序权重',
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限路由' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_route
-- ----------------------------
INSERT INTO `sd_route` VALUES (1, '系统设置', '', 0, 1, 0, 'layui-icon-set', '2020-04-23 13:05:17', '2020-04-23 13:05:17', 0);
INSERT INTO `sd_route` VALUES (2, '角色管理', 'System.Role/index', 1, 1, 1, '', '2020-06-18 14:47:49', '2020-06-18 14:47:49', 0);
INSERT INTO `sd_route` VALUES (3, '管理员管理', 'System.Administrators/index', 1, 1, 2, '', '2020-06-18 14:49:17', '2020-06-18 14:49:17', 0);
INSERT INTO `sd_route` VALUES (4, '权限节点管理', 'System.Route/index', 1, 1, 3, '', '2020-06-18 14:50:06', '2020-06-18 14:50:06', 0);
INSERT INTO `sd_route` VALUES (5, '新增管理员', 'System.Administrators/create', 3, 2, 2, 'layui-icon-heart-fill', '2020-06-18 14:49:35', '2020-06-18 14:49:35', 0);
INSERT INTO `sd_route` VALUES (8, '新增角色', 'System.Role/create', 2, 2, 1, '', '2020-06-18 14:48:10', '2020-06-18 14:48:10', 0);
INSERT INTO `sd_route` VALUES (9, '修改角色', 'System.Role/update', 2, 2, 0, '', '2020-06-18 14:48:22', '2020-06-18 14:48:22', 0);
INSERT INTO `sd_route` VALUES (10, '删除角色', 'System.Role/del', 2, 2, 2, '', '2020-06-18 14:53:47', '2020-06-18 14:53:47', 0);
INSERT INTO `sd_route` VALUES (11, '权限设置', 'System.Power/power', 2, 2, 3, '', '2020-05-14 13:09:07', '2020-05-14 13:09:07', 0);
INSERT INTO `sd_route` VALUES (12, '修改管理员', 'System.Administrators/update', 3, 2, 2, '', '2020-06-18 14:49:45', '2020-06-18 14:49:45', 0);
INSERT INTO `sd_route` VALUES (13, '删除管理员', 'System.Administrators/del', 3, 2, 3, '', '2020-05-14 13:11:05', '2020-05-14 13:11:05', 0);
INSERT INTO `sd_route` VALUES (14, '角色数据', 'System.Role/index', 2, 2, 1, '', '2020-06-18 14:49:02', '2020-06-18 14:49:02', 0);
INSERT INTO `sd_route` VALUES (16, '后台操作日志', 'System.Log/index', 1, 1, 4, '', '2020-06-18 14:50:23', '2020-06-18 14:50:23', 0);
INSERT INTO `sd_route` VALUES (28, '管理员数据', 'System.Administrators/index', 3, 2, 1, '', '2020-06-18 14:49:55', '2020-06-18 14:49:55', 0);
INSERT INTO `sd_route` VALUES (30, '测试列表', 'test/index', 1, 1, 1, 'layui-icon-light', '2020-11-24 09:59:08', '2020-11-24 09:59:08', 0);
INSERT INTO `sd_route` VALUES (31, '列表列表数据', 'test/index', 30, 2, 0, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (32, '编辑列表', 'test/update', 30, 2, 1, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (33, '删除列表', 'test/del', 30, 2, 2, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (34, '新增', 'test/create', 30, 2, 1, 'layui-icon-heart-fill', '2020-11-23 19:11:20', '2020-11-23 19:11:20', 0);

-- ----------------------------
-- Table structure for sd_test
-- ----------------------------
DROP TABLE IF EXISTS `sd_test`;
CREATE TABLE `sd_test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '封面',
  `show_images` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '展示图',
  `intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=正常，2=冻结',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '上级',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '详情',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '测试表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_test
-- ----------------------------
INSERT INTO `sd_test` VALUES (1, '测试一下图片', 'upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200507/2927d1fef03f197505d4f52f973be2e2.jpg,upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', '你你你', 1, 1, 0, '<p>aS大萨达</p>', '2020-05-11 10:36:03', '2020-05-11 10:36:03', 0);
INSERT INTO `sd_test` VALUES (2, 'asdsadasd', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20201022/a52fa02852f28bf133a7b825084c0447.jpg,upload_resource/20200806/55939371c868085bbf2828cb963c9d0e.jpg,upload_resource/20200807/74dd9908a44e7ccfc5b18cc80dd283dd.jpg', 'asdasdasd', 1, 1, 3, '<p>asdasd</p>', '2020-05-11 12:05:34', '2020-11-24 15:34:43', 0);

-- ----------------------------
-- Table structure for sd_user
-- ----------------------------
DROP TABLE IF EXISTS `sd_user`;
CREATE TABLE `sd_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
  `id_card` char(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '身份证号',
  `wx_openid` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '微信openid',
  `invite_code` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邀请码',
  `zfb_user_id` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付宝user_id',
  `vip_level` tinyint(1) NOT NULL DEFAULT 0 COMMENT '会员等级',
  `balance` int(11) NOT NULL DEFAULT 0 COMMENT '余额，分',
  `integral` int(11) NOT NULL DEFAULT 0 COMMENT '积分',
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=正常，2=封禁，3=注销',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for sd_data_auth
-- ----------------------------

CREATE TABLE `sd_data_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色',
  `administrators_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员',
  `table_names` varchar(32) NOT NULL DEFAULT '' COMMENT '表名',
  `auth_id` varchar(2048) NOT NULL DEFAULT '' COMMENT '授权数据',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='数据授权表';


-- ----------------------------
-- Table structure for sd_api
-- ----------------------------

CREATE TABLE `sd_api`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_module_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属模块',
  `method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'get' COMMENT '请求类型',
  `api_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '接口名',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路径',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token参数',
  `describe` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '响应示例',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '对接状态：1=未对接，2=已对接',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'api接口表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sd_api_module
-- ----------------------------

CREATE TABLE `sd_api_module`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模块名',
  `url_prefix` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路径前缀',
  `describe` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token参数',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '接口模块' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for sd_query_params
-- ----------------------------

CREATE TABLE `sd_query_params`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL DEFAULT 0,
  `method` tinyint(1) NOT NULL DEFAULT 1 COMMENT '请求参数类型：1=GET,2=POST,3=HEADER',
  `param_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '参数类型：1=文本，2=文件',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '参数名',
  `test_value` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '测试值',
  `describe` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` datetime(0) NOT NULL COMMENT '创建',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '请求参数表' ROW_FORMAT = Dynamic;


SET FOREIGN_KEY_CHECKS = 1;
