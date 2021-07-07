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

 Date: 19/04/2021 17:19:46
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
INSERT INTO `sd_administrators` VALUES (1, '超管', 'admin', '$2y$10$d/uL2UnR81Hw3ZuXv4WYS.1UXZv4YchW5mFrjcPk/En1Byjp3QHES', 0, '2021-04-19 17:19:28', '2020-07-09', 1, '0', 1, '2020-11-26 12:16:32', '2020-11-26 12:16:32', 0);

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
-- Records of sd_administrators_role
-- ----------------------------

-- ----------------------------
-- Table structure for sd_api
-- ----------------------------
DROP TABLE IF EXISTS `sd_api`;
CREATE TABLE `sd_api`  (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `api_module_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属模块',
   `method` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'get' COMMENT '请求类型',
   `api_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '接口名',
   `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路径',
   `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token参数',
   `describe` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
   `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=未对接，2=已对接',
   `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '响应示例',
   `create_time` datetime(0) NOT NULL COMMENT '创建时间',
   `update_time` datetime(0) NOT NULL COMMENT '修改时间',
   `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
   PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'api接口表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_api
-- ----------------------------

-- ----------------------------
-- Table structure for sd_api_module
-- ----------------------------
DROP TABLE IF EXISTS `sd_api_module`;
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '接口模块' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_api_module
-- ----------------------------

-- ----------------------------
-- Table structure for sd_base_config
-- ----------------------------
DROP TABLE IF EXISTS `sd_base_config`;
CREATE TABLE `sd_base_config`  (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `group_id` varchar(32) NOT NULL DEFAULT '' COMMENT '分组标识',
    `group_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分组名称',
    `key_id` varchar(32) NOT NULL DEFAULT '' COMMENT '配置标识',
    `key_name` varchar(32) NOT NULL DEFAULT '' COMMENT '配置名称',
    `form_type` varchar(32) NOT NULL DEFAULT '' COMMENT '表单类型',
    `options` json DEFAULT NULL COMMENT '表单选项值',
    `key_value` text COMMENT '值',
    `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必填：0=否，1=是',
    `placeholder` varchar(32) NOT NULL DEFAULT '' COMMENT 'placeholder',
    `short_tip` varchar(32) NOT NULL DEFAULT '' COMMENT '短标签提示语',
    `create_time` datetime NOT NULL COMMENT '创建时间',
    `update_time` datetime NOT NULL COMMENT '跟新时间',
    `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE KEY `group_id` (`group_id`,`key_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '基本配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_base_config
-- ----------------------------

-- ----------------------------
-- Table structure for egg_dictionary
-- ----------------------------
DROP TABLE IF EXISTS `sd_dictionary`;
CREATE TABLE `sd_dictionary`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '标识ID',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标识名称',
  `dictionary_value` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典值',
  `dictionary_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典名字',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1=正常，2=停用',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of egg_dictionary
-- ----------------------------

-- ----------------------------
-- Table structure for sd_data_auth
-- ----------------------------
DROP TABLE IF EXISTS `sd_data_auth`;
CREATE TABLE `sd_data_auth`  (
     `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
     `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色',
     `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员',
     `table_names` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名',
     `auth_id` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '授权数据',
     `create_time` datetime(0) NOT NULL COMMENT '创建时间',
     `update_time` datetime(0) NOT NULL COMMENT '更新时间',
     `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
     PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '数据授权表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_data_auth
-- ----------------------------

-- ----------------------------
-- Table structure for sd_log
-- ----------------------------
DROP TABLE IF EXISTS `sd_log`;
CREATE TABLE `sd_log`  (
   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
   `method` tinyint(1) NOT NULL DEFAULT 1 COMMENT '请求方式：1=GET,2=POST',
   `route_id` int(11) NOT NULL DEFAULT 0 COMMENT '路由ID',
   `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '操作管理员',
   `open_table` varchar(32) NOT NULL DEFAULT '' COMMENT '开放登录的表',
   `open_id` int(11) NOT NULL DEFAULT '0' COMMENT '开放登录的用户ID',
   `param` json NOT NULL COMMENT '请求参数',
   `route` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
   `create_time` datetime(0) NOT NULL COMMENT '创建时间',
   `update_time` datetime(0) NOT NULL COMMENT '修改时间',
   `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
   PRIMARY KEY (`id`) USING BTREE,
   INDEX `administrators_id`(`administrators_id`) USING BTREE,
   INDEX `route_id`(`route_id`) USING BTREE,
   INDEX `route`(`route`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台操作日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_log
-- ----------------------------

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
-- Records of sd_power
-- ----------------------------

-- ----------------------------
-- Table structure for sd_query_params
-- ----------------------------
DROP TABLE IF EXISTS `sd_query_params`;
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '请求参数表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_query_params
-- ----------------------------

-- ----------------------------
-- Table structure for sd_resource
-- ----------------------------
DROP TABLE IF EXISTS `sd_resource`;
CREATE TABLE `sd_resource`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '类型：1=虚拟文件夹，2=文件',
  `tag` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标签',
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
-- Records of sd_resource
-- ----------------------------

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
  `assign_table` varchar(32) NOT NULL DEFAULT '' COMMENT '该角色对指定表生效，用户开放表登录后台',
  `open_table` varchar(32) NOT NULL DEFAULT '' COMMENT '创建角色的开放表信息',
  `open_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建角色的开放表ID',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_role
-- ----------------------------

-- ----------------------------
-- Table structure for sd_route
-- ----------------------------
DROP TABLE IF EXISTS `sd_route`;
CREATE TABLE `sd_route`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标题',
  `route` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限路由',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级菜单',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1=菜单，2=操作',
  `weigh` int(11) NOT NULL DEFAULT 0 COMMENT '排序权重',
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限路由' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_route
-- ----------------------------
INSERT INTO `sd_route` VALUES (1, '系统设置', '', 0, 1, 0, 'layui-icon-set', '2020-04-23 13:05:17', '2020-04-23 13:05:17', 0);
INSERT INTO `sd_route` VALUES (2, '角色管理', 'system.Role/index', 1, 1, 1, '', '2020-06-18 14:47:49', '2020-06-18 14:47:49', 0);
INSERT INTO `sd_route` VALUES (3, '管理员管理', 'system.Administrators/index', 1, 1, 2, '', '2020-06-18 14:49:17', '2020-06-18 14:49:17', 0);
INSERT INTO `sd_route` VALUES (4, '权限节点管理', 'system.Route/index', 1, 1, 3, '', '2020-06-18 14:50:06', '2021-03-01 11:18:56', 0);
INSERT INTO `sd_route` VALUES (5, '新增管理员', 'system.Administrators/create', 3, 2, 2, 'layui-icon-heart-fill', '2020-06-18 14:49:35', '2020-06-18 14:49:35', 0);
INSERT INTO `sd_route` VALUES (7, '测试表', 'Test/index', 1, 1, 9, '', '2020-11-24 09:58:44', '2020-11-24 09:58:44', 1606183124);
INSERT INTO `sd_route` VALUES (8, '新增角色', 'system.Role/create', 2, 2, 1, '', '2020-06-18 14:48:10', '2020-06-18 14:48:10', 0);
INSERT INTO `sd_route` VALUES (9, '修改角色', 'system.Role/update', 2, 2, 0, '', '2020-06-18 14:48:22', '2020-06-18 14:48:22', 0);
INSERT INTO `sd_route` VALUES (10, '删除角色', 'system.Role/del', 2, 2, 2, '', '2020-06-18 14:53:47', '2020-06-18 14:53:47', 0);
INSERT INTO `sd_route` VALUES (11, '权限设置', 'system.Power/power', 2, 2, 3, '', '2020-05-14 13:09:07', '2020-05-14 13:09:07', 0);
INSERT INTO `sd_route` VALUES (12, '修改管理员', 'system.Administrators/update', 3, 2, 2, '', '2020-06-18 14:49:45', '2020-06-18 14:49:45', 0);
INSERT INTO `sd_route` VALUES (13, '删除管理员', 'system.Administrators/del', 3, 2, 3, '', '2020-05-14 13:11:05', '2020-05-14 13:11:05', 0);
INSERT INTO `sd_route` VALUES (14, '角色数据', 'system.Role/index', 2, 2, 1, '', '2020-06-18 14:49:02', '2020-06-18 14:49:02', 0);
INSERT INTO `sd_route` VALUES (16, '后台操作日志', 'system.Log/index', 1, 1, 4, '', '2020-06-18 14:50:23', '2020-06-18 14:50:23', 0);
INSERT INTO `sd_route` VALUES (28, '管理员数据', 'system.Administrators/index', 3, 2, 1, '', '2020-06-18 14:49:55', '2020-06-18 14:49:55', 0);
INSERT INTO `sd_route` VALUES (29, '新增', 'Test/create', 7, 2, 26, '', '2020-11-24 09:58:36', '2020-11-24 09:58:36', 1606183116);
INSERT INTO `sd_route` VALUES (30, '测试列表', 'test/index', 1, 1, 1, 'layui-icon-light', '2020-11-24 09:59:08', '2020-11-24 09:59:08', 0);
INSERT INTO `sd_route` VALUES (31, '列表列表数据', 'test/index', 30, 2, 0, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (32, '编辑列表', 'test/update', 30, 2, 1, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (33, '删除列表', 'test/del', 30, 2, 2, '', '2020-11-23 19:09:18', '2020-11-23 19:09:18', 0);
INSERT INTO `sd_route` VALUES (34, '新增', 'test/create', 30, 2, 1, 'layui-icon-heart-fill', '2020-11-23 19:11:20', '2020-11-23 19:11:20', 0);
INSERT INTO `sd_route` VALUES (35, '基础信息设置', 'system.System/basicInformationSet', 1, 1, 6, '', '2021-03-30 19:47:46', '2021-03-30 19:50:04', 0);
INSERT INTO `sd_route` VALUES (36, '字典管理', 'system.dictionary/index', 1, 1, 7, '', '2021-05-06 21:56:38', '2021-05-06 21:56:38', 0);
INSERT INTO `sd_route` VALUES (37, '字典管理列表数据', 'system.dictionary/index', 36, 2, 0, '', '2021-05-06 21:56:38', '2021-05-06 21:56:38', 0);
INSERT INTO `sd_route` VALUES (38, '新增字典管理', 'system.dictionary/create', 36, 2, 1, '', '2021-05-06 21:56:38', '2021-05-06 21:56:38', 0);
INSERT INTO `sd_route` VALUES (39, '编辑字典管理', 'system.dictionary/update', 36, 2, 2, '', '2021-05-06 21:56:38', '2021-05-06 21:56:38', 0);
INSERT INTO `sd_route` VALUES (40, '删除字典管理', 'system.dictionary/del', 36, 2, 3, '', '2021-05-06 21:56:38', '2021-05-06 21:56:38', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '测试表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_test
-- ----------------------------
INSERT INTO `sd_test` VALUES (1, '你好你好', 'upload_resource/20200429/6fba04f2044bb0c5eb969003f95c5d93.jpg', 'upload_resource/20200429/7f82a960b6c217b6f8246ed3d20d9e3e.jpg,upload_resource/20200429/306d40a286538033d5d4f4e40e50f620.jpg,upload_resource/20200429/da68c56a9c4a7d68c6268c44258d6145.jpg', '年后冬奥会打算', 1, 1, 0, '<p>你好安神补脑对啊稍等<img src=\"http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200429/1588162802249051.jpg\" title=\"1588162802249051.jpg\" alt=\"xub5zyafowl.jpg\"/></p>', '2020-04-29 20:20:07', '2020-04-29 21:43:42', 1603350910);
INSERT INTO `sd_test` VALUES (2, '123123', 'upload_resource/20200507/ecc2de76468d8d5193f5d42a3d12db81.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', '123123', 2, 1, 1, '<p><img src=\"http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200430/1588213392322248.jpg\" title=\"1588213392322248.jpg\" alt=\"白蛇缘起.jpg\"/></p>', '2020-04-30 10:23:50', '2020-05-19 11:37:57', 1604914901);
INSERT INTO `sd_test` VALUES (3, '测试一下图片', 'upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200507/2927d1fef03f197505d4f52f973be2e2.jpg,upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', '你你你', 1, 1, 0, '<p>aS大萨达</p>', '2020-05-11 10:36:03', '2020-05-11 10:36:03', 0);
INSERT INTO `sd_test` VALUES (4, 'asdsadasd', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg,upload_resource/20201022/a52fa02852f28bf133a7b825084c0447.jpg,upload_resource/20200806/55939371c868085bbf2828cb963c9d0e.jpg,upload_resource/20200807/74dd9908a44e7ccfc5b18cc80dd283dd.jpg', 'asdasdasd', 1, 1, 3, '<p>asdasd</p>', '2020-05-11 12:05:34', '2020-12-23 15:42:19', 0);

-- ----------------------------
-- Table structure for sd_user
-- ----------------------------
DROP TABLE IF EXISTS `sd_user`;
CREATE TABLE `sd_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '上级',
  `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
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

SET FOREIGN_KEY_CHECKS = 1;
