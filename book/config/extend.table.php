<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 扩展表结构（由开发者定义）
 *
 * sql: 初始化SQL语句，用{tablename}表示表名称
 * filed：初始化的自定义字段，可以用来由用户修改的字段
 */

return array(

	'sql' => '
	CREATE TABLE IF NOT EXISTS `{tablename}` (
	  `id` int(10) unsigned NOT NULL,
	  `cid` mediumint(8) unsigned NOT NULL COMMENT "内容id",
	  `uid` mediumint(8) unsigned NOT NULL COMMENT "作者uid",
	  `catid` smallint(5) unsigned NOT NULL COMMENT "栏目id",
	  `mytype` smallint(5) unsigned DEFAULT NULL COMMENT "分卷类别",
	  `name` varchar(255) DEFAULT NULL COMMENT "名称",
	  `body` mediumtext DEFAULT NULL COMMENT "内容",
	  `url` varchar(255) DEFAULT NULL COMMENT "地址",
	  `inputtime` int(10) unsigned NOT NULL COMMENT "录入时间",
	  `displayorder` tinyint(3) NOT NULL DEFAULT "0",
	  UNIQUE KEY `id` (`id`),
	  KEY `uid` (`uid`),
	  KEY `catid` (`catid`),
	  KEY `displayorder` (`displayorder`,`inputtime`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT="扩展表";
	',
	
	'field' => array(
		array(
			'textname' => '分卷类别',	// 字段显示名称
			'fieldname' => 'mytype',	// 字段名称
			'fieldtype'	=> 'Mytype',	// 字段类别
			'setting' => array(
				'validate' => array(
					'xss' => 1, // xss过滤
				)
			)
		),
		array(
			'textname' => '章节名',	// 字段显示名称
			'fieldname' => 'name',	// 字段名称
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
				)
			)
		),
		array(
			'textname' => '内容', // 字段显示名称
			'fieldname' => 'body', // 字段名称
			'fieldtype'	=> 'Ueditor', // 字段类别
			'setting' => array(
				'option' => array(
					'mode' => 1, // 工具栏模式
					'width' => '90%', // 表单宽度
					'height' => 400, // 表单高度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
					'required' => 1, // 表示必填
				)
			)
		)
	)

);