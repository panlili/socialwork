/*
Navicat MySQL Data Transfer

Source Server         : utf8
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2012-07-26 20:48:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `sum_camera`
-- ----------------------------
DROP TABLE IF EXISTS `sum_camera`;
CREATE TABLE `sum_camera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hdr_id` varchar(40) DEFAULT NULL COMMENT '录像机的设备号',
  `name` varchar(50) DEFAULT NULL COMMENT '摄像头的名字',
  `model` char(20) DEFAULT NULL COMMENT '录像机型号',
  `channels` tinyint(4) DEFAULT NULL COMMENT '通道数量',
  `remain` tinyint(4) DEFAULT NULL COMMENT '剩余通道数',
  `ip` varchar(50) DEFAULT NULL COMMENT '访问的ip',
  `comment` varchar(500) DEFAULT NULL COMMENT '备注，其他的信息',
  `port` varchar(4) DEFAULT NULL COMMENT '端口',
  `yard_id` int(11) DEFAULT NULL COMMENT '所属院落id',
  `stat` tinyint(4) DEFAULT NULL COMMENT '状态: 00默认，01异常，03暂停',
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_camera`,`jz_camera`);

-- ----------------------------
-- Table structure for `sum_channel`
-- ----------------------------
DROP TABLE IF EXISTS `sum_channel`;
CREATE TABLE `sum_channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hdr_id` varchar(50) DEFAULT NULL COMMENT '对应的hdr',
  `number` tinyint(4) NOT NULL COMMENT '通道编号',
  `name` varchar(60) NOT NULL DEFAULT '空' COMMENT '通道名称',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '录像机状态: 00未使用，01正常，02故障',
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_channel`,`jz_channel`);

-- ----------------------------
-- Table structure for `sum_citizen`
-- ----------------------------
DROP TABLE IF EXISTS `sum_citizen`;
CREATE TABLE `sum_citizen` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '居民序号',
  `house_id` int(10) unsigned DEFAULT NULL COMMENT 'house表的id，表示这个人属于哪个房屋',
  `yard_id` int(11) DEFAULT NULL,
  `name` varchar(20) NOT NULL COMMENT '居民名字',
  `relation_with_householder` varchar(50) DEFAULT NULL COMMENT '与房屋户主的关系，如配偶，儿子等',
  `id_card` varchar(18) DEFAULT NULL COMMENT '居民身份证号码',
  `sex` varchar(2) NOT NULL COMMENT '居民性别',
  `nation` varchar(10) DEFAULT NULL COMMENT '民族',
  `birthday` date DEFAULT NULL COMMENT '居民生日',
  `is_fertility` varchar(2) DEFAULT '否' COMMENT '是否领取计划生育指标',
  `marry_info` varchar(50) DEFAULT NULL COMMENT '民居婚姻情况',
  `education` varchar(50) DEFAULT NULL COMMENT '居民文化程度',
  `political_status` varchar(50) DEFAULT NULL COMMENT '居民政治面貌',
  `is_special` varchar(2) DEFAULT '否' COMMENT '是否特殊人群',
  `employee` varchar(50) DEFAULT NULL COMMENT '居民就业情况',
  `is_low_level` varchar(2) DEFAULT '否' COMMENT '是否低保',
  `is_disability` varchar(2) DEFAULT '否' COMMENT '是否残疾',
  `is_low_rent` varchar(2) DEFAULT '否' COMMENT '是否廉租房',
  `is_long_live` varchar(2) DEFAULT '否' COMMENT '是否长寿金',
  `household` varchar(100) DEFAULT NULL COMMENT '户口地址',
  `telephone` varchar(30) DEFAULT NULL COMMENT '联系电话',
  `collection_date` date DEFAULT NULL COMMENT '采集日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `house_id` (`house_id`),
  KEY `id_card` (`id_card`),
  KEY `name` (`name`),
  KEY `nation` (`nation`),
  KEY `bool` (`is_fertility`,`is_special`,`is_low_level`,`is_disability`,`is_low_rent`,`is_long_live`),
  KEY `enumtype` (`relation_with_householder`,`marry_info`,`education`,`political_status`,`employee`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_citizen`,`jz_citizen`) COMMENT='居民信息表，对应其people表';

-- ----------------------------
-- Table structure for `sum_house`
-- ----------------------------
DROP TABLE IF EXISTS `sum_house`;
CREATE TABLE `sum_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yard_id` int(10) DEFAULT NULL COMMENT '所在落院的id，与yard表一对多关系',
  `address` varchar(100) DEFAULT '' COMMENT '房屋地址',
  `is_free` varchar(2) DEFAULT '否' COMMENT '房屋是否空闲',
  `is_fit` varchar(2) DEFAULT '否' COMMENT '人户一致',
  `is_lowrent` varchar(2) DEFAULT '否' COMMENT '是否廉租房',
  `is_floor` varchar(2) DEFAULT '否' COMMENT '否是是平房',
  `is_afford` varchar(2) DEFAULT '否' COMMENT '否是购买了经济适用房',
  `is_taiwan` varchar(2) DEFAULT '否' COMMENT '否是台属',
  `is_army` varchar(2) DEFAULT '否' COMMENT '否是军属',
  `collection_date` date DEFAULT NULL COMMENT '采集日期',
  `address_1` int(4) DEFAULT NULL COMMENT '**街**号1栋，代表1,栋',
  `address_2` int(4) DEFAULT NULL COMMENT '**街**号*栋1单元，代表1，单元',
  `address_3` int(4) DEFAULT NULL COMMENT '**街**号*栋*单元1楼，代表1楼，楼层',
  `address_4` int(4) DEFAULT NULL COMMENT '**街45号,代表45.具体房号',
  `address_other` int(4) DEFAULT NULL COMMENT '这是只有是平房的编码，**街附34号，代表34',
  `contactor` varchar(10) DEFAULT NULL COMMENT '联系人',
  `is_fuel` varchar(2) DEFAULT '否' COMMENT '否是有燃油补贴',
  `telephone` varchar(30) DEFAULT NULL COMMENT '联系人电话',
  `house_id` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `bool` (`is_free`,`is_fit`,`is_lowrent`,`is_floor`,`is_afford`,`is_taiwan`,`is_army`,`is_fuel`),
  KEY `address` (`yard_id`,`address_1`,`address_2`,`address_3`,`address_4`,`address_other`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_house`,`jz_house`) COMMENT='房屋信息表，对应其house_base表';

-- ----------------------------
-- Table structure for `sum_map_mark`
-- ----------------------------
DROP TABLE IF EXISTS `sum_map_mark`;
CREATE TABLE `sum_map_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `x` int(11) DEFAULT NULL COMMENT '地图标记的x坐标',
  `y` int(11) DEFAULT NULL COMMENT '地图标记Y坐标',
  `type` int(11) DEFAULT NULL COMMENT '地图标记类型',
  `target` int(11) DEFAULT NULL COMMENT '应对的对象id（比如院落id）',
  `has_child` varchar(2) DEFAULT '否' COMMENT '否是有子元素，比如有摄像机',
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_map_mark`,`jz_map_mark`) COMMENT='地图标记信息表';

-- ----------------------------
-- Table structure for `sum_map_set`
-- ----------------------------
DROP TABLE IF EXISTS `sum_map_set`;
CREATE TABLE `sum_map_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mapWidth` int(11) DEFAULT NULL COMMENT '原始图宽',
  `mapHeight` int(11) DEFAULT NULL COMMENT '原始图高',
  `mapAreaWidth` int(11) DEFAULT NULL COMMENT '地图显示区域宽',
  `mapAreaHeight` int(11) DEFAULT NULL COMMENT '地图显示区域高',
  `pieceWidth` int(11) DEFAULT NULL COMMENT '地图切片宽',
  `pieceHeight` int(11) DEFAULT NULL COMMENT '地图切片高',
  `piecePath` varchar(100) DEFAULT NULL COMMENT '切片的路径',
  `methodeAddress` varchar(100) DEFAULT NULL COMMENT '地图方法的完整路径',
  `mapbaseX` int(11) DEFAULT NULL COMMENT '地图初始基点位置',
  `mapbaseY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_map_set`,`jz_map_set`);

-- ----------------------------
-- Table structure for `sum_ngo`
-- ----------------------------
DROP TABLE IF EXISTS `sum_ngo`;
CREATE TABLE `sum_ngo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '组织名称',
  `chairman` varchar(50) NOT NULL COMMENT '负责人姓名',
  `telephone` varchar(20) DEFAULT '' COMMENT '负责人电话',
  `register_date` date DEFAULT NULL COMMENT '成立日期',
  `registerplace` varchar(100) DEFAULT '' COMMENT '登记备案机关',
  `scope` varchar(100) DEFAULT NULL COMMENT '活动类型',
  `introduce` text COMMENT '简介',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_ngo`) COMMENT='非政府组织（Non-Governmental Organization），民间组织表，对应其pope_org\r\n';

-- ----------------------------
-- Table structure for `sum_old`
-- ----------------------------
DROP TABLE IF EXISTS `sum_old`;
CREATE TABLE `sum_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '老人信息id',
  `address` varchar(500) DEFAULT NULL COMMENT '现住地',
  `name` varchar(50) DEFAULT NULL COMMENT '老人名字',
  `sex` char(5) DEFAULT NULL COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `id_card` varchar(100) DEFAULT NULL COMMENT '身份证号码',
  `nation` varchar(20) DEFAULT NULL COMMENT '民族',
  `household_reg` varchar(500) DEFAULT NULL COMMENT '户籍地',
  `child` varchar(50) DEFAULT NULL COMMENT '往来密切子女姓名',
  `child_tel` varchar(100) DEFAULT NULL COMMENT '子女电话',
  `friend` varchar(50) DEFAULT NULL COMMENT '往来密切亲友姓名',
  `friend_tel` varchar(100) DEFAULT NULL COMMENT '亲友电话',
  `health` varchar(20) DEFAULT NULL COMMENT '康健情况',
  `ticket` int(11) DEFAULT NULL COMMENT '服务点数',
  `live` varchar(20) DEFAULT NULL COMMENT '居住情况',
  `income_source` varchar(20) DEFAULT NULL COMMENT '收入来源',
  `self_care` varchar(20) DEFAULT NULL COMMENT '理自能力',
  `retirement` varchar(20) DEFAULT NULL COMMENT '养老方式',
  `contact` varchar(50) DEFAULT NULL COMMENT '联系方式',
  `daycare` tinyint(1) DEFAULT NULL COMMENT '是否需要日托服务',
  `food` tinyint(1) DEFAULT NULL COMMENT '就餐',
  `clean` tinyint(1) DEFAULT NULL COMMENT '保洁服务',
  `buy_agent` tinyint(1) DEFAULT NULL COMMENT '代购服务',
  `daynurse` tinyint(1) DEFAULT NULL COMMENT '日间护理',
  `repair` tinyint(1) DEFAULT NULL COMMENT '物业维修',
  `talk` tinyint(1) DEFAULT NULL COMMENT '心里疏导',
  `washing` tinyint(1) DEFAULT NULL COMMENT '上门洗衣',
  `law` tinyint(1) DEFAULT NULL COMMENT '法律援助',
  `collectionn_date` date DEFAULT NULL COMMENT '采集日期',
  `cancel_mark` bit(1) DEFAULT NULL COMMENT '注销标志',
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_old`,`jz_old`) COMMENT='老人信息表';

-- ----------------------------
-- Table structure for `sum_organization`
-- ----------------------------
DROP TABLE IF EXISTS `sum_organization`;
CREATE TABLE `sum_organization` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '单位序号',
  `street_id` int(10) unsigned DEFAULT NULL COMMENT 'street表的id，表示这个组织在哪个街道',
  `name` varchar(255) NOT NULL COMMENT '单位名称',
  `chairman` varchar(50) DEFAULT NULL COMMENT '法人代表',
  `address` varchar(255) NOT NULL COMMENT '单位地址',
  `code` varchar(50) DEFAULT '' COMMENT '组织机构代码',
  `type` varchar(100) DEFAULT '' COMMENT '机构类型，如事业单位，民营企业等',
  `license` varchar(100) DEFAULT NULL COMMENT '商工营业执照号码',
  `taxcode` varchar(100) DEFAULT NULL COMMENT '税务登记证号码',
  `employee` varchar(20) DEFAULT NULL COMMENT '用工人数',
  `postcode` varchar(10) DEFAULT '' COMMENT '邮政编码',
  `contactor` varchar(10) DEFAULT '' COMMENT '联系人姓名',
  `telephone` varchar(50) DEFAULT NULL COMMENT '联系人电话',
  `note` text COMMENT '备注信息',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_organization`,`jz_organization`) COMMENT='驻区单位表，对应其group表';

-- ----------------------------
-- Table structure for `sum_parter`
-- ----------------------------
DROP TABLE IF EXISTS `sum_parter`;
CREATE TABLE `sum_parter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `party_id` int(10) DEFAULT NULL COMMENT '所在党支部',
  `category` varchar(255) DEFAULT NULL COMMENT '班子成员+正式党员+预备党员+发展对象+积极分子',
  `name` varchar(50) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL COMMENT '出生日期',
  `nation` varchar(20) DEFAULT NULL COMMENT '民族',
  `hometown` varchar(255) DEFAULT NULL COMMENT ' 籍贯',
  `joindate` varchar(255) DEFAULT NULL COMMENT '入党日期',
  `education` varchar(50) DEFAULT NULL COMMENT '学历',
  `degree` varchar(50) DEFAULT NULL COMMENT '学位',
  `level` varchar(255) DEFAULT NULL COMMENT '专业技术职务',
  `position` varchar(255) DEFAULT NULL COMMENT '党内职务，党支部书记+党支部组织委员+党支部宣传委员',
  `idcard` varchar(255) DEFAULT NULL COMMENT '身份证',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `liveplace` varchar(255) DEFAULT NULL COMMENT '现居住地',
  `indate` varchar(255) DEFAULT NULL COMMENT '进入当前党支部日期',
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`party_id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC INSERT_METHOD=LAST UNION=(`sjf_parter`);

-- ----------------------------
-- Table structure for `sum_party`
-- ----------------------------
DROP TABLE IF EXISTS `sum_party`;
CREATE TABLE `sum_party` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '织组名称',
  `type` varchar(255) DEFAULT NULL COMMENT '组织类型',
  `place` varchar(255) DEFAULT NULL COMMENT '党组织属地关系',
  `found` varchar(255) DEFAULT NULL COMMENT '批准建立党组织日期',
  `information` text COMMENT '单位信息',
  `address` varchar(255) DEFAULT NULL COMMENT '讯通地址',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `postcode` int(10) DEFAULT NULL COMMENT '邮政编码',
  PRIMARY KEY (`id`),
  KEY `id` (`name`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC INSERT_METHOD=LAST UNION=(`sjf_party`);

-- ----------------------------
-- Table structure for `sum_service`
-- ----------------------------
DROP TABLE IF EXISTS `sum_service`;
CREATE TABLE `sum_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '社区服务id',
  `district` varchar(100) DEFAULT NULL COMMENT '所属区',
  `street_office` varchar(100) DEFAULT NULL COMMENT '所属办事处',
  `community` varchar(100) DEFAULT NULL COMMENT '所属社区',
  `type` varchar(50) DEFAULT NULL COMMENT '服务类型',
  `name` varchar(200) DEFAULT NULL COMMENT '服务名',
  `desc` varchar(500) DEFAULT NULL COMMENT '服务描述',
  `cost` int(10) DEFAULT NULL COMMENT '点券值',
  `add_time` datetime DEFAULT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_service`,`jz_service`) COMMENT='社区服务表';

-- ----------------------------
-- Table structure for `sum_service_log`
-- ----------------------------
DROP TABLE IF EXISTS `sum_service_log`;
CREATE TABLE `sum_service_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_card` varchar(50) DEFAULT NULL,
  `service` int(11) DEFAULT NULL,
  `expense` int(11) DEFAULT NULL,
  `optime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_service_log`,`jz_service_log`);

-- ----------------------------
-- Table structure for `sum_store`
-- ----------------------------
DROP TABLE IF EXISTS `sum_store`;
CREATE TABLE `sum_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '街临商铺序号',
  `street_id` int(10) unsigned DEFAULT NULL COMMENT '在所街道id，对应street表，一对多关系',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '铺商名称',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '商铺地址',
  `owner` varchar(50) DEFAULT NULL COMMENT '店主',
  `telephone` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `license` varchar(100) DEFAULT NULL COMMENT '营业执照编号',
  `health` varchar(100) DEFAULT NULL COMMENT '卫生许可证',
  `taxcode` varchar(200) DEFAULT NULL COMMENT '务税登记证号码',
  `note` text COMMENT '备注',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_store`,`jz_store`) COMMENT='临街商铺表,对应其store_unit表';

-- ----------------------------
-- Table structure for `sum_street`
-- ----------------------------
DROP TABLE IF EXISTS `sum_street`;
CREATE TABLE `sum_street` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_street`,`jz_street`);

-- ----------------------------
-- Table structure for `sum_yard`
-- ----------------------------
DROP TABLE IF EXISTS `sum_yard`;
CREATE TABLE `sum_yard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mapMark_number` int(11) DEFAULT NULL COMMENT '地图关联号',
  `street_id` int(11) DEFAULT NULL COMMENT '应对的街道id',
  `address` varchar(255) DEFAULT NULL COMMENT '落院地址',
  `number` varchar(20) DEFAULT NULL COMMENT '院落编号',
  `name` varchar(255) DEFAULT NULL COMMENT '院落名',
  `building_count` int(11) DEFAULT NULL COMMENT '院落楼栋数',
  `building_age` year(4) DEFAULT NULL COMMENT '建筑年代',
  `area` int(11) DEFAULT NULL COMMENT '建筑面积',
  `admin_org` varchar(20) DEFAULT NULL COMMENT '自治管理组织机构',
  `admin_name` varchar(20) DEFAULT NULL COMMENT '管理员列表',
  `cancel_mark` bit(1) DEFAULT NULL COMMENT '注销标志',
  `edit_time` datetime DEFAULT NULL COMMENT '修改时间',
  `collection_time` datetime DEFAULT NULL COMMENT '采集时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `street_id` (`street_id`),
  KEY `address` (`address`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_yard`,`jz_yard`) COMMENT='院落信息表';

-- ----------------------------
-- Table structure for `sum_yardadmin`
-- ----------------------------
DROP TABLE IF EXISTS `sum_yardadmin`;
CREATE TABLE `sum_yardadmin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yard_id` int(10) NOT NULL,
  `job` varchar(100) DEFAULT NULL COMMENT '管理人员的职务',
  `name` varchar(100) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL COMMENT '所属类型，是管理还是党支部',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `yard_id` (`yard_id`),
  KEY `man` (`job`,`name`,`type`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 INSERT_METHOD=LAST UNION=(`sjf_yardadmin`,`jz_yardadmin`);
