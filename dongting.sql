/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50536
Source Host           : localhost:3306
Source Database       : dongting

Target Server Type    : MYSQL
Target Server Version : 50536
File Encoding         : 65001

Date: 2016-07-14 00:42:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tp_admin_action`
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_action`;
CREATE TABLE `tp_admin_action` (
  `action_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `action_code` varchar(20) NOT NULL DEFAULT '',
  `relevance` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`action_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_action
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_log`;
CREATE TABLE `tp_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_log
-- ----------------------------
INSERT INTO `tp_admin_log` VALUES ('1', '1424943402', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('2', '1424943413', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('3', '1424943431', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('4', '1424944139', '1', '编辑广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('5', '1424944150', '1', '编辑广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('6', '1425264818', '1', '添加文章: 地面保障服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('7', '1425265380', '1', '添加文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('8', '1425265443', '1', '添加文章: 广东省机场管理集团翼通商务航空服务有限公司正式挂牌', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('9', '1425265471', '1', '添加文章: 广东低空空域管理改革试点取得阶段性成果 穗澳两地试开直升机航线 ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('10', '1425265629', '1', '添加文章: 穗澳两地试开直升机航线', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('11', '1425265646', '1', '添加文章: 中国低空空域管制制度放松 促通航产业发展', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('12', '1425265676', '1', '添加文章: 公务机由\"身份消费\"向\"功能消费\"转变', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('13', '1425265706', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('14', '1425265711', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('15', '1425265714', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('16', '1425265718', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('17', '1425265722', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('18', '1425265726', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('19', '1425265733', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('20', '1425265737', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('21', '1425265744', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('22', '1425265757', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('23', '1425265764', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('24', '1425265771', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('25', '1425265786', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('26', '1425265793', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('27', '1425265800', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('28', '1425265807', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('29', '1425265814', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('30', '1425265820', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('31', '1425266576', '1', '添加文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('32', '1425266597', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('33', '1425266605', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('34', '1425266612', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('35', '1425266618', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('36', '1425266625', '1', '添加文章: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('37', '1425266642', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('38', '1425266653', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('39', '1425266665', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('40', '1425266676', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('41', '1425266687', '1', '编辑文章: 广东翼通航空正式挂牌 \"试水\"商务航空', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('42', '1425266755', '1', '编辑文章分类: 调机惊喜', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('43', '1425266764', '1', '编辑文章分类: 机型介绍', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('44', '1425266772', '1', '编辑文章分类: 包机流程', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('45', '1425266787', '1', '编辑文章分类: 调机惊喜', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('46', '1425266878', '1', '编辑文章分类: 机型介绍', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('47', '1425266904', '1', '编辑文章分类: 包机流程', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('48', '1425276391', '1', '编辑文章: 湾流G450', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('49', '1425298213', '1', '编辑文章分类: 产品服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('50', '1425381748', '1', '编辑文章分类: 新闻中心', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('51', '1425381757', '1', '编辑文章分类: 客户服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('52', '1425381765', '1', '编辑文章分类: FBO项目', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('53', '1425381772', '1', '编辑文章分类: 关于翼通', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('54', '1425381786', '1', '编辑文章分类: 关于翼通', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('55', '1425381791', '1', '编辑文章分类: FBO项目', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('56', '1425381811', '1', '编辑文章分类: FBO项目', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('57', '1425381914', '1', '编辑文章分类: 产品服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('58', '1425381930', '1', '编辑文章分类: 客户服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('59', '1425386753', '1', '编辑文章分类: 地面保障服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('60', '1425386982', '1', '编辑文章分类: 航务代理服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('61', '1425386988', '1', '编辑文章分类: 机库停放服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('62', '1425386993', '1', '编辑文章分类: 燃油加注服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('63', '1425386999', '1', '编辑文章分类: 商务机租赁服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('64', '1425387005', '1', '编辑文章分类: 外站代理服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('65', '1425387757', '1', '编辑文章分类: 产品服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('66', '1425387762', '1', '编辑文章分类: 新闻中心', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('67', '1425387766', '1', '编辑文章分类: 客户服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('68', '1425387770', '1', '编辑文章分类: FBO项目', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('69', '1425387774', '1', '编辑文章分类: 关于翼通', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('70', '1425387984', '1', '编辑文章分类: 关于翼通', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('71', '1425387988', '1', '编辑文章分类: 产品服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('72', '1425387992', '1', '编辑文章分类: 新闻中心', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('73', '1425387996', '1', '编辑文章分类: 客户服务', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('74', '1425387999', '1', '编辑文章分类: FBO项目', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('75', '1425388191', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('76', '1425388199', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('77', '1425388206', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('78', '1425388227', '1', '添加文章分类: 其他文章', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('79', '1425389196', '1', '添加广告: logo', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('80', '1425451253', '1', '添加广告: 产品服务', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('81', '1425451361', '1', '添加广告: 新闻中心', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('82', '1425451406', '1', '添加广告: 客户服务', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('83', '1425451448', '1', '添加广告: FBO服务', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('84', '1425451522', '1', '添加广告: 关于翼通', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('85', '1425454920', '1', '添加广告: 默认Banner图', '59.41.41.143');
INSERT INTO `tp_admin_log` VALUES ('86', '1425632178', '1', '编辑广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('87', '1425632611', '1', '编辑广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('88', '1425633286', '1', '删除文章: 网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线网站上线', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('89', '1425636031', '1', '编辑广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('90', '1425636662', '1', '添加广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('91', '1425636741', '1', '添加广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('92', '1425636777', '1', '添加广告: ', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('93', '1425636811', '1', '编辑广告: FBO基地功能介绍', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('94', '1425636837', '1', '编辑广告: FBO基地最新建设情况', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('95', '1425636866', '1', '编辑广告: FBO基地简介', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('96', '1425636895', '1', '编辑广告: FBO基地简介', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('97', '1425636931', '1', '编辑广告: FBO基地简介', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('98', '1425637050', '1', '编辑广告: FBO基地简介', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('99', '1425637063', '1', '编辑广告: FBO基地最新建设情况', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('100', '1425637074', '1', '编辑广告: FBO基地功能介绍', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('101', '1425637264', '1', '编辑广告: FBO基地简介', '59.41.205.182');
INSERT INTO `tp_admin_log` VALUES ('102', '1427175762', '1', '删除文章: ', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('103', '1427175960', '1', '删除文章: 广东翼通航空正式挂牌 \\\"试水\\\"商务航空', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('104', '1427175965', '1', '删除文章: 广东翼通航空正式挂牌 \\\"试水\\\"商务航空', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('105', '1427180919', '1', '删除文章: FBO基地最新建设情况', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('106', '1427276038', '1', '删除文章: FBO基地功能介绍', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('107', '1427276065', '1', '删除文章分类: FBO基地功能介绍', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('108', '1427282558', '1', '删除文章: 撒旦撒', '113.119.215.239');
INSERT INTO `tp_admin_log` VALUES ('109', '1427332219', '1', '删除文章: 包机流程', '59.41.40.100');
INSERT INTO `tp_admin_log` VALUES ('110', '1427355877', '1', '编辑文章分类: 航务代理服务', '59.41.32.34');
INSERT INTO `tp_admin_log` VALUES ('111', '1427355901', '1', '编辑文章分类: 机库停放服务', '59.41.32.34');
INSERT INTO `tp_admin_log` VALUES ('112', '1427355924', '1', '编辑文章分类: 燃油加注服务', '59.41.32.34');
INSERT INTO `tp_admin_log` VALUES ('113', '1428335543', '1', '编辑文章分类: Product Service', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('114', '1428335554', '1', '编辑文章分类: News Center', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('115', '1428335563', '1', '编辑文章分类: Customer Service', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('116', '1428335579', '1', '编辑文章分类: FBO', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('117', '1428335587', '1', '编辑文章分类: About', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('118', '1428335597', '1', '编辑文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('119', '1428335677', '1', '编辑文章分类: Ground Support', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('120', '1428335697', '1', '编辑文章分类: Shipping Agency', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('121', '1428335712', '1', '编辑文章分类: Hangar Park', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('122', '1428335735', '1', '编辑文章分类: Fuel Filler', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('123', '1428335758', '1', '编辑文章分类: Leasing Business', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('124', '1428335804', '1', '编辑文章分类: Models Introduced', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('125', '1428335909', '1', '编辑文章分类: Plane Introduced', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('126', '1428336036', '1', '编辑文章分类: Adjustable Plane Surprised', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('127', '1428336090', '1', '编辑文章分类: Charter Process', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('128', '1428336121', '1', '编辑文章分类: Outside Agent', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('129', '1428336157', '1', '编辑文章分类: Company News', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('130', '1428336173', '1', '编辑文章分类: Media Reports', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('131', '1428336190', '1', '编辑文章分类: Industry Information', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('132', '1428336226', '1', '编辑文章分类: Online Survey', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('133', '1428336250', '1', '编辑文章分类: Complaints & Suggestions', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('134', '1428336284', '1', '编辑文章分类: FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('135', '1428336339', '1', '编辑文章分类: The Construction Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('136', '1428336388', '1', '编辑文章分类: FBO Base Construction', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('137', '1428336446', '1', '编辑文章分类: The Long-Term Construction Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('138', '1428336472', '1', '编辑文章分类: Company Profile', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('139', '1428336494', '1', '编辑文章分类: Development Process', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('140', '1428336519', '1', '编辑文章分类: Company Strategy', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('141', '1428336548', '1', '编辑文章分类: Internal Style', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('142', '1428336556', '1', '编辑文章分类: Contact Us', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('143', '1428336933', '1', '编辑广告: FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('144', '1428337007', '1', '编辑广告: FBO基地 The new building', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('145', '1428337038', '1', '编辑广告: The Lastest Building Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('146', '1428337110', '1', '编辑广告: Base Function Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('147', '1428337127', '1', '编辑广告: The Lastest Building', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('148', '1428337199', '1', '编辑广告: Base Function Of FBO', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('149', '1466865553', '1', '编辑广告: logo', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('150', '1466865580', '1', '删除广告: FBO Base', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('151', '1466865583', '1', '删除广告: The Lastest Building', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('152', '1466865614', '1', '编辑广告: 首页图片', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('153', '1466865669', '1', '添加文章分类: home', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('154', '1466866346', '1', '编辑文章分类: Locks & Cylinders', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('155', '1466866359', '1', '编辑文章分类: products', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('156', '1466866372', '1', '编辑文章分类: Aluminum', '0.0.0.0');
INSERT INTO `tp_admin_log` VALUES ('157', '1466946994', '1', '添加文章分类: Rim locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('158', '1466947002', '1', '添加文章分类: Mortise locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('159', '1466947009', '1', '添加文章分类: Cylinders', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('160', '1466947015', '1', '添加文章分类: Push button locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('161', '1466947021', '1', '添加文章分类: Door control accessories', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('162', '1466947082', '1', '编辑文章分类: Aluminum', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('163', '1466947109', '1', '编辑文章分类: Furnituer Fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('164', '1466947128', '1', '编辑文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('165', '1466947185', '1', '删除文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('166', '1466947197', '1', '编辑文章分类: Surface treatment', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('167', '1466947208', '1', '编辑文章分类: Aluminum door & window', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('168', '1466947215', '1', '编辑文章分类: Aluminum profiles', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('169', '1466947225', '1', '添加文章分类: Aluminium rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('170', '1466947265', '1', '删除文章分类: Surface treatment', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('171', '1466947267', '1', '删除文章分类: Aluminum door & window', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('172', '1466947279', '1', '删除文章分类: Aluminium rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('173', '1466947282', '1', '删除文章分类: Aluminum profiles', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('174', '1466947285', '1', '删除文章分类: Aluminum', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('175', '1466947289', '1', '删除文章分类: Online Survey', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('176', '1466947291', '1', '删除文章分类: Complaints & Suggestions', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('177', '1466947293', '1', '删除文章分类: Furnituer Fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('178', '1466947296', '1', '删除文章分类: FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('179', '1466947299', '1', '删除文章分类: The Construction Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('180', '1466947301', '1', '删除文章分类: The Long-Term Construction Of FBO Base', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('181', '1466947303', '1', '删除文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('182', '1466947308', '1', '删除文章分类: Company Profile', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('183', '1466947310', '1', '删除文章分类: Development Process', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('184', '1466947312', '1', '删除文章分类: Company Strategy', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('185', '1466947315', '1', '删除文章分类: Internal Style', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('186', '1466947318', '1', '删除文章分类: Contact Us', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('187', '1466947320', '1', '删除文章分类: About', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('188', '1466947340', '1', '编辑文章分类: Furnituer Fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('189', '1466947349', '1', '编辑文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('190', '1466947352', '1', '删除文章分类: Outside Agent', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('191', '1466947355', '1', '删除文章分类: Adjustable Plane Surprised', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('192', '1466947357', '1', '删除文章分类: Plane Introduced', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('193', '1466947359', '1', '删除文章分类: Charter Process', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('194', '1466947376', '1', '添加文章分类: Surface treatment', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('195', '1466947383', '1', '添加文章分类: Aluminum door & window', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('196', '1466947392', '1', '添加文章分类: Aluminum profiles', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('197', '1466947400', '1', '添加文章分类: Aluminium rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('198', '1466947408', '1', '添加文章分类: Curtain rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('199', '1466947413', '1', '添加文章分类: Connecting fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('200', '1466947418', '1', '添加文章分类: Kitchen & bathroom', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('201', '1466947422', '1', '添加文章分类: Cabinet hardware', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('202', '1466947428', '1', '添加文章分类: Wardrobe accessories', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('203', '1466947433', '1', '添加文章分类: Furniture legs/wheels', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('204', '1466947449', '1', '删除文章分类: Fuel Filler', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('205', '1466947453', '1', '编辑文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('206', '1466947458', '1', '添加文章分类: Wallpaper', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('207', '1466947463', '1', '添加文章分类: Ladders', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('208', '1466947469', '1', '添加文章分类: Fan', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('209', '1466947473', '1', '添加文章分类: Weeder', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('210', '1466947478', '1', '添加文章分类: Flooring', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('211', '1466954908', '1', '添加文章分类: 123', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('212', '1467983822', '1', '删除文章分类: Door control accessories', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('213', '1467983824', '1', '删除文章分类: Push button locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('214', '1467983827', '1', '删除文章分类: Cylinders', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('215', '1467983829', '1', '删除文章分类: Mortise locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('216', '1467983831', '1', '删除文章分类: 123', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('217', '1467983833', '1', '删除文章分类: Rim locks', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('218', '1467983838', '1', '删除文章分类: Aluminium rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('219', '1467983840', '1', '删除文章分类: Aluminum profiles', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('220', '1467983842', '1', '删除文章分类: Aluminum door & window', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('221', '1467983843', '1', '删除文章分类: Surface treatment', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('222', '1467983846', '1', '删除文章分类: Aluminum', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('223', '1467983850', '1', '删除文章分类: Furniture legs/wheels', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('224', '1467983852', '1', '删除文章分类: Wardrobe accessories', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('225', '1467983854', '1', '删除文章分类: Kitchen & bathroom', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('226', '1467983856', '1', '删除文章分类: Connecting fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('227', '1467983858', '1', '删除文章分类: Curtain rods', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('228', '1467983863', '1', '删除文章分类: Flooring', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('229', '1467983865', '1', '删除文章分类: Weeder', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('230', '1467983866', '1', '删除文章分类: Fan', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('231', '1467983870', '1', '删除文章分类: Ladders', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('232', '1467983872', '1', '删除文章分类: Wallpaper', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('233', '1467983878', '1', '删除文章分类: Cabinet hardware', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('234', '1467983880', '1', '删除文章分类: Furnituer Fittings', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('235', '1467983882', '1', '删除文章分类: Other', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('236', '1467983884', '1', '删除文章分类: Locks & Cylinders', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('237', '1467983886', '1', '删除文章分类: products', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('238', '1467983888', '1', '删除文章分类: home', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('239', '1467983915', '1', '添加文章分类: 作品案例', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('240', '1467983925', '1', '删除文章分类: 作品案例', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('241', '1467984011', '1', '添加文章分类: 作品案例', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('242', '1467984153', '1', '编辑文章分类: 作品案例', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('243', '1467984165', '1', '添加文章分类: 新闻资讯', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('244', '1467984170', '1', '添加文章分类: 关于我们', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('245', '1467984180', '1', '添加文章分类: 联系我们', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('246', '1467984213', '1', '编辑广告: logo', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('247', '1467984376', '1', '编辑广告: logo', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('248', '1467984844', '1', '编辑广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('249', '1467984858', '1', '编辑广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('250', '1467984871', '1', '编辑广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('251', '1467985437', '1', '添加文章分类: 公司動態', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('252', '1467985466', '1', '编辑文章分类: 公司动态', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('253', '1467985518', '1', '编辑文章分类: 公司動態', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('254', '1467985537', '1', '编辑文章分类: 公司动态', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('255', '1467985560', '1', '添加文章分类: 媒体报道', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('256', '1467985572', '1', '添加文章分类: 行业资讯', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('257', '1467985587', '1', '添加文章分类: 设计百科', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('258', '1467985602', '1', '添加文章分类: 佳作欣赏', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('259', '1467985662', '1', '添加文章分类: 办公', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('260', '1467985667', '1', '添加文章分类: 别墅', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('261', '1467985672', '1', '添加文章分类: 豪宅', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('262', '1467985694', '1', '添加文章分类: 创始人', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('263', '1467985701', '1', '添加文章分类: 人才招聘', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('264', '1467985712', '1', '添加文章分类: 合作伙伴', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('265', '1468409172', '1', '添加广告: 百度', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('266', '1468409209', '1', '添加广告: 天猫', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('267', '1468411627', '1', '添加广告: 微信二维码', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('268', '1468411644', '1', '编辑广告: 微信二维码', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('269', '1468411687', '1', '添加广告: 123', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('270', '1468412004', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('271', '1468412100', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('272', '1468412125', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('273', '1468412166', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('274', '1468412185', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('275', '1468412199', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('276', '1468412208', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('277', '1468412360', '1', '添加广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('278', '1468412432', '1', '删除广告: 123', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('279', '1468412436', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('280', '1468412437', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('281', '1468412439', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('282', '1468412441', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('283', '1468412443', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('284', '1468412445', '1', '删除广告: ', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('285', '1468412452', '1', '编辑广告: 微信二维码', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('286', '1468427519', '1', '添加: 管理员', '127.0.0.1');
INSERT INTO `tp_admin_log` VALUES ('287', '1468427585', '1', '添加权限管理: dongting', '127.0.0.1');

-- ----------------------------
-- Table structure for `tp_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_user`;
CREATE TABLE `tp_admin_user` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `lang_type` varchar(50) NOT NULL DEFAULT '',
  `agency_id` smallint(5) unsigned NOT NULL,
  `suppliers_id` smallint(5) unsigned DEFAULT '0',
  `todolist` longtext,
  `role_id` smallint(5) DEFAULT NULL,
  `login_count` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_user
-- ----------------------------
INSERT INTO `tp_admin_user` VALUES ('1', 'hunuokeji', '12345678@qq.com', '4db80c6ff9ac1393dc359d5ec1dc15da', '1321927163', '1468427899', '127.0.0.1', 'cat_2,cat_17,cat_18,cat_19,cat_20,cat_21,cat_23,cat_3,cat_25,cat_26,cat_27,cat_28,cat_29,cat_30,cat_32,cat_4,cat_33,cat_34,cat_35,cat_36,cat_37,cat_38,cat_39,cat_40,cat_41,cat_42,cat_43,cat_44,cat_45,cat_46,cat_47,cat_48,cat_49,cat_50,cat_51,cat_52,cat_53,cat_5,cat_146,cat_145,cat_144,cat_143,cat_147,cat_6,cat_61,cat_62,cat_63,cat_65,cat_66,cat_67,cat_68,cat_71,cat_7,cat_72,cat_73,cat_74,cat_75,cat_76,cat_77,cat_78,cat_79,cat_80,cat_81,cat_8,cat_82,cat_83,cat_84,cat_85,cat_86,cat_87,cat_88,cat_89,cat_90,cat_91,cat_92,cat_93,cat_9,cat_95,cat_96,cat_97,cat_98,cat_99,cat_149,cat_150,cat_151,cat_10,cat_100,cat_101,cat_102,cat_11,cat_104,cat_105,cat_106,cat_107,cat_108,cat_109,cat_110,cat_111,cat_112,cat_113,cat_12,cat_114,cat_115,cat_117,cat_118,cat_119,cat_120,cat_121,cat_122,cat_124,cat_125,cat_126,cat_127,cat_128,cat_129,cat_13,cat_131,cat_132,cat_134,cat_14,cat_135,cat_136,cat_139,cat_140,cat_141,cat_142,cat_148,Forms,Attr,Message,Images,Systems', '', '0', '0', null, '6', '0');
INSERT INTO `tp_admin_user` VALUES ('10', 'dongting', '', '7bffe3df795fa9be88400e5daa5245cd', '1468427585', '1468427899', '127.0.0.1', 'cat_10,cat_11,cat_12,Helps,Columns,Forms,Attr,Message,Member,Systems', '', '0', '0', null, '7', '0');

-- ----------------------------
-- Table structure for `tp_ads`
-- ----------------------------
DROP TABLE IF EXISTS `tp_ads`;
CREATE TABLE `tp_ads` (
  `ads_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `original_img` varchar(200) NOT NULL,
  `thumb_img` varchar(200) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL,
  `link` varchar(200) NOT NULL DEFAULT '',
  `img_size` varchar(30) DEFAULT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ads_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_ads
-- ----------------------------
INSERT INTO `tp_ads` VALUES ('1', '', '', 'Uploads/Banner/original_img/1467984843.jpg', 'Uploads/Banner/thumb_img/1467984843.jpg', '1', '1', '1424943402', '', '980px * 565px', '2');
INSERT INTO `tp_ads` VALUES ('2', '', '', 'Uploads/Banner/original_img/1467984858.jpg', 'Uploads/Banner/thumb_img/1467984858.jpg', '2', '1', '1424943413', '', '980px * 565px', '2');
INSERT INTO `tp_ads` VALUES ('3', '', '', 'Uploads/Banner/original_img/1467984871.jpg', 'Uploads/Banner/thumb_img/1467984871.jpg', '3', '1', '1424943431', '', '980px * 565px', '2');
INSERT INTO `tp_ads` VALUES ('4', '', '', 'Uploads/Banner/original_img/1425388191.jpg', 'Uploads/Banner/thumb_img/ads_1425388191.jpg', '50', '1', '1425388191', 'http://yitong.gz9.hostadm.net/Info-fboIndex.html', '193px * 169px', '5');
INSERT INTO `tp_ads` VALUES ('5', '', '', 'Uploads/Banner/original_img/1425388199.jpg', 'Uploads/Banner/thumb_img/ads_1425388199.jpg', '50', '1', '1425388199', '', '193px * 169px', '5');
INSERT INTO `tp_ads` VALUES ('6', '', '', 'Uploads/Banner/original_img/1425388206.jpg', 'Uploads/Banner/thumb_img/ads_1425388206.jpg', '50', '1', '1425388206', '', '193px * 169px', '5');
INSERT INTO `tp_ads` VALUES ('7', '', 'logo', 'Uploads/Banner/original_img/1467984376.jpg', 'Uploads/Banner/thumb_img/1467984376.jpg', '50', '1', '1425389196', '', '85px * 78px', '4');
INSERT INTO `tp_ads` VALUES ('8', '', '产品服务', 'Uploads/Banner/original_img/1425451253.jpg', 'Uploads/Banner/thumb_img/ads_1425451253.jpg', '1', '1', '1425451253', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('9', '', '新闻中心', 'Uploads/Banner/original_img/1425451361.jpg', 'Uploads/Banner/thumb_img/ads_1425451361.jpg', '50', '1', '1425451361', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('10', '', '客户服务', 'Uploads/Banner/original_img/1425451406.jpg', 'Uploads/Banner/thumb_img/ads_1425451406.jpg', '50', '1', '1425451406', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('11', '', 'FBO服务', 'Uploads/Banner/original_img/1425451448.jpg', 'Uploads/Banner/thumb_img/ads_1425451448.jpg', '50', '1', '1425451448', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('12', '', '关于翼通', 'Uploads/Banner/original_img/1425451522.jpg', 'Uploads/Banner/thumb_img/ads_1425451522.jpg', '50', '1', '1425451522', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('13', '', '默认Banner图', 'Uploads/Banner/original_img/1425454920.jpg', 'Uploads/Banner/thumb_img/ads_1425454920.jpg', '50', '1', '1425454920', '', '1680px * 341px', '3');
INSERT INTO `tp_ads` VALUES ('16', '', '首页图片', 'Uploads/Banner/original_img/1466865614.jpg', 'Uploads/Banner/thumb_img/1466865614.jpg', '50', '1', '1425636777', '', '800px * 490px', '6');
INSERT INTO `tp_ads` VALUES ('17', '', '百度', '', '', '1', '1', '1468409172', 'https://www.baidu.com/', '', '7');
INSERT INTO `tp_ads` VALUES ('18', '', '天猫', '', '', '2', '1', '1468409209', 'https://www.tmall.com/', '', '7');
INSERT INTO `tp_ads` VALUES ('19', '', '微信二维码', 'Uploads/Banner/original_img/57863224580bf.png', 'Uploads/Banner/thumb_img/1468411644.png', '1', '1', '1468411627', '', '96*96', '8');

-- ----------------------------
-- Table structure for `tp_album`
-- ----------------------------
DROP TABLE IF EXISTS `tp_album`;
CREATE TABLE `tp_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `original_img` varchar(200) DEFAULT NULL,
  `thumb_img` varchar(200) DEFAULT NULL,
  `img_size` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `id_value` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_album
-- ----------------------------
INSERT INTO `tp_album` VALUES ('20', 'article', '50', 'Uploads/Album/20150324/original_img/201503240440023601.jpg', 'Uploads/Album/20150324/thumb_img/201503240440023601.jpg', null, '', '27');
INSERT INTO `tp_album` VALUES ('21', 'article', '50', 'Uploads/Album/20150324/original_img/201503240440066932.jpg', 'Uploads/Album/20150324/thumb_img/201503240440066932.jpg', null, '', '27');
INSERT INTO `tp_album` VALUES ('22', 'article', '50', 'Uploads/Album/20150324/original_img/201503240440092946.jpg', 'Uploads/Album/20150324/thumb_img/201503240440092946.jpg', null, '', '27');
INSERT INTO `tp_album` VALUES ('23', 'article', '50', 'Uploads/Album/20150324/original_img/201503240440236325.jpg', 'Uploads/Album/20150324/thumb_img/201503240440236325.jpg', null, '', '27');
INSERT INTO `tp_album` VALUES ('24', 'article', '50', 'Uploads/Album/20150324/original_img/201503240440375223.jpg', 'Uploads/Album/20150324/thumb_img/201503240440375223.jpg', null, '', '27');
INSERT INTO `tp_album` VALUES ('7', 'article', '50', 'Uploads/Album/20150302/original_img/201503020600159405.jpg', 'Uploads/Album/20150302/thumb_img/201503020600159405.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('8', 'article', '1', 'Uploads/Album/20150302/original_img/201503020600165417.jpg', 'Uploads/Album/20150302/thumb_img/201503020600165417.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('18', 'article', '50', 'Uploads/Album/20150324/original_img/201503240413321620.jpg', 'Uploads/Album/20150324/thumb_img/201503240413321620.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('37', 'article', '50', 'Uploads/Album/20150324/original_img/201503240523199012.jpg', 'Uploads/Album/20150324/thumb_img/201503240523199012.jpg', null, '', '64');
INSERT INTO `tp_album` VALUES ('26', 'article', '50', 'Uploads/Album/20150324/original_img/201503240448254175.jpg', 'Uploads/Album/20150324/thumb_img/201503240448254175.jpg', null, '', '61');
INSERT INTO `tp_album` VALUES ('15', 'article', '50', 'Uploads/Album/20150324/original_img/201503240413099779.jpg', 'Uploads/Album/20150324/thumb_img/201503240413099779.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('16', 'article', '50', 'Uploads/Album/20150324/original_img/201503240413148363.jpg', 'Uploads/Album/20150324/thumb_img/201503240413148363.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('17', 'article', '50', 'Uploads/Album/20150324/original_img/201503240413254680.jpg', 'Uploads/Album/20150324/thumb_img/201503240413254680.jpg', null, '45', '28');
INSERT INTO `tp_album` VALUES ('38', 'article', '50', 'Uploads/Album/20150324/original_img/201503240523233950.jpg', 'Uploads/Album/20150324/thumb_img/201503240523233950.jpg', null, '', '64');
INSERT INTO `tp_album` VALUES ('39', 'article', '50', 'Uploads/Album/20150324/original_img/201503240523243134.jpg', 'Uploads/Album/20150324/thumb_img/201503240523243134.jpg', null, '', '64');
INSERT INTO `tp_album` VALUES ('40', 'article', '50', 'Uploads/Album/20150324/original_img/201503240523287261.jpg', 'Uploads/Album/20150324/thumb_img/201503240523287261.jpg', null, '', '64');
INSERT INTO `tp_album` VALUES ('30', 'article', '50', 'Uploads/Album/20150324/original_img/201503240458334434.jpg', 'Uploads/Album/20150324/thumb_img/201503240458334434.jpg', null, '', '62');
INSERT INTO `tp_album` VALUES ('31', 'article', '50', 'Uploads/Album/20150324/original_img/201503240458387800.jpg', 'Uploads/Album/20150324/thumb_img/201503240458387800.jpg', null, '', '62');
INSERT INTO `tp_album` VALUES ('32', 'article', '50', 'Uploads/Album/20150324/original_img/201503240510134886.jpg', 'Uploads/Album/20150324/thumb_img/201503240510134886.jpg', null, '', '63');
INSERT INTO `tp_album` VALUES ('33', 'article', '50', 'Uploads/Album/20150324/original_img/201503240510162871.jpg', 'Uploads/Album/20150324/thumb_img/201503240510162871.jpg', null, '', '63');
INSERT INTO `tp_album` VALUES ('34', 'article', '50', 'Uploads/Album/20150324/original_img/201503240510183917.jpg', 'Uploads/Album/20150324/thumb_img/201503240510183917.jpg', null, '', '63');
INSERT INTO `tp_album` VALUES ('35', 'article', '50', 'Uploads/Album/20150324/original_img/201503240510297638.jpg', 'Uploads/Album/20150324/thumb_img/201503240510297638.jpg', null, '', '63');
INSERT INTO `tp_album` VALUES ('36', 'article', '50', 'Uploads/Album/20150324/original_img/201503240510381934.jpg', 'Uploads/Album/20150324/thumb_img/201503240510381934.jpg', null, '', '63');
INSERT INTO `tp_album` VALUES ('41', 'article', '50', 'Uploads/Album/20150324/original_img/201503240523332695.jpg', 'Uploads/Album/20150324/thumb_img/201503240523332695.jpg', null, '', '64');
INSERT INTO `tp_album` VALUES ('42', 'article', '50', 'Uploads/Album/20150324/original_img/201503240531467386.jpg', 'Uploads/Album/20150324/thumb_img/201503240531467386.jpg', null, '', '65');
INSERT INTO `tp_album` VALUES ('43', 'article', '50', 'Uploads/Album/20150324/original_img/201503240531491468.jpg', 'Uploads/Album/20150324/thumb_img/201503240531491468.jpg', null, '', '65');
INSERT INTO `tp_album` VALUES ('44', 'article', '50', 'Uploads/Album/20150324/original_img/201503240531542803.jpg', 'Uploads/Album/20150324/thumb_img/201503240531542803.jpg', null, '', '65');
INSERT INTO `tp_album` VALUES ('45', 'article', '50', 'Uploads/Album/20150324/original_img/201503240531567853.jpg', 'Uploads/Album/20150324/thumb_img/201503240531567853.jpg', null, '', '65');
INSERT INTO `tp_album` VALUES ('46', 'article', '50', 'Uploads/Album/20150324/original_img/201503240532004476.jpg', 'Uploads/Album/20150324/thumb_img/201503240532004476.jpg', null, '', '65');
INSERT INTO `tp_album` VALUES ('47', 'article', '50', 'Uploads/Album/20150324/original_img/201503240538237550.jpg', 'Uploads/Album/20150324/thumb_img/201503240538237550.jpg', null, '', '66');
INSERT INTO `tp_album` VALUES ('48', 'article', '50', 'Uploads/Album/20150324/original_img/201503240538403676.jpg', 'Uploads/Album/20150324/thumb_img/201503240538403676.jpg', null, '', '66');
INSERT INTO `tp_album` VALUES ('49', 'article', '50', 'Uploads/Album/20150324/original_img/201503240538515854.jpg', 'Uploads/Album/20150324/thumb_img/201503240538515854.jpg', null, '', '66');
INSERT INTO `tp_album` VALUES ('50', 'article', '50', 'Uploads/Album/20150324/original_img/201503240539029263.jpg', 'Uploads/Album/20150324/thumb_img/201503240539029263.jpg', null, '', '66');
INSERT INTO `tp_album` VALUES ('51', 'article', '50', 'Uploads/Album/20150324/original_img/201503240539055739.jpg', 'Uploads/Album/20150324/thumb_img/201503240539055739.jpg', null, '', '66');
INSERT INTO `tp_album` VALUES ('52', 'article', '50', 'Uploads/Album/20150324/original_img/201503240633017064.jpg', 'Uploads/Album/20150324/thumb_img/201503240633017064.jpg', null, '', '61');
INSERT INTO `tp_album` VALUES ('53', 'article', '50', 'Uploads/Album/20150324/original_img/201503240633039927.jpg', 'Uploads/Album/20150324/thumb_img/201503240633039927.jpg', null, '', '61');
INSERT INTO `tp_album` VALUES ('54', 'article', '50', 'Uploads/Album/20150324/original_img/201503240633051666.jpg', 'Uploads/Album/20150324/thumb_img/201503240633051666.jpg', null, '', '61');

-- ----------------------------
-- Table structure for `tp_article`
-- ----------------------------
DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article` (
  `article_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) NOT NULL DEFAULT '0',
  `title` varchar(150) DEFAULT NULL,
  `en_title` varchar(100) DEFAULT NULL,
  `content` longtext NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `article_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `file_url` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(8) NOT NULL DEFAULT '50',
  `short` text,
  `original_img` text,
  `thumb_img` varchar(50) DEFAULT NULL,
  `click_sum` int(11) NOT NULL DEFAULT '0',
  `jixing` varchar(32) NOT NULL,
  `zuowei` int(11) NOT NULL,
  `jiage` varchar(32) NOT NULL,
  `feixingshijian` varchar(32) NOT NULL,
  `hangcheng` varchar(32) NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_article
-- ----------------------------
INSERT INTO `tp_article` VALUES ('89', '10', '陵海吸塑办公楼', null, '<span style=\"color:#AAAAAA;font-family:Arial, 微软雅黑;line-height:24px;\">集商務、休閑、居住融壹身的會所式辦公設計，融端莊、古韻、簡約與壹體，本案以閑熟的大手筆構造空間，做到壹氣呵成的渾然與灑脫。以古典元素爲切入點，意在讓現代生活沈澱出文化精髓的底蘊，空間結構的宏大與內斂構建了現代人對曆史的思考和繼承，整個布局設置和氣氛營造提取了古典建築的含蓄與從容，讓人沈醉。</span>', '', '2', '1', '0', '1467993600', '', '', '', '50', '', 'Uploads/article/original_img/thumb_578669f83d4d0.jpg,Uploads/article/original_img/thumb_578669f873425.jpg,Uploads/article/original_img/thumb_578669f89b0e6.jpg,Uploads/article/original_img/thumb_578669f8c29bf.jpg,Uploads/article/original_img/thumb_578669fa12d89.jpg,Uploads/article/original_img/thumb_578669fb21ff5.jpg,Uploads/article/original_img/thumb_578669fe29da3.jpg,Uploads/article/original_img/thumb_57866a02efeae.jpg,Uploads/article/original_img/thumb_57866a070ab9e.jpg', null, '20', '陵海吸塑办公楼', '0', '800平米', '汕头', '汕头');
INSERT INTO `tp_article` VALUES ('86', '5', '迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标', null, '<span style=\"color:#666666;font-family:Arial, 微软雅黑;font-size:13px;line-height:25px;\">“土豪之都”迪拜拥有众多吸引世人眼球的奇迹与纪录。近期，迪拜再出惊人之举，其耗费4500万株鲜花打造的迪拜奇迹花园，再次震惊世界。该花园美艳程度堪称一绝，令人叹为观止，并引起了不少富豪的关注。也许，在未来，打造一座媲美迪拜奇迹花园的私家花园，或成为豪宅装饰设计的新指标。</span>', '', '2', '1', '1', '1467907200', '', '', '', '50', '', 'Uploads/article/original_img/1467992348.jpg', null, '47', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('87', '5', '迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标', null, '<span style=\"color:#222222;font-family:Consolas, \'Lucida Console\', monospace;line-height:normal;background-color:#FFFFFF;\">迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标</span>', '', '2', '1', '1', '1467907200', '', '', '', '50', '', 'Uploads/article/original_img/1467992561.jpg', null, '33', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('88', '6', '迪拜耗费4500万株鲜花造最大花园 豪宅装饰设计诞生新指标2', null, '<span style=\"color:#666666;font-family:Arial, 微软雅黑;font-size:13px;line-height:25px;\">“土豪之都”迪拜拥有众多吸引世人眼球的奇迹与纪录。近期，迪拜再出惊人之举，其耗费4500万株鲜花打造的迪拜奇迹花园，再次震惊世界。该花园美艳程度堪称一绝，令人叹为观止，并引起了不少富豪的关注。也许，在未来，打造一座媲美迪拜奇迹花园的私家花园，或成为豪宅装饰设计的新指标。</span>', '', '2', '1', '0', '1467993600', '', '', '', '50', '', 'Uploads/article/original_img/1468031458.jpg', null, '0', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('90', '13', '创始人', null, '<div class=\"single_content\" style=\"margin:0px;padding:0px;width:764px;float:left;font-family:微软雅黑;line-height:normal;white-space:normal;\">\r\n	<p class=\"MsoNormal\" style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<img alt=\"\" align=\"left\" width=\"200\" height=\"396\" src=\"http://www.ksldesign.net/Files/UploadFiles/image/KSL%E9%A6%96%E5%B8%AD%E8%AE%BE%E8%AE%A1%E5%B8%88%20%E6%9E%97%E5%86%A0%E6%88%90.jpg\" style=\"margin:0px;padding:0px 20px 0px 0px;border-width:initial;border-style:none;\" /> \r\n	</p>\r\n	<p class=\"MsoNormal\" style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<span style=\"font-size:large;\">林冠成</span> \r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<br />\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		KSL設計事務所 創始人/董事長<o:p></o:p>\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		高級室內建築師<o:p></o:p>\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		國際NLP導師\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		2012年度“精英設計師”<o:p></o:p>\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		2013年度“十大最具影響力設計師”\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		2014年度“十大國際酒店設計師”\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		2015年度“中國十大高端室內設計師”\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<br />\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<br />\r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		林冠成先生于2007年創立“KSL設計事務所”，在業界有著近20年豐富的建築概念設計、室內設計、軟裝配飾設計等經驗，引领着KSL設計事務所在五星級酒店、頂級私人會所、高端樣板房、售楼处與高級寫字樓等領域室內設計上頗有造詣！同時讓KSL設計團隊贏得了與中洲、中海信、深長城、粵華、天倫、黃河、萬怡、阳光华艺等地産集團的信任、肯定，並確認了長期合作的關系。\r\n	</p>\r\n	<p class=\"MsoNormal\" align=\"left\" style=\"margin:0cm 0cm 0pt;padding:0px;color:#AAAAAA;line-height:19.5pt;\">\r\n		<span lang=\"EN-US\" style=\"font-size:12pt;font-family:微软雅黑, sans-serif;\"><o:p></o:p></span> \r\n	</p>\r\n	<p style=\"margin-top:0px;margin-bottom:0px;padding:0px;color:#AAAAAA;line-height:26px;\">\r\n		<br />\r\n	</p>\r\n</div>\r\n<p>\r\n	<br />\r\n</p>\r\n<p style=\"color:#AAAAAA;font-family:微软雅黑;\">\r\n	<br />\r\n</p>\r\n<p class=\"MsoNormal\" align=\"left\" style=\"color:#AAAAAA;font-family:微软雅黑;\">\r\n	<span style=\"font-size:12pt;font-family:微软雅黑, sans-serif;\"></span> \r\n</p>\r\n<p style=\"color:#AAAAAA;font-family:微软雅黑;\">\r\n	<br />\r\n</p>', '', '2', '1', '0', '1468166400', '', '', '', '50', '', '', null, '0', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('91', '14', '人才招聘', null, '人才招聘', '', '2', '1', '0', '1468245068', '', '', '', '50', '', '', null, '0', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('92', '15', '合作伙伴', null, '<a href=\"/Info/About/cat_id/15.html\">合作伙伴</a>', '', '2', '1', '0', '1468245076', '', '', '', '50', '', '', null, '0', '', '0', '', '', '');
INSERT INTO `tp_article` VALUES ('93', '4', '联系我们', null, '<div class=\"a1\">\r\n	<h4>\r\n		深圳\r\n	</h4>\r\n	<p>\r\n		深圳市福田区天安数码时代大厦A座1313\r\n	</p>\r\n	<h5 class=\"clearfix\">\r\n		<span><i class=\"fa fa-phone\"></i>电话：+86.755.23977700</span> <span><i class=\"fa fa-weixin\"></i>微信：+86.755.23977755</span> <span><i class=\"fa fa-envelope\"></i>E-mail：kslam@163.com</span> \r\n	</h5>\r\n</div>\r\n<div class=\"a2\">\r\n	<h4>\r\n		深圳\r\n	</h4>\r\n	<p>\r\n		深圳市福田区天安数码时代大厦A座1313\r\n	</p>\r\n	<h5 class=\"clearfix\">\r\n		<span><i class=\"fa fa-phone\"></i>电话：+86.755.23977700</span> <span><i class=\"fa fa-weixin\"></i>微信：+86.755.23977755</span> <span><i class=\"fa fa-envelope\"></i>E-mail：kslam@163.com</span> \r\n	</h5>\r\n</div>', '', '2', '1', '0', '1468166400', '', '', '', '50', '', '', null, '0', '', '0', '', '', '');

-- ----------------------------
-- Table structure for `tp_articlecat`
-- ----------------------------
DROP TABLE IF EXISTS `tp_articlecat`;
CREATE TABLE `tp_articlecat` (
  `cat_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cat_img` varchar(200) DEFAULT NULL,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_en_name` varchar(60) DEFAULT NULL,
  `cat_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `is_red` tinyint(1) NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `cat_type` (`cat_type`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_articlecat
-- ----------------------------
INSERT INTO `tp_articlecat` VALUES ('1', null, '作品案例', '', '1', '', '', '1', '0', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('2', null, '新闻资讯', null, '1', '', '', '2', '0', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('3', null, '关于我们', null, '1', '', '', '3', '0', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('4', null, '联系我们', null, '1', '', '', '4', '0', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('5', null, '公司动态', '', '1', '', '', '1', '2', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('6', null, '媒体报道', null, '1', '', '', '2', '2', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('7', null, '行业资讯', null, '1', '', '', '3', '2', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('8', null, '设计百科', null, '1', '', '', '4', '2', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('9', null, '佳作欣赏', null, '1', '', '', '5', '2', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('10', null, '办公', null, '1', '', '', '1', '1', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('11', null, '别墅', null, '1', '', '', '2', '1', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('12', null, '豪宅', null, '1', '', '', '3', '1', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('13', null, '创始人', null, '1', '', '', '1', '3', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('14', null, '人才招聘', null, '1', '', '', '2', '3', '', '0', '0', '0');
INSERT INTO `tp_articlecat` VALUES ('15', null, '合作伙伴', null, '1', '', '', '3', '3', '', '0', '0', '0');

-- ----------------------------
-- Table structure for `tp_articleimg`
-- ----------------------------
DROP TABLE IF EXISTS `tp_articleimg`;
CREATE TABLE `tp_articleimg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `title` varchar(120) CHARACTER SET latin1 DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `original_img` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `thumb_img` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `img_size` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_articleimg
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_download`
-- ----------------------------
DROP TABLE IF EXISTS `tp_download`;
CREATE TABLE `tp_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `file_url` varchar(200) NOT NULL,
  `file_desc` varchar(200) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_download
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_goods`
-- ----------------------------
DROP TABLE IF EXISTS `tp_goods`;
CREATE TABLE `tp_goods` (
  `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_price` varchar(20) DEFAULT NULL,
  `cat_id` smallint(5) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `goods_img` varchar(200) DEFAULT NULL,
  `content` longtext NOT NULL,
  `content1` text,
  `content2` text,
  `related_downloads` text,
  `downloads` text,
  `keywords` varchar(255) DEFAULT '',
  `goods_type` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(8) NOT NULL DEFAULT '50',
  `short` text,
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  PRIMARY KEY (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_goodscat`
-- ----------------------------
DROP TABLE IF EXISTS `tp_goodscat`;
CREATE TABLE `tp_goodscat` (
  `cat_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `cat_desc` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cat_attr` text,
  `original_img` varchar(200) DEFAULT NULL,
  `thumb_img` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `cat_type` (`cat_type`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_goodscat
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_goodsimg`
-- ----------------------------
DROP TABLE IF EXISTS `tp_goodsimg`;
CREATE TABLE `tp_goodsimg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `goods_id` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `original_img` varchar(200) DEFAULT NULL,
  `thumb_img` varchar(200) DEFAULT NULL,
  `img_size` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `activity_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_goodsimg
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_goods_excontent`
-- ----------------------------
DROP TABLE IF EXISTS `tp_goods_excontent`;
CREATE TABLE `tp_goods_excontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_goods_excontent
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_guestbook`
-- ----------------------------
DROP TABLE IF EXISTS `tp_guestbook`;
CREATE TABLE `tp_guestbook` (
  `guestbook_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `add_time` varchar(30) NOT NULL,
  `real_ip` varchar(10) NOT NULL,
  PRIMARY KEY (`guestbook_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_guestbook
-- ----------------------------
INSERT INTO `tp_guestbook` VALUES ('1', 'TT', '', '15361360253', '1058514799@qq.com', '', '测试！！！！！！！！！！！！！！！！！', '1425375405', '');
INSERT INTO `tp_guestbook` VALUES ('2', 'fvb', '', '13631302268', 'rewgt@126.com', '', 'weewrgrg', '1427772068', '');

-- ----------------------------
-- Table structure for `tp_partner`
-- ----------------------------
DROP TABLE IF EXISTS `tp_partner`;
CREATE TABLE `tp_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `level` varchar(20) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `weixin` varchar(30) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `idcard` varchar(30) DEFAULT NULL,
  `no` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_partner
-- ----------------------------

-- ----------------------------
-- Table structure for `tp_role`
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(60) NOT NULL DEFAULT '',
  `action_list` text NOT NULL,
  `role_describe` text,
  PRIMARY KEY (`role_id`),
  KEY `user_name` (`role_name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_role
-- ----------------------------
INSERT INTO `tp_role` VALUES ('6', '网管', 'cat_2,cat_15,cat_16,cat_17,cat_18,cat_19,cat_20,cat_21,cat_22,cat_23,cat_3,cat_24,cat_25,cat_26,cat_27,cat_28,cat_29,cat_30,cat_31,cat_32,cat_167,cat_168,cat_4,cat_33,cat_34,cat_35,cat_36,cat_37,cat_38,cat_39,cat_40,cat_41,cat_42,cat_43,cat_44,cat_45,cat_46,cat_47,cat_48,cat_49,cat_50,cat_51,cat_52,cat_53,cat_5,cat_146,cat_145,cat_144,cat_143,cat_147,cat_6,cat_61,cat_62,cat_63,cat_65,cat_66,cat_67,cat_68,cat_71,cat_7,cat_72,cat_73,cat_74,cat_75,cat_76,cat_77,cat_78,cat_79,cat_80,cat_81,cat_8,cat_82,cat_83,cat_84,cat_85,cat_86,cat_87,cat_88,cat_89,cat_90,cat_91,cat_92,cat_93,cat_9,cat_95,cat_96,cat_97,cat_98,cat_99,cat_149,cat_150,cat_151,cat_10,cat_100,cat_101,cat_102,cat_11,cat_104,cat_105,cat_106,cat_107,cat_108,cat_109,cat_110,cat_111,cat_112,cat_113,cat_12,cat_114,cat_115,cat_116,cat_117,cat_118,cat_119,cat_120,cat_121,cat_122,cat_123,cat_124,cat_125,cat_126,cat_127,cat_128,cat_129,cat_130,cat_13,cat_131,cat_132,cat_133,cat_134,cat_14,cat_135,cat_136,cat_137,Helps,cat_139,cat_140,cat_141,cat_142,cat_148,Columns,Forms,Attr,Message,Images,Member,Systems,Privilege', '超级管理员');
INSERT INTO `tp_role` VALUES ('7', '管理员', 'cat_10,cat_11,cat_12,Helps,Columns,Forms,Attr,Message,Images,Member,Systems', '');

-- ----------------------------
-- Table structure for `tp_survey`
-- ----------------------------
DROP TABLE IF EXISTS `tp_survey`;
CREATE TABLE `tp_survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(50) NOT NULL,
  `file_url` varchar(200) NOT NULL,
  `add_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_survey
-- ----------------------------
INSERT INTO `tp_survey` VALUES ('6', '各大航空公司简介.doc', 'Uploads/survey/20150304/54f697afb724c.doc', '1425446831', '1', 'TTT', '1058514799@qq.com');
INSERT INTO `tp_survey` VALUES ('7', '各大航空公司简介.doc', 'Uploads/survey/20150304/54f6c7fec3216.doc', '1425459198', '1', 'TTT', '1058514799@qq.com');

-- ----------------------------
-- Table structure for `tp_users`
-- ----------------------------
DROP TABLE IF EXISTS `tp_users`;
CREATE TABLE `tp_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `reg_time` int(11) NOT NULL,
  `last_login_time` int(11) NOT NULL,
  `last_login_ip` varchar(30) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_users
-- ----------------------------
INSERT INTO `tp_users` VALUES ('1', 'TTT', 'e10adc3949ba59abbe56e057f20f883e', '15361360253', '1058514799@qq.com', '互诺', '1425433790', '1425463076', '59.41.204.221');
INSERT INTO `tp_users` VALUES ('2', 'RRRR', 'e10adc3949ba59abbe56e057f20f883e', '15361360252', '10585147992@qq.com', 'RRR', '1425459055', '1425459055', '59.41.204.221');
INSERT INTO `tp_users` VALUES ('3', '陈', 'e10adc3949ba59abbe56e057f20f883e', '13278945611', '123@123.com', '互诺科技', '1425522150', '1425522177', '59.42.14.239');
