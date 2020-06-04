/*
Navicat MySQL Data Transfer

Source Server         : 本地-wamp数据库
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : simple-teaching-man-sys

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-04 21:47:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `my_user`
-- ----------------------------
DROP TABLE IF EXISTS `my_user`;
CREATE TABLE `my_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `role` tinyint(1) unsigned DEFAULT NULL COMMENT '角色字段：0:管理员 1:超级管理员',
  `email` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(11) unsigned DEFAULT NULL,
  `login_count` int(11) unsigned DEFAULT NULL,
  `last_login_time` int(11) unsigned DEFAULT NULL,
  `logout_time` int(11) unsigned DEFAULT NULL,
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '软删除状态：1:已删除 0:未删除',
  `delete_time` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '启用状态：0已停用 1已启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='管理员用户表';

-- ----------------------------
-- Records of my_user
-- ----------------------------
INSERT INTO `my_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', 'admin@qq.com', '0', '1591275837', '4', '1591275284', '1591275261', '0', null, '1');
INSERT INTO `my_user` VALUES ('2', 'xuesi', 'e10adc3949ba59abbe56e057f20f883e', '0', 'xuesi@qq.com', '0', '1591278308', '0', '1591247803', '1591254057', '1', null, '1');
INSERT INTO `my_user` VALUES ('3', 'admin4', 'e10adc3949ba59abbe56e057f20f883e', '1', 'admin4@qq.com', '1591271082', '1591277899', '0', null, null, '0', null, '1');
