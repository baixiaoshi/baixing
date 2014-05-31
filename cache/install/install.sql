DROP TABLE IF EXISTS `{dbprefix}urlrule`;
CREATE TABLE IF NOT EXISTS `{dbprefix}urlrule` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '规则类型',
  `name` varchar(50) NOT NULL COMMENT '规则名称',
  `value` text NOT NULL COMMENT '相信规则',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='URL规则表' ;

DROP TABLE IF EXISTS `{dbprefix}newpm`;
CREATE TABLE IF NOT EXISTS `{dbprefix}newpm` (
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbprefix}pm_indexes`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_indexes` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbprefix}pm_lists`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_lists` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbprefix}pm_members`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_members` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_0`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_0` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_1` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_2`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_2` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_3`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_3` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_4`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_4` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_5`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_5` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_6`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_6` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_7`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_7` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_8`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_8` (
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

DROP TABLE IF EXISTS `{dbprefix}pm_messages_9`;
CREATE TABLE IF NOT EXISTS `{dbprefix}pm_messages_9` (
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

DROP TABLE IF EXISTS `{dbprefix}member_notice_0`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_2`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_3`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_4`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_5`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_6`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_7`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_8`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_notice_9`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
  `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
  `content` text NOT NULL COMMENT '通知内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY (`isnew`),
  KEY `type` (`type`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}member_new_notice`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_new_notice` (
  `uid` smallint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新通知提醒表';

DROP TABLE IF EXISTS `{dbprefix}cron_queue`;
CREATE TABLE IF NOT EXISTS `{dbprefix}cron_queue` (
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

DROP TABLE IF EXISTS `{dbprefix}space_flag`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_flag` (
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '标记id',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  KEY `flag` (`flag`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标记表';

DROP TABLE IF EXISTS `{dbprefix}application`;
CREATE TABLE IF NOT EXISTS `{dbprefix}application` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` text COMMENT '模块划分',
  `dirname` varchar(50) NOT NULL COMMENT '目录名称',
  `setting` text COMMENT '配置信息',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dirname` (`dirname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';

DROP TABLE IF EXISTS `{dbprefix}space_category`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL COMMENT '会员uid',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) DEFAULT NULL COMMENT '所有上级id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0外链，1模型，2单页',
  `name` varchar(30) NOT NULL COMMENT '栏目名称',
  `link` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `body` text DEFAULT NULL COMMENT '单页内容',
  `showid` tinyint(1) unsigned NOT NULL COMMENT '0不显示,1顶部,2底部,3都显示',
  `modelid` smallint(5) unsigned NOT NULL COMMENT '模型id',
  `child` tinyint(1) unsigned DEFAULT NULL DEFAULT '0' COMMENT '是否有下级',
  `childids` text DEFAULT NULL COMMENT '下级所有id',
  `title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` text NOT NULL COMMENT '描述信息',
  `displayorder` tinyint(3) DEFAULT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `showid` (`showid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

DROP TABLE IF EXISTS `{dbprefix}space_model`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `table` varchar(50) NOT NULL COMMENT '表名',
  `setting` text NOT NULL COMMENT '配置信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table` (`table`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员模型表';

DROP TABLE IF EXISTS `{dbprefix}member_menu`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_menu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员菜单表';

DROP TABLE IF EXISTS `{dbprefix}space`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space` (
  `uid` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '空间名称',
  `logo` varchar(255) NOT NULL COMMENT '空间logo',
  `style` varchar(30) NOT NULL COMMENT '空间风格',
  `title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `description` text DEFAULT NULL COMMENT 'SEO描述',
  `introduction` text DEFAULT NULL COMMENT 'SEO描述',
  `code` text NOT NULL COMMENT '第三方代码',
  `footer` text NOT NULL COMMENT '底部信息',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `regtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `displayorder` tinyint(3) DEFAULT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `hits` (`hits`),
  KEY `status` (`status`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间表';

DROP TABLE IF EXISTS `{dbprefix}admin`;
CREATE TABLE IF NOT EXISTS `{dbprefix}admin` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `realname` varchar(50) DEFAULT NULL COMMENT '管理员姓名',
  `usermenu` text COMMENT '自定义面板菜单，序列化数组格式',
  `lastloginip` varchar(15) DEFAULT NULL COMMENT '上次登录IP',
  `lastlogintime` int(10) unsigned DEFAULT NULL COMMENT '上次登录时间戳',
  `loginip` varchar(15) DEFAULT NULL COMMENT '本次登录IP',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '本次登录时间戳',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表';

REPLACE INTO `{dbprefix}admin` VALUES(1, '{username}', '网站创始人', NULL, NULL, '', 0);

DROP TABLE IF EXISTS `{dbprefix}mail_smtp`;
CREATE TABLE IF NOT EXISTS `{dbprefix}mail_smtp` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `port` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邮件账户表';

DROP TABLE IF EXISTS `{dbprefix}mail_queue`;
CREATE TABLE IF NOT EXISTS `{dbprefix}mail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL COMMENT '邮件地址',
  `subject` varchar(255) NOT NULL COMMENT '邮件标题',
  `message` text NOT NULL COMMENT '邮件内容',
  `status` tinyint(1) unsigned NOT NULL COMMENT '发送状态',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邮件队列表';

DROP TABLE IF EXISTS `{dbprefix}admin_menu`;
CREATE TABLE IF NOT EXISTS `{dbprefix}admin_menu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

DROP TABLE IF EXISTS `{dbprefix}admin_role`;
CREATE TABLE IF NOT EXISTS `{dbprefix}admin_role` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `site` varchar(255) NOT NULL COMMENT '允许管理的站点，序列化数组格式',
  `name` text NOT NULL COMMENT '角色组语言名称',
  `system` text NOT NULL COMMENT '系统权限',
  `module` text NOT NULL COMMENT '模块权限',
  `application` text NOT NULL COMMENT '应用权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台角色权限表';

REPLACE INTO `{dbprefix}admin_role` VALUES(1, '', '超级管理员', '', '', '');
REPLACE INTO `{dbprefix}admin_role` VALUES(2, '', '网站编辑员', '', '', '');

DROP TABLE IF EXISTS `{dbprefix}admin_verify`;
CREATE TABLE IF NOT EXISTS `{dbprefix}admin_verify` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL COMMENT '名称',
  `verify` text NOT NULL COMMENT '审核部署',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='审核管理表';

REPLACE INTO `{dbprefix}admin_verify` VALUES(1, '审核一次', 'a:1:{i:1;a:2:{i:0;s:1:\\"2\\";i:1;s:1:\\"3\\";}}');

DROP TABLE IF EXISTS `{dbprefix}attachment`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='附件表';

DROP TABLE IF EXISTS `{dbprefix}attachment_0`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_0` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_1` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_2`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_2` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_3`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_3` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_4`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_4` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_5`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_5` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_6`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_6` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_7`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_7` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_8`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_8` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_9`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_9` (
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

DROP TABLE IF EXISTS `{dbprefix}attachment_unused`;
CREATE TABLE IF NOT EXISTS `{dbprefix}attachment_unused` (
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

DROP TABLE IF EXISTS `{dbprefix}field`;
CREATE TABLE IF NOT EXISTS `{dbprefix}field` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='字段表';

DROP TABLE IF EXISTS `{dbprefix}linkage`;
CREATE TABLE IF NOT EXISTS `{dbprefix}linkage` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '菜单名称',
  `type` tinyint(1) unsigned NOT NULL,
  `code` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `module` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动菜单表';

DROP TABLE IF EXISTS `{dbprefix}linkage_data_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}linkage_data_1` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `site` mediumint(5) unsigned NOT NULL COMMENT '站点id',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `pids` varchar(255) DEFAULT NULL COMMENT '所有上级id',
  `name` varchar(30) NOT NULL COMMENT '栏目名称',
  `child` tinyint(1) unsigned DEFAULT NULL DEFAULT '0' COMMENT '是否有下级',
  `childids` text DEFAULT NULL COMMENT '下级所有id',
  `displayorder` tinyint(3) DEFAULT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `list` (`site`,`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动菜单数据表';

DROP TABLE IF EXISTS `{dbprefix}member`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员表';

DROP TABLE IF EXISTS `{dbprefix}member_address`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_address` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员收货地址表';

DROP TABLE IF EXISTS `{dbprefix}member_data`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_data` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `complete` tinyint(1) unsigned NOT NULL COMMENT '完善资料标识',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员表';

DROP TABLE IF EXISTS `{dbprefix}member_group`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_group` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员组表';

DROP TABLE IF EXISTS `{dbprefix}member_level`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_level` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '会员级别名称',
  `stars` tinyint(2) NOT NULL COMMENT '星星数量',
  `experience` int(10) unsigned NOT NULL COMMENT '经验值要求',
  `allowupgrade` tinyint(1) NOT NULL COMMENT '允许自动升级',
  PRIMARY KEY (`id`),
  KEY `experience` (`experience`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员级别表';

REPLACE INTO `{dbprefix}member_level` VALUES(1, 3, '初级', 1, 0, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(2, 3, '中级', 5, 200, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(3, 3, '高级', 10, 500, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(4, 3, '元老', 15, 1000, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(5, 4, '普通', 16, 0, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(6, 4, '银牌', 23, 500, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(7, 4, '金牌', 35, 1000, 0);
REPLACE INTO `{dbprefix}member_level` VALUES(8, 4, '钻石', 55, 2000, 0);

DROP TABLE IF EXISTS `{dbprefix}member_oauth`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_oauth` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员OAuth2授权表';

DROP TABLE IF EXISTS `{dbprefix}member_paycard`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paycard` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付卡表';

DROP TABLE IF EXISTS `{dbprefix}member_paylog_0`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_0` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_1` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付记录表';

DROP TABLE IF EXISTS `{dbprefix}member_paylog_2`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_2` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_3`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_3` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_4`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_4` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_5`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_5` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_6`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_6` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_7`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_7` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_8`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_8` (
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

DROP TABLE IF EXISTS `{dbprefix}member_paylog_9`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog_9` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_0`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_0` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_1`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_1` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='得分日志';

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_2`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_2` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_3`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_3` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_4`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_4` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_5`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_5` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_6`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_6` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_7`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_7` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_8`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_8` (
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

DROP TABLE IF EXISTS `{dbprefix}member_scorelog_9`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_scorelog_9` (
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

DROP TABLE IF EXISTS `{dbprefix}member_session`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_session` (
  `uid` mediumint(8) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `session` varchar(50) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `time` (`time`),
  KEY `session` (`session`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员会话活动表';

DROP TABLE IF EXISTS `{dbprefix}member_setting`;
CREATE TABLE IF NOT EXISTS `{dbprefix}member_setting` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员属性参数表';


DROP TABLE IF EXISTS `{dbprefix}module`;
CREATE TABLE IF NOT EXISTS `{dbprefix}module` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` text NULL COMMENT '站点划分',
  `dirname` varchar(50) NOT NULL COMMENT '目录名称',
  `extend` tinyint(1) unsigned DEFAULT NULL COMMENT '是否是扩展模块',
  `setting` text NULL COMMENT '配置信息',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁用？',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dirname` (`dirname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模块表';

DROP TABLE IF EXISTS `{dbprefix}site`;
CREATE TABLE IF NOT EXISTS `{dbprefix}site` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '站点名称',
  `domain` varchar(50) NOT NULL COMMENT '站点域名',
  `setting` text NOT NULL COMMENT '站点配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='站点表';

REPLACE INTO `{dbprefix}linkage` VALUES(1, '中国地区', 0, 'address');

REPLACE INTO `{dbprefix}member` VALUES(1, '{email}', '{username}', '{password}', '{salt}', '', '', '', 9999.00, 0.00, 10000, 10000, 1, 3, 4, 0, '', 0, 0, 0, '');

REPLACE INTO `{dbprefix}member_group` VALUES(1, '待审核会员', 'default', 'default', 0.00, 1, 1, 0, 0, 0, '', 0);
REPLACE INTO `{dbprefix}member_group` VALUES(2, 'OAuth会员', 'default', 'default', 0.00, 0, 0, 0, 0, 0, '', 0);
REPLACE INTO `{dbprefix}member_group` VALUES(3, '普通会员', 'default', 'default', 0.00, 1, 1, 3, 0, 1, '', 0);
REPLACE INTO `{dbprefix}member_group` VALUES(4, '商业会员', 'default', 'default', 10.00, 2, 1, 3, 1, 1, '', 0);

REPLACE INTO `{dbprefix}member_setting` VALUES('ucentercfg', '');
REPLACE INTO `{dbprefix}member_setting` VALUES('domain-1', '');
REPLACE INTO `{dbprefix}member_setting` VALUES('pagesize', '10');
REPLACE INTO `{dbprefix}member_setting` VALUES('regnotallow', 'dayrui,finecms');
REPLACE INTO `{dbprefix}member_setting` VALUES('ucenter', '0');
REPLACE INTO `{dbprefix}member_setting` VALUES('regnamerule', '/^[0-9a-z]+$/i');
REPLACE INTO `{dbprefix}member_setting` VALUES('register', '1');
REPLACE INTO `{dbprefix}member_setting` VALUES('regcode', '0');
REPLACE INTO `{dbprefix}member_setting` VALUES('regverify', '1');
REPLACE INTO `{dbprefix}member_setting` VALUES('regiptime', '2');
REPLACE INTO `{dbprefix}member_setting` VALUES('logincode', '1');
REPLACE INTO `{dbprefix}member_setting` VALUES('permission', 'a:10:{i:1;a:13:{s:16:\\"login_experience\\";s:1:\\"1\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"1\\";s:12:\\"update_score\\";s:1:\\"0\\";s:10:\\"attachsize\\";s:1:\\"0\\";}i:2;a:14:{s:16:\\"login_experience\\";s:1:\\"5\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"1\\";s:12:\\"update_score\\";s:1:\\"0\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:1:\\"5\\";}s:3:\\"3_1\\";a:15:{s:16:\\"login_experience\\";s:1:\\"5\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"2\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"10\\";}s:3:\\"3_2\\";a:15:{s:16:\\"login_experience\\";s:1:\\"5\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"2\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"10\\";}s:3:\\"3_3\\";a:15:{s:16:\\"login_experience\\";s:1:\\"5\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:2:\\"10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"2\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"20\\";}s:3:\\"3_4\\";a:15:{s:16:\\"login_experience\\";s:1:\\"5\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"3\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"30\\";}s:3:\\"4_5\\";a:15:{s:16:\\"login_experience\\";s:2:\\"10\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:2:\\"10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"5\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"50\\";}s:3:\\"4_6\\";a:15:{s:16:\\"login_experience\\";s:2:\\"10\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"5\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:2:\\"70\\";}s:3:\\"4_7\\";a:15:{s:16:\\"login_experience\\";s:2:\\"10\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"5\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:3:\\"100\\";}s:3:\\"4_8\\";a:15:{s:16:\\"login_experience\\";s:2:\\"10\\";s:11:\\"login_score\\";s:1:\\"0\\";s:17:\\"avatar_experience\\";s:2:\\"10\\";s:12:\\"avatar_score\\";s:1:\\"0\\";s:19:\\"complete_experience\\";s:2:\\"10\\";s:14:\\"complete_score\\";s:1:\\"0\\";s:15:\\"bang_experience\\";s:2:\\"10\\";s:10:\\"bang_score\\";s:1:\\"0\\";s:14:\\"jie_experience\\";s:3:\\"-10\\";s:9:\\"jie_score\\";s:1:\\"0\\";s:17:\\"update_experience\\";s:1:\\"5\\";s:12:\\"update_score\\";s:1:\\"0\\";s:9:\\"is_upload\\";s:1:\\"1\\";s:11:\\"is_download\\";s:1:\\"1\\";s:10:\\"attachsize\\";s:1:\\"0\\";}}');
REPLACE INTO `{dbprefix}member_setting` VALUES('complete', '0');
REPLACE INTO `{dbprefix}member_setting` VALUES('avatar', '0');
REPLACE INTO `{dbprefix}member_setting` VALUES('pay', 'a:2:{s:6:\\"tenpay\\";a:3:{s:4:\\"name\\";s:9:\\"财付通\\";s:2:\\"id\\";s:0:\\"\\";s:3:\\"key\\";s:0:\\"\\";}s:6:\\"alipay\\";a:4:{s:4:\\"name\\";s:9:\\"支付宝\\";s:8:\\"username\\";s:0:\\"\\";s:2:\\"id\\";s:0:\\"\\";s:3:\\"key\\";s:0:\\"\\";}}');
REPLACE INTO `{dbprefix}member_setting` VALUES('space', 'a:9:{s:6:\\"domain\\";s:0:\\"\\";s:4:\\"edit\\";s:1:\\"1\\";s:6:\\"verify\\";s:1:\\"0\\";s:7:\\"rewrite\\";s:1:\\"0\\";s:7:\\"seojoin\\";s:1:\\"_\\";s:5:\\"title\\";s:41:\\"会员空间_FineCMS自助建站平台！\\";s:8:\\"keywords\\";s:0:\\"\\";s:11:\\"description\\";s:0:\\"\\";s:4:\\"flag\\";a:9:{i:1;a:1:{s:4:\\"name\\";s:12:\\"达人空间\\";}i:2;a:1:{s:4:\\"name\\";s:12:\\"推荐空间\\";}i:3;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:4;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:5;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:6;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:7;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:8;a:1:{s:4:\\"name\\";s:0:\\"\\";}i:9;a:1:{s:4:\\"name\\";s:0:\\"\\";}}}');