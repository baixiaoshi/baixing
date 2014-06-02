/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.5.36 : Database - baixing
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`baixing` /*!40100 DEFAULT CHARACTER SET gbk */;

USE `baixing`;

/*Table structure for table `dr_1_block` */

DROP TABLE IF EXISTS `dr_1_block`;

CREATE TABLE `dr_1_block` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '文本块名称',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文本块表';

/*Data for the table `dr_1_block` */

/*Table structure for table `dr_1_form` */

DROP TABLE IF EXISTS `dr_1_form`;

CREATE TABLE `dr_1_form` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `table` varchar(50) NOT NULL COMMENT '表名',
  `setting` text COMMENT '配置信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table` (`table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单模型表';

/*Data for the table `dr_1_form` */

/*Table structure for table `dr_1_navigator` */

DROP TABLE IF EXISTS `dr_1_navigator`;

CREATE TABLE `dr_1_navigator` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL COMMENT '上级id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '导航类型',
  `name` varchar(255) NOT NULL COMMENT '导航名称',
  `title` varchar(255) NOT NULL COMMENT 'seo标题',
  `url` varchar(255) NOT NULL COMMENT '导航地址',
  `thumb` varchar(255) NOT NULL COMMENT '图片标识',
  `show` tinyint(1) unsigned NOT NULL COMMENT '显示',
  `child` tinyint(1) unsigned NOT NULL COMMENT '是否有下级',
  `target` tinyint(1) unsigned NOT NULL COMMENT '是否站外链接',
  `displayorder` tinyint(3) NOT NULL COMMENT '显示顺序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `list` (`id`,`type`,`show`,`displayorder`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='网站导航表';

/*Data for the table `dr_1_navigator` */

insert  into `dr_1_navigator`(`id`,`pid`,`type`,`name`,`title`,`url`,`thumb`,`show`,`child`,`target`,`displayorder`) values (1,0,3,'FineCMS设计室','FineCMS官方设计室-专业Web团队','http://www.dayrui.com','',1,0,1,0),(2,0,3,'云商城','','http://store.dayrui.com','',1,0,1,0),(3,0,3,'FAQ','','http://faq.dayrui.com','',1,0,1,0),(4,0,3,'帮助中心','','http://help.dayrui.com','',1,0,1,0),(5,0,3,'客服中心','','http://i.dayrui.com','',1,0,1,0),(6,0,3,'技术论坛','','http://www.finecms.net','',1,0,1,0),(7,0,4,'FineCMS','这是一套神奇的系统','http://www.dayrui.com','http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner3.jpg',1,0,1,0),(8,0,4,'开放平台','开发者共享分成','http://www.dayrui.com/cms/','http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner1.jpg',1,0,1,0),(9,0,4,'群站支持','一站式管理255个站点','http://www.dayrui.com/cms/','http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner2.jpg',1,0,1,0),(10,0,0,'新闻模块','','http://www.baixing.cc/news/','',1,0,1,0),(11,0,0,'图片模块','','http://www.baixing.cc/photo/','',1,0,1,0),(12,0,0,'下载模块','','http://www.baixing.cc/down/','',1,0,1,0),(13,0,0,'租房模块','','http://www.baixing.cc/fang/','',1,0,1,0),(14,0,1,'官方网站','','http://www.dayrui.com','',1,0,1,0),(15,0,1,'技术论坛','','http://www.finecms.net','',1,0,1,0),(16,0,2,'关于我们','','http://www.baixing.cc/index.php?c=page&id=1','',1,0,1,0),(17,0,2,'联系我们','','http://www.baixing.cc/index.php?c=page&id=4','',1,0,1,0),(18,0,2,'网站地图','','http://www.baixing.cc/sitemap.xml','',1,0,1,0),(19,0,0,'会员空间','','http://www.baixing.cc/space/','',1,0,1,0),(20,0,0,'单网页','','http://www.baixing.cc/index.php?c=page&id=1','',1,0,1,0),(21,0,0,'图书模块','','http://www.baixing.cc/book/','',1,0,1,0),(22,0,0,'视频模块','','http://www.baixing.cc/video/','',1,0,1,0),(23,0,0,'专题模块','','http://www.baixing.cc/special/','',1,0,1,0);

/*Table structure for table `dr_1_news` */

DROP TABLE IF EXISTS `dr_1_news`;

CREATE TABLE `dr_1_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) DEFAULT NULL COMMENT '主题',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `description` text COMMENT '描述',
  `hits` mediumint(8) unsigned DEFAULT NULL COMMENT '浏览数',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者id',
  `author` varchar(50) NOT NULL COMMENT '作者名称',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `url` varchar(255) DEFAULT NULL COMMENT '地址',
  `tableid` smallint(5) unsigned NOT NULL COMMENT '副表id',
  `inputip` varchar(15) DEFAULT NULL COMMENT '录入者ip',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `jiage` varchar(20) DEFAULT NULL,
  `zhaopian` text,
  `sex` varchar(255) DEFAULT NULL,
  `xiaoyuanshehui` varchar(255) DEFAULT NULL,
  `gongsigeren` varchar(255) DEFAULT NULL,
  `bibeitiaojian` text,
  `provinces` mediumint(11) NOT NULL DEFAULT '0' COMMENT '省',
  `provalue` varchar(20) NOT NULL COMMENT '省值',
  `city` mediumint(11) NOT NULL DEFAULT '0' COMMENT '市',
  `cityvalue` varchar(20) NOT NULL COMMENT '市值',
  `area` mediumint(11) NOT NULL DEFAULT '0' COMMENT '县',
  `areavalue` varchar(20) NOT NULL DEFAULT '0' COMMENT '县值',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`,`updatetime`),
  KEY `status` (`status`),
  KEY `hits` (`hits`),
  KEY `displayorder` (`displayorder`,`updatetime`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='主表';

/*Data for the table `dr_1_news` */

insert  into `dr_1_news`(`id`,`catid`,`title`,`thumb`,`description`,`hits`,`uid`,`author`,`status`,`url`,`tableid`,`inputip`,`inputtime`,`updatetime`,`displayorder`,`jiage`,`zhaopian`,`sex`,`xiaoyuanshehui`,`gongsigeren`,`bibeitiaojian`,`provinces`,`provalue`,`city`,`cityvalue`,`area`,`areavalue`) values (1,9,'asdfsa','','',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401686998)',0,NULL,0,0,0,'fdsa','','男','校园','公司','',0,'',0,'',0,'0'),(2,9,'找家教','','我有很多空闲时间，想找一个家教',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401687168)',0,NULL,0,0,0,'40/h','a:3:{s:4:\\\"file\\\";a:1:{i:0;s:1:\\\"3\\\";}s:3:\\\"pan\\\";a:1:{i:0;s:0:\\\"\\\";}s:5:\\\"title\\\";a:1:{i:0;s:6:\\\"pic001\\\";}}','男','校园','公司','基础不能太差',0,'',0,'',0,'0'),(3,9,'代购','','你是我的东西',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401687275)',0,NULL,0,0,0,'20/件','','女','校园','个人','',0,'',0,'',0,'0'),(4,9,'hello world','','',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401698681)',0,NULL,0,0,0,'102452','','男','校园','公司','',0,'',0,'',0,'0'),(5,9,'HELLOWW','','DFSFASD',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401698894)',0,NULL,0,0,0,'SADFDAS','','男','校园','公司','',0,'',0,'',0,'0'),(6,9,'sdfas','','sdfas',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401699815)',0,NULL,0,0,0,'sdfasd','','男','校园','公司','',0,'',0,'',0,'0'),(7,12,'dsafafa','','asdfasf',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 12, 9, 1401699970)',0,NULL,0,0,0,'SADFasf','','男','校园','公司','',0,'',0,'',0,'0'),(8,9,'我的标题','','我要找一个份兼职工作',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 9, 9, 1401700023)',0,NULL,0,0,0,'20/h','','男','校园','公司','',0,'',0,'',0,'0'),(9,32,'dsafadsfa','','asdfasdf',0,2,'',9,'http://www.baixing.cc/news/index.php?c=show&id=INSERT INTO `dr_1_news_index` (`uid`, `catid`, `status`, `inputtime`) VALUES (2, 32, 9, 1401704085)',0,NULL,0,0,0,'adsfadsf','','男','校园','公司','',6,'山西',138,'阳泉',729,'平定');

/*Table structure for table `dr_1_news_buy_0` */

DROP TABLE IF EXISTS `dr_1_news_buy_0`;

CREATE TABLE `dr_1_news_buy_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_0` */

/*Table structure for table `dr_1_news_buy_1` */

DROP TABLE IF EXISTS `dr_1_news_buy_1`;

CREATE TABLE `dr_1_news_buy_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_1` */

/*Table structure for table `dr_1_news_buy_2` */

DROP TABLE IF EXISTS `dr_1_news_buy_2`;

CREATE TABLE `dr_1_news_buy_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_2` */

/*Table structure for table `dr_1_news_buy_3` */

DROP TABLE IF EXISTS `dr_1_news_buy_3`;

CREATE TABLE `dr_1_news_buy_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_3` */

/*Table structure for table `dr_1_news_buy_4` */

DROP TABLE IF EXISTS `dr_1_news_buy_4`;

CREATE TABLE `dr_1_news_buy_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_4` */

/*Table structure for table `dr_1_news_buy_5` */

DROP TABLE IF EXISTS `dr_1_news_buy_5`;

CREATE TABLE `dr_1_news_buy_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_5` */

/*Table structure for table `dr_1_news_buy_6` */

DROP TABLE IF EXISTS `dr_1_news_buy_6`;

CREATE TABLE `dr_1_news_buy_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_6` */

/*Table structure for table `dr_1_news_buy_7` */

DROP TABLE IF EXISTS `dr_1_news_buy_7`;

CREATE TABLE `dr_1_news_buy_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_7` */

/*Table structure for table `dr_1_news_buy_8` */

DROP TABLE IF EXISTS `dr_1_news_buy_8`;

CREATE TABLE `dr_1_news_buy_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_8` */

/*Table structure for table `dr_1_news_buy_9` */

DROP TABLE IF EXISTS `dr_1_news_buy_9`;

CREATE TABLE `dr_1_news_buy_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `score` int(10) unsigned NOT NULL COMMENT '使用虚拟币',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

/*Data for the table `dr_1_news_buy_9` */

/*Table structure for table `dr_1_news_category` */

DROP TABLE IF EXISTS `dr_1_news_category`;

CREATE TABLE `dr_1_news_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) NOT NULL COMMENT '所有上级id',
  `name` varchar(30) NOT NULL COMMENT '栏目名称',
  `letter` char(1) NOT NULL COMMENT '首字母',
  `dirname` varchar(30) NOT NULL COMMENT '栏目目录',
  `pdirname` varchar(100) NOT NULL COMMENT '上级目录',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有下级',
  `childids` text NOT NULL COMMENT '下级所有id',
  `thumb` varchar(255) NOT NULL COMMENT '栏目图片',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `permission` text COMMENT '会员权限',
  `setting` text NOT NULL COMMENT '属性配置',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `show` (`show`),
  KEY `module` (`pid`,`displayorder`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='栏目表';

/*Data for the table `dr_1_news_category` */

insert  into `dr_1_news_category`(`id`,`pid`,`pids`,`name`,`letter`,`dirname`,`pdirname`,`child`,`childids`,`thumb`,`show`,`permission`,`setting`,`displayorder`) values (1,0,'0','代取 代购 作业 游戏','y','daigou','',1,'1,9,10,11,12,30,31,32,33,34,35,36,37,38','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(2,0,'0','代送 代接 送客 代探','y','daisong','',1,'2,39,40,41,42,43,44,45,46,47,48,49,50','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(3,0,'0','生活常出租','d','shenghuo','',1,'3,16,17,51,52,53,54,55,56,57,58,59,60,61,62,63','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(4,0,'0','感情问题','f','ganqing','',1,'4,18,19,64,65,66,67,68,69,70,71,72,73','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(5,0,'0','代办','y','daiban','',1,'5,20,21,22,23,74,75,76,77,78,79,80,81','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(6,0,'0','兼职','s','jianzhi','',1,'6,24,25,82,83,84,85,86,87,88,89','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(7,0,'0','家政兼职','b','jiazheng','',1,'7,26,27,90,91','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(8,0,'0','义务劳动','f','yiwulaodong','',1,'8,28,29,92,93,94','',1,'','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(9,1,'0,1','快递件','d','kuaidijian','daigou/',0,'9','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(10,1,'0,1','邮信件','w','youxinjian','daigou/',0,'10','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(11,1,'0,1','明信片','p','mingxinpian','daigou/',0,'11','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(12,1,'0,1','汽车票','c','qichepiao','daigou/',0,'12','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(60,3,'0,3','购物清单','g','gouwuqingdan','shenghuo/',0,'60','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(61,3,'0,3','医院挂号','y','yiyuanguahao','shenghuo/',0,'61','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(62,3,'0,3','演唱会门票','y','yanchanghuimenpiao','shenghuo/',0,'62','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(63,3,'0,3','银行取号','y','yinxingquhao','shenghuo/',0,'63','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(56,3,'0,3','洗衣服','x','xiyifu','shenghuo/',0,'56','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(57,3,'0,3','买菜','m','maicai','shenghuo/',0,'57','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(58,3,'0,3','食品','s','shipin','shenghuo/',0,'58','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(59,3,'0,3','日常物品','r','richangwupin','shenghuo/',0,'59','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(51,3,'0,3','缴电话费','j','jiaodianhuafei','shenghuo/',0,'51','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(52,3,'0,3','缴电视费','j','jiaodianshifei','shenghuo/',0,'52','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(53,3,'0,3','房间清洁','f','fangjianqingjie','shenghuo/',0,'53','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(54,3,'0,3','厨房清洁','c','chufangqingjie','shenghuo/',0,'54','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(55,3,'0,3','煮饭','z','zhufan','shenghuo/',0,'55','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(16,3,'0,3','交电费','y','jiaodianfei','shenghuo/',0,'16','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(17,3,'0,3','交水费','f','jiaoshuifei','shenghuo/',0,'17','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(18,4,'0,4','代培/代喝','d','daipeidaihe','ganqing/',0,'18','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(19,4,'0,4','陪玩/逛街','z','peiwanguangjie','ganqing/',0,'19','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(20,5,'0,5','中国商标','t','zhongguoshangbiao','daiban/',0,'20','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(21,5,'0,5','香港商标','s','xianggangshangbiao','daiban/',0,'21','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(22,5,'0,5','外国商标','y','waiguoshangbiao','daiban/',0,'22','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(23,5,'0,5','中国签证','p','zhongguoqianzheng','daiban/',0,'23','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(24,6,'0,6','钟点工','j','zhongdiangong','jianzhi/',0,'24','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(25,6,'0,6','派发传单','j','paifachuandan','jianzhi/',0,'25','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(26,7,'0,7','搬家','z','banjia','jiazheng/',0,'26','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(27,7,'0,7','鲜花绿植','y','xianhualvzhi','jiazheng/',0,'27','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(28,8,'0,8','社区服务','x','shequfuwu','yiwulaodong/',0,'28','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(29,8,'0,8','火车站出行','a','huochezhnachuxing','yiwulaodong/',0,'29','',1,'a:12:{i:2;a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_1\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:2:\\\"10\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"5\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_2\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_3\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"3_4\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"20\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_5\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_6\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_7\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}s:3:\\\"4_8\\\";a:6:{s:3:\\\"add\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:1:\\\"0\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}i:0;a:0:{}i:1;a:0:{}i:9;a:5:{s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:1:\\\"0\\\";s:7:\\\"postnum\\\";s:2:\\\"10\\\";s:6:\\\"verify\\\";s:1:\\\"1\\\";s:9:\\\"postcount\\\";s:1:\\\"0\\\";}}','a:8:{s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:61:\\\"[第{page}页{join}]{name}{join}{modulename}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}s:7:\\\"urlmode\\\";s:1:\\\"0\\\";s:3:\\\"url\\\";a:5:{s:4:\\\"show\\\";s:0:\\\"\\\";s:9:\\\"show_page\\\";s:0:\\\"\\\";s:4:\\\"list\\\";s:0:\\\"\\\";s:9:\\\"list_page\\\";s:0:\\\"\\\";s:7:\\\"catjoin\\\";s:0:\\\"\\\";}s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:5:\\\"admin\\\";a:0:{}s:6:\\\"member\\\";a:0:{}}',0),(30,1,'0,1','火车票','h','huochepiao','daigou/',0,'30','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(31,1,'0,1','飞机票','f','feijipiao','daigou/',0,'31','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(32,1,'0,1','船票','c','chuanpiao','daigou/',0,'32','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(33,1,'0,1','电影票','d','dianyingpiao','daigou/',0,'33','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(34,1,'0,1','代练','d','dailian','daigou/',0,'34','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(35,1,'0,1','带徒弟','d','daitudi','daigou/',0,'35','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(36,1,'0,1','陪玩','p','peiwan','daigou/',0,'36','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(37,1,'0,1','代做作业','d','daizuozuoye','daigou/',0,'37','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(38,1,'0,1','枪手','q','qiangshou','daigou/',0,'38','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(39,2,'0,2','礼物','l','liwu','daisong/',0,'39','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(40,2,'0,2','送货','s','songhuo','daisong/',0,'40','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(41,2,'0,2','送花','s','songhua','daisong/',0,'41','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(42,2,'0,2','送饭','s','songfan','daisong/',0,'42','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(43,2,'0,2','接客人','j','jiekeren','daisong/',0,'43','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(44,2,'0,2','接亲戚','j','jieqinqi','daisong/',0,'44','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(45,2,'0,2','接朋友','j','jiepengyou','daisong/',0,'45','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(46,2,'0,2','接孩子','j','jiehaizi','daisong/',0,'46','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(47,2,'0,2','探亲','t','tanqin','daisong/',0,'47','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(48,2,'0,2','朋友','p','pengyou','daisong/',0,'48','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(49,2,'0,2','送礼探病','s','songlitanbing','daisong/',0,'49','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(50,2,'0,2','问候','w','wenhou','daisong/',0,'50','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(64,4,'0,4','娱乐','y','yule','ganqing/',0,'64','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(65,4,'0,4','运动','y','yundong','ganqing/',0,'65','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(66,4,'0,4','看电视','k','kandianshi','ganqing/',0,'66','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(67,4,'0,4','聚会','j','juhui','ganqing/',0,'67','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(68,4,'0,4','陪聊','p','peiliao','ganqing/',0,'68','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(69,4,'0,4','租男友','z','zunanyou','ganqing/',0,'69','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(70,4,'0,4','租女友','z','zunuyou','ganqing/',0,'70','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(71,4,'0,4','帮追男生','b','bangzhuinansheng','ganqing/',0,'71','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(72,4,'0,4','帮追女生','b','bangzhuinusheng','ganqing/',0,'72','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(73,4,'0,4','感情助手','g','ganqingzhushou','ganqing/',0,'73','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(74,5,'0,5','移民留学','y','yiminliuxue','daiban/',0,'74','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(75,5,'0,5','港澳通行证','g','gangaotongxingzheng','daiban/',0,'75','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(76,5,'0,5','工商注册','g','gongshangzhuce','daiban/',0,'76','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(77,5,'0,5','营业执照','y','yingyezhizhao','daiban/',0,'77','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(78,5,'0,5','办银行卡','b','banyinxingka','daiban/',0,'78','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(79,5,'0,5','办信用卡','b','banxinyongka','daiban/',0,'79','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(80,5,'0,5','入学手续','r','ruxueshouxu','daiban/',0,'80','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(81,5,'0,5','代订酒店','d','daidingjiudian','daiban/',0,'81','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(82,6,'0,6','问卷调查','w','wenjuandiaocha','jianzhi/',0,'82','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(83,6,'0,6','家教','j','jiajiao','jianzhi/',0,'83','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(84,6,'0,6','服务员','f','fuwuyuan','jianzhi/',0,'84','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(85,6,'0,6','翻译','f','fanyi','jianzhi/',0,'85','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(86,6,'0,6','促销员','c','cuxiaoyuan','jianzhi/',0,'86','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(87,6,'0,6','礼仪','l','liyi','jianzhi/',0,'87','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(88,6,'0,6','男女模特','n','nannumote','jianzhi/',0,'88','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(89,6,'0,6','客服','k','kefu','jianzhi/',0,'89','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(90,7,'0,7','家庭装修','j','jiatingzhuangxiu','jiazheng/',0,'90','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(91,7,'0,7','开锁换锁','k','kaisuohuansuo','jiazheng/',0,'91','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(92,8,'0,8','养老院出行','y','yanglaoyuanchuxing','yiwulaodong/',0,'92','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(93,8,'0,8','看望孤寡老人','k','kanwanggugualaoren','yiwulaodong/',0,'93','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0),(94,8,'0,8','关爱自闭儿童','g','guanaizibiertong','yiwulaodong/',0,'94','',1,NULL,'a:4:{s:7:\\\"urlrule\\\";s:1:\\\"0\\\";s:4:\\\"edit\\\";s:1:\\\"0\\\";s:3:\\\"seo\\\";a:4:{s:10:\\\"show_title\\\";s:74:\\\"[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}\\\";s:10:\\\"list_title\\\";s:58:\\\"[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}\\\";s:13:\\\"list_keywords\\\";s:0:\\\"\\\";s:16:\\\"list_description\\\";s:0:\\\"\\\";}s:8:\\\"template\\\";a:4:{s:8:\\\"pagesize\\\";s:2:\\\"20\\\";s:4:\\\"show\\\";s:9:\\\"show.html\\\";s:8:\\\"category\\\";s:13:\\\"category.html\\\";s:4:\\\"list\\\";s:9:\\\"list.html\\\";}}',0);

/*Table structure for table `dr_1_news_category_data` */

DROP TABLE IF EXISTS `dr_1_news_category_data`;

CREATE TABLE `dr_1_news_category_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目附加表';

/*Data for the table `dr_1_news_category_data` */

/*Table structure for table `dr_1_news_category_data_0` */

DROP TABLE IF EXISTS `dr_1_news_category_data_0`;

CREATE TABLE `dr_1_news_category_data_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目附加表';

/*Data for the table `dr_1_news_category_data_0` */

/*Table structure for table `dr_1_news_data_0` */

DROP TABLE IF EXISTS `dr_1_news_data_0`;

CREATE TABLE `dr_1_news_data_0` (
  `id` int(10) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附表';

/*Data for the table `dr_1_news_data_0` */

insert  into `dr_1_news_data_0`(`id`,`uid`,`catid`) values (1,1,9),(2,1,10),(3,1,12),(4,1,12),(5,1,12),(6,1,12),(7,1,11),(50,1,16),(51,1,16),(52,1,16),(53,1,16),(54,1,16),(55,1,16),(56,1,16),(57,1,16),(58,1,16),(59,1,16),(60,1,16),(61,1,16),(62,1,16),(63,1,16),(64,1,16),(65,1,16),(66,1,16),(67,1,17),(68,1,17),(69,1,17),(70,1,17),(71,1,17),(72,1,17),(73,1,17),(74,1,17),(75,1,17),(76,1,17),(77,1,17),(78,1,18),(79,1,18),(80,1,18),(81,1,18),(82,1,18),(83,1,18),(84,1,18),(85,1,18),(86,1,18),(87,1,18),(88,1,18),(89,1,18),(90,1,19),(91,1,19),(92,1,19),(93,1,19),(94,1,19),(95,1,19),(96,1,19),(97,1,19),(98,1,19),(99,1,19),(100,1,20),(101,1,20),(102,1,20),(103,1,20),(104,1,20),(105,1,20),(106,1,20),(107,1,20),(108,1,20),(109,1,21),(110,1,21),(111,1,21),(112,1,21),(113,1,21),(114,1,21),(115,1,21),(116,1,21),(117,1,21),(118,1,23),(119,1,23),(120,1,23),(121,1,23),(122,1,23),(123,1,23),(124,1,23),(125,1,23),(126,1,23),(127,1,23),(128,1,24),(129,1,24),(130,1,24),(131,1,24),(132,1,24),(133,1,25),(134,1,25),(135,1,25),(136,1,25),(137,1,25),(138,1,26),(139,1,26),(140,1,26),(141,1,26),(142,1,26),(143,1,26),(144,1,26),(145,1,26),(146,1,26),(147,1,26),(148,1,26),(149,1,27),(150,1,27),(151,1,27),(152,1,27),(153,1,27),(154,1,27),(155,1,27),(156,1,27),(157,1,27),(158,1,27),(159,1,28),(160,1,28),(161,1,28),(162,1,28),(163,1,28),(164,1,28),(165,1,28),(166,1,28),(167,1,29),(168,1,29),(169,1,9),(170,1,9),(171,1,9),(172,1,9),(173,1,9),(174,1,9),(175,1,9),(176,1,9),(177,1,9),(178,1,10),(179,1,10),(180,1,10),(181,1,10),(182,1,10),(183,1,10),(184,1,10),(185,1,10),(186,1,10),(187,1,11),(188,1,11),(189,1,11),(190,1,11),(191,1,11),(192,1,11),(193,1,11),(194,1,11),(195,1,12),(196,1,12),(197,1,12),(198,1,12),(199,1,12),(200,1,12),(201,1,12),(202,1,12),(203,1,12),(204,1,9),(205,1,9),(206,1,9),(0,2,32);

/*Table structure for table `dr_1_news_extend` */

DROP TABLE IF EXISTS `dr_1_news_extend`;

CREATE TABLE `dr_1_news_extend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `tableid` smallint(5) unsigned NOT NULL COMMENT '附表id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`),
  KEY `tableid` (`tableid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='扩展表';

/*Data for the table `dr_1_news_extend` */

/*Table structure for table `dr_1_news_favorite_0` */

DROP TABLE IF EXISTS `dr_1_news_favorite_0`;

CREATE TABLE `dr_1_news_favorite_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_0` */

/*Table structure for table `dr_1_news_favorite_1` */

DROP TABLE IF EXISTS `dr_1_news_favorite_1`;

CREATE TABLE `dr_1_news_favorite_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_1` */

/*Table structure for table `dr_1_news_favorite_2` */

DROP TABLE IF EXISTS `dr_1_news_favorite_2`;

CREATE TABLE `dr_1_news_favorite_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_2` */

/*Table structure for table `dr_1_news_favorite_3` */

DROP TABLE IF EXISTS `dr_1_news_favorite_3`;

CREATE TABLE `dr_1_news_favorite_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_3` */

/*Table structure for table `dr_1_news_favorite_4` */

DROP TABLE IF EXISTS `dr_1_news_favorite_4`;

CREATE TABLE `dr_1_news_favorite_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_4` */

/*Table structure for table `dr_1_news_favorite_5` */

DROP TABLE IF EXISTS `dr_1_news_favorite_5`;

CREATE TABLE `dr_1_news_favorite_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_5` */

/*Table structure for table `dr_1_news_favorite_6` */

DROP TABLE IF EXISTS `dr_1_news_favorite_6`;

CREATE TABLE `dr_1_news_favorite_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_6` */

/*Table structure for table `dr_1_news_favorite_7` */

DROP TABLE IF EXISTS `dr_1_news_favorite_7`;

CREATE TABLE `dr_1_news_favorite_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_7` */

/*Table structure for table `dr_1_news_favorite_8` */

DROP TABLE IF EXISTS `dr_1_news_favorite_8`;

CREATE TABLE `dr_1_news_favorite_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_8` */

/*Table structure for table `dr_1_news_favorite_9` */

DROP TABLE IF EXISTS `dr_1_news_favorite_9`;

CREATE TABLE `dr_1_news_favorite_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cid` int(10) unsigned NOT NULL COMMENT '文档id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT 'uid',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL地址',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`uid`,`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏夹表';

/*Data for the table `dr_1_news_favorite_9` */

/*Table structure for table `dr_1_news_flag` */

DROP TABLE IF EXISTS `dr_1_news_flag`;

CREATE TABLE `dr_1_news_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文档标记id',
  `id` int(10) unsigned NOT NULL COMMENT '文档内容id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  KEY `flag` (`flag`,`id`,`uid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标记表';

/*Data for the table `dr_1_news_flag` */

insert  into `dr_1_news_flag`(`flag`,`id`,`uid`,`catid`) values (1,178,1,10),(1,180,1,10),(1,181,1,10),(1,182,1,10),(1,176,1,9),(4,179,1,10),(4,178,1,10),(4,177,1,9),(4,176,1,9),(3,168,1,29),(3,190,1,11),(3,191,1,11),(3,192,1,11),(4,148,1,26),(4,168,1,29),(4,169,1,9),(4,170,1,9),(4,172,1,9),(4,173,1,9),(4,174,1,9),(4,175,1,9),(5,135,1,25),(5,136,1,25),(5,137,1,25),(5,138,1,26),(5,139,1,26),(5,140,1,26),(5,141,1,26),(5,142,1,26),(3,186,1,10),(5,171,1,9),(2,204,1,9),(2,205,1,9),(2,206,1,9),(1,183,1,10),(1,184,1,10),(1,185,1,10),(1,187,1,11),(1,188,1,11),(1,189,1,11),(1,190,1,11),(1,191,1,11),(1,167,1,29),(1,168,1,29),(1,169,1,9),(1,170,1,9),(1,171,1,9),(1,172,1,9),(1,173,1,9),(1,174,1,9),(4,180,1,10),(4,181,1,10),(4,182,1,10);

/*Table structure for table `dr_1_news_form` */

DROP TABLE IF EXISTS `dr_1_news_form`;

CREATE TABLE `dr_1_news_form` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '表单名称',
  `disabled` tinyint(1) unsigned NOT NULL COMMENT '是否禁用',
  `permission` text NOT NULL COMMENT '会员权限',
  `setting` text NOT NULL COMMENT '表单配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块表单表';

/*Data for the table `dr_1_news_form` */

/*Table structure for table `dr_1_news_html` */

DROP TABLE IF EXISTS `dr_1_news_html`;

CREATE TABLE `dr_1_news_html` (
  `id` bigint(18) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '相关id',
  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `type` tinyint(1) unsigned NOT NULL COMMENT '文件类型',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `filepath` text NOT NULL COMMENT '文件地址',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `rid` (`rid`),
  KEY `cid` (`cid`),
  KEY `type` (`type`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='html文件存储表';

/*Data for the table `dr_1_news_html` */

/*Table structure for table `dr_1_news_index` */

DROP TABLE IF EXISTS `dr_1_news_index`;

CREATE TABLE `dr_1_news_index` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=utf8 COMMENT='索引表';

/*Data for the table `dr_1_news_index` */

insert  into `dr_1_news_index`(`id`,`uid`,`catid`,`status`,`inputtime`) values (1,1,9,9,1370856094),(2,1,10,9,1370856142),(3,1,12,9,1370856380),(4,1,12,9,1370856432),(5,1,12,9,1370856490),(6,1,12,9,1370856572),(7,1,11,9,1370856605),(231,2,32,9,1401704085),(230,2,9,9,1401700023),(229,2,12,9,1401699970),(228,2,9,9,1401699815),(227,2,9,9,1401698894),(226,2,9,9,1401698681),(225,2,9,9,1401687275),(224,2,9,9,1401687168),(223,2,9,9,1401686998),(222,2,9,9,1401686635),(221,2,9,9,1401686635),(220,2,9,9,1401686586),(219,2,9,9,1401686586),(218,2,9,9,1401686581),(217,2,9,9,1401686581),(216,2,9,9,1401686555),(215,2,9,9,1401686555),(214,2,9,9,1401686547),(213,2,9,9,1401686547),(212,2,9,9,1401686414),(211,2,9,9,1401686414),(210,2,9,9,1401686402),(209,2,9,9,1401686402),(208,2,9,9,1401686394),(207,2,9,9,1401686394),(50,1,16,9,1370858227),(51,1,16,9,1370859555),(52,1,16,9,1370859572),(53,1,16,9,1370859589),(54,1,16,9,1370859622),(55,1,16,9,1370859647),(56,1,16,9,1370859678),(57,1,16,9,1370859695),(58,1,16,9,1370859728),(59,1,16,9,1370859746),(60,1,16,9,1370859764),(61,1,16,9,1370859789),(62,1,16,9,1370859807),(63,1,16,9,1370859825),(64,1,16,9,1370859844),(65,1,16,9,1370859867),(66,1,16,9,1370859884),(67,1,17,9,1370859907),(68,1,17,9,1370860019),(69,1,17,9,1370860045),(70,1,17,9,1370860056),(71,1,17,9,1370860081),(72,1,17,9,1370860117),(73,1,17,9,1370860138),(74,1,17,9,1370860162),(75,1,17,9,1370860183),(76,1,17,9,1370860207),(77,1,17,9,1370860254),(78,1,18,9,1370860281),(79,1,18,9,1370860341),(80,1,18,9,1370860367),(81,1,18,9,1370860398),(82,1,18,9,1370860423),(83,1,18,9,1370860484),(84,1,18,9,1370860505),(85,1,18,9,1370860538),(86,1,18,9,1370860559),(87,1,18,9,1370860578),(88,1,18,9,1370860602),(89,1,18,9,1370860626),(90,1,19,9,1370860655),(91,1,19,9,1370860682),(92,1,19,9,1370860699),(93,1,19,9,1370860717),(94,1,19,9,1370860734),(95,1,19,9,1370860757),(96,1,19,9,1370860797),(97,1,19,9,1370860833),(98,1,19,9,1370860906),(99,1,19,9,1370860933),(100,1,20,9,1370860963),(101,1,20,9,1370861001),(102,1,20,9,1370861020),(103,1,20,9,1370861060),(104,1,20,9,1370861086),(105,1,20,9,1370861106),(106,1,20,9,1370861130),(107,1,20,9,1370861152),(108,1,20,9,1370861192),(109,1,21,9,1370861225),(110,1,21,9,1370861266),(111,1,21,9,1370861288),(112,1,21,9,1370861312),(113,1,21,9,1370861390),(114,1,21,9,1370861427),(115,1,21,9,1370861457),(116,1,21,9,1370861477),(117,1,21,9,1370861509),(118,1,23,9,1370861588),(119,1,23,9,1370861627),(120,1,23,9,1370861642),(121,1,23,9,1370861661),(122,1,23,9,1370861677),(123,1,23,9,1370861697),(124,1,23,9,1370861728),(125,1,23,9,1370861754),(126,1,23,9,1370861783),(127,1,23,9,1370861802),(128,1,24,9,1370861814),(129,1,24,9,1370861849),(130,1,24,9,1370861870),(131,1,24,9,1370861892),(132,1,24,9,1370861937),(133,1,25,9,1370861966),(134,1,25,9,1370862004),(135,1,25,9,1370862032),(136,1,25,9,1370862063),(137,1,25,9,1370862085),(138,1,26,9,1370862116),(139,1,26,9,1370862193),(140,1,26,9,1370862215),(141,1,26,9,1370862242),(142,1,26,9,1370862315),(143,1,26,9,1370862331),(144,1,26,9,1370862365),(145,1,26,9,1370862420),(146,1,26,9,1370862496),(147,1,26,9,1370862517),(148,1,26,9,1370862546),(149,1,27,9,1370862581),(150,1,27,9,1370862627),(151,1,27,9,1370862652),(152,1,27,9,1370862672),(153,1,27,9,1370862697),(154,1,27,9,1370862742),(155,1,27,9,1370862772),(156,1,27,9,1370862793),(157,1,27,9,1370862836),(158,1,27,9,1370862858),(159,1,28,9,1370862902),(160,1,28,9,1370862937),(161,1,28,9,1370862955),(162,1,28,9,1370862980),(163,1,28,9,1370863000),(164,1,28,9,1370863024),(165,1,28,9,1370863072),(166,1,28,9,1370863128),(167,1,29,9,1370863180),(168,1,29,9,1370863246),(169,1,9,9,1370863403),(170,1,9,9,1370863470),(171,1,9,9,1370863500),(172,1,9,9,1370863546),(173,1,9,9,1370863571),(174,1,9,9,1370863606),(175,1,9,9,1370863630),(176,1,9,9,1370863659),(177,1,9,9,1370863712),(178,1,10,9,1370863784),(179,1,10,9,1370863843),(180,1,10,9,1370863876),(181,1,10,9,1370863893),(182,1,10,9,1370863915),(183,1,10,9,1370863949),(184,1,10,9,1370863985),(185,1,10,9,1370864028),(186,1,10,9,1370864040),(187,1,11,9,1370864069),(188,1,11,9,1370864098),(189,1,11,9,1370864122),(190,1,11,9,1370864141),(191,1,11,9,1370864164),(192,1,11,9,1370864183),(193,1,11,9,1370864212),(194,1,11,9,1370864252),(195,1,12,9,1370864272),(196,1,12,9,1370864310),(197,1,12,9,1370864336),(198,1,12,9,1370864356),(199,1,12,9,1370864371),(200,1,12,9,1370864396),(201,1,12,9,1370864410),(202,1,12,9,1370864432),(203,1,12,9,1370864459),(204,1,9,9,1371319749),(205,1,9,9,1371319809),(206,1,9,9,1371319846);

/*Table structure for table `dr_1_news_search` */

DROP TABLE IF EXISTS `dr_1_news_search`;

CREATE TABLE `dr_1_news_search` (
  `id` varchar(32) NOT NULL,
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `params` text NOT NULL COMMENT '参数数组',
  `keyword` varchar(255) NOT NULL COMMENT '关键字',
  `contentid` mediumtext NOT NULL COMMENT 'id集合',
  `inputtime` int(10) unsigned NOT NULL COMMENT '搜索时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `catid` (`catid`),
  KEY `keyword` (`keyword`),
  KEY `inputtime` (`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索表';

/*Data for the table `dr_1_news_search` */

/*Table structure for table `dr_1_news_tag` */

DROP TABLE IF EXISTS `dr_1_news_tag`;

CREATE TABLE `dr_1_news_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL COMMENT 'tag名称',
  `code` varchar(200) NOT NULL COMMENT 'tag代码（拼音）',
  `hits` mediumint(8) unsigned NOT NULL COMMENT '点击量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `letter` (`code`,`hits`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Tag标签表';

/*Data for the table `dr_1_news_tag` */

insert  into `dr_1_news_tag`(`id`,`name`,`code`,`hits`) values (1,'兼职','jianzhi',100),(2,'招聘','zhaopin',90),(3,'代购','daigou',80),(4,'代作业','daizuoye',60),(5,'送货','songhuo',50);

/*Table structure for table `dr_1_news_verify` */

DROP TABLE IF EXISTS `dr_1_news_verify`;

CREATE TABLE `dr_1_news_verify` (
  `id` int(10) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `content` mediumtext NOT NULL COMMENT '具体内容',
  `backuid` mediumint(8) unsigned NOT NULL COMMENT '操作人uid',
  `backinfo` text NOT NULL COMMENT '操作退回信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `backuid` (`backuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='审核表';

/*Data for the table `dr_1_news_verify` */

/*Table structure for table `dr_1_page` */

DROP TABLE IF EXISTS `dr_1_page`;

CREATE TABLE `dr_1_page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(20) NOT NULL COMMENT '模块dir',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) NOT NULL COMMENT '所有上级id',
  `name` varchar(255) NOT NULL COMMENT '单页名称',
  `dirname` varchar(30) NOT NULL COMMENT '栏目目录',
  `pdirname` varchar(100) NOT NULL COMMENT '上级目录',
  `child` tinyint(1) unsigned NOT NULL COMMENT '是否有子类',
  `childids` varchar(255) NOT NULL COMMENT '下级所有id',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `title` varchar(255) NOT NULL COMMENT 'seo标题',
  `keywords` varchar(255) NOT NULL COMMENT 'seo关键字',
  `description` varchar(255) NOT NULL COMMENT 'seo描述',
  `content` mediumtext NOT NULL COMMENT '单页内容',
  `attachment` text NOT NULL COMMENT '附件信息',
  `template` varchar(30) NOT NULL COMMENT '模板文件',
  `urlrule` smallint(5) unsigned DEFAULT NULL COMMENT 'url规则id',
  `urlpage` varchar(255) NOT NULL COMMENT '废弃',
  `urllink` varchar(255) NOT NULL COMMENT 'url外链',
  `getchild` tinyint(1) unsigned NOT NULL COMMENT '将下级第一个菜单作为当前菜单',
  `show` tinyint(1) unsigned NOT NULL COMMENT '是否显示在菜单',
  `url` varchar(255) NOT NULL COMMENT 'url地址',
  `seojoin` varchar(20) NOT NULL COMMENT '废弃',
  `displayorder` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`module`),
  KEY `pid` (`pid`),
  KEY `show` (`show`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='单页表';

/*Data for the table `dr_1_page` */

insert  into `dr_1_page`(`id`,`module`,`pid`,`pids`,`name`,`dirname`,`pdirname`,`child`,`childids`,`thumb`,`title`,`keywords`,`description`,`content`,`attachment`,`template`,`urlrule`,`urlpage`,`urllink`,`getchild`,`show`,`url`,`seojoin`,`displayorder`) values (1,'',0,'0','关于我们','guanyuwomen','',1,'1,2,3,4,5','','','','','','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=1','_',0),(2,'',1,'0,1','FineCMS v1.x','v1','guanyuwomen/',0,'2','','FineCMS v1.x 轻量级网站管理系统','FineCMS,网站建设,内容管理系统','FineCMSv1（简称v1）是一款基于PHP+MySql开发的内容管理系统，采用MVC设计模式实现业务逻辑与表现层的适当分离，使网页设计师能够轻松设计出理想的模板，插件化方式开发功能易用便于扩展，支持自定义内容模型和会员...','<p>FineCMS v1（简称v1）是一款基于PHP+MySql开发的内容管理系统，采用MVC设计模式实现业务逻辑与表现层的适当分离，使网页设计师能够轻松设计出理想的模板， 插件化方式开发功能易用便于扩展，支持自定义内容模型和会员模型，并且可以自定义字段，可面向中小型站点提供重量级网站建设解决方案。</p><p><strong>功能特点</strong><br /></p><p>1、自定义模型和字段<br />超强的自定义模型和字段功能则把系统灵活度发挥到了极致，不用编程就实现各种信息发布和检索</p><p>2、多国语言支持<br />系统自带语言有简体中文、繁体中文、英文，其他语言扩展相当方便</p><p>3、支持多站点站群管理<br />持多个站点管理及分站功能，多站只需绑定域名到根目录，使用相当方便</p><p>4、负载能力强<br />从缓存技术、数据库设计、代码优化等多个角度入手进行优化，支持百万级数据量</p><p>5、模板制作方便<br />采用MVC设计模式实现了程序与模板完全分离，灵活的模板标签能完全显示全站信息</p><p>6、支持文章内链<br />有助于提高搜索引擎对网站的爬行索引效率，支持Tag自动内链到文章</p><p>7、表单功能<br />用于拓展内容模型和会员模型，如报名、评论、询价、咨询等</p><p>8、推荐位功能<br />推荐位功能可以让编辑随时把信息推送至指定位置，操作简单实用</p><p>9、 文字块功能<br />把一些小段内容放在文字块中，支持HTML代码和图片上传</p><p>10、自定义URL规则<br />可以完全自定义URL地址规则，包括栏目、内容及自定义页</p><p>11、功能插件化<br />按照官方提供的插件为蓝本，用户可开发出属于自己的插件</p><p>12、SEO处理<br />个性化设置每个栏目的标题标签、描述标签、关键词标签，自动生成百度谷歌网站地图</p>','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=2','_',0),(3,'',1,'0,1','FineCMS v2.x','v2','guanyuwomen/',0,'3','','FineCMS v2.x 这是一套神奇的系统','FineCMS,网站建设,内容管理系统','FineCMSv2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，...','<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。<br /><br /></p><div name=\"dr_page_break\" class=\"pagebreak\">功能特点</div><p><br />每个站点均属独立的系统，模板与语言相互独立、权限互相独立、操作互不影响，共享会员中心，单点同步登录注册等，结合站点语言包能创建出多语言版本的网站，支持站点独立数据库存储，支持站点静态页面同步至指定服务器，可轻松实现强大的负载均衡<br /><br />各个模块相互独立，支持域名绑定，支持按栏目进行权限划分，无限分表存储让模块的负载能力更高，超强的自定义模块和字段功能则把系统灵活度发挥到了极致，可以不用编程就实现各种信息发布和检索，系统内置了文章、组图、下载等内容模块<br /><br />自定义URL一直是FineCMS系统的一个亮点，能够DIY出各种格式的URL，并支持函数与自定义运用到标签中，增强了自定义URL的灵活性。在v2中支持自动生成伪静态规则，无需用户动手写规则，v2能自动帮你把规则写好，真正的傻瓜式操作，一键生成规则<br /><br />内置腾讯QQ、新浪微博、百度、网易、360、google、搜狐等OAuth一键登录功能，发布文章、评论自动发微博分享，还集成手机短信接口API通过手机短信验证更安全，同时会员支持与Ucenter完美整合，会员空间支持域名绑定，用它可以创建标准的个人网站或者企业网站<br /></p><p><br />应用平台为站长提供一站式应用筛选、下载、安装、更新、卸载；满足不同阶段、不同规模的站长自选适合自己的应用。为第三方应用开发商提供可靠、方便、快捷、高效的发布平台；系统基于CodeIgniter框架，开发教程文档齐全，让第三方应用快速、安全的服务到千百万站长，同时也能获得高额的回报 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br /><br />简单易用的模板引擎，支持原生PHP语法，万能的系统标签，灵活的模板制作，系统内置模板标签调用向导，能生成完善的调用代码，让美工人员可独立完成模板制作及数据调用，可让程序人员和美工人员分工协作，最大可能提高团队执行力<br /><br />内置财务管理系统，支持用虚拟币进行站内交易与兑换功能，支持主流第三方交易平台如支付宝、财付通等，帮助用户最简捷、快速、安全的实现电子商务平台</p>','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=3','_',0),(4,'',1,'0,1','联系我们','lianxiwomen','guanyuwomen/',0,'4','','','','联系QQ：135977378用户交流QQ群：253068474开发者联盟QQ群：8615168','<p><img src=\"http://api.map.baidu.com/staticimage?center=116.355779,39.872817&amp;zoom=16&amp;width=530&amp;height=340&amp;markers=116.357863,39.872125\" height=\"340\" width=\"530\" /></p><p>联系QQ：<a href=\"http://wpa.qq.com/msgrd?v=3&amp;uin=135977378&amp;site=qq&amp;menu=yes\" target=\"_blank\">135977378</a></p><p>用户交流QQ群：253068474</p><p>开发者联盟QQ群：8615168<br /></p>','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=4','_',0),(5,'',1,'0,1','官方网站','guanfangwangzhan','guanyuwomen/',0,'5','','','','','','','page.html',0,'','http://www.dayrui.com',1,1,'http://www.dayrui.com','_',0),(6,'',0,'0','adfasdf','adfasdf','',1,'6,7','','','','','','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=6','_',0),(7,'',6,'0,6','asdfasdf','asdfasdf','adfasdf/',0,'7','','','','','','','page.html',0,'','',1,1,'http://www.baixing.cc/index.php?c=page&id=7','_',0);

/*Table structure for table `dr_admin` */

DROP TABLE IF EXISTS `dr_admin`;

CREATE TABLE `dr_admin` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) DEFAULT NULL COMMENT '管理员姓名',
  `usermenu` text COMMENT '自定义面板菜单，序列化数组格式',
  `lastloginip` varchar(15) DEFAULT NULL COMMENT '上次登录IP',
  `lastlogintime` int(10) unsigned DEFAULT NULL COMMENT '上次登录时间戳',
  `loginip` varchar(15) DEFAULT NULL COMMENT '本次登录IP',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '本次登录时间戳',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

/*Data for the table `dr_admin` */

insert  into `dr_admin`(`uid`,`realname`,`usermenu`,`lastloginip`,`lastlogintime`,`loginip`,`logintime`) values (1,'admin','网站创始人','',0,'127.0.0.1',1401255842);

/*Table structure for table `dr_admin_menu` */

DROP TABLE IF EXISTS `dr_admin_menu`;

CREATE TABLE `dr_admin_menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL COMMENT '上级菜单id',
  `name` text NOT NULL COMMENT '菜单语言名称',
  `uri` varchar(255) DEFAULT NULL COMMENT 'uri字符串',
  `url` varchar(255) DEFAULT NULL COMMENT '外链地址',
  `mark` varchar(20) DEFAULT NULL COMMENT '菜单标识',
  `displayorder` tinyint(3) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `list` (`pid`),
  KEY `displayorder` (`displayorder`),
  KEY `mark` (`mark`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

/*Data for the table `dr_admin_menu` */

insert  into `dr_admin_menu`(`id`,`pid`,`name`,`uri`,`url`,`mark`,`displayorder`) values (1,0,'首页','/',NULL,'',0),(2,0,'系统','/',NULL,'',0),(3,0,'网站','/',NULL,'',0),(4,0,'会员','/',NULL,'',0),(5,0,'应用','/',NULL,'',0),(6,1,'我的面板','/',NULL,'',0),(7,6,'后台首页','admin/home/main','','',0),(8,2,'系统功能','/',NULL,'',0),(9,2,'系统维护','/',NULL,'',0),(10,2,'角色权限','/',NULL,'',0),(11,8,'后台菜单','admin/menu/index','','',4),(12,9,'数据备份','admin/db/index','','',0),(13,10,'角色管理','admin/role/index','','',0),(14,10,'管理员管理','admin/root/index','','',0),(15,3,'网站功能','/',NULL,'',0),(40,24,'单页管理','admin/page/index','','',0),(18,15,'网站配置','admin/site/config','','',0),(19,8,'网站管理','admin/site/index','','',8),(20,15,'网站导航','admin/navigator/index','','',0),(21,8,'模块管理','admin/module/index','','',9),(22,15,'附件管理','admin/attachment/index','','',0),(24,3,'内容相关','/',NULL,'',0),(25,4,'会员管理','/',NULL,'',1),(26,25,'会员列表','member/admin/home/index','','',0),(27,25,'会 员 组','member/admin/group/index','','',0),(29,4,'功能配置','/',NULL,'',3),(30,29,'基本参数','member/admin/setting/index','','',0),(31,29,'注册设置','member/admin/setting/index/page/1','','',1),(32,4,'模板风格','/',NULL,'',4),(33,29,'自定义字段','admin/field/index/rname/member/rid/0','','',9),(36,29,'Ucenter','member/admin/setting/index/page/2','','',2),(37,29,'OAuth','member/admin/setting/oauth','','',4),(38,10,'审核流程','admin/verify/index','','',0),(39,25,'会员权限','member/admin/setting/permission','','',0),(59,25,'财务管理','member/admin/pay/card','','',0),(60,24,'联动菜单','admin/linkage/index','','',0),(61,8,'邮件系统','admin/mail/index','','',2),(62,9,'系统升级','admin/upgrade/index','','',1),(63,8,'系统配置','admin/system/index','','',1),(64,9,'数据恢复','admin/db/recovery','','',0),(65,9,'系统体检','admin/check/index','','',0),(66,8,'操作日志','admin/system/oplog','','',4),(67,3,'模板风格','/',NULL,'',0),(68,67,'模板管理','admin/tpl/index','','',0),(69,67,'风格管理','admin/theme/index','','',0),(70,67,'标签向导','admin/tpl/tag','','',0),(71,32,'会员模板','member/admin/tpl/index','','',0),(72,32,'会员风格','member/admin/theme/index','','',0),(73,80,'空间模板','member/admin/spacetpl/index','','',99),(74,80,'空间管理','member/admin/space/index','','',1),(75,5,'应用中心','/',NULL,'',0),(76,75,'应用管理','admin/application/index','','',0),(77,24,'文本块管理','admin/block/index','','',99),(78,29,'会员菜单','member/admin/menu/index','','',5),(79,6,'资料修改','admin/root/my','','',0),(80,4,'会员空间','','','',2),(81,80,'空间模型','member/admin/model/index','','',2),(82,24,'表单管理','admin/form/index','','',0),(83,80,'文章管理','member/admin/content/index/mid/1','','space-1',6),(84,80,'外链管理','member/admin/content/index/mid/2','','space-2',7),(85,80,'日志管理','member/admin/content/index/mid/3','','space-3',8),(86,80,'相册管理','member/admin/content/index/mid/4','','space-4',9),(87,80,'幻灯管理','member/admin/content/index/mid/5','','space-5',10),(88,80,'空间设置','member/admin/setting/space','','',199),(89,8,'任务队列','admin/cron/index','','',3),(90,8,'短信系统','admin/sms/index','','',2),(91,14,'URL规则','admin/urlrule/index','','',2),(92,0,'新闻','',NULL,'module-news',0),(93,92,'文档管理','',NULL,'module-news',0),(94,93,'已通过文档','news/admin/home/index',NULL,'module-news',0),(95,93,'待审核文档','news/admin/home/verify',NULL,'module-news',0),(96,93,'栏目分类','news/admin/category/index',NULL,'module-news',0),(97,93,'Tag标签','news/admin/tag/index',NULL,'module-news',0),(98,93,'单页管理','news/admin/page/index',NULL,'module-news',0),(99,92,'相关功能','',NULL,'module-news',0),(100,99,'更新地址','news/admin/home/url',NULL,'module-news',0),(101,99,'生成静态','news/admin/home/html',NULL,'module-news',0),(102,99,'自定义字段','admin/field/index/rname/module/rid/1',NULL,'module-news',0),(103,92,'模板风格','',NULL,'module-news',0),(104,103,'模板管理','news/admin/tpl/index',NULL,'module-news',0),(105,103,'风格管理','news/admin/theme/index',NULL,'module-news',0),(106,103,'标签向导','news/admin/tpl/tag',NULL,'module-news',0);

/*Table structure for table `dr_admin_role` */

DROP TABLE IF EXISTS `dr_admin_role`;

CREATE TABLE `dr_admin_role` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `site` varchar(255) NOT NULL COMMENT '允许管理的站点，序列化数组格式',
  `name` text NOT NULL COMMENT '角色组语言名称',
  `system` text NOT NULL COMMENT '系统权限',
  `module` text NOT NULL COMMENT '模块权限',
  `application` text NOT NULL COMMENT '应用权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台角色权限表';

/*Data for the table `dr_admin_role` */

insert  into `dr_admin_role`(`id`,`site`,`name`,`system`,`module`,`application`) values (1,'','超级管理员','','',''),(2,'','网站编辑员','','','');

/*Table structure for table `dr_admin_verify` */

DROP TABLE IF EXISTS `dr_admin_verify`;

CREATE TABLE `dr_admin_verify` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT '名称',
  `verify` text NOT NULL COMMENT '审核部署',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='审核管理表';

/*Data for the table `dr_admin_verify` */

insert  into `dr_admin_verify`(`id`,`name`,`verify`) values (1,'审核一次','a:1:{i:1;a:2:{i:0;s:1:\\\"2\\\";i:1;s:1:\\\"3\\\";}}');

/*Table structure for table `dr_application` */

DROP TABLE IF EXISTS `dr_application`;

CREATE TABLE `dr_application` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` text COMMENT '模块划分',
  `dirname` varchar(50) NOT NULL COMMENT '目录名称',
  `setting` text COMMENT '配置信息',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dirname` (`dirname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';

/*Data for the table `dr_application` */

/*Table structure for table `dr_attachment` */

DROP TABLE IF EXISTS `dr_attachment`;

CREATE TABLE `dr_attachment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `siteid` tinyint(3) unsigned NOT NULL COMMENT '站点id',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `tableid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件副表id',
  `download` mediumint(8) NOT NULL DEFAULT '0' COMMENT '下载次数',
  `filesize` int(10) unsigned NOT NULL COMMENT '文件大小',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `author` (`author`),
  KEY `relatedtid` (`related`),
  KEY `fileext` (`fileext`),
  KEY `siteid` (`siteid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='附件表';

/*Data for the table `dr_attachment` */

insert  into `dr_attachment`(`id`,`uid`,`author`,`siteid`,`related`,`tableid`,`download`,`filesize`,`fileext`) values (1,1,'admin',1,'',0,0,30761,'jpg'),(2,1,'admin',1,'',0,0,265155,'jpg'),(3,1,'admin',1,'',0,0,265155,'jpg');

/*Table structure for table `dr_attachment_0` */

DROP TABLE IF EXISTS `dr_attachment_0`;

CREATE TABLE `dr_attachment_0` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表0';

/*Data for the table `dr_attachment_0` */

/*Table structure for table `dr_attachment_1` */

DROP TABLE IF EXISTS `dr_attachment_1`;

CREATE TABLE `dr_attachment_1` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表1';

/*Data for the table `dr_attachment_1` */

/*Table structure for table `dr_attachment_2` */

DROP TABLE IF EXISTS `dr_attachment_2`;

CREATE TABLE `dr_attachment_2` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表2';

/*Data for the table `dr_attachment_2` */

/*Table structure for table `dr_attachment_3` */

DROP TABLE IF EXISTS `dr_attachment_3`;

CREATE TABLE `dr_attachment_3` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表3';

/*Data for the table `dr_attachment_3` */

/*Table structure for table `dr_attachment_4` */

DROP TABLE IF EXISTS `dr_attachment_4`;

CREATE TABLE `dr_attachment_4` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表4';

/*Data for the table `dr_attachment_4` */

/*Table structure for table `dr_attachment_5` */

DROP TABLE IF EXISTS `dr_attachment_5`;

CREATE TABLE `dr_attachment_5` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表5';

/*Data for the table `dr_attachment_5` */

/*Table structure for table `dr_attachment_6` */

DROP TABLE IF EXISTS `dr_attachment_6`;

CREATE TABLE `dr_attachment_6` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表6';

/*Data for the table `dr_attachment_6` */

/*Table structure for table `dr_attachment_7` */

DROP TABLE IF EXISTS `dr_attachment_7`;

CREATE TABLE `dr_attachment_7` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表7';

/*Data for the table `dr_attachment_7` */

/*Table structure for table `dr_attachment_8` */

DROP TABLE IF EXISTS `dr_attachment_8`;

CREATE TABLE `dr_attachment_8` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表8';

/*Data for the table `dr_attachment_8` */

/*Table structure for table `dr_attachment_9` */

DROP TABLE IF EXISTS `dr_attachment_9`;

CREATE TABLE `dr_attachment_9` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `related` varchar(50) NOT NULL COMMENT '相关表标识',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表9';

/*Data for the table `dr_attachment_9` */

/*Table structure for table `dr_attachment_unused` */

DROP TABLE IF EXISTS `dr_attachment_unused`;

CREATE TABLE `dr_attachment_unused` (
  `id` mediumint(8) unsigned NOT NULL COMMENT '附件id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `author` varchar(50) NOT NULL COMMENT '会员',
  `siteid` tinyint(3) unsigned NOT NULL COMMENT '站点id',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '原文件名',
  `fileext` varchar(20) NOT NULL COMMENT '文件扩展名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '服务器路径',
  `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachinfo` text NOT NULL COMMENT '附件信息',
  `inputtime` int(10) unsigned NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `author` (`author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='未使用的附件表';

/*Data for the table `dr_attachment_unused` */

insert  into `dr_attachment_unused`(`id`,`uid`,`author`,`siteid`,`filename`,`fileext`,`filesize`,`attachment`,`remote`,`attachinfo`,`inputtime`) values (1,1,'admin',1,'dengni37','jpg',30761,'member/uploadfile/201406/7998a48ac6.jpg',0,'',1401626673),(2,1,'admin',1,'pic001','jpg',265155,'member/uploadfile/201406/f375dae9a2.jpg',0,'',1401677829),(3,1,'admin',1,'pic001','jpg',265155,'member/uploadfile/201406/a4fac826ad.jpg',0,'',1401678407);

/*Table structure for table `dr_cron_queue` */

DROP TABLE IF EXISTS `dr_cron_queue`;

CREATE TABLE `dr_cron_queue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) unsigned NOT NULL COMMENT '类型',
  `value` text NOT NULL COMMENT '值',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `error` varchar(255) NOT NULL COMMENT '错误信息',
  `updatetime` int(10) unsigned NOT NULL COMMENT '执行时间',
  `inputtime` int(10) unsigned NOT NULL COMMENT '写入时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='任务队列表';

/*Data for the table `dr_cron_queue` */

/*Table structure for table `dr_district` */

DROP TABLE IF EXISTS `dr_district`;

CREATE TABLE `dr_district` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `parentid` int(11) DEFAULT NULL,
  `path` varchar(255) NOT NULL COMMENT '路径',
  `initial` varchar(3) DEFAULT NULL,
  `initials` varchar(30) DEFAULT NULL,
  `pinyin` varchar(90) DEFAULT NULL,
  `extra` varchar(45) DEFAULT NULL,
  `suffix` varchar(30) DEFAULT NULL,
  `code` varchar(21) DEFAULT NULL,
  `area_code` varchar(12) DEFAULT NULL,
  `order` tinyint(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*Data for the table `dr_district` */

insert  into `dr_district`(`id`,`name`,`parentid`,`path`,`initial`,`initials`,`pinyin`,`extra`,`suffix`,`code`,`area_code`,`order`) values (1,'北京',0,'0,1','b','bj','beijing','','市','110000','010',1),(2,'天津',0,'0,2','t','tj','tianjin','','市','120000','022',2),(3,'上海',0,'0,3','s','sh','shanghai','','市','310000','021',3),(4,'重庆',0,'0,4','z','zq','zhongqing','','市','500000','023',4),(5,'河北',0,'0,5','h','hb','hebei','','省','130000','',5),(6,'山西',0,'0,6','s','sx','shanxi','','省','140000','',6),(7,'内蒙古',0,'0,7','n','nmg','neimenggu','','省','150000','',7),(8,'辽宁',0,'0,8','l','ln','liaoning','','自治区','210000','',8),(9,'吉林',0,'0,9','j','jl','jilin','','省','220000','',9),(10,'黑龙江',0,'0,10','h','hlj','heilongjiang','','省','230000','',10),(11,'江苏',0,'0,11','j','js','jiangsu','','省','320000','',11),(12,'浙江',0,'0,12','z','zj','zhejiang','','省','330000','',12),(13,'安徽',0,'0,13','a','ah','anhui','','省','340000','',13),(14,'福建',0,'0,14','f','fj','fujian','','省','350000','',14),(15,'江西',0,'0,15','j','jx','jiangxi','','省','360000','',15),(16,'山东',0,'0,16','s','sd','shandong','','省','370000','',16),(17,'河南',0,'0,17','h','hn','henan','','省','410000','',17),(18,'湖北',0,'0,18','h','hb','hubei','','省','420000','',18),(19,'湖南',0,'0,19','h','hn','hunan','','省','430000','',19),(20,'广东',0,'0,20','g','gd','guangdong','','省','440000','',20),(21,'广西',0,'0,21','g','gx','guangxi','壮族','自治区','450000','',21),(22,'海南',0,'0,22','h','hn','hainan','','省','460000','',22),(23,'四川',0,'0,23','s','sc','sichuan','','省','510000','',23),(24,'贵州',0,'0,24','g','gz','guizhou','','省','520000','',24),(25,'云南',0,'0,25','y','yn','yunnan','','省','530000','',25),(26,'西藏',0,'0,26','x','xz','xizang','','自治区','540000','',26),(27,'陕西',0,'0,27','s','sx','shanxi','','省','610000','',27),(28,'甘肃',0,'0,28','g','gs','gansu','','省','620000','',28),(29,'青海',0,'0,29','q','qh','qinghai','','省','630000','',29),(30,'宁夏',0,'0,30','n','nx','ningxia','回族','自治区','640000','',30),(31,'新疆',0,'0,31','x','xj','xinjiang','维吾尔','自治区','650000','',31),(32,'台湾',0,'0,32','t','tw','taiwan','','省','710000','',32),(33,'香港',0,'0,33','x','xg','xianggang','','特别行政区','810000','852',33),(34,'澳门',0,'0,34','a','am','aomen','','特别行政区','820000','853',34),(36,'东城',1,'0,1,36','d','dc','dongcheng','','区','110101','010',1),(37,'西城',1,'0,1,37','x','xc','xicheng','','区','110102','010',2),(38,'崇文',1,'0,1,38','c','cw','chongwen','','区','110103','010',3),(39,'宣武',1,'0,1,39','x','xw','xuanwu','','区','110104','010',4),(40,'朝阳',1,'0,1,40','c','cy','chaoyang','','区','110105','010',5),(41,'丰台',1,'0,1,41','f','ft','fengtai','','区','110106','010',6),(42,'石景山',1,'0,1,42','s','sjs','shijingshan','','区','110107','010',7),(43,'海淀',1,'0,1,43','h','hd','haidian','','区','110108','010',8),(44,'门头沟',1,'0,1,44','m','mtg','mentougou','','区','110109','010',9),(45,'房山',1,'0,1,45','f','fs','fangshan','','区','110111','010',10),(46,'通州',1,'0,1,46','t','tz','tongzhou','','区','110112','010',11),(47,'顺义',1,'0,1,47','s','sy','shunyi','','区','110113','010',12),(48,'昌平',1,'0,1,48','c','cp','changping','','区','110114','010',13),(49,'大兴',1,'0,1,49','d','dx','daxing','','区','110115','010',14),(50,'怀柔',1,'0,1,50','h','hr','huairou','','区','110116','010',15),(51,'平谷',1,'0,1,51','p','pg','pinggu','','区','110117','010',16),(52,'密云',1,'0,1,52','m','my','miyun','','县','110228','010',17),(53,'延庆',1,'0,1,53','y','yq','yanqing','','县','110229','010',18),(54,'和平',2,'0,2,54','h','hp','heping','','区','120101','022',1),(55,'河东',2,'0,2,55','h','hd','hedong','','区','120102','022',2),(56,'河西',2,'0,2,56','h','hx','hexi','','区','120103','022',3),(57,'南开',2,'0,2,57','n','nk','nankai','','区','120104','022',4),(58,'河北',2,'0,2,58','h','hb','hebei','','区','120105','022',5),(59,'红桥',2,'0,2,59','h','hq','hongqiao','','区','120106','022',6),(60,'东丽',2,'0,2,60','d','dl','dongli','','区','120110','022',7),(61,'西青',2,'0,2,61','x','xq','xiqing','','区','120111','022',8),(62,'津南',2,'0,2,62','j','jn','jinnan','','区','120112','022',9),(63,'北辰',2,'0,2,63','b','bc','beichen','','区','120113','022',10),(64,'武清',2,'0,2,64','w','wq','wuqing','','区','120114','022',11),(65,'宝坻',2,'0,2,65','b','bc','baochi','','区','120115','022',12),(66,'滨海新区',2,'0,2,66','b','bhxq','binhaixinqu','','','120116','022',13),(67,'宁河',2,'0,2,67','n','nh','ninghe','','县','120221','022',14),(68,'蓟县',2,'0,2,68','j','jx','jixian','','','120223','022',15),(69,'静海',2,'0,2,69','j','jh','jinghai','','县','120225','022',16),(70,'黄浦',3,'0,3,70','h','hp','huangpu','','区','310101','021',1),(71,'徐汇',3,'0,3,71','x','xh','xuhui','','区','310104','021',2),(72,'长宁',3,'0,3,72','z','zn','zhangning','','区','310105','021',3),(73,'静安',3,'0,3,73','j','ja','jingan','','区','310106','021',4),(74,'普陀',3,'0,3,74','p','pt','putuo','','区','310107','021',5),(75,'闸北',3,'0,3,75','z','zb','zhabei','','区','310108','021',6),(76,'虹口',3,'0,3,76','h','hk','hongkou','','区','310109','021',7),(77,'杨浦',3,'0,3,77','y','yp','yangpu','','区','310110','021',8),(78,'闵行',3,'0,3,78','m','mx','minxing','','区','310112','021',9),(79,'宝山',3,'0,3,79','b','bs','baoshan','','区','310113','021',10),(80,'嘉定',3,'0,3,80','j','jd','jiading','','区','310114','021',11),(81,'浦东新区',3,'0,3,81','p','pdxq','pudongxinqu','','','310115','021',12),(82,'金山',3,'0,3,82','j','js','jinshan','','区','310116','021',13),(83,'松江',3,'0,3,83','s','sj','songjiang','','区','310117','021',14),(84,'青浦',3,'0,3,84','q','qp','qingpu','','区','310118','021',15),(85,'奉贤',3,'0,3,85','f','fx','fengxian','','区','310120','021',16),(86,'崇明',3,'0,3,86','c','cm','chongming','','县','310230','021',17),(87,'万州',4,'0,4,87','w','wz','wanzhou','','区','500101','023',1),(88,'涪陵',4,'0,4,88','f','fl','fuling','','区','500102','023',2),(89,'渝中',4,'0,4,89','y','yz','yuzhong','','区','500103','023',3),(90,'大渡口',4,'0,4,90','d','ddk','dadukou','','区','500104','023',4),(91,'江北',4,'0,4,91','j','jb','jiangbei','','区','500105','023',5),(92,'沙坪坝',4,'0,4,92','s','spb','shapingba','','区','500106','023',6),(93,'九龙坡',4,'0,4,93','j','jlp','jiulongpo','','区','500107','023',7),(94,'南岸',4,'0,4,94','n','na','nanan','','区','500108','023',8),(95,'北碚',4,'0,4,95','b','bb','beibei','','区','500109','023',9),(96,'綦江',4,'0,4,96','q','qj','qijiang','','区','500110','023',10),(97,'大足',4,'0,4,97','d','dz','dazu','','区','500111','023',11),(98,'渝北',4,'0,4,98','y','yb','yubei','','区','500112','023',12),(99,'巴南',4,'0,4,99','b','bn','banan','','区','500113','023',13),(100,'黔江',4,'0,4,100','q','qj','qianjiang','','区','500114','023',14),(101,'长寿',4,'0,4,101','z','zs','zhangshou','','区','500115','023',15),(102,'江津',4,'0,4,102','j','jj','jiangjin','','区','500116','023',16),(103,'合川',4,'0,4,103','h','hc','hechuan','','区','500117','023',17),(104,'永川',4,'0,4,104','y','yc','yongchuan','','区','500118','023',18),(105,'南川',4,'0,4,105','n','nc','nanchuan','','区','500119','023',19),(106,'潼南',4,'0,4,106','t','tn','tongnan','','县','500223','023',20),(107,'铜梁',4,'0,4,107','t','tl','tongliang','','县','500224','023',21),(108,'荣昌',4,'0,4,108','r','rc','rongchang','','县','500226','023',22),(109,'璧山',4,'0,4,109','b','bs','bishan','','县','500227','023',23),(116,'开县',4,'0,4,116','k','kx','kaixian','','','500234','023',30),(125,'石家庄',5,'0,5,125','s','sjz','shijiazhuang','','市','130100','0311',1),(126,'唐山',5,'0,5,126','t','ts','tangshan','','市','130200','0315',2),(127,'秦皇岛',5,'0,5,127','q','qhd','qinhuangdao','','市','130300','0335',3),(128,'邯郸',5,'0,5,128','h','hd','handan','','市','130400','0310',4),(129,'邢台',5,'0,5,129','x','xt','xingtai','','市','130500','0319',5),(130,'保定',5,'0,5,130','b','bd','baoding','','市','130600','0312',6),(131,'张家口',5,'0,5,131','z','zjk','zhangjiakou','','市','130700','0313',7),(132,'承德',5,'0,5,132','c','cd','chengde','','市','130800','0314',8),(133,'沧州',5,'0,5,133','c','cz','cangzhou','','市','130900','0317',9),(134,'廊坊',5,'0,5,134','l','lf','langfang','','市','131000','0316',10),(135,'衡水',5,'0,5,135','h','hs','hengshui','','市','131100','0318',11),(136,'太原',6,'0,6,136','t','ty','taiyuan','','市','140100','0351',1),(137,'大同',6,'0,6,137','d','dt','datong','','市','140200','0352',2),(138,'阳泉',6,'0,6,138','y','yq','yangquan','','市','140300','0353',3),(139,'长治',6,'0,6,139','z','zz','zhangzhi','','市','140400','0355',4),(140,'晋城',6,'0,6,140','j','jc','jincheng','','市','140500','0356',5),(141,'朔州',6,'0,6,141','s','sz','shuozhou','','市','140600','0349',6),(142,'晋中',6,'0,6,142','j','jz','jinzhong','','市','140700','0354',7),(143,'运城',6,'0,6,143','y','yc','yuncheng','','市','140800','0359',8),(144,'忻州',6,'0,6,144','x','xz','xinzhou','','市','140900','0350',9),(145,'临汾',6,'0,6,145','l','lf','linfen','','市','141000','0357',10),(146,'吕梁',6,'0,6,146','l','ll','lu:liang','','市','141100','0358',11),(147,'呼和浩特',7,'0,7,147','h','hhht','huhehaote','','市','150100','0471',1),(148,'包头',7,'0,7,148','b','bt','baotou','','市','150200','0472',2),(149,'乌海',7,'0,7,149','w','wh','wuhai','','市','150300','0473',3),(150,'赤峰',7,'0,7,150','c','cf','chifeng','','市','150400','0476',4),(151,'通辽',7,'0,7,151','t','tl','tongliao','','市','150500','0475',5),(152,'鄂尔多斯',7,'0,7,152','e','eeds','eerduosi','','市','150600','0477',6),(153,'呼伦贝尔',7,'0,7,153','h','hlbe','hulunbeier','','市','150700','0470',7),(154,'巴彦淖尔',7,'0,7,154','b','byne','bayannaoer','','市','150800','0478',8),(155,'乌兰察布',7,'0,7,155','w','wlcb','wulanchabu','','市','150900','0474',9),(156,'兴安',7,'0,7,156','x','xa','xingan','','盟','152200','0482',10),(157,'锡林郭勒',7,'0,7,157','x','xlgl','xilinguole','','盟','152500','0479',11),(158,'阿拉善',7,'0,7,158','a','als','alashan','','盟','152900','0483',12),(159,'沈阳',8,'0,8,159','s','sy','shenyang','','市','210100','024',1),(160,'大连',8,'0,8,160','d','dl','dalian','','市','210200','0411',2),(161,'鞍山',8,'0,8,161','a','as','anshan','','市','210300','0412',3),(162,'抚顺',8,'0,8,162','f','fs','fushun','','市','210400','0413',4),(163,'本溪',8,'0,8,163','b','bx','benxi','','市','210500','0414',5),(164,'丹东',8,'0,8,164','d','dd','dandong','','市','210600','0415',6),(165,'锦州',8,'0,8,165','j','jz','jinzhou','','市','210700','0416',7),(166,'营口',8,'0,8,166','y','yk','yingkou','','市','210800','0417',8),(167,'阜新',8,'0,8,167','f','fx','fuxin','','市','210900','0418',9),(168,'辽阳',8,'0,8,168','l','ly','liaoyang','','市','211000','0419',10),(169,'盘锦',8,'0,8,169','p','pj','panjin','','市','211100','0427',11),(170,'铁岭',8,'0,8,170','t','tl','tieling','','市','211200','0410',12),(171,'朝阳',8,'0,8,171','c','cy','chaoyang','','市','211300','0421',13),(172,'葫芦岛',8,'0,8,172','h','hld','huludao','','市','211400','0429',14),(173,'长春',9,'0,9,173','z','zc','zhangchun','','市','220100','0431',1),(174,'吉林',9,'0,9,174','j','jl','jilin','','市','220200','0432',2),(175,'四平',9,'0,9,175','s','sp','siping','','市','220300','0434',3),(176,'辽源',9,'0,9,176','l','ly','liaoyuan','','市','220400','0437',4),(177,'通化',9,'0,9,177','t','th','tonghua','','市','220500','0435',5),(178,'白山',9,'0,9,178','b','bs','baishan','','市','220600','0439',6),(179,'松原',9,'0,9,179','s','sy','songyuan','','市','220700','0438',7),(180,'白城',9,'0,9,180','b','bc','baicheng','','市','220800','0436',8),(181,'延边',9,'0,9,181','y','yb','yanbian','朝鲜族','自治州','222400','0433',9),(182,'哈尔滨',10,'0,10,182','h','heb','haerbin','','市','230100','0451',1),(183,'齐齐哈尔',10,'0,10,183','q','qqhe','qiqihaer','','市','230200','0452',2),(184,'鸡西',10,'0,10,184','j','jx','jixi','','市','230300','0467',3),(185,'鹤岗',10,'0,10,185','h','hg','hegang','','市','230400','0468',4),(186,'双鸭山',10,'0,10,186','s','sys','shuangyashan','','市','230500','0469',5),(187,'大庆',10,'0,10,187','d','dq','daqing','','市','230600','0459',6),(188,'伊春',10,'0,10,188','y','yc','yichun','','市','230700','0458',7),(189,'佳木斯',10,'0,10,189','j','jms','jiamusi','','市','230800','0454',8),(190,'七台河',10,'0,10,190','q','qth','qitaihe','','市','230900','0464',9),(191,'牡丹江',10,'0,10,191','m','mdj','mudanjiang','','市','231000','0453',10),(192,'黑河',10,'0,10,192','h','hh','heihe','','市','231100','0456',11),(193,'绥化',10,'0,10,193','s','sh','suihua','','市','231200','0455',12),(194,'大兴安岭',10,'0,10,194','d','dxal','daxinganling','','地区','232700','0457',13),(195,'南京',11,'0,11,195','n','nj','nanjing','','市','320100','',1),(196,'无锡',11,'0,11,196','w','wx','wuxi','','市','320200','0510',2),(197,'徐州',11,'0,11,197','x','xz','xuzhou','','市','320300','0516',3),(198,'常州',11,'0,11,198','c','cz','changzhou','','市','320400','0519',4),(199,'苏州',11,'0,11,199','s','sz','suzhou','','市','320500','0512',5),(200,'南通',11,'0,11,200','n','nt','nantong','','市','320600','0513',6),(201,'连云港',11,'0,11,201','l','lyg','lianyungang','','市','320700','0518',7),(202,'淮安',11,'0,11,202','h','ha','huaian','','市','320800','0517',8),(203,'盐城',11,'0,11,203','y','yc','yancheng','','市','320900','0515',9),(204,'扬州',11,'0,11,204','y','yz','yangzhou','','市','321000','0514',10),(205,'镇江',11,'0,11,205','z','zj','zhenjiang','','市','321100','0511',11),(206,'泰州',11,'0,11,206','t','tz','taizhou','','市','321200','0523',12),(207,'宿迁',11,'0,11,207','s','sq','suqian','','市','321300','0527',13),(208,'杭州',12,'0,12,208','h','hz','hangzhou','','市','330100','0571',1),(209,'宁波',12,'0,12,209','n','nb','ningbo','','市','330200','0574',2),(210,'温州',12,'0,12,210','w','wz','wenzhou','','市','330300','0577',3),(211,'嘉兴',12,'0,12,211','j','jx','jiaxing','','市','330400','0573',4),(212,'湖州',12,'0,12,212','h','hz','huzhou','','市','330500','0572',5),(213,'绍兴',12,'0,12,213','s','sx','shaoxing','','市','330600','0575',6),(214,'金华',12,'0,12,214','j','jh','jinhua','','市','330700','0579',7),(215,'衢州',12,'0,12,215','q','qz','quzhou','','市','330800','0570',8),(216,'舟山',12,'0,12,216','z','zs','zhoushan','','市','330900','0580',9),(217,'台州',12,'0,12,217','t','tz','taizhou','','市','331000','0576',10),(218,'丽水',12,'0,12,218','l','ls','lishui','','市','331100','0578',11),(219,'合肥',13,'0,13,219','h','hf','hefei','','市','340100','0551',1),(220,'芜湖',13,'0,13,220','w','wh','wuhu','','市','340200','0553',2),(221,'蚌埠',13,'0,13,221','b','bb','bangbu','','市','340300','0552',3),(222,'淮南',13,'0,13,222','h','hn','huainan','','市','340400','0554',4),(223,'马鞍山',13,'0,13,223','m','mas','maanshan','','市','340500','0555',5),(224,'淮北',13,'0,13,224','h','hb','huaibei','','市','340600','0561',6),(225,'铜陵',13,'0,13,225','t','tl','tongling','','市','340700','0562',7),(226,'安庆',13,'0,13,226','a','aq','anqing','','市','340800','0556',8),(227,'黄山',13,'0,13,227','h','hs','huangshan','','市','341000','0559',9),(228,'滁州',13,'0,13,228','c','cz','chuzhou','','市','341100','0550',10),(229,'阜阳',13,'0,13,229','f','fy','fuyang','','市','341200','0558',11),(230,'宿州',13,'0,13,230','s','sz','suzhou','','市','341300','0557',12),(231,'六安',13,'0,13,231','l','la','liuan','','市','341500','0564',13),(232,'亳州',13,'0,13,232','b','bz','bozhou','','市','341600','0558',14),(233,'池州',13,'0,13,233','c','cz','chizhou','','市','341700','0566',15),(234,'宣城',13,'0,13,234','x','xc','xuancheng','','市','341800','0563',16),(235,'福州',14,'0,14,235','f','fz','fuzhou','','市','350100','0591',1),(236,'厦门',14,'0,14,236','s','sm','shamen','','市','350200','0592',2),(237,'莆田',14,'0,14,237','p','pt','putian','','市','350300','0594',3),(238,'三明',14,'0,14,238','s','sm','sanming','','市','350400','0598',4),(239,'泉州',14,'0,14,239','q','qz','quanzhou','','市','350500','0595',5),(240,'漳州',14,'0,14,240','z','zz','zhangzhou','','市','350600','0596',6),(241,'南平',14,'0,14,241','n','np','nanping','','市','350700','0599',7),(242,'龙岩',14,'0,14,242','l','ly','longyan','','市','350800','0597',8),(243,'宁德',14,'0,14,243','n','nd','ningde','','市','350900','0593',9),(244,'南昌',15,'0,15,244','n','nc','nanchang','','市','360100','0791',1),(245,'景德镇',15,'0,15,245','j','jdz','jingdezhen','','市','360200','0798',2),(246,'萍乡',15,'0,15,246','p','px','pingxiang','','市','360300','0799',3),(247,'九江',15,'0,15,247','j','jj','jiujiang','','市','360400','0792',4),(248,'新余',15,'0,15,248','x','xy','xinyu','','市','360500','0790',5),(249,'鹰潭',15,'0,15,249','y','yt','yingtan','','市','360600','0701',6),(250,'赣州',15,'0,15,250','g','gz','ganzhou','','市','360700','0797',7),(251,'吉安',15,'0,15,251','j','ja','jian','','市','360800','0796',8),(252,'宜春',15,'0,15,252','y','yc','yichun','','市','360900','0795',9),(253,'抚州',15,'0,15,253','f','fz','fuzhou','','市','361000','0794',10),(254,'上饶',15,'0,15,254','s','sr','shangrao','','市','361100','0793',11),(255,'济南',16,'0,16,255','j','jn','jinan','','市','370100','0531',1),(256,'青岛',16,'0,16,256','q','qd','qingdao','','市','370200','0532',2),(257,'淄博',16,'0,16,257','z','zb','zibo','','市','370300','0533',3),(258,'枣庄',16,'0,16,258','z','zz','zaozhuang','','市','370400','0632',4),(259,'东营',16,'0,16,259','d','dy','dongying','','市','370500','0546',5),(260,'烟台',16,'0,16,260','y','yt','yantai','','市','370600','0535',6),(261,'潍坊',16,'0,16,261','w','wf','weifang','','市','370700','0536',7),(262,'济宁',16,'0,16,262','j','jn','jining','','市','370800','0537',8),(263,'泰安',16,'0,16,263','t','ta','taian','','市','370900','0538',9),(264,'威海',16,'0,16,264','w','wh','weihai','','市','371000','0631',10),(265,'日照',16,'0,16,265','r','rz','rizhao','','市','371100','0633',11),(266,'莱芜',16,'0,16,266','l','lw','laiwu','','市','371200','0634',12),(267,'临沂',16,'0,16,267','l','ly','linyi','','市','371300','0539',13),(268,'德州',16,'0,16,268','d','dz','dezhou','','市','371400','0534',14),(269,'聊城',16,'0,16,269','l','lc','liaocheng','','市','371500','0635',15),(270,'滨州',16,'0,16,270','b','bz','binzhou','','市','371600','0543',16),(271,'菏泽',16,'0,16,271','h','hz','heze','','市','371700','0530',17),(272,'郑州',17,'0,17,272','z','zz','zhengzhou','','市','410100','0371',1),(273,'开封',17,'0,17,273','k','kf','kaifeng','','市','410200','0378',2),(274,'洛阳',17,'0,17,274','l','ly','luoyang','','市','410300','0376',3),(275,'平顶山',17,'0,17,275','p','pds','pingdingshan','','市','410400','0375',4),(276,'安阳',17,'0,17,276','a','ay','anyang','','市','410500','0372',5),(277,'鹤壁',17,'0,17,277','h','hb','hebi','','市','410600','0392',6),(278,'新乡',17,'0,17,278','x','xx','xinxiang','','市','410700','0373',7),(279,'焦作',17,'0,17,279','j','jz','jiaozuo','','市','410800','0391',8),(280,'濮阳',17,'0,17,280','p','py','puyang','','市','410900','',9),(281,'许昌',17,'0,17,281','x','xc','xuchang','','市','411000','0374',10),(282,'漯河',17,'0,17,282','l','lh','luohe','','市','411100','0395',11),(283,'三门峡',17,'0,17,283','s','smx','sanmenxia','','市','411200','0398',12),(284,'南阳',17,'0,17,284','n','ny','nanyang','','市','411300','0377',13),(285,'商丘',17,'0,17,285','s','sq','shangqiu','','市','411400','0370',14),(286,'信阳',17,'0,17,286','x','xy','xinyang','','市','411500','0376',15),(287,'周口',17,'0,17,287','z','zk','zhoukou','','市','411600','0394',16),(288,'驻马店',17,'0,17,288','z','zmd','zhumadian','','市','411700','0396',17),(289,'济源',17,'0,17,289','j','jy','jiyuan','','市','419001','0391',18),(290,'武汉',18,'0,18,290','w','wh','wuhan','','市','420100','027',1),(291,'黄石',18,'0,18,291','h','hs','huangshi','','市','420200','0714',2),(292,'十堰',18,'0,18,292','s','sy','shiyan','','市','420300','0719',3),(293,'宜昌',18,'0,18,293','y','yc','yichang','','市','420500','0717',4),(294,'襄阳',18,'0,18,294','x','xy','xiangyang','','市','420600','0710',5),(295,'鄂州',18,'0,18,295','e','ez','ezhou','','市','420700','',6),(296,'荆门',18,'0,18,296','j','jm','jingmen','','市','420800','0724',7),(297,'孝感',18,'0,18,297','x','xg','xiaogan','','市','420900','0712',8),(298,'荆州',18,'0,18,298','j','jz','jingzhou','','市','421000','0716',9),(299,'黄冈',18,'0,18,299','h','hg','huanggang','','市','421100','0713',10),(300,'咸宁',18,'0,18,300','x','xn','xianning','','市','421200','0715',11),(301,'随州',18,'0,18,301','s','sz','suizhou','','市','421300','0722',12),(302,'恩施',18,'0,18,302','e','es','enshi','土家族苗族','自治州','422800','0718',13),(303,'仙桃',18,'0,18,303','x','xt','xiantao','','市','429004','0728',14),(304,'潜江',18,'0,18,304','q','qj','qianjiang','','市','429005','0728',15),(305,'天门',18,'0,18,305','t','tm','tianmen','','市','429006','0728',16),(306,'神农架林区',18,'0,18,306','s','snjlq','shennongjialinqu','','','429021','0719',17),(307,'长沙',19,'0,19,307','z','zs','zhangsha','','市','430100','0731',1),(308,'株洲',19,'0,19,308','z','zz','zhuzhou','','市','430200','0731',2),(309,'湘潭',19,'0,19,309','x','xt','xiangtan','','市','430300','0731',3),(310,'衡阳',19,'0,19,310','h','hy','hengyang','','市','430400','0734',4),(311,'邵阳',19,'0,19,311','s','sy','shaoyang','','市','430500','0739',5),(312,'岳阳',19,'0,19,312','y','yy','yueyang','','市','430600','0730',6),(313,'常德',19,'0,19,313','c','cd','changde','','市','430700','0736',7),(314,'张家界',19,'0,19,314','z','zjj','zhangjiajie','','市','430800','0744',8),(315,'益阳',19,'0,19,315','y','yy','yiyang','','市','430900','0737',9),(316,'郴州',19,'0,19,316','c','cz','chenzhou','','市','431000','0735',10),(317,'永州',19,'0,19,317','y','yz','yongzhou','','市','431100','0746',11),(318,'怀化',19,'0,19,318','h','hh','huaihua','','市','431200','0745',12),(319,'娄底',19,'0,19,319','l','ld','loudi','','市','431300','0738',13),(320,'湘西',19,'0,19,320','x','xx','xiangxi','土家族苗族','自治州','433100','0743',14),(321,'广州',20,'0,20,321','g','gz','guangzhou','','市','440100','020',1),(322,'韶关',20,'0,20,322','s','sg','shaoguan','','市','440200','0751',2),(323,'深圳',20,'0,20,323','s','sz','shenzhen','','市','440300','0755',3),(324,'珠海',20,'0,20,324','z','zh','zhuhai','','市','440400','0756',4),(325,'汕头',20,'0,20,325','s','st','shantou','','市','440500','0754',5),(326,'佛山',20,'0,20,326','f','fs','foshan','','市','440600','0757',6),(327,'江门',20,'0,20,327','j','jm','jiangmen','','市','440700','0750',7),(328,'湛江',20,'0,20,328','z','zj','zhanjiang','','市','440800','0759',8),(329,'茂名',20,'0,20,329','m','mm','maoming','','市','440900','0668',9),(330,'肇庆',20,'0,20,330','z','zq','zhaoqing','','市','441200','0758',10),(331,'惠州',20,'0,20,331','h','hz','huizhou','','市','441300','0752',11),(332,'梅州',20,'0,20,332','m','mz','meizhou','','市','441400','0753',12),(333,'汕尾',20,'0,20,333','s','sw','shanwei','','市','441500','0660',13),(334,'河源',20,'0,20,334','h','hy','heyuan','','市','441600','0762',14),(335,'阳江',20,'0,20,335','y','yj','yangjiang','','市','441700','0662',15),(336,'清远',20,'0,20,336','q','qy','qingyuan','','市','441800','0763',16),(337,'东莞',20,'0,20,337','d','dg','dongguan','','市','441900','0769',17),(338,'中山',20,'0,20,338','z','zs','zhongshan','','市','442000','0760',18),(339,'潮州',20,'0,20,339','c','cz','chaozhou','','市','445100','0768',19),(340,'揭阳',20,'0,20,340','j','jy','jieyang','','市','445200','0663',20),(341,'云浮',20,'0,20,341','y','yf','yunfu','','市','445300','0766',21),(342,'南宁',21,'0,21,342','n','nn','nanning','','市','450100','0771',1),(343,'柳州',21,'0,21,343','l','lz','liuzhou','','市','450200','0772',2),(344,'桂林',21,'0,21,344','g','gl','guilin','','市','450300','0773',3),(345,'梧州',21,'0,21,345','w','wz','wuzhou','','市','450400','0774',4),(346,'北海',21,'0,21,346','b','bh','beihai','','市','450500','0779',5),(347,'防城港',21,'0,21,347','f','fcg','fangchenggang','','市','450600','0770',6),(348,'钦州',21,'0,21,348','q','qz','qinzhou','','市','450700','0777',7),(349,'贵港',21,'0,21,349','g','gg','guigang','','市','450800','0775',8),(350,'玉林',21,'0,21,350','y','yl','yulin','','市','450900','0775',9),(351,'百色',21,'0,21,351','b','bs','baise','','市','451000','0776',10),(352,'贺州',21,'0,21,352','h','hz','hezhou','','市','451100','0774',11),(353,'河池',21,'0,21,353','h','hc','hechi','','市','451200','0778',12),(354,'来宾',21,'0,21,354','l','lb','laibin','','市','451300','0772',13),(355,'崇左',21,'0,21,355','c','cz','chongzuo','','市','451400','0771',14),(356,'海口',22,'0,22,356','h','hk','haikou','','市','460100','0898',1),(357,'三亚',22,'0,22,357','s','sy','sanya','','市','460200','0898',2),(358,'三沙',22,'0,22,358','s','ss','sansha','','市','460300','0898',3),(359,'五指山',22,'0,22,359','w','wzs','wuzhishan','','市','469001','0898',4),(360,'琼海',22,'0,22,360','q','qh','qionghai','','市','469002','0898',5),(361,'儋州',22,'0,22,361','d','dz','danzhou','','市','469003','0898',6),(362,'文昌',22,'0,22,362','w','wc','wenchang','','市','469005','0898',7),(363,'万宁',22,'0,22,363','w','wn','wanning','','市','469006','0898',8),(364,'东方',22,'0,22,364','d','df','dongfang','','市','469007','0898',9),(365,'定安',22,'0,22,365','d','da','dingan','','县','469025','0898',10),(366,'屯昌',22,'0,22,366','t','tc','tunchang','','县','469026','0898',11),(367,'澄迈',22,'0,22,367','c','cm','chengmai','','县','469027','0898',12),(368,'临高',22,'0,22,368','l','lg','lingao','','县','469028','0898',13),(369,'白沙',22,'0,22,369','b','bs','baisha','黎族','自治县','469030','0898',14),(370,'昌江',22,'0,22,370','c','cj','changjiang','黎族','自治县','469031','0898',15),(371,'乐东',22,'0,22,371','l','ld','ledong','黎族','自治县','469033','0898',16),(372,'陵水',22,'0,22,372','l','ls','lingshui','黎族','自治县','469034','0898',17),(373,'保亭',22,'0,22,373','b','bt','baoting','黎族苗族','自治县','469035','0898',18),(374,'琼中',22,'0,22,374','q','qz','qiongzhong','黎族苗族','自治县','469036','0898',19),(375,'成都',23,'0,23,375','c','cd','chengdou','','市','510100','028',1),(376,'自贡',23,'0,23,376','z','zg','zigong','','市','510300','0813',2),(377,'攀枝花',23,'0,23,377','p','pzh','panzhihua','','市','510400','0812',3),(378,'泸州',23,'0,23,378','l','lz','luzhou','','市','510500','0830',4),(379,'德阳',23,'0,23,379','d','dy','deyang','','市','510600','0838',5),(380,'绵阳',23,'0,23,380','m','my','mianyang','','市','510700','0816',6),(381,'广元',23,'0,23,381','g','gy','guangyuan','','市','510800','0839',7),(382,'遂宁',23,'0,23,382','s','sn','suining','','市','510900','0825',8),(383,'内江',23,'0,23,383','n','nj','neijiang','','市','511000','',9),(384,'乐山',23,'0,23,384','l','ls','leshan','','市','511100','0833',10),(385,'南充',23,'0,23,385','n','nc','nanchong','','市','511300','0817',11),(386,'眉山',23,'0,23,386','m','ms','meishan','','市','511400','028',12),(387,'宜宾',23,'0,23,387','y','yb','yibin','','市','511500','0831',13),(388,'广安',23,'0,23,388','g','ga','guangan','','市','511600','',14),(389,'达州',23,'0,23,389','d','dz','dazhou','','市','511700','0818',15),(390,'雅安',23,'0,23,390','y','ya','yaan','','市','511800','0835',16),(391,'巴中',23,'0,23,391','b','bz','bazhong','','市','511900','0827',17),(392,'资阳',23,'0,23,392','z','zy','ziyang','','市','512000','028',18),(393,'阿坝',23,'0,23,393','a','ab','aba','藏族羌族','自治州','513200','0837',19),(394,'甘孜',23,'0,23,394','g','gz','ganzi','藏族','自治州','513300','0836',20),(395,'凉山',23,'0,23,395','l','ls','liangshan','彝族','自治州','615000','0834',21),(396,'贵阳',24,'0,24,396','g','gy','guiyang','','市','520100','0851',1),(397,'六盘水',24,'0,24,397','l','lps','liupanshui','','市','520200','0858',2),(398,'遵义',24,'0,24,398','z','zy','zunyi','','市','520300','0852',3),(399,'安顺',24,'0,24,399','a','as','anshun','','市','520400','0853',4),(400,'毕节',24,'0,24,400','b','bj','bijie','','市','520500','0857',5),(401,'铜仁',24,'0,24,401','t','tr','tongren','','市','520600','0856',6),(402,'黔西南',24,'0,24,402','q','qxn','qianxinan','布依族苗族','自治州','522300','0859',7),(403,'黔东南',24,'0,24,403','q','qdn','qiandongnan','苗族侗族','自治州','522600','0855',8),(404,'黔南',24,'0,24,404','q','qn','qiannan','布依族苗族','自治州','522700','0854',9),(405,'昆明',25,'0,25,405','k','km','kunming','','市','530100','0871',1),(406,'曲靖',25,'0,25,406','q','qj','qujing','','市','530300','0874',2),(407,'玉溪',25,'0,25,407','y','yx','yuxi','','市','530400','0877',3),(408,'昭通',25,'0,25,408','z','zt','zhaotong','','市','530600','0870',4),(409,'丽江',25,'0,25,409','l','lj','lijiang','','市','530700','0888',5),(410,'普洱',25,'0,25,410','p','pe','puer','','市','530800','0879',6),(411,'临沧',25,'0,25,411','l','lc','lincang','','市','530900','0883',7),(412,'楚雄',25,'0,25,412','c','cx','chuxiong','彝族','自治州','532300','0878',8),(413,'红河',25,'0,25,413','h','hh','honghe','哈尼族彝族','自治州','532500','0873',9),(414,'文山',25,'0,25,414','w','ws','wenshan','壮族苗族','自治州','532600','0876',10),(415,'西双版纳',25,'0,25,415','x','xsbn','xishuangbanna','傣族','自治州','532800','0691',11),(416,'大理',25,'0,25,416','d','dl','dali','白族','自治州','532900','0872',12),(417,'德宏',25,'0,25,417','d','dh','dehong','傣族景颇族','自治州','533100','0692',13),(418,'怒江',25,'0,25,418','n','nj','nujiang','傈僳族','自治州','533300','0886',14),(419,'迪庆',25,'0,25,419','d','dq','diqing','藏族','自治州','533400','0887',15),(420,'保山',25,'0,25,420','b','bs','baoshan','','市','678000','0875',16),(421,'拉萨',26,'0,26,421','l','ls','lasa','','市','540100','0891',1),(422,'昌都',26,'0,26,422','c','cd','changdou','','地区','542100','0895',2),(423,'山南',26,'0,26,423','s','sn','shannan','','地区','542200','0893',3),(424,'日喀则',26,'0,26,424','r','rkz','rikaze','','地区','542300','0892',4),(425,'那曲',26,'0,26,425','n','nq','neiqu','','地区','542400','0896',5),(426,'阿里',26,'0,26,426','a','al','ali','','地区','542500','0897',6),(427,'林芝',26,'0,26,427','l','lz','linzhi','','地区','542600','0894',7),(428,'西安',27,'0,27,428','x','xa','xian','','市','610100','029',1),(429,'铜川',27,'0,27,429','t','tc','tongchuan','','市','610200','0919',2),(430,'宝鸡',27,'0,27,430','b','bj','baoji','','市','610300','0917',3),(431,'咸阳',27,'0,27,431','x','xy','xianyang','','市','610400','029',4),(432,'渭南',27,'0,27,432','w','wn','weinan','','市','610500','0913',5),(433,'延安',27,'0,27,433','y','ya','yanan','','市','610600','0911',6),(434,'汉中',27,'0,27,434','h','hz','hanzhong','','市','610700','0916',7),(435,'榆林',27,'0,27,435','y','yl','yulin','','市','610800','0912',8),(436,'安康',27,'0,27,436','a','ak','ankang','','市','610900','0915',9),(437,'商洛',27,'0,27,437','s','sl','shangluo','','市','611000','0914',10),(438,'兰州',28,'0,28,438','l','lz','lanzhou','','市','620100','0931',1),(439,'嘉峪关',28,'0,28,439','j','jyg','jiayuguan','','市','620200','0937',2),(440,'金昌',28,'0,28,440','j','jc','jinchang','','市','620300','0935',3),(441,'白银',28,'0,28,441','b','by','baiyin','','市','620400','0943',4),(442,'天水',28,'0,28,442','t','ts','tianshui','','市','620500','0938',5),(443,'武威',28,'0,28,443','w','ww','wuwei','','市','620600','0935',6),(444,'张掖',28,'0,28,444','z','zy','zhangye','','市','620700','0936',7),(445,'平凉',28,'0,28,445','p','pl','pingliang','','市','620800','0933',8),(446,'酒泉',28,'0,28,446','j','jq','jiuquan','','市','620900','0937',9),(447,'庆阳',28,'0,28,447','q','qy','qingyang','','市','621000','0934',10),(448,'定西',28,'0,28,448','d','dx','dingxi','','市','621100','0932',11),(449,'陇南',28,'0,28,449','l','ln','longnan','','市','621200','0939',12),(450,'临夏',28,'0,28,450','l','lx','linxia','回族','自治州','622900','0930',13),(451,'甘南',28,'0,28,451','g','gn','gannan','藏族','自治州','623000','0941',14),(452,'西宁',29,'0,29,452','x','xn','xining','','市','630100','0971',1),(453,'海东',29,'0,29,453','h','hd','haidong','','市','632100','0972',2),(454,'海北',29,'0,29,454','h','hb','haibei','藏族','自治州','632200','0970',3),(455,'黄南',29,'0,29,455','h','hn','huangnan','藏族','自治州','632300','0973',4),(456,'海南',29,'0,29,456','h','hn','hainan','藏族','自治州','632500','0974',5),(457,'果洛',29,'0,29,457','g','gl','guoluo','藏族','自治州','632600','0975',6),(458,'玉树',29,'0,29,458','y','ys','yushu','藏族','自治州','632700','0976',7),(459,'海西',29,'0,29,459','h','hx','haixi','蒙古族藏族','自治州','632800','0977',8),(460,'银川',30,'0,30,460','y','yc','yinchuan','','市','640100','0951',1),(461,'石嘴山',30,'0,30,461','s','szs','shizuishan','','市','640200','0952',2),(462,'吴忠',30,'0,30,462','w','wz','wuzhong','','市','640300','0953',3),(463,'固原',30,'0,30,463','g','gy','guyuan','','市','640400','0954',4),(464,'中卫',30,'0,30,464','z','zw','zhongwei','','市','640500','',5),(465,'铁门关',31,'0,31,465','t','tmg','tiemenguan','','市','','',1),(466,'北屯',31,'0,31,466','b','bt','beitun','','市','','0906',2),(467,'乌鲁木齐',31,'0,31,467','w','wlmq','wulumuqi','','市','650100','0991',3),(468,'克拉玛依',31,'0,31,468','k','klmy','kelamayi','','市','650200','0990',4),(469,'吐鲁番',31,'0,31,469','t','tlf','tulufan','','地区','652100','0995',5),(470,'哈密',31,'0,31,470','h','hm','hami','','地区','652200','0902',6),(471,'昌吉',31,'0,31,471','c','cj','changji','回族','自治州','652300','0994',7),(472,'博尔塔拉',31,'0,31,472','b','betl','boertala','蒙古','自治州','652700','0909',8),(473,'巴音郭楞',31,'0,31,473','b','bygl','bayinguoleng','蒙古','自治州','652800','0996',9),(474,'阿克苏',31,'0,31,474','a','aks','akesu','','地区','652900','',10),(475,'克孜勒苏',31,'0,31,475','k','kzls','kezilesu','柯尔克孜','自治州','653000','0908',11),(476,'喀什',31,'0,31,476','k','ks','kashen','','地区','653100','0998',12),(477,'和田',31,'0,31,477','h','ht','hetian','','地区','653200','0903',13),(478,'伊犁',31,'0,31,478','y','yl','yili','哈萨克','自治州','654000','0999',14),(479,'塔城',31,'0,31,479','t','tc','tacheng','','地区','654200','0901',15),(480,'阿勒泰',31,'0,31,480','a','alt','aletai','','地区','654300','0906',16),(481,'石河子',31,'0,31,481','s','shz','shihezi','','市','659001','0993',17),(482,'阿拉尔',31,'0,31,482','a','ale','alaer','','市','659002','0997',18),(484,'五家渠',31,'0,31,484','w','wjq','wujiaqu','','市','659004','0994',20),(485,'台北',32,'0,32,485','t','tb','taibei','','市','63','02',1),(486,'高雄',32,'0,32,486','g','gx','gaoxiong','','市','64','07',2),(487,'基隆',32,'0,32,487','j','jl','jilong','','市','10017','02',3),(488,'台中',32,'0,32,488','t','tz','taizhong','','市','10019','04',4),(489,'台南',32,'0,32,489','t','tn','tainan','','市','10021','06',5),(490,'新竹',32,'0,32,490','x','xz','xinzhu','','市','10018','',6),(491,'嘉义',32,'0,32,491','j','jy','jiayi','','市','10020','05',7),(492,'新北',32,'0,32,492','x','xb','xinbei','','市','','',8),(493,'宜兰',32,'0,32,493','y','yl','yilan','','县','10002','',9),(494,'桃园',32,'0,32,494','t','ty','taoyuan','','县','10003','',10),(495,'新竹',32,'0,32,495','x','xz','xinzhu','','县','10004','',11),(496,'苗栗',32,'0,32,496','m','ml','miaoli','','县','10005','',12),(497,'彰化',32,'0,32,497','z','zh','zhanghua','','县','10007','',13),(498,'南投',32,'0,32,498','n','nt','nantou','','县','10008','',14),(499,'云林',32,'0,32,499','y','yl','yunlin','','县','10009','',15),(500,'嘉义',32,'0,32,500','j','jy','jiayi','','县','10020','',16),(501,'屏东',32,'0,32,501','p','pd','pingdong','','县','10013','',17),(502,'台东',32,'0,32,502','t','td','taidong','','县','10014','',18),(503,'花莲',32,'0,32,503','h','hl','hualian','','县','10015','',19),(504,'澎湖',32,'0,32,504','p','ph','penghu','','县','10016','07',20),(505,'连江',32,'0,32,505','l','lj','lianjiang','','县','','',21),(506,'金门',32,'0,32,506','j','jm','jinmen','','县','','',22),(507,'中西',33,'0,33,507','z','zx','zhongxi','','区','','852',1),(508,'葵青',33,'0,33,508','k','kq','kuiqing','','区','','852',2),(509,'元朗',33,'0,33,509','y','yl','yuanlang','','区','','852',3),(510,'屯门',33,'0,33,510','t','tm','tunmen','','区','','852',4),(511,'荃湾',33,'0,33,511','q','qw','quanwan','','区','','852',5),(512,'西贡',33,'0,33,512','x','xg','xigong','','区','','852',6),(513,'沙田',33,'0,33,513','s','st','shatian','','区','','852',7),(514,'大埔',33,'0,33,514','d','dp','dapu','','区','','852',8),(515,'北区',33,'0,33,515','b','bq','beiqu','','','','852',9),(516,'观塘',33,'0,33,516','g','gt','guantang','','区','','852',10),(517,'黄大仙',33,'0,33,517','h','hdx','huangdaxian','','区','','852',11),(518,'深水埗',33,'0,33,518','s','ssb','shenshuibu','','区','','852',12),(519,'油尖旺',33,'0,33,519','y','yjw','youjianwang','','区','','852',13),(520,'九龙城',33,'0,33,520','j','jlc','jiulongcheng','','区','','852',14),(521,'南区',33,'0,33,521','n','nq','nanqu','','','','852',15),(522,'东区',33,'0,33,522','d','dq','dongqu','','','','852',16),(523,'湾仔',33,'0,33,523','w','wz','wanzi','','区','','852',17),(524,'离岛',33,'0,33,524','l','ld','lidao','','区','','852',18),(525,'花地玛',34,'0,34,525','h','hdm','huadima','','堂区','','853',1),(526,'圣安多',34,'0,34,526','s','sad','shenganduo','','堂区','','853',2),(527,'大堂区',34,'0,34,527','d','dtq','datangqu','','堂区','','853',3),(528,'望德',34,'0,34,528','w','wd','wangde','','堂区','','853',4),(529,'风顺',34,'0,34,529','f','fs','fengshun','','堂区','','853',5),(530,'嘉模',34,'0,34,530','j','jm','jiamo','','堂区','','853',6),(531,'圣方济各',34,'0,34,531','s','sfjg','shengfangjige','','堂区','','853',7),(532,'路氹城',34,'0,34,532','l','ldc','ludangcheng','','','','853',8),(533,'长安',125,'125,533','z','za','zhangan','','区','130102','0311',1),(534,'桥东',125,'125,534','q','qd','qiaodong','','区','130103','0311',2),(535,'桥西',125,'125,535','q','qx','qiaoxi','','区','130104','0311',3),(536,'新华',125,'125,536','x','xh','xinhua','','区','130105','0311',4),(537,'井陉矿',125,'125,537','j','jxk','jingxingkuang','','区','130107','0311',5),(538,'裕华',125,'125,538','y','yh','yuhua','','区','130108','0311',6),(539,'井陉',125,'125,539','j','jx','jingxing','','县','130121','0311',7),(540,'正定',125,'125,540','z','zd','zhengding','','县','130123','0311',8),(541,'栾城',125,'125,541','l','lc','luancheng','','县','130124','0311',9),(542,'行唐',125,'125,542','x','xt','xingtang','','县','130125','0311',10),(543,'灵寿',125,'125,543','l','ls','lingshou','','县','130126','0311',11),(544,'高邑',125,'125,544','g','gy','gaoyi','','县','130127','0311',12),(545,'深泽',125,'125,545','s','sz','shenze','','县','130128','0311',13),(546,'赞皇',125,'125,546','z','zh','zanhuang','','县','130129','0311',14),(547,'无极',125,'125,547','w','wj','wuji','','县','130130','0311',15),(548,'平山',125,'125,548','p','ps','pingshan','','县','130131','0311',16),(549,'元氏',125,'125,549','y','ys','yuanshi','','县','130132','0311',17),(550,'赵县',125,'125,550','z','zx','zhaoxian','','','130133','0311',18),(551,'辛集',125,'125,551','x','xj','xinji','','市','130181','0311',19),(552,'藁城',125,'125,552','g','gc','gaocheng','','市','130182','0311',20),(553,'晋州',125,'125,553','j','jz','jinzhou','','市','130183','0311',21),(554,'新乐',125,'125,554','x','xl','xinle','','市','130184','0311',22),(555,'鹿泉',125,'125,555','l','lq','luquan','','市','130185','0311',23),(556,'路南',126,'126,556','l','ln','lunan','','区','130202','0315',1),(557,'路北',126,'126,557','l','lb','lubei','','区','130203','0315',2),(558,'古冶',126,'126,558','g','gy','guye','','区','130204','0315',3),(559,'开平',126,'126,559','k','kp','kaiping','','区','130205','0315',4),(560,'丰南',126,'126,560','f','fn','fengnan','','区','130207','0315',5),(561,'丰润',126,'126,561','f','fr','fengrun','','区','130208','0315',6),(562,'滦县',126,'126,562','l','lx','luanxian','','','130223','0315',7),(563,'滦南',126,'126,563','l','ln','luannan','','县','130224','0315',8),(564,'乐亭',126,'126,564','l','lt','leting','','县','130225','0315',9),(565,'迁西',126,'126,565','q','qx','qianxi','','县','130227','0315',10),(566,'玉田',126,'126,566','y','yt','yutian','','县','130229','0315',11),(567,'唐海',126,'126,567','t','th','tanghai','','县','130230','0315',12),(568,'遵化',126,'126,568','z','zh','zunhua','','市','130281','0315',13),(569,'迁安',126,'126,569','q','qa','qianan','','市','130283','0315',14),(570,'海港',127,'127,570','h','hg','haigang','','区','130302','0335',1),(571,'山海关',127,'127,571','s','shg','shanhaiguan','','区','130303','0335',2),(572,'北戴河',127,'127,572','b','bdh','beidaihe','','区','130304','0335',3),(573,'青龙',127,'127,573','q','ql','qinglong','满族','自治县','130321','0335',4),(574,'昌黎',127,'127,574','c','cl','changli','','县','130322','0335',5),(575,'抚宁',127,'127,575','f','fn','funing','','县','130323','0335',6),(576,'卢龙',127,'127,576','l','ll','lulong','','县','130324','0335',7),(577,'邯山',128,'128,577','h','hs','hanshan','','区','130402','0310',1),(578,'丛台',128,'128,578','c','ct','congtai','','区','130403','0310',2),(579,'复兴',128,'128,579','f','fx','fuxing','','区','130404','0310',3),(580,'峰峰矿',128,'128,580','f','ffk','fengfengkuang','','区','130406','0310',4),(581,'邯郸',128,'128,581','h','hd','handan','','县','130421','0310',5),(582,'临漳',128,'128,582','l','lz','linzhang','','县','130423','0310',6),(583,'成安',128,'128,583','c','ca','chengan','','县','130424','0310',7),(584,'大名',128,'128,584','d','dm','daming','','县','130425','0310',8),(585,'涉县',128,'128,585','s','sx','shexian','','','130426','0310',9),(586,'磁县',128,'128,586','c','cx','cixian','','','130427','0310',10),(587,'肥乡',128,'128,587','f','fx','feixiang','','县','130428','0310',11),(588,'永年',128,'128,588','y','yn','yongnian','','县','130429','0310',12),(589,'邱县',128,'128,589','q','qx','qiuxian','','','130430','0310',13),(590,'鸡泽',128,'128,590','j','jz','jize','','县','130431','0310',14),(591,'广平',128,'128,591','g','gp','guangping','','县','130432','0310',15),(592,'馆陶',128,'128,592','g','gt','guantao','','县','130433','0310',16),(593,'魏县',128,'128,593','w','wx','weixian','','','130434','0310',17),(594,'曲周',128,'128,594','q','qz','quzhou','','县','130435','0310',18),(595,'武安',128,'128,595','w','wa','wuan','','市','130481','0310',19),(596,'桥东',129,'129,596','q','qd','qiaodong','','区','130502','0319',1),(597,'桥西',129,'129,597','q','qx','qiaoxi','','区','130503','0319',2),(598,'邢台',129,'129,598','x','xt','xingtai','','县','130521','0319',3),(599,'临城',129,'129,599','l','lc','lincheng','','县','130522','0319',4),(600,'内丘',129,'129,600','n','nq','neiqiu','','县','130523','0319',5),(601,'柏乡',129,'129,601','b','bx','boxiang','','县','130524','0319',6),(602,'隆尧',129,'129,602','l','ly','longyao','','县','130525','0319',7),(603,'任县',129,'129,603','r','rx','renxian','','','130526','0319',8),(604,'南和',129,'129,604','n','nh','nanhe','','县','130527','0319',9),(605,'宁晋',129,'129,605','n','nj','ningjin','','县','130528','0319',10),(606,'巨鹿',129,'129,606','j','jl','julu','','县','130529','0319',11),(607,'新河',129,'129,607','x','xh','xinhe','','县','130530','0319',12),(608,'广宗',129,'129,608','g','gz','guangzong','','县','130531','0319',13),(609,'平乡',129,'129,609','p','px','pingxiang','','县','130532','0319',14),(610,'威县',129,'129,610','w','wx','weixian','','','130533','0319',15),(611,'清河',129,'129,611','q','qh','qinghe','','县','130534','0319',16),(612,'临西',129,'129,612','l','lx','linxi','','县','130535','0319',17),(613,'南宫',129,'129,613','n','ng','nangong','','市','130581','0319',18),(614,'沙河',129,'129,614','s','sh','shahe','','市','130582','0319',19),(615,'新市',130,'130,615','x','xs','xinshi','','区','130602','0312',1),(616,'北市',130,'130,616','b','bs','beishi','','区','130603','0312',2),(617,'南市',130,'130,617','n','ns','nanshi','','区','130604','0312',3),(618,'满城',130,'130,618','m','mc','mancheng','','县','130621','0312',4),(619,'清苑',130,'130,619','q','qy','qingyuan','','县','130622','0312',5),(620,'涞水',130,'130,620','l','ls','laishui','','县','130623','0312',6),(621,'阜平',130,'130,621','f','fp','fuping','','县','130624','0312',7),(622,'徐水',130,'130,622','x','xs','xushui','','县','130625','0312',8),(623,'定兴',130,'130,623','d','dx','dingxing','','县','130626','0312',9),(624,'唐县',130,'130,624','t','tx','tangxian','','','130627','0312',10),(625,'高阳',130,'130,625','g','gy','gaoyang','','县','130628','0312',11),(626,'容城',130,'130,626','r','rc','rongcheng','','县','130629','0312',12),(627,'涞源',130,'130,627','l','ly','laiyuan','','县','130630','0312',13),(628,'望都',130,'130,628','w','wd','wangdou','','县','130631','0312',14),(629,'安新',130,'130,629','a','ax','anxin','','县','130632','0312',15),(630,'易县',130,'130,630','y','yx','yixian','','','130633','0312',16),(631,'曲阳',130,'130,631','q','qy','quyang','','县','130634','0312',17),(632,'蠡县',130,'130,632','l','lx','lixian','','','130635','0312',18),(633,'顺平',130,'130,633','s','sp','shunping','','县','130636','0312',19),(634,'博野',130,'130,634','b','by','boye','','县','130637','0312',20),(635,'雄县',130,'130,635','x','xx','xiongxian','','','130638','0312',21),(636,'涿州',130,'130,636','z','zz','zhuozhou','','市','130681','0312',22),(637,'定州',130,'130,637','d','dz','dingzhou','','市','130682','0312',23),(638,'安国',130,'130,638','a','ag','anguo','','市','130683','0312',24),(639,'高碑店',130,'130,639','g','gbd','gaobeidian','','市','130684','0312',25),(640,'桥东',131,'131,640','q','qd','qiaodong','','区','130702','0313',1),(641,'桥西',131,'131,641','q','qx','qiaoxi','','区','130703','0313',2),(642,'宣化',131,'131,642','x','xh','xuanhua','','区','130705','0313',3),(643,'下花园',131,'131,643','x','xhy','xiahuayuan','','区','130706','0313',4),(644,'宣化',131,'131,644','x','xh','xuanhua','','县','130721','0313',5),(645,'张北',131,'131,645','z','zb','zhangbei','','县','130722','0313',6),(646,'康保',131,'131,646','k','kb','kangbao','','县','130723','0313',7),(647,'沽源',131,'131,647','g','gy','guyuan','','县','130724','0313',8),(648,'尚义',131,'131,648','s','sy','shangyi','','县','130725','0313',9),(649,'蔚县',131,'131,649','y','yx','yuxian','','','130726','0313',10),(650,'阳原',131,'131,650','y','yy','yangyuan','','县','130727','0313',11),(651,'怀安',131,'131,651','h','ha','huaian','','县','130728','0313',12),(652,'万全',131,'131,652','w','wq','wanquan','','县','130729','0313',13),(653,'怀来',131,'131,653','h','hl','huailai','','县','130730','0313',14),(654,'涿鹿',131,'131,654','z','zl','zhuolu','','县','130731','0313',15),(655,'赤城',131,'131,655','c','cc','chicheng','','县','130732','0313',16),(656,'崇礼',131,'131,656','c','cl','chongli','','县','130733','0313',17),(657,'双桥',132,'132,657','s','sq','shuangqiao','','区','130802','0314',1),(658,'双滦',132,'132,658','s','sl','shuangluan','','区','130803','0314',2),(659,'鹰手营子',132,'132,659','y','ysyz','yingshouyingzi','矿','区','130804','0314',3),(660,'承德',132,'132,660','c','cd','chengde','','县','130821','0314',4),(661,'兴隆',132,'132,661','x','xl','xinglong','','县','130822','0314',5),(662,'平泉',132,'132,662','p','pq','pingquan','','县','130823','0314',6),(663,'滦平',132,'132,663','l','lp','luanping','','县','130824','0314',7),(664,'隆化',132,'132,664','l','lh','longhua','','县','130825','0314',8),(665,'丰宁',132,'132,665','f','fn','fengning','满族','自治县','130826','0314',9),(666,'宽城',132,'132,666','k','kc','kuancheng','满族','自治县','130827','0314',10),(667,'围场',132,'132,667','w','wc','weichang','满族蒙古族','自治县','130828','0314',11),(668,'新华',133,'133,668','x','xh','xinhua','','区','130902','0317',1),(669,'运河',133,'133,669','y','yh','yunhe','','区','130903','0317',2),(670,'沧县',133,'133,670','c','cx','cangxian','','','130921','0317',3),(671,'青县',133,'133,671','q','qx','qingxian','','','130922','0317',4),(672,'东光',133,'133,672','d','dg','dongguang','','县','130923','0317',5),(673,'海兴',133,'133,673','h','hx','haixing','','县','130924','0317',6),(674,'盐山',133,'133,674','y','ys','yanshan','','县','130925','0317',7),(675,'肃宁',133,'133,675','s','sn','suning','','县','130926','0317',8),(676,'南皮',133,'133,676','n','np','nanpi','','县','130927','0317',9),(677,'吴桥',133,'133,677','w','wq','wuqiao','','县','130928','0317',10),(678,'献县',133,'133,678','x','xx','xianxian','','','130929','0317',11),(679,'孟村',133,'133,679','m','mc','mengcun','回族','自治县','130930','0317',12),(680,'泊头',133,'133,680','b','bt','botou','','市','130981','0317',13),(681,'任丘',133,'133,681','r','rq','renqiu','','市','130982','0317',14),(682,'黄骅',133,'133,682','h','hh','huanghua','','市','130983','0317',15),(683,'河间',133,'133,683','h','hj','hejian','','市','130984','0317',16),(684,'安次',134,'134,684','a','ac','anci','','区','131002','0316',1),(685,'广阳',134,'134,685','g','gy','guangyang','','区','131003','0316',2),(686,'固安',134,'134,686','g','ga','guan','','县','131022','0316',3),(687,'永清',134,'134,687','y','yq','yongqing','','县','131023','0316',4),(688,'香河',134,'134,688','x','xh','xianghe','','县','131024','0316',5),(689,'大城',134,'134,689','d','dc','dacheng','','县','131025','0316',6),(690,'文安',134,'134,690','w','wa','wenan','','县','131026','0316',7),(691,'大厂',134,'134,691','d','dc','dachang','回族','自治县','131028','0316',8),(692,'霸州',134,'134,692','b','bz','bazhou','','市','131081','0316',9),(693,'三河',134,'134,693','s','sh','sanhe','','市','131082','0316',10),(694,'桃城',135,'135,694','t','tc','taocheng','','区','131102','0318',1),(695,'枣强',135,'135,695','z','zq','zaoqiang','','县','131121','0318',2),(696,'武邑',135,'135,696','w','wy','wuyi','','县','131122','0318',3),(697,'武强',135,'135,697','w','wq','wuqiang','','县','131123','0318',4),(698,'饶阳',135,'135,698','r','ry','raoyang','','县','131124','0318',5),(699,'安平',135,'135,699','a','ap','anping','','县','131125','0318',6),(700,'故城',135,'135,700','g','gc','gucheng','','县','131126','0318',7),(701,'景县',135,'135,701','j','jx','jingxian','','','131127','0318',8),(702,'阜城',135,'135,702','f','fc','fucheng','','县','131128','0318',9),(703,'冀州',135,'135,703','j','jz','jizhou','','市','131181','0318',10),(704,'深州',135,'135,704','s','sz','shenzhou','','市','131182','0318',11),(705,'小店',136,'136,705','x','xd','xiaodian','','区','140105','0351',1),(706,'迎泽',136,'136,706','y','yz','yingze','','区','140106','0351',2),(707,'杏花岭',136,'136,707','x','xhl','xinghualing','','区','140107','0351',3),(708,'尖草坪',136,'136,708','j','jcp','jiancaoping','','区','140108','0351',4),(709,'万柏林',136,'136,709','w','wbl','wanbolin','','区','140109','0351',5),(710,'晋源',136,'136,710','j','jy','jinyuan','','区','140110','0351',6),(711,'清徐',136,'136,711','q','qx','qingxu','','县','140121','0351',7),(712,'阳曲',136,'136,712','y','yq','yangqu','','县','140122','0351',8),(713,'娄烦',136,'136,713','l','lf','loufan','','县','140123','0351',9),(714,'古交',136,'136,714','g','gj','gujiao','','市','140181','0351',10),(715,'城区',137,'137,715','c','cq','chengqu','','','140202','0352',1),(716,'矿区',137,'137,716','k','kq','kuangqu','','','140203','0352',2),(717,'南郊',137,'137,717','n','nj','nanjiao','','区','140211','0352',3),(718,'新荣',137,'137,718','x','xr','xinrong','','区','140212','0352',4),(719,'阳高',137,'137,719','y','yg','yanggao','','县','140221','0352',5),(720,'天镇',137,'137,720','t','tz','tianzhen','','县','140222','0352',6),(721,'广灵',137,'137,721','g','gl','guangling','','县','140223','0352',7),(722,'灵丘',137,'137,722','l','lq','lingqiu','','县','140224','0352',8),(723,'浑源',137,'137,723','h','hy','hunyuan','','县','140225','0352',9),(724,'左云',137,'137,724','z','zy','zuoyun','','县','140226','0352',10),(725,'大同',137,'137,725','d','dt','datong','','县','140227','0352',11),(726,'城区',138,'138,726','c','cq','chengqu','','','140302','0353',1),(727,'矿区',138,'138,727','k','kq','kuangqu','','','140303','0353',2),(728,'郊区',138,'138,728','j','jq','jiaoqu','','','140311','0353',3),(729,'平定',138,'138,729','p','pd','pingding','','县','140321','0353',4),(730,'盂县',138,'138,730','y','yx','yuxian','','','140322','0353',5),(731,'城区',139,'139,731','c','cq','chengqu','','','140402','0355',1),(732,'郊区',139,'139,732','j','jq','jiaoqu','','','140411','0355',2),(733,'长治',139,'139,733','z','zz','zhangzhi','','县','140421','0355',3),(734,'襄垣',139,'139,734','x','xy','xiangyuan','','县','140423','0355',4),(735,'屯留',139,'139,735','t','tl','tunliu','','县','140424','0355',5),(736,'平顺',139,'139,736','p','ps','pingshun','','县','140425','0355',6),(737,'黎城',139,'139,737','l','lc','licheng','','县','140426','0355',7),(738,'壶关',139,'139,738','h','hg','huguan','','县','140427','0355',8),(739,'长子',139,'139,739','z','zz','zhangzi','','县','140428','0355',9),(740,'武乡',139,'139,740','w','wx','wuxiang','','县','140429','0355',10),(741,'沁县',139,'139,741','q','qx','qinxian','','','140430','0355',11),(742,'沁源',139,'139,742','q','qy','qinyuan','','县','140431','0355',12),(743,'潞城',139,'139,743','l','lc','lucheng','','县','140481','0355',13),(744,'城区',140,'140,744','c','cq','chengqu','','','140502','0356',1),(745,'沁水',140,'140,745','q','qs','qinshui','','县','140521','0356',2),(746,'阳城',140,'140,746','y','yc','yangcheng','','县','140522','0356',3),(747,'陵川',140,'140,747','l','lc','lingchuan','','县','140524','0356',4),(748,'泽州',140,'140,748','z','zz','zezhou','','县','140525','0356',5),(749,'高平',140,'140,749','g','gp','gaoping','','市','140581','0356',6),(750,'朔城',141,'141,750','s','sc','shuocheng','','区','140602','0349',1),(751,'平鲁',141,'141,751','p','pl','pinglu','','区','140603','0349',2),(752,'山阴',141,'141,752','s','sy','shanyin','','县','140621','0349',3),(753,'应县',141,'141,753','y','yx','yingxian','','','140622','0349',4),(754,'右玉',141,'141,754','y','yy','youyu','','县','140623','0349',5),(755,'怀仁',141,'141,755','h','hr','huairen','','县','140624','0349',6),(756,'榆次',142,'142,756','y','yc','yuci','','区','030600','0354',1),(757,'榆社',142,'142,757','y','ys','yushe','','县','140721','0354',2),(758,'左权',142,'142,758','z','zq','zuoquan','','县','140722','0354',3),(759,'和顺',142,'142,759','h','hs','heshun','','县','140723','0354',4),(760,'昔阳',142,'142,760','x','xy','xiyang','','县','140724','0354',5),(761,'寿阳',142,'142,761','s','sy','shouyang','','县','140725','0354',6),(762,'太谷',142,'142,762','t','tg','taigu','','县','140726','0354',7),(763,'祁县',142,'142,763','q','qx','qixian','','','140727','0354',8),(764,'平遥',142,'142,764','p','py','pingyao','','县','140728','0354',9),(765,'灵石',142,'142,765','l','ls','lingshi','','县','140729','0354',10),(766,'介休',142,'142,766','j','jx','jiexiu','','市','140781','0354',11),(767,'盐湖',143,'143,767','y','yh','yanhu','','区','140802','0359',1),(768,'临猗',143,'143,768','l','ly','linyi','','县','140821','0359',2),(769,'万荣',143,'143,769','w','wr','wanrong','','县','140822','0359',3),(770,'闻喜',143,'143,770','w','wx','wenxi','','县','140823','0359',4),(771,'稷山',143,'143,771','j','js','jishan','','县','140824','0359',5),(772,'新绛',143,'143,772','x','xj','xinjiang','','县','140825','0359',6),(773,'绛县',143,'143,773','j','jx','jiangxian','','','140826','0359',7),(774,'垣曲',143,'143,774','y','yq','yuanqu','','县','140827','0359',8),(775,'夏县',143,'143,775','x','xx','xiaxian','','','140828','0359',9),(776,'平陆',143,'143,776','p','pl','pinglu','','县','140829','0359',10),(777,'芮城',143,'143,777','r','rc','ruicheng','','县','140830','0359',11),(778,'永济',143,'143,778','y','yj','yongji','','市','140881','0359',12),(779,'河津',143,'143,779','h','hj','hejin','','市','140882','0359',13),(780,'忻府',144,'144,780','x','xf','xinfu','','区','140902','0350',1),(781,'定襄',144,'144,781','d','dx','dingxiang','','县','140921','0350',2),(782,'五台',144,'144,782','w','wt','wutai','','县','140922','0350',3),(783,'代县',144,'144,783','d','dx','daixian','','','140923','0350',4),(784,'繁峙',144,'144,784','f','fz','fanzhi','','县','140924','0350',5),(785,'宁武',144,'144,785','n','nw','ningwu','','县','140925','0350',6),(786,'静乐',144,'144,786','j','jl','jingle','','县','140926','0350',7),(787,'神池',144,'144,787','s','sc','shenchi','','县','140927','0350',8),(788,'五寨',144,'144,788','w','wz','wuzhai','','县','140928','0350',9),(789,'岢岚',144,'144,789','k','kl','kelan','','县','140929','0350',10),(790,'河曲',144,'144,790','h','hq','hequ','','县','140930','0350',11),(791,'保德',144,'144,791','b','bd','baode','','县','140931','0350',12),(792,'偏关',144,'144,792','p','pg','pianguan','','县','140932','0350',13),(793,'原平',144,'144,793','y','yp','yuanping','','市','140981','0350',14),(794,'尧都',145,'145,794','y','yd','yaodou','','区','141002','0357',1),(795,'曲沃',145,'145,795','q','qw','quwo','','县','141021','0357',2),(796,'翼城',145,'145,796','y','yc','yicheng','','县','141022','0357',3),(797,'襄汾',145,'145,797','x','xf','xiangfen','','县','141023','0357',4),(798,'洪洞',145,'145,798','h','hd','hongdong','','县','141024','0357',5),(799,'古县',145,'145,799','g','gx','guxian','','','141025','0357',6),(800,'安泽',145,'145,800','a','az','anze','','县','141026','0357',7),(801,'浮山',145,'145,801','f','fs','fushan','','县','141027','0357',8),(802,'吉县',145,'145,802','j','jx','jixian','','','141028','0357',9),(803,'乡宁',145,'145,803','x','xn','xiangning','','县','141029','0357',10),(804,'大宁',145,'145,804','d','dn','daning','','县','141030','0357',11),(805,'隰县',145,'145,805','x','xx','xixian','','','141031','0357',12),(806,'永和',145,'145,806','y','yh','yonghe','','县','141032','0357',13),(807,'蒲县',145,'145,807','p','px','puxian','','','141033','0357',14),(808,'汾西',145,'145,808','f','fx','fenxi','','县','141034','0357',15),(809,'侯马',145,'145,809','h','hm','houma','','市','141081','0357',16),(810,'霍州',145,'145,810','h','hz','huozhou','','市','141082','0357',17),(811,'离石',146,'146,811','l','ls','lishi','','区','141102','0358',1),(812,'文水',146,'146,812','w','ws','wenshui','','县','141121','0358',2),(813,'交城',146,'146,813','j','jc','jiaocheng','','县','141122','0358',3),(814,'兴县',146,'146,814','x','xx','xingxian','','','141123','0358',4),(815,'临县',146,'146,815','l','lx','linxian','','','141124','0358',5),(816,'柳林',146,'146,816','l','ll','liulin','','县','141125','0358',6),(817,'石楼',146,'146,817','s','sl','shilou','','县','141126','0358',7),(818,'岚县',146,'146,818','l','lx','lanxian','','','141127','0358',8),(819,'方山',146,'146,819','f','fs','fangshan','','县','141128','0358',9),(820,'中阳',146,'146,820','z','zy','zhongyang','','县','141129','0358',10),(821,'交口',146,'146,821','j','jk','jiaokou','','县','141130','0358',11),(822,'孝义',146,'146,822','x','xy','xiaoyi','','市','141181','0358',12),(823,'汾阳',146,'146,823','f','fy','fenyang','','市','141182','0358',13),(824,'新城',147,'147,824','x','xc','xincheng','','区','150102','0471',1),(825,'回民',147,'147,825','h','hm','huimin','','区','150103','0471',2),(826,'玉泉',147,'147,826','y','yq','yuquan','','区','150104','0471',3),(827,'赛罕',147,'147,827','s','sh','saihan','','区','150105','0471',4),(828,'土默特左',147,'147,828','t','tmtz','tumotezuo','','旗','150121','0471',5),(829,'托克托',147,'147,829','t','tkt','tuoketuo','','县','150122','0471',6),(830,'和林格尔',147,'147,830','h','hlge','helingeer','','县','150123','0471',7),(831,'清水河',147,'147,831','q','qsh','qingshuihe','','县','150124','0471',8),(832,'武川',147,'147,832','w','wc','wuchuan','','县','150125','0471',9),(833,'东河',148,'148,833','d','dh','donghe','','区','150202','0472',1),(834,'昆都仑',148,'148,834','k','kdl','kundoulun','','区','150203','0472',2),(835,'青山',148,'148,835','q','qs','qingshan','','区','150204','0472',3),(836,'石拐',148,'148,836','s','sg','shiguai','','区','150205','0472',4),(837,'白云',148,'148,837','b','by','baiyun','矿','区','150206','0472',5),(838,'九原',148,'148,838','j','jy','jiuyuan','','区','150207','0472',6),(839,'土默特右',148,'148,839','t','tmty','tumoteyou','','旗','150221','0472',7),(840,'固阳',148,'148,840','g','gy','guyang','','县','150222','0472',8),(841,'达尔罕茂明安',148,'148,841','d','dehmma','daerhanmaomingan','联合','旗','150223','0472',9),(842,'海勃湾',149,'149,842','h','hbw','haibowan','','区','150302','0473',1),(843,'海南',149,'149,843','h','hn','hainan','','区','150303','0473',2),(844,'乌达',149,'149,844','w','wd','wuda','','区','150304','0473',3),(845,'红山',150,'150,845','h','hs','hongshan','','区','150402','0476',1),(846,'元宝山',150,'150,846','y','ybs','yuanbaoshan','','区','150403','0476',2),(847,'松山',150,'150,847','s','ss','songshan','','区','150404','0476',3),(848,'阿鲁科尔沁',150,'150,848','a','alkeq','alukeerqin','','旗','150421','0476',4),(849,'巴林左',150,'150,849','b','blz','balinzuo','','旗','150422','0476',5),(850,'巴林右',150,'150,850','b','bly','balinyou','','旗','150423','0476',6),(851,'林西',150,'150,851','l','lx','linxi','','县','150424','0476',7),(852,'克什克腾',150,'150,852','k','kskt','keshenketeng','','旗','150425','0476',8),(853,'翁牛特',150,'150,853','w','wnt','wengniute','','旗','150426','0476',9),(854,'喀喇沁',150,'150,854','k','klq','kalaqin','','旗','150428','0476',10),(855,'宁城',150,'150,855','n','nc','ningcheng','','县','150429','0476',11),(856,'敖汉',150,'150,856','a','ah','aohan','','旗','150430','0476',12),(857,'科尔沁',151,'151,857','k','keq','keerqin','','区','150502','0475',1),(858,'科尔沁左翼中',151,'151,858','k','keqzyz','keerqinzuoyizhong','','旗','150521','0475',2),(859,'科尔沁左翼后',151,'151,859','k','keqzyh','keerqinzuoyihou','','旗','150522','0475',3),(860,'开鲁',151,'151,860','k','kl','kailu','','县','150523','0475',4),(861,'库伦',151,'151,861','k','kl','kulun','','旗','150524','0475',5),(862,'奈曼',151,'151,862','n','nm','naiman','','旗','150525','0475',6),(863,'扎鲁特',151,'151,863','z','zlt','zhalute','','旗','150526','0475',7),(864,'霍林郭勒',151,'151,864','h','hlgl','huolinguole','','市','150581','0475',8),(865,'东胜',152,'152,865','d','ds','dongsheng','','区','150602','0477',1),(866,'达拉特',152,'152,866','d','dlt','dalate','','旗','150621','0477',2),(867,'准格尔',152,'152,867','z','zge','zhungeer','','旗','150622','0477',3),(868,'鄂托克前',152,'152,868','e','etkq','etuokeqian','','旗','150623','0477',4),(869,'鄂托克',152,'152,869','e','etk','etuoke','','旗','150624','0477',5),(870,'杭锦',152,'152,870','h','hj','hangjin','','旗','150625','0477',6),(871,'乌审',152,'152,871','w','ws','wushen','','旗','150626','0477',7),(872,'伊金霍洛',152,'152,872','y','yjhl','yijinhuoluo','','旗','150627','0477',8),(873,'海拉尔',153,'153,873','h','hle','hailaer','','区','150702','0470',1),(874,'阿荣',153,'153,874','a','ar','arong','','旗','150721','0470',2),(875,'莫力达瓦',153,'153,875','m','mldw','molidawa','达斡尔族','自治旗','150722','0470',3),(876,'鄂伦春',153,'153,876','e','elc','elunchun','','自治旗','150723','0470',4),(877,'鄂温克族',153,'153,877','e','ewkz','ewenkezu','','自治旗','150724','0470',5),(878,'陈巴尔虎',153,'153,878','c','cbeh','chenbaerhu','','旗','150725','0470',6),(879,'新巴尔虎左',153,'153,879','x','xbehz','xinbaerhuzuo','','旗','150726','0470',7),(880,'新巴尔虎右',153,'153,880','x','xbehy','xinbaerhuyou','','旗','150727','0470',8),(881,'满洲里',153,'153,881','m','mzl','manzhouli','','市','150781','0470',9),(882,'牙克石',153,'153,882','y','yks','yakeshi','','市','150782','0470',10),(883,'扎兰屯',153,'153,883','z','zlt','zhalantun','','市','150783','0470',11),(884,'额尔古纳',153,'153,884','e','eegn','eerguna','','市','150784','0470',12),(885,'根河',153,'153,885','g','gh','genhe','','市','150785','0470',13),(886,'临河',154,'154,886','l','lh','linhe','','区','150802','0478',1),(887,'五原',154,'154,887','w','wy','wuyuan','','县','150821','0478',2),(888,'磴口',154,'154,888','d','dk','dengkou','','县','150822','0478',3),(889,'乌拉特前',154,'154,889','w','wltq','wulateqian','','旗','150823','0478',4),(890,'乌拉特中',154,'154,890','w','wltz','wulatezhong','','旗','150824','0478',5),(891,'乌拉特后',154,'154,891','w','wlth','wulatehou','','旗','150825','0478',6),(892,'杭锦后',154,'154,892','h','hjh','hangjinhou','','旗','150826','0478',7),(893,'集宁',155,'155,893','j','jn','jining','','区','150902','0474',1),(894,'卓资',155,'155,894','z','zz','zhuozi','','县','150921','0474',2),(895,'化德',155,'155,895','h','hd','huade','','县','150922','0474',3),(896,'商都',155,'155,896','s','sd','shangdou','','县','150923','0474',4),(897,'兴和',155,'155,897','x','xh','xinghe','','县','150924','0474',5),(898,'凉城',155,'155,898','l','lc','liangcheng','','县','150925','0474',6),(899,'察哈尔右翼前',155,'155,899','c','cheyyq','chahaeryouyiqian','','旗','150926','0474',7),(900,'察哈尔右翼中',155,'155,900','c','cheyyz','chahaeryouyizhong','','旗','150927','0474',8),(901,'察哈尔右翼后',155,'155,901','c','cheyyh','chahaeryouyihou','','旗','150928','0474',9),(902,'四子王',155,'155,902','s','szw','siziwang','','旗','150929','0474',10),(903,'丰镇',155,'155,903','f','fz','fengzhen','','市','150981','0474',11),(904,'乌兰浩特',156,'156,904','w','wlht','wulanhaote','','市','152201','0482',1),(905,'阿尔山',156,'156,905','a','aes','aershan','','市','152202','0482',2),(906,'科尔沁右翼前',156,'156,906','k','keqyyq','keerqinyouyiqian','','旗','152221','0482',3),(907,'科尔沁右翼中',156,'156,907','k','keqyyz','keerqinyouyizhong','','旗','152222','0482',4),(908,'扎赉特',156,'156,908','z','zlt','zhalaite','','旗','152223','0482',5),(909,'突泉',156,'156,909','t','tq','tuquan','','县','152224','0482',6),(910,'二连浩特',157,'157,910','e','elht','erlianhaote','','市','152501','0479',1),(911,'锡林浩特',157,'157,911','x','xlht','xilinhaote','','市','152502','0479',2),(912,'阿巴嘎',157,'157,912','a','abg','abaga','','旗','152522','0479',3),(913,'苏尼特左',157,'157,913','s','sntz','sunitezuo','','旗','152523','0479',4),(914,'苏尼特右',157,'157,914','s','snty','suniteyou','','旗','152524','0479',5),(915,'东乌珠穆沁',157,'157,915','d','dwzmq','dongwuzhumuqin','','旗','152525','0479',6),(916,'西乌珠穆沁',157,'157,916','x','xwzmq','xiwuzhumuqin','','旗','152526','0479',7),(917,'太仆寺',157,'157,917','t','tps','taipusi','','旗','152527','0479',8),(918,'镶黄',157,'157,918','x','xh','xianghuang','','旗','152528','0479',9),(919,'正镶白',157,'157,919','z','zxb','zhengxiangbai','','旗','152529','0479',10),(920,'正蓝',157,'157,920','z','zl','zhenglan','','旗','152530','0479',11),(921,'多伦',157,'157,921','d','dl','duolun','','县','152531','0479',12),(922,'阿拉善左',158,'158,922','a','alsz','alashanzuo','','旗','152921','0483',1),(923,'阿拉善右',158,'158,923','a','alsy','alashanyou','','旗','152922','0483',2),(924,'额济纳',158,'158,924','e','ejn','ejina','','旗','152923','0483',3),(925,'和平',159,'159,925','h','hp','heping','','区','210102','024',1),(926,'沈河',159,'159,926','s','sh','shenhe','','区','210103','024',2),(927,'大东',159,'159,927','d','dd','dadong','','区','210104','024',3),(928,'皇姑',159,'159,928','h','hg','huanggu','','区','210105','024',4),(929,'铁西',159,'159,929','t','tx','tiexi','','区','210106','024',5),(930,'苏家屯',159,'159,930','s','sjt','sujiatun','','区','210111','024',6),(931,'东陵',159,'159,931','d','dl','dongling','','区','210112','024',7),(932,'沈北新区',159,'159,932','s','sbxq','shenbeixinqu','','','210113','024',8),(933,'于洪',159,'159,933','y','yh','yuhong','','区','210114','024',9),(934,'辽中',159,'159,934','l','lz','liaozhong','','县','210122','024',10),(935,'康平',159,'159,935','k','kp','kangping','','县','210123','024',11),(936,'法库',159,'159,936','f','fk','faku','','县','210124','024',12),(937,'新民',159,'159,937','x','xm','xinmin','','市','210181','024',13),(938,'中山',160,'160,938','z','zs','zhongshan','','区','210202','0411',1),(939,'西岗',160,'160,939','x','xg','xigang','','区','210203','0411',2),(940,'沙河口',160,'160,940','s','shk','shahekou','','区','210204','0411',3),(941,'甘井子',160,'160,941','g','gjz','ganjingzi','','区','210211','0411',4),(942,'旅顺口',160,'160,942','l','lsk','lu:shunkou','','区','210212','0411',5),(943,'金州',160,'160,943','j','jz','jinzhou','','区','210213','0411',6),(944,'长海',160,'160,944','z','zh','zhanghai','','县','210224','0411',7),(945,'瓦房店',160,'160,945','w','wfd','wafangdian','','市','210281','0411',8),(946,'普兰店',160,'160,946','p','pld','pulandian','','市','210282','0411',9),(947,'庄河',160,'160,947','z','zh','zhuanghe','','市','210283','0411',10),(948,'铁东',161,'161,948','t','td','tiedong','','区','210302','0412',1),(949,'铁西',161,'161,949','t','tx','tiexi','','区','210303','0412',2),(950,'立山',161,'161,950','l','ls','lishan','','区','210304','0412',3),(951,'千山',161,'161,951','q','qs','qianshan','','区','210311','0412',4),(952,'台安',161,'161,952','t','ta','taian','','县','210321','0412',5),(953,'岫岩',161,'161,953','x','xy','xiuyan','满族','自治县','210323','0412',6),(954,'海城',161,'161,954','h','hc','haicheng','','市','210381','0412',7),(955,'新抚',162,'162,955','x','xf','xinfu','','区','210402','0413',1),(956,'东洲',162,'162,956','d','dz','dongzhou','','区','210403','0413',2),(957,'望花',162,'162,957','w','wh','wanghua','','区','210404','0413',3),(958,'顺城',162,'162,958','s','sc','shuncheng','','区','210411','0413',4),(959,'抚顺',162,'162,959','f','fs','fushun','','县','210421','0413',5),(960,'新宾',162,'162,960','x','xb','xinbin','满族','自治县','210422','0413',6),(961,'清原',162,'162,961','q','qy','qingyuan','满族','自治县','210423','0413',7),(962,'平山',163,'163,962','p','ps','pingshan','','区','210502','0414',1),(963,'溪湖',163,'163,963','x','xh','xihu','','区','210503','0414',2),(964,'明山',163,'163,964','m','ms','mingshan','','区','210504','0414',3),(965,'南芬',163,'163,965','n','nf','nanfen','','区','210505','0414',4),(966,'本溪',163,'163,966','b','bx','benxi','满族','自治县','210521','0414',5),(967,'桓仁',163,'163,967','h','hr','huanren','满族','自治县','210522','0414',6),(968,'元宝',164,'164,968','y','yb','yuanbao','','区','210602','0415',1),(969,'振兴',164,'164,969','z','zx','zhenxing','','区','210603','0415',2),(970,'振安',164,'164,970','z','za','zhenan','','区','210604','0415',3),(971,'宽甸',164,'164,971','k','kd','kuandian','满族','自治县','210624','0415',4),(972,'东港',164,'164,972','d','dg','donggang','','市','210681','0415',5),(973,'凤城',164,'164,973','f','fc','fengcheng','','市','210682','0415',6),(974,'古塔',165,'165,974','g','gt','guta','','区','210702','0416',1),(975,'凌河',165,'165,975','l','lh','linghe','','区','210703','0416',2),(976,'太和',165,'165,976','t','th','taihe','','区','210711','0416',3),(977,'黑山',165,'165,977','h','hs','heishan','','县','210726','0416',4),(978,'义县',165,'165,978','y','yx','yixian','','','210727','0416',5),(979,'凌海',165,'165,979','l','lh','linghai','','市','210781','0416',6),(980,'北镇',165,'165,980','b','bz','beizhen','','市','210782','0416',7),(981,'站前',166,'166,981','z','zq','zhanqian','','区','210802','0417',1),(982,'西市',166,'166,982','x','xs','xishi','','区','210803','0417',2),(983,'鲅鱼圈',166,'166,983','b','byq','bayuquan','','区','210804','0417',3),(984,'老边',166,'166,984','l','lb','laobian','','区','210811','0417',4),(985,'盖州',166,'166,985','g','gz','gaizhou','','市','210881','0417',5),(986,'大石桥',166,'166,986','d','dsq','dashiqiao','','市','210882','0417',6),(987,'海州',167,'167,987','h','hz','haizhou','','区','210902','0418',1),(988,'新邱',167,'167,988','x','xq','xinqiu','','区','210903','0418',2),(989,'太平',167,'167,989','t','tp','taiping','','区','210904','0418',3),(990,'清河门',167,'167,990','q','qhm','qinghemen','','区','210905','0418',4),(991,'细河',167,'167,991','x','xh','xihe','','区','210911','0418',5),(992,'阜新',167,'167,992','f','fx','fuxin','蒙古族','自治县','210921','0418',6),(993,'彰武',167,'167,993','z','zw','zhangwu','','县','210922','0418',7),(994,'白塔',168,'168,994','b','bt','baita','','区','211002','0419',1),(995,'文圣',168,'168,995','w','ws','wensheng','','区','211003','0419',2),(996,'宏伟',168,'168,996','h','hw','hongwei','','区','211004','0419',3),(997,'弓长岭',168,'168,997','g','gzl','gongzhangling','','区','211005','0419',4),(998,'辽阳',168,'168,998','l','ly','liaoyang','','县','211021','0419',5),(999,'灯塔',168,'168,999','d','dt','dengta','','市','211081','0419',6),(1000,'太子河',168,'168,1000','t','tzh','taizihe','','区','211110','0419',7),(1001,'双台子',169,'169,1001','s','stz','shuangtaizi','','区','211103','0427',1),(1002,'兴隆台',169,'169,1002','x','xlt','xinglongtai','','区','211103','0427',2),(1003,'大洼',169,'169,1003','d','dw','dawa','','县','211121','0427',3),(1004,'盘山',169,'169,1004','p','ps','panshan','','县','211122','0427',4),(1005,'银州',170,'170,1005','y','yz','yinzhou','','区','211202','0410',1),(1006,'清河',170,'170,1006','q','qh','qinghe','','区','211204','0410',2),(1007,'铁岭',170,'170,1007','t','tl','tieling','','县','211221','0410',3),(1008,'西丰',170,'170,1008','x','xf','xifeng','','县','211223','0410',4),(1009,'昌图',170,'170,1009','c','ct','changtu','','县','211224','0410',5),(1010,'调兵山',170,'170,1010','d','dbs','diaobingshan','','市','211281','0410',6),(1011,'开原',170,'170,1011','k','ky','kaiyuan','','市','211282','0410',7),(1012,'双塔',171,'171,1012','s','st','shuangta','','区','211302','0421',1),(1013,'龙城',171,'171,1013','l','lc','longcheng','','区','211303','0421',2),(1014,'朝阳',171,'171,1014','c','cy','chaoyang','','县','211321','0421',3),(1015,'建平',171,'171,1015','j','jp','jianping','','县','211322','0421',4),(1016,'喀喇沁左翼',171,'171,1016','k','klqzy','kalaqinzuoyi','蒙古族','自治县','211324','0421',5),(1017,'北票',171,'171,1017','b','bp','beipiao','','市','211381','0421',6),(1018,'凌源',171,'171,1018','l','ly','lingyuan','','市','211382','0421',7),(1019,'连山',172,'172,1019','l','ls','lianshan','','区','211402','0429',1),(1020,'龙港',172,'172,1020','l','lg','longgang','','区','211403','0429',2),(1021,'南票',172,'172,1021','n','np','nanpiao','','区','211404','0429',3),(1022,'绥中',172,'172,1022','s','sz','suizhong','','县','211421','0429',4),(1023,'建昌',172,'172,1023','j','jc','jianchang','','县','211422','0429',5),(1024,'兴城',172,'172,1024','x','xc','xingcheng','','市','211481','0429',6),(1025,'南关',173,'173,1025','n','ng','nanguan','','区','220102','0431',1),(1026,'宽城',173,'173,1026','k','kc','kuancheng','','区','220103','0431',2),(1027,'朝阳',173,'173,1027','c','cy','chaoyang','','区','220104','0431',3),(1028,'二道',173,'173,1028','e','ed','erdao','','区','220105','0431',4),(1029,'绿园',173,'173,1029','l','ly','lu:yuan','','区','220106','0431',5),(1030,'双阳',173,'173,1030','s','sy','shuangyang','','区','220112','0431',6),(1031,'农安',173,'173,1031','n','na','nongan','','县','220122','0431',7),(1032,'九台',173,'173,1032','j','jt','jiutai','','市','220181','0431',8),(1033,'榆树',173,'173,1033','y','ys','yushu','','市','220182','0431',9),(1034,'德惠',173,'173,1034','d','dh','dehui','','市','220183','0431',10),(1035,'昌邑',174,'174,1035','c','cy','changyi','','区','220202','0432',1),(1036,'龙潭',174,'174,1036','l','lt','longtan','','区','220203','0432',2),(1037,'船营',174,'174,1037','c','cy','chuanying','','区','220204','0432',3),(1038,'丰满',174,'174,1038','f','fm','fengman','','区','220211','0432',4),(1039,'永吉',174,'174,1039','y','yj','yongji','','县','220221','0432',5),(1040,'桦甸',174,'174,1040','h','hd','huadian','','市','220282','0432',6),(1041,'蛟河',174,'174,1041','j','jh','jiaohe','','市','220282','0432',7),(1042,'舒兰',174,'174,1042','s','sl','shulan','','市','220283','0432',8),(1043,'磐石',174,'174,1043','p','ps','panshi','','市','220284','0432',9),(1044,'铁西',175,'175,1044','t','tx','tiexi','','区','220302','0434',1),(1045,'铁东',175,'175,1045','t','td','tiedong','','区','220303','0434',2),(1046,'梨树',175,'175,1046','l','ls','lishu','','县','220322','0434',3),(1047,'伊通',175,'175,1047','y','yt','yitong','满族','自治县','220323','0434',4),(1048,'公主岭',175,'175,1048','g','gzl','gongzhuling','','市','220381','0434',5),(1049,'双辽',175,'175,1049','s','sl','shuangliao','','市','220382','0434',6),(1050,'龙山',176,'176,1050','l','ls','longshan','','区','220402','0437',1),(1051,'西安',176,'176,1051','x','xa','xian','','区','220403','0437',2),(1052,'东丰',176,'176,1052','d','df','dongfeng','','县','220421','0437',3),(1053,'东辽',176,'176,1053','d','dl','dongliao','','县','220422','0437',4),(1054,'东昌',177,'177,1054','d','dc','dongchang','','区','220502','0435',1),(1055,'二道江',177,'177,1055','e','edj','erdaojiang','','区','220503','0435',2),(1056,'通化',177,'177,1056','t','th','tonghua','','县','220521','0435',3),(1057,'辉南',177,'177,1057','h','hn','huinan','','县','220523','0435',4),(1058,'柳河',177,'177,1058','l','lh','liuhe','','县','220524','0435',5),(1059,'梅河口',177,'177,1059','m','mhk','meihekou','','市','220581','0435',6),(1060,'集安',177,'177,1060','j','ja','jian','','市','220582','0435',7),(1061,'浑江',178,'178,1061','h','hj','hunjiang','','区','220602','0439',1),(1062,'江源',178,'178,1062','j','jy','jiangyuan','','区','220604','0439',2),(1063,'抚松',178,'178,1063','f','fs','fusong','','县','220621','0439',3),(1064,'靖宇',178,'178,1064','j','jy','jingyu','','县','220622','0439',4),(1065,'长白',178,'178,1065','z','zb','zhangbai','朝鲜族','自治县','220623','0439',5),(1066,'临江',178,'178,1066','l','lj','linjiang','','市','220681','0439',6),(1067,'宁江',179,'179,1067','n','nj','ningjiang','','区','220702','0438',1),(1068,'前郭尔罗斯',179,'179,1068','q','qgels','qianguoerluosi','蒙古族','自治县','220721','0438',2),(1069,'长岭',179,'179,1069','z','zl','zhangling','','县','220722','0438',3),(1070,'乾安',179,'179,1070','q','qa','qianan','','县','220723','0438',4),(1071,'扶余',179,'179,1071','f','fy','fuyu','','市','220724','0438',5),(1072,'洮北',180,'180,1072','t','tb','taobei','','区','220802','0436',1),(1073,'镇赉',180,'180,1073','z','zl','zhenlai','','县','220821','0436',2),(1074,'洮南',180,'180,1074','t','tn','taonan','','市','220881','0436',3),(1075,'大安',180,'180,1075','d','da','daan','','市','220882','0436',4),(1076,'通榆',180,'180,1076','t','ty','tongyu','','县','220882','0436',5),(1077,'延吉',181,'181,1077','y','yj','yanji','','市','222401','0433',1),(1078,'图们',181,'181,1078','t','tm','tumen','','市','222402','0433',2),(1079,'敦化',181,'181,1079','d','dh','dunhua','','市','222403','0433',3),(1080,'珲春',181,'181,1080','h','hc','hunchun','','市','222404','0433',4),(1081,'龙井',181,'181,1081','l','lj','longjing','','市','222405','0433',5),(1082,'和龙',181,'181,1082','h','hl','helong','','市','222406','0433',6),(1083,'汪清',181,'181,1083','w','wq','wangqing','','县','222424','0433',7),(1084,'安图',181,'181,1084','a','at','antu','','县','222426','0433',8),(1085,'道里',182,'182,1085','d','dl','daoli','','区','230102','0451',1),(1086,'南岗',182,'182,1086','n','ng','nangang','','区','230103','0451',2),(1087,'道外',182,'182,1087','d','dw','daowai','','区','230104','0451',3),(1088,'平房',182,'182,1088','p','pf','pingfang','','区','230108','0451',4),(1089,'松北',182,'182,1089','s','sb','songbei','','区','230109','0451',5),(1090,'香坊',182,'182,1090','x','xf','xiangfang','','区','230110','0451',6),(1091,'呼兰',182,'182,1091','h','hl','hulan','','区','230111','0451',7),(1092,'阿城',182,'182,1092','a','ac','acheng','','区','230112','0451',8),(1093,'依兰',182,'182,1093','y','yl','yilan','','县','230123','0451',9),(1094,'方正',182,'182,1094','f','fz','fangzheng','','县','230124','0451',10),(1095,'宾县',182,'182,1095','b','bx','binxian','','','230125','0451',11),(1096,'巴彦',182,'182,1096','b','by','bayan','','县','230126','0451',12),(1097,'木兰',182,'182,1097','m','ml','mulan','','县','230127','0451',13),(1098,'通河',182,'182,1098','t','th','tonghe','','县','230128','0451',14),(1099,'延寿',182,'182,1099','y','ys','yanshou','','县','230129','0451',15),(1100,'双城',182,'182,1100','s','sc','shuangcheng','','市','230182','0451',16),(1101,'尚志',182,'182,1101','s','sz','shangzhi','','市','230183','0451',17),(1102,'五常',182,'182,1102','w','wc','wuchang','','市','230184','0451',18),(1103,'龙沙',183,'183,1103','l','ls','longsha','','区','230202','0452',1),(1104,'建华',183,'183,1104','j','jh','jianhua','','区','230203','0452',2),(1105,'铁锋',183,'183,1105','t','tf','tiefeng','','区','230204','0452',3),(1106,'昂昂溪',183,'183,1106','a','aax','angangxi','','区','230205','0452',4),(1107,'富拉尔基',183,'183,1107','f','flej','fulaerji','','区','230206','0452',5),(1108,'碾子山',183,'183,1108','n','nzs','nianzishan','','区','230207','0452',6),(1109,'梅里斯',183,'183,1109','m','mls','meilisi','达斡尔族','区','230208','0452',7),(1110,'龙江',183,'183,1110','l','lj','longjiang','','县','230221','0452',8),(1111,'依安',183,'183,1111','y','ya','yian','','县','230223','0452',9),(1112,'泰来',183,'183,1112','t','tl','tailai','','县','230224','0452',10),(1113,'甘南',183,'183,1113','g','gn','gannan','','县','230225','0452',11),(1114,'富裕',183,'183,1114','f','fy','fuyu','','县','230227','0452',12),(1115,'克山',183,'183,1115','k','ks','keshan','','县','230229','0452',13),(1116,'克东',183,'183,1116','k','kd','kedong','','县','230230','0452',14),(1117,'拜泉',183,'183,1117','b','bq','baiquan','','县','230231','0452',15),(1118,'讷河',183,'183,1118','n','nh','nehe','','市','230281','0452',16),(1119,'鸡冠',184,'184,1119','j','jg','jiguan','','区','230302','0467',1),(1120,'恒山',184,'184,1120','h','hs','hengshan','','区','230303','0467',2),(1121,'滴道',184,'184,1121','d','dd','didao','','区','230304','0467',3),(1122,'梨树',184,'184,1122','l','ls','lishu','','区','230305','0467',4),(1123,'城子河',184,'184,1123','c','czh','chengzihe','','区','230306','0467',5),(1124,'麻山',184,'184,1124','m','ms','mashan','','区','230307','0467',6),(1125,'鸡东',184,'184,1125','j','jd','jidong','','县','230321','0467',7),(1126,'虎林',184,'184,1126','h','hl','hulin','','市','230381','0467',8),(1127,'密山',184,'184,1127','m','ms','mishan','','市','230382','0467',9),(1128,'向阳',185,'185,1128','x','xy','xiangyang','','区','230402','0468',1),(1129,'工农',185,'185,1129','g','gn','gongnong','','区','230403','0468',2),(1130,'南山',185,'185,1130','n','ns','nanshan','','区','230404','0468',3),(1131,'兴安',185,'185,1131','x','xa','xingan','','区','230405','0468',4),(1132,'东山',185,'185,1132','d','ds','dongshan','','区','230406','0468',5),(1133,'兴山',185,'185,1133','x','xs','xingshan','','区','230407','0468',6),(1134,'萝北',185,'185,1134','l','lb','luobei','','县','230421','0468',7),(1135,'绥滨',185,'185,1135','s','sb','suibin','','县','230422','0468',8),(1136,'尖山',186,'186,1136','j','js','jianshan','','区','230502','0469',1),(1137,'岭东',186,'186,1137','l','ld','lingdong','','区','230503','0469',2),(1138,'四方台',186,'186,1138','s','sft','sifangtai','','区','230505','0469',3),(1139,'宝山',186,'186,1139','b','bs','baoshan','','区','230506','0469',4),(1140,'集贤',186,'186,1140','j','jx','jixian','','县','230521','0469',5),(1141,'友谊',186,'186,1141','y','yy','youyi','','县','230522','0469',6),(1142,'宝清',186,'186,1142','b','bq','baoqing','','县','230523','0469',7),(1143,'饶河',186,'186,1143','r','rh','raohe','','县','230524','0469',8),(1144,'萨尔图',187,'187,1144','s','set','saertu','','区','230602','0459',1),(1145,'龙凤',187,'187,1145','l','lf','longfeng','','区','230603','0459',2),(1146,'让胡路',187,'187,1146','r','rhl','ranghulu','','区','230604','0459',3),(1147,'红岗',187,'187,1147','h','hg','honggang','','区','230605','0459',4),(1148,'大同',187,'187,1148','d','dt','datong','','区','230606','0459',5),(1149,'肇州',187,'187,1149','z','zz','zhaozhou','','县','230621','0459',6),(1150,'肇源',187,'187,1150','z','zy','zhaoyuan','','县','230622','0459',7),(1151,'林甸',187,'187,1151','l','ld','lindian','','县','230623','0459',8),(1152,'杜尔伯特',187,'187,1152','d','debt','duerbote','蒙古族','自治县','230624','0459',9),(1153,'伊春',188,'188,1153','y','yc','yichun','','区','230702','0458',1),(1154,'南岔',188,'188,1154','n','nc','nancha','','区','230703','0458',2),(1155,'友好',188,'188,1155','y','yh','youhao','','区','230704','0458',3),(1156,'西林',188,'188,1156','x','xl','xilin','','区','230705','0458',4),(1157,'翠峦',188,'188,1157','c','cl','cuiluan','','区','230706','0458',5),(1158,'新青',188,'188,1158','x','xq','xinqing','','区','230707','0458',6),(1159,'美溪',188,'188,1159','m','mx','meixi','','区','230708','0458',7),(1160,'金山屯',188,'188,1160','j','jst','jinshantun','','区','230709','0458',8),(1161,'五营',188,'188,1161','w','wy','wuying','','区','230710','0458',9),(1162,'乌马河',188,'188,1162','w','wmh','wumahe','','区','230711','0458',10),(1163,'汤旺河',188,'188,1163','t','twh','tangwanghe','','区','230712','0458',11),(1164,'带岭',188,'188,1164','d','dl','dailing','','区','230713','0458',12),(1165,'乌伊岭',188,'188,1165','w','wyl','wuyiling','','区','230714','0458',13),(1166,'红星',188,'188,1166','h','hx','hongxing','','区','230715','0458',14),(1167,'上甘岭',188,'188,1167','s','sgl','shangganling','','区','230716','0458',15),(1168,'嘉荫',188,'188,1168','j','jy','jiayin','','县','230722','0458',16),(1169,'铁力',188,'188,1169','t','tl','tieli','','市','230781','0458',17),(1170,'向阳',189,'189,1170','x','xy','xiangyang','','区','230803','0454',1),(1171,'前进',189,'189,1171','q','qj','qianjin','','区','230804','0454',2),(1172,'东风',189,'189,1172','d','df','dongfeng','','区','230805','0454',3),(1173,'郊区',189,'189,1173','j','jq','jiaoqu','','','230811','0454',4),(1174,'桦南',189,'189,1174','h','hn','huanan','','县','230822','0454',5),(1175,'桦川',189,'189,1175','h','hc','huachuan','','县','230826','0454',6),(1176,'汤原',189,'189,1176','t','ty','tangyuan','','县','230828','0454',7),(1177,'抚远',189,'189,1177','f','fy','fuyuan','','县','230833','0454',8),(1178,'同江',189,'189,1178','t','tj','tongjiang','','市','230881','0454',9),(1179,'富锦',189,'189,1179','f','fj','fujin','','市','230882','0454',10),(1180,'新兴',190,'190,1180','x','xx','xinxing','','区','230902','0464',1),(1181,'桃山',190,'190,1181','t','ts','taoshan','','区','230903','0464',2),(1182,'茄子河',190,'190,1182','q','qzh','qiezihe','','区','230904','0464',3),(1183,'勃利',190,'190,1183','b','bl','boli','','县','230921','0464',4),(1184,'东安',191,'191,1184','d','da','dongan','','区','231002','0453',1),(1185,'阳明',191,'191,1185','y','ym','yangming','','区','231003','0453',2),(1186,'爱民',191,'191,1186','a','am','aimin','','区','231004','0453',3),(1187,'西安',191,'191,1187','x','xa','xian','','区','231005','0453',4),(1188,'东宁',191,'191,1188','d','dn','dongning','','县','231024','0453',5),(1189,'林口',191,'191,1189','l','lk','linkou','','县','231025','0453',6),(1190,'绥芬河',191,'191,1190','s','sfh','suifenhe','','市','231081','0453',7),(1191,'海林',191,'191,1191','h','hl','hailin','','市','231083','0453',8),(1192,'宁安',191,'191,1192','n','na','ningan','','市','231084','0453',9),(1193,'穆棱',191,'191,1193','m','ml','muleng','','市','231085','0453',10),(1194,'爱辉',192,'192,1194','a','ah','aihui','','区','231102','0456',1),(1195,'嫩江',192,'192,1195','n','nj','nenjiang','','县','231121','0456',2),(1196,'逊克',192,'192,1196','x','xk','xunke','','县','231123','0456',3),(1197,'孙吴',192,'192,1197','s','sw','sunwu','','县','231124','0456',4),(1198,'北安',192,'192,1198','b','ba','beian','','市','231181','0456',5),(1199,'五大连池',192,'192,1199','w','wdlc','wudalianchi','','市','231182','0456',6),(1200,'北林',193,'193,1200','b','bl','beilin','','区','231202','0455',1),(1201,'望奎',193,'193,1201','w','wk','wangkui','','县','231221','0455',2),(1202,'兰西',193,'193,1202','l','lx','lanxi','','县','231222','0455',3),(1203,'青冈',193,'193,1203','q','qg','qinggang','','县','231223','0455',4),(1204,'庆安',193,'193,1204','q','qa','qingan','','县','231224','0455',5),(1205,'明水',193,'193,1205','m','ms','mingshui','','县','231225','0455',6),(1206,'绥棱',193,'193,1206','s','sl','suileng','','县','231226','0455',7),(1207,'安达',193,'193,1207','a','ad','anda','','市','231281','0455',8),(1208,'肇东',193,'193,1208','z','zd','zhaodong','','市','231282','0455',9),(1209,'海伦',193,'193,1209','h','hl','hailun','','市','231283','0455',10),(1210,'加格达奇',194,'194,1210','j','jgdq','jiagedaqi','','区','232701','0457',1),(1211,'松岭',194,'194,1211','s','sl','songling','','区','232702','0457',2),(1212,'新林',194,'194,1212','x','xl','xinlin','','区','232703','0457',3),(1213,'呼中',194,'194,1213','h','hz','huzhong','','区','232704','0457',4),(1214,'呼玛',194,'194,1214','h','hm','huma','','县','232721','0457',5),(1215,'塔河',194,'194,1215','t','th','tahe','','县','232722','0457',6),(1216,'漠河',194,'194,1216','m','mh','mohe','','县','232723','0457',7),(1217,'玄武',195,'195,1217','x','xw','xuanwu','','区','320102','',1),(1218,'秦淮',195,'195,1218','q','qh','qinhuai','','区','320104','',2),(1219,'建邺',195,'195,1219','j','jy','jianye','','区','320105','',3),(1220,'鼓楼',195,'195,1220','g','gl','gulou','','区','320106','',4),(1221,'浦口',195,'195,1221','p','pk','pukou','','区','320111','',5),(1222,'栖霞',195,'195,1222','q','qx','qixia','','区','320113','',6),(1223,'雨花台',195,'195,1223','y','yht','yuhuatai','','区','320114','',7),(1224,'江宁',195,'195,1224','j','jn','jiangning','','区','320115','',8),(1225,'六合',195,'195,1225','l','lh','liuhe','','区','320116','',9),(1226,'溧水',195,'195,1226','l','ls','lishui','','区','320124','',10),(1227,'高淳',195,'195,1227','g','gc','gaochun','','区','320125','',11),(1228,'崇安',196,'196,1228','c','ca','chongan','','区','320202','0510',1),(1229,'南长',196,'196,1229','n','nz','nanzhang','','区','320203','0510',2),(1230,'北塘',196,'196,1230','b','bt','beitang','','区','320204','0510',3),(1231,'锡山',196,'196,1231','x','xs','xishan','','区','320205','0510',4),(1232,'惠山',196,'196,1232','h','hs','huishan','','区','320206','0510',5),(1233,'滨湖',196,'196,1233','b','bh','binhu','','区','320211','0510',6),(1234,'江阴',196,'196,1234','j','jy','jiangyin','','市','320281','0510',7),(1235,'宜兴',196,'196,1235','y','yx','yixing','','市','320282','0510',8),(1236,'鼓楼',197,'197,1236','g','gl','gulou','','区','320302','0516',1),(1237,'云龙',197,'197,1237','y','yl','yunlong','','区','320303','0516',2),(1238,'贾汪',197,'197,1238','j','jw','jiawang','','区','320305','0516',3),(1239,'泉山',197,'197,1239','q','qs','quanshan','','区','320311','0516',4),(1240,'铜山',197,'197,1240','t','ts','tongshan','','区','320312','0516',5),(1241,'丰县',197,'197,1241','f','fx','fengxian','','','320321','0516',6),(1242,'沛县',197,'197,1242','p','px','peixian','','','320322','0516',7),(1243,'睢宁',197,'197,1243','s','sn','suining','','县','320324','0516',8),(1244,'新沂',197,'197,1244','x','xy','xinyi','','市','320381','0516',9),(1245,'邳州',197,'197,1245','p','pz','pizhou','','市','320382','0516',10),(1246,'天宁',198,'198,1246','t','tn','tianning','','区','320402','0519',1),(1247,'钟楼',198,'198,1247','z','zl','zhonglou','','区','320404','0519',2),(1248,'戚墅堰',198,'198,1248','q','qsy','qishuyan','','区','320405','0519',3),(1249,'新北',198,'198,1249','x','xb','xinbei','','区','320411','0519',4),(1250,'武进',198,'198,1250','w','wj','wujin','','区','320412','0519',5),(1251,'溧阳',198,'198,1251','l','ly','liyang','','市','320481','0519',6),(1252,'金坛',198,'198,1252','j','jt','jintan','','市','320482','0519',7),(1253,'虎丘',199,'199,1253','h','hq','huqiu','','区','320505','0512',1),(1254,'吴中',199,'199,1254','w','wz','wuzhong','','区','320506','0512',2),(1255,'相城',199,'199,1255','x','xc','xiangcheng','','区','320507','0512',3),(1256,'姑苏',199,'199,1256','g','gs','gusu','','区','320508','0512',4),(1257,'吴江',199,'199,1257','w','wj','wujiang','','市','320509','0512',5),(1258,'常熟',199,'199,1258','c','cs','changshu','','市','320581','0512',6),(1259,'张家港',199,'199,1259','z','zjg','zhangjiagang','','市','320582','0512',7),(1260,'昆山',199,'199,1260','k','ks','kunshan','','市','320583','0512',8),(1261,'太仓',199,'199,1261','t','tc','taicang','','市','320585','0512',9),(1262,'崇川',200,'200,1262','c','cc','chongchuan','','区','320602','0513',1),(1263,'港闸',200,'200,1263','g','gz','gangzha','','区','320611','0513',2),(1264,'通州',200,'200,1264','t','tz','tongzhou','','区','320612','0513',3),(1265,'海安',200,'200,1265','h','ha','haian','','县','320621','0513',4),(1266,'如东',200,'200,1266','r','rd','rudong','','县','320623','0513',5),(1267,'启东',200,'200,1267','q','qd','qidong','','市','320681','0513',6),(1268,'如皋',200,'200,1268','r','rg','rugao','','市','320682','0513',7),(1269,'海门',200,'200,1269','h','hm','haimen','','市','320684','0513',8),(1270,'连云',201,'201,1270','l','ly','lianyun','','区','320703','0518',1),(1271,'新浦',201,'201,1271','x','xp','xinpu','','区','320705','0518',2),(1272,'海州',201,'201,1272','h','hz','haizhou','','区','320706','0518',3),(1273,'赣榆',201,'201,1273','g','gy','ganyu','','县','320721','0518',4),(1274,'东海',201,'201,1274','d','dh','donghai','','县','320722','0518',5),(1275,'灌云',201,'201,1275','g','gy','guanyun','','县','320723','0518',6),(1276,'灌南',201,'201,1276','g','gn','guannan','','县','320724','0518',7),(1277,'清河',202,'202,1277','q','qh','qinghe','','区','320802','0517',1),(1278,'淮安',202,'202,1278','h','ha','huaian','','区','320803','0517',2),(1279,'淮阴',202,'202,1279','h','hy','huaiyin','','区','320804','0517',3),(1280,'清浦',202,'202,1280','q','qp','qingpu','','区','320811','0517',4),(1281,'涟水',202,'202,1281','l','ls','lianshui','','县','320826','0517',5),(1282,'洪泽',202,'202,1282','h','hz','hongze','','县','320829','0517',6),(1283,'盱眙',202,'202,1283','x','xy','xuyi','','县','320830','0517',7),(1284,'金湖',202,'202,1284','j','jh','jinhu','','县','320831','0517',8),(1285,'亭湖',203,'203,1285','t','th','tinghu','','区','320902','0515',1),(1286,'盐都',203,'203,1286','y','yd','yandou','','区','320903','0515',2),(1287,'响水',203,'203,1287','x','xs','xiangshui','','县','320921','0515',3),(1288,'滨海',203,'203,1288','b','bh','binhai','','县','320922','0515',4),(1289,'阜宁',203,'203,1289','f','fn','funing','','县','320923','0515',5),(1290,'射阳',203,'203,1290','s','sy','sheyang','','县','320924','0515',6),(1291,'建湖',203,'203,1291','j','jh','jianhu','','县','320925','0515',7),(1292,'东台',203,'203,1292','d','dt','dongtai','','市','320981','0515',8),(1293,'大丰',203,'203,1293','d','df','dafeng','','市','320982','0515',9),(1294,'广陵',204,'204,1294','g','gl','guangling','','区','321002','0514',1),(1295,'邗江',204,'204,1295','h','hj','hanjiang','','区','321003','0514',2),(1296,'江都',204,'204,1296','j','jd','jiangdou','','区','321012','0514',3),(1297,'宝应',204,'204,1297','b','by','baoying','','县','321023','0514',4),(1298,'仪征',204,'204,1298','y','yz','yizheng','','市','321081','0514',5),(1299,'高邮',204,'204,1299','g','gy','gaoyou','','市','321084','0514',6),(1300,'京口',205,'205,1300','j','jk','jingkou','','区','321102','0511',1),(1301,'润州',205,'205,1301','r','rz','runzhou','','区','321111','0511',2),(1302,'丹徒',205,'205,1302','d','dt','dantu','','区','321112','0511',3),(1303,'丹阳',205,'205,1303','d','dy','danyang','','市','321181','0511',4),(1304,'扬中',205,'205,1304','y','yz','yangzhong','','市','321182','0511',5),(1305,'句容',205,'205,1305','j','jr','jurong','','市','321183','0511',6),(1306,'海陵',206,'206,1306','h','hl','hailing','','区','321202','0523',1),(1307,'高港',206,'206,1307','g','gg','gaogang','','区','321203','0523',2),(1308,'兴化',206,'206,1308','x','xh','xinghua','','市','321281','0523',3),(1309,'靖江',206,'206,1309','j','jj','jingjiang','','市','321282','0523',4),(1310,'泰兴',206,'206,1310','t','tx','taixing','','市','321283','0523',5),(1311,'姜堰',206,'206,1311','j','jy','jiangyan','','区','321284','0523',6),(1312,'宿城',207,'207,1312','s','sc','sucheng','','区','321302','0527',1),(1313,'宿豫',207,'207,1313','s','sy','suyu','','区','321311','0527',2),(1314,'沭阳',207,'207,1314','s','sy','shuyang','','县','321322','0527',3),(1315,'泗阳',207,'207,1315','s','sy','siyang','','县','321323','0527',4),(1316,'泗洪',207,'207,1316','s','sh','sihong','','县','321324','0527',5),(1317,'上城',208,'208,1317','s','sc','shangcheng','','区','330102','0571',1),(1318,'下城',208,'208,1318','x','xc','xiacheng','','区','330103','0571',2),(1319,'江干',208,'208,1319','j','jg','jianggan','','区','330104','0571',3),(1320,'拱墅',208,'208,1320','g','gs','gongshu','','区','330105','0571',4),(1321,'西湖',208,'208,1321','x','xh','xihu','','区','330106','0571',5),(1322,'滨江',208,'208,1322','b','bj','binjiang','','区','330108','0571',6),(1323,'萧山',208,'208,1323','x','xs','xiaoshan','','区','330109','0571',7),(1324,'余杭',208,'208,1324','y','yh','yuhang','','区','330110','0571',8),(1325,'桐庐',208,'208,1325','t','tl','tonglu','','县','330122','0571',9),(1326,'淳安',208,'208,1326','c','ca','chunan','','县','330127','0571',10),(1327,'建德',208,'208,1327','j','jd','jiande','','市','330182','0571',11),(1328,'富阳',208,'208,1328','f','fy','fuyang','','市','330183','0571',12),(1329,'临安',208,'208,1329','l','la','linan','','市','330185','0571',13),(1330,'海曙',209,'209,1330','h','hs','haishu','','区','330203','0574',1),(1331,'江东',209,'209,1331','j','jd','jiangdong','','区','330204','0574',2),(1332,'江北',209,'209,1332','j','jb','jiangbei','','区','330205','0574',3),(1333,'北仑',209,'209,1333','b','bl','beilun','','区','330206','0574',4),(1334,'镇海',209,'209,1334','z','zh','zhenhai','','区','330211','0574',5),(1335,'鄞州',209,'209,1335','y','yz','yinzhou','','区','330212','0574',6),(1336,'象山',209,'209,1336','x','xs','xiangshan','','县','330225','0574',7),(1337,'宁海',209,'209,1337','n','nh','ninghai','','县','330226','0574',8),(1338,'余姚',209,'209,1338','y','yy','yuyao','','市','330281','0574',9),(1339,'慈溪',209,'209,1339','c','cx','cixi','','市','330282','0574',10),(1340,'奉化',209,'209,1340','f','fh','fenghua','','市','330283','0574',11),(1341,'鹿城',210,'210,1341','l','lc','lucheng','','区','330302','0577',1),(1342,'龙湾',210,'210,1342','l','lw','longwan','','区','330303','0577',2),(1343,'瓯海',210,'210,1343','o','oh','ouhai','','区','330304','0577',3),(1344,'洞头',210,'210,1344','d','dt','dongtou','','县','330322','0577',4),(1345,'永嘉',210,'210,1345','y','yj','yongjia','','县','330324','0577',5),(1346,'平阳',210,'210,1346','p','py','pingyang','','县','330326','0577',6),(1347,'苍南',210,'210,1347','c','cn','cangnan','','县','330327','0577',7),(1348,'文成',210,'210,1348','w','wc','wencheng','','县','330328','0577',8),(1349,'泰顺',210,'210,1349','t','ts','taishun','','县','330329','0577',9),(1350,'瑞安',210,'210,1350','r','ra','ruian','','市','330381','0577',10),(1351,'乐清',210,'210,1351','l','lq','leqing','','市','330382','0577',11),(1352,'南湖',211,'211,1352','n','nh','nanhu','','区','330402','0573',1),(1353,'秀洲',211,'211,1353','x','xz','xiuzhou','','区','330411','0573',2),(1354,'嘉善',211,'211,1354','j','js','jiashan','','县','330421','0573',3),(1355,'海盐',211,'211,1355','h','hy','haiyan','','县','330424','0573',4),(1356,'海宁',211,'211,1356','h','hn','haining','','市','330481','0573',5),(1357,'平湖',211,'211,1357','p','ph','pinghu','','市','330482','0573',6),(1358,'桐乡',211,'211,1358','t','tx','tongxiang','','市','330483','0573',7),(1359,'吴兴',212,'212,1359','w','wx','wuxing','','区','330502','0572',1),(1360,'南浔',212,'212,1360','n','nx','nanxun','','区','330503','0572',2),(1361,'德清',212,'212,1361','d','dq','deqing','','县','330521','0572',3),(1362,'长兴',212,'212,1362','z','zx','zhangxing','','县','330522','0572',4),(1363,'安吉',212,'212,1363','a','aj','anji','','县','330523','0572',5),(1364,'越城',213,'213,1364','y','yc','yuecheng','','区','330602','0575',1),(1365,'绍兴',213,'213,1365','s','sx','shaoxing','','县','330621','0575',2),(1366,'新昌',213,'213,1366','x','xc','xinchang','','县','330624','0575',3),(1367,'诸暨',213,'213,1367','z','zj','zhuji','','市','330681','0575',4),(1368,'上虞',213,'213,1368','s','sy','shangyu','','市','330682','0575',5),(1369,'嵊州',213,'213,1369','s','sz','shengzhou','','市','330683','0575',6),(1370,'婺城',214,'214,1370','w','wc','wucheng','','区','330702','0579',1),(1371,'金东',214,'214,1371','j','jd','jindong','','区','330703','0579',2),(1372,'武义',214,'214,1372','w','wy','wuyi','','县','330723','0579',3),(1373,'浦江',214,'214,1373','p','pj','pujiang','','县','330726','0579',4),(1374,'磐安',214,'214,1374','p','pa','panan','','县','330727','0579',5),(1375,'兰溪',214,'214,1375','l','lx','lanxi','','市','330781','0579',6),(1376,'义乌',214,'214,1376','y','yw','yiwu','','市','330782','0579',7),(1377,'东阳',214,'214,1377','d','dy','dongyang','','市','330783','0579',8),(1378,'永康',214,'214,1378','y','yk','yongkang','','市','330784','0579',9),(1379,'柯城',215,'215,1379','k','kc','kecheng','','区','330802','0570',1),(1380,'衢江',215,'215,1380','q','qj','qujiang','','区','330803','0570',2),(1381,'常山',215,'215,1381','c','cs','changshan','','县','330822','0570',3),(1382,'开化',215,'215,1382','k','kh','kaihua','','县','330824','0570',4),(1383,'龙游',215,'215,1383','l','ly','longyou','','县','330825','0570',5),(1384,'江山',215,'215,1384','j','js','jiangshan','','市','330881','0570',6),(1385,'定海',216,'216,1385','d','dh','dinghai','','区','330902','0580',1),(1386,'普陀',216,'216,1386','p','pt','putuo','','区','330903','0580',2),(1387,'岱山',216,'216,1387','d','ds','daishan','','县','330921','0580',3),(1388,'嵊泗',216,'216,1388','s','ss','shengsi','','县','330922','0580',4),(1389,'椒江',217,'217,1389','j','jj','jiaojiang','','区','331002','0576',1),(1390,'黄岩',217,'217,1390','h','hy','huangyan','','区','331003','0576',2),(1391,'路桥',217,'217,1391','l','lq','luqiao','','区','331004','0576',3),(1392,'玉环',217,'217,1392','y','yh','yuhuan','','县','331021','0576',4),(1393,'三门',217,'217,1393','s','sm','sanmen','','县','331022','0576',5),(1394,'天台',217,'217,1394','t','tt','tiantai','','县','331023','0576',6),(1395,'仙居',217,'217,1395','x','xj','xianju','','县','331024','0576',7),(1396,'温岭',217,'217,1396','w','wl','wenling','','市','331081','0576',8),(1397,'临海',217,'217,1397','l','lh','linhai','','市','331082','0576',9),(1398,'莲都',218,'218,1398','l','ld','liandou','','区','331102','0578',1),(1399,'青田',218,'218,1399','q','qt','qingtian','','县','331121','0578',2),(1400,'缙云',218,'218,1400','j','jy','jinyun','','县','331122','0578',3),(1401,'遂昌',218,'218,1401','s','sc','suichang','','县','331123','0578',4),(1402,'松阳',218,'218,1402','s','sy','songyang','','县','331124','0578',5),(1403,'云和',218,'218,1403','y','yh','yunhe','','县','331125','0578',6),(1404,'庆元',218,'218,1404','q','qy','qingyuan','','县','331126','0578',7),(1405,'景宁',218,'218,1405','j','jn','jingning','畲族','自治县','331127','0578',8),(1406,'龙泉',218,'218,1406','l','lq','longquan','','市','331181','0578',9),(1407,'瑶海',219,'219,1407','y','yh','yaohai','','区','340102','0551',1),(1408,'庐阳',219,'219,1408','l','ly','luyang','','区','340103','0551',2),(1409,'蜀山',219,'219,1409','s','ss','shushan','','区','340104','0551',3),(1410,'包河',219,'219,1410','b','bh','baohe','','区','340111','0551',4),(1411,'长丰',219,'219,1411','z','zf','zhangfeng','','县','340121','0551',5),(1412,'肥东',219,'219,1412','f','fd','feidong','','县','340122','0551',6),(1413,'肥西',219,'219,1413','f','fx','feixi','','县','340123','0551',7),(1414,'庐江',219,'219,1414','l','lj','lujiang','','县','340124','0551',8),(1415,'巢湖',219,'219,1415','c','ch','chaohu','','市','340181','0551',9),(1416,'镜湖',220,'220,1416','j','jh','jinghu','','区','340202','0553',1),(1417,'弋江',220,'220,1417','y','yj','yijiang','','区','340203','0553',2),(1418,'鸠江',220,'220,1418','j','jj','jiujiang','','区','340207','0553',3),(1419,'三山',220,'220,1419','s','ss','sanshan','','区','340208','0553',4),(1420,'芜湖',220,'220,1420','w','wh','wuhu','','县','340221','0553',5),(1421,'繁昌',220,'220,1421','f','fc','fanchang','','县','340222','0553',6),(1422,'南陵',220,'220,1422','n','nl','nanling','','县','340223','0553',7),(1423,'无为',220,'220,1423','w','ww','wuwei','','县','340225','0553',8),(1424,'龙子湖',221,'221,1424','l','lzh','longzihu','','区','340302','0552',1),(1425,'蚌山',221,'221,1425','b','bs','bangshan','','区','340303','0552',2),(1426,'禹会',221,'221,1426','y','yh','yuhui','','区','340304','0552',3),(1427,'淮上',221,'221,1427','h','hs','huaishang','','区','340311','0552',4),(1428,'怀远',221,'221,1428','h','hy','huaiyuan','','县','340321','0552',5),(1429,'五河',221,'221,1429','w','wh','wuhe','','县','340322','0552',6),(1430,'固镇',221,'221,1430','g','gz','guzhen','','县','340323','0552',7),(1431,'大通',222,'222,1431','d','dt','datong','','区','340402','0554',1),(1432,'田家庵',222,'222,1432','t','tja','tianjiaan','','区','340403','0554',2),(1433,'谢家集',222,'222,1433','x','xjj','xiejiaji','','区','340404','0554',3),(1434,'八公山',222,'222,1434','b','bgs','bagongshan','','区','340405','0554',4),(1435,'潘集',222,'222,1435','p','pj','panji','','区','340406','0554',5),(1436,'凤台',222,'222,1436','f','ft','fengtai','','县','340421','0554',6),(1437,'花山',223,'223,1437','h','hs','huashan','','区','340503','0555',1),(1438,'雨山',223,'223,1438','y','ys','yushan','','区','340504','0555',2),(1439,'博望',223,'223,1439','b','bw','bowang','','区','340506','0555',3),(1440,'当涂',223,'223,1440','d','dt','dangtu','','县','340521','0555',4),(1441,'含山',223,'223,1441','h','hs','hanshan','','县','340522','0555',5),(1442,'和县',223,'223,1442','h','hx','hexian','','','340523','0555',6),(1443,'杜集',224,'224,1443','d','dj','duji','','区','340602','0561',1),(1444,'相山',224,'224,1444','x','xs','xiangshan','','区','340603','0561',2),(1445,'烈山',224,'224,1445','l','ls','lieshan','','区','340604','0561',3),(1446,'濉溪',224,'224,1446','s','sx','suixi','','县','340621','0561',4),(1447,'铜官山',225,'225,1447','t','tgs','tongguanshan','','区','340702','0562',1),(1448,'狮子山',225,'225,1448','s','szs','shizishan','','区','340703','0562',2),(1449,'郊区',225,'225,1449','j','jq','jiaoqu','','','340711','0562',3),(1450,'铜陵',225,'225,1450','t','tl','tongling','','县','340721','0562',4),(1451,'迎江',226,'226,1451','y','yj','yingjiang','','区','340802','0556',1),(1452,'大观',226,'226,1452','d','dg','daguan','','区','340803','0556',2),(1453,'宜秀',226,'226,1453','y','yx','yixiu','','区','340811','0556',3),(1454,'怀宁',226,'226,1454','h','hn','huaining','','县','340822','0556',4),(1455,'枞阳',226,'226,1455','z','zy','zongyang','','县','340823','0556',5),(1456,'潜山',226,'226,1456','q','qs','qianshan','','县','340824','0556',6),(1457,'太湖',226,'226,1457','t','th','taihu','','县','340825','0556',7),(1458,'宿松',226,'226,1458','s','ss','susong','','县','340826','0556',8),(1459,'望江',226,'226,1459','w','wj','wangjiang','','县','340827','0556',9),(1460,'岳西',226,'226,1460','y','yx','yuexi','','县','340828','0556',10),(1461,'桐城',226,'226,1461','t','tc','tongcheng','','市','340881','0556',11),(1462,'屯溪',227,'227,1462','t','tx','tunxi','','区','341002','0559',1),(1463,'黄山',227,'227,1463','h','hs','huangshan','','区','341003','0559',2),(1464,'徽州',227,'227,1464','h','hz','huizhou','','区','341004','0559',3),(1465,'歙县',227,'227,1465','s','sx','shexian','','','341021','0559',4),(1466,'休宁',227,'227,1466','x','xn','xiuning','','县','341022','0559',5),(1467,'黟县',227,'227,1467','y','yx','yixian','','','341023','0559',6),(1468,'祁门',227,'227,1468','q','qm','qimen','','县','341024','0559',7),(1469,'琅玡',228,'228,1469','l','ly','langya','','区','341102','0550',1),(1470,'南谯',228,'228,1470','n','nq','nanqiao','','区','341103','0550',2),(1471,'来安',228,'228,1471','l','la','laian','','县','341122','0550',3),(1472,'全椒',228,'228,1472','q','qj','quanjiao','','县','341124','0550',4),(1473,'定远',228,'228,1473','d','dy','dingyuan','','县','341125','0550',5),(1474,'凤阳',228,'228,1474','f','fy','fengyang','','县','341126','0550',6),(1475,'天长',228,'228,1475','t','tz','tianzhang','','市','341181','0550',7),(1476,'明光',228,'228,1476','m','mg','mingguang','','市','341182','0550',8),(1477,'颍州',229,'229,1477','y','yz','yingzhou','','区','341202','0558',1),(1478,'颍东',229,'229,1478','y','yd','yingdong','','区','341203','0558',2),(1479,'颍泉',229,'229,1479','y','yq','yingquan','','区','341204','0558',3),(1480,'临泉',229,'229,1480','l','lq','linquan','','县','341221','0558',4),(1481,'太和',229,'229,1481','t','th','taihe','','县','341222','0558',5),(1482,'阜南',229,'229,1482','f','fn','funan','','县','341225','0558',6),(1483,'颖上',229,'229,1483','y','ys','yingshang','','县','341226','0558',7),(1484,'界首',229,'229,1484','j','js','jieshou','','市','341282','0558',8),(1485,'埇桥',230,'230,1485','y','yq','yongqiao','','区','341302','0557',1),(1486,'砀山',230,'230,1486','d','ds','dangshan','','县','341321','0557',2),(1487,'萧县',230,'230,1487','x','xx','xiaoxian','','','341322','0557',3),(1488,'灵璧',230,'230,1488','l','lb','lingbi','','县','341323','0557',4),(1489,'泗县',230,'230,1489','s','sx','sixian','','','341324','0557',5),(1490,'金安',231,'231,1490','j','ja','jinan','','区','341502','0564',1),(1491,'裕安',231,'231,1491','y','ya','yuan','','区','341503','0564',2),(1492,'寿县',231,'231,1492','s','sx','shouxian','','','341521','0564',3),(1493,'霍邱',231,'231,1493','h','hq','huoqiu','','县','341522','0564',4),(1494,'舒城',231,'231,1494','s','sc','shucheng','','县','341523','0564',5),(1495,'金寨',231,'231,1495','j','jz','jinzhai','','县','341524','0564',6),(1496,'霍山',231,'231,1496','h','hs','huoshan','','县','341525','0564',7),(1497,'谯城',232,'232,1497','q','qc','qiaocheng','','区','341602','0558',1),(1498,'涡阳',232,'232,1498','w','wy','woyang','','县','341621','0558',2),(1499,'蒙城',232,'232,1499','m','mc','mengcheng','','县','341622','0558',3),(1500,'利辛',232,'232,1500','l','lx','lixin','','县','341623','0558',4),(1501,'贵池',233,'233,1501','g','gc','guichi','','区','341702','0566',1),(1502,'东至',233,'233,1502','d','dz','dongzhi','','县','341721','0566',2),(1503,'石台',233,'233,1503','s','st','shitai','','县','341722','0566',3),(1504,'青阳',233,'233,1504','q','qy','qingyang','','县','341723','0566',4),(1505,'宣州',234,'234,1505','x','xz','xuanzhou','','区','341802','0563',1),(1506,'郎溪',234,'234,1506','l','lx','langxi','','县','341821','0563',2),(1507,'广德',234,'234,1507','g','gd','guangde','','县','341822','0563',3),(1508,'泾县',234,'234,1508','j','jx','jingxian','','','341823','0563',4),(1509,'绩溪',234,'234,1509','j','jx','jixi','','县','341824','0563',5),(1510,'旌德',234,'234,1510','j','jd','jingde','','县','341825','0563',6),(1511,'宁国',234,'234,1511','n','ng','ningguo','','市','341881','0563',7),(1512,'鼓楼',235,'235,1512','g','gl','gulou','','区','350102','0591',1),(1513,'台江',235,'235,1513','t','tj','taijiang','','区','350103','0591',2),(1514,'仓山',235,'235,1514','c','cs','cangshan','','区','350104','0591',3),(1515,'马尾',235,'235,1515','m','mw','mawei','','区','350105','0591',4),(1516,'晋安',235,'235,1516','j','ja','jinan','','区','350111','0591',5),(1517,'闽侯',235,'235,1517','m','mh','minhou','','县','350121','0591',6),(1518,'连江',235,'235,1518','l','lj','lianjiang','','县','350122','0591',7),(1519,'罗源',235,'235,1519','l','ly','luoyuan','','县','350123','0591',8),(1520,'闽清',235,'235,1520','m','mq','minqing','','县','350124','0591',9),(1521,'永泰',235,'235,1521','y','yt','yongtai','','县','350125','0591',10),(1522,'平潭',235,'235,1522','p','pt','pingtan','','县','350128','0591',11),(1523,'福清',235,'235,1523','f','fq','fuqing','','市','350181','0591',12),(1524,'长乐',235,'235,1524','z','zl','zhangle','','市','350182','0591',13),(1525,'思明',236,'236,1525','s','sm','siming','','区','350203','0592',1),(1526,'海沧',236,'236,1526','h','hc','haicang','','区','350205','0592',2),(1527,'湖里',236,'236,1527','h','hl','huli','','区','350206','0592',3),(1528,'集美',236,'236,1528','j','jm','jimei','','区','350211','0592',4),(1529,'同安',236,'236,1529','t','ta','tongan','','区','350212','0592',5),(1530,'翔安',236,'236,1530','x','xa','xiangan','','区','350213','0592',6),(1531,'城厢',237,'237,1531','c','cx','chengxiang','','区','350302','0594',1),(1532,'涵江',237,'237,1532','h','hj','hanjiang','','区','350303','0594',2),(1533,'荔城',237,'237,1533','l','lc','licheng','','区','350304','0594',3),(1534,'秀屿',237,'237,1534','x','xy','xiuyu','','区','350305','0594',4),(1535,'仙游',237,'237,1535','x','xy','xianyou','','县','350322','0594',5),(1536,'梅列',238,'238,1536','m','ml','meilie','','区','350402','0598',1),(1537,'三元',238,'238,1537','s','sy','sanyuan','','区','350403','0598',2),(1538,'明溪',238,'238,1538','m','mx','mingxi','','县','350421','0598',3),(1539,'清流',238,'238,1539','q','ql','qingliu','','县','350423','0598',4),(1540,'宁化',238,'238,1540','n','nh','ninghua','','县','350424','0598',5),(1541,'大田',238,'238,1541','d','dt','datian','','县','350425','0598',6),(1542,'尤溪',238,'238,1542','y','yx','youxi','','县','350426','0598',7),(1543,'沙县',238,'238,1543','s','sx','shaxian','','','350427','0598',8),(1544,'将乐',238,'238,1544','j','jl','jiangle','','县','350428','0598',9),(1545,'泰宁',238,'238,1545','t','tn','taining','','县','350429','0598',10),(1546,'建宁',238,'238,1546','j','jn','jianning','','县','350430','0598',11),(1547,'永安',238,'238,1547','y','ya','yongan','','市','350481','0598',12),(1548,'鲤城',239,'239,1548','l','lc','licheng','','区','350502','0595',1),(1549,'丰泽',239,'239,1549','f','fz','fengze','','区','350503','0595',2),(1550,'洛江',239,'239,1550','l','lj','luojiang','','区','350504','0595',3),(1551,'泉港',239,'239,1551','q','qg','quangang','','区','350505','0595',4),(1552,'惠安',239,'239,1552','h','ha','huian','','县','350521','0595',5),(1553,'安溪',239,'239,1553','a','ax','anxi','','县','350524','0595',6),(1554,'永春',239,'239,1554','y','yc','yongchun','','县','350525','0595',7),(1555,'德化',239,'239,1555','d','dh','dehua','','县','350526','0595',8),(1556,'金门',239,'239,1556','j','jm','jinmen','','县','350527','0595',9),(1557,'石狮',239,'239,1557','s','ss','shishi','','市','350581','0595',10),(1558,'晋江',239,'239,1558','j','jj','jinjiang','','市','350582','0595',11),(1559,'南安',239,'239,1559','n','na','nanan','','市','350583','0595',12),(1560,'芗城',240,'240,1560','x','xc','xiangcheng','','区','350602','0596',1),(1561,'龙文',240,'240,1561','l','lw','longwen','','区','350603','0596',2),(1562,'云霄',240,'240,1562','y','yx','yunxiao','','县','350622','0596',3),(1563,'漳浦',240,'240,1563','z','zp','zhangpu','','县','350623','0596',4),(1564,'诏安',240,'240,1564','z','za','zhaoan','','县','350624','0596',5),(1565,'长泰',240,'240,1565','z','zt','zhangtai','','县','350625','0596',6),(1566,'东山',240,'240,1566','d','ds','dongshan','','县','350626','0596',7),(1567,'南靖',240,'240,1567','n','nj','nanjing','','县','350627','0596',8),(1568,'平和',240,'240,1568','p','ph','pinghe','','县','350628','0596',9),(1569,'华安',240,'240,1569','h','ha','huaan','','县','350629','0596',10),(1570,'龙海',240,'240,1570','l','lh','longhai','','市','350681','0596',11),(1571,'延平',241,'241,1571','y','yp','yanping','','区','350702','0599',1),(1572,'顺昌',241,'241,1572','s','sc','shunchang','','县','350721','0599',2),(1573,'浦城',241,'241,1573','p','pc','pucheng','','县','350722','0599',3),(1574,'光泽',241,'241,1574','g','gz','guangze','','县','350723','0599',4),(1575,'松溪',241,'241,1575','s','sx','songxi','','县','350724','0599',5),(1576,'政和',241,'241,1576','z','zh','zhenghe','','县','350725','0599',6),(1577,'邵武',241,'241,1577','s','sw','shaowu','','市','350781','0599',7),(1578,'武夷山',241,'241,1578','w','wys','wuyishan','','市','350782','0599',8),(1579,'建瓯',241,'241,1579','j','jo','jianou','','市','350783','0599',9),(1580,'建阳',241,'241,1580','j','jy','jianyang','','市','350784','0599',10),(1581,'新罗',242,'242,1581','x','xl','xinluo','','区','350802','0597',1),(1582,'长汀',242,'242,1582','z','zt','zhangting','','县','350821','0597',2),(1583,'永定',242,'242,1583','y','yd','yongding','','县','350822','0597',3),(1584,'上杭',242,'242,1584','s','sh','shanghang','','县','350823','0597',4),(1585,'武平',242,'242,1585','w','wp','wuping','','县','350824','0597',5),(1586,'连城',242,'242,1586','l','lc','liancheng','','县','350825','0597',6),(1587,'漳平',242,'242,1587','z','zp','zhangping','','市','350881','0597',7),(1588,'蕉城',243,'243,1588','j','jc','jiaocheng','','区','350902','0593',1),(1589,'霞浦',243,'243,1589','x','xp','xiapu','','县','350921','0593',2),(1590,'古田',243,'243,1590','g','gt','gutian','','县','350922','0593',3),(1591,'屏南',243,'243,1591','p','pn','pingnan','','县','350923','0593',4),(1592,'寿宁',243,'243,1592','s','sn','shouning','','县','350924','0593',5),(1593,'周宁',243,'243,1593','z','zn','zhouning','','县','350925','0593',6),(1594,'柘荣',243,'243,1594','z','zr','zherong','','县','350926','0593',7),(1595,'福安',243,'243,1595','f','fa','fuan','','市','350981','0593',8),(1596,'福鼎',243,'243,1596','f','fd','fuding','','市','350982','0593',9),(1597,'东湖',244,'244,1597','d','dh','donghu','','区','360102','0791',1),(1598,'西湖',244,'244,1598','x','xh','xihu','','区','360103','0791',2),(1599,'青云谱',244,'244,1599','q','qyp','qingyunpu','','区','360104','0791',3),(1600,'湾里',244,'244,1600','w','wl','wanli','','区','360105','0791',4),(1601,'青山湖',244,'244,1601','q','qsh','qingshanhu','','区','360111','0791',5),(1602,'南昌',244,'244,1602','n','nc','nanchang','','县','360121','0791',6),(1603,'新建',244,'244,1603','x','xj','xinjian','','县','360122','0791',7),(1604,'安义',244,'244,1604','a','ay','anyi','','县','360123','0791',8),(1605,'进贤',244,'244,1605','j','jx','jinxian','','县','360124','0791',9),(1606,'昌江',245,'245,1606','c','cj','changjiang','','区','360202','0798',1),(1607,'珠山',245,'245,1607','z','zs','zhushan','','区','360203','0798',2),(1608,'浮梁',245,'245,1608','f','fl','fuliang','','县','360222','0798',3),(1609,'乐平',245,'245,1609','l','lp','leping','','市','360281','0798',4),(1610,'安源',246,'246,1610','a','ay','anyuan','','区','360302','0799',1),(1611,'湘东',246,'246,1611','x','xd','xiangdong','','区','360313','0799',2),(1612,'莲花',246,'246,1612','l','lh','lianhua','','县','360321','0799',3),(1613,'上栗',246,'246,1613','s','sl','shangli','','县','360322','0799',4),(1614,'芦溪',246,'246,1614','l','lx','luxi','','县','360323','0799',5),(1615,'庐山',247,'247,1615','l','ls','lushan','','区','360402','0792',1),(1616,'浔阳',247,'247,1616','x','xy','xunyang','','区','360403','0792',2),(1617,'九江',247,'247,1617','j','jj','jiujiang','','县','360421','0792',3),(1618,'武宁',247,'247,1618','w','wn','wuning','','县','360423','0792',4),(1619,'修水',247,'247,1619','x','xs','xiushui','','县','360424','0792',5),(1620,'永修',247,'247,1620','y','yx','yongxiu','','县','360425','0792',6),(1621,'德安',247,'247,1621','d','da','dean','','县','360426','0792',7),(1622,'星子',247,'247,1622','x','xz','xingzi','','县','360427','0792',8),(1623,'都昌',247,'247,1623','d','dc','douchang','','县','360428','0792',9),(1624,'湖口',247,'247,1624','h','hk','hukou','','县','360429','0792',10),(1625,'彭泽',247,'247,1625','p','pz','pengze','','县','360430','0792',11),(1626,'瑞昌',247,'247,1626','r','rc','ruichang','','市','360481','0792',12),(1627,'共青城',247,'247,1627','g','gqc','gongqingcheng','','市','360482','0792',13),(1628,'渝水',248,'248,1628','y','ys','yushui','','区','360502','0790',1),(1629,'分宜',248,'248,1629','f','fy','fenyi','','县','360521','0790',2),(1630,'月湖',249,'249,1630','y','yh','yuehu','','区','360602','0701',1),(1631,'余江',249,'249,1631','y','yj','yujiang','','县','360622','0701',2),(1632,'贵溪',249,'249,1632','g','gx','guixi','','市','360681','0701',3),(1633,'章贡',250,'250,1633','z','zg','zhanggong','','区','360702','0797',1),(1634,'赣县',250,'250,1634','g','gx','ganxian','','','360721','0797',2),(1635,'信丰',250,'250,1635','x','xf','xinfeng','','县','360722','0797',3),(1636,'大余',250,'250,1636','d','dy','dayu','','县','360723','0797',4),(1637,'上犹',250,'250,1637','s','sy','shangyou','','县','360724','0797',5),(1638,'崇义',250,'250,1638','c','cy','chongyi','','县','360725','0797',6),(1639,'安远',250,'250,1639','a','ay','anyuan','','县','360726','0797',7),(1640,'龙南',250,'250,1640','l','ln','longnan','','县','360727','0797',8),(1641,'定南',250,'250,1641','d','dn','dingnan','','县','360728','0797',9),(1642,'全南',250,'250,1642','q','qn','quannan','','县','360729','0797',10),(1643,'宁都',250,'250,1643','n','nd','ningdou','','县','360730','0797',11),(1644,'于都',250,'250,1644','y','yd','yudou','','县','360731','0797',12),(1645,'兴国',250,'250,1645','x','xg','xingguo','','县','360732','0797',13),(1646,'会昌',250,'250,1646','h','hc','huichang','','县','360733','0797',14),(1647,'寻乌',250,'250,1647','x','xw','xunwu','','县','360734','0797',15),(1648,'石城',250,'250,1648','s','sc','shicheng','','县','360735','0797',16),(1649,'瑞金',250,'250,1649','r','rj','ruijin','','市','360781','0797',17),(1650,'南康',250,'250,1650','n','nk','nankang','','市','360782','0797',18),(1651,'吉州',251,'251,1651','j','jz','jizhou','','区','360802','0796',1),(1652,'青原',251,'251,1652','q','qy','qingyuan','','区','360803','0796',2),(1653,'吉安',251,'251,1653','j','ja','jian','','县','360821','0796',3),(1654,'吉水',251,'251,1654','j','js','jishui','','县','360822','0796',4),(1655,'峡江',251,'251,1655','x','xj','xiajiang','','县','360823','0796',5),(1656,'新干',251,'251,1656','x','xg','xingan','','县','360824','0796',6),(1657,'永丰',251,'251,1657','y','yf','yongfeng','','县','360825','0796',7),(1658,'泰和',251,'251,1658','t','th','taihe','','县','360826','0796',8),(1659,'遂川',251,'251,1659','s','sc','suichuan','','县','360827','0796',9),(1660,'万安',251,'251,1660','w','wa','wanan','','县','360828','0796',10),(1661,'安福',251,'251,1661','a','af','anfu','','县','360829','0796',11),(1662,'永新',251,'251,1662','y','yx','yongxin','','县','360830','0796',12),(1663,'井冈山',251,'251,1663','j','jgs','jinggangshan','','市','360881','0796',13),(1664,'袁州',252,'252,1664','y','yz','yuanzhou','','区','360902','0795',1),(1665,'奉新',252,'252,1665','f','fx','fengxin','','县','360921','0795',2),(1666,'万载',252,'252,1666','w','wz','wanzai','','县','360922','0795',3),(1667,'上高',252,'252,1667','s','sg','shanggao','','县','360923','0795',4),(1668,'宜丰',252,'252,1668','y','yf','yifeng','','县','360924','0795',5),(1669,'靖安',252,'252,1669','j','ja','jingan','','县','360925','0795',6),(1670,'铜鼓',252,'252,1670','t','tg','tonggu','','县','360926','0795',7),(1671,'丰城',252,'252,1671','f','fc','fengcheng','','市','360981','0795',8),(1672,'樟树',252,'252,1672','z','zs','zhangshu','','市','360982','0795',9),(1673,'高安',252,'252,1673','g','ga','gaoan','','市','360983','0795',10),(1674,'临川',253,'253,1674','l','lc','linchuan','','区','361002','0794',1),(1675,'南城',253,'253,1675','n','nc','nancheng','','县','361021','0794',2),(1676,'黎川',253,'253,1676','l','lc','lichuan','','县','361022','0794',3),(1677,'南丰',253,'253,1677','n','nf','nanfeng','','县','361023','0794',4),(1678,'崇仁',253,'253,1678','c','cr','chongren','','县','361024','0794',5),(1679,'乐安',253,'253,1679','l','la','lean','','县','361025','0794',6),(1680,'宜黄',253,'253,1680','y','yh','yihuang','','县','361026','0794',7),(1681,'金溪',253,'253,1681','j','jx','jinxi','','县','361027','0794',8),(1682,'资溪',253,'253,1682','z','zx','zixi','','县','361028','0794',9),(1683,'东乡',253,'253,1683','d','dx','dongxiang','','县','361029','0794',10),(1684,'广昌',253,'253,1684','g','gc','guangchang','','县','361030','0794',11),(1685,'信州',254,'254,1685','x','xz','xinzhou','','区','361102','0793',1),(1686,'上饶',254,'254,1686','s','sr','shangrao','','县','361121','0793',2),(1687,'广丰',254,'254,1687','g','gf','guangfeng','','县','361122','0793',3),(1688,'玉山',254,'254,1688','y','ys','yushan','','县','361123','0793',4),(1689,'铅山',254,'254,1689','q','qs','qianshan','','县','361124','0793',5),(1690,'横峰',254,'254,1690','h','hf','hengfeng','','县','361125','0793',6),(1691,'弋阳',254,'254,1691','y','yy','yiyang','','县','361126','0793',7),(1692,'余干',254,'254,1692','y','yg','yugan','','县','361127','0793',8),(1693,'鄱阳',254,'254,1693','p','py','poyang','','县','361128','0793',9),(1694,'万年',254,'254,1694','w','wn','wannian','','县','361129','0793',10),(1695,'婺源',254,'254,1695','w','wy','wuyuan','','县','361130','0793',11),(1696,'德兴',254,'254,1696','d','dx','dexing','','市','361181','0793',12),(1697,'历下',255,'255,1697','l','lx','lixia','','区','370102','0531',1),(1698,'市中',255,'255,1698','s','sz','shizhong','','区','370103','0531',2),(1699,'槐荫',255,'255,1699','h','hy','huaiyin','','区','370104','0531',3),(1700,'天桥',255,'255,1700','t','tq','tianqiao','','区','370105','0531',4),(1701,'历城',255,'255,1701','l','lc','licheng','','区','370112','0531',5),(1702,'长清',255,'255,1702','z','zq','zhangqing','','区','370113','0531',6),(1703,'平阴',255,'255,1703','p','py','pingyin','','县','370124','0531',7),(1704,'济阳',255,'255,1704','j','jy','jiyang','','县','370125','0531',8),(1705,'商河',255,'255,1705','s','sh','shanghe','','县','370126','0531',9),(1706,'章丘',255,'255,1706','z','zq','zhangqiu','','市','370181','0531',10),(1707,'市南',256,'256,1707','s','sn','shinan','','区','370202','0532',1),(1708,'市北',256,'256,1708','s','sb','shibei','','区','370203','0532',2),(1709,'黄岛',256,'256,1709','h','hd','huangdao','','区','370211','0532',3),(1710,'崂山',256,'256,1710','l','ls','laoshan','','区','370212','0532',4),(1711,'李沧',256,'256,1711','l','lc','licang','','区','370213','0532',5),(1712,'城阳',256,'256,1712','c','cy','chengyang','','区','370214','0532',6),(1713,'胶州',256,'256,1713','j','jz','jiaozhou','','市','370281','0532',7),(1714,'即墨',256,'256,1714','j','jm','jimo','','市','370282','0532',8),(1715,'平度',256,'256,1715','p','pd','pingdu','','市','370283','0532',9),(1716,'莱西',256,'256,1716','l','lx','laixi','','市','370285','0532',10),(1717,'淄川',257,'257,1717','z','zc','zichuan','','区','370302','0533',1),(1718,'张店',257,'257,1718','z','zd','zhangdian','','区','370303','0533',2),(1719,'博山',257,'257,1719','b','bs','boshan','','区','370304','0533',3),(1720,'临淄',257,'257,1720','l','lz','linzi','','区','370305','0533',4),(1721,'周村',257,'257,1721','z','zc','zhoucun','','区','370306','0533',5),(1722,'桓台',257,'257,1722','h','ht','huantai','','县','370321','0533',6),(1723,'高青',257,'257,1723','g','gq','gaoqing','','县','370322','0533',7),(1724,'沂源',257,'257,1724','y','yy','yiyuan','','县','370323','0533',8),(1725,'市中',258,'258,1725','s','sz','shizhong','','区','370402','0632',1),(1726,'薛城',258,'258,1726','x','xc','xuecheng','','区','370403','0632',2),(1727,'峄城',258,'258,1727','y','yc','yicheng','','区','370404','0632',3),(1728,'台儿庄',258,'258,1728','t','tez','taierzhuang','','区','370405','0632',4),(1729,'山亭',258,'258,1729','s','st','shanting','','区','370406','0632',5),(1730,'滕州',258,'258,1730','t','tz','tengzhou','','市','370481','0632',6),(1731,'东营',259,'259,1731','d','dy','dongying','','区','370502','0546',1),(1732,'河口',259,'259,1732','h','hk','hekou','','区','370503','0546',2),(1733,'垦利',259,'259,1733','k','kl','kenli','','县','370521','0546',3),(1734,'利津',259,'259,1734','l','lj','lijin','','县','370522','0546',4),(1735,'广饶',259,'259,1735','g','gr','guangrao','','县','370523','0546',5),(1736,'芝罘',260,'260,1736','z','zf','zhifu','','区','370602','0535',1),(1737,'福山',260,'260,1737','f','fs','fushan','','区','370611','0535',2),(1738,'牟平',260,'260,1738','m','mp','mouping','','区','370612','0535',3),(1739,'莱山',260,'260,1739','l','ls','laishan','','区','370613','0535',4),(1740,'长岛',260,'260,1740','z','zd','zhangdao','','县','370634','0535',5),(1741,'龙口',260,'260,1741','l','lk','longkou','','市','370681','0535',6),(1742,'莱阳',260,'260,1742','l','ly','laiyang','','市','370682','0535',7),(1743,'莱州',260,'260,1743','l','lz','laizhou','','市','370683','0535',8),(1744,'蓬莱',260,'260,1744','p','pl','penglai','','市','370684','0535',9),(1745,'招远',260,'260,1745','z','zy','zhaoyuan','','市','370685','0535',10),(1746,'栖霞',260,'260,1746','q','qx','qixia','','市','370686','0535',11),(1747,'海阳',260,'260,1747','h','hy','haiyang','','市','370687','0535',12),(1748,'潍城',261,'261,1748','w','wc','weicheng','','区','370702','0536',1),(1749,'寒亭',261,'261,1749','h','ht','hanting','','区','370703','0536',2),(1750,'坊子',261,'261,1750','f','fz','fangzi','','区','370704','0536',3),(1751,'奎文',261,'261,1751','k','kw','kuiwen','','区','370705','0536',4),(1752,'临朐',261,'261,1752','l','lq','linqu','','县','370724','0536',5),(1753,'昌乐',261,'261,1753','c','cl','changle','','县','370725','0536',6),(1754,'青州',261,'261,1754','q','qz','qingzhou','','市','370781','0536',7),(1755,'诸城',261,'261,1755','z','zc','zhucheng','','市','370782','0536',8),(1756,'寿光',261,'261,1756','s','sg','shouguang','','市','370783','0536',9),(1757,'安丘',261,'261,1757','a','aq','anqiu','','市','370784','0536',10),(1758,'高密',261,'261,1758','g','gm','gaomi','','市','370785','0536',11),(1759,'昌邑',261,'261,1759','c','cy','changyi','','市','370786','0536',12),(1760,'市中',262,'262,1760','s','sz','shizhong','','区','370802','0537',1),(1761,'任城',262,'262,1761','r','rc','rencheng','','区','370811','0537',2),(1762,'微山',262,'262,1762','w','ws','weishan','','县','370826','0537',3),(1763,'鱼台',262,'262,1763','y','yt','yutai','','县','370827','0537',4),(1764,'金乡',262,'262,1764','j','jx','jinxiang','','县','370828','0537',5),(1765,'嘉祥',262,'262,1765','j','jx','jiaxiang','','县','370829','0537',6),(1766,'汶上',262,'262,1766','w','ws','wenshang','','县','370830','0537',7),(1767,'泗水',262,'262,1767','s','ss','sishui','','县','370831','0537',8),(1768,'梁山',262,'262,1768','l','ls','liangshan','','县','370832','0537',9),(1769,'曲阜',262,'262,1769','q','qf','qufu','','市','370881','0537',10),(1770,'兖州',262,'262,1770','y','yz','yanzhou','','市','370882','0537',11),(1771,'邹城',262,'262,1771','z','zc','zoucheng','','市','370883','0537',12),(1772,'泰山',263,'263,1772','t','ts','taishan','','区','370902','0538',1),(1773,'岱岳',263,'263,1773','d','dy','daiyue','','区','370911','0538',2),(1774,'宁阳',263,'263,1774','n','ny','ningyang','','县','370921','0538',3),(1775,'东平',263,'263,1775','d','dp','dongping','','县','370923','0538',4),(1776,'新泰',263,'263,1776','x','xt','xintai','','市','370982','0538',5),(1777,'肥城',263,'263,1777','f','fc','feicheng','','市','370983','0538',6),(1778,'环翠',264,'264,1778','h','hc','huancui','','区','371002','0631',1),(1779,'文登',264,'264,1779','w','wd','wendeng','','市','371081','0631',2),(1780,'荣成',264,'264,1780','r','rc','rongcheng','','市','371082','0631',3),(1781,'乳山',264,'264,1781','r','rs','rushan','','市','371083','0631',4),(1782,'东港',265,'265,1782','d','dg','donggang','','区','371102','0633',1),(1783,'岚山',265,'265,1783','l','ls','lanshan','','区','371103','0633',2),(1784,'五莲',265,'265,1784','w','wl','wulian','','县','371121','0633',3),(1785,'莒县',265,'265,1785','j','jx','juxian','','','371122','0633',4),(1786,'莱城',266,'266,1786','l','lc','laicheng','','区','371202','0634',1),(1787,'钢城',266,'266,1787','g','gc','gangcheng','','区','371203','0634',2),(1788,'兰山',267,'267,1788','l','ls','lanshan','','区','371302','0539',1),(1789,'罗庄',267,'267,1789','l','lz','luozhuang','','区','371311','0539',2),(1790,'河东',267,'267,1790','h','hd','hedong','','区','371312','0539',3),(1791,'沂南',267,'267,1791','y','yn','yinan','','县','371321','0539',4),(1792,'郯城',267,'267,1792','t','tc','tancheng','','县','371322','0539',5),(1793,'沂水',267,'267,1793','y','ys','yishui','','县','371323','0539',6),(1794,'苍山',267,'267,1794','c','cs','cangshan','','县','371324','0539',7),(1795,'费县',267,'267,1795','f','fx','feixian','','','371325','0539',8),(1796,'平邑',267,'267,1796','p','py','pingyi','','县','371326','0539',9),(1797,'莒南',267,'267,1797','j','jn','junan','','县','371327','0539',10),(1798,'蒙阴',267,'267,1798','m','my','mengyin','','县','371328','0539',11),(1799,'临沭',267,'267,1799','l','ls','linshu','','县','371329','0539',12),(1800,'德城',268,'268,1800','d','dc','decheng','','区','371402','0534',1),(1801,'陵县',268,'268,1801','l','lx','lingxian','','','371421','0534',2),(1802,'宁津',268,'268,1802','n','nj','ningjin','','县','371422','0534',3),(1803,'庆云',268,'268,1803','q','qy','qingyun','','县','371423','0534',4),(1804,'临邑',268,'268,1804','l','ly','linyi','','县','371424','0534',5),(1805,'齐河',268,'268,1805','q','qh','qihe','','县','371425','0534',6),(1806,'平原',268,'268,1806','p','py','pingyuan','','县','371426','0534',7),(1807,'夏津',268,'268,1807','x','xj','xiajin','','县','371427','0534',8),(1808,'武城',268,'268,1808','w','wc','wucheng','','县','371428','0534',9),(1809,'乐陵',268,'268,1809','l','ll','leling','','市','371481','0534',10),(1810,'禹城',268,'268,1810','y','yc','yucheng','','市','371482','0534',11),(1811,'东昌府',269,'269,1811','d','dcf','dongchangfu','','区','371502','0635',1),(1812,'阳谷',269,'269,1812','y','yg','yanggu','','县','371521','0635',2),(1813,'莘县',269,'269,1813','x','xx','xinxian','','','371522','0635',3),(1814,'茌平',269,'269,1814','c','cp','chiping','','县','371523','0635',4),(1815,'东阿',269,'269,1815','d','da','donga','','县','371524','0635',5),(1816,'冠县',269,'269,1816','g','gx','guanxian','','','371525','0635',6),(1817,'高唐',269,'269,1817','g','gt','gaotang','','县','371526','0635',7),(1818,'临清',269,'269,1818','l','lq','linqing','','市','371581','0635',8),(1819,'滨城',270,'270,1819','b','bc','bincheng','','区','371602','0543',1),(1820,'惠民',270,'270,1820','h','hm','huimin','','县','371621','0543',2),(1821,'阳信',270,'270,1821','y','yx','yangxin','','县','371622','0543',3),(1822,'无棣',270,'270,1822','w','wd','wudi','','县','371623','0543',4),(1823,'沾化',270,'270,1823','z','zh','zhanhua','','县','371624','0543',5),(1824,'博兴',270,'270,1824','b','bx','boxing','','县','371625','0543',6),(1825,'邹平',270,'270,1825','z','zp','zouping','','县','371626','0543',7),(1826,'牡丹',271,'271,1826','m','md','mudan','','区','371702','0530',1),(1827,'曹县',271,'271,1827','c','cx','caoxian','','','371721','0530',2),(1828,'单县',271,'271,1828','d','dx','danxian','','','371722','0530',3),(1829,'成武',271,'271,1829','c','cw','chengwu','','县','371723','0530',4),(1830,'巨野',271,'271,1830','j','jy','juye','','县','371724','0530',5),(1831,'郓城',271,'271,1831','y','yc','yuncheng','','县','371725','0530',6),(1832,'鄄城',271,'271,1832','j','jc','juancheng','','县','371726','0530',7),(1833,'定陶',271,'271,1833','d','dt','dingtao','','县','371727','0530',8),(1834,'东明',271,'271,1834','d','dm','dongming','','县','371728','0530',9),(1835,'中原',272,'272,1835','z','zy','zhongyuan','','区','410102','0371',1),(1836,'二七',272,'272,1836','e','eq','erqi','','区','410103','0371',2),(1837,'管城',272,'272,1837','g','gc','guancheng','回族','区','410104','0371',3),(1838,'金水',272,'272,1838','j','js','jinshui','','区','410105','0371',4),(1839,'上街',272,'272,1839','s','sj','shangjie','','区','410106','0371',5),(1840,'惠济',272,'272,1840','h','hj','huiji','','区','410108','0371',6),(1841,'中牟',272,'272,1841','z','zm','zhongmou','','县','410122','0371',7),(1842,'巩义',272,'272,1842','g','gy','gongyi','','市','410181','0371',8),(1843,'荥阳',272,'272,1843','y','yy','yingyang','','市','410182','0371',9),(1844,'新密',272,'272,1844','x','xm','xinmi','','市','410183','0371',10),(1845,'新郑',272,'272,1845','x','xz','xinzheng','','市','410184','0371',11),(1846,'登封',272,'272,1846','d','df','dengfeng','','市','410185','0371',12),(1847,'龙亭',273,'273,1847','l','lt','longting','','区','410202','0378',1),(1848,'顺河',273,'273,1848','s','sh','shunhe','回族','区','410203','0378',2),(1849,'鼓楼',273,'273,1849','g','gl','gulou','','区','410204','0378',3),(1850,'禹王台',273,'273,1850','y','ywt','yuwangtai','','区','410205','0378',4),(1851,'金明',273,'273,1851','j','jm','jinming','','区','410211','0378',5),(1852,'杞县',273,'273,1852','q','qx','qixian','','','410221','0378',6),(1853,'通许',273,'273,1853','t','tx','tongxu','','县','410222','0378',7),(1854,'尉氏',273,'273,1854','w','ws','weishi','','县','410223','0378',8),(1855,'开封',273,'273,1855','k','kf','kaifeng','','县','410224','0378',9),(1856,'兰考',273,'273,1856','l','lk','lankao','','县','410225','0378',10),(1857,'老城',274,'274,1857','l','lc','laocheng','','区','410302','0376',1),(1858,'西工',274,'274,1858','x','xg','xigong','','区','410303','0376',2),(1859,'瀍河',274,'274,1859','c','ch','chanhe','回族','区','410304','0376',3),(1860,'涧西',274,'274,1860','j','jx','jianxi','','区','410305','0376',4),(1861,'吉利',274,'274,1861','j','jl','jili','','区','410306','0376',5),(1862,'洛龙',274,'274,1862','l','ll','luolong','','区','410311','0376',6),(1863,'孟津',274,'274,1863','m','mj','mengjin','','县','410322','0376',7),(1864,'新安',274,'274,1864','x','xa','xinan','','县','410323','0376',8),(1865,'栾川',274,'274,1865','l','lc','luanchuan','','县','410324','0376',9),(1866,'嵩县',274,'274,1866','s','sx','songxian','','','410325','0376',10),(1867,'汝阳',274,'274,1867','r','ry','ruyang','','县','410326','0376',11),(1868,'宜阳',274,'274,1868','y','yy','yiyang','','县','410327','0376',12),(1869,'洛宁',274,'274,1869','l','ln','luoning','','县','410328','0376',13),(1870,'伊川',274,'274,1870','y','yc','yichuan','','县','410329','0376',14),(1871,'偃师',274,'274,1871','y','ys','yanshi','','市','410381','0376',15),(1872,'新华',275,'275,1872','x','xh','xinhua','','区','410402','0375',1),(1873,'卫东',275,'275,1873','w','wd','weidong','','区','410403','0375',2),(1874,'石龙',275,'275,1874','s','sl','shilong','','区','410404','0375',3),(1875,'湛河',275,'275,1875','z','zh','zhanhe','','区','410411','0375',4),(1876,'宝丰',275,'275,1876','b','bf','baofeng','','县','410421','0375',5),(1877,'叶县',275,'275,1877','y','yx','yexian','','','410422','0375',6),(1878,'鲁山',275,'275,1878','l','ls','lushan','','县','410423','0375',7),(1879,'郏县',275,'275,1879','j','jx','jiaxian','','','410425','0375',8),(1880,'舞钢',275,'275,1880','w','wg','wugang','','市','410481','0375',9),(1881,'汝州',275,'275,1881','r','rz','ruzhou','','市','410482','0375',10),(1882,'文峰',276,'276,1882','w','wf','wenfeng','','区','410502','0372',1),(1883,'北关',276,'276,1883','b','bg','beiguan','','区','410503','0372',2),(1884,'殷都',276,'276,1884','y','yd','yindou','','区','410505','0372',3),(1885,'龙安',276,'276,1885','l','la','longan','','区','410506','0372',4),(1886,'安阳',276,'276,1886','a','ay','anyang','','县','410522','0372',5),(1887,'汤阴',276,'276,1887','t','ty','tangyin','','县','410523','0372',6),(1888,'滑县',276,'276,1888','h','hx','huaxian','','','410526','0372',7),(1889,'内黄',276,'276,1889','n','nh','neihuang','','县','410527','0372',8),(1890,'林州',276,'276,1890','l','lz','linzhou','','市','410581','0372',9),(1891,'鹤山',277,'277,1891','h','hs','heshan','','区','410602','0392',1),(1892,'山城',277,'277,1892','s','sc','shancheng','','区','410603','0392',2),(1893,'淇滨',277,'277,1893','q','qb','qibin','','区','410611','0392',3),(1894,'浚县',277,'277,1894','j','jx','junxian','','','410621','0392',4),(1895,'淇县',277,'277,1895','q','qx','qixian','','','410622','0392',5),(1896,'红旗',278,'278,1896','h','hq','hongqi','','区','410702','0373',1),(1897,'卫滨',278,'278,1897','w','wb','weibin','','区','410703','0373',2),(1898,'凤泉',278,'278,1898','f','fq','fengquan','','区','410704','0373',3),(1899,'牧野',278,'278,1899','m','my','muye','','区','410711','0373',4),(1900,'新乡',278,'278,1900','x','xx','xinxiang','','县','410721','0373',5),(1901,'获嘉',278,'278,1901','h','hj','huojia','','县','410724','0373',6),(1902,'原阳',278,'278,1902','y','yy','yuanyang','','县','410725','0373',7),(1903,'延津',278,'278,1903','y','yj','yanjin','','县','410726','0373',8),(1904,'封丘',278,'278,1904','f','fq','fengqiu','','县','410727','0373',9),(1905,'长垣',278,'278,1905','z','zy','zhangyuan','','县','410728','0373',10),(1906,'卫辉',278,'278,1906','w','wh','weihui','','市','410781','0373',11),(1907,'辉县',278,'278,1907','h','hx','huixian','','市','410782','0373',12),(1908,'解放',279,'279,1908','j','jf','jiefang','','区','410802','0391',1),(1909,'中站',279,'279,1909','z','zz','zhongzhan','','区','410803','0391',2),(1910,'马村',279,'279,1910','m','mc','macun','','区','410804','0391',3),(1911,'山阳',279,'279,1911','s','sy','shanyang','','区','410811','0391',4),(1912,'修武',279,'279,1912','x','xw','xiuwu','','县','410821','0391',5),(1913,'博爱',279,'279,1913','b','ba','boai','','县','410822','0391',6),(1914,'武陟',279,'279,1914','w','wz','wuzhi','','县','410823','0391',7),(1915,'温县',279,'279,1915','w','wx','wenxian','','','410825','0391',8),(1916,'沁阳',279,'279,1916','q','qy','qinyang','','市','410882','0391',9),(1917,'孟州',279,'279,1917','m','mz','mengzhou','','市','410883','0391',10),(1918,'华龙',280,'280,1918','h','hl','hualong','','区','410902','',1),(1919,'清丰',280,'280,1919','q','qf','qingfeng','','县','410922','',2),(1920,'南乐',280,'280,1920','n','nl','nanle','','县','410923','',3),(1921,'范县',280,'280,1921','f','fx','fanxian','','','410926','',4),(1922,'台前',280,'280,1922','t','tq','taiqian','','县','410927','',5),(1923,'濮阳',280,'280,1923','p','py','puyang','','县','410928','',6),(1924,'魏都',281,'281,1924','w','wd','weidou','','区','411002','0374',1),(1925,'许昌',281,'281,1925','x','xc','xuchang','','县','411023','0374',2),(1926,'鄢陵',281,'281,1926','y','yl','yanling','','县','411024','0374',3),(1927,'襄城',281,'281,1927','x','xc','xiangcheng','','县','411025','0374',4),(1928,'禹州',281,'281,1928','y','yz','yuzhou','','市','411081','0374',5),(1929,'长葛',281,'281,1929','z','zg','zhangge','','市','411082','0374',6),(1930,'源汇',282,'282,1930','y','yh','yuanhui','','区','411102','0395',1),(1931,'郾城',282,'282,1931','y','yc','yancheng','','区','411103','0395',2),(1932,'召陵',282,'282,1932','z','zl','zhaoling','','区','411104','0395',3),(1933,'舞阳',282,'282,1933','w','wy','wuyang','','县','411121','0395',4),(1934,'临颍',282,'282,1934','l','ly','linying','','县','411122','0395',5),(1935,'湖滨',283,'283,1935','h','hb','hubin','','区','411202','0398',1),(1936,'渑池',283,'283,1936','m','mc','mianchi','','县','411221','0398',2),(1937,'陕县',283,'283,1937','s','sx','shanxian','','','411222','0398',3),(1938,'卢氏',283,'283,1938','l','ls','lushi','','县','411224','0398',4),(1939,'义马',283,'283,1939','y','ym','yima','','市','411281','0398',5),(1940,'灵宝',283,'283,1940','l','lb','lingbao','','市','411282','0398',6),(1941,'宛城',284,'284,1941','w','wc','wancheng','','区','411302','0377',1),(1942,'卧龙',284,'284,1942','w','wl','wolong','','区','411303','0377',2),(1943,'南召',284,'284,1943','n','nz','nanzhao','','县','411321','0377',3),(1944,'方城',284,'284,1944','f','fc','fangcheng','','县','411322','0377',4),(1945,'西峡',284,'284,1945','x','xx','xixia','','县','411323','0377',5),(1946,'镇平',284,'284,1946','z','zp','zhenping','','县','411324','0377',6),(1947,'内乡',284,'284,1947','n','nx','neixiang','','县','411325','0377',7),(1948,'淅川',284,'284,1948','x','xc','xichuan','','县','411326','0377',8),(1949,'社旗',284,'284,1949','s','sq','sheqi','','县','411327','0377',9),(1950,'唐河',284,'284,1950','t','th','tanghe','','县','411328','0377',10),(1951,'新野',284,'284,1951','x','xy','xinye','','县','411329','0377',11),(1952,'桐柏',284,'284,1952','t','tb','tongbo','','县','411330','0377',12),(1953,'邓州',284,'284,1953','d','dz','dengzhou','','市','411381','0377',13),(1954,'粱园',285,'285,1954','l','ly','liangyuan','','区','411402','0370',1),(1955,'睢阳',285,'285,1955','s','sy','suiyang','','区','411403','0370',2),(1956,'民权',285,'285,1956','m','mq','minquan','','县','411421','0370',3),(1957,'睢县',285,'285,1957','s','sx','suixian','','','411422','0370',4),(1958,'宁陵',285,'285,1958','n','nl','ningling','','县','411423','0370',5),(1959,'柘城',285,'285,1959','z','zc','zhecheng','','县','411424','0370',6),(1960,'虞城',285,'285,1960','y','yc','yucheng','','县','411425','0370',7),(1961,'夏邑',285,'285,1961','x','xy','xiayi','','县','411426','0370',8),(1962,'永城',285,'285,1962','y','yc','yongcheng','','市','411481','0370',9),(1963,'浉河',286,'286,1963','s','sh','shihe','','区','411502','0376',1),(1964,'平桥',286,'286,1964','p','pq','pingqiao','','区','411503','0376',2),(1965,'罗山',286,'286,1965','l','ls','luoshan','','县','411521','0376',3),(1966,'光山',286,'286,1966','g','gs','guangshan','','县','411522','0376',4),(1967,'新县',286,'286,1967','x','xx','xinxian','','','411523','0376',5),(1968,'商城',286,'286,1968','s','sc','shangcheng','','县','411524','0376',6),(1969,'固始',286,'286,1969','g','gs','gushi','','县','411525','0376',7),(1970,'潢川',286,'286,1970','h','hc','huangchuan','','县','411526','0376',8),(1971,'淮滨',286,'286,1971','h','hb','huaibin','','县','411527','0376',9),(1972,'息县',286,'286,1972','x','xx','xixian','','','411528','0376',10),(1973,'川汇',287,'287,1973','c','ch','chuanhui','','区','411602','0394',1),(1974,'扶沟',287,'287,1974','f','fg','fugou','','县','411621','0394',2),(1975,'西华',287,'287,1975','x','xh','xihua','','县','411622','0394',3),(1976,'商水',287,'287,1976','s','ss','shangshui','','县','411623','0394',4),(1977,'沈丘',287,'287,1977','s','sq','shenqiu','','县','411624','0394',5),(1978,'郸城',287,'287,1978','d','dc','dancheng','','县','411625','0394',6),(1979,'淮阳',287,'287,1979','h','hy','huaiyang','','县','411626','0394',7),(1980,'太康',287,'287,1980','t','tk','taikang','','县','411627','0394',8),(1981,'鹿邑',287,'287,1981','l','ly','luyi','','县','411628','0394',9),(1982,'项城',287,'287,1982','x','xc','xiangcheng','','市','411681','0394',10),(1983,'驿城',288,'288,1983','y','yc','yicheng','','区','411702','0396',1),(1984,'西平',288,'288,1984','x','xp','xiping','','县','411721','0396',2),(1985,'上蔡',288,'288,1985','s','sc','shangcai','','县','411722','0396',3),(1986,'平舆',288,'288,1986','p','py','pingyu','','县','411723','0396',4),(1987,'正阳',288,'288,1987','z','zy','zhengyang','','县','411724','0396',5),(1988,'确山',288,'288,1988','q','qs','queshan','','县','411725','0396',6),(1989,'泌阳',288,'288,1989','m','my','miyang','','县','411726','0396',7),(1990,'汝南',288,'288,1990','r','rn','runan','','县','411727','0396',8),(1991,'遂平',288,'288,1991','s','sp','suiping','','县','411728','0396',9),(1992,'新蔡',288,'288,1992','x','xc','xincai','','县','411729','0396',10),(1993,'江岸',290,'290,1993','j','ja','jiangan','','区','420102','027',1),(1994,'江汉',290,'290,1994','j','jh','jianghan','','区','420103','027',2),(1995,'硚口',290,'290,1995','q','qk','qiaokou','','区','420104','027',3),(1996,'汉阳',290,'290,1996','h','hy','hanyang','','区','420105','027',4),(1997,'武昌',290,'290,1997','w','wc','wuchang','','区','420106','027',5),(1998,'青山',290,'290,1998','q','qs','qingshan','','区','420107','027',6),(1999,'洪山',290,'290,1999','h','hs','hongshan','','区','420111','027',7),(2000,'东西湖',290,'290,2000','d','dxh','dongxihu','','区','420112','027',8),(2001,'汉南',290,'290,2001','h','hn','hannan','','区','420113','027',9),(2002,'蔡甸',290,'290,2002','c','cd','caidian','','区','420114','027',10),(2003,'江夏',290,'290,2003','j','jx','jiangxia','','区','420115','027',11),(2004,'黄陂',290,'290,2004','h','hp','huangpo','','区','420116','027',12),(2005,'新洲',290,'290,2005','x','xz','xinzhou','','区','420117','027',13),(2006,'黄石港',291,'291,2006','h','hsg','huangshigang','','区','420202','0714',1),(2007,'西塞山',291,'291,2007','x','xss','xisaishan','','区','420203','0714',2),(2008,'下陆',291,'291,2008','x','xl','xialu','','区','420204','0714',3),(2009,'铁山',291,'291,2009','t','ts','tieshan','','区','420205','0714',4),(2010,'阳新',291,'291,2010','y','yx','yangxin','','县','420222','0714',5),(2011,'大冶',291,'291,2011','d','dy','daye','','市','420281','0714',6),(2012,'茅箭',292,'292,2012','m','mj','maojian','','区','420302','0719',1),(2013,'张湾',292,'292,2013','z','zw','zhangwan','','区','420303','0719',2),(2014,'郧县',292,'292,2014','y','yx','yunxian','','','420321','0719',3),(2015,'郧西',292,'292,2015','y','yx','yunxi','','县','420322','0719',4),(2016,'竹山',292,'292,2016','z','zs','zhushan','','县','420323','0719',5),(2017,'竹溪',292,'292,2017','z','zx','zhuxi','','县','420324','0719',6),(2018,'房县',292,'292,2018','f','fx','fangxian','','','420325','0719',7),(2019,'丹江口',292,'292,2019','d','djk','danjiangkou','','市','420381','0719',8),(2020,'西陵',293,'293,2020','x','xl','xiling','','区','420502','0717',1),(2021,'伍家岗',293,'293,2021','w','wjg','wujiagang','','区','420503','0717',2),(2022,'点军',293,'293,2022','d','dj','dianjun','','区','420504','0717',3),(2023,'虢亭',293,'293,2023','g','gt','guoting','','区','420505','0717',4),(2024,'夷陵',293,'293,2024','y','yl','yiling','','区','420506','0717',5),(2025,'远安',293,'293,2025','y','ya','yuanan','','县','420525','0717',6),(2026,'兴山',293,'293,2026','x','xs','xingshan','','县','420526','0717',7),(2027,'秭归',293,'293,2027','z','zg','zigui','','县','420527','0717',8),(2028,'长阳',293,'293,2028','z','zy','zhangyang','土家族','自治县','420528','0717',9),(2029,'五峰',293,'293,2029','w','wf','wufeng','土家族','自治县','420529','0717',10),(2030,'宜都',293,'293,2030','y','yd','yidou','','市','420581','0717',11),(2031,'当阳',293,'293,2031','d','dy','dangyang','','市','420582','0717',12),(2032,'枝江',293,'293,2032','z','zj','zhijiang','','市','420583','0717',13),(2033,'襄城',294,'294,2033','x','xc','xiangcheng','','区','420602','0710',1),(2034,'樊城',294,'294,2034','f','fc','fancheng','','区','420606','0710',2),(2035,'襄州',294,'294,2035','x','xz','xiangzhou','','区','420607','0710',3),(2036,'南漳',294,'294,2036','n','nz','nanzhang','','县','420624','0710',4),(2037,'谷城',294,'294,2037','g','gc','gucheng','','县','420625','0710',5),(2038,'保康',294,'294,2038','b','bk','baokang','','县','420626','0710',6),(2039,'老河口',294,'294,2039','l','lhk','laohekou','','市','420682','0710',7),(2040,'枣阳',294,'294,2040','z','zy','zaoyang','','市','420683','0710',8),(2041,'宜城',294,'294,2041','y','yc','yicheng','','市','420684','0710',9),(2042,'粱子湖',295,'295,2042','l','lzh','liangzihu','','区','420702','',1),(2043,'华容',295,'295,2043','h','hr','huarong','','区','420703','',2),(2044,'鄂城',295,'295,2044','e','ec','echeng','','区','420704','',3),(2045,'东宝',296,'296,2045','d','db','dongbao','','区','420802','0724',1),(2046,'掇刀',296,'296,2046','d','dd','duodao','','区','420804','0724',2),(2047,'京山',296,'296,2047','j','js','jingshan','','县','420821','0724',3),(2048,'沙洋',296,'296,2048','s','sy','shayang','','县','420822','0724',4),(2049,'钟祥',296,'296,2049','z','zx','zhongxiang','','市','420881','0724',5),(2050,'孝南',297,'297,2050','x','xn','xiaonan','','区','420902','0712',1),(2051,'大悟',297,'297,2051','d','dw','dawu','','县','420922','0712',2),(2052,'云梦',297,'297,2052','y','ym','yunmeng','','县','420923','0712',3),(2053,'应城',297,'297,2053','y','yc','yingcheng','','市','420981','0712',4),(2054,'安陆',297,'297,2054','a','al','anlu','','市','420982','0712',5),(2055,'汉川',297,'297,2055','h','hc','hanchuan','','市','420984','0712',6),(2056,'沙市',298,'298,2056','s','ss','shashi','','区','421002','0716',1),(2057,'荆州',298,'298,2057','j','jz','jingzhou','','区','421003','0716',2),(2058,'公安',298,'298,2058','g','ga','gongan','','县','421022','0716',3),(2059,'监利',298,'298,2059','j','jl','jianli','','县','421023','0716',4),(2060,'江陵',298,'298,2060','j','jl','jiangling','','县','421024','0716',5),(2061,'石首',298,'298,2061','s','ss','shishou','','市','421081','0716',6),(2062,'洪湖',298,'298,2062','h','hh','honghu','','市','421083','0716',7),(2063,'松滋',298,'298,2063','s','sz','songzi','','市','421087','0716',8),(2064,'黄州',299,'299,2064','h','hz','huangzhou','','区','421102','0713',1),(2065,'团风',299,'299,2065','t','tf','tuanfeng','','县','421121','0713',2),(2066,'红安',299,'299,2066','h','ha','hongan','','县','421122','0713',3),(2067,'罗田',299,'299,2067','l','lt','luotian','','县','421123','0713',4),(2068,'英山',299,'299,2068','y','ys','yingshan','','县','421124','0713',5),(2069,'浠水',299,'299,2069','x','xs','xishui','','县','421125','0713',6),(2070,'蕲春',299,'299,2070','q','qc','qichun','','县','421126','0713',7),(2071,'黄梅',299,'299,2071','h','hm','huangmei','','县','421127','0713',8),(2072,'麻城',299,'299,2072','m','mc','macheng','','市','421181','0713',9),(2073,'武穴',299,'299,2073','w','wx','wuxue','','市','421182','0713',10),(2074,'咸安',300,'300,2074','x','xa','xianan','','区','421202','0715',1),(2075,'嘉鱼',300,'300,2075','j','jy','jiayu','','县','421221','0715',2),(2076,'通城',300,'300,2076','t','tc','tongcheng','','县','421222','0715',3),(2077,'崇阳',300,'300,2077','c','cy','chongyang','','县','421223','0715',4),(2078,'通山',300,'300,2078','t','ts','tongshan','','县','421224','0715',5),(2079,'赤壁',300,'300,2079','c','cb','chibi','','市','421281','0715',6),(2080,'曾都',301,'301,2080','c','cd','cengdou','','区','421303','0722',1),(2081,'随县',301,'301,2081','s','sx','suixian','','','421321','0722',2),(2082,'广水',301,'301,2082','g','gs','guangshui','','市','421381','0722',3),(2083,'恩施',302,'302,2083','e','es','enshi','','市','422801','0718',1),(2084,'利川',302,'302,2084','l','lc','lichuan','','市','422802','0718',2),(2085,'建始',302,'302,2085','j','js','jianshi','','县','422822','0718',3),(2086,'巴东',302,'302,2086','b','bd','badong','','县','422823','0718',4),(2087,'宣恩',302,'302,2087','x','xe','xuanen','','县','422825','0718',5),(2088,'咸丰',302,'302,2088','x','xf','xianfeng','','县','422826','0718',6),(2089,'来凤',302,'302,2089','l','lf','laifeng','','县','422827','0718',7),(2090,'鹤峰',302,'302,2090','h','hf','hefeng','','县','422828','0718',8),(2091,'芙蓉',307,'307,2091','f','fr','furong','','区','430102','0731',1),(2092,'天心',307,'307,2092','t','tx','tianxin','','区','430103','0731',2),(2093,'岳麓',307,'307,2093','y','yl','yuelu','','区','430104','0731',3),(2094,'开福',307,'307,2094','k','kf','kaifu','','区','430105','0731',4),(2095,'雨花',307,'307,2095','y','yh','yuhua','','区','430111','0731',5),(2096,'望城',307,'307,2096','w','wc','wangcheng','','区','430112','0731',6),(2097,'长沙',307,'307,2097','z','zs','zhangsha','','县','430121','0731',7),(2098,'宁乡',307,'307,2098','n','nx','ningxiang','','县','430124','0731',8),(2099,'浏阳',307,'307,2099','l','ly','liuyang','','市','430181','0731',9),(2100,'荷塘',308,'308,2100','h','ht','hetang','','区','430202','0731',1),(2101,'芦淞',308,'308,2101','l','ls','lusong','','区','430203','0731',2),(2102,'石峰',308,'308,2102','s','sf','shifeng','','区','430204','0731',3),(2103,'天元',308,'308,2103','t','ty','tianyuan','','区','430211','0731',4),(2104,'株洲',308,'308,2104','z','zz','zhuzhou','','县','430221','0731',5),(2105,'攸县',308,'308,2105','y','yx','youxian','','','430223','0731',6),(2106,'茶陵',308,'308,2106','c','cl','chaling','','县','430224','0731',7),(2107,'炎陵',308,'308,2107','y','yl','yanling','','县','430225','0731',8),(2108,'醴陵',308,'308,2108','l','ll','liling','','市','430281','0731',9),(2109,'雨湖',309,'309,2109','y','yh','yuhu','','区','430302','0731',1),(2110,'岳塘',309,'309,2110','y','yt','yuetang','','区','430304','0731',2),(2111,'湘潭',309,'309,2111','x','xt','xiangtan','','县','430321','0731',3),(2112,'湘乡',309,'309,2112','x','xx','xiangxiang','','市','430381','0731',4),(2113,'韶山',309,'309,2113','s','ss','shaoshan','','市','430382','0731',5),(2114,'珠晖',310,'310,2114','z','zh','zhuhui','','区','430405','0734',1),(2115,'雁峰',310,'310,2115','y','yf','yanfeng','','区','430406','0734',2),(2116,'石鼓',310,'310,2116','s','sg','shigu','','区','430407','0734',3),(2117,'蒸湘',310,'310,2117','z','zx','zhengxiang','','区','430408','0734',4),(2118,'南岳',310,'310,2118','n','ny','nanyue','','区','430412','0734',5),(2119,'衡阳',310,'310,2119','h','hy','hengyang','','县','430421','0734',6),(2120,'衡南',310,'310,2120','h','hn','hengnan','','县','430422','0734',7),(2121,'衡山',310,'310,2121','h','hs','hengshan','','县','430423','0734',8),(2122,'衡东',310,'310,2122','h','hd','hengdong','','县','430424','0734',9),(2123,'祁东',310,'310,2123','q','qd','qidong','','县','430426','0734',10),(2124,'耒阳',310,'310,2124','l','ly','leiyang','','市','430481','0734',11),(2125,'常宁',310,'310,2125','c','cn','changning','','市','430482','0734',12),(2126,'双清',311,'311,2126','s','sq','shuangqing','','区','430502','0739',1),(2127,'大祥',311,'311,2127','d','dx','daxiang','','区','430503','0739',2),(2128,'北塔',311,'311,2128','b','bt','beita','','区','430511','0739',3),(2129,'邵东',311,'311,2129','s','sd','shaodong','','县','430521','0739',4),(2130,'新邵',311,'311,2130','x','xs','xinshao','','县','430522','0739',5),(2131,'邵阳',311,'311,2131','s','sy','shaoyang','','县','430523','0739',6),(2132,'隆回',311,'311,2132','l','lh','longhui','','县','430524','0739',7),(2133,'洞口',311,'311,2133','d','dk','dongkou','','县','430525','0739',8),(2134,'绥宁',311,'311,2134','s','sn','suining','','县','430527','0739',9),(2135,'新宁',311,'311,2135','x','xn','xinning','','县','430528','0739',10),(2136,'城步',311,'311,2136','c','cb','chengbu','苗族','自治县','430529','0739',11),(2137,'武冈',311,'311,2137','w','wg','wugang','','市','430581','0739',12),(2138,'岳阳楼',312,'312,2138','y','yyl','yueyanglou','','区','430602','0730',1),(2139,'云溪',312,'312,2139','y','yx','yunxi','','区','430603','0730',2),(2140,'君山',312,'312,2140','j','js','junshan','','区','430611','0730',3),(2141,'岳阳',312,'312,2141','y','yy','yueyang','','县','430621','0730',4),(2142,'华容',312,'312,2142','h','hr','huarong','','县','430623','0730',5),(2143,'湘阴',312,'312,2143','x','xy','xiangyin','','县','430624','0730',6),(2144,'平江',312,'312,2144','p','pj','pingjiang','','县','430626','0730',7),(2145,'汨罗',312,'312,2145','m','ml','miluo','','市','430681','0730',8),(2146,'临湘',312,'312,2146','l','lx','linxiang','','市','430682','0730',9),(2147,'武陵',313,'313,2147','w','wl','wuling','','区','430702','0736',1),(2148,'鼎城',313,'313,2148','d','dc','dingcheng','','区','430703','0736',2),(2149,'安乡',313,'313,2149','a','ax','anxiang','','县','430721','0736',3),(2150,'汉寿',313,'313,2150','h','hs','hanshou','','县','430722','0736',4),(2151,'澧县',313,'313,2151','l','lx','lixian','','','430723','0736',5),(2152,'临澧',313,'313,2152','l','ll','linli','','县','430724','0736',6),(2153,'桃源',313,'313,2153','t','ty','taoyuan','','县','430725','0736',7),(2154,'石门',313,'313,2154','s','sm','shimen','','县','430726','0736',8),(2155,'津市',313,'313,2155','j','js','jinshi','','市','430781','0736',9),(2156,'永定',314,'314,2156','y','yd','yongding','','区','430802','0744',1),(2157,'武陵源',314,'314,2157','w','wly','wulingyuan','','区','430811','0744',2),(2158,'慈利',314,'314,2158','c','cl','cili','','县','430821','0744',3),(2159,'桑植',314,'314,2159','s','sz','sangzhi','','县','430822','0744',4),(2160,'资阳',315,'315,2160','z','zy','ziyang','','区','430902','0737',1),(2161,'赫山',315,'315,2161','h','hs','heshan','','区','430903','0737',2),(2162,'南县',315,'315,2162','n','nx','nanxian','','','430921','0737',3),(2163,'桃江',315,'315,2163','t','tj','taojiang','','县','430922','0737',4),(2164,'安化',315,'315,2164','a','ah','anhua','','县','430923','0737',5),(2165,'沅江',315,'315,2165','y','yj','yuanjiang','','市','430981','0737',6),(2166,'北湖',316,'316,2166','b','bh','beihu','','区','431002','0735',1),(2167,'苏仙',316,'316,2167','s','sx','suxian','','区','431003','0735',2),(2168,'桂阳',316,'316,2168','g','gy','guiyang','','县','431021','0735',3),(2169,'宜章',316,'316,2169','y','yz','yizhang','','县','431022','0735',4),(2170,'永兴',316,'316,2170','y','yx','yongxing','','县','431023','0735',5),(2171,'嘉禾',316,'316,2171','j','jh','jiahe','','县','431024','0735',6),(2172,'临武',316,'316,2172','l','lw','linwu','','县','431025','0735',7),(2173,'汝城',316,'316,2173','r','rc','rucheng','','县','431026','0735',8),(2174,'桂东',316,'316,2174','g','gd','guidong','','县','431027','0735',9),(2175,'安仁',316,'316,2175','a','ar','anren','','县','431028','0735',10),(2176,'资兴',316,'316,2176','z','zx','zixing','','市','431081','0735',11),(2177,'零陵',317,'317,2177','l','ll','lingling','','区','431102','0746',1),(2178,'冷水滩',317,'317,2178','l','lst','lengshuitan','','区','431103','0746',2),(2179,'祁阳',317,'317,2179','q','qy','qiyang','','县','431121','0746',3),(2180,'东安',317,'317,2180','d','da','dongan','','县','431122','0746',4),(2181,'双牌',317,'317,2181','s','sp','shuangpai','','县','431123','0746',5),(2182,'道县',317,'317,2182','d','dx','daoxian','','','431124','0746',6),(2183,'江永',317,'317,2183','j','jy','jiangyong','','县','431125','0746',7),(2184,'宁远',317,'317,2184','n','ny','ningyuan','','县','431126','0746',8),(2185,'蓝山',317,'317,2185','l','ls','lanshan','','县','431127','0746',9),(2186,'新田',317,'317,2186','x','xt','xintian','','县','431128','0746',10),(2187,'江华',317,'317,2187','j','jh','jianghua','瑶族','自治县','431129','0746',11),(2188,'鹤城',318,'318,2188','h','hc','hecheng','','区','431202','0745',1),(2189,'中方',318,'318,2189','z','zf','zhongfang','','县','431221','0745',2),(2190,'沅陵',318,'318,2190','y','yl','yuanling','','县','431222','0745',3),(2191,'辰溪',318,'318,2191','c','cx','chenxi','','县','431223','0745',4),(2192,'溆浦',318,'318,2192','x','xp','xupu','','县','431224','0745',5),(2193,'会同',318,'318,2193','h','ht','huitong','','县','431225','0745',6),(2194,'麻阳',318,'318,2194','m','my','mayang','苗族','自治县','431226','0745',7),(2195,'新晃',318,'318,2195','x','xh','xinhuang','侗族','自治县','431227','0745',8),(2196,'芷江',318,'318,2196','z','zj','zhijiang','侗族','自治县','431228','0745',9),(2197,'靖州',318,'318,2197','j','jz','jingzhou','苗族侗族','自治县','431229','0745',10),(2198,'通道',318,'318,2198','t','td','tongdao','侗族','自治县','431230','0745',11),(2199,'洪江',318,'318,2199','h','hj','hongjiang','','市','431281','0745',12),(2200,'娄星',319,'319,2200','l','lx','louxing','','区','431302','0738',1),(2201,'双峰',319,'319,2201','s','sf','shuangfeng','','县','431321','0738',2),(2202,'新化',319,'319,2202','x','xh','xinhua','','县','431322','0738',3),(2203,'冷水江',319,'319,2203','l','lsj','lengshuijiang','','市','431381','0738',4),(2204,'涟源',319,'319,2204','l','ly','lianyuan','','市','431382','0738',5),(2205,'吉首',320,'320,2205','j','js','jishou','','市','433101','0743',1),(2206,'泸溪',320,'320,2206','l','lx','luxi','','县','433122','0743',2),(2207,'凤凰',320,'320,2207','f','fh','fenghuang','','县','433123','0743',3),(2208,'花垣',320,'320,2208','h','hy','huayuan','','县','433124','0743',4),(2209,'保靖',320,'320,2209','b','bj','baojing','','县','433125','0743',5),(2210,'古丈',320,'320,2210','g','gz','guzhang','','县','433126','0743',6),(2211,'永顺',320,'320,2211','y','ys','yongshun','','县','433127','0743',7),(2212,'龙山',320,'320,2212','l','ls','longshan','','县','433130','0743',8),(2213,'荔湾',321,'321,2213','l','lw','liwan','','区','440103','020',1),(2214,'越秀',321,'321,2214','y','yx','yuexiu','','区','440104','020',2),(2215,'海珠',321,'321,2215','h','hz','haizhu','','区','440105','020',3),(2216,'天河',321,'321,2216','t','th','tianhe','','区','440106','020',4),(2217,'白云',321,'321,2217','b','by','baiyun','','区','440111','020',5),(2218,'黄埔',321,'321,2218','h','hp','huangpu','','区','440112','020',6),(2219,'番禺',321,'321,2219','f','fy','fanyu','','区','440113','020',7),(2220,'花都',321,'321,2220','h','hd','huadou','','区','440114','020',8),(2221,'南沙',321,'321,2221','n','ns','nansha','','区','440115','020',9),(2223,'增城',321,'321,2223','z','zc','zengcheng','','区','440183','020',11),(2224,'从化',321,'321,2224','c','ch','conghua','','区','440184','020',12),(2225,'武江',322,'322,2225','w','wj','wujiang','','区','440203','0751',1),(2226,'浈江',322,'322,2226','z','zj','zhenjiang','','区','440204','0751',2),(2227,'曲江',322,'322,2227','q','qj','qujiang','','区','440205','0751',3),(2228,'始兴',322,'322,2228','s','sx','shixing','','县','440222','0751',4),(2229,'仁化',322,'322,2229','r','rh','renhua','','县','440224','0751',5),(2230,'翁源',322,'322,2230','w','wy','wengyuan','','县','440229','0751',6),(2231,'乳源',322,'322,2231','r','ry','ruyuan','瑶族','自治县','440232','0751',7),(2232,'新丰',322,'322,2232','x','xf','xinfeng','','县','440233','0751',8),(2233,'乐昌',322,'322,2233','l','lc','lechang','','市','440281','0751',9),(2234,'南雄',322,'322,2234','n','nx','nanxiong','','市','440282','0751',10),(2235,'罗湖',323,'323,2235','l','lh','luohu','','区','440303','0755',1),(2236,'福田',323,'323,2236','f','ft','futian','','区','440304','0755',2),(2237,'南山',323,'323,2237','n','ns','nanshan','','区','440305','0755',3),(2238,'宝安',323,'323,2238','b','ba','baoan','','区','440306','0755',4),(2239,'龙岗',323,'323,2239','l','lg','longgang','','区','440307','0755',5),(2240,'盐田',323,'323,2240','y','yt','yantian','','区','440308','0755',6),(2241,'香洲',324,'324,2241','x','xz','xiangzhou','','区','440402','0756',1),(2242,'斗门',324,'324,2242','d','dm','doumen','','区','440403','0756',2),(2243,'金湾',324,'324,2243','j','jw','jinwan','','区','440404','0756',3),(2244,'龙湖',325,'325,2244','l','lh','longhu','','区','440507','0754',1),(2245,'金平',325,'325,2245','j','jp','jinping','','区','440511','0754',2),(2246,'濠江',325,'325,2246','h','hj','haojiang','','区','440512','0754',3),(2247,'潮阳',325,'325,2247','c','cy','chaoyang','','区','440513','0754',4),(2248,'潮南',325,'325,2248','c','cn','chaonan','','区','440514','0754',5),(2249,'澄海',325,'325,2249','c','ch','chenghai','','区','440515','0754',6),(2250,'南澳',325,'325,2250','n','na','nanao','','县','440523','0754',7),(2251,'禅城',326,'326,2251','s','sc','shancheng','','区','440604','0757',1),(2252,'南海',326,'326,2252','n','nh','nanhai','','区','440605','0757',2),(2253,'顺德',326,'326,2253','s','sd','shunde','','区','440606','0757',3),(2254,'三水',326,'326,2254','s','ss','sanshui','','区','440607','0757',4),(2255,'高明',326,'326,2255','g','gm','gaoming','','区','440608','0757',5),(2256,'蓬江',327,'327,2256','p','pj','pengjiang','','区','440703','0750',1),(2257,'江海',327,'327,2257','j','jh','jianghai','','区','440704','0750',2),(2258,'新会',327,'327,2258','x','xh','xinhui','','区','440705','0750',3),(2259,'台山',327,'327,2259','t','ts','taishan','','市','440781','0750',4),(2260,'开平',327,'327,2260','k','kp','kaiping','','市','440783','0750',5),(2261,'鹤山',327,'327,2261','h','hs','heshan','','市','440784','0750',6),(2262,'恩平',327,'327,2262','e','ep','enping','','市','440785','0750',7),(2263,'赤坎',328,'328,2263','c','ck','chikan','','区','440802','0759',1),(2264,'霞山',328,'328,2264','x','xs','xiashan','','区','440803','0759',2),(2265,'坡头',328,'328,2265','p','pt','potou','','区','440804','0759',3),(2266,'麻章',328,'328,2266','m','mz','mazhang','','区','440811','0759',4),(2267,'遂溪',328,'328,2267','s','sx','suixi','','县','440823','0759',5),(2268,'徐闻',328,'328,2268','x','xw','xuwen','','县','440825','0759',6),(2269,'廉江',328,'328,2269','l','lj','lianjiang','','市','440881','0759',7),(2270,'雷州',328,'328,2270','l','lz','leizhou','','市','440882','0759',8),(2271,'吴川',328,'328,2271','w','wc','wuchuan','','市','440883','0759',9),(2272,'茂南',329,'329,2272','m','mn','maonan','','区','440902','0668',1),(2273,'茂港',329,'329,2273','m','mg','maogang','','区','440903','0668',2),(2274,'电白',329,'329,2274','d','db','dianbai','','县','440923','0668',3),(2275,'高州',329,'329,2275','g','gz','gaozhou','','市','440981','0668',4),(2276,'化州',329,'329,2276','h','hz','huazhou','','市','440982','0668',5),(2277,'信宜',329,'329,2277','x','xy','xinyi','','市','440983','0668',6),(2278,'端州',330,'330,2278','d','dz','duanzhou','','区','441202','0758',1),(2279,'鼎湖',330,'330,2279','d','dh','dinghu','','区','441203','0758',2),(2280,'广宁',330,'330,2280','g','gn','guangning','','县','441223','0758',3),(2281,'怀集',330,'330,2281','h','hj','huaiji','','县','441224','0758',4),(2282,'封开',330,'330,2282','f','fk','fengkai','','县','441225','0758',5),(2283,'德庆',330,'330,2283','d','dq','deqing','','县','441226','0758',6),(2284,'高要',330,'330,2284','g','gy','gaoyao','','市','441283','0758',7),(2285,'四会',330,'330,2285','s','sh','sihui','','市','441284','0758',8),(2286,'惠城',331,'331,2286','h','hc','huicheng','','区','441302','0752',1),(2287,'惠阳',331,'331,2287','h','hy','huiyang','','区','441303','0752',2),(2288,'博罗',331,'331,2288','b','bl','boluo','','县','441322','0752',3),(2289,'惠东',331,'331,2289','h','hd','huidong','','县','441323','0752',4),(2290,'龙门',331,'331,2290','l','lm','longmen','','县','441324','0752',5),(2291,'梅江',332,'332,2291','m','mj','meijiang','','区','441402','0753',1),(2292,'梅县',332,'332,2292','m','mx','meixian','','','441421','0753',2),(2293,'大埔',332,'332,2293','d','dp','dapu','','县','441422','0753',3),(2294,'丰顺',332,'332,2294','f','fs','fengshun','','县','441423','0753',4),(2295,'五华',332,'332,2295','w','wh','wuhua','','县','441424','0753',5),(2296,'平远',332,'332,2296','p','py','pingyuan','','县','441426','0753',6),(2297,'蕉岭',332,'332,2297','j','jl','jiaoling','','县','441427','0753',7),(2298,'兴宁',332,'332,2298','x','xn','xingning','','市','441481','0753',8),(2299,'城区',333,'333,2299','c','cq','chengqu','','区','441502','0660',1),(2300,'海丰',333,'333,2300','h','hf','haifeng','','县','441521','0660',2),(2301,'陆河',333,'333,2301','l','lh','luhe','','县','441523','0660',3),(2302,'陆丰',333,'333,2302','l','lf','lufeng','','市','441581','0660',4),(2303,'源城',334,'334,2303','y','yc','yuancheng','','区','441602','0762',1),(2304,'紫金',334,'334,2304','z','zj','zijin','','县','441621','0762',2),(2305,'龙川',334,'334,2305','l','lc','longchuan','','县','441622','0762',3),(2306,'连平',334,'334,2306','l','lp','lianping','','县','441623','0762',4),(2307,'和平',334,'334,2307','h','hp','heping','','县','441624','0762',5),(2308,'东源',334,'334,2308','d','dy','dongyuan','','县','441625','0762',6),(2309,'江城',335,'335,2309','j','jc','jiangcheng','','区','441702','0662',1),(2310,'阳西',335,'335,2310','y','yx','yangxi','','县','441721','0662',2),(2311,'阳东',335,'335,2311','y','yd','yangdong','','县','441723','0662',3),(2312,'阳春',335,'335,2312','y','yc','yangchun','','市','441781','0662',4),(2313,'清城',336,'336,2313','q','qc','qingcheng','','区','441802','0763',1),(2314,'佛冈',336,'336,2314','f','fg','fogang','','县','441821','0763',2),(2315,'阳山',336,'336,2315','y','ys','yangshan','','县','441823','0763',3),(2316,'连山',336,'336,2316','l','ls','lianshan','壮族瑶族','自治县','441825','0763',4),(2317,'连南',336,'336,2317','l','ln','liannan','瑶族','自治县','441826','0763',5),(2318,'清新',336,'336,2318','q','qx','qingxin','','县','441827','0763',6),(2319,'英德',336,'336,2319','y','yd','yingde','','市','441881','0763',7),(2320,'连州',336,'336,2320','l','lz','lianzhou','','市','441882','0763',8),(2321,'湘桥',339,'339,2321','x','xq','xiangqiao','','区','445102','0768',1),(2322,'潮安',339,'339,2322','c','ca','chaoan','','县','445121','0768',2),(2323,'饶平',339,'339,2323','r','rp','raoping','','县','445122','0768',3),(2324,'榕城',340,'340,2324','r','rc','rongcheng','','区','445202','0663',1),(2325,'揭东',340,'340,2325','j','jd','jiedong','','县','445221','0663',2),(2326,'揭西',340,'340,2326','j','jx','jiexi','','县','445222','0663',3),(2327,'惠来',340,'340,2327','h','hl','huilai','','县','445224','0663',4),(2328,'普宁',340,'340,2328','p','pn','puning','','市','445281','0663',5),(2329,'云城',341,'341,2329','y','yc','yuncheng','','区','445302','0766',1),(2330,'新兴',341,'341,2330','x','xx','xinxing','','县','445321','0766',2),(2331,'郁南',341,'341,2331','y','yn','yunan','','县','445322','0766',3),(2332,'云安',341,'341,2332','y','ya','yunan','','县','445323','0766',4),(2333,'罗定',341,'341,2333','l','ld','luoding','','市','445381','0766',5),(2334,'兴宁',342,'342,2334','x','xn','xingning','','区','450102','0771',1),(2335,'青秀',342,'342,2335','q','qx','qingxiu','','区','450103','0771',2),(2336,'江南',342,'342,2336','j','jn','jiangnan','','区','450105','0771',3),(2337,'西乡塘',342,'342,2337','x','xxt','xixiangtang','','区','450107','0771',4),(2338,'良庆',342,'342,2338','l','lq','liangqing','','区','450108','0771',5),(2339,'邕宁',342,'342,2339','y','yn','yongning','','区','450109','0771',6),(2340,'武鸣',342,'342,2340','w','wm','wuming','','县','450122','0771',7),(2341,'隆安',342,'342,2341','l','la','longan','','县','450123','0771',8),(2342,'马山',342,'342,2342','m','ms','mashan','','县','450124','0771',9),(2343,'上林',342,'342,2343','s','sl','shanglin','','县','450125','0771',10),(2344,'宾阳',342,'342,2344','b','by','binyang','','县','450126','0771',11),(2345,'横县',342,'342,2345','h','hx','hengxian','','','450127','0771',12),(2346,'城中',343,'343,2346','c','cz','chengzhong','','区','450202','0772',1),(2347,'鱼峰',343,'343,2347','y','yf','yufeng','','区','450203','0772',2),(2348,'柳南',343,'343,2348','l','ln','liunan','','区','450204','0772',3),(2349,'柳北',343,'343,2349','l','lb','liubei','','区','450205','0772',4),(2350,'柳江',343,'343,2350','l','lj','liujiang','','县','450221','0772',5),(2351,'柳城',343,'343,2351','l','lc','liucheng','','县','450222','0772',6),(2352,'鹿寨',343,'343,2352','l','lz','luzhai','','县','450223','0772',7),(2353,'融安',343,'343,2353','r','ra','rongan','','县','450224','0772',8),(2354,'融水',343,'343,2354','r','rs','rongshui','苗族','自治县','450225','0772',9),(2355,'三江',343,'343,2355','s','sj','sanjiang','侗族','自治县','450226','0772',10),(2356,'秀峰',344,'344,2356','x','xf','xiufeng','','区','450302','0773',1),(2357,'叠彩',344,'344,2357','d','dc','diecai','','区','450303','0773',2),(2358,'象山',344,'344,2358','x','xs','xiangshan','','区','450304','0773',3),(2359,'七星',344,'344,2359','q','qx','qixing','','区','450305','0773',4),(2360,'雁山',344,'344,2360','y','ys','yanshan','','区','450311','0773',5),(2361,'阳朔',344,'344,2361','y','ys','yangshuo','','县','450321','0773',6),(2362,'临桂',344,'344,2362','l','lg','lingui','','县','450322','0773',7),(2363,'灵川',344,'344,2363','l','lc','lingchuan','','县','450323','0773',8),(2364,'全州',344,'344,2364','q','qz','quanzhou','','县','450324','0773',9),(2365,'兴安',344,'344,2365','x','xa','xingan','','县','450325','0773',10),(2366,'永福',344,'344,2366','y','yf','yongfu','','县','450326','0773',11),(2367,'灌阳',344,'344,2367','g','gy','guanyang','','县','450327','0773',12),(2368,'龙胜',344,'344,2368','l','ls','longsheng','各族','自治县','450328','0773',13),(2369,'资源',344,'344,2369','z','zy','ziyuan','','县','450329','0773',14),(2370,'平乐',344,'344,2370','p','pl','pingle','','县','450330','0773',15),(2371,'荔浦',344,'344,2371','l','lp','lipu','','县','450331','0773',16),(2372,'恭城',344,'344,2372','g','gc','gongcheng','瑶族','自治县','450332','0773',17),(2373,'龙圩',345,'345,2373','l','lw','longwei','','区','','0774',1),(2374,'万秀',345,'345,2374','w','wx','wanxiu','','区','450403','0774',2),(2375,'长洲',345,'345,2375','z','zz','zhangzhou','','区','450405','0774',3),(2376,'苍梧',345,'345,2376','c','cw','cangwu','','县','450421','0774',4),(2377,'藤县',345,'345,2377','t','tx','tengxian','','','450422','0774',5),(2378,'蒙山',345,'345,2378','m','ms','mengshan','','县','450423','0774',6),(2379,'岑溪',345,'345,2379','c','cx','cenxi','','市','450481','0774',7),(2380,'海城',346,'346,2380','h','hc','haicheng','','区','450502','0779',1),(2381,'银海',346,'346,2381','y','yh','yinhai','','区','450503','0779',2),(2382,'铁山港',346,'346,2382','t','tsg','tieshangang','','区','450512','0779',3),(2383,'合浦',346,'346,2383','h','hp','hepu','','县','450521','0779',4),(2384,'港口',347,'347,2384','g','gk','gangkou','','区','450602','0770',1),(2385,'防城',347,'347,2385','f','fc','fangcheng','','区','450603','0770',2),(2386,'上思',347,'347,2386','s','ss','shangsi','','县','450621','0770',3),(2387,'东兴',347,'347,2387','d','dx','dongxing','','市','450681','0770',4),(2388,'钦南',348,'348,2388','q','qn','qinnan','','区','450702','0777',1),(2389,'钦北',348,'348,2389','q','qb','qinbei','','区','450703','0777',2),(2390,'灵山',348,'348,2390','l','ls','lingshan','','县','450721','0777',3),(2391,'浦北',348,'348,2391','p','pb','pubei','','县','450722','0777',4),(2392,'港北',349,'349,2392','g','gb','gangbei','','区','450802','0775',1),(2393,'港南',349,'349,2393','g','gn','gangnan','','区','450803','0775',2),(2394,'覃塘',349,'349,2394','t','tt','tantang','','区','450804','0775',3),(2395,'桂平',349,'349,2395','g','gp','guiping','','市','450821','0775',4),(2396,'平南',349,'349,2396','p','pn','pingnan','','县','450881','0775',5),(2397,'玉州',350,'350,2397','y','yz','yuzhou','','区','450902','0775',1),(2398,'容县',350,'350,2398','r','rx','rongxian','','','450921','0775',2),(2399,'陆川',350,'350,2399','l','lc','luchuan','','县','450922','0775',3),(2400,'博白',350,'350,2400','b','bb','bobai','','县','450923','0775',4),(2401,'兴业',350,'350,2401','x','xy','xingye','','县','450924','0775',5),(2402,'北流',350,'350,2402','b','bl','beiliu','','市','450981','0775',6),(2403,'右江',351,'351,2403','y','yj','youjiang','','区','451002','0776',1),(2404,'田阳',351,'351,2404','t','ty','tianyang','','县','451021','0776',2),(2405,'田东',351,'351,2405','t','td','tiandong','','县','451022','0776',3),(2406,'平果',351,'351,2406','p','pg','pingguo','','县','451023','0776',4),(2407,'德保',351,'351,2407','d','db','debao','','县','451024','0776',5),(2408,'靖西',351,'351,2408','j','jx','jingxi','','县','451025','0776',6),(2409,'那坡',351,'351,2409','n','np','neipo','','县','451026','0776',7),(2410,'凌云',351,'351,2410','l','ly','lingyun','','县','451027','0776',8),(2411,'乐业',351,'351,2411','l','ly','leye','','县','451028','0776',9),(2412,'田林',351,'351,2412','t','tl','tianlin','','县','451029','0776',10),(2413,'西林',351,'351,2413','x','xl','xilin','','县','451030','0776',11),(2414,'隆林',351,'351,2414','l','ll','longlin','各族','自治县','451031','0776',12),(2415,'八步',352,'352,2415','b','bb','babu','','区','451102','0774',1),(2416,'昭平',352,'352,2416','z','zp','zhaoping','','县','451121','0774',2),(2417,'钟山',352,'352,2417','z','zs','zhongshan','','县','451122','0774',3),(2418,'富川',352,'352,2418','f','fc','fuchuan','瑶族','自治县','451123','0774',4),(2419,'金城江',353,'353,2419','j','jcj','jinchengjiang','','区','451202','0778',1),(2420,'南丹',353,'353,2420','n','nd','nandan','','县','451221','0778',2),(2421,'天峨',353,'353,2421','t','te','tiane','','县','451222','0778',3),(2422,'凤山',353,'353,2422','f','fs','fengshan','','县','451223','0778',4),(2423,'东兰',353,'353,2423','d','dl','donglan','','县','451224','0778',5),(2424,'罗城',353,'353,2424','l','lc','luocheng','仫佬族','自治县','451225','0778',6),(2425,'环江',353,'353,2425','h','hj','huanjiang','毛南族','自治县','451226','0778',7),(2426,'巴马',353,'353,2426','b','bm','bama','瑶族','自治县','451227','0778',8),(2427,'都安',353,'353,2427','d','da','douan','瑶族','自治县','451228','0778',9),(2428,'大化',353,'353,2428','d','dh','dahua','瑶族','自治县','451229','0778',10),(2429,'宜州',353,'353,2429','y','yz','yizhou','','市','451281','0778',11),(2430,'兴宾',354,'354,2430','x','xb','xingbin','','区','451302','0772',1),(2431,'忻城',354,'354,2431','x','xc','xincheng','','县','451321','0772',2),(2432,'象州',354,'354,2432','x','xz','xiangzhou','','县','451322','0772',3),(2433,'武宣',354,'354,2433','w','wx','wuxuan','','县','451323','0772',4),(2434,'金秀',354,'354,2434','j','jx','jinxiu','瑶族','自治县','451324','0772',5),(2435,'合山',354,'354,2435','h','hs','heshan','','市','451381','0772',6),(2436,'江州',355,'355,2436','j','jz','jiangzhou','','区','451402','0771',1),(2437,'扶绥',355,'355,2437','f','fs','fusui','','县','451421','0771',2),(2438,'宁明',355,'355,2438','n','nm','ningming','','县','451422','0771',3),(2439,'龙州',355,'355,2439','l','lz','longzhou','','县','451423','0771',4),(2440,'大新',355,'355,2440','d','dx','daxin','','县','451424','0771',5),(2441,'天等',355,'355,2441','t','td','tiandeng','','县','451425','0771',6),(2442,'凭祥',355,'355,2442','p','px','pingxiang','','市','451481','0771',7),(2443,'秀英',356,'356,2443','x','xy','xiuying','','区','460105','0898',1),(2444,'龙华',356,'356,2444','l','lh','longhua','','区','460106','0898',2),(2445,'琼山',356,'356,2445','q','qs','qiongshan','','区','460107','0898',3),(2446,'美兰',356,'356,2446','m','ml','meilan','','区','460108','0898',4),(2447,'锦江',375,'375,2447','j','jj','jinjiang','','区','510104','028',1),(2448,'青羊',375,'375,2448','q','qy','qingyang','','区','510105','028',2),(2449,'金牛',375,'375,2449','j','jn','jinniu','','区','510106','028',3),(2450,'武侯',375,'375,2450','w','wh','wuhou','','区','510107','028',4),(2451,'成华',375,'375,2451','c','ch','chenghua','','区','510108','028',5),(2452,'龙泉驿',375,'375,2452','l','lqy','longquanyi','','区','510112','028',6),(2453,'青白江',375,'375,2453','q','qbj','qingbaijiang','','区','510113','028',7),(2454,'新都',375,'375,2454','x','xd','xindou','','区','510114','028',8),(2455,'温江',375,'375,2455','w','wj','wenjiang','','区','510115','028',9),(2456,'金堂',375,'375,2456','j','jt','jintang','','县','510121','028',10),(2457,'双流',375,'375,2457','s','sl','shuangliu','','县','510122','028',11),(2458,'郫县',375,'375,2458','p','px','pixian','','','510124','028',12),(2459,'大邑',375,'375,2459','d','dy','dayi','','县','510129','028',13),(2460,'蒲江',375,'375,2460','p','pj','pujiang','','县','510131','028',14),(2461,'新津',375,'375,2461','x','xj','xinjin','','县','510132','028',15),(2462,'都江堰',375,'375,2462','d','djy','doujiangyan','','市','510181','028',16),(2463,'彭州',375,'375,2463','p','pz','pengzhou','','市','510182','028',17),(2464,'邛崃',375,'375,2464','q','ql','qionglai','','市','510183','028',18),(2465,'崇州',375,'375,2465','c','cz','chongzhou','','市','510184','028',19),(2466,'自流井',376,'376,2466','z','zlj','ziliujing','','区','510302','0813',1),(2467,'贡井',376,'376,2467','g','gj','gongjing','','区','510303','0813',2),(2468,'大安',376,'376,2468','d','da','daan','','区','510304','0813',3),(2469,'沿滩',376,'376,2469','y','yt','yantan','','区','510311','0813',4),(2470,'荣县',376,'376,2470','r','rx','rongxian','','','510321','0813',5),(2471,'富顺',376,'376,2471','f','fs','fushun','','县','510322','0813',6),(2472,'东区',377,'377,2472','d','dq','dongqu','','','510402','0812',1),(2473,'西区',377,'377,2473','x','xq','xiqu','','','510403','0812',2),(2474,'仁和',377,'377,2474','r','rh','renhe','','区','510411','0812',3),(2475,'米易',377,'377,2475','m','my','miyi','','县','510421','0812',4),(2476,'盐边',377,'377,2476','y','yb','yanbian','','县','510422','0812',5),(2477,'江阳',378,'378,2477','j','jy','jiangyang','','区','510502','0830',1),(2478,'纳溪',378,'378,2478','n','nx','naxi','','区','510503','0830',2),(2479,'龙马潭',378,'378,2479','l','lmt','longmatan','','区','510504','0830',3),(2480,'泸县',378,'378,2480','l','lx','luxian','','','510521','0830',4),(2481,'合江',378,'378,2481','h','hj','hejiang','','县','510522','0830',5),(2482,'叙永',378,'378,2482','x','xy','xuyong','','县','510524','0830',6),(2483,'古蔺',378,'378,2483','g','gl','gulin','','县','510525','0830',7),(2484,'旌阳',379,'379,2484','j','jy','jingyang','','区','510603','0838',1),(2485,'中江',379,'379,2485','z','zj','zhongjiang','','县','510623','0838',2),(2486,'罗江',379,'379,2486','l','lj','luojiang','','县','510626','0838',3),(2487,'广汉',379,'379,2487','g','gh','guanghan','','市','510681','0838',4),(2488,'什邡',379,'379,2488','s','sf','shenfang','','市','510682','0838',5),(2489,'绵竹',379,'379,2489','m','mz','mianzhu','','市','510683','0838',6),(2490,'涪城',380,'380,2490','f','fc','fucheng','','区','510703','0816',1),(2491,'游仙',380,'380,2491','y','yx','youxian','','区','510704','0816',2),(2492,'三台',380,'380,2492','s','st','santai','','县','510722','0816',3),(2493,'盐亭',380,'380,2493','y','yt','yanting','','县','510723','0816',4),(2494,'安县',380,'380,2494','a','ax','anxian','','','510724','0816',5),(2495,'梓潼',380,'380,2495','z','zt','zitong','','县','510725','0816',6),(2496,'北川',380,'380,2496','b','bc','beichuan','羌族','自治县','510726','0816',7),(2497,'平武',380,'380,2497','p','pw','pingwu','','县','510727','0816',8),(2498,'江油',380,'380,2498','j','jy','jiangyou','','市','510781','0816',9),(2499,'利州',381,'381,2499','l','lz','lizhou','','区','510802','0839',1),(2500,'昭化',381,'381,2500','z','zh','zhaohua','','区','510811','0839',2),(2501,'朝天',381,'381,2501','c','ct','chaotian','','区','510812','0839',3),(2502,'旺苍',381,'381,2502','w','wc','wangcang','','县','510821','0839',4),(2503,'青川',381,'381,2503','q','qc','qingchuan','','县','510822','0839',5),(2504,'剑阁',381,'381,2504','j','jg','jiange','','县','510823','0839',6),(2505,'苍溪',381,'381,2505','c','cx','cangxi','','县','510824','0839',7),(2506,'船山',382,'382,2506','c','cs','chuanshan','','区','510903','0825',1),(2507,'安居',382,'382,2507','a','aj','anju','','区','510904','0825',2),(2508,'蓬溪',382,'382,2508','p','px','pengxi','','县','510921','0825',3),(2509,'射洪',382,'382,2509','s','sh','shehong','','县','510922','0825',4),(2510,'大英',382,'382,2510','d','dy','daying','','县','510923','0825',5),(2511,'市中',383,'383,2511','s','sz','shizhong','','区','511002','',1),(2512,'东兴',383,'383,2512','d','dx','dongxing','','区','511011','',2),(2513,'威远',383,'383,2513','w','wy','weiyuan','','县','511024','',3),(2514,'资中',383,'383,2514','z','zz','zizhong','','县','511025','',4),(2515,'隆昌',383,'383,2515','l','lc','longchang','','县','511028','',5),(2516,'市中',384,'384,2516','s','sz','shizhong','','区','511102','0833',1),(2517,'沙湾',384,'384,2517','s','sw','shawan','','区','511111','0833',2),(2518,'五通桥',384,'384,2518','w','wtq','wutongqiao','','区','511112','0833',3),(2519,'金口河',384,'384,2519','j','jkh','jinkouhe','','区','511113','0833',4),(2520,'犍为',384,'384,2520','j','jw','jianwei','','县','511123','0833',5),(2521,'井研',384,'384,2521','j','jy','jingyan','','县','511124','0833',6),(2522,'夹江',384,'384,2522','j','jj','jiajiang','','县','511126','0833',7),(2523,'沐川',384,'384,2523','m','mc','muchuan','','县','511129','0833',8),(2524,'峨边',384,'384,2524','e','eb','ebian','彝族','自治县','511132','0833',9),(2525,'马边',384,'384,2525','m','mb','mabian','彝族','自治县','511133','0833',10),(2526,'峨眉山',384,'384,2526','e','ems','emeishan','','市','511181','0833',11),(2527,'顺庆',385,'385,2527','s','sq','shunqing','','区','511302','0817',1),(2528,'高坪',385,'385,2528','g','gp','gaoping','','区','511303','0817',2),(2529,'嘉陵',385,'385,2529','j','jl','jialing','','区','511304','0817',3),(2530,'南部',385,'385,2530','n','nb','nanbu','','县','511321','0817',4),(2531,'营山',385,'385,2531','y','ys','yingshan','','县','511322','0817',5),(2532,'蓬安',385,'385,2532','p','pa','pengan','','县','511323','0817',6),(2533,'仪陇',385,'385,2533','y','yl','yilong','','县','511324','0817',7),(2534,'西充',385,'385,2534','x','xc','xichong','','县','511325','0817',8),(2535,'阆中',385,'385,2535','l','lz','langzhong','','市','511381','0817',9),(2536,'东坡',386,'386,2536','d','dp','dongpo','','区','511402','028',1),(2537,'仁寿',386,'386,2537','r','rs','renshou','','县','511421','028',2),(2538,'彭山',386,'386,2538','p','ps','pengshan','','县','511422','028',3),(2539,'洪雅',386,'386,2539','h','hy','hongya','','县','511423','028',4),(2540,'丹棱',386,'386,2540','d','dl','danleng','','县','511424','028',5),(2541,'青神',386,'386,2541','q','qs','qingshen','','县','511425','028',6),(2542,'翠屏',387,'387,2542','c','cp','cuiping','','区','511502','0831',1),(2543,'南溪',387,'387,2543','n','nx','nanxi','','区','511503','0831',2),(2544,'宜宾',387,'387,2544','y','yb','yibin','','县','511521','0831',3),(2545,'江安',387,'387,2545','j','ja','jiangan','','县','511523','0831',4),(2546,'长宁',387,'387,2546','z','zn','zhangning','','县','511524','0831',5),(2547,'高县',387,'387,2547','g','gx','gaoxian','','','511525','0831',6),(2548,'珙县',387,'387,2548','g','gx','gongxian','','','511526','0831',7),(2549,'筠连',387,'387,2549','y','yl','yunlian','','县','511527','0831',8),(2550,'兴文',387,'387,2550','x','xw','xingwen','','县','511528','0831',9),(2551,'屏山',387,'387,2551','p','ps','pingshan','','县','511529','0831',10),(2552,'广安',388,'388,2552','g','ga','guangan','','区','511602','',1),(2553,'岳池',388,'388,2553','y','yc','yuechi','','县','511621','',2),(2554,'武胜',388,'388,2554','w','ws','wusheng','','县','511622','',3),(2555,'邻水',388,'388,2555','l','ls','linshui','','县','511623','',4),(2556,'华蓥',388,'388,2556','h','hy','huaying','','市','511681','',5),(2557,'通川',389,'389,2557','t','tc','tongchuan','','区','511702','0818',1),(2558,'达县',389,'389,2558','d','dx','daxian','','','511721','0818',2),(2559,'宣汉',389,'389,2559','x','xh','xuanhan','','县','511722','0818',3),(2560,'开江',389,'389,2560','k','kj','kaijiang','','县','511723','0818',4),(2561,'大竹',389,'389,2561','d','dz','dazhu','','县','511724','0818',5),(2562,'渠县',389,'389,2562','q','qx','quxian','','','511725','0818',6),(2563,'万源',389,'389,2563','w','wy','wanyuan','','市','511781','0818',7),(2564,'雨城',390,'390,2564','y','yc','yucheng','','区','511802','0835',1),(2565,'名山',390,'390,2565','m','ms','mingshan','','区','511803','0835',2),(2566,'荥经',390,'390,2566','y','yj','yingjing','','县','511822','0835',3),(2567,'汉源',390,'390,2567','h','hy','hanyuan','','县','511823','0835',4),(2568,'石棉',390,'390,2568','s','sm','shimian','','县','511824','0835',5),(2569,'天全',390,'390,2569','t','tq','tianquan','','县','511825','0835',6),(2570,'芦山',390,'390,2570','l','ls','lushan','','县','511826','0835',7),(2571,'宝兴',390,'390,2571','b','bx','baoxing','','县','511827','0835',8),(2572,'巴州',391,'391,2572','b','bz','bazhou','','区','511902','0827',1),(2573,'通江',391,'391,2573','t','tj','tongjiang','','县','511921','0827',2),(2574,'南江',391,'391,2574','n','nj','nanjiang','','县','511922','0827',3),(2575,'平昌',391,'391,2575','p','pc','pingchang','','县','511923','0827',4),(2576,'雁江',392,'392,2576','y','yj','yanjiang','','区','512002','028',1),(2577,'安岳',392,'392,2577','a','ay','anyue','','县','512021','028',2),(2578,'乐至',392,'392,2578','l','lz','lezhi','','县','512022','028',3),(2579,'简阳',392,'392,2579','j','jy','jianyang','','市','512081','028',4),(2580,'马尔康',393,'393,2580','m','mek','maerkang','','县','513229','0837',1),(2581,'汶川',393,'393,2581','w','wc','wenchuan','','县','513221','0837',2),(2582,'理县',393,'393,2582','l','lx','lixian','','','513222','0837',3),(2583,'茂县',393,'393,2583','m','mx','maoxian','','','513223','0837',4),(2584,'松潘',393,'393,2584','s','sp','songpan','','县','513224','0837',5),(2585,'九寨沟',393,'393,2585','j','jzg','jiuzhaigou','','县','513225','0837',6),(2586,'金川',393,'393,2586','j','jc','jinchuan','','县','513226','0837',7),(2587,'小金',393,'393,2587','x','xj','xiaojin','','县','513227','0837',8),(2588,'黑水',393,'393,2588','h','hs','heishui','','县','513228','0837',9),(2589,'壤塘',393,'393,2589','r','rt','rangtang','','县','513230','0837',10),(2590,'阿坝',393,'393,2590','a','ab','aba','','县','513231','0837',11),(2591,'若尔盖',393,'393,2591','r','reg','ruoergai','','县','513232','0837',12),(2592,'红原',393,'393,2592','h','hy','hongyuan','','县','513233','0837',13),(2593,'康定',394,'394,2593','k','kd','kangding','','县','513321','0836',1),(2594,'泸定',394,'394,2594','l','ld','luding','','县','513322','0836',2),(2595,'丹巴',394,'394,2595','d','db','danba','','县','513323','0836',3),(2596,'九龙',394,'394,2596','j','jl','jiulong','','县','513324','0836',4),(2597,'雅江',394,'394,2597','y','yj','yajiang','','县','513325','0836',5),(2598,'道孚',394,'394,2598','d','df','daofu','','县','513326','0836',6),(2599,'炉霍',394,'394,2599','l','lh','luhuo','','县','513327','0836',7),(2600,'甘孜',394,'394,2600','g','gz','ganzi','','县','513328','0836',8),(2601,'新龙',394,'394,2601','x','xl','xinlong','','县','513329','0836',9),(2602,'德格',394,'394,2602','d','dg','dege','','县','513330','0836',10),(2603,'白玉',394,'394,2603','b','by','baiyu','','县','513331','0836',11),(2604,'石渠',394,'394,2604','s','sq','shiqu','','县','513332','0836',12),(2605,'色达',394,'394,2605','s','sd','seda','','县','513333','0836',13),(2606,'理塘',394,'394,2606','l','lt','litang','','县','513334','0836',14),(2607,'巴塘',394,'394,2607','b','bt','batang','','县','513335','0836',15),(2608,'乡城',394,'394,2608','x','xc','xiangcheng','','县','513336','0836',16),(2609,'稻城',394,'394,2609','d','dc','daocheng','','县','513337','0836',17),(2610,'得荣',394,'394,2610','d','dr','derong','','县','513338','0836',18),(2611,'西昌',395,'395,2611','x','xc','xichang','','市','513401','0834',1),(2612,'木里',395,'395,2612','m','ml','muli','藏族','自治县','513422','0834',2),(2613,'盐源',395,'395,2613','y','yy','yanyuan','','县','513423','0834',3),(2614,'德昌',395,'395,2614','d','dc','dechang','','县','513424','0834',4),(2615,'会理',395,'395,2615','h','hl','huili','','县','513425','0834',5),(2616,'会东',395,'395,2616','h','hd','huidong','','县','513426','0834',6),(2617,'宁南',395,'395,2617','n','nn','ningnan','','县','513427','0834',7),(2618,'普格',395,'395,2618','p','pg','puge','','县','513428','0834',8),(2619,'布拖',395,'395,2619','b','bt','butuo','','县','513429','0834',9),(2620,'金阳',395,'395,2620','j','jy','jinyang','','县','513430','0834',10),(2621,'昭觉',395,'395,2621','z','zj','zhaojue','','县','513431','0834',11),(2622,'喜德',395,'395,2622','x','xd','xide','','县','513432','0834',12),(2623,'冕宁',395,'395,2623','m','mn','mianning','','县','513433','0834',13),(2624,'越西',395,'395,2624','y','yx','yuexi','','县','513434','0834',14),(2625,'甘洛',395,'395,2625','g','gl','ganluo','','县','513435','0834',15),(2626,'美姑',395,'395,2626','m','mg','meigu','','县','513436','0834',16),(2627,'雷波',395,'395,2627','l','lb','leibo','','县','513437','0834',17),(2628,'观山湖',396,'396,2628','g','gsh','guanshanhu','','区','','0851',1),(2629,'南明',396,'396,2629','n','nm','nanming','','区','520102','0851',2),(2630,'云岩',396,'396,2630','y','yy','yunyan','','区','520103','0851',3),(2631,'花溪',396,'396,2631','h','hx','huaxi','','区','520111','0851',4),(2632,'乌当',396,'396,2632','w','wd','wudang','','区','520112','0851',5),(2633,'白云',396,'396,2633','b','by','baiyun','','区','520113','0851',6),(2634,'开阳',396,'396,2634','k','ky','kaiyang','','县','520121','0851',7),(2635,'息烽',396,'396,2635','x','xf','xifeng','','县','520122','0851',8),(2636,'修文',396,'396,2636','x','xw','xiuwen','','县','520123','0851',9),(2637,'清镇',396,'396,2637','q','qz','qingzhen','','市','520181','0851',10),(2638,'钟山',397,'397,2638','z','zs','zhongshan','','区','520201','0858',1),(2639,'六枝特',397,'397,2639','l','lzt','liuzhite','','区','520203','0858',2),(2640,'水城',397,'397,2640','s','sc','shuicheng','','县','520221','0858',3),(2641,'盘县',397,'397,2641','p','px','panxian','','','520222','0858',4),(2642,'红花岗',398,'398,2642','h','hhg','honghuagang','','区','520302','0852',1),(2643,'汇川',398,'398,2643','h','hc','huichuan','','区','520303','0852',2),(2644,'遵义',398,'398,2644','z','zy','zunyi','','县','520321','0852',3),(2645,'桐梓',398,'398,2645','t','tz','tongzi','','县','520322','0852',4),(2646,'绥阳',398,'398,2646','s','sy','suiyang','','县','520323','0852',5),(2647,'正安',398,'398,2647','z','za','zhengan','','县','520324','0852',6),(2648,'道真',398,'398,2648','d','dz','daozhen','仡佬族苗族','自治县','520325','0852',7),(2649,'务川',398,'398,2649','w','wc','wuchuan','仡佬族苗族','自治县','520326','0852',8),(2650,'凤冈',398,'398,2650','f','fg','fenggang','','县','520327','0852',9),(2651,'湄潭',398,'398,2651','m','mt','meitan','','县','520328','0852',10),(2652,'余庆',398,'398,2652','y','yq','yuqing','','县','520329','0852',11),(2653,'习水',398,'398,2653','x','xs','xishui','','县','520330','0852',12),(2654,'赤水',398,'398,2654','c','cs','chishui','','市','520381','0852',13),(2655,'仁怀',398,'398,2655','r','rh','renhuai','','市','520382','0852',14),(2656,'西秀',399,'399,2656','x','xx','xixiu','','区','520402','0853',1),(2657,'平坝',399,'399,2657','p','pb','pingba','','县','520421','0853',2),(2658,'普定',399,'399,2658','p','pd','puding','','县','520422','0853',3),(2659,'镇宁',399,'399,2659','z','zn','zhenning','布依族苗族','自治县','520423','0853',4),(2660,'关岭',399,'399,2660','g','gl','guanling','布依族苗族','自治县','520424','0853',5),(2661,'紫云',399,'399,2661','z','zy','ziyun','苗族布依族','自治县','520425','0853',6),(2662,'七星关',400,'400,2662','q','qxg','qixingguan','','区','520502','0857',1),(2663,'大方',400,'400,2663','d','df','dafang','','县','520521','0857',2),(2664,'黔西',400,'400,2664','q','qx','qianxi','','县','520522','0857',3),(2665,'金沙',400,'400,2665','j','js','jinsha','','县','520523','0857',4),(2666,'织金',400,'400,2666','z','zj','zhijin','','县','520524','0857',5),(2667,'纳雍',400,'400,2667','n','ny','nayong','','县','520525','0857',6),(2668,'威宁',400,'400,2668','w','wn','weining','彝族回族苗族','自治县','520526','0857',7),(2669,'赫章',400,'400,2669','h','hz','hezhang','','县','520527','0857',8),(2670,'碧江',401,'401,2670','b','bj','bijiang','','区','520602','0856',1),(2671,'万山',401,'401,2671','w','ws','wanshan','','区','520603','0856',2),(2672,'江口',401,'401,2672','j','jk','jiangkou','','县','520621','0856',3),(2673,'玉屏',401,'401,2673','y','yp','yuping','侗族','自治县','520622','0856',4),(2674,'石阡',401,'401,2674','s','sq','shiqian','','县','520623','0856',5),(2675,'思南',401,'401,2675','s','sn','sinan','','县','520624','0856',6),(2676,'印江',401,'401,2676','y','yj','yinjiang','土家族苗族','自治县','520625','0856',7),(2677,'德江',401,'401,2677','d','dj','dejiang','','县','520626','0856',8),(2678,'沿河',401,'401,2678','y','yh','yanhe','土家族','自治县','520627','0856',9),(2679,'松桃',401,'401,2679','s','st','songtao','苗族','自治县','520628','0856',10),(2680,'兴义',402,'402,2680','x','xy','xingyi','','市','522301','0859',1),(2681,'兴仁',402,'402,2681','x','xr','xingren','','县','522322','0859',2),(2682,'普安',402,'402,2682','p','pa','puan','','县','522323','0859',3),(2683,'晴隆',402,'402,2683','q','ql','qinglong','','县','522324','0859',4),(2684,'贞丰',402,'402,2684','z','zf','zhenfeng','','县','522325','0859',5),(2685,'望谟',402,'402,2685','w','wm','wangmo','','县','522326','0859',6),(2686,'册亨',402,'402,2686','c','ch','ceheng','','县','522327','0859',7),(2687,'安龙',402,'402,2687','a','al','anlong','','县','522328','0859',8),(2688,'凯里',403,'403,2688','k','kl','kaili','','市','522601','0855',1),(2689,'黄平',403,'403,2689','h','hp','huangping','','县','522622','0855',2),(2690,'施秉',403,'403,2690','s','sb','shibing','','县','522623','0855',3),(2691,'三穗',403,'403,2691','s','ss','sansui','','县','522624','0855',4),(2692,'镇远',403,'403,2692','z','zy','zhenyuan','','县','522625','0855',5),(2693,'岑巩',403,'403,2693','c','cg','cengong','','县','522626','0855',6),(2694,'天柱',403,'403,2694','t','tz','tianzhu','','县','522627','0855',7),(2695,'锦屏',403,'403,2695','j','jp','jinping','','县','522628','0855',8),(2696,'剑河',403,'403,2696','j','jh','jianhe','','县','522629','0855',9),(2697,'台江',403,'403,2697','t','tj','taijiang','','县','522630','0855',10),(2698,'黎平',403,'403,2698','l','lp','liping','','县','522631','0855',11),(2699,'榕江',403,'403,2699','r','rj','rongjiang','','县','522632','0855',12),(2700,'从江',403,'403,2700','c','cj','congjiang','','县','522633','0855',13),(2701,'雷山',403,'403,2701','l','ls','leishan','','县','522634','0855',14),(2702,'麻江',403,'403,2702','m','mj','majiang','','县','522635','0855',15),(2703,'丹寨',403,'403,2703','d','dz','danzhai','','县','522636','0855',16),(2704,'都匀',404,'404,2704','d','dy','douyun','','市','522701','0854',1),(2705,'福泉',404,'404,2705','f','fq','fuquan','','市','522702','0854',2),(2706,'荔波',404,'404,2706','l','lb','libo','','县','522722','0854',3),(2707,'贵定',404,'404,2707','g','gd','guiding','','县','522723','0854',4),(2708,'瓮安',404,'404,2708','w','wa','wengan','','县','522725','0854',5),(2709,'独山',404,'404,2709','d','ds','dushan','','县','522726','0854',6),(2710,'平塘',404,'404,2710','p','pt','pingtang','','县','522727','0854',7),(2711,'罗甸',404,'404,2711','l','ld','luodian','','县','522728','0854',8),(2712,'长顺',404,'404,2712','z','zs','zhangshun','','县','522729','0854',9),(2713,'龙里',404,'404,2713','l','ll','longli','','县','522730','0854',10),(2714,'惠水',404,'404,2714','h','hs','huishui','','县','522731','0854',11),(2715,'三都',404,'404,2715','s','sd','sandou','水族','自治县','522732','0854',12),(2716,'五华',405,'405,2716','w','wh','wuhua','','区','530102','0871',1),(2717,'盘龙',405,'405,2717','p','pl','panlong','','区','530103','0871',2),(2718,'官渡',405,'405,2718','g','gd','guandu','','区','530111','0871',3),(2719,'西山',405,'405,2719','x','xs','xishan','','区','530112','0871',4),(2720,'东川',405,'405,2720','d','dc','dongchuan','','区','530113','0871',5),(2721,'呈贡',405,'405,2721','c','cg','chenggong','','区','530114','0871',6),(2722,'晋宁',405,'405,2722','j','jn','jinning','','县','530122','0871',7),(2723,'富民',405,'405,2723','f','fm','fumin','','县','530124','0871',8),(2724,'宜良',405,'405,2724','y','yl','yiliang','','县','530125','0871',9),(2725,'石林',405,'405,2725','s','sl','shilin','彝族','自治县','530126','0871',10),(2726,'嵩明',405,'405,2726','s','sm','songming','','县','530127','0871',11),(2727,'禄劝',405,'405,2727','l','lq','luquan','彝族苗族','自治县','530128','0871',12),(2728,'寻甸',405,'405,2728','x','xd','xundian','回族彝族','自治县','530129','0871',13),(2729,'安宁',405,'405,2729','a','an','anning','','市','530181','0871',14),(2730,'麒麟',406,'406,2730','q','ql','qilin','','区','530302','0874',1),(2731,'马龙',406,'406,2731','m','ml','malong','','县','530321','0874',2),(2732,'陆良',406,'406,2732','l','ll','luliang','','县','530322','0874',3),(2733,'师宗',406,'406,2733','s','sz','shizong','','县','530323','0874',4),(2734,'罗平',406,'406,2734','l','lp','luoping','','县','530324','0874',5),(2735,'富源',406,'406,2735','f','fy','fuyuan','','县','530325','0874',6),(2736,'会泽',406,'406,2736','h','hz','huize','','县','530326','0874',7),(2737,'沾益',406,'406,2737','z','zy','zhanyi','','县','530328','0874',8),(2738,'宣威',406,'406,2738','x','xw','xuanwei','','市','530381','0874',9),(2739,'红塔',407,'407,2739','h','ht','hongta','','区','530402','0877',1),(2740,'江川',407,'407,2740','j','jc','jiangchuan','','县','530421','0877',2),(2741,'澄江',407,'407,2741','c','cj','chengjiang','','县','530422','0877',3),(2742,'通海',407,'407,2742','t','th','tonghai','','县','530423','0877',4),(2743,'华宁',407,'407,2743','h','hn','huaning','','县','530424','0877',5),(2744,'易门',407,'407,2744','y','ym','yimen','','县','530425','0877',6),(2745,'峨山',407,'407,2745','e','es','eshan','彝族','自治县','530426','0877',7),(2746,'新平',407,'407,2746','x','xp','xinping','彝族傣族','自治县','530427','0877',8),(2747,'元江',407,'407,2747','y','yj','yuanjiang','哈尼族彝族傣族','自治县','530428','0877',9),(2748,'昭阳',408,'408,2748','z','zy','zhaoyang','','区','530602','0870',1),(2749,'鲁甸',408,'408,2749','l','ld','ludian','','县','530621','0870',2),(2750,'巧家',408,'408,2750','q','qj','qiaojia','','县','530622','0870',3),(2751,'盐津',408,'408,2751','y','yj','yanjin','','县','530623','0870',4),(2752,'大关',408,'408,2752','d','dg','daguan','','县','530624','0870',5),(2753,'永善',408,'408,2753','y','ys','yongshan','','县','530625','0870',6),(2754,'绥江',408,'408,2754','s','sj','suijiang','','县','530626','0870',7),(2755,'镇雄',408,'408,2755','z','zx','zhenxiong','','县','530627','0870',8),(2756,'彝良',408,'408,2756','y','yl','yiliang','','县','530628','0870',9),(2757,'威信',408,'408,2757','w','wx','weixin','','县','530629','0870',10),(2758,'水富',408,'408,2758','s','sf','shuifu','','县','530630','0870',11),(2759,'古城',409,'409,2759','g','gc','gucheng','','区','530702','0888',1),(2760,'玉龙',409,'409,2760','y','yl','yulong','纳西族','自治县','530721','0888',2),(2761,'永胜',409,'409,2761','y','ys','yongsheng','','县','530722','0888',3),(2762,'华坪',409,'409,2762','h','hp','huaping','','县','530723','0888',4),(2763,'宁蒗',409,'409,2763','n','nl','ninglang','彝族','自治县','530724','0888',5),(2764,'思茅',410,'410,2764','s','sm','simao','','区','530802','0879',1),(2765,'宁洱',410,'410,2765','n','ne','ninger','哈尼族彝族','县','530821','0879',2),(2766,'墨江',410,'410,2766','m','mj','mojiang','哈尼族','县','530822','0879',3),(2767,'景东',410,'410,2767','j','jd','jingdong','彝族','县','530823','0879',4),(2768,'景谷',410,'410,2768','j','jg','jinggu','傣族彝族','县','530824','0879',5),(2769,'镇沅',410,'410,2769','z','zy','zhenyuan','彝族哈尼族拉祜族','县','530825','0879',6),(2770,'江城',410,'410,2770','j','jc','jiangcheng','哈尼族彝族','县','530826','0879',7),(2771,'孟连',410,'410,2771','m','ml','menglian','傣族拉祜族佤族','县','530827','0879',8),(2772,'澜沧',410,'410,2772','l','lc','lancang','拉祜族','县','530828','0879',9),(2773,'西盟',410,'410,2773','x','xm','ximeng','佤族','县','530829','0879',10),(2774,'临翔',411,'411,2774','l','lx','linxiang','','区','530902','0883',1),(2775,'凤庆',411,'411,2775','f','fq','fengqing','','县','530921','0883',2),(2776,'云县',411,'411,2776','y','yx','yunxian','','','530922','0883',3),(2777,'永德',411,'411,2777','y','yd','yongde','','县','530923','0883',4),(2778,'镇康',411,'411,2778','z','zk','zhenkang','','县','530924','0883',5),(2779,'双江',411,'411,2779','s','sj','shuangjiang','拉祜族佤族布朗族傣族','自治县','530925','0883',6),(2780,'耿马',411,'411,2780','g','gm','gengma','傣族佤族','自治县','530926','0883',7),(2781,'沧源',411,'411,2781','c','cy','cangyuan','佤族','自治县','530927','0883',8),(2782,'楚雄',412,'412,2782','c','cx','chuxiong','','市','532301','0878',1),(2783,'双柏',412,'412,2783','s','sb','shuangbo','','县','532322','0878',2),(2784,'牟定',412,'412,2784','m','md','mouding','','县','532323','0878',3),(2785,'南华',412,'412,2785','n','nh','nanhua','','县','532324','0878',4),(2786,'姚安',412,'412,2786','y','ya','yaoan','','县','532325','0878',5),(2787,'大姚',412,'412,2787','d','dy','dayao','','县','532326','0878',6),(2788,'永仁',412,'412,2788','y','yr','yongren','','县','532327','0878',7),(2789,'元谋',412,'412,2789','y','ym','yuanmou','','县','532328','0878',8),(2790,'武定',412,'412,2790','w','wd','wuding','','县','532329','0878',9),(2791,'禄丰',412,'412,2791','l','lf','lufeng','','县','532331','0878',10),(2792,'个旧',413,'413,2792','g','gj','gejiu','','市','532501','0873',1),(2793,'开远',413,'413,2793','k','ky','kaiyuan','','市','532502','0873',2),(2794,'蒙自',413,'413,2794','m','mz','mengzi','','市','532503','0873',3),(2795,'屏边',413,'413,2795','p','pb','pingbian','苗族','自治县','532523','0873',4),(2796,'建水',413,'413,2796','j','js','jianshui','','县','532524','0873',5),(2797,'石屏',413,'413,2797','s','sp','shiping','','县','532525','0873',6),(2798,'弥勒',413,'413,2798','m','ml','mile','','市','532526','0873',7),(2799,'泸西',413,'413,2799','l','lx','luxi','','县','532527','0873',8),(2800,'元阳',413,'413,2800','y','yy','yuanyang','','县','532528','0873',9),(2801,'红河',413,'413,2801','h','hh','honghe','','县','532529','0873',10),(2802,'金平',413,'413,2802','j','jp','jinping','苗族瑶族傣族','自治县','532530','0873',11),(2803,'绿春',413,'413,2803','l','lc','lu:chun','','县','532531','0873',12),(2804,'河口',413,'413,2804','h','hk','hekou','瑶族','自治县','532532','0873',13),(2805,'文山',414,'414,2805','w','ws','wenshan','','县','532601','0876',1),(2806,'砚山',414,'414,2806','y','ys','yanshan','','县','532622','0876',2),(2807,'西畴',414,'414,2807','x','xc','xichou','','县','532623','0876',3),(2808,'麻栗坡',414,'414,2808','m','mlp','malipo','','县','532624','0876',4),(2809,'马关',414,'414,2809','m','mg','maguan','','县','532625','0876',5),(2810,'丘北',414,'414,2810','q','qb','qiubei','','县','532626','0876',6),(2811,'广南',414,'414,2811','g','gn','guangnan','','县','532627','0876',7),(2812,'富宁',414,'414,2812','f','fn','funing','','县','532628','0876',8),(2813,'景洪',415,'415,2813','j','jh','jinghong','','市','532801','0691',1),(2814,'勐海',415,'415,2814','m','mh','menghai','','县','532822','0691',2),(2815,'勐腊',415,'415,2815','m','ml','mengla','','县','532823','0691',3),(2816,'大理',416,'416,2816','d','dl','dali','','市','532901','0872',1),(2817,'漾濞',416,'416,2817','y','yb','yangbi','彝族','自治县','532922','0872',2),(2818,'祥云',416,'416,2818','x','xy','xiangyun','','县','532923','0872',3),(2819,'宾川',416,'416,2819','b','bc','binchuan','','县','532924','0872',4),(2820,'弥渡',416,'416,2820','m','md','midu','','县','532925','0872',5),(2821,'南涧',416,'416,2821','n','nj','nanjian','彝族','自治县','532926','0872',6),(2822,'巍山',416,'416,2822','w','ws','weishan','彝族回族','自治县','532927','0872',7),(2823,'永平',416,'416,2823','y','yp','yongping','','县','532928','0872',8),(2824,'云龙',416,'416,2824','y','yl','yunlong','','县','532929','0872',9),(2825,'洱源',416,'416,2825','e','ey','eryuan','','县','532930','0872',10),(2826,'剑川',416,'416,2826','j','jc','jianchuan','','县','532931','0872',11),(2827,'鹤庆',416,'416,2827','h','hq','heqing','','县','532932','0872',12),(2828,'瑞丽',417,'417,2828','r','rl','ruili','','市','533102','0692',1),(2829,'芒市',417,'417,2829','m','ms','mangshi','','','533103','0692',2),(2830,'梁河',417,'417,2830','l','lh','lianghe','','县','533122','0692',3),(2831,'盈江',417,'417,2831','y','yj','yingjiang','','县','533123','0692',4),(2832,'陇川',417,'417,2832','l','lc','longchuan','','县','533124','0692',5),(2833,'泸水',418,'418,2833','l','ls','lushui','','县','533321','0886',1),(2834,'福贡',418,'418,2834','f','fg','fugong','','县','533323','0886',2),(2835,'贡山',418,'418,2835','g','gs','gongshan','独龙族怒族','县','533324','0886',3),(2836,'兰坪',418,'418,2836','l','lp','lanping','白族普米族','县','533325','0886',4),(2837,'香格里拉',419,'419,2837','x','xgll','xianggelila','','县','533421','0887',1),(2838,'德钦',419,'419,2838','d','dq','deqin','','县','533422','0887',2),(2839,'维西',419,'419,2839','w','wx','weixi','','县','533423','0887',3),(2840,'隆阳',420,'420,2840','l','ly','longyang','','区','530502','0875',1),(2841,'施甸',420,'420,2841','s','sd','shidian','','县','530521','0875',2),(2842,'腾冲',420,'420,2842','t','tc','tengchong','','县','530522','0875',3),(2843,'龙陵',420,'420,2843','l','ll','longling','','县','530523','0875',4),(2844,'昌宁',420,'420,2844','c','cn','changning','','县','530524','0875',5),(2845,'城关',421,'421,2845','c','cg','chengguan','','区','540102','0891',1),(2846,'林周',421,'421,2846','l','lz','linzhou','','县','540121','0891',2),(2847,'当雄',421,'421,2847','d','dx','dangxiong','','县','540122','0891',3),(2848,'尼木',421,'421,2848','n','nm','nimu','','县','540123','0891',4),(2849,'曲水',421,'421,2849','q','qs','qushui','','县','540124','0891',5),(2850,'堆龙德庆',421,'421,2850','d','dldq','duilongdeqing','','县','540125','0891',6),(2851,'达孜',421,'421,2851','d','dz','dazi','','县','540126','0891',7),(2852,'墨竹工卡',421,'421,2852','m','mzgk','mozhugongka','','县','540127','0891',8),(2853,'昌都',422,'422,2853','c','cd','changdou','','县','542121','0895',1),(2854,'江达',422,'422,2854','j','jd','jiangda','','县','542122','0895',2),(2855,'贡觉',422,'422,2855','g','gj','gongjue','','县','542123','0895',3),(2856,'类乌齐',422,'422,2856','l','lwq','leiwuqi','','县','542124','0895',4),(2857,'丁青',422,'422,2857','d','dq','dingqing','','县','542125','0895',5),(2858,'察雅',422,'422,2858','c','cy','chaya','','县','542126','0895',6),(2859,'八宿',422,'422,2859','b','bs','basu','','县','542127','0895',7),(2860,'左贡',422,'422,2860','z','zg','zuogong','','县','542128','0895',8),(2861,'芒康',422,'422,2861','m','mk','mangkang','','县','542129','0895',9),(2862,'洛隆',422,'422,2862','l','ll','luolong','','县','542132','0895',10),(2863,'边坝',422,'422,2863','b','bb','bianba','','县','542133','0895',11),(2864,'乃东',423,'423,2864','n','nd','naidong','','县','542221','0893',1),(2865,'扎囊',423,'423,2865','z','zn','zhanang','','县','542222','0893',2),(2866,'贡嘎',423,'423,2866','g','gg','gongga','','县','542223','0893',3),(2867,'桑日',423,'423,2867','s','sr','sangri','','县','542224','0893',4),(2868,'琼结',423,'423,2868','q','qj','qiongjie','','县','542225','0893',5),(2869,'曲松',423,'423,2869','q','qs','qusong','','县','542226','0893',6),(2870,'措美',423,'423,2870','c','cm','cuomei','','县','542227','0893',7),(2871,'洛扎',423,'423,2871','l','lz','luozha','','县','542228','0893',8),(2872,'加查',423,'423,2872','j','jc','jiacha','','县','542229','0893',9),(2873,'隆子',423,'423,2873','l','lz','longzi','','县','542231','0893',10),(2874,'错那',423,'423,2874','c','cn','cuonei','','县','542232','0893',11),(2875,'浪卡子',423,'423,2875','l','lkz','langkazi','','县','542233','0893',12),(2876,'日喀则',424,'424,2876','r','rkz','rikaze','','市','542301','0892',1),(2877,'南木林',424,'424,2877','n','nml','nanmulin','','县','542322','0892',2),(2878,'江孜',424,'424,2878','j','jz','jiangzi','','县','542323','0892',3),(2879,'定日',424,'424,2879','d','dr','dingri','','县','542324','0892',4),(2880,'萨迦',424,'424,2880','s','sj','sajia','','县','542325','0892',5),(2881,'拉孜',424,'424,2881','l','lz','lazi','','县','542326','0892',6),(2882,'昂仁',424,'424,2882','a','ar','angren','','县','542327','0892',7),(2883,'谢通门',424,'424,2883','x','xtm','xietongmen','','县','542328','0892',8),(2884,'白朗',424,'424,2884','b','bl','bailang','','县','542329','0892',9),(2885,'仁布',424,'424,2885','r','rb','renbu','','县','542330','0892',10),(2886,'康马',424,'424,2886','k','km','kangma','','县','542331','0892',11),(2887,'定结',424,'424,2887','d','dj','dingjie','','县','542332','0892',12),(2888,'仲巴',424,'424,2888','z','zb','zhongba','','县','542333','0892',13),(2889,'亚东',424,'424,2889','y','yd','yadong','','县','542334','0892',14),(2890,'吉隆',424,'424,2890','j','jl','jilong','','县','542335','0892',15),(2891,'聂拉木',424,'424,2891','n','nlm','nielamu','','县','542336','0892',16),(2892,'萨嘎',424,'424,2892','s','sg','saga','','县','542337','0892',17),(2893,'岗巴',424,'424,2893','g','gb','gangba','','县','542338','0892',18),(2894,'双湖',425,'425,2894','s','sh','shuanghu','','县','','0896',1),(2895,'那曲',425,'425,2895','n','nq','neiqu','','县','542421','0896',2),(2896,'嘉黎',425,'425,2896','j','jl','jiali','','县','542421','0896',3),(2897,'比如',425,'425,2897','b','br','biru','','县','542423','0896',4),(2898,'聂荣',425,'425,2898','n','nr','nierong','','县','542424','0896',5),(2899,'安多',425,'425,2899','a','ad','anduo','','县','542425','0896',6),(2900,'申扎',425,'425,2900','s','sz','shenzha','','县','542426','0896',7),(2901,'索县',425,'425,2901','s','sx','suoxian','','','542427','0896',8),(2902,'班戈',425,'425,2902','b','bg','bange','','县','542428','0896',9),(2903,'巴青',425,'425,2903','b','bq','baqing','','县','542429','0896',10),(2904,'尼玛',425,'425,2904','n','nm','nima','','县','542430','0896',11),(2905,'普兰',426,'426,2905','p','pl','pulan','','县','542521','0897',1),(2906,'札达',426,'426,2906','z','zd','zhada','','县','542522','0897',2),(2907,'噶尔',426,'426,2907','g','ge','gaer','','县','542523','0897',3),(2908,'日土',426,'426,2908','r','rt','ritu','','县','542524','0897',4),(2909,'革吉',426,'426,2909','g','gj','geji','','县','542525','0897',5),(2910,'改则',426,'426,2910','g','gz','gaize','','县','542526','0897',6),(2911,'措勤',426,'426,2911','c','cq','cuoqin','','县','542527','0897',7),(2912,'林芝',427,'427,2912','l','lz','linzhi','','县','542621','0894',1),(2913,'工布江达',427,'427,2913','g','gbjd','gongbujiangda','','县','542621','0894',2),(2914,'米林',427,'427,2914','m','ml','milin','','县','542621','0894',3),(2915,'墨脱',427,'427,2915','m','mt','motuo','','县','542621','0894',4),(2916,'波密',427,'427,2916','b','bm','bomi','','县','542621','0894',5),(2917,'察隅',427,'427,2917','c','cy','chayu','','县','542621','0894',6),(2918,'朗县',427,'427,2918','l','lx','langxian','','','542621','0894',7),(2919,'新城',428,'428,2919','x','xc','xincheng','','区','610102','029',1),(2920,'碑林',428,'428,2920','b','bl','beilin','','区','610103','029',2),(2921,'莲湖',428,'428,2921','l','lh','lianhu','','区','610104','029',3),(2922,'灞桥',428,'428,2922','b','bq','baqiao','','区','610111','029',4),(2923,'未央',428,'428,2923','w','wy','weiyang','','区','610112','029',5),(2924,'雁塔',428,'428,2924','y','yt','yanta','','区','610113','029',6),(2925,'阎良',428,'428,2925','y','yl','yanliang','','区','610114','029',7),(2926,'临潼',428,'428,2926','l','lt','lintong','','区','610115','029',8),(2927,'长安',428,'428,2927','z','za','zhangan','','区','610116','029',9),(2928,'蓝田',428,'428,2928','l','lt','lantian','','县','610122','029',10),(2929,'周至',428,'428,2929','z','zz','zhouzhi','','县','610124','029',11),(2930,'户县',428,'428,2930','h','hx','huxian','','','610125','029',12),(2931,'高陵',428,'428,2931','g','gl','gaoling','','县','610126','029',13),(2932,'王益',429,'429,2932','w','wy','wangyi','','区','610202','0919',1),(2933,'印台',429,'429,2933','y','yt','yintai','','区','610203','0919',2),(2934,'耀州',429,'429,2934','y','yz','yaozhou','','区','610204','0919',3),(2935,'宜君',429,'429,2935','y','yj','yijun','','县','610222','0919',4),(2936,'渭滨',430,'430,2936','w','wb','weibin','','区','610302','0917',1),(2937,'金台',430,'430,2937','j','jt','jintai','','区','610303','0917',2),(2938,'陈仓',430,'430,2938','c','cc','chencang','','区','610304','0917',3),(2939,'凤翔',430,'430,2939','f','fx','fengxiang','','县','610322','0917',4),(2940,'岐山',430,'430,2940','q','qs','qishan','','县','610323','0917',5),(2941,'扶风',430,'430,2941','f','ff','fufeng','','县','610324','0917',6),(2942,'眉县',430,'430,2942','m','mx','meixian','','','610326','0917',7),(2943,'陇县',430,'430,2943','l','lx','longxian','','','610327','0917',8),(2944,'千阳',430,'430,2944','q','qy','qianyang','','县','610328','0917',9),(2945,'麟游',430,'430,2945','l','ly','linyou','','县','610329','0917',10),(2946,'凤县',430,'430,2946','f','fx','fengxian','','','610330','0917',11),(2947,'太白',430,'430,2947','t','tb','taibai','','县','610331','0917',12),(2948,'秦都',431,'431,2948','q','qd','qindou','','区','610402','029',1),(2949,'杨陵',431,'431,2949','y','yl','yangling','','区','610403','029',2),(2950,'渭城',431,'431,2950','w','wc','weicheng','','区','610404','029',3),(2951,'三原',431,'431,2951','s','sy','sanyuan','','县','610422','029',4),(2952,'泾阳',431,'431,2952','j','jy','jingyang','','县','610423','029',5),(2953,'乾县',431,'431,2953','q','qx','qianxian','','','610424','029',6),(2954,'礼泉',431,'431,2954','l','lq','liquan','','县','610425','029',7),(2955,'永寿',431,'431,2955','y','ys','yongshou','','县','610426','029',8),(2956,'彬县',431,'431,2956','b','bx','binxian','','','610427','029',9),(2957,'长武',431,'431,2957','z','zw','zhangwu','','县','610428','029',10),(2958,'旬邑',431,'431,2958','x','xy','xunyi','','县','610429','029',11),(2959,'淳化',431,'431,2959','c','ch','chunhua','','县','610430','029',12),(2960,'武功',431,'431,2960','w','wg','wugong','','县','610431','029',13),(2961,'兴平',431,'431,2961','x','xp','xingping','','市','610481','029',14),(2962,'临渭',432,'432,2962','l','lw','linwei','','区','610502','0913',1),(2963,'华县',432,'432,2963','h','hx','huaxian','','','610521','0913',2),(2964,'潼关',432,'432,2964','t','tg','tongguan','','县','610522','0913',3),(2965,'大荔',432,'432,2965','d','dl','dali','','县','610523','0913',4),(2966,'合阳',432,'432,2966','h','hy','heyang','','县','610524','0913',5),(2967,'澄城',432,'432,2967','c','cc','chengcheng','','县','610525','0913',6),(2968,'蒲城',432,'432,2968','p','pc','pucheng','','县','610526','0913',7),(2969,'白水',432,'432,2969','b','bs','baishui','','县','610527','0913',8),(2970,'富平',432,'432,2970','f','fp','fuping','','县','610528','0913',9),(2971,'韩城',432,'432,2971','h','hc','hancheng','','市','610581','0913',10),(2972,'华阴',432,'432,2972','h','hy','huayin','','市','610582','0913',11),(2973,'宝塔',433,'433,2973','b','bt','baota','','区','610602','0911',1),(2974,'延长',433,'433,2974','y','yz','yanzhang','','县','610621','0911',2),(2975,'延川',433,'433,2975','y','yc','yanchuan','','县','610622','0911',3),(2976,'子长',433,'433,2976','z','zz','zizhang','','县','610623','0911',4),(2977,'安塞',433,'433,2977','a','as','ansai','','县','610624','0911',5),(2978,'志丹',433,'433,2978','z','zd','zhidan','','县','610625','0911',6),(2979,'吴起',433,'433,2979','w','wq','wuqi','','县','610626','0911',7),(2980,'甘泉',433,'433,2980','g','gq','ganquan','','县','610627','0911',8),(2981,'富县',433,'433,2981','f','fx','fuxian','','','610628','0911',9),(2982,'洛川',433,'433,2982','l','lc','luochuan','','县','610629','0911',10),(2983,'宜川',433,'433,2983','y','yc','yichuan','','县','610630','0911',11),(2984,'黄龙',433,'433,2984','h','hl','huanglong','','县','610631','0911',12),(2985,'黄陵',433,'433,2985','h','hl','huangling','','县','610632','0911',13),(2986,'汉台',434,'434,2986','h','ht','hantai','','区','610702','0916',1),(2987,'南郑',434,'434,2987','n','nz','nanzheng','','县','610721','0916',2),(2988,'城固',434,'434,2988','c','cg','chenggu','','县','610722','0916',3),(2989,'洋县',434,'434,2989','y','yx','yangxian','','','610723','0916',4),(2990,'西乡',434,'434,2990','x','xx','xixiang','','县','610724','0916',5),(2991,'勉县',434,'434,2991','m','mx','mianxian','','','610725','0916',6),(2992,'宁强',434,'434,2992','n','nq','ningqiang','','县','610726','0916',7),(2993,'略阳',434,'434,2993','l','ly','lu:eyang','','县','610727','0916',8),(2994,'镇巴',434,'434,2994','z','zb','zhenba','','县','610728','0916',9),(2995,'留坝',434,'434,2995','l','lb','liuba','','县','610729','0916',10),(2996,'佛坪',434,'434,2996','f','fp','foping','','县','610730','0916',11),(2997,'榆阳',435,'435,2997','y','yy','yuyang','','区','610802','0912',1),(2998,'神木',435,'435,2998','s','sm','shenmu','','县','610821','0912',2),(2999,'府谷',435,'435,2999','f','fg','fugu','','县','610822','0912',3),(3000,'横山',435,'435,3000','h','hs','hengshan','','县','610823','0912',4),(3001,'靖边',435,'435,3001','j','jb','jingbian','','县','610824','0912',5),(3002,'定边',435,'435,3002','d','db','dingbian','','县','610825','0912',6),(3003,'绥德',435,'435,3003','s','sd','suide','','县','610826','0912',7),(3004,'米脂',435,'435,3004','m','mz','mizhi','','县','610827','0912',8),(3005,'佳县',435,'435,3005','j','jx','jiaxian','','','610828','0912',9),(3006,'吴堡',435,'435,3006','w','wb','wubao','','县','610829','0912',10),(3007,'清涧',435,'435,3007','q','qj','qingjian','','县','610830','0912',11),(3008,'子洲',435,'435,3008','z','zz','zizhou','','县','610831','0912',12),(3009,'汉滨',436,'436,3009','h','hb','hanbin','','区','610902','0915',1),(3010,'汉阴',436,'436,3010','h','hy','hanyin','','县','610921','0915',2),(3011,'石泉',436,'436,3011','s','sq','shiquan','','县','610922','0915',3),(3012,'宁陕',436,'436,3012','n','ns','ningshan','','县','610923','0915',4),(3013,'紫阳',436,'436,3013','z','zy','ziyang','','县','610924','0915',5),(3014,'岚皋',436,'436,3014','l','lg','langao','','县','610925','0915',6),(3015,'平利',436,'436,3015','p','pl','pingli','','县','610926','0915',7),(3016,'镇坪',436,'436,3016','z','zp','zhenping','','县','610927','0915',8),(3017,'旬阳',436,'436,3017','x','xy','xunyang','','县','610928','0915',9),(3018,'白河',436,'436,3018','b','bh','baihe','','县','610929','0915',10),(3019,'商州',437,'437,3019','s','sz','shangzhou','','区','611002','0914',1),(3020,'洛南',437,'437,3020','l','ln','luonan','','县','611021','0914',2),(3021,'丹凤',437,'437,3021','d','df','danfeng','','县','611022','0914',3),(3022,'商南',437,'437,3022','s','sn','shangnan','','县','611023','0914',4),(3023,'山阳',437,'437,3023','s','sy','shanyang','','县','611024','0914',5),(3024,'镇安',437,'437,3024','z','za','zhenan','','县','611025','0914',6),(3025,'柞水',437,'437,3025','z','zs','zuoshui','','县','611026','0914',7),(3026,'城关',438,'438,3026','c','cg','chengguan','','区','620102','0931',1),(3027,'七里河',438,'438,3027','q','qlh','qilihe','','区','620103','0931',2),(3028,'西固',438,'438,3028','x','xg','xigu','','区','620104','0931',3),(3029,'安宁',438,'438,3029','a','an','anning','','区','620105','0931',4),(3030,'红古',438,'438,3030','h','hg','honggu','','区','620111','0931',5),(3031,'永登',438,'438,3031','y','yd','yongdeng','','县','620121','0931',6),(3032,'皋兰',438,'438,3032','g','gl','gaolan','','县','620122','0931',7),(3033,'榆中',438,'438,3033','y','yz','yuzhong','','县','620123','0931',8),(3034,'镜铁',439,'439,3034','j','jt','jingtie','','区','430802','0937',1),(3035,'长城',439,'439,3035','z','zc','zhangcheng','','区','620201','0937',2),(3036,'雄关',439,'439,3036','x','xg','xiongguan','','区','620201','0937',3),(3037,'金川',440,'440,3037','j','jc','jinchuan','','区','620302','0935',1),(3038,'永昌',440,'440,3038','y','yc','yongchang','','县','620321','0935',2),(3039,'白银',441,'441,3039','b','by','baiyin','','区','620402','0943',1),(3040,'平川',441,'441,3040','p','pc','pingchuan','','区','620403','0943',2),(3041,'靖远',441,'441,3041','j','jy','jingyuan','','县','620421','0943',3),(3042,'会宁',441,'441,3042','h','hn','huining','','县','620422','0943',4),(3043,'景泰',441,'441,3043','j','jt','jingtai','','县','620423','0943',5),(3044,'秦州',442,'442,3044','q','qz','qinzhou','','区','620502','0938',1),(3045,'麦积',442,'442,3045','m','mj','maiji','','区','620503','0938',2),(3046,'清水',442,'442,3046','q','qs','qingshui','','县','620521','0938',3),(3047,'秦安',442,'442,3047','q','qa','qinan','','县','620522','0938',4),(3048,'甘谷',442,'442,3048','g','gg','gangu','','县','620523','0938',5),(3049,'武山',442,'442,3049','w','ws','wushan','','县','620524','0938',6),(3050,'张家川',442,'442,3050','z','zjc','zhangjiachuan','回族','自治县','620525','0938',7),(3051,'凉州',443,'443,3051','l','lz','liangzhou','','区','620602','0935',1),(3052,'民勤',443,'443,3052','m','mq','minqin','','县','620621','0935',2),(3053,'古浪',443,'443,3053','g','gl','gulang','','县','620622','0935',3),(3054,'天祝',443,'443,3054','t','tz','tianzhu','藏族','自治县','620623','0935',4),(3055,'甘州',444,'444,3055','g','gz','ganzhou','','区','620702','0936',1),(3056,'肃南',444,'444,3056','s','sn','sunan','裕固族','自治县','620721','0936',2),(3057,'民乐',444,'444,3057','m','ml','minle','','县','620722','0936',3),(3058,'临泽',444,'444,3058','l','lz','linze','','县','620723','0936',4),(3059,'高台',444,'444,3059','g','gt','gaotai','','县','620724','0936',5),(3060,'山丹',444,'444,3060','s','sd','shandan','','县','620725','0936',6),(3061,'崆峒',445,'445,3061','k','kt','kongtong','','区','620802','0933',1),(3062,'泾川',445,'445,3062','j','jc','jingchuan','','县','620821','0933',2),(3063,'灵台',445,'445,3063','l','lt','lingtai','','县','620822','0933',3),(3064,'崇信',445,'445,3064','c','cx','chongxin','','县','620823','0933',4),(3065,'华亭',445,'445,3065','h','ht','huating','','县','620824','0933',5),(3066,'庄浪',445,'445,3066','z','zl','zhuanglang','','县','620825','0933',6),(3067,'静宁',445,'445,3067','j','jn','jingning','','县','620826','0933',7),(3068,'肃州',446,'446,3068','s','sz','suzhou','','区','620902','0937',1),(3069,'金塔',446,'446,3069','j','jt','jinta','','县','620921','0937',2),(3070,'瓜州',446,'446,3070','g','gz','guazhou','','县','620922','0937',3),(3071,'肃北',446,'446,3071','s','sb','subei','蒙古族','自治县','620923','0937',4),(3072,'阿克塞',446,'446,3072','a','aks','akesai','哈萨克族','自治县','620924','0937',5),(3073,'玉门',446,'446,3073','y','ym','yumen','','市','620981','0937',6),(3074,'敦煌',446,'446,3074','d','dh','dunhuang','','市','620982','0937',7),(3075,'西峰',447,'447,3075','x','xf','xifeng','','区','621002','0934',1),(3076,'庆城',447,'447,3076','q','qc','qingcheng','','县','621021','0934',2),(3077,'环县',447,'447,3077','h','hx','huanxian','','','621022','0934',3),(3078,'华池',447,'447,3078','h','hc','huachi','','县','621023','0934',4),(3079,'合水',447,'447,3079','h','hs','heshui','','县','621024','0934',5),(3080,'正宁',447,'447,3080','z','zn','zhengning','','县','621025','0934',6),(3081,'宁县',447,'447,3081','n','nx','ningxian','','','621026','0934',7),(3082,'镇原',447,'447,3082','z','zy','zhenyuan','','县','621027','0934',8),(3083,'安定',448,'448,3083','a','ad','anding','','区','621102','0932',1),(3084,'通渭',448,'448,3084','t','tw','tongwei','','县','621121','0932',2),(3085,'陇西',448,'448,3085','l','lx','longxi','','县','621122','0932',3),(3086,'渭源',448,'448,3086','w','wy','weiyuan','','县','621123','0932',4),(3087,'临洮',448,'448,3087','l','lt','lintao','','县','621124','0932',5),(3088,'漳县',448,'448,3088','z','zx','zhangxian','','','621125','0932',6),(3089,'岷县',448,'448,3089','m','mx','minxian','','','621126','0932',7),(3090,'武都',449,'449,3090','w','wd','wudou','','区','621202','0939',1),(3091,'成县',449,'449,3091','c','cx','chengxian','','','621221','0939',2),(3092,'文县',449,'449,3092','w','wx','wenxian','','','621222','0939',3),(3093,'宕昌',449,'449,3093','d','dc','dangchang','','县','621223','0939',4),(3094,'康县',449,'449,3094','k','kx','kangxian','','','621224','0939',5),(3095,'西和',449,'449,3095','x','xh','xihe','','县','621225','0939',6),(3096,'礼县',449,'449,3096','l','lx','lixian','','','621226','0939',7),(3097,'徽县',449,'449,3097','h','hx','huixian','','','621227','0939',8),(3098,'两当',449,'449,3098','l','ld','liangdang','','县','621228','0939',9),(3099,'临夏',450,'450,3099','l','lx','linxia','','市','622901','0930',1),(3100,'临夏',450,'450,3100','l','lx','linxia','','县','622921','0930',2),(3101,'康乐',450,'450,3101','k','kl','kangle','','县','622922','0930',3),(3102,'永靖',450,'450,3102','y','yj','yongjing','','县','622923','0930',4),(3103,'广河',450,'450,3103','g','gh','guanghe','','县','622924','0930',5),(3104,'和政',450,'450,3104','h','hz','hezheng','','县','622925','0930',6),(3105,'东乡族',450,'450,3105','d','dxz','dongxiangzu','','自治县','622926','0930',7),(3106,'积石山',450,'450,3106','j','jss','jishishan','保安族东乡族撒拉族','自治县','622927','0930',8),(3107,'合作',451,'451,3107','h','hz','hezuo','','市','623001','0941',1),(3108,'临潭',451,'451,3108','l','lt','lintan','','县','623021','0941',2),(3109,'卓尼',451,'451,3109','z','zn','zhuoni','','县','623022','0941',3),(3110,'舟曲',451,'451,3110','z','zq','zhouqu','','县','623023','0941',4),(3111,'迭部',451,'451,3111','d','db','diebu','','县','623024','0941',5),(3112,'玛曲',451,'451,3112','m','mq','maqu','','县','623025','0941',6),(3113,'碌曲',451,'451,3113','l','lq','liuqu','','县','623026','0941',7),(3114,'夏河',451,'451,3114','x','xh','xiahe','','县','623027','0941',8),(3115,'城东',452,'452,3115','c','cd','chengdong','','区','630102','0971',1),(3116,'城中',452,'452,3116','c','cz','chengzhong','','区','630103','0971',2),(3117,'城西',452,'452,3117','c','cx','chengxi','','区','630104','0971',3),(3118,'城北',452,'452,3118','c','cb','chengbei','','区','630105','0971',4),(3119,'大通',452,'452,3119','d','dt','datong','回族土族','自治县','630121','0971',5),(3120,'湟中',452,'452,3120','h','hz','huangzhong','','县','630122','0971',6),(3121,'湟源',452,'452,3121','h','hy','huangyuan','','县','630123','0971',7),(3122,'乐都',453,'453,3122','l','ld','ledou','','区','632123','0972',1),(3123,'平安',453,'453,3123','p','pa','pingan','','县','632121','0972',2),(3124,'民和',453,'453,3124','m','mh','minhe','回族土族','自治县','632122','0972',3),(3125,'互助',453,'453,3125','h','hz','huzhu','土族','自治县','632126','0972',4),(3126,'化隆',453,'453,3126','h','hl','hualong','回族','自治县','632127','0972',5),(3127,'循化',453,'453,3127','x','xh','xunhua','撒拉族','自治县','632128','0972',6),(3128,'门源',454,'454,3128','m','my','menyuan','回族','自治县','632221','0970',1),(3129,'祁连',454,'454,3129','q','ql','qilian','','县','632222','0970',2),(3130,'海晏',454,'454,3130','h','hy','haiyan','','县','632223','0970',3),(3131,'刚察',454,'454,3131','g','gc','gangcha','','县','632224','0970',4),(3132,'同仁',455,'455,3132','t','tr','tongren','','县','632321','0973',1),(3133,'尖扎',455,'455,3133','j','jz','jianzha','','县','632322','0973',2),(3134,'泽库',455,'455,3134','z','zk','zeku','','县','632323','0973',3),(3135,'河南',455,'455,3135','h','hn','henan','蒙古族','自治县','632324','0973',4),(3136,'共和',456,'456,3136','g','gh','gonghe','','县','632521','0974',1),(3137,'同德',456,'456,3137','t','td','tongde','','县','632522','0974',2),(3138,'贵德',456,'456,3138','g','gd','guide','','县','632523','0974',3),(3139,'兴海',456,'456,3139','x','xh','xinghai','','县','632524','0974',4),(3140,'贵南',456,'456,3140','g','gn','guinan','','县','632525','0974',5),(3141,'玛沁',457,'457,3141','m','mq','maqin','','县','632621','0975',1),(3142,'班玛',457,'457,3142','b','bm','banma','','县','632622','0975',2),(3143,'甘德',457,'457,3143','g','gd','gande','','县','632623','0975',3),(3144,'达日',457,'457,3144','d','dr','dari','','县','632624','0975',4),(3145,'久治',457,'457,3145','j','jz','jiuzhi','','县','632625','0975',5),(3146,'玛多',457,'457,3146','m','md','maduo','','县','632626','0975',6),(3147,'玉树',458,'458,3147','y','ys','yushu','','市','632721','0976',1),(3148,'杂多',458,'458,3148','z','zd','zaduo','','县','632722','0976',2),(3149,'治多',458,'458,3149','z','zd','zhiduo','','县','632724','0976',3),(3150,'囊谦',458,'458,3150','n','nq','nangqian','','县','632725','0976',4),(3151,'曲麻莱',458,'458,3151','q','qml','qumalai','','县','632726','0976',5),(3152,'大柴旦',459,'459,3152','d','dcd','dachaidan','','行委','','0977',1),(3153,'冷湖',459,'459,3153','l','lh','lenghu','','行委','','0977',2),(3154,'茫崖',459,'459,3154','m','my','mangya','','行委','','0977',3),(3155,'格尔木',459,'459,3155','g','gem','geermu','','市','632801','0977',4),(3156,'德令哈',459,'459,3156','d','dlh','delingha','','市','632802','0977',5),(3157,'乌兰',459,'459,3157','w','wl','wulan','','县','632821','0977',6),(3158,'都兰',459,'459,3158','d','dl','doulan','','县','632822','0977',7),(3159,'天峻',459,'459,3159','t','tj','tianjun','','县','632823','0977',8),(3160,'兴庆',460,'460,3160','x','xq','xingqing','','区','640104','0951',1),(3161,'西夏',460,'460,3161','x','xx','xixia','','区','640105','0951',2),(3162,'金凤',460,'460,3162','j','jf','jinfeng','','区','640106','0951',3),(3163,'永宁',460,'460,3163','y','yn','yongning','','县','640121','0951',4),(3164,'贺兰',460,'460,3164','h','hl','helan','','县','640122','0951',5),(3165,'灵武',460,'460,3165','l','lw','lingwu','','市','640181','0951',6),(3166,'大武口',461,'461,3166','d','dwk','dawukou','','区','640202','0952',1),(3167,'惠农',461,'461,3167','h','hn','huinong','','区','640205','0952',2),(3168,'平罗',461,'461,3168','p','pl','pingluo','','县','640221','0952',3),(3169,'红寺堡',462,'462,3169','h','hsb','hongsibao','','区','','0953',1),(3170,'利通',462,'462,3170','l','lt','litong','','区','640302','0953',2),(3171,'盐池',462,'462,3171','y','yc','yanchi','','县','640323','0953',3),(3172,'同心',462,'462,3172','t','tx','tongxin','','县','640324','0953',4),(3173,'青铜峡',462,'462,3173','q','qtx','qingtongxia','','市','640381','0953',5),(3174,'原州',463,'463,3174','y','yz','yuanzhou','','区','640402','0954',1),(3175,'西吉',463,'463,3175','x','xj','xiji','','县','640422','0954',2),(3176,'隆德',463,'463,3176','l','ld','longde','','县','640423','0954',3),(3177,'泾源',463,'463,3177','j','jy','jingyuan','','县','640424','0954',4),(3178,'彭阳',463,'463,3178','p','py','pengyang','','县','640425','0954',5),(3179,'沙坡头',464,'464,3179','s','spt','shapotou','','区','640502','',1),(3180,'中宁',464,'464,3180','z','zn','zhongning','','县','640521','',2),(3181,'海原',464,'464,3181','h','hy','haiyuan','','县','640522','',3),(3182,'天山',467,'467,3182','t','ts','tianshan','','区','650102','0991',1),(3183,'沙依巴克',467,'467,3183','s','sybk','shayibake','','区','650103','0991',2),(3184,'新市',467,'467,3184','x','xs','xinshi','','区','650104','0991',3),(3185,'水磨沟',467,'467,3185','s','smg','shuimogou','','区','650105','0991',4),(3186,'头屯河',467,'467,3186','t','tth','toutunhe','','区','650106','0991',5),(3187,'达坂城',467,'467,3187','d','dbc','dabancheng','','区','650107','0991',6),(3188,'米东',467,'467,3188','m','md','midong','','区','650109','0991',7),(3189,'乌鲁木齐',467,'467,3189','w','wlmq','wulumuqi','','县','650121','0991',8),(3190,'独山子',468,'468,3190','d','dsz','dushanzi','','区','650202','0990',1),(3191,'克拉玛依',468,'468,3191','k','klmy','kelamayi','','区','650203','0990',2),(3192,'白碱滩',468,'468,3192','b','bjt','baijiantan','','区','650204','0990',3),(3193,'乌尔禾',468,'468,3193','w','weh','wuerhe','','区','650205','0990',4),(3194,'吐鲁番',469,'469,3194','t','tlf','tulufan','','市','652101','0995',1),(3195,'鄯善',469,'469,3195','s','ss','shanshan','','县','652122','0995',2),(3196,'托克逊',469,'469,3196','t','tkx','tuokexun','','县','652123','0995',3),(3197,'哈密',470,'470,3197','h','hm','hami','','市','652201','0902',1),(3198,'巴里坤',470,'470,3198','b','blk','balikun','哈萨克','自治县','652222','0902',2),(3199,'伊吾',470,'470,3199','y','yw','yiwu','','县','652223','0902',3),(3200,'昌吉',471,'471,3200','c','cj','changji','','市','652301','0994',1),(3201,'阜康',471,'471,3201','f','fk','fukang','','市','652302','0994',2),(3202,'呼图壁',471,'471,3202','h','htb','hutubi','','县','652323','0994',3),(3203,'玛纳斯',471,'471,3203','m','mns','manasi','','县','652324','0994',4),(3204,'奇台',471,'471,3204','q','qt','qitai','','县','652325','0994',5),(3205,'吉木萨尔',471,'471,3205','j','jmse','jimusaer','','县','652327','0994',6),(3206,'木垒',471,'471,3206','m','ml','mulei','哈萨克','自治县','652328','0994',7),(3207,'阿拉山口',472,'472,3207','a','alsk','alashankou','','市','','0909',1),(3208,'博乐',472,'472,3208','b','bl','bole','','市','652701','0909',2),(3209,'精河',472,'472,3209','j','jh','jinghe','','县','652722','0909',3),(3210,'温泉',472,'472,3210','w','wq','wenquan','','县','652723','0909',4),(3211,'库尔勒',473,'473,3211','k','kel','kuerle','','市','652801','0996',1),(3212,'轮台',473,'473,3212','l','lt','luntai','','县','652822','0996',2),(3213,'尉犁',473,'473,3213','w','wl','weili','','县','652823','0996',3),(3214,'若羌',473,'473,3214','r','rq','ruoqiang','','县','652824','0996',4),(3215,'且末',473,'473,3215','q','qm','qiemo','','县','652825','0996',5),(3216,'焉耆',473,'473,3216','y','yq','yanqi','回族','自治县','652826','0996',6),(3217,'和静',473,'473,3217','h','hj','hejing','','县','652827','0996',7),(3218,'和硕',473,'473,3218','h','hs','heshuo','','县','652828','0996',8),(3219,'博湖',473,'473,3219','b','bh','bohu','','县','博湖县','0996',9),(3220,'阿克苏',474,'474,3220','a','aks','akesu','','市','652901','',1),(3221,'温宿',474,'474,3221','w','ws','wensu','','县','652922','',2),(3222,'库车',474,'474,3222','k','kc','kuche','','县','652923','',3),(3223,'沙雅',474,'474,3223','s','sy','shaya','','县','652924','',4),(3224,'新和',474,'474,3224','x','xh','xinhe','','县','652925','',5),(3225,'拜城',474,'474,3225','b','bc','baicheng','','县','652926','',6),(3226,'乌什',474,'474,3226','w','ws','wushen','','县','652927','',7),(3227,'阿瓦提',474,'474,3227','a','awt','awati','','县','652928','',8),(3228,'柯坪',474,'474,3228','k','kp','keping','','县','652929','',9),(3229,'阿图什',475,'475,3229','a','ats','atushen','','市','653001','0908',1),(3230,'阿克陶',475,'475,3230','a','akt','aketao','','县','653022','0908',2),(3231,'阿合奇',475,'475,3231','a','ahq','aheqi','','县','653023','0997',3),(3232,'乌恰',475,'475,3232','w','wq','wuqia','','县','653024','0908',4),(3233,'喀什',476,'476,3233','k','ks','kashen','','市','653101','0998',1),(3234,'疏附',476,'476,3234','s','sf','shufu','','县','653121','0998',2),(3235,'疏勒',476,'476,3235','s','sl','shule','','县','653122','0998',3),(3236,'英吉沙',476,'476,3236','y','yjs','yingjisha','','县','653123','0998',4),(3237,'泽普',476,'476,3237','z','zp','zepu','','县','653124','0998',5),(3238,'莎车',476,'476,3238','s','sc','shache','','县','653125','0998',6),(3239,'叶城',476,'476,3239','y','yc','yecheng','','县','653126','0998',7),(3240,'麦盖提',476,'476,3240','m','mgt','maigaiti','','县','653127','0998',8),(3241,'岳普湖',476,'476,3241','y','yph','yuepuhu','','县','653128','0998',9),(3242,'伽师',476,'476,3242','j','js','jiashi','','县','653129','0998',10),(3243,'巴楚',476,'476,3243','b','bc','bachu','','县','653130','0998',11),(3244,'塔什库尔干',476,'476,3244','t','tskeg','tashenkuergan','塔吉克','自治县','653131','0998',12),(3245,'和田',477,'477,3245','h','ht','hetian','','市','653201','0903',1),(3246,'和田',477,'477,3246','h','ht','hetian','','县','653221','0903',2),(3247,'墨玉',477,'477,3247','m','my','moyu','','县','653222','0903',3),(3248,'皮山',477,'477,3248','p','ps','pishan','','县','653223','0903',4),(3249,'洛浦',477,'477,3249','l','lp','luopu','','县','653224','0903',5),(3250,'策勒',477,'477,3250','c','cl','cele','','县','653225','0903',6),(3251,'于田',477,'477,3251','y','yt','yutian','','县','653226','0903',7),(3252,'民丰',477,'477,3252','m','mf','minfeng','','县','653227','0903',8),(3253,'伊宁',478,'478,3253','y','yn','yining','','市','654002','0999',1),(3254,'奎屯',478,'478,3254','k','kt','kuitun','','市','654003','0999',2),(3255,'伊宁',478,'478,3255','y','yn','yining','','县','654021','0999',3),(3256,'察布查尔锡伯',478,'478,3256','c','cbcexb','chabuchaerxibo','','自治县','654022','0999',4),(3257,'霍城',478,'478,3257','h','hc','huocheng','','县','654023','0999',5),(3258,'巩留',478,'478,3258','g','gl','gongliu','','县','654024','0999',6),(3259,'新源',478,'478,3259','x','xy','xinyuan','','县','654025','0999',7),(3260,'昭苏',478,'478,3260','z','zs','zhaosu','','县','654026','0999',8),(3261,'特克斯',478,'478,3261','t','tks','tekesi','','县','654027','0999',9),(3262,'尼勒克',478,'478,3262','n','nlk','nileke','','县','654028','0999',10),(3263,'塔城',479,'479,3263','t','tc','tacheng','','市','654201','0901',1),(3264,'乌苏',479,'479,3264','w','ws','wusu','','市','654202','0901',2),(3265,'额敏',479,'479,3265','e','em','emin','','县','654221','0901',3),(3266,'沙湾',479,'479,3266','s','sw','shawan','','县','654223','0901',4),(3267,'托里',479,'479,3267','t','tl','tuoli','','县','654224','0901',5),(3268,'裕民',479,'479,3268','y','ym','yumin','','县','654225','0901',6),(3269,'和布克赛尔',479,'479,3269','h','hbkse','hebukesaier','蒙古','自治县','654226','0901',7),(3270,'阿勒泰',480,'480,3270','a','alt','aletai','','市','654301','0906',1),(3271,'布尔津',480,'480,3271','b','bej','buerjin','','县','654321','0906',2),(3272,'富蕴',480,'480,3272','f','fy','fuyun','','县','654322','0906',3),(3273,'福海',480,'480,3273','f','fh','fuhai','','县','654323','0906',4),(3274,'哈巴河',480,'480,3274','h','hbh','habahe','','县','654324','0906',5),(3275,'青河',480,'480,3275','q','qh','qinghe','','县','654325','0906',6),(3276,'吉木乃',480,'480,3276','j','jmn','jimunai','','县','654326','0906',7),(3277,'松山',485,'485,3277','s','ss','songshan','','区','6300100','02',1),(3278,'信义',485,'485,3278','x','xy','xinyi','','区','6300200','02',2),(3279,'大安',485,'485,3279','d','da','daan','','区','6300300','02',3),(3280,'中山',485,'485,3280','z','zs','zhongshan','','区','6300400','02',4),(3281,'中正',485,'485,3281','z','zz','zhongzheng','','区','6300500','02',5),(3282,'大同',485,'485,3282','d','dt','datong','','区','6300600','02',6),(3283,'万华',485,'485,3283','w','wh','wanhua','','区','6300700','02',7),(3284,'文山',485,'485,3284','w','ws','wenshan','','区','6300800','02',8),(3285,'南港',485,'485,3285','n','ng','nangang','','区','6300900','02',9),(3286,'内湖',485,'485,3286','n','nh','neihu','','区','6301000','02',10),(3287,'士林',485,'485,3287','s','sl','shilin','','区','6301100','02',11),(3288,'北投',485,'485,3288','b','bt','beitou','','区','6301200','02',12),(3289,'盐埕',486,'486,3289','y','yc','yancheng','','区','6400100','07',1),(3290,'鼓山',486,'486,3290','g','gs','gushan','','区','6400200','07',2),(3291,'左营',486,'486,3291','z','zy','zuoying','','区','6400300','07',3),(3292,'楠梓',486,'486,3292','n','nz','nanzi','','区','6400400','07',4),(3293,'三民',486,'486,3293','s','sm','sanmin','','区','6400500','07',5),(3294,'新兴',486,'486,3294','x','xx','xinxing','','区','6400600','07',6),(3295,'前金',486,'486,3295','q','qj','qianjin','','区','6400700','07',7),(3296,'苓雅',486,'486,3296','l','ly','lingya','','区','6400800','07',8),(3297,'前镇',486,'486,3297','q','qz','qianzhen','','区','6400900','07',9),(3298,'旗津',486,'486,3298','q','qj','qijin','','区','6401000','07',10),(3299,'小港',486,'486,3299','x','xg','xiaogang','','区','6401100','07',11),(3300,'中正',487,'487,3300','z','zz','zhongzheng','','区','1001701','02',1),(3301,'七堵',487,'487,3301','q','qd','qidu','','区','1001702','02',2),(3302,'暖暖',487,'487,3302','n','nn','nuannuan','','区','1001703','02',3),(3303,'仁爱',487,'487,3303','r','ra','renai','','区','1001704','02',4),(3304,'中山',487,'487,3304','z','zs','zhongshan','','区','1001705','02',5),(3305,'安乐',487,'487,3305','a','al','anle','','区','1001706','02',6),(3306,'信义',487,'487,3306','x','xy','xinyi','','区','1001707','02',7),(3307,'大安',488,'488,3307','d','da','daan','','区','','04',1),(3308,'神冈',488,'488,3308','s','sg','shengang','','区','','04',2),(3309,'石冈',488,'488,3309','s','sg','shigang','','区','','04',3),(3310,'东势',488,'488,3310','d','ds','dongshi','','区','','04',4),(3311,'新社',488,'488,3311','x','xs','xinshe','','区','','04',5),(3312,'和平',488,'488,3312','h','hp','heping','','区','','04',6),(3313,'大肚',488,'488,3313','d','dd','dadu','','区','','04',7),(3314,'沙鹿',488,'488,3314','s','sl','shalu','','区','','04',8),(3315,'龙井',488,'488,3315','l','lj','longjing','','区','','04',9),(3316,'梧栖',488,'488,3316','w','wq','wuqi','','区','','04',10),(3317,'清水',488,'488,3317','q','qs','qingshui','','区','','04',11),(3318,'大甲',488,'488,3318','d','dj','dajia','','区','','04',12),(3319,'外埔',488,'488,3319','w','wp','waipu','','区','','04',13),(3320,'大雅',488,'488,3320','d','dy','daya','','区','','04',14),(3321,'潭子',488,'488,3321','t','tz','tanzi','','区','','04',15),(3322,'后里',488,'488,3322','h','hl','houli','','区','','04',16),(3323,'丰原',488,'488,3323','f','fy','fengyuan','','区','','04',17),(3324,'乌日',488,'488,3324','w','wr','wuri','','区','','04',18),(3325,'雾峰',488,'488,3325','w','wf','wufeng','','区','','04',19),(3326,'大里',488,'488,3326','d','dl','dali','','区','','04',20),(3327,'太平',488,'488,3327','t','tp','taiping','','区','','04',21),(3328,'中区',488,'488,3328','z','zq','zhongqu','','','1001901','04',22),(3329,'东区',488,'488,3329','d','dq','dongqu','','','1001902','04',23),(3330,'南区',488,'488,3330','n','nq','nanqu','','','1001903','04',24),(3331,'西区',488,'488,3331','x','xq','xiqu','','','1001904','04',25),(3332,'北区',488,'488,3332','b','bq','beiqu','','','1001905','04',26),(3333,'西屯',488,'488,3333','x','xt','xitun','','区','1001906','04',27),(3334,'南屯',488,'488,3334','n','nt','nantun','','区','1001907','04',28),(3335,'北屯',488,'488,3335','b','bt','beitun','','区','1001908','04',29),(3336,'东区',489,'489,3336','d','dq','dongqu','','','1002101','06',1),(3337,'南区',489,'489,3337','n','nq','nanqu','','','1002102','06',2),(3338,'北区',489,'489,3338','b','bq','beiqu','','','1002104','06',3),(3339,'安南',489,'489,3339','a','an','annan','','区','1002106','06',4),(3340,'安平',489,'489,3340','a','ap','anping','','区','1002107','06',5),(3341,'中西',489,'489,3341','z','zx','zhongxi','','区','1002108','06',6),(3342,'东区',490,'490,3342','d','dq','dongqu','','','1001801','',1),(3343,'北区',490,'490,3343','b','bq','beiqu','','','1001802','',2),(3344,'香山',490,'490,3344','x','xs','xiangshan','','区','1001803','',3),(3345,'东区',491,'491,3345','d','dq','dongqu','','','1002001','05',1),(3346,'西区',491,'491,3346','x','xq','xiqu','','','1002002','05',2),(3347,'板桥',492,'492,3347','b','bq','banqiao','','区','','',1),(3348,'瑞芳',492,'492,3348','r','rf','ruifang','','区','','',2),(3349,'八里',492,'492,3349','b','bl','bali','','区','','',3),(3350,'深坑',492,'492,3350','s','sk','shenkeng','','区','','',4),(3351,'三芝',492,'492,3351','s','sz','sanzhi','','区','','',5),(3352,'金山',492,'492,3352','j','js','jinshan','','区','','',6),(3353,'万里',492,'492,3353','w','wl','wanli','','区','','',7),(3354,'贡寮',492,'492,3354','g','gl','gongliao','','区','','',8),(3355,'石门',492,'492,3355','s','sm','shimen','','区','','',9),(3356,'双溪',492,'492,3356','s','sx','shuangxi','','区','','',10),(3357,'石碇',492,'492,3357','s','sd','shiding','','区','','',11),(3358,'坪林',492,'492,3358','p','pl','pinglin','','区','','',12),(3359,'乌来',492,'492,3359','w','wl','wulai','','区','','',13),(3360,'泰山',492,'492,3360','t','ts','taishan','','区','','',14),(3361,'五股',492,'492,3361','w','wg','wugu','','区','','',15),(3362,'莺歌',492,'492,3362','y','yg','yingge','','区','','',16),(3363,'中和',492,'492,3363','z','zh','zhonghe','','区','','',17),(3364,'新庄',492,'492,3364','x','xz','xinzhuang','','区','','',18),(3365,'三重',492,'492,3365','s','sz','sanzhong','','区','','',19),(3366,'新店',492,'492,3366','x','xd','xindian','','区','','',20),(3367,'土城',492,'492,3367','t','tc','tucheng','','区','','',21),(3368,'永和',492,'492,3368','y','yh','yonghe','','区','','',22),(3369,'芦洲',492,'492,3369','l','lz','luzhou','','区','','',23),(3370,'汐止',492,'492,3370','x','xz','xizhi','','区','','',24),(3371,'树林',492,'492,3371','s','sl','shulin','','区','','',25),(3372,'淡水',492,'492,3372','d','ds','danshui','','区','','',26),(3373,'三峡',492,'492,3373','s','sx','sanxia','','区','','',27),(3374,'林口',492,'492,3374','l','lk','linkou','','区','','',28),(3375,'平溪',492,'492,3375','p','px','pingxi','','区','','',29),(3376,'宜兰',493,'493,3376','y','yl','yilan','','市','1000201','',1),(3377,'罗东',493,'493,3377','l','ld','luodong','','镇','1000202','',2),(3378,'苏澳',493,'493,3378','s','sa','suao','','镇','1000203','',3),(3379,'头城',493,'493,3379','t','tc','toucheng','','乡','1000204','',4),(3380,'礁溪',493,'493,3380','j','jx','jiaoxi','','乡','1000205','',5),(3381,'壮围',493,'493,3381','z','zw','zhuangwei','','乡','1000206','',6),(3382,'员山',493,'493,3382','y','ys','yuanshan','','乡','1000207','',7),(3383,'冬山',493,'493,3383','d','ds','dongshan','','乡','1000208','',8),(3384,'五结',493,'493,3384','w','wj','wujie','','乡','1000209','',9),(3385,'三星',493,'493,3385','s','sx','sanxing','','乡','1000210','',10),(3386,'大同',493,'493,3386','d','dt','datong','','乡','1000211','',11),(3387,'南澳',493,'493,3387','n','na','nanao','','乡','1000212','',12),(3388,'桃园',494,'494,3388','t','ty','taoyuan','','市','1000301','',1),(3389,'中坜',494,'494,3389','z','zl','zhongli','','市','1000302','',2),(3390,'大溪',494,'494,3390','d','dx','daxi','','镇','1000303','',3),(3391,'杨梅',494,'494,3391','y','ym','yangmei','','镇','1000304','',4),(3392,'芦竹',494,'494,3392','l','lz','luzhu','','乡','1000305','',5),(3393,'大园',494,'494,3393','d','dy','dayuan','','乡','1000306','',6),(3394,'龟山',494,'494,3394','g','gs','guishan','','乡','1000307','',7),(3395,'八德',494,'494,3395','b','bd','bade','','市','1000308','',8),(3396,'龙潭',494,'494,3396','l','lt','longtan','','乡','1000309','',9),(3397,'平镇',494,'494,3397','p','pz','pingzhen','','市','1000310','',10),(3398,'新屋',494,'494,3398','x','xw','xinwu','','乡','1000311','',11),(3399,'观音',494,'494,3399','g','gy','guanyin','','乡','1000312','',12),(3400,'复兴',494,'494,3400','f','fx','fuxing','','乡','1000313','',13),(3401,'竹北',495,'495,3401','z','zb','zhubei','','市','1000401','',1),(3402,'竹东',495,'495,3402','z','zd','zhudong','','镇','1000402','',2),(3403,'新埔',495,'495,3403','x','xp','xinpu','','镇','1000403','',3),(3404,'关西',495,'495,3404','g','gx','guanxi','','镇','1000404','',4),(3405,'湖口',495,'495,3405','h','hk','hukou','','乡','1000405','',5),(3406,'新丰',495,'495,3406','x','xf','xinfeng','','乡','1000406','',6),(3407,'芎林',495,'495,3407','x','xl','xionglin','','乡','1000407','',7),(3408,'横山',495,'495,3408','h','hs','hengshan','','乡','1000408','',8),(3409,'北埔',495,'495,3409','b','bp','beipu','','乡','1000409','',9),(3410,'宝山',495,'495,3410','b','bs','baoshan','','乡','1000410','',10),(3411,'峨眉',495,'495,3411','e','em','emei','','乡','1000411','',11),(3412,'尖石',495,'495,3412','j','js','jianshi','','乡','1000412','',12),(3413,'五峰',495,'495,3413','w','wf','wufeng','','乡','1000413','',13),(3414,'苗栗',496,'496,3414','m','ml','miaoli','','市','1000501','',1),(3415,'苑里',496,'496,3415','y','yl','yuanli','','镇','1000502','',2),(3416,'通霄',496,'496,3416','t','tx','tongxiao','','镇','1000503','',3),(3417,'竹南',496,'496,3417','z','zn','zhunan','','镇','1000504','',4),(3418,'头份',496,'496,3418','t','tf','toufen','','镇','1000505','',5),(3419,'后龙',496,'496,3419','h','hl','houlong','','镇','1000506','',6),(3420,'卓兰',496,'496,3420','z','zl','zhuolan','','镇','1000507','',7),(3421,'大湖',496,'496,3421','d','dh','dahu','','乡','1000508','',8),(3422,'公馆',496,'496,3422','g','gg','gongguan','','乡','1000509','',9),(3423,'铜锣',496,'496,3423','t','tl','tongluo','','乡','1000510','',10),(3424,'南庄',496,'496,3424','n','nz','nanzhuang','','乡','1000511','',11),(3425,'头屋',496,'496,3425','t','tw','touwu','','乡','1000512','',12),(3426,'三义',496,'496,3426','s','sy','sanyi','','乡','1000513','',13),(3427,'西湖',496,'496,3427','x','xh','xihu','','乡','1000514','',14),(3428,'造桥',496,'496,3428','z','zq','zaoqiao','','乡','1000515','',15),(3429,'三湾',496,'496,3429','s','sw','sanwan','','乡','1000516','',16),(3430,'狮潭',496,'496,3430','s','st','shitan','','乡','1000517','',17),(3431,'泰安',496,'496,3431','t','ta','taian','','乡','1000518','',18),(3432,'彰化',497,'497,3432','z','zh','zhanghua','','市','1000701','',1),(3433,'鹿港',497,'497,3433','l','lg','lugang','','镇','1000702','',2),(3434,'和美',497,'497,3434','h','hm','hemei','','镇','1000703','',3),(3435,'线西',497,'497,3435','x','xx','xianxi','','乡','1000704','',4),(3436,'伸港',497,'497,3436','s','sg','shengang','','乡','1000705','',5),(3437,'福兴',497,'497,3437','f','fx','fuxing','','乡','1000706','',6),(3438,'秀水',497,'497,3438','x','xs','xiushui','','乡','1000707','',7),(3439,'花坛',497,'497,3439','h','ht','huatan','','乡','1000708','',8),(3440,'芬园',497,'497,3440','f','fy','fenyuan','','乡','1000709','',9),(3441,'员林',497,'497,3441','y','yl','yuanlin','','镇','1000710','',10),(3442,'溪湖',497,'497,3442','x','xh','xihu','','镇','1000711','',11),(3443,'田中',497,'497,3443','t','tz','tianzhong','','镇','1000712','',12),(3444,'大村',497,'497,3444','d','dc','dacun','','乡','1000713','',13),(3445,'埔盐',497,'497,3445','p','py','puyan','','乡','1000714','',14),(3446,'埔心',497,'497,3446','p','px','puxin','','乡','1000715','',15),(3447,'永靖',497,'497,3447','y','yj','yongjing','','乡','1000716','',16),(3448,'社头',497,'497,3448','s','st','shetou','','乡','1000717','',17),(3449,'二水',497,'497,3449','e','es','ershui','','乡','1000718','',18),(3450,'北斗',497,'497,3450','b','bd','beidou','','镇','1000719','',19),(3451,'二林',497,'497,3451','e','el','erlin','','镇','1000720','',20),(3452,'田尾',497,'497,3452','t','tw','tianwei','','乡','1000721','',21),(3453,'埤头',497,'497,3453','p','pt','pitou','','乡','1000722','',22),(3454,'芳苑',497,'497,3454','f','fy','fangyuan','','乡','1000723','',23),(3455,'大城',497,'497,3455','d','dc','dacheng','','乡','1000724','',24),(3456,'竹塘',497,'497,3456','z','zt','zhutang','','乡','1000725','',25),(3457,'溪州',497,'497,3457','x','xz','xizhou','','乡','1000726','',26),(3458,'南投',498,'498,3458','n','nt','nantou','','市','1000801','',1),(3459,'南投',498,'498,3459','n','nt','nantou','','镇','1000802','',2),(3460,'草屯',498,'498,3460','c','ct','caotun','','镇','1000803','',3),(3461,'竹山',498,'498,3461','z','zs','zhushan','','镇','1000804','',4),(3462,'集集',498,'498,3462','j','jj','jiji','','镇','1000805','',5),(3463,'名间',498,'498,3463','m','mj','mingjian','','乡','1000806','',6),(3464,'鹿谷',498,'498,3464','l','lg','lugu','','乡','1000807','',7),(3465,'中寮',498,'498,3465','z','zl','zhongliao','','乡','1000808','',8),(3466,'鱼池',498,'498,3466','y','yc','yuchi','','乡','1000809','',9),(3467,'国姓',498,'498,3467','g','gx','guoxing','','乡','1000810','',10),(3468,'水里',498,'498,3468','s','sl','shuili','','乡','1000811','',11),(3469,'信义',498,'498,3469','x','xy','xinyi','','乡','1000812','',12),(3470,'仁爱',498,'498,3470','r','ra','renai','','乡','1000813','',13),(3471,'斗六',499,'499,3471','d','dl','douliu','','市','1000901','',1),(3472,'斗南',499,'499,3472','d','dn','dounan','','镇','1000902','',2),(3473,'虎尾',499,'499,3473','h','hw','huwei','','镇','1000903','',3),(3474,'西螺',499,'499,3474','x','xl','xiluo','','镇','1000904','',4),(3475,'土库',499,'499,3475','t','tk','tuku','','镇','1000905','',5),(3476,'北港',499,'499,3476','b','bg','beigang','','镇','1000906','',6),(3477,'古坑',499,'499,3477','g','gk','gukeng','','乡','1000907','',7),(3478,'大埤',499,'499,3478','d','dp','dapi','','乡','1000908','',8),(3479,'莿桐',499,'499,3479','c','ct','citong','','乡','1000909','',9),(3480,'林内',499,'499,3480','l','ln','linnei','','乡','1000910','',10),(3481,'二仑',499,'499,3481','e','el','erlun','','乡','1000911','',11),(3482,'仑背',499,'499,3482','l','lb','lunbei','','乡','1000912','',12),(3483,'麦寮',499,'499,3483','m','ml','mailiao','','乡','1000913','',13),(3484,'东势',499,'499,3484','d','ds','dongshi','','乡','1000914','',14),(3485,'褒忠',499,'499,3485','b','bz','baozhong','','乡','1000915','',15),(3486,'台西',499,'499,3486','t','tx','taixi','','乡','1000916','',16),(3487,'元长',499,'499,3487','y','yz','yuanzhang','','乡','1000917','',17),(3488,'四湖',499,'499,3488','s','sh','sihu','','乡','1000918','',18),(3489,'口湖',499,'499,3489','k','kh','kouhu','','乡','1000919','',19),(3490,'水林',499,'499,3490','s','sl','shuilin','','乡','1000920','',20),(3491,'太保',500,'500,3491','t','tb','taibao','','市','','',1),(3492,'朴子',500,'500,3492','p','pz','pozi','','市','','',2),(3493,'布袋',500,'500,3493','b','bd','budai','','镇','','',3),(3494,'大林',500,'500,3494','d','dl','dalin','','镇','10010','',4),(3495,'民雄',500,'500,3495','m','mx','minxiong','','乡','1001001','',5),(3496,'溪口',500,'500,3496','x','xk','xikou','','乡','1001002','',6),(3497,'新港',500,'500,3497','x','xg','xingang','','乡','1001003','',7),(3498,'六脚',500,'500,3498','l','lj','liujiao','','乡','1001004','',8),(3499,'东石',500,'500,3499','d','ds','dongshi','','乡','1001005','',9),(3500,'义竹',500,'500,3500','y','yz','yizhu','','乡','1001006','',10),(3501,'鹿草',500,'500,3501','l','lc','lucao','','乡','1001007','',11),(3502,'水上',500,'500,3502','s','ss','shuishang','','乡','1001008','',12),(3503,'中埔',500,'500,3503','z','zp','zhongpu','','乡','1001009','',13),(3504,'竹崎',500,'500,3504','z','zq','zhuqi','','乡','1001010','',14),(3505,'梅山',500,'500,3505','m','ms','meishan','','乡','1001011','',15),(3506,'番路',500,'500,3506','f','fl','fanlu','','乡','1001012','',16),(3507,'大埔',500,'500,3507','d','dp','dapu','','乡','1001013','',17),(3508,'阿里山',500,'500,3508','a','als','alishan','','乡','1001014','',18),(3509,'屏东',501,'501,3509','p','pd','pingdong','','市','1001301','',1),(3510,'潮州',501,'501,3510','c','cz','chaozhou','','镇','1001302','',2),(3511,'东港',501,'501,3511','d','dg','donggang','','镇','1001303','',3),(3512,'恒春',501,'501,3512','h','hc','hengchun','','镇','1001304','',4),(3513,'万丹',501,'501,3513','w','wd','wandan','','乡','1001305','',5),(3514,'长治',501,'501,3514','z','zz','zhangzhi','','乡','1001306','',6),(3515,'麟洛',501,'501,3515','l','ll','linluo','','乡','1001307','',7),(3516,'九如',501,'501,3516','j','jr','jiuru','','乡','1001308','',8),(3517,'里港',501,'501,3517','l','lg','ligang','','乡','1001309','',9),(3518,'盐埔',501,'501,3518','y','yp','yanpu','','乡','1001310','',10),(3519,'高树',501,'501,3519','g','gs','gaoshu','','乡','1001311','',11),(3520,'万峦',501,'501,3520','w','wl','wanluan','','乡','1001312','',12),(3521,'内埔',501,'501,3521','n','np','neipu','','乡','1001313','',13),(3522,'竹田',501,'501,3522','z','zt','zhutian','','乡','1001314','',14),(3523,'新埤',501,'501,3523','x','xp','xinpi','','乡','1001315','',15),(3524,'枋寮',501,'501,3524','f','fl','fangliao','','乡','1001316','',16),(3525,'新园',501,'501,3525','x','xy','xinyuan','','乡','1001317','',17),(3526,'崁顶',501,'501,3526','k','kd','kanding','','乡','1001318','',18),(3527,'林边',501,'501,3527','l','lb','linbian','','乡','1001319','',19),(3528,'南州',501,'501,3528','n','nz','nanzhou','','乡','1001320','',20),(3529,'佳冬',501,'501,3529','j','jd','jiadong','','乡','1001321','',21),(3530,'琉球',501,'501,3530','l','lq','liuqiu','','乡','1001322','',22),(3531,'车城',501,'501,3531','c','cc','checheng','','乡','1001323','',23),(3532,'满州',501,'501,3532','m','mz','manzhou','','乡','1001324','',24),(3533,'枋山',501,'501,3533','f','fs','fangshan','','乡','1001325','',25),(3534,'三地门',501,'501,3534','s','sdm','sandimen','','乡','1001326','',26),(3535,'雾台',501,'501,3535','w','wt','wutai','','乡','1001327','',27),(3536,'玛家',501,'501,3536','m','mj','majia','','乡','1001328','',28),(3537,'泰武',501,'501,3537','t','tw','taiwu','','乡','1001329','',29),(3538,'来义',501,'501,3538','l','ly','laiyi','','乡','1001330','',30),(3539,'春日',501,'501,3539','c','cr','chunri','','乡','1001331','',31),(3540,'狮子',501,'501,3540','s','sz','shizi','','乡','1001332','',32),(3541,'牡丹',501,'501,3541','m','md','mudan','','乡','1001333','',33),(3542,'卑南',502,'502,3542','b','bn','beinan','','乡','','',1),(3543,'台东',502,'502,3543','t','td','taidong','','市','1001401','',2),(3544,'成功',502,'502,3544','c','cg','chenggong','','镇','1001402','',3),(3545,'关山',502,'502,3545','g','gs','guanshan','','镇','1001403','',4),(3546,'鹿野',502,'502,3546','l','ly','luye','','乡','1001405','',5),(3547,'池上',502,'502,3547','c','cs','chishang','','乡','1001406','',6),(3548,'东河',502,'502,3548','d','dh','donghe','','乡','1001407','',7),(3549,'长滨',502,'502,3549','z','zb','zhangbin','','乡','1001408','',8),(3550,'太麻里',502,'502,3550','t','tml','taimali','','乡','1001409','',9),(3551,'大武',502,'502,3551','d','dw','dawu','','乡','1001410','',10),(3552,'绿岛',502,'502,3552','l','ld','lu:dao','','乡','1001411','',11),(3553,'海端',502,'502,3553','h','hd','haiduan','','乡','1001412','',12),(3554,'延平',502,'502,3554','y','yp','yanping','','乡','1001413','',13),(3555,'金峰',502,'502,3555','j','jf','jinfeng','','乡','1001414','',14),(3556,'达仁',502,'502,3556','d','dr','daren','','乡','1001415','',15),(3557,'兰屿',502,'502,3557','l','ly','lanyu','','乡','1001416','',16),(3558,'花莲',503,'503,3558','h','hl','hualian','','市','1001501','',1),(3559,'凤林',503,'503,3559','f','fl','fenglin','','镇','1001502','',2),(3560,'玉里',503,'503,3560','y','yl','yuli','','镇','1001503','',3),(3561,'新城',503,'503,3561','x','xc','xincheng','','乡','1001504','',4),(3562,'吉安',503,'503,3562','j','ja','jian','','乡','1001505','',5),(3563,'寿丰',503,'503,3563','s','sf','shoufeng','','乡','1001506','',6),(3564,'光复',503,'503,3564','g','gf','guangfu','','乡','1001507','',7),(3565,'丰滨',503,'503,3565','f','fb','fengbin','','乡','1001508','',8),(3566,'瑞穗',503,'503,3566','r','rs','ruisui','','乡','1001509','',9),(3567,'富里',503,'503,3567','f','fl','fuli','','乡','1001510','',10),(3568,'秀林',503,'503,3568','x','xl','xiulin','','乡','1001511','',11),(3569,'万荣',503,'503,3569','w','wr','wanrong','','乡','1001512','',12),(3570,'卓溪',503,'503,3570','z','zx','zhuoxi','','乡','1001513','',13),(3571,'马公',504,'504,3571','m','mg','magong','','市','1001601','07',1),(3572,'湖西',504,'504,3572','h','hx','huxi','','乡','1001602','07',2),(3573,'白沙',504,'504,3573','b','bs','baisha','','乡','1001603','07',3),(3574,'西屿',504,'504,3574','x','xy','xiyu','','乡','1001604','07',4),(3575,'望安',504,'504,3575','w','wa','wangan','','乡','1001605','07',5),(3576,'七美',504,'504,3576','q','qm','qimei','','乡','1001606','07',6);

/*Table structure for table `dr_field` */

DROP TABLE IF EXISTS `dr_field`;

CREATE TABLE `dr_field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT '字段别名语言',
  `fieldname` varchar(50) NOT NULL COMMENT '字段名称',
  `fieldtype` varchar(50) NOT NULL COMMENT '字段类型',
  `relatedid` smallint(5) unsigned NOT NULL COMMENT '相关id',
  `relatedname` varchar(20) NOT NULL COMMENT '相关表',
  `isedit` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可修改',
  `ismain` tinyint(1) unsigned NOT NULL COMMENT '是否主表',
  `issystem` tinyint(1) unsigned NOT NULL COMMENT '是否系统表',
  `ismember` tinyint(1) unsigned NOT NULL COMMENT '是否会员可见',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可搜索',
  `disabled` tinyint(1) unsigned NOT NULL COMMENT '禁用？',
  `setting` text NOT NULL COMMENT '配置信息',
  `displayorder` tinyint(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `list` (`relatedid`,`disabled`,`issystem`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='字段表';

/*Data for the table `dr_field` */

insert  into `dr_field`(`id`,`name`,`fieldname`,`fieldtype`,`relatedid`,`relatedname`,`isedit`,`ismain`,`issystem`,`ismember`,`issearch`,`disabled`,`setting`,`displayorder`) values (1,'名称','title','Text',1,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:34:\\\"onblur=\\\"get_keywords(\\\'keywords\\\');\\\"\\\";}}',1),(2,'名称','title','Text',2,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',0),(3,'主题','title','Text',3,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:34:\\\"onblur=\\\"get_keywords(\\\'keywords\\\');\\\"\\\";}}',1),(4,'名称','title','Text',4,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:34:\\\"onblur=\\\"get_keywords(\\\'keywords\\\');\\\"\\\";}}',1),(6,'链接地址','link','Redirect',2,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:2:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',0),(8,'图片集','image','Files',4,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:6:{s:5:\\\"width\\\";s:3:\\\"80%\\\";s:4:\\\"size\\\";s:2:\\\"10\\\";s:5:\\\"count\\\";s:2:\\\"50\\\";s:3:\\\"ext\\\";s:11:\\\"gif,png,jpg\\\";s:10:\\\"uploadpath\\\";s:25:\\\"{siteid}/photo/{y}{m}{d}/\\\";s:3:\\\"pan\\\";s:1:\\\"0\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',3),(9,'简介','content','Ueditor',4,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:6:{s:5:\\\"width\\\";s:3:\\\"90%\\\";s:6:\\\"height\\\";s:3:\\\"100\\\";s:3:\\\"key\\\";s:0:\\\"\\\";s:4:\\\"mode\\\";s:1:\\\"2\\\";s:4:\\\"tool\\\";s:29:\\\"\\\'bold\\\', \\\'italic\\\', \\\'underline\\\'\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',5),(10,'主题','title','Text',5,'space',1,1,1,1,1,0,'a:2:{s:6:\\\"option\\\";a:3:{s:5:\\\"width\\\";i:400;s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:4:{s:3:\\\"xss\\\";i:1;s:8:\\\"required\\\";i:1;s:4:\\\"tips\\\";N;s:9:\\\"errortips\\\";N;}}',0),(11,'图片','image','File',5,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:4:\\\"size\\\";s:2:\\\"10\\\";s:3:\\\"ext\\\";s:11:\\\"gif,png,jpg\\\";s:10:\\\"uploadpath\\\";s:0:\\\"\\\";s:3:\\\"pan\\\";s:1:\\\"0\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',0),(12,'链接地址','link','Redirect',5,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:2:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',0),(14,'描述','description','Textarea',1,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:3:{s:5:\\\"width\\\";s:3:\\\"500\\\";s:6:\\\"height\\\";s:2:\\\"60\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:12:\\\"dr_clearhtml\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',3),(16,'描述','description','Textarea',3,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:3:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:6:\\\"height\\\";s:2:\\\"60\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:12:\\\"dr_clearhtml\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',3),(18,'描述','description','Textarea',4,'space',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:3:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:6:\\\"height\\\";s:2:\\\"60\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";N;s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:12:\\\"dr_clearhtml\\\";s:4:\\\"tips\\\";N;s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',4),(19,'标题','title','Text',1,'module',1,1,1,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"400\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"1\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:18:\\\"标题不能为空\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',1),(22,'描述','description','Textarea',1,'module',1,1,1,1,1,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";i:500;s:6:\\\"height\\\";i:60;s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:3:\\\"255\\\";}s:8:\\\"validate\\\";a:2:{s:3:\\\"xss\\\";i:1;s:6:\\\"filter\\\";s:12:\\\"dr_clearhtml\\\";}}',3),(50,'公司/个人','gongsigeren','Radio',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:7:\\\"options\\\";s:28:\\\"公司|公司\r\n个人|个人\\\";s:5:\\\"value\\\";s:6:\\\"公司\\\";s:9:\\\"fieldtype\\\";s:0:\\\"\\\";s:11:\\\"fieldlength\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:0:\\\"\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',7),(51,'必备条件','bibeitiaojian','Textarea',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:3:{s:5:\\\"width\\\";s:3:\\\"200\\\";s:6:\\\"height\\\";s:2:\\\"40\\\";s:5:\\\"value\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:0:\\\"\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',8),(46,'价格','jiage','Text',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:5:\\\"width\\\";s:3:\\\"100\\\";s:5:\\\"value\\\";s:0:\\\"\\\";s:9:\\\"fieldtype\\\";s:7:\\\"VARCHAR\\\";s:11:\\\"fieldlength\\\";s:2:\\\"20\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"1\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:18:\\\"价格不能为空\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:18:\\\"如：60元/小时\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',2),(47,'照片','zhaopian','Files',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:5:{s:5:\\\"width\\\";s:5:\\\"100px\\\";s:4:\\\"size\\\";s:1:\\\"2\\\";s:5:\\\"count\\\";s:1:\\\"8\\\";s:3:\\\"ext\\\";s:11:\\\"jpg,gif,png\\\";s:10:\\\"uploadpath\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:0:\\\"\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',4),(48,'性别','sex','Radio',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:7:\\\"options\\\";s:16:\\\"男|男\r\n女|女\\\";s:5:\\\"value\\\";s:3:\\\"男\\\";s:9:\\\"fieldtype\\\";s:0:\\\"\\\";s:11:\\\"fieldlength\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:0:\\\"\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',5),(49,'校园/社会','xiaoyuanshehui','Radio',1,'module',1,1,0,1,0,0,'a:2:{s:6:\\\"option\\\";a:4:{s:7:\\\"options\\\";s:28:\\\"校园|校园\r\n社会|社会\\\";s:5:\\\"value\\\";s:6:\\\"校园\\\";s:9:\\\"fieldtype\\\";s:0:\\\"\\\";s:11:\\\"fieldlength\\\";s:0:\\\"\\\";}s:8:\\\"validate\\\";a:9:{s:3:\\\"xss\\\";s:1:\\\"0\\\";s:8:\\\"required\\\";s:1:\\\"0\\\";s:7:\\\"pattern\\\";s:0:\\\"\\\";s:9:\\\"errortips\\\";s:0:\\\"\\\";s:6:\\\"isedit\\\";s:1:\\\"0\\\";s:5:\\\"check\\\";s:0:\\\"\\\";s:6:\\\"filter\\\";s:0:\\\"\\\";s:4:\\\"tips\\\";s:0:\\\"\\\";s:8:\\\"formattr\\\";s:0:\\\"\\\";}}',6);

/*Table structure for table `dr_linkage` */

DROP TABLE IF EXISTS `dr_linkage`;

CREATE TABLE `dr_linkage` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '菜单名称',
  `type` tinyint(1) unsigned NOT NULL,
  `code` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `module` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='联动菜单表';

/*Data for the table `dr_linkage` */

insert  into `dr_linkage`(`id`,`name`,`type`,`code`) values (1,'中国地区',0,'address');

/*Table structure for table `dr_linkage_data_1` */

DROP TABLE IF EXISTS `dr_linkage_data_1`;

CREATE TABLE `dr_linkage_data_1` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `site` mediumint(5) unsigned NOT NULL COMMENT '站点id',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) DEFAULT NULL COMMENT '所有上级id',
  `name` varchar(30) NOT NULL COMMENT '栏目名称',
  `child` tinyint(1) unsigned DEFAULT '0' COMMENT '是否有下级',
  `childids` text COMMENT '下级所有id',
  `displayorder` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `list` (`site`,`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=1149 DEFAULT CHARSET=utf8 COMMENT='联动菜单数据表';

/*Data for the table `dr_linkage_data_1` */

insert  into `dr_linkage_data_1`(`id`,`site`,`pid`,`pids`,`name`,`child`,`childids`,`displayorder`) values (1,1,0,'0','北京市',0,'1',1),(2,1,0,'0','天津市',0,'2',2),(3,1,0,'0','河北省',1,'3,65,66,67,68,69,70,71,72,73,74,75',3),(4,1,0,'0','山西省',1,'4,248,259,271,277,291,298,305,317,331,346,364',4),(5,1,0,'0','辽宁省',1,'5,491,505,516,524,532,539,546,554,561,569,577,582,590,598',5),(6,1,0,'0','吉林省',1,'6,605,616,626,633,638,646,653,659,665',6),(7,1,0,'0','黑龙江省',1,'7,674,693,710,720,729,738,748,766,777,782,793,800,811',7),(8,1,0,'0','江苏省',1,'8,833,834,835,836,837,838,839,840,841,842,843,844,845',9),(9,1,0,'0','浙江省',1,'9,846,847,848,849,850,851,852,853,854,855,856',10),(10,1,0,'0','安徽省',1,'10,857,858,859,860,861,862,863,864,865,866,867,868,869,870,871,872',11),(11,1,0,'0','福建省',1,'11,873,874,875,876,877,878,879,880,881',12),(12,1,0,'0','江西省',1,'12,882,883,884,885,886,887,888,889,890,891,892',13),(13,1,0,'0','山东省',1,'13,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909',14),(14,1,0,'0','河南省',1,'14,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926',15),(15,1,0,'0','湖北省',1,'15,927,928,929,930,931,932,933,934,935,936,937,938,939',16),(16,1,0,'0','湖南省',1,'16,940,941,942,943,944,945,946,947,948,949,950,951,952,953',17),(17,1,0,'0','广东省',1,'17,954,955,956,957,958,959,960,961,962,963,964,965,966,967,968,969,972,973,974',18),(18,1,0,'0','海南省',1,'18,990',19),(19,1,0,'0','重庆市',0,'19',20),(20,1,0,'0','四川省',1,'20,1046,1047,1048,1049,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1065,1066',21),(21,1,0,'0','贵州省',1,'21,1067,1068,1069,1070,1071,1072,1073,1074,1075',22),(22,1,0,'0','云南省',1,'22,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1087,1088,1089,1090,1091',23),(23,1,0,'0','陕西省',1,'23,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108',24),(24,1,0,'0','甘肃省',1,'24,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121',25),(25,1,0,'0','青海省',1,'25,1122,1123,1124,1125,1126,1127,1128,1129',26),(26,1,0,'0','宁夏',1,'26,1130,1131,1132,1133,1134',27),(27,1,0,'0','西藏',1,'27,1092,1093,1094,1095,1096,1097,1098',28),(28,1,0,'0','新疆',1,'28,1135,1136,1137,1138,1139,1140,1141,1142,1143,1144,1145,1146,1147,1148',29),(29,1,0,'0','内蒙古',1,'29,378,388,398,402,415,424,433,447,455,467,474,487',30),(30,1,0,'0','香港',0,'30',31),(31,1,0,'0','澳门',0,'31',32),(32,1,0,'0','台湾',0,'32',33),(65,1,3,'0,3','石家庄市',0,'65',0),(66,1,3,'0,3','唐山市',0,'66',0),(67,1,3,'0,3','秦皇岛市',0,'67',0),(68,1,3,'0,3','邯郸市',0,'68',0),(69,1,3,'0,3','邢台市',0,'69',0),(70,1,3,'0,3','保定市',0,'70',0),(71,1,3,'0,3','张家口市',0,'71',0),(72,1,3,'0,3','承德市',0,'72',0),(73,1,3,'0,3','沧州市',0,'73',0),(74,1,3,'0,3','廊坊市',0,'74',0),(75,1,3,'0,3','衡水市',0,'75',0),(248,1,4,'0,4','太原市',0,'248',0),(259,1,4,'0,4','大同市',0,'259',0),(271,1,4,'0,4','阳泉市',0,'271',0),(277,1,4,'0,4','长治市',0,'277',0),(291,1,4,'0,4','晋城市',0,'291',0),(298,1,4,'0,4','朔州市',0,'298',0),(305,1,4,'0,4','晋中市',0,'305',0),(317,1,4,'0,4','运城市',0,'317',0),(331,1,4,'0,4','忻州市',0,'331',0),(346,1,4,'0,4','临汾市',0,'346',0),(364,1,4,'0,4','吕梁市',0,'364',0),(378,1,29,'0,29','呼和浩特市',0,'378',0),(388,1,29,'0,29','包头市',0,'388',0),(398,1,29,'0,29','乌海市',0,'398',0),(402,1,29,'0,29','赤峰市',0,'402',0),(415,1,29,'0,29','通辽市',0,'415',0),(424,1,29,'0,29','鄂尔多斯市',0,'424',0),(433,1,29,'0,29','呼伦贝尔市',0,'433',0),(447,1,29,'0,29','巴彦淖尔市',0,'447',0),(455,1,29,'0,29','乌兰察布市',0,'455',0),(467,1,29,'0,29','兴安盟',0,'467',0),(474,1,29,'0,29','锡林郭勒盟',0,'474',0),(487,1,29,'0,29','阿拉善盟',0,'487',0),(491,1,5,'0,5','沈阳市',0,'491',0),(505,1,5,'0,5','大连市',0,'505',0),(516,1,5,'0,5','鞍山市',0,'516',0),(524,1,5,'0,5','抚顺市',0,'524',0),(532,1,5,'0,5','本溪市',0,'532',0),(539,1,5,'0,5','丹东市',0,'539',0),(546,1,5,'0,5','锦州市',0,'546',0),(554,1,5,'0,5','营口市',0,'554',0),(561,1,5,'0,5','阜新市',0,'561',0),(569,1,5,'0,5','辽阳市',0,'569',0),(577,1,5,'0,5','盘锦市',0,'577',0),(582,1,5,'0,5','铁岭市',0,'582',0),(590,1,5,'0,5','朝阳市',0,'590',0),(598,1,5,'0,5','葫芦岛市',0,'598',0),(605,1,6,'0,6','长春市',0,'605',0),(616,1,6,'0,6','吉林市',0,'616',0),(626,1,6,'0,6','四平市',0,'626',0),(633,1,6,'0,6','辽源市',0,'633',0),(638,1,6,'0,6','通化市',0,'638',0),(646,1,6,'0,6','白山市',0,'646',0),(653,1,6,'0,6','松原市',0,'653',0),(659,1,6,'0,6','白城市',0,'659',0),(665,1,6,'0,6','延边自治州',0,'665',0),(674,1,7,'0,7','哈尔滨市',0,'674',0),(693,1,7,'0,7','齐齐哈尔市',0,'693',0),(710,1,7,'0,7','鸡西市',0,'710',0),(720,1,7,'0,7','鹤岗市',0,'720',0),(729,1,7,'0,7','双鸭山市',0,'729',0),(738,1,7,'0,7','大庆市',0,'738',0),(748,1,7,'0,7','伊春市',0,'748',0),(766,1,7,'0,7','佳木斯市',0,'766',0),(777,1,7,'0,7','七台河市',0,'777',0),(782,1,7,'0,7','牡丹江市',0,'782',0),(793,1,7,'0,7','黑河市',0,'793',0),(800,1,7,'0,7','绥化市',0,'800',0),(811,1,7,'0,7','大兴安岭地区',0,'811',0),(815,1,0,'0','上海市',0,'815',8),(833,1,8,'0,8','南京市',0,'833',0),(834,1,8,'0,8','无锡市',0,'834',0),(835,1,8,'0,8','徐州市',0,'835',0),(836,1,8,'0,8','常州市',0,'836',0),(837,1,8,'0,8','苏州市',0,'837',0),(838,1,8,'0,8','南通市',0,'838',0),(839,1,8,'0,8','连云港市',0,'839',0),(840,1,8,'0,8','淮安市',0,'840',0),(841,1,8,'0,8','盐城市',0,'841',0),(842,1,8,'0,8','扬州市',0,'842',0),(843,1,8,'0,8','镇江市',0,'843',0),(844,1,8,'0,8','泰州市',0,'844',0),(845,1,8,'0,8','宿迁市',0,'845',0),(846,1,9,'0,9','杭州市',0,'846',0),(847,1,9,'0,9','宁波市',0,'847',0),(848,1,9,'0,9','温州市',0,'848',0),(849,1,9,'0,9','嘉兴市',0,'849',0),(850,1,9,'0,9','湖州市',0,'850',0),(851,1,9,'0,9','绍兴市',0,'851',0),(852,1,9,'0,9','金华市',0,'852',0),(853,1,9,'0,9','衢州市',0,'853',0),(854,1,9,'0,9','舟山市',0,'854',0),(855,1,9,'0,9','台州市',0,'855',0),(856,1,9,'0,9','丽水市',0,'856',0),(857,1,10,'0,10','合肥市',0,'857',0),(858,1,10,'0,10','芜湖市',0,'858',0),(859,1,10,'0,10','蚌埠市',0,'859',0),(860,1,10,'0,10','淮南市',0,'860',0),(861,1,10,'0,10','马鞍山市',0,'861',0),(862,1,10,'0,10','淮北市',0,'862',0),(863,1,10,'0,10','铜陵市',0,'863',0),(864,1,10,'0,10','安庆市',0,'864',0),(865,1,10,'0,10','黄山市',0,'865',0),(866,1,10,'0,10','滁州市',0,'866',0),(867,1,10,'0,10','阜阳市',0,'867',0),(868,1,10,'0,10','宿州市',0,'868',0),(869,1,10,'0,10','六安市',0,'869',0),(870,1,10,'0,10','亳州市',0,'870',0),(871,1,10,'0,10','池州市',0,'871',0),(872,1,10,'0,10','宣城市',0,'872',0),(873,1,11,'0,11','福州市',0,'873',0),(874,1,11,'0,11','厦门市',0,'874',0),(875,1,11,'0,11','莆田市',0,'875',0),(876,1,11,'0,11','三明市',0,'876',0),(877,1,11,'0,11','泉州市',0,'877',0),(878,1,11,'0,11','漳州市',0,'878',0),(879,1,11,'0,11','南平市',0,'879',0),(880,1,11,'0,11','龙岩市',0,'880',0),(881,1,11,'0,11','宁德市',0,'881',0),(882,1,12,'0,12','南昌市',0,'882',0),(883,1,12,'0,12','景德镇市',0,'883',0),(884,1,12,'0,12','萍乡市',0,'884',0),(885,1,12,'0,12','九江市',0,'885',0),(886,1,12,'0,12','新余市',0,'886',0),(887,1,12,'0,12','鹰潭市',0,'887',0),(888,1,12,'0,12','赣州市',0,'888',0),(889,1,12,'0,12','吉安市',0,'889',0),(890,1,12,'0,12','宜春市',0,'890',0),(891,1,12,'0,12','抚州市',0,'891',0),(892,1,12,'0,12','上饶市',0,'892',0),(893,1,13,'0,13','济南市',0,'893',0),(894,1,13,'0,13','青岛市',0,'894',0),(895,1,13,'0,13','淄博市',0,'895',0),(896,1,13,'0,13','枣庄市',0,'896',0),(897,1,13,'0,13','东营市',0,'897',0),(898,1,13,'0,13','烟台市',0,'898',0),(899,1,13,'0,13','潍坊市',0,'899',0),(900,1,13,'0,13','济宁市',0,'900',0),(901,1,13,'0,13','泰安市',0,'901',0),(902,1,13,'0,13','威海市',0,'902',0),(903,1,13,'0,13','日照市',0,'903',0),(904,1,13,'0,13','莱芜市',0,'904',0),(905,1,13,'0,13','临沂市',0,'905',0),(906,1,13,'0,13','德州市',0,'906',0),(907,1,13,'0,13','聊城市',0,'907',0),(908,1,13,'0,13','滨州市',0,'908',0),(909,1,13,'0,13','菏泽市',0,'909',0),(910,1,14,'0,14','郑州市',0,'910',0),(911,1,14,'0,14','开封市',0,'911',0),(912,1,14,'0,14','洛阳市',0,'912',0),(913,1,14,'0,14','平顶山市',0,'913',0),(914,1,14,'0,14','安阳市',0,'914',0),(915,1,14,'0,14','鹤壁市',0,'915',0),(916,1,14,'0,14','新乡市',0,'916',0),(917,1,14,'0,14','焦作市',0,'917',0),(918,1,14,'0,14','濮阳市',0,'918',0),(919,1,14,'0,14','许昌市',0,'919',0),(920,1,14,'0,14','漯河市',0,'920',0),(921,1,14,'0,14','三门峡市',0,'921',0),(922,1,14,'0,14','南阳市',0,'922',0),(923,1,14,'0,14','商丘市',0,'923',0),(924,1,14,'0,14','信阳市',0,'924',0),(925,1,14,'0,14','周口市',0,'925',0),(926,1,14,'0,14','驻马店市',0,'926',0),(927,1,15,'0,15','武汉市',0,'927',0),(928,1,15,'0,15','黄石市',0,'928',0),(929,1,15,'0,15','十堰市',0,'929',0),(930,1,15,'0,15','宜昌市',0,'930',0),(931,1,15,'0,15','襄阳市',0,'931',0),(932,1,15,'0,15','鄂州市',0,'932',0),(933,1,15,'0,15','荆门市',0,'933',0),(934,1,15,'0,15','孝感市',0,'934',0),(935,1,15,'0,15','荆州市',0,'935',0),(936,1,15,'0,15','黄冈市',0,'936',0),(937,1,15,'0,15','咸宁市',0,'937',0),(938,1,15,'0,15','随州市',0,'938',0),(939,1,15,'0,15','恩施自治州',0,'939',0),(940,1,16,'0,16','长沙市',0,'940',0),(941,1,16,'0,16','株洲市',0,'941',0),(942,1,16,'0,16','湘潭市',0,'942',0),(943,1,16,'0,16','衡阳市',0,'943',0),(944,1,16,'0,16','邵阳市',0,'944',0),(945,1,16,'0,16','岳阳市',0,'945',0),(946,1,16,'0,16','常德市',0,'946',0),(947,1,16,'0,16','张家界市',0,'947',0),(948,1,16,'0,16','益阳市',0,'948',0),(949,1,16,'0,16','郴州市',0,'949',0),(950,1,16,'0,16','永州市',0,'950',0),(951,1,16,'0,16','怀化市',0,'951',0),(952,1,16,'0,16','娄底市',0,'952',0),(953,1,16,'0,16','湘西自治州',0,'953',0),(954,1,17,'0,17','广州市',0,'954',0),(955,1,17,'0,17','韶关市',0,'955',0),(956,1,17,'0,17','深圳市',0,'956',0),(957,1,17,'0,17','珠海市',0,'957',0),(958,1,17,'0,17','汕头市',0,'958',0),(959,1,17,'0,17','佛山市',0,'959',0),(960,1,17,'0,17','江门市',0,'960',0),(961,1,17,'0,17','湛江市',0,'961',0),(962,1,17,'0,17','茂名市',0,'962',0),(963,1,17,'0,17','肇庆市',0,'963',0),(964,1,17,'0,17','惠州市',0,'964',0),(965,1,17,'0,17','梅州市',0,'965',0),(966,1,17,'0,17','汕尾市',0,'966',0),(967,1,17,'0,17','河源市',0,'967',0),(968,1,17,'0,17','阳江市',0,'968',0),(969,1,17,'0,17','清远市',0,'969',0),(972,1,17,'0,17','潮州市',0,'972',0),(973,1,17,'0,17','揭阳市',0,'973',0),(974,1,17,'0,17','云浮市',0,'974',0),(975,1,0,'0','广西',1,'975,976,977,978,979,980,981,982,983,984,985,986,987,988,989',18),(976,1,975,'0,975','南宁市',0,'976',0),(977,1,975,'0,975','柳州市',0,'977',0),(978,1,975,'0,975','桂林市',0,'978',0),(979,1,975,'0,975','梧州市',0,'979',0),(980,1,975,'0,975','北海市',0,'980',0),(981,1,975,'0,975','防城港市',0,'981',0),(982,1,975,'0,975','钦州市',0,'982',0),(983,1,975,'0,975','贵港市',0,'983',0),(984,1,975,'0,975','玉林市',0,'984',0),(985,1,975,'0,975','百色市',0,'985',0),(986,1,975,'0,975','贺州市',0,'986',0),(987,1,975,'0,975','河池市',0,'987',0),(988,1,975,'0,975','来宾市',0,'988',0),(989,1,975,'0,975','崇左市',0,'989',0),(990,1,18,'0,18','海口市',0,'990',0),(1046,1,20,'0,20','成都市',0,'1046',0),(1047,1,20,'0,20','自贡市',0,'1047',0),(1048,1,20,'0,20','攀枝花市',0,'1048',0),(1049,1,20,'0,20','泸州市',0,'1049',0),(1050,1,20,'0,20','德阳市',0,'1050',0),(1051,1,20,'0,20','绵阳市',0,'1051',0),(1052,1,20,'0,20','广元市',0,'1052',0),(1053,1,20,'0,20','遂宁市',0,'1053',0),(1054,1,20,'0,20','内江市',0,'1054',0),(1055,1,20,'0,20','乐山市',0,'1055',0),(1056,1,20,'0,20','南充市',0,'1056',0),(1057,1,20,'0,20','眉山市',0,'1057',0),(1058,1,20,'0,20','宜宾市',0,'1058',0),(1059,1,20,'0,20','广安市',0,'1059',0),(1060,1,20,'0,20','达州市',0,'1060',0),(1061,1,20,'0,20','雅安市',0,'1061',0),(1062,1,20,'0,20','巴中市',0,'1062',0),(1063,1,20,'0,20','资阳市',0,'1063',0),(1064,1,20,'0,20','阿坝自治州',0,'1064',0),(1065,1,20,'0,20','甘孜自治州',0,'1065',0),(1066,1,20,'0,20','凉山自治州',0,'1066',0),(1067,1,21,'0,21','贵阳市',0,'1067',0),(1068,1,21,'0,21','六盘水市',0,'1068',0),(1069,1,21,'0,21','遵义市',0,'1069',0),(1070,1,21,'0,21','安顺市',0,'1070',0),(1071,1,21,'0,21','毕节市',0,'1071',0),(1072,1,21,'0,21','铜仁市',0,'1072',0),(1073,1,21,'0,21','黔西南自治州',0,'1073',0),(1074,1,21,'0,21','黔东南自治州',0,'1074',0),(1075,1,21,'0,21','黔南自治州',0,'1075',0),(1076,1,22,'0,22','昆明市',0,'1076',0),(1077,1,22,'0,22','曲靖市',0,'1077',0),(1078,1,22,'0,22','玉溪市',0,'1078',0),(1079,1,22,'0,22','保山市',0,'1079',0),(1080,1,22,'0,22','昭通市',0,'1080',0),(1081,1,22,'0,22','丽江市',0,'1081',0),(1082,1,22,'0,22','普洱市',0,'1082',0),(1083,1,22,'0,22','临沧市',0,'1083',0),(1084,1,22,'0,22','楚雄自治州',0,'1084',0),(1085,1,22,'0,22','红河自治州',0,'1085',0),(1086,1,22,'0,22','文山自治州',0,'1086',0),(1087,1,22,'0,22','西双版纳自治州',0,'1087',0),(1088,1,22,'0,22','大理自治州',0,'1088',0),(1089,1,22,'0,22','德宏自治州',0,'1089',0),(1090,1,22,'0,22','怒江自治州',0,'1090',0),(1091,1,22,'0,22','迪庆自治州',0,'1091',0),(1092,1,27,'0,27','拉萨市',0,'1092',0),(1093,1,27,'0,27','昌都地区',0,'1093',0),(1094,1,27,'0,27','山南地区',0,'1094',0),(1095,1,27,'0,27','日喀则地区',0,'1095',0),(1096,1,27,'0,27','那曲地区',0,'1096',0),(1097,1,27,'0,27','阿里地区',0,'1097',0),(1098,1,27,'0,27','林芝地区',0,'1098',0),(1099,1,23,'0,23','西安市',0,'1099',0),(1100,1,23,'0,23','铜川市',0,'1100',0),(1101,1,23,'0,23','宝鸡市',0,'1101',0),(1102,1,23,'0,23','咸阳市',0,'1102',0),(1103,1,23,'0,23','渭南市',0,'1103',0),(1104,1,23,'0,23','延安市',0,'1104',0),(1105,1,23,'0,23','汉中市',0,'1105',0),(1106,1,23,'0,23','榆林市',0,'1106',0),(1107,1,23,'0,23','安康市',0,'1107',0),(1108,1,23,'0,23','商洛市',0,'1108',0),(1109,1,24,'0,24','兰州市',0,'1109',0),(1110,1,24,'0,24','嘉峪关市',0,'1110',0),(1111,1,24,'0,24','白银市',0,'1111',0),(1112,1,24,'0,24','天水市',0,'1112',0),(1113,1,24,'0,24','武威市',0,'1113',0),(1114,1,24,'0,24','张掖市',0,'1114',0),(1115,1,24,'0,24','平凉市',0,'1115',0),(1116,1,24,'0,24','酒泉市',0,'1116',0),(1117,1,24,'0,24','庆阳市',0,'1117',0),(1118,1,24,'0,24','定西市',0,'1118',0),(1119,1,24,'0,24','陇南市',0,'1119',0),(1120,1,24,'0,24','临夏自治州',0,'1120',0),(1121,1,24,'0,24','甘南自治州',0,'1121',0),(1122,1,25,'0,25','西宁市',0,'1122',0),(1123,1,25,'0,25','海东地区',0,'1123',0),(1124,1,25,'0,25','海北自治州',0,'1124',0),(1125,1,25,'0,25','黄南自治州',0,'1125',0),(1126,1,25,'0,25','海南自治州',0,'1126',0),(1127,1,25,'0,25','果洛自治州',0,'1127',0),(1128,1,25,'0,25','玉树自治州',0,'1128',0),(1129,1,25,'0,25','海西自治州',0,'1129',0),(1130,1,26,'0,26','银川市',0,'1130',0),(1131,1,26,'0,26','石嘴山市',0,'1131',0),(1132,1,26,'0,26','吴忠市',0,'1132',0),(1133,1,26,'0,26','固原市',0,'1133',0),(1134,1,26,'0,26','中卫市',0,'1134',0),(1135,1,28,'0,28','乌鲁木齐市',0,'1135',0),(1136,1,28,'0,28','克拉玛依市',0,'1136',0),(1137,1,28,'0,28','吐鲁番地区',0,'1137',0),(1138,1,28,'0,28','哈密地区',0,'1138',0),(1139,1,28,'0,28','昌吉自治州',0,'1139',0),(1140,1,28,'0,28','博尔塔拉自治州',0,'1140',0),(1141,1,28,'0,28','巴音郭楞自治州',0,'1141',0),(1142,1,28,'0,28','阿克苏地区',0,'1142',0),(1143,1,28,'0,28','克孜勒自治州',0,'1143',0),(1144,1,28,'0,28','喀什地区',0,'1144',0),(1145,1,28,'0,28','和田地区',0,'1145',0),(1146,1,28,'0,28','伊犁自治州',0,'1146',0),(1147,1,28,'0,28','塔城地区',0,'1147',0),(1148,1,28,'0,28','阿勒泰地区',0,'1148',0);

/*Table structure for table `dr_mail_queue` */

DROP TABLE IF EXISTS `dr_mail_queue`;

CREATE TABLE `dr_mail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL COMMENT '邮件地址',
  `subject` varchar(255) NOT NULL COMMENT '邮件标题',
  `message` text NOT NULL COMMENT '邮件内容',
  `status` tinyint(1) unsigned NOT NULL COMMENT '发送状态',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件队列表';

/*Data for the table `dr_mail_queue` */

/*Table structure for table `dr_mail_smtp` */

DROP TABLE IF EXISTS `dr_mail_smtp`;

CREATE TABLE `dr_mail_smtp` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `port` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='邮件账户表';

/*Data for the table `dr_mail_smtp` */

insert  into `dr_mail_smtp`(`id`,`host`,`user`,`pass`,`port`) values (1,'smtp.163.com','15879302304@163.com','baixiaoshi',25);

/*Table structure for table `dr_member` */

DROP TABLE IF EXISTS `dr_member`;

CREATE TABLE `dr_member` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` char(40) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '加密密码',
  `salt` char(10) NOT NULL COMMENT '随机加密码',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `phone` char(20) NOT NULL COMMENT '手机号码',
  `avatar` varchar(255) NOT NULL COMMENT '头像地址',
  `money` decimal(10,2) unsigned NOT NULL COMMENT 'RMB',
  `freeze` decimal(10,2) unsigned NOT NULL COMMENT '冻结RMB',
  `score` int(10) unsigned NOT NULL COMMENT '虚拟币',
  `experience` int(10) unsigned NOT NULL COMMENT '经验值',
  `adminid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '管理组id',
  `groupid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `levelid` smallint(5) unsigned NOT NULL COMMENT '会员级别',
  `overdue` int(10) unsigned NOT NULL COMMENT '到期时间',
  `regip` varchar(15) NOT NULL COMMENT '注册ip',
  `regtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `randcode` smallint(4) unsigned NOT NULL COMMENT '随机验证码',
  `ismobile` tinyint(1) unsigned DEFAULT NULL COMMENT '手机认证标识',
  `loginlog` text NOT NULL COMMENT '登录日志',
  PRIMARY KEY (`uid`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`),
  KEY `adminid` (`adminid`),
  KEY `username` (`username`),
  KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员表';

/*Data for the table `dr_member` */

insert  into `dr_member`(`uid`,`email`,`username`,`password`,`salt`,`name`,`phone`,`avatar`,`money`,`freeze`,`score`,`experience`,`adminid`,`groupid`,`levelid`,`overdue`,`regip`,`regtime`,`randcode`,`ismobile`,`loginlog`) values (1,'634842632@qq.com','admin','8901738ac8cfedbc3c4e8dc7f862da2e','f57a2f557b','','','','9999.00','0.00',10000,9805,1,3,4,0,'',0,0,0,''),(2,'63484263@qq.com','test','65ac5cf4300f2eae05b88d7815cb393a','03afdbd66e','','','','0.00','0.00',0,196,0,3,1,0,'127.0.0.1',1401503761,6738,0,'a:3:{i:0;a:3:{s:8:\\\"login_ip\\\";s:9:\\\"127.0.0.1\\\";s:10:\\\"login_time\\\";i:1401503843;s:10:\\\"login_type\\\";s:0:\\\"\\\";}i:-1;a:2:{s:10:\\\"login_time\\\";i:1401715108;s:10:\\\"login_type\\\";s:0:\\\"\\\";}i:2;a:3:{s:8:\\\"login_ip\\\";s:9:\\\"127.0.0.1\\\";s:10:\\\"login_time\\\";i:1401519407;s:10:\\\"login_type\\\";s:0:\\\"\\\";}}');

/*Table structure for table `dr_member_address` */

DROP TABLE IF EXISTS `dr_member_address`;

CREATE TABLE `dr_member_address` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '会员id',
  `city` mediumint(8) unsigned NOT NULL COMMENT '城市id',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `phone` varchar(20) NOT NULL COMMENT '电话',
  `zipcode` varchar(10) NOT NULL COMMENT '邮编',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `default` tinyint(1) unsigned NOT NULL COMMENT '是否默认',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`default`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员收货地址表';

/*Data for the table `dr_member_address` */

/*Table structure for table `dr_member_data` */

DROP TABLE IF EXISTS `dr_member_data`;

CREATE TABLE `dr_member_data` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `complete` tinyint(1) unsigned NOT NULL COMMENT '完善资料标识',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

/*Data for the table `dr_member_data` */

/*Table structure for table `dr_member_group` */

DROP TABLE IF EXISTS `dr_member_group`;

CREATE TABLE `dr_member_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT '会员组名称',
  `theme` varchar(20) NOT NULL COMMENT '风格目录',
  `template` varchar(20) NOT NULL COMMENT '模板目录',
  `price` decimal(10,2) NOT NULL COMMENT '售价',
  `unit` tinyint(1) unsigned NOT NULL COMMENT '价格单位:1虚拟卡，2金钱',
  `limit` tinyint(1) unsigned NOT NULL COMMENT '售价限制：1月，2半年，3年',
  `overdue` smallint(5) unsigned NOT NULL COMMENT '过期后变成的组',
  `allowapply` tinyint(1) unsigned NOT NULL COMMENT '是否允许会员申请',
  `allowspace` tinyint(1) unsigned NOT NULL COMMENT '是否允许会员空间',
  `allowfield` text NOT NULL COMMENT '可用字段，序列化数组格式',
  `displayorder` tinyint(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员组表';

/*Data for the table `dr_member_group` */

insert  into `dr_member_group`(`id`,`name`,`theme`,`template`,`price`,`unit`,`limit`,`overdue`,`allowapply`,`allowspace`,`allowfield`,`displayorder`) values (1,'待审核会员','default','default','0.00',1,1,0,0,0,'',0),(2,'OAuth会员','default','default','0.00',0,0,0,0,0,'',0),(3,'普通会员','default','default','0.00',1,1,3,0,1,'',0),(4,'商业会员','default','default','10.00',2,1,3,1,1,'',0);

/*Table structure for table `dr_member_level` */

DROP TABLE IF EXISTS `dr_member_level`;

CREATE TABLE `dr_member_level` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '会员级别名称',
  `stars` tinyint(2) NOT NULL COMMENT '星星数量',
  `experience` int(10) unsigned NOT NULL COMMENT '经验值要求',
  `allowupgrade` tinyint(1) NOT NULL COMMENT '允许自动升级',
  PRIMARY KEY (`id`),
  KEY `experience` (`experience`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='会员级别表';

/*Data for the table `dr_member_level` */

insert  into `dr_member_level`(`id`,`groupid`,`name`,`stars`,`experience`,`allowupgrade`) values (1,3,'初级',1,0,0),(2,3,'中级',5,200,0),(3,3,'高级',10,500,0),(4,3,'元老',15,1000,0),(5,4,'普通',16,0,0),(6,4,'银牌',23,500,0),(7,4,'金牌',35,1000,0),(8,4,'钻石',55,2000,0);

/*Table structure for table `dr_member_menu` */

DROP TABLE IF EXISTS `dr_member_menu`;

CREATE TABLE `dr_member_menu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL COMMENT '上级菜单id',
  `name` text NOT NULL COMMENT '菜单名称',
  `uri` varchar(255) DEFAULT NULL COMMENT 'uri字符串',
  `url` varchar(255) DEFAULT NULL COMMENT 'url',
  `mark` varchar(50) DEFAULT NULL COMMENT '菜单标识',
  `target` tinyint(3) unsigned DEFAULT NULL COMMENT '新窗口',
  `displayorder` tinyint(3) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `list` (`pid`),
  KEY `displayorder` (`displayorder`),
  KEY `mark` (`mark`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='会员菜单表';

/*Data for the table `dr_member_menu` */

insert  into `dr_member_menu`(`id`,`pid`,`name`,`uri`,`url`,`mark`,`target`,`displayorder`) values (1,0,'账号','','',NULL,0,0),(2,0,'财务','','',NULL,0,0),(3,0,'空间','','',NULL,0,0),(4,0,'应用','','',NULL,0,0),(5,1,'基本管理','','',NULL,0,0),(6,5,'基本资料','account/index','',NULL,0,0),(7,5,'收货地址','address/index','',NULL,0,0),(8,5,'上传头像','account/avatar','',NULL,0,0),(9,5,'附件管理','account/attachment','',NULL,0,0),(10,1,'会员相关','',NULL,NULL,0,0),(11,10,'修改密码','account/password','',NULL,0,0),(12,10,'快捷登录','account/oauth','',NULL,0,0),(13,10,'登录记录','account/login','',NULL,0,0),(14,10,'会员升级','account/upgrade','',NULL,0,0),(15,10,'会员权限','account/permission','',NULL,0,0),(16,2,'充值','',NULL,NULL,0,0),(17,2,'记录','',NULL,NULL,0,0),(18,16,'网银充值','pay/add','',NULL,0,0),(19,16,'卡密充值','pay/card','',NULL,0,0),(20,16,'兑换服务','pay/convert','',NULL,0,0),(21,17,'收入记录','pay/index','',NULL,0,0),(22,17,'消费记录','pay/spend','',NULL,0,0),(23,17,'经验值记录','pay/experience','',NULL,0,0),(24,17,'虚拟币记录','pay/score','',NULL,0,0),(25,3,'基本设置','',NULL,NULL,0,0),(26,3,'内容管理','',NULL,NULL,0,0),(27,25,'空间设置','space/index','',NULL,0,0),(28,25,'模板设置','space/template','',NULL,0,0),(29,26,'栏目分类','category/index','',NULL,0,0),(30,4,'应用管理','',NULL,NULL,0,99),(31,26,'文章管理','space1/index','','space-1',0,0),(32,26,'外链管理','space2/index','','space-2',0,0),(33,26,'日志管理','space3/index','','space-3',0,0),(34,26,'相册管理','space4/index','','space-4',0,0),(35,26,'幻灯管理','space5/index','','space-5',0,0),(36,4,'通知管理','',NULL,NULL,0,0),(37,36,'短消息','pm/index','','',0,0),(38,36,'系统提醒','notice/index','','',0,0),(39,36,'会员互动','notice/member','','',0,0),(40,36,'模块提醒','notice/module','','',0,0),(41,36,'应用提醒','notice/app','','',0,0),(42,0,'新闻','','','module-news',0,0),(43,42,'文档管理','','','module-news',0,0),(44,43,'已通过的文档','news/home/index','','module-news',0,0),(45,43,'待审核的文档','news/verify/index','','module-news',0,0),(46,43,'被退回的文档','news/back/index','','module-news',0,0),(47,43,'已推荐的文档','news/home/flag','','module-news',0,0),(48,43,'我收藏的文档','news/home/favorite','','module-news',0,0),(49,43,'我购买的文档','news/home/buy','','module-news',0,0);

/*Table structure for table `dr_member_new_notice` */

DROP TABLE IF EXISTS `dr_member_new_notice`;

CREATE TABLE `dr_member_new_notice` (
  `uid` smallint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新通知提醒表';

/*Data for the table `dr_member_new_notice` */

/*Table structure for table `dr_member_notice_0` */

DROP TABLE IF EXISTS `dr_member_notice_0`;

CREATE TABLE `dr_member_notice_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_0` */

/*Table structure for table `dr_member_notice_1` */

DROP TABLE IF EXISTS `dr_member_notice_1`;

CREATE TABLE `dr_member_notice_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_1` */

/*Table structure for table `dr_member_notice_2` */

DROP TABLE IF EXISTS `dr_member_notice_2`;

CREATE TABLE `dr_member_notice_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_2` */

insert  into `dr_member_notice_2`(`id`,`type`,`uid`,`isnew`,`content`,`inputtime`) values (1,1,2,0,'您的会员组由管理员admin改变成：普通会员',1401504071),(2,1,2,0,'恭喜亲，您的会员组等级升级成功',1401504086);

/*Table structure for table `dr_member_notice_3` */

DROP TABLE IF EXISTS `dr_member_notice_3`;

CREATE TABLE `dr_member_notice_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_3` */

/*Table structure for table `dr_member_notice_4` */

DROP TABLE IF EXISTS `dr_member_notice_4`;

CREATE TABLE `dr_member_notice_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_4` */

/*Table structure for table `dr_member_notice_5` */

DROP TABLE IF EXISTS `dr_member_notice_5`;

CREATE TABLE `dr_member_notice_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_5` */

/*Table structure for table `dr_member_notice_6` */

DROP TABLE IF EXISTS `dr_member_notice_6`;

CREATE TABLE `dr_member_notice_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_6` */

/*Table structure for table `dr_member_notice_7` */

DROP TABLE IF EXISTS `dr_member_notice_7`;

CREATE TABLE `dr_member_notice_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_7` */

/*Table structure for table `dr_member_notice_8` */

DROP TABLE IF EXISTS `dr_member_notice_8`;

CREATE TABLE `dr_member_notice_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_8` */

/*Table structure for table `dr_member_notice_9` */

DROP TABLE IF EXISTS `dr_member_notice_9`;

CREATE TABLE `dr_member_notice_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `isnew` (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

/*Data for the table `dr_member_notice_9` */

/*Table structure for table `dr_member_oauth` */

DROP TABLE IF EXISTS `dr_member_oauth`;

CREATE TABLE `dr_member_oauth` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '会员uid',
  `oid` varchar(255) NOT NULL COMMENT 'OAuth返回id',
  `oauth` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `expire_at` int(10) unsigned NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员OAuth2授权表';

/*Data for the table `dr_member_oauth` */

/*Table structure for table `dr_member_paycard` */

DROP TABLE IF EXISTS `dr_member_paycard`;

CREATE TABLE `dr_member_paycard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card` char(20) NOT NULL COMMENT '卡号',
  `password` mediumint(6) unsigned NOT NULL COMMENT '密码',
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `inputtime` int(10) unsigned NOT NULL COMMENT '生成时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `usetime` int(10) unsigned NOT NULL COMMENT '使用时间',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '使用人id',
  `username` varchar(50) NOT NULL COMMENT '使用人名称',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付卡表';

/*Data for the table `dr_member_paycard` */

/*Table structure for table `dr_member_paylog_0` */

DROP TABLE IF EXISTS `dr_member_paylog_0`;

CREATE TABLE `dr_member_paylog_0` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_0` */

/*Table structure for table `dr_member_paylog_1` */

DROP TABLE IF EXISTS `dr_member_paylog_1`;

CREATE TABLE `dr_member_paylog_1` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_1` */

/*Table structure for table `dr_member_paylog_2` */

DROP TABLE IF EXISTS `dr_member_paylog_2`;

CREATE TABLE `dr_member_paylog_2` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_2` */

/*Table structure for table `dr_member_paylog_3` */

DROP TABLE IF EXISTS `dr_member_paylog_3`;

CREATE TABLE `dr_member_paylog_3` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_3` */

/*Table structure for table `dr_member_paylog_4` */

DROP TABLE IF EXISTS `dr_member_paylog_4`;

CREATE TABLE `dr_member_paylog_4` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_4` */

/*Table structure for table `dr_member_paylog_5` */

DROP TABLE IF EXISTS `dr_member_paylog_5`;

CREATE TABLE `dr_member_paylog_5` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_5` */

/*Table structure for table `dr_member_paylog_6` */

DROP TABLE IF EXISTS `dr_member_paylog_6`;

CREATE TABLE `dr_member_paylog_6` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_6` */

/*Table structure for table `dr_member_paylog_7` */

DROP TABLE IF EXISTS `dr_member_paylog_7`;

CREATE TABLE `dr_member_paylog_7` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_7` */

/*Table structure for table `dr_member_paylog_8` */

DROP TABLE IF EXISTS `dr_member_paylog_8`;

CREATE TABLE `dr_member_paylog_8` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_8` */

/*Table structure for table `dr_member_paylog_9` */

DROP TABLE IF EXISTS `dr_member_paylog_9`;

CREATE TABLE `dr_member_paylog_9` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `value` decimal(10,2) NOT NULL COMMENT '价格',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `order` text NOT NULL COMMENT '订单号码组',
  `module` varchar(30) NOT NULL COMMENT '应用或模块目录',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录表';

/*Data for the table `dr_member_paylog_9` */

/*Table structure for table `dr_member_scorelog_0` */

DROP TABLE IF EXISTS `dr_member_scorelog_0`;

CREATE TABLE `dr_member_scorelog_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`),
  KEY `inputtime` (`inputtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_0` */

/*Table structure for table `dr_member_scorelog_1` */

DROP TABLE IF EXISTS `dr_member_scorelog_1`;

CREATE TABLE `dr_member_scorelog_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_1` */

insert  into `dr_member_scorelog_1`(`id`,`uid`,`type`,`value`,`mark`,`note`,`inputtime`) values (1,1,0,5,'login','lang,m-056',1401258503),(2,1,0,-5,'','lang,m-149,产品',1401279828),(3,1,0,-5,'','lang,m-149,产品',1401279828),(4,1,0,-5,'','lang,m-149,产品',1401279828),(5,1,0,-5,'','lang,m-149,产品',1401279828),(6,1,0,-5,'','lang,m-149,产品',1401279828),(7,1,0,-5,'','lang,m-149,产品',1401279828),(8,1,0,-5,'','lang,m-149,产品',1401279828),(9,1,0,-5,'','lang,m-149,产品',1401279828),(10,1,0,-5,'','lang,m-149,产品',1401279828),(11,1,0,-5,'','lang,m-149,产品',1401279828),(12,1,0,-5,'','lang,m-149,产品',1401279828),(13,1,0,-5,'','lang,m-149,产品',1401279828),(14,1,0,-5,'','lang,m-149,产品',1401279828),(15,1,0,-5,'','lang,m-149,产品',1401279828),(16,1,0,-5,'','lang,m-149,产品',1401279828),(17,1,0,-5,'','lang,m-149,产品',1401279828),(18,1,0,-5,'','lang,m-149,产品',1401279828),(19,1,0,-5,'','lang,m-149,交互',1401279828),(20,1,0,-5,'','lang,m-149,交互',1401279828),(21,1,0,-5,'','lang,m-149,交互',1401279828),(22,1,0,-5,'','lang,m-149,交互',1401279828),(23,1,0,-5,'','lang,m-149,交互',1401279828),(24,1,0,-5,'','lang,m-149,交互',1401279828),(25,1,0,-5,'','lang,m-149,交互',1401279828),(26,1,0,-5,'','lang,m-149,交互',1401279828),(27,1,0,-5,'','lang,m-149,交互',1401279828),(28,1,0,-5,'','lang,m-149,交互',1401279828),(29,1,0,-5,'','lang,m-149,交互',1401279828),(30,1,0,-5,'','lang,m-149,推广',1401279828),(31,1,0,-5,'','lang,m-149,推广',1401279828),(32,1,0,-5,'','lang,m-149,推广',1401279828),(33,1,0,-5,'','lang,m-149,推广',1401279828),(34,1,0,-5,'','lang,m-149,推广',1401279828),(35,1,0,-5,'','lang,m-149,推广',1401279828),(36,1,0,-5,'','lang,m-149,推广',1401279828),(37,1,0,-5,'','lang,m-149,推广',1401279828),(38,1,0,-5,'','lang,m-149,推广',1401279828),(39,1,0,-5,'','lang,m-149,推广',1401279828),(40,1,0,-5,'','lang,m-149,推广',1401279828),(41,1,0,-5,'','lang,m-149,推广',1401279828),(42,1,0,-5,'','lang,m-149,推广',1401279828),(43,1,0,-5,'','lang,m-149,推广',1401279828),(44,1,0,5,'login','lang,m-056',1401622943),(45,1,0,5,'login','lang,m-056',1401677815);

/*Table structure for table `dr_member_scorelog_2` */

DROP TABLE IF EXISTS `dr_member_scorelog_2`;

CREATE TABLE `dr_member_scorelog_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_2` */

insert  into `dr_member_scorelog_2`(`id`,`uid`,`type`,`value`,`mark`,`note`,`inputtime`) values (1,2,0,1,'login','lang,m-056',1401503847),(2,2,0,10,'dr_1_news-abc','lang,m-151,快递件',1401685952),(3,2,0,10,'dr_1_news-hello world','lang,m-151,快递件',1401686078),(4,2,0,10,'dr_1_news-hello','lang,m-151,快递件',1401686268),(5,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686394),(6,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686402),(7,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686414),(8,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686547),(9,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686554),(10,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686581),(11,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686586),(12,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686634),(13,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401686998),(14,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401687167),(15,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401687275),(16,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401698680),(17,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401698894),(18,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401699815),(19,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,汽车票',1401699970),(20,2,0,10,'dr_1_news-INSERT INTO `dr_1_news_index` (`uid`, `c','lang,m-151,快递件',1401700023),(21,2,0,5,'login','lang,m-056',1401704284);

/*Table structure for table `dr_member_scorelog_3` */

DROP TABLE IF EXISTS `dr_member_scorelog_3`;

CREATE TABLE `dr_member_scorelog_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_3` */

/*Table structure for table `dr_member_scorelog_4` */

DROP TABLE IF EXISTS `dr_member_scorelog_4`;

CREATE TABLE `dr_member_scorelog_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_4` */

/*Table structure for table `dr_member_scorelog_5` */

DROP TABLE IF EXISTS `dr_member_scorelog_5`;

CREATE TABLE `dr_member_scorelog_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_5` */

/*Table structure for table `dr_member_scorelog_6` */

DROP TABLE IF EXISTS `dr_member_scorelog_6`;

CREATE TABLE `dr_member_scorelog_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_6` */

/*Table structure for table `dr_member_scorelog_7` */

DROP TABLE IF EXISTS `dr_member_scorelog_7`;

CREATE TABLE `dr_member_scorelog_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_7` */

/*Table structure for table `dr_member_scorelog_8` */

DROP TABLE IF EXISTS `dr_member_scorelog_8`;

CREATE TABLE `dr_member_scorelog_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_8` */

/*Table structure for table `dr_member_scorelog_9` */

DROP TABLE IF EXISTS `dr_member_scorelog_9`;

CREATE TABLE `dr_member_scorelog_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL COMMENT '积分0,虚拟币1',
  `value` int(10) NOT NULL COMMENT '分数变化值',
  `mark` varchar(50) NOT NULL COMMENT '标记',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `inputtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `mark` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='得分日志';

/*Data for the table `dr_member_scorelog_9` */

/*Table structure for table `dr_member_session` */

DROP TABLE IF EXISTS `dr_member_session`;

CREATE TABLE `dr_member_session` (
  `uid` mediumint(8) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `session` varchar(50) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `time` (`time`),
  KEY `session` (`session`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员会话活动表';

/*Data for the table `dr_member_session` */

insert  into `dr_member_session`(`uid`,`time`,`session`) values (1,1401723087,'a0913475590f040951e7f02d6ae658de');

/*Table structure for table `dr_member_setting` */

DROP TABLE IF EXISTS `dr_member_setting`;

CREATE TABLE `dr_member_setting` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员属性参数表';

/*Data for the table `dr_member_setting` */

insert  into `dr_member_setting`(`name`,`value`) values ('ucentercfg',''),('domain-1',''),('pagesize','10'),('regnotallow','dayrui,finecms'),('ucenter','0'),('regnamerule','/^[0-9a-z]+$/i'),('register','1'),('regcode','0'),('regverify','1'),('regiptime','2'),('logincode','1'),('permission','a:10:{i:1;a:13:{s:16:\\\"login_experience\\\";s:1:\\\"1\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"1\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:10:\\\"attachsize\\\";s:1:\\\"0\\\";}i:2;a:14:{s:16:\\\"login_experience\\\";s:1:\\\"5\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"1\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:1:\\\"5\\\";}s:3:\\\"3_1\\\";a:15:{s:16:\\\"login_experience\\\";s:1:\\\"5\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"2\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"10\\\";}s:3:\\\"3_2\\\";a:15:{s:16:\\\"login_experience\\\";s:1:\\\"5\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"2\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"10\\\";}s:3:\\\"3_3\\\";a:15:{s:16:\\\"login_experience\\\";s:1:\\\"5\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:2:\\\"10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"2\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"20\\\";}s:3:\\\"3_4\\\";a:15:{s:16:\\\"login_experience\\\";s:1:\\\"5\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"3\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"30\\\";}s:3:\\\"4_5\\\";a:15:{s:16:\\\"login_experience\\\";s:2:\\\"10\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:2:\\\"10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"5\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"50\\\";}s:3:\\\"4_6\\\";a:15:{s:16:\\\"login_experience\\\";s:2:\\\"10\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"5\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:2:\\\"70\\\";}s:3:\\\"4_7\\\";a:15:{s:16:\\\"login_experience\\\";s:2:\\\"10\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"5\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:3:\\\"100\\\";}s:3:\\\"4_8\\\";a:15:{s:16:\\\"login_experience\\\";s:2:\\\"10\\\";s:11:\\\"login_score\\\";s:1:\\\"0\\\";s:17:\\\"avatar_experience\\\";s:2:\\\"10\\\";s:12:\\\"avatar_score\\\";s:1:\\\"0\\\";s:19:\\\"complete_experience\\\";s:2:\\\"10\\\";s:14:\\\"complete_score\\\";s:1:\\\"0\\\";s:15:\\\"bang_experience\\\";s:2:\\\"10\\\";s:10:\\\"bang_score\\\";s:1:\\\"0\\\";s:14:\\\"jie_experience\\\";s:3:\\\"-10\\\";s:9:\\\"jie_score\\\";s:1:\\\"0\\\";s:17:\\\"update_experience\\\";s:1:\\\"5\\\";s:12:\\\"update_score\\\";s:1:\\\"0\\\";s:9:\\\"is_upload\\\";s:1:\\\"1\\\";s:11:\\\"is_download\\\";s:1:\\\"1\\\";s:10:\\\"attachsize\\\";s:1:\\\"0\\\";}}'),('complete','0'),('avatar','0'),('pay','a:2:{s:6:\\\"tenpay\\\";a:3:{s:4:\\\"name\\\";s:9:\\\"财付通\\\";s:2:\\\"id\\\";s:0:\\\"\\\";s:3:\\\"key\\\";s:0:\\\"\\\";}s:6:\\\"alipay\\\";a:4:{s:4:\\\"name\\\";s:9:\\\"支付宝\\\";s:8:\\\"username\\\";s:0:\\\"\\\";s:2:\\\"id\\\";s:0:\\\"\\\";s:3:\\\"key\\\";s:0:\\\"\\\";}}'),('space','a:9:{s:6:\\\"domain\\\";s:0:\\\"\\\";s:4:\\\"edit\\\";s:1:\\\"1\\\";s:6:\\\"verify\\\";s:1:\\\"0\\\";s:7:\\\"rewrite\\\";s:1:\\\"0\\\";s:7:\\\"seojoin\\\";s:1:\\\"_\\\";s:5:\\\"title\\\";s:41:\\\"会员空间_FineCMS自助建站平台！\\\";s:8:\\\"keywords\\\";s:0:\\\"\\\";s:11:\\\"description\\\";s:0:\\\"\\\";s:4:\\\"flag\\\";a:9:{i:1;a:1:{s:4:\\\"name\\\";s:12:\\\"达人空间\\\";}i:2;a:1:{s:4:\\\"name\\\";s:12:\\\"推荐空间\\\";}i:3;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:4;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:5;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:6;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:7;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:8;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}i:9;a:1:{s:4:\\\"name\\\";s:0:\\\"\\\";}}}');

/*Table structure for table `dr_module` */

DROP TABLE IF EXISTS `dr_module`;

CREATE TABLE `dr_module` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` text COMMENT '站点划分',
  `dirname` varchar(50) NOT NULL COMMENT '目录名称',
  `extend` tinyint(1) unsigned DEFAULT NULL COMMENT '是否是扩展模块',
  `setting` text COMMENT '配置信息',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁用？',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dirname` (`dirname`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='模块表';

/*Data for the table `dr_module` */

insert  into `dr_module`(`id`,`site`,`dirname`,`extend`,`setting`,`disabled`) values (1,'a:1:{i:1;a:5:{s:3:\\\"use\\\";i:1;s:4:\\\"html\\\";i:0;s:5:\\\"theme\\\";s:7:\\\"default\\\";s:6:\\\"domain\\\";s:0:\\\"\\\";s:8:\\\"template\\\";s:7:\\\"default\\\";}}','news',0,'a:5:{s:4:\\\"flag\\\";a:9:{i:1;a:10:{s:4:\\\"name\\\";s:12:\\\"首页中间\\\";s:3:\\\"3_1\\\";s:2:\\\"10\\\";s:3:\\\"3_2\\\";s:2:\\\"10\\\";s:3:\\\"3_3\\\";s:2:\\\"10\\\";s:3:\\\"3_4\\\";s:2:\\\"10\\\";s:3:\\\"4_5\\\";s:2:\\\"10\\\";s:3:\\\"4_6\\\";s:2:\\\"10\\\";s:3:\\\"4_7\\\";s:2:\\\"10\\\";s:3:\\\"4_8\\\";s:2:\\\"10\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:2;a:10:{s:4:\\\"name\\\";s:12:\\\"幻灯图片\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:3;a:10:{s:4:\\\"name\\\";s:12:\\\"今日视点\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:4;a:10:{s:4:\\\"name\\\";s:12:\\\"推荐阅读\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:5;a:10:{s:4:\\\"name\\\";s:12:\\\"新闻焦点\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:6;a:10:{s:4:\\\"name\\\";s:12:\\\"栏目图片\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:7;a:10:{s:4:\\\"name\\\";s:0:\\\"\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:8;a:10:{s:4:\\\"name\\\";s:0:\\\"\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}i:9;a:10:{s:4:\\\"name\\\";s:0:\\\"\\\";s:3:\\\"3_1\\\";s:1:\\\"0\\\";s:3:\\\"3_2\\\";s:1:\\\"0\\\";s:3:\\\"3_3\\\";s:1:\\\"0\\\";s:3:\\\"3_4\\\";s:1:\\\"0\\\";s:3:\\\"4_5\\\";s:1:\\\"0\\\";s:3:\\\"4_6\\\";s:1:\\\"0\\\";s:3:\\\"4_7\\\";s:1:\\\"0\\\";s:3:\\\"4_8\\\";s:1:\\\"0\\\";s:4:\\\"5_10\\\";s:1:\\\"0\\\";}}s:3:\\\"seo\\\";a:3:{s:12:\\\"module_title\\\";s:0:\\\"\\\";s:15:\\\"module_keywords\\\";s:0:\\\"\\\";s:18:\\\"module_description\\\";s:0:\\\"\\\";}s:3:\\\"tag\\\";a:3:{s:8:\\\"pagesize\\\";s:0:\\\"\\\";s:3:\\\"url\\\";s:0:\\\"\\\";s:8:\\\"url_page\\\";s:0:\\\"\\\";}s:6:\\\"search\\\";a:3:{s:5:\\\"cache\\\";s:5:\\\"10000\\\";s:6:\\\"length\\\";s:1:\\\"4\\\";s:7:\\\"rewrite\\\";s:1:\\\"0\\\";}s:10:\\\"show_cache\\\";s:5:\\\"10000\\\";}',0);

/*Table structure for table `dr_newpm` */

DROP TABLE IF EXISTS `dr_newpm`;

CREATE TABLE `dr_newpm` (
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_newpm` */

/*Table structure for table `dr_pm_indexes` */

DROP TABLE IF EXISTS `dr_pm_indexes`;

CREATE TABLE `dr_pm_indexes` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_indexes` */

insert  into `dr_pm_indexes`(`pmid`,`plid`) values (1,1),(2,1);

/*Table structure for table `dr_pm_lists` */

DROP TABLE IF EXISTS `dr_pm_lists`;

CREATE TABLE `dr_pm_lists` (
  `plid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author` varchar(50) NOT NULL,
  `pmtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(80) NOT NULL,
  `members` smallint(5) unsigned NOT NULL DEFAULT '0',
  `min_max` varchar(17) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmessage` text NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `pmtype` (`pmtype`),
  KEY `min_max` (`min_max`),
  KEY `authorid` (`authorid`,`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_lists` */

insert  into `dr_pm_lists`(`plid`,`authorid`,`author`,`pmtype`,`subject`,`members`,`min_max`,`dateline`,`lastmessage`) values (1,2,'test',1,'你是不是amdin用户？',2,'1_2',1401507526,'a:3:{s:12:\\\"lastauthorid\\\";i:1;s:10:\\\"lastauthor\\\";s:5:\\\"admin\\\";s:11:\\\"lastsummary\\\";s:15:\\\"我当然是啦\\\";}');

/*Table structure for table `dr_pm_members` */

DROP TABLE IF EXISTS `dr_pm_members`;

CREATE TABLE `dr_pm_members` (
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `isnew` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pmnum` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`plid`,`uid`),
  KEY `isnew` (`isnew`),
  KEY `lastdateline` (`uid`,`lastdateline`),
  KEY `lastupdate` (`uid`,`lastupdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_members` */

insert  into `dr_pm_members`(`plid`,`uid`,`isnew`,`pmnum`,`lastupdate`,`lastdateline`) values (1,1,0,2,0,1401507593),(1,2,0,2,1401507526,1401507593);

/*Table structure for table `dr_pm_messages_0` */

DROP TABLE IF EXISTS `dr_pm_messages_0`;

CREATE TABLE `dr_pm_messages_0` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_0` */

/*Table structure for table `dr_pm_messages_1` */

DROP TABLE IF EXISTS `dr_pm_messages_1`;

CREATE TABLE `dr_pm_messages_1` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_1` */

insert  into `dr_pm_messages_1`(`pmid`,`plid`,`authorid`,`message`,`delstatus`,`dateline`) values (1,1,2,'&lt;p&gt;你是不是amdin用户？&lt;/p&gt;',0,1401507526),(2,1,1,'&lt;p&gt;我当然是啦&lt;br /&gt;&lt;/p&gt;',0,1401507593);

/*Table structure for table `dr_pm_messages_2` */

DROP TABLE IF EXISTS `dr_pm_messages_2`;

CREATE TABLE `dr_pm_messages_2` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_2` */

/*Table structure for table `dr_pm_messages_3` */

DROP TABLE IF EXISTS `dr_pm_messages_3`;

CREATE TABLE `dr_pm_messages_3` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_3` */

/*Table structure for table `dr_pm_messages_4` */

DROP TABLE IF EXISTS `dr_pm_messages_4`;

CREATE TABLE `dr_pm_messages_4` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_4` */

/*Table structure for table `dr_pm_messages_5` */

DROP TABLE IF EXISTS `dr_pm_messages_5`;

CREATE TABLE `dr_pm_messages_5` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_5` */

/*Table structure for table `dr_pm_messages_6` */

DROP TABLE IF EXISTS `dr_pm_messages_6`;

CREATE TABLE `dr_pm_messages_6` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_6` */

/*Table structure for table `dr_pm_messages_7` */

DROP TABLE IF EXISTS `dr_pm_messages_7`;

CREATE TABLE `dr_pm_messages_7` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_7` */

/*Table structure for table `dr_pm_messages_8` */

DROP TABLE IF EXISTS `dr_pm_messages_8`;

CREATE TABLE `dr_pm_messages_8` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_8` */

/*Table structure for table `dr_pm_messages_9` */

DROP TABLE IF EXISTS `dr_pm_messages_9`;

CREATE TABLE `dr_pm_messages_9` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dr_pm_messages_9` */

/*Table structure for table `dr_site` */

DROP TABLE IF EXISTS `dr_site`;

CREATE TABLE `dr_site` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '站点名称',
  `domain` varchar(50) NOT NULL COMMENT '站点域名',
  `setting` text NOT NULL COMMENT '站点配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='站点表';

/*Data for the table `dr_site` */

insert  into `dr_site`(`id`,`name`,`domain`,`setting`) values (1,'百姓网','www.baixing.cc','a:37:{s:9:\\\"SITE_NAME\\\";s:9:\\\"百姓网\\\";s:16:\\\"SITE_TIME_FORMAT\\\";s:11:\\\"Y-m-d H:i:s\\\";s:13:\\\"SITE_LANGUAGE\\\";s:5:\\\"zh-cn\\\";s:10:\\\"SITE_THEME\\\";s:7:\\\"default\\\";s:13:\\\"SITE_TEMPLATE\\\";s:7:\\\"default\\\";s:11:\\\"SITE_MOBILE\\\";s:1:\\\"0\\\";s:13:\\\"SITE_TIMEZONE\\\";s:1:\\\"8\\\";s:12:\\\"SITE_SEOJOIN\\\";s:1:\\\"_\\\";s:10:\\\"SITE_TITLE\\\";s:54:\\\"百姓网，一个发布信息的需求信息的网站\\\";s:13:\\\"SITE_KEYWORDS\\\";s:9:\\\"关键字\\\";s:16:\\\"SITE_DESCRIPTION\\\";s:6:\\\"描述\\\";s:18:\\\"SITE_ATTACH_REMOTE\\\";s:1:\\\"0\\\";s:16:\\\"SITE_ATTACH_HOST\\\";s:14:\\\"www.baixing.cc\\\";s:16:\\\"SITE_ATTACH_PORT\\\";s:2:\\\"21\\\";s:20:\\\"SITE_ATTACH_USERNAME\\\";s:0:\\\"\\\";s:20:\\\"SITE_ATTACH_PASSWORD\\\";s:6:\\\"dayrui\\\";s:16:\\\"SITE_ATTACH_PATH\\\";s:8:\\\"/wwwroot\\\";s:16:\\\"SITE_ATTACH_PASV\\\";s:1:\\\"0\\\";s:16:\\\"SITE_ATTACH_MODE\\\";s:4:\\\"auto\\\";s:16:\\\"SITE_ATTACH_EXTS\\\";s:11:\\\"jpg,gif,png\\\";s:15:\\\"SITE_ATTACH_URL\\\";s:14:\\\"www.baixing.cc\\\";s:16:\\\"SITE_IMAGE_RATIO\\\";s:1:\\\"1\\\";s:20:\\\"SITE_IMAGE_WATERMARK\\\";s:1:\\\"1\\\";s:17:\\\"SITE_IMAGE_REMOTE\\\";s:1:\\\"1\\\";s:19:\\\"SITE_IMAGE_VRTALIGN\\\";s:6:\\\"middle\\\";s:19:\\\"SITE_IMAGE_HORALIGN\\\";s:6:\\\"center\\\";s:20:\\\"SITE_IMAGE_VRTOFFSET\\\";s:3:\\\"-15\\\";s:20:\\\"SITE_IMAGE_HOROFFSET\\\";s:2:\\\"-5\\\";s:15:\\\"SITE_IMAGE_TYPE\\\";s:1:\\\"0\\\";s:18:\\\"SITE_IMAGE_OVERLAY\\\";s:11:\\\"default.png\\\";s:18:\\\"SITE_IMAGE_OPACITY\\\";s:2:\\\"80\\\";s:15:\\\"SITE_IMAGE_FONT\\\";s:11:\\\"default.ttf\\\";s:16:\\\"SITE_IMAGE_COLOR\\\";s:7:\\\"#9f6a19\\\";s:15:\\\"SITE_IMAGE_SIZE\\\";s:2:\\\"20\\\";s:15:\\\"SITE_IMAGE_TEXT\\\";s:7:\\\"FineCMS\\\";s:11:\\\"SITE_DOMAIN\\\";s:14:\\\"www.baixing.cc\\\";s:14:\\\"SITE_NAVIGATOR\\\";s:78:\\\"主导航,顶部导航,底部导航,友情链接,首页幻灯,合作伙伴,,,,\\\";}');

/*Table structure for table `dr_space` */

DROP TABLE IF EXISTS `dr_space`;

CREATE TABLE `dr_space` (
  `uid` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '空间名称',
  `logo` varchar(255) NOT NULL COMMENT '空间logo',
  `style` varchar(30) NOT NULL COMMENT '空间风格',
  `title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `description` text COMMENT 'SEO描述',
  `introduction` text COMMENT 'SEO描述',
  `code` text NOT NULL COMMENT '第三方代码',
  `footer` text NOT NULL COMMENT '底部信息',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `regtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `displayorder` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `hits` (`hits`),
  KEY `status` (`status`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间表';

/*Data for the table `dr_space` */

insert  into `dr_space`(`uid`,`name`,`logo`,`style`,`title`,`keywords`,`description`,`introduction`,`code`,`footer`,`hits`,`status`,`regtime`,`displayorder`) values (1,'FineCMS设计室','http://www.dayrui.com/member/statics/default/images/logo_new.png','default','FineCMS设计室-专业技术团队','FineCMS,网站建设,内容管理系统','FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案','<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。</p>','<script type=\"text/javascript\">var cnzz_protocol = ((\"https:\" == document.location.protocol) ? \" https://\" : \" http://\");document.write(unescape(\"%3Cdiv id=\'cnzz_stat_icon_5629330\'%3E%3C/div%3E%3Cscript src=\'\" + cnzz_protocol + \"s9.cnzz.com/stat.php%3Fid%3D5629330%26show%3Dpic2\' type=\'text/javascript\'%3E%3C/script%3E\"));</script>','<p>扣扣咨询：135977378 电子邮箱：finecms@qq.com</p>某某某公司版权所有，ICP备案0000001<p><br /></p>',888888,1,1377949585,0);

/*Table structure for table `dr_space_category` */

DROP TABLE IF EXISTS `dr_space_category`;

CREATE TABLE `dr_space_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '会员uid',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) DEFAULT NULL COMMENT '所有上级id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0外链，1模型，2单页',
  `name` varchar(30) NOT NULL COMMENT '栏目名称',
  `link` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `body` text COMMENT '单页内容',
  `showid` tinyint(1) unsigned NOT NULL COMMENT '0不显示,1顶部,2底部,3都显示',
  `modelid` smallint(5) unsigned NOT NULL COMMENT '模型id',
  `child` tinyint(1) unsigned DEFAULT '0' COMMENT '是否有下级',
  `childids` text COMMENT '下级所有id',
  `title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` text NOT NULL COMMENT '描述信息',
  `displayorder` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `showid` (`showid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='栏目表';

/*Data for the table `dr_space_category` */

insert  into `dr_space_category`(`id`,`uid`,`pid`,`pids`,`type`,`name`,`link`,`body`,`showid`,`modelid`,`child`,`childids`,`title`,`keywords`,`description`,`displayorder`) values (1,1,0,'0',2,'关于我们','','',3,0,1,'1,2,3','','','',0),(2,1,1,'0,1',2,'空间简介','','<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。<br /></p>',3,0,0,'2','','','',0),(3,1,1,'0,1',2,'联系我们','','<p><img src=\"http://api.map.baidu.com/staticimage?center=104.077889,30.551305&zoom=18&width=530&height=340&markers=104.076658,30.551693\" height=\"340\" width=\"530\" /></p><p>扣扣咨询：135977378<br />电子邮箱：finecms@qq.com</p>',3,0,0,'3','','','',0),(4,1,0,'0',1,'新闻资讯','','',3,1,0,'4','','','',0),(5,1,0,'0',1,'我的日志','','',3,3,0,'5','','','',0),(6,1,0,'0',1,'精彩图片','','',3,4,0,'6','','','',0),(7,1,0,'0',1,'首页幻灯','','',0,5,0,'7','','','',0),(8,1,0,'0',1,'友情链接','','',3,2,0,'8','','','',0),(9,1,0,'0',0,'技术支持','http://www.dayrui.com','',3,0,0,'9','','','0',0);

/*Table structure for table `dr_space_flag` */

DROP TABLE IF EXISTS `dr_space_flag`;

CREATE TABLE `dr_space_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '标记id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  KEY `flag` (`flag`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标记表';

/*Data for the table `dr_space_flag` */

insert  into `dr_space_flag`(`flag`,`uid`) values (1,1),(2,1);

/*Table structure for table `dr_space_link` */

DROP TABLE IF EXISTS `dr_space_link`;

CREATE TABLE `dr_space_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间外链模型表';

/*Data for the table `dr_space_link` */

/*Table structure for table `dr_space_log` */

DROP TABLE IF EXISTS `dr_space_log`;

CREATE TABLE `dr_space_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间日志模型表';

/*Data for the table `dr_space_log` */

/*Table structure for table `dr_space_model` */

DROP TABLE IF EXISTS `dr_space_model`;

CREATE TABLE `dr_space_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `table` varchar(50) NOT NULL COMMENT '表名',
  `setting` text NOT NULL COMMENT '配置信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table` (`table`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='会员模型表';

/*Data for the table `dr_space_model` */

insert  into `dr_space_model`(`id`,`name`,`table`,`setting`) values (1,'文章','news','a:8:{s:3:\\\"3_1\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_2\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"2\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_3\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"3\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_4\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"4\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_5\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_6\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"6\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_7\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"7\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_8\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}}'),(2,'外链','link','a:8:{s:3:\\\"3_1\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_2\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"2\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_3\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"3\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_4\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"4\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_5\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_6\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"6\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_7\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"7\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_8\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}}'),(3,'日志','log','a:8:{s:3:\\\"3_1\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_2\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"2\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_3\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"3\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_4\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"4\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_5\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"5\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_6\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"6\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_7\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"7\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_8\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"8\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}}'),(4,'相册','photo','a:8:{s:3:\\\"3_1\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_2\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"2\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_3\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"3\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_4\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"4\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_5\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"1\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_6\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"2\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_7\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"3\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_8\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:1:\\\"4\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}}'),(5,'幻灯','slides','a:8:{s:3:\\\"3_1\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_2\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_3\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"3_4\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_5\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_6\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_7\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}s:3:\\\"4_8\\\";a:3:{s:3:\\\"use\\\";s:1:\\\"1\\\";s:10:\\\"experience\\\";s:0:\\\"\\\";s:5:\\\"score\\\";s:0:\\\"\\\";}}');

/*Table structure for table `dr_space_news` */

DROP TABLE IF EXISTS `dr_space_news`;

CREATE TABLE `dr_space_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间单页模型表';

/*Data for the table `dr_space_news` */

/*Table structure for table `dr_space_photo` */

DROP TABLE IF EXISTS `dr_space_photo`;

CREATE TABLE `dr_space_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `image` text,
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间相册模型表';

/*Data for the table `dr_space_photo` */

/*Table structure for table `dr_space_slides` */

DROP TABLE IF EXISTS `dr_space_slides`;

CREATE TABLE `dr_space_slides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='会员空间幻灯模型表';

/*Data for the table `dr_space_slides` */

insert  into `dr_space_slides`(`id`,`catid`,`title`,`uid`,`author`,`hits`,`status`,`inputtime`,`updatetime`,`displayorder`,`image`,`link`) values (1,7,'应用开放平台',1,'admin',0,1,1377949237,1377949237,0,'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner1.jpg','http://store.dayrui.com'),(2,7,'群站多语言管理',1,'admin',0,1,1377949258,1377949258,0,'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner2.jpg','http://www.dayrui.com/cms/'),(3,7,'FineCMS 一套神奇的系统',1,'admin',0,1,1377949306,1377949306,0,'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner3.jpg','http://www.dayrui.com/');

/*Table structure for table `dr_urlrule` */

DROP TABLE IF EXISTS `dr_urlrule`;

CREATE TABLE `dr_urlrule` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '规则类型',
  `name` varchar(50) NOT NULL COMMENT '规则名称',
  `value` text NOT NULL COMMENT '相信规则',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='URL规则表';

/*Data for the table `dr_urlrule` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
