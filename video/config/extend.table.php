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
	  `preview` varchar(255) DEFAULT NULL COMMENT "视频预览图",
	  `name` varchar(255) DEFAULT NULL COMMENT "分集名称",
	  `desc` varchar(255) DEFAULT NULL COMMENT "简单描述",
	  `body` text DEFAULT NULL COMMENT "分集简介",
	  `video` text DEFAULT NULL COMMENT "视频信息",
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
			'textname' => '预览图', // 字段显示名称
			'fieldname' => 'preview',	// 字段名称
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
					'tips' => '视频的预览图片', // 提示信息
				),
			)
		),
		array(
			'textname' => '分集名称',	// 字段显示名称
			'fieldname' => 'name',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
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
			'textname' => '简单描述',	// 字段显示名称
			'fieldname' => 'desc',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'setting' => array(
				'option' => array(
					'width' => 300, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
				)
			)
		),
		array(
			'textname' => '视频信息', // 字段显示名称
			'fieldname' => 'video', // 字段名称
			'fieldtype'	=> 'Video', // 字段类别
			'setting' => array(
				'option' => array(
					'ext' => 'mp4,flv',
					'size' => 10, 
					'width' => '90%', // 表单宽度
				),
				'validate' => array(
					'tips' => '支持youku、tudou、56、ltv、ku6视频地址', // 提示信息
					'required' => 1, // 表示必填
				)
			)
		),
		array(
			'textname' => '分集简介', // 字段显示名称
			'fieldname' => 'body', // 字段名称
			'fieldtype'	=> 'Ueditor', // 字段类别
			'setting' => array(
				'option' => array(
					'mode' => 2, // 工具栏模式
					'width' => '90%', // 表单宽度
					'height' => 300, // 表单高度
				),
				'validate' => array(
					'xss' => 1, // xss过滤
				)
			)
		),
	)

);