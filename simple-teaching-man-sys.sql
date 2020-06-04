/*
 Navicat Premium Data Transfer

 Source Server         : xampp
 Source Server Type    : MySQL
 Source Server Version : 100406
 Source Host           : localhost:3306
 Source Schema         : simple-teaching-man-sys

 Target Server Type    : MySQL
 Target Server Version : 100406
 File Encoding         : 65001

 Date: 05/06/2020 02:11:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for my_grade
-- ----------------------------
DROP TABLE IF EXISTS `my_grade`;
CREATE TABLE `my_grade`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '班级id，主键',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '班级名称',
  `length` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '学制',
  `price` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '学费',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否启用：1:启用 0:禁用',
  `create_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
  `is_delete` int(11) UNSIGNED NULL DEFAULT 1 COMMENT '允许删除',
  `student_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '关键外键',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for my_user
-- ----------------------------
DROP TABLE IF EXISTS `my_user`;
CREATE TABLE `my_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户密码',
  `role` tinyint(1) UNSIGNED NULL DEFAULT NULL COMMENT '角色字段：0:管理员 1:超级管理员',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户邮箱',
  `create_time` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `login_count` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '登录次数',
  `last_login_time` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '上次登录时间',
  `logout_time` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '退出登录时间',
  `is_delete` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '软删除状态：1:已删除 0:未删除',
  `delete_time` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '启用状态：0已停用 1已启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of my_user
-- ----------------------------
INSERT INTO `my_user` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 'admin@qq.com', 0, 1591293366, 14, 1591293010, 1591293366, 1, NULL, 1);
INSERT INTO `my_user` VALUES (2, 'xuesi', 'e10adc3949ba59abbe56e057f20f883e', 0, 'xuesi@qq.com', 0, 1591292650, 3, 1591291790, 1591291848, 0, NULL, 1);
INSERT INTO `my_user` VALUES (3, 'admin4', 'e10adc3949ba59abbe56e057f20f883e', 1, 'admin4@qq.com', 1591271082, 1591293081, 0, NULL, NULL, 1, NULL, 1);
INSERT INTO `my_user` VALUES (4, 'php', 'e10adc3949ba59abbe56e057f20f883e', 0, 'php11@qq.com', 1591291880, 1591293374, 3, 1591293374, 1591292152, 1, NULL, 1);
INSERT INTO `my_user` VALUES (5, 'java', 'e10adc3949ba59abbe56e057f20f883e', 1, 'java@qq.com', 1591291901, 1591293081, 0, NULL, NULL, 1, NULL, 1);

SET FOREIGN_KEY_CHECKS = 1;
