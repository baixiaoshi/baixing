<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 主表结构（由开发者定义）
 *
 * sql: 初始化SQL语句，用{tablename}表示表名称
 * filed：初始化的自定义字段，可以用来由用户修改的字段
 */

return array(

	'sql' => '
	CREATE TABLE IF NOT EXISTS `{tablename}` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `catid` smallint(5) unsigned NOT NULL COMMENT "栏目id",
	  `title` varchar(255) DEFAULT NULL COMMENT "主题",
	  `thumb` varchar(255) DEFAULT NULL COMMENT "缩略图",
	  `banner` varchar(255) DEFAULT NULL COMMENT "宣传图",
	  `keywords` varchar(255) DEFAULT NULL COMMENT "关键字",
	  `description` text DEFAULT NULL COMMENT "描述",
	  `schedule` varchar(255) DEFAULT NULL COMMENT "进度情况",
	  `year` smallint(4) DEFAULT NULL COMMENT "年代",
	  `area` varchar(50) DEFAULT NULL COMMENT "地区",
	  `director` varchar(50) DEFAULT NULL COMMENT "导演",
	  `actor` varchar(50) DEFAULT NULL COMMENT "演员",
	  `hits` mediumint(8) unsigned DEFAULT NULL COMMENT "浏览数",
	  `uid` mediumint(8) unsigned NOT NULL COMMENT "作者id",
	  `author` varchar(50) NOT NULL COMMENT "作者名称",
	  `status` tinyint(1) unsigned NOT NULL COMMENT "审核状态",
	  `url` varchar(255) DEFAULT NULL COMMENT "地址",
	  `tableid` smallint(5) unsigned NOT NULL COMMENT "副表id",
	  `inputip` varchar(15) DEFAULT NULL COMMENT "录入者ip",
	  `inputtime` int(10) unsigned NOT NULL COMMENT "录入时间",
	  `updatetime` int(10) unsigned NOT NULL COMMENT "更新时间",
	  `displayorder` tinyint(3) NOT NULL DEFAULT "0",
	  PRIMARY KEY (`id`),
	  KEY `uid` (`uid`),
	  KEY `catid` (`catid`,`updatetime`),
	  KEY `status` (`status`),
	  KEY `hits` (`hits`),
	  KEY `displayorder` (`displayorder`,`updatetime`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT="主表";
	',
	
	'field' => array(
		array(
			'textname' => '名称',	// 字段显示名称
			'fieldname' => 'title',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'setting' => array(
				'option' => array(
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'required' => 1, // 表示必填
					'formattr' => 'onblur="check_title();get_keywords(\'keywords\');"', // 表单附件参数
				)
			)
		),
		array(
			'textname' => '封面图', // 字段显示名称
			'fieldname' => 'thumb',	// 字段名称
			'fieldtype'	=> 'File', // 字段类别
			'setting' => array(
				'option' => array(
					'ext' => 'jpg,gif,png',
					'size' => 10, 
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255', // 字段长度
				),
				'validate' => array(
					'tips' => '视频封面小图', // 提示信息
				)
			)
		),
		array(
			'textname' => '宣传图', // 字段显示名称
			'fieldname' => 'banner',	// 字段名称
			'fieldtype'	=> 'File', // 字段类别
			'issystem' => 0,
			'setting' => array(
				'option' => array(
					'ext' => 'jpg,gif,png',
					'size' => 10, 
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255', // 字段长度
				),
				'validate' => array(
					'tips' => '视频封面大图，用于首页幻灯，内容页Banner', // 提示信息
				),
			)
		),
		array(
			'textname' => '关键字', // 字段显示名称
			'fieldname' => 'keywords', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'setting' => array(
				'option' => array(
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'tips' => '多个关键字以小写分号“,”分隔', // 提示信息
				),
			)
		),
		array(
			'textname' => '描述', // 字段显示名称
			'fieldname' => 'description', // 字段名称
			'fieldtype'	=> 'Textarea', // 字段类别
			'setting' => array(
				'option' => array(
					'width' => 500, // 表单宽度
					'height' => 60,
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'filter' => 'dr_clearhtml', // 过滤html 
				),
			)
		),
		array(
			'textname' => '进度情况', // 字段显示名称
			'fieldname' => 'schedule', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
					'value' => '连载中',
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'filter' => 'dr_clearhtml', // 过滤html 
				),
			)
		),
		array(
			'textname' => '年代', // 字段显示名称
			'fieldname' => 'year', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'issystem' => 0,
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
					'value' => '',
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'tips' => '格式为：2013', // 提示信息
					'filter' => 'intval', // 过滤html 
				),
			)
		),
		array(
			'textname' => '地区', // 字段显示名称
			'fieldname' => 'area', // 字段名称
			'fieldtype'	=> 'Select', // 字段类别
			'issystem' => 0,
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
					'value' => '大陆',
					'options' => '大陆'.PHP_EOL.'香港'.PHP_EOL.'台湾'.PHP_EOL.'美国'.PHP_EOL.'韩国'.PHP_EOL.'日本'.PHP_EOL.'欧洲'.PHP_EOL.'英国'.PHP_EOL.'其他',
				),
			)
		),
		array(
			'textname' => '导演', // 字段显示名称
			'fieldname' => 'director', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'issystem' => 0,
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
					'value' => '',
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'tips' => '多个导演以,分隔', // 提示信息
				),
			)
		),
		array(
			'textname' => '演员', // 字段显示名称
			'fieldname' => 'actor', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'issystem' => 0,
			'setting' => array(
				'option' => array(
					'width' => 300, // 表单宽度
					'value' => '',
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'tips' => '多个演员以,分隔', // 提示信息
				),
			)
		),
	)

);