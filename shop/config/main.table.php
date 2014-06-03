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
	  `title` varchar(255) NOT NULL COMMENT "商品标题",
	  `keywords` varchar(255) NOT NULL COMMENT "关键字",
	  `description` text NOT NULL COMMENT "描述",
	  `thumb` varchar(255) NOT NULL COMMENT "缩略图",
	  `price` decimal(10,2) unsigned NOT NULL COMMENT "商品价格",
	  `quantity` int(10) unsigned NOT NULL COMMENT "商品数量",
	  `city` mediumint(8) unsigned NOT NULL COMMENT "城市id",
	  `freight` varchar(255) NOT NULL COMMENT "运费模式",
	  `volume` int(10) unsigned NOT NULL COMMENT "商品成交量",
	  `onsale` tinyint(1) unsigned NOT NULL COMMENT "是否上架",
	  `review` tinyint(3) unsigned NOT NULL COMMENT "点评分值",
	  `hits` mediumint(8) unsigned NOT NULL COMMENT "浏览数",
	  `uid` mediumint(8) unsigned NOT NULL COMMENT "作者id",
	  `author` varchar(20) NOT NULL COMMENT "作者名称",
	  `status` tinyint(1) unsigned NOT NULL COMMENT "审核状态",
	  `url` varchar(255) NOT NULL COMMENT "地址",
	  `inputip` varchar(15) NOT NULL COMMENT "录入者ip",
	  `inputtime` int(10) unsigned NOT NULL COMMENT "录入时间",
	  `updatetime` int(10) unsigned NOT NULL COMMENT "更新时间",
	  `tableid` tinyint(3) unsigned NOT NULL COMMENT "副表id",
	  `displayorder` tinyint(3) NOT NULL DEFAULT "0",
	  PRIMARY KEY (`id`),
	  KEY `uid` (`uid`),
	  KEY `catid` (`catid`,`updatetime`,`price`,`quantity`,`volume`,`onsale`,`city`,`freight`),
	  KEY `status` (`status`),
	  KEY `hits` (`hits`),
	  KEY `displayorder` (`displayorder`,`updatetime`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT="主表";
	',
	
	'field' => array(
		array(
			'textname' => '商品标题',	// 字段显示名称
			'fieldname' => 'title',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'displayorder' => 1, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'required' => 1, // 表示必填
					'formattr' => 'onblur="check_title();get_keywords(\'keywords\');"', // 表单附件参数
				)
			)
		),
		array(
			'textname' => '关键字', // 字段显示名称
			'fieldname' => 'keywords', // 字段名称
			'fieldtype'	=> 'Text', // 字段类别
			'displayorder' => 2, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255' // 字段长度
				),
				'validate' => array(
					'tips' => '多个关键字以小写分号“,”分隔' // 提示信息
				),
			)
		),
		array(
			'textname' => '缩略图', // 字段显示名称
			'fieldname' => 'thumb',	// 字段名称
			'fieldtype'	=> 'File', // 字段类别
			'displayorder' => 11, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 400, // 表单宽度
					'fieldtype' => 'VARCHAR', // 字段类型
					'fieldlength' => '255', // 字段长度
					'size' => 10, 
					'ext' => 'jpg,gif,png'
				)
			)
		),
		array(
			'textname' => '商品价格',	// 字段显示名称
			'fieldname' => 'price',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'displayorder' => 5, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 150, // 表单宽度
					'fieldtype' => 'DECIMAL', // 字段类型
					'fieldlength' => '10,2' // 字段长度
				),
			)
		),
		array(
			'textname' => '商品总量',	// 字段显示名称
			'fieldname' => 'quantity',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'displayorder' => 6, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 150, // 表单宽度
					'fieldtype' => 'INT', // 字段类型
					'fieldlength' => '10' // 字段长度
				)
			)
		),
		array(
			'textname' => '成交数量',	// 字段显示名称
			'fieldname' => 'volume',	// 字段名称
			'fieldtype'	=> 'Text',	// 字段类别
			'ismember' => 0, // 前端不显示
			'displayorder' => 99, // 排序号 
			'setting' => array(
				'option' => array(
					'width' => 150, // 表单宽度
					'fieldtype' => 'INT', // 字段类型
					'fieldlength' => '10' // 字段长度
				)
			)
		),
		array(
			'textname' => '所在地',	// 字段显示名称
			'fieldname' => 'city',	// 字段名称
			'fieldtype'	=> 'Linkage',	// 字段类别
			'displayorder' => 14, // 排序号 
			'setting' => array(
				'option' => array(
					'linkage' => 'address'
				),
				'validate' => array(
					'required' => 1, // 表示必填
				)
			)
		),
		array(
			'textname' => '运费模式',	// 字段显示名称
			'fieldname' => 'freight',	// 字段名称
			'fieldtype'	=> 'Freight',	// 字段类别
			'displayorder' => 15, // 排序号 
			'setting' => array()
		),
		array(
			'textname' => '是否上架',	// 字段显示名称
			'fieldname' => 'onsale',	// 字段名称
			'fieldtype'	=> 'Radio',	// 字段类别
			'displayorder' => 99, // 排序号 
			'setting' => array(
				'option' => array(
					'value' => 1,
					'options' => '是|1'.PHP_EOL.'否|0', // 表单宽度
					'fieldtype' => 'TINYINT', // 字段类型
					'fieldlength' => '3' // 字段长度
				),
				'validate' => array(
					'required' => 1 // 表示必填
				)
			)
		),
	)

);