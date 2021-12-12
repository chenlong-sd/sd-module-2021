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

 Date: 12/12/2021 19:17:52
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
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `account`(`account`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_administrators
-- ----------------------------
INSERT INTO `sd_administrators` VALUES (1, '超管', 'admin', '$2y$10$BoKXiIvr9ebwDBHWAaIliub5Xk.D8c4u5sNKgQKqcWxYoaO5Ppzzq', 0, '2021-12-12 17:18:22', '2020-07-09', 1, '0', 1, '2020-11-26 12:16:32', '2021-12-12 17:18:22', 0);
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'api接口表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_api
-- ----------------------------
INSERT INTO `sd_api` VALUES (1, 1, 'get', 'test', 'ee', 'ee', '', 1, 'qqqsadasd&nbsp;', '2021-09-24 17:22:18', '2021-11-09 15:44:19', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '接口模块' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_api_module
-- ----------------------------
INSERT INTO `sd_api_module` VALUES (1, 'test', 'api|-|api1', '1111', '1111', '2021-09-24 17:20:04', '2021-11-09 15:16:07', 0);
INSERT INTO `sd_api_module` VALUES (2, 'test1', '22', '1', '1', '2021-11-09 15:16:58', '2021-11-09 15:16:58', 0);

-- ----------------------------
-- Table structure for sd_base_config
-- ----------------------------
DROP TABLE IF EXISTS `sd_base_config`;
CREATE TABLE `sd_base_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分组标识',
  `group_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `key_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '配置标识',
  `key_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `form_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表单类型',
  `options` json NULL COMMENT '表单选项值',
  `key_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '值',
  `required` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否必填：0=否，1=是',
  `placeholder` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'placeholder',
  `short_tip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '短标签提示语',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序值',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '跟新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `group_id`(`group_id`, `key_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '基本配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_base_config
-- ----------------------------
INSERT INTO `sd_base_config` VALUES (1, 'default_group', '默认分组', 'test', 'test', 'images', NULL, 'upload_resource/20211126/7521a0c55352b8ec395ddec913683c34.jpg', 1, 'ttttttt', '', 1, '2021-11-09 15:58:03', '2021-11-26 02:11:48', 0);

-- ----------------------------
-- Table structure for sd_data_auth
-- ----------------------------
DROP TABLE IF EXISTS `sd_data_auth`;
CREATE TABLE `sd_data_auth`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT 0 COMMENT '角色',
  `table_names` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名',
  `auth_id` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '授权数据',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '数据授权表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for sd_dictionary_content
-- ----------------------------
DROP TABLE IF EXISTS `sd_dictionary_content`;
CREATE TABLE `sd_dictionary_content`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `new_dictionary_id` int(11) NOT NULL DEFAULT 0 COMMENT '所属字典',
  `dictionary_content` json NOT NULL COMMENT '字典内容',
  `search` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '搜索字段',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序字段',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `new_dictionary_id`(`new_dictionary_id`, `search`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '字典内容' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_dictionary_content
-- ----------------------------
INSERT INTO `sd_dictionary_content` VALUES (1, 1, '{\"te\": \"1\", \"ttt\": [\"1\"], \"test\": \"test\", \"iamge\": \"upload_resource/20211126/7521a0c55352b8ec395ddec913683c34.jpg\"}', 'test', 1, '2021-11-26 23:55:02', '2021-11-30 15:54:26', 0);
INSERT INTO `sd_dictionary_content` VALUES (2, 1, '{\"te\": \"12\", \"test\": \"tt\", \"iamge\": \"\"}', 'tt', 12, '2021-11-27 00:23:12', '2021-11-30 15:57:07', 0);
INSERT INTO `sd_dictionary_content` VALUES (3, 2, '{\"name\": \"123333\", \"value\": \"123\"}', '', 0, '2021-11-27 01:00:09', '2021-12-12 17:41:14', 0);
INSERT INTO `sd_dictionary_content` VALUES (4, 1, '{\"cc\": \"<p>asfsdfsdfsfdsdfsdfsdf</p><p>sadf</p><p>sdf</p><p>sf</p><p>sdf</p><p>sd</p><p>f</p><p>sf</p><p>sf</p><p>sd</p><p>f</p><p>sf</p><p>sd</p><p>f</p><p>sf</p><p>s</p><p>df</p><p>sdf</p><p>sd</p><p>f</p>\", \"te\": \"\", \"ttt\": [\"1\", \"2\"], \"test\": \"\", \"iamge\": \"\"}', '', 0, '2021-12-09 11:45:35', '2021-12-11 18:27:56', 0);

-- ----------------------------
-- Table structure for sd_log
-- ----------------------------
DROP TABLE IF EXISTS `sd_log`;
CREATE TABLE `sd_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `method` tinyint(1) NOT NULL DEFAULT 1 COMMENT '请求方式：1=GET,2=POST',
  `route_id` int(11) NOT NULL DEFAULT 0 COMMENT '路由ID',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '操作管理员',
  `open_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '开放登录的表',
  `open_id` int(11) NOT NULL DEFAULT 0 COMMENT '开放登录的用户ID',
  `param` json NOT NULL COMMENT '请求参数',
  `route` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `administrators_id`(`administrators_id`) USING BTREE,
  INDEX `route_id`(`route_id`) USING BTREE,
  INDEX `route`(`route`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '后台操作日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_log
-- ----------------------------


-- ----------------------------
-- Table structure for sd_new_dictionary
-- ----------------------------
DROP TABLE IF EXISTS `sd_new_dictionary`;
CREATE TABLE `sd_new_dictionary`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1=常规，2=增强',
  `sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典标识ID',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典名称',
  `image` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '增强状态下的图片',
  `introduce` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '增强状态下的简介',
  `customize` json NULL COMMENT '增强状态下的字段配置',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '新字典表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_new_dictionary
-- ----------------------------
INSERT INTO `sd_new_dictionary` VALUES (1, 2, 'test2', '测试', '', 'test', '[{\"d_key\": \"test\", \"d_type\": \"text\", \"d_title\": \"测试\", \"d_search\": [\"1\"], \"d_options\": \"\"}, {\"d_key\": \"iamge\", \"d_type\": \"image\", \"d_title\": \"测试图\", \"d_options\": \"\"}, {\"d_key\": \"te\", \"d_type\": \"textarea\", \"d_title\": \"xia\", \"d_search\": {\"1\": \"2\"}, \"d_options\": \"\"}, {\"d_key\": \"ttt\", \"d_type\": \"checkbox\", \"d_title\": \"选择\", \"d_options\": \"1=正常，2=禁用\"}, {\"d_key\": \"cc\", \"d_type\": \"uEditor\", \"d_title\": \"内容\", \"d_options\": \"\"}]', '2021-11-26 16:38:40', '2021-12-11 18:27:41', 0);
INSERT INTO `sd_new_dictionary` VALUES (2, 1, 'test', '常规', '', '', '[]', '2021-11-27 00:23:52', '2021-11-27 00:23:52', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限记录' ROW_FORMAT = DYNAMIC;


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
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '请求参数表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_query_params
-- ----------------------------
INSERT INTO `sd_query_params` VALUES (1, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:40:24', '2021-11-09 15:40:24', 1636443624);
INSERT INTO `sd_query_params` VALUES (2, 1, 1, 1, 'ss', 'ss', '', '2021-11-09 15:40:24', '2021-11-09 15:40:24', 1636443624);
INSERT INTO `sd_query_params` VALUES (3, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:41:10', '2021-11-09 15:41:10', 1636443670);
INSERT INTO `sd_query_params` VALUES (4, 1, 1, 1, 'ss', 'ss', '', '2021-11-09 15:41:10', '2021-11-09 15:41:10', 1636443670);
INSERT INTO `sd_query_params` VALUES (5, 1, 1, 1, 'asd', 'asd', '', '2021-11-09 15:41:10', '2021-11-09 15:41:10', 1636443670);
INSERT INTO `sd_query_params` VALUES (6, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:41:19', '2021-11-09 15:41:19', 1636443679);
INSERT INTO `sd_query_params` VALUES (7, 1, 1, 1, 'ss', 'ss', '', '2021-11-09 15:41:19', '2021-11-09 15:41:19', 1636443679);
INSERT INTO `sd_query_params` VALUES (8, 1, 1, 1, 'asd', 'asd', '', '2021-11-09 15:41:19', '2021-11-09 15:41:19', 1636443679);
INSERT INTO `sd_query_params` VALUES (9, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:43:48', '2021-11-09 15:43:48', 1636443828);
INSERT INTO `sd_query_params` VALUES (10, 1, 1, 1, 'ss', 'ss', '', '2021-11-09 15:43:48', '2021-11-09 15:43:48', 1636443828);
INSERT INTO `sd_query_params` VALUES (11, 1, 1, 1, 'asd', 'asd', '', '2021-11-09 15:43:48', '2021-11-09 15:43:48', 1636443828);
INSERT INTO `sd_query_params` VALUES (12, 1, 1, 1, '11', '11', '', '2021-11-09 15:43:48', '2021-11-09 15:43:48', 1636443828);
INSERT INTO `sd_query_params` VALUES (13, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:44:19', '2021-11-09 15:44:19', 1636443859);
INSERT INTO `sd_query_params` VALUES (14, 1, 2, 1, 'ss', 'ss', '', '2021-11-09 15:44:19', '2021-11-09 15:44:19', 1636443859);
INSERT INTO `sd_query_params` VALUES (15, 1, 1, 1, 'aa', 'aa', '', '2021-11-09 15:44:19', '2021-11-09 15:44:19', 0);
INSERT INTO `sd_query_params` VALUES (16, 1, 2, 1, 'ss', 'ss', '', '2021-11-09 15:44:19', '2021-11-09 15:44:19', 0);
INSERT INTO `sd_query_params` VALUES (17, 1, 3, 1, '44', '44', '', '2021-11-09 15:44:19', '2021-11-09 15:44:19', 0);

-- ----------------------------
-- Table structure for sd_quick_operation
-- ----------------------------
DROP TABLE IF EXISTS `sd_quick_operation`;
CREATE TABLE `sd_quick_operation`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `route_id` int(11) NOT NULL DEFAULT 0 COMMENT '节点',
  `is_show` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否展示：1=是，2=否',
  `administrators_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员',
  `open_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '开放登录表',
  `coordinate` int(11) NOT NULL DEFAULT 0 COMMENT '坐标',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `administrators_id`(`administrators_id`, `route_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '快捷操作' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sd_quick_operation
-- ----------------------------
INSERT INTO `sd_quick_operation` VALUES (1, 115, 1, 1, '', 3, '2021-12-03 21:58:00', '2021-12-03 22:30:44', 0);
INSERT INTO `sd_quick_operation` VALUES (2, 120, 1, 1, '', 28, '2021-12-03 22:01:43', '2021-12-03 22:24:31', 0);
INSERT INTO `sd_quick_operation` VALUES (3, 122, 1, 1, '', 12, '2021-12-03 22:01:49', '2021-12-06 22:02:14', 0);
INSERT INTO `sd_quick_operation` VALUES (4, 181, 1, 1, '', 23, '2021-12-03 22:01:56', '2021-12-10 15:32:18', 0);
INSERT INTO `sd_quick_operation` VALUES (5, 119, 1, 1, '', 0, '2021-12-03 22:22:12', '2021-12-03 22:23:05', 0);
INSERT INTO `sd_quick_operation` VALUES (6, 146, 1, 1, '', 10, '2021-12-03 22:22:15', '2021-12-03 22:22:30', 0);
INSERT INTO `sd_quick_operation` VALUES (7, 121, 1, 1, '', 4, '2021-12-03 22:22:25', '2021-12-03 22:24:19', 0);
INSERT INTO `sd_quick_operation` VALUES (8, 121, 1, 33, '', 10, '2021-12-03 23:13:37', '2021-12-03 23:13:51', 0);
INSERT INTO `sd_quick_operation` VALUES (9, 124, 1, 33, '', 20, '2021-12-03 23:13:40', '2021-12-03 23:13:54', 0);
INSERT INTO `sd_quick_operation` VALUES (10, 146, 1, 33, '', 3, '2021-12-03 23:13:41', '2021-12-03 23:13:43', 0);
INSERT INTO `sd_quick_operation` VALUES (11, 165, 1, 1, '', 2, '2021-12-03 23:19:01', '2021-12-03 23:19:13', 0);
INSERT INTO `sd_quick_operation` VALUES (12, 206, 1, 1, '', 1, '2021-12-04 11:48:15', '2021-12-04 11:48:41', 0);
INSERT INTO `sd_quick_operation` VALUES (13, 211, 1, 1, '', 32, '2021-12-06 12:54:55', '2021-12-10 15:32:18', 0);
INSERT INTO `sd_quick_operation` VALUES (14, 200, 1, 1, '', 5, '2021-12-07 14:09:01', '2021-12-07 14:09:03', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '资源表' ROW_FORMAT = DYNAMIC;


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
  `assign_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '该角色对指定表生效，用户开放表登录后台',
  `open_table` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '创建角色的人员的开放表信息',
  `open_id` int(11) NOT NULL DEFAULT 0 COMMENT '创建角色的的人员的开放表ID',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for sd_route
-- ----------------------------
DROP TABLE IF EXISTS `sd_route`;
CREATE TABLE `sd_route`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标题',
  `route` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限路由',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级菜单',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1=左侧菜单，2=顶部菜单，3=节点',
  `weigh` int(11) NOT NULL DEFAULT 0 COMMENT '排序权重',
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` datetime(0) NOT NULL COMMENT '创建时间',
  `update_time` datetime(0) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 212 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限路由' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_route
-- ----------------------------
INSERT INTO `sd_route` VALUES (1, '系统设置', '', 0, 1, 0, 'layui-icon-set', '2020-04-23 13:05:17', '2020-04-23 13:05:17', 0);
INSERT INTO `sd_route` VALUES (115, '系统管理员', 'system.Administrators/index', 1, 1, 1, '', '2021-11-16 14:14:46', '2021-12-03 20:58:12', 0);
INSERT INTO `sd_route` VALUES (116, '接口管理', '', 1, 3, 12, '', '2021-11-16 14:14:46', '2021-12-03 16:30:47', 0);
INSERT INTO `sd_route` VALUES (117, '接口模块', '', 116, 3, 6, '', '2021-11-16 14:14:46', '2021-11-29 14:49:53', 0);
INSERT INTO `sd_route` VALUES (118, '字典管理', 'system.Dictionary/index', 1, 1, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:18:45', 1637947962);
INSERT INTO `sd_route` VALUES (119, '杂项', '', 0, 3, 5, '', '2021-11-16 14:14:46', '2021-12-03 23:25:10', 0);
INSERT INTO `sd_route` VALUES (120, '系统日志', 'system.Log/index', 1, 1, 6, '', '2021-11-16 14:14:46', '2021-12-03 17:27:04', 0);
INSERT INTO `sd_route` VALUES (121, '系统管理员角色', 'system.Role/index', 1, 1, 2, 'layui-icon-user', '2021-11-16 14:14:46', '2021-12-03 17:27:04', 0);
INSERT INTO `sd_route` VALUES (122, '系统权限节点', 'system.Route/index', 1, 1, 8, '', '2021-11-16 14:14:46', '2021-12-03 17:27:05', 0);
INSERT INTO `sd_route` VALUES (123, '系统工具箱', '', 1, 3, 13, '', '2021-11-16 14:14:46', '2021-11-29 14:52:54', 0);
INSERT INTO `sd_route` VALUES (124, '测试表', 'Test/index', 1, 1, 10, '', '2021-11-16 14:14:46', '2021-12-03 17:27:05', 1638582570);
INSERT INTO `sd_route` VALUES (125, '列表数据', 'system.Administrators/index', 115, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (126, '数据创建', 'system.Administrators/create', 115, 3, 2, '', '2021-11-16 14:14:46', '2021-12-03 20:58:10', 0);
INSERT INTO `sd_route` VALUES (127, '数据更新', 'system.Administrators/update', 115, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (128, '数据删除', 'system.Administrators/delete', 115, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (129, '状态更新', 'system.Administrators/switchHandle', 115, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (130, '修改密码', 'system.Administrators/passwordUpdate', 115, 3, 6, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (131, '接口列表', 'system.Api/index', 116, 3, 1, '', '2021-11-16 14:14:46', '2021-12-03 17:27:47', 0);
INSERT INTO `sd_route` VALUES (132, '接口新增', 'system.Api/create', 116, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (133, '接口更新', 'system.Api/update', 116, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (134, '保存api数据', 'system.Api/save', 116, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (135, '改接口为已对接', 'system.Api/docking', 116, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (136, '新增api模块', 'system.ApiModule/create', 117, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (137, '修改api模块', 'system.ApiModule/update', 117, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (138, '删除api模块', 'system.ApiModule/delete', 117, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (139, '所有字典', 'system.Dictionary/index', 118, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (140, '新增字典', 'system.Dictionary/create', 118, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (141, '修改字典', 'system.Dictionary/update', 118, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (142, '字典删除', 'system.Dictionary/delete', 118, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (143, '字典配置页面', 'system.Dictionary/dictionary', 118, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (144, '字典值新增', 'system.Dictionary/dictionaryAdd', 118, 3, 6, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (145, '字典值修改', 'system.Dictionary/dictionaryEdit', 118, 3, 7, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1637947962);
INSERT INTO `sd_route` VALUES (146, '小游戏', 'system.Index/game', 119, 3, 1, '', '2021-11-16 14:14:46', '2021-12-03 17:27:43', 0);
INSERT INTO `sd_route` VALUES (147, '日志列表', 'system.Log/index', 120, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (148, '所有角色列表', 'system.Role/index', 121, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (149, '新增角色', 'system.Role/create', 121, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (150, '修改角色', 'system.Role/update', 121, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (151, '删除角色', 'system.Role/delete', 121, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (152, '权限设置', 'system.Role/powerSet', 121, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (153, '权限树新数据获取', 'system.Role/getPowerTreeData', 121, 3, 6, '', '2021-11-16 14:14:46', '2021-11-16 14:32:47', 0);
INSERT INTO `sd_route` VALUES (154, '路由列表', 'system.Route/index', 122, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (155, '删除路由', 'system.Route/delete', 122, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (156, '创建路由', 'system.Route/create', 122, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (157, '更新路由', 'system.Route/update', 122, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (158, '获取用户可访问菜单', 'system.Route/getNode', 122, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:35:24', 0);
INSERT INTO `sd_route` VALUES (159, '自动检测权限', 'system.Route/automaticDetection', 122, 3, 6, '', '2021-11-16 14:14:46', '2021-11-16 14:33:18', 0);
INSERT INTO `sd_route` VALUES (160, '本地资源数据列表', 'system.System/resource', 123, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (161, '开始备份数据', 'system.System/backUp', 123, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (162, '查看备份文件', 'system.System/viewBackupFiles', 123, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (163, '数据恢复', 'system.System/dataRecover', 123, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (164, '删除备份文件', 'system.System/backUpDelete', 123, 3, 5, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 0);
INSERT INTO `sd_route` VALUES (165, '基础信息设置', 'system.System/basicInformationSet', 1, 1, 6, '', '2021-11-16 14:14:46', '2021-12-03 17:27:04', 0);
INSERT INTO `sd_route` VALUES (166, '基础信息配置（组页面', 'system.System/baseConfig', 165, 3, 7, '', '2021-11-16 14:14:46', '2021-11-16 14:22:50', 0);
INSERT INTO `sd_route` VALUES (167, '删除基础信息设置', 'system.System/deleteConfig', 165, 3, 8, '', '2021-11-16 14:14:46', '2021-11-16 14:22:59', 0);
INSERT INTO `sd_route` VALUES (168, '列表数据', 'Test/index', 124, 3, 1, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1638582570);
INSERT INTO `sd_route` VALUES (169, '数据创建', 'Test/create', 124, 3, 2, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1638582570);
INSERT INTO `sd_route` VALUES (170, '数据更新', 'Test/update', 124, 3, 3, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1638582570);
INSERT INTO `sd_route` VALUES (171, '数据删除', 'Test/delete', 124, 3, 4, '', '2021-11-16 14:14:46', '2021-11-16 14:14:46', 1638582570);
INSERT INTO `sd_route` VALUES (172, '字典内容', '', 181, 3, 10, '', '2021-11-24 23:30:39', '2021-12-02 15:19:18', 0);
INSERT INTO `sd_route` VALUES (173, '列表数据', 'system.DictionaryContent/index', 172, 3, 1, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 0);
INSERT INTO `sd_route` VALUES (174, '数据创建', 'system.DictionaryContent/create', 172, 3, 2, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 0);
INSERT INTO `sd_route` VALUES (175, '数据更新', 'system.DictionaryContent/update', 172, 3, 3, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 0);
INSERT INTO `sd_route` VALUES (176, '数据删除', 'system.DictionaryContent/delete', 172, 3, 4, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 0);
INSERT INTO `sd_route` VALUES (177, '列表数据', 'system.NewDictionary/index', 1, 3, 11, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 1637767866);
INSERT INTO `sd_route` VALUES (178, '数据创建', 'system.NewDictionary/create', 1, 3, 12, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 1637767874);
INSERT INTO `sd_route` VALUES (179, '数据更新', 'system.NewDictionary/update', 1, 3, 13, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 1637767878);
INSERT INTO `sd_route` VALUES (180, '数据删除', 'system.NewDictionary/delete', 1, 3, 14, '', '2021-11-24 23:30:39', '2021-11-24 23:30:39', 1637767882);
INSERT INTO `sd_route` VALUES (181, '新字典表', 'system.NewDictionary/index', 1, 1, 11, '', '2021-11-24 23:31:28', '2021-12-03 17:27:06', 0);
INSERT INTO `sd_route` VALUES (182, '列表数据', 'system.NewDictionary/index', 181, 3, 1, '', '2021-11-24 23:31:28', '2021-11-24 23:31:28', 0);
INSERT INTO `sd_route` VALUES (183, '数据创建', 'system.NewDictionary/create', 181, 3, 2, '', '2021-11-24 23:31:28', '2021-11-24 23:31:28', 0);
INSERT INTO `sd_route` VALUES (184, '数据更新', 'system.NewDictionary/update', 181, 3, 3, '', '2021-11-24 23:31:28', '2021-11-24 23:31:28', 0);
INSERT INTO `sd_route` VALUES (185, '数据删除', 'system.NewDictionary/delete', 181, 3, 4, '', '2021-11-24 23:31:28', '2021-11-24 23:31:28', 0);
INSERT INTO `sd_route` VALUES (186, '请求日志详情', 'system.Log/detail', 120, 3, 2, '', '2021-11-29 14:50:23', '2021-11-29 14:50:23', 0);
INSERT INTO `sd_route` VALUES (187, ' 测试表 ', '', 0, 3, 6, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (188, '测试表列表', 'Test/index', 187, 3, 1, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (189, '新增测试表', 'Test/create', 187, 3, 2, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (190, '更新测试表', 'Test/update', 187, 3, 3, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (191, '删除测试表', 'Test/delete', 187, 3, 4, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (192, '测试表状态更新', 'Test/switchHandle', 187, 3, 5, '', '2021-12-04 10:38:05', '2021-12-04 10:38:05', 1638585572);
INSERT INTO `sd_route` VALUES (193, '测试表', 'Test/index', 1, 1, 11, '', '2021-12-04 10:39:51', '2021-12-04 10:41:11', 1638587194);
INSERT INTO `sd_route` VALUES (194, '测试表列表', 'Test/index', 193, 3, 1, '', '2021-12-04 10:39:51', '2021-12-04 10:39:51', 1638587194);
INSERT INTO `sd_route` VALUES (195, '新增测试表', 'Test/create', 193, 3, 2, '', '2021-12-04 10:39:51', '2021-12-04 10:39:51', 1638587194);
INSERT INTO `sd_route` VALUES (196, '更新测试表', 'Test/update', 193, 3, 3, '', '2021-12-04 10:39:51', '2021-12-04 10:39:51', 1638587194);
INSERT INTO `sd_route` VALUES (197, '删除测试表', 'Test/delete', 193, 3, 4, '', '2021-12-04 10:39:51', '2021-12-04 10:39:51', 1638587194);
INSERT INTO `sd_route` VALUES (198, '测试表状态更新', 'Test/switchHandle', 193, 3, 5, '', '2021-12-04 10:39:51', '2021-12-04 10:39:51', 1638587194);
INSERT INTO `sd_route` VALUES (199, ' 测试表 ', '', 0, 3, 6, '', '2021-12-04 11:26:52', '2021-12-04 11:26:52', 0);
INSERT INTO `sd_route` VALUES (200, '测试表列表', 'Test/index', 199, 3, 11, 'layui-icon-heart-fill', '2021-12-04 11:26:52', '2021-12-11 18:01:57', 0);
INSERT INTO `sd_route` VALUES (201, '新增测试表', 'Test/create', 199, 3, 2, '', '2021-12-04 11:26:52', '2021-12-04 11:26:52', 0);
INSERT INTO `sd_route` VALUES (202, '更新测试表', 'Test/update', 199, 3, 3, '', '2021-12-04 11:26:52', '2021-12-04 11:26:52', 0);
INSERT INTO `sd_route` VALUES (203, '删除测试表', 'Test/delete', 199, 3, 4, '', '2021-12-04 11:26:52', '2021-12-04 11:26:52', 0);
INSERT INTO `sd_route` VALUES (204, '测试表状态更新', 'Test/switchHandle', 199, 3, 5, '', '2021-12-04 11:26:52', '2021-12-04 11:26:52', 0);
INSERT INTO `sd_route` VALUES (205, '用户表', 'User/index', 1, 1, 7, '', '2021-12-04 11:47:48', '2021-12-04 11:48:40', 1639307781);
INSERT INTO `sd_route` VALUES (206, '用户表列表', 'User/index', 205, 3, 1, '', '2021-12-04 11:47:48', '2021-12-04 11:47:48', 1639307781);
INSERT INTO `sd_route` VALUES (207, '新增用户表', 'User/create', 205, 3, 2, '', '2021-12-04 11:47:48', '2021-12-04 11:47:48', 1639307781);
INSERT INTO `sd_route` VALUES (208, '更新用户表', 'User/update', 205, 3, 3, '', '2021-12-04 11:47:48', '2021-12-04 11:47:48', 1639307781);
INSERT INTO `sd_route` VALUES (209, '删除用户表', 'User/delete', 205, 3, 4, '', '2021-12-04 11:47:48', '2021-12-04 11:47:48', 1639307781);
INSERT INTO `sd_route` VALUES (210, '用户表状态更新', 'User/switchHandle', 205, 3, 5, '', '2021-12-04 11:47:48', '2021-12-04 11:47:48', 1639307781);
INSERT INTO `sd_route` VALUES (211, '代码高亮显示', 'system.System/codeMirror', 123, 3, 6, '', '2021-12-06 12:54:38', '2021-12-06 12:54:38', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '测试表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_test
-- ----------------------------
INSERT INTO `sd_test` VALUES (1, '你好你好', 'upload_resource/20200429/6fba04f2044bb0c5eb969003f95c5d93.jpg', 'upload_resource/20200429/7f82a960b6c217b6f8246ed3d20d9e3e.jpg,upload_resource/20200429/306d40a286538033d5d4f4e40e50f620.jpg,upload_resource/20200429/da68c56a9c4a7d68c6268c44258d6145.jpg', '年后冬奥会打算', 1, 1, 0, '<p>你好安神补脑对啊稍等<img src=\"http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200429/1588162802249051.jpg\" title=\"1588162802249051.jpg\" alt=\"xub5zyafowl.jpg\"/></p>', '2020-04-29 20:20:07', '2020-04-29 21:43:42', 0);
INSERT INTO `sd_test` VALUES (2, '123123', 'upload_resource/20200507/ecc2de76468d8d5193f5d42a3d12db81.jpg', 'upload_resource/20200506/fd8551e0e6b688d07ad7f88fc852fb62.jpg,upload_resource/20200507/a698be506aa366e43641b638fcabb0ab.jpg,upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', '123123', 2, 1, 1, '<p><img src=\"http://192.168.5.11/sd-module/public//admin_resource/ueditor/image/20200430/1588213392322248.jpg\" title=\"1588213392322248.jpg\" alt=\"白蛇缘起.jpg\"/></p>', '2020-04-30 10:23:50', '2021-12-09 18:33:59', 1639057468);
INSERT INTO `sd_test` VALUES (3, '测试一下图片', 'upload_resource/20200506/1aca73e97e2bff537df22b2046e02cd0.jpg', 'upload_resource/20211019/a1bc430007071f80bf37671e95a00533.png,upload_resource/20211019/9710f717dfded51ed5b1c0aaaa044db3.png', '你你你', 1, 1, 3, '<p>aS大萨达</p>', '2020-05-11 10:36:03', '2021-12-04 10:36:58', 0);
INSERT INTO `sd_test` VALUES (4, 'asdsadasd', 'upload_resource/20200507/99e5b9ec17d81b488f2c560d33fe3cbe.jpg', 'upload_resource/20211021/284356fff1bb1316cb505b15cab20f4f.png,upload_resource/20211021/78b69405d65ef05a05cf96f619d4f0d3.png,upload_resource/20211019/9710f717dfded51ed5b1c0aaaa044db3.png,upload_resource/20211019/338f62ecb49b4398e5222a34242d2d11.png,upload_resource/20211019/372d566b15fa1e2fa2ece665edb7732c.png,upload_resource/20211019/372d566b15fa1e2fa2ece665edb7732c.png,upload_resource/20211019/69c9df0b20628cefa38e8c5d1903c999.png,upload_resource/20211019/a1bc430007071f80bf37671e95a00533.png', 'asdasdasd', 1, 1, 3, '<p>asdasd</p>', '2020-05-11 12:05:34', '2021-12-10 15:25:19', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sd_user
-- ----------------------------
INSERT INTO `sd_user` VALUES (1, 0, 'test', 'test', '13555556666', '$2y$10$1lJv1qS4lrlRSwL5rKLFBeFIw9EIvBXIy9rls/AtiJnA.oEezV26K', 'upload_resource/20211019/372d566b15fa1e2fa2ece665edb7732c.png', 'test', '', '', '', '', 0, 0, 0, '', 1, '2021-12-04 11:51:18', '2021-12-04 11:51:18', 0);

SET FOREIGN_KEY_CHECKS = 1;
