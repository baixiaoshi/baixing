<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 附表结构（由开发者定义）
 *
 * sql: 初始化SQL语句，用{tablename}表示表名称
 * filed：初始化的自定义字段，可以用来由用户修改的字段
 */

return array(

	'sql' => '
	CREATE TABLE IF NOT EXISTS `{tablename}` (
	  `id` int(10) unsigned NOT NULL,
	  `uid` mediumint(8) unsigned NOT NULL COMMENT "作者uid",
	  `catid` smallint(5) unsigned NOT NULL COMMENT "栏目id",
	  `content` mediumtext NOT NULL COMMENT "内容",
	  `number` varchar(255) DEFAULT NULL COMMENT "商品编号",
	  `images` text DEFAULT NULL COMMENT "商品图片",
	  `format` text DEFAULT NULL COMMENT "商品规格",
	  `discount` text DEFAULT NULL COMMENT "折扣信息",
	  UNIQUE KEY `id` (`id`),
	  KEY `uid` (`uid`),
	  KEY `catid` (`catid`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT="附表";
	',
	
	'field' => array(
		array(
			'textname' => '内容', // 字段显示名称
			'fieldname' => 'content', // 字段名称
			'fieldtype'	=> 'Ueditor', // 字段类别
			'displayorder' => 13, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => '90%', // 表单宽度
					'height' => 400, // 表单高度
					'mode' => 2 // 工具栏模式
				),
				'validate' => array(
					'required' => 1 // 表示必填
				)
			)
		),
		array(
			'textname' => '商品规格', // 字段显示名称
			'fieldname' => 'format',	// 字段名称
			'fieldtype'	=> 'Format', // 字段类别
			'displayorder' => 4, // 排序号 
			'setting' => array(
				'validate' => array(
					'check' => '_format',
					'filter' => '_format'
				)
			)
		),
		array(
			'textname' => '商品折扣', // 字段显示名称
			'fieldname' => 'discount', // 字段名称
			'fieldtype'	=> 'Discount', // 字段类别
			'displayorder' => 8, // 排序号 discount
			'setting' => array(
				'validate' => array(
					'filter' => '_discount',
				)
			)
		),
		array(
			'textname' => '商品编号',	// 字段显示名称
			'fieldname' => 'number',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'displayorder' => 7, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 200, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '30' // 字段长度
				)
			)
		),
		array(
			'textname' => '商品图片',	// 字段显示名称
			'fieldname' => 'images',	// 字段名称
			'fieldtype'	=> 'Files',	// 字段类别
			'displayorder' => 12, // 排序号 
			'setting' => array(
				'option' => array(
					'size' => 10, // 文件大小
					'count' => 10, // 文件数量
					'ext' => 'gif,png,jpg' // 扩展名限制
				)
			)
		),
	)

);