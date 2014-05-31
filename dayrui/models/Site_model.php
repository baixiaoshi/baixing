<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Site_model extends CI_Model {

	public $config;

    public function __construct() {
        parent::__construct();
		$this->config = array(
			'SITE_NAME'					=> '网站的名称',
			'SITE_DOMAIN'				=> '网站的域名',
			'SITE_MOBILE'				=> '是否开启手机端模板',
			'SITE_LANGUAGE'				=> '网站的语言',
			'SITE_THEME'				=> '网站的主题风格',
			'SITE_TEMPLATE'				=> '网站的模板目录',
			'SITE_TIMEZONE'				=> '所在的时区常量',
			'SITE_TIME_FORMAT'			=> '时间显示格式，与date函数一致，默认Y-m-d H:i:s',
			'SITE_TITLE'				=> '网站首页SEO标题',
			'SITE_SEOJOIN'				=> '网站SEO间隔符号',
			'SITE_KEYWORDS'				=> '网站SEO关键字',
			'SITE_DESCRIPTION'			=> '网站SEO描述信息',
			'SITE_NAVIGATOR'			=> '网站导航信息，多个导航逗号分开',
			'SITE_ATTACH_REMOTE'		=> '是否开启远程附件',
			'SITE_ATTACH_HOST'			=> '附件服务器地址',
			'SITE_ATTACH_PORT'			=> '附件服务器端口',
			'SITE_ATTACH_USERNAME'		=> '附件服务器用户名',
			'SITE_ATTACH_PASSWORD'		=> '附件服务器密码',
			'SITE_ATTACH_PATH'			=> '附件服务器目录',
			'SITE_ATTACH_PASV'			=> '被动模式(pasv)连接',
			'SITE_ATTACH_URL'			=> '附件远程访问URL',
			'SITE_ATTACH_MODE'			=> '传输模式',
			'SITE_ATTACH_EXTS'			=> '允许的附件扩展名',
			'SITE_IMAGE_RATIO'			=> '保持原始的纵横比例',
			'SITE_IMAGE_WATERMARK'		=> '图片水印功能开关',
			'SITE_IMAGE_REMOTE'			=> '远程附件水印开关',
			'SITE_IMAGE_TYPE'			=> '图片水印方式',
			'SITE_IMAGE_OVERLAY'		=> '水印图片',
			'SITE_IMAGE_OPACITY'		=> '图像不透明度，这将使水印模糊化，从而不会掩盖住底层原始图片的细节',
			'SITE_IMAGE_FONT'			=> '水印字体文件',
			'SITE_IMAGE_TEXT'			=> '水印文字',
			'SITE_IMAGE_SIZE'			=> '字体大小',
			'SITE_IMAGE_COLOR'			=> '字体颜色',
			'SITE_IMAGE_VRTALIGN'		=> '垂直对齐方式',
			'SITE_IMAGE_HORALIGN'		=> '水平对齐方式',
			'SITE_IMAGE_VRTOFFSET'		=> '垂直偏移量',
			'SITE_IMAGE_HOROFFSET'		=> '水平偏移量',
		);
    }
	
	/**
	 * 创建站点
	 *
	 * @return	id
	 */
	public function add_site($data) {
	
		if (!$data) return NULL;
		
		$data['setting']['SITE_NAVIGATOR'] = '主导航,顶部导航,底部导航,友情链接,首页幻灯,合作伙伴';
		
		$this->db->insert('site', array(
			'name' => $data['name'],
			'domain' => $data['domain'],
			'setting' => dr_array2string($data['setting'])
		));
		
		$id = $this->db->insert_id();
		$this->db->query(trim("
		CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix($id.'_page')."` (
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
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页表';
		"));
		
		$this->db->query(trim("
		CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix($id.'_block')."` (
		  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(100) NOT NULL COMMENT '文本块名称',
		  `content` text NOT NULL COMMENT '内容',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文本块表';
		"));
		
		$this->db->query(trim("
		CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix($id.'_form')."` (
		  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(50) NOT NULL COMMENT '名称',
		  `table` varchar(50) NOT NULL COMMENT '表名',
		  `setting` text DEFAULT NULL COMMENT '配置信息',
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `table` (`table`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单模型表';
		"));
		
		$this->db->query(trim("
		CREATE TABLE IF NOT EXISTS `".$this->db->dbprefix($id.'_navigator')."` (
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
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站导航表';
		"));
		
		return $id;
	}
	
	/**
	 * 修改站点
	 *
	 * @return	void
	 */
	public function edit_site($id, $data) {
	
		if (!$data || !$id) return NULL;
		
		$this->db->where('id', $id)->update('site', array(
			'name' => $data['name'],
			'domain' => $data['domain'],
			'setting' => dr_array2string($data['setting'])
		));
	}
	
	/**
	 * 站点
	 *
	 * @return	array|NULL
	 */
	public function get_site_data() {
	
		$_data = $this->db
					  ->order_by('id ASC')
					  ->get('site')
					  ->result_array();
		if (!$_data) return NULL;
		
		$data = array();
		foreach ($_data as $t) {
			$t['setting'] = dr_string2array($t['setting']);
			$t['setting']['SITE_NAME'] = $t['name'];
			$t['setting']['SITE_DOMAIN'] = $t['domain'];
			$data[$t['id']]	= $t;
		}
		
		return $data;
	}
	
}