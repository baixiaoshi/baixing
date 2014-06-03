<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Module_model extends CI_Model {
	
	private $system_table; // 系统默认表
	
	/*
	 * 模块模型类
	 */
    public function __construct() {
        parent::__construct();
		
		$favorite = "
		CREATE TABLE IF NOT EXISTS `{tablename}` (
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
		";
		
		$buy = "
		CREATE TABLE IF NOT EXISTS `{tablename}` (
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
		";
		
		$this->system_table = array(
			'verify' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
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
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='审核表';",
			
			'index' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
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
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='索引表';",
			
			'extend' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
			  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
			  `tableid` smallint(5)unsigned NOT NULL COMMENT '附表id',
			  PRIMARY KEY (`id`),
			  KEY `cid` (`cid`),
			  KEY `uid` (`uid`),
			  KEY `catid` (`catid`),
			  KEY `tableid` (`tableid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='扩展表';",
			
			'category' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
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
				`permission` text NULL COMMENT '会员权限',
				`setting` text NOT NULL COMMENT '属性配置',
				`displayorder` tinyint(3) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				KEY `show` (`show`),
				KEY `module` (`pid`,`displayorder`,`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';",
			
			'category_data' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
			  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
			  PRIMARY KEY (`id`),
			  KEY `uid` (`uid`),
			  KEY `catid` (`catid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目附加表';",
			
			'category_data_0' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
			  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
			  PRIMARY KEY (`id`),
			  KEY `uid` (`uid`),
			  KEY `catid` (`catid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目附加表';",
			
			'tag' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(200) NOT NULL COMMENT 'tag名称',
			  `code` varchar(200) NOT NULL COMMENT 'tag代码（拼音）',
			  `hits` mediumint(8) unsigned NOT NULL COMMENT '点击量',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `name` (`name`),
			  KEY `letter` (`code`,`hits`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Tag标签表';
			",
			
			'flag' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文档标记id',
			  `id` int(10) unsigned NOT NULL COMMENT '文档内容id',
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
			  `catid` smallint(5) unsigned NOT NULL COMMENT '栏目id',
			  KEY `flag` (`flag`,`id`,`uid`),
			  KEY `catid` (`catid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标记表';
			",
			
			'search' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
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
			",
			
			'html' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
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
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='html文件存储表';",
			
			'form' => "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL COMMENT '表单名称',
			  `disabled` tinyint(1) unsigned NOT NULL COMMENT '是否禁用',
			  `permission` text NOT NULL COMMENT '会员权限',
			  `setting` text NOT NULL COMMENT '表单配置',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块表单表';
			",
			
			'favorite_0' => $favorite,
			
			'favorite_1' => $favorite,
			
			'favorite_2' => $favorite,
			
			'favorite_3' => $favorite,
			
			'favorite_4' => $favorite,
			
			'favorite_5' => $favorite,
			
			'favorite_6' => $favorite,
			
			'favorite_7' => $favorite,
			
			'favorite_8' => $favorite,
			
			'favorite_9' => $favorite,
			
			
			'buy_0' => $buy,
			
			'buy_1' => $buy,
			
			'buy_2' => $buy,
			
			'buy_3' => $buy,
			
			'buy_4' => $buy,
			
			'buy_5' => $buy,
			
			'buy_6' => $buy,
			
			'buy_7' => $buy,
			
			'buy_8' => $buy,
			
			'buy_9' => $buy,

		);	
	}
	
	/**
	 * 所有模块
	 *
	 * @return	array
	 */
	public function get_data() {
		$_data = $this->db
					  ->order_by('id ASC')
					  ->get('module')
					  ->result_array();
		if (!$_data) return NULL;
		$data = array();
		foreach ($_data as $t) {
			$t['site'] = dr_string2array($t['site']);
			$t['setting'] = dr_string2array($t['setting']);
			$data[$t['dirname']] = $t;
		}
		return $data;
	}
	
	/**
	 * 模块数据
	 *
	 * @param	int		$id
	 * @return	array
	 */
	public function get($id) {
		$data = $this->db
					 ->limit(1)
					 ->where('id', (int)$id)
					 ->get('module')
					 ->row_array();
		if (!$data) return NULL;
		$data['site'] = dr_string2array($data['site']);
		$data['setting'] = dr_string2array($data['setting']);
		return $data;
	}
	
	/**
	 * 模块入库
	 *
	 * @param	string	$dir
	 * @return	intval
	 */
	public function add($dir) {
		if (!$dir) return NULL;
		$config = require FCPATH.$dir.'/config/module.php';
		$extend = (int)$config['extend'];
		$this->db->insert('module', array(
			'site' => '',
			'extend' => $extend,
			'dirname' => $dir,
			'setting' => '',
			'disabled' => 0,
		));
		$id = $this->db->insert_id();
		if (!$id) return NULL;
		// 字段入库
		$main = require FCPATH.$dir.'/config/main.table.php'; // 主表信息
		foreach ($main['field'] as $field) {
			$this->add_field($id, $field, 1);
		}
		$data = require FCPATH.$dir.'/config/data.table.php'; // 附表信息
		foreach ($data['field'] as $field) {
			$this->add_field($id, $field, 0);
		}
		if ($extend) {
			$data = require FCPATH.$dir.'/config/extend.table.php'; // 扩展表信息
			foreach ($data['field'] as $field) {
				$this->add_field($id, $field, 1, 1);
			}
		}
		// 菜单
		if (is_file(FCPATH.$dir.'/config/menu.php')) {
			$name = $config['name']; // 顶部菜单名称
			$menu = require FCPATH.$dir.'/config/menu.php';
			// 插入后台的顶级菜单
			$this->db->insert('admin_menu', array(
				'uri' => '',
				'pid' => 0,
				'mark' => 'module-'.$dir,
				'name' => $name,
				'displayorder' => 0,
			));
			$topid = $this->db->insert_id();
			foreach ($menu['admin'] as $left) { // 分组菜单名称
				$this->db->insert('admin_menu', array(
					'uri' => '',
					'pid' => $topid,
					'mark' => 'module-'.$dir,
					'name' => $left['name'],
					'displayorder' => 0,
				));
				$leftid = $this->db->insert_id();
				foreach ($left['menu'] as $link) { // 链接菜单
					$this->db->insert('admin_menu', array(
						'pid' => $leftid,
						'uri' => strpos($link['uri'], '{id}') === FALSE ? trim($dir.'/'.$link['uri'], '/') : str_replace('{id}', $id, $link['uri']),
						'mark' => 'module-'.$dir,
						'name' => $link['name'],
						'displayorder' => 0,
					));
				}
			}
			// 插入会员的顶级菜单
			$this->db->insert('member_menu', array(
				'pid' => 0,
				'uri' => '',
				'url' => '',
				'mark' => 'module-'.$dir,
				'name' => $name,
				'target' => 0,
				'displayorder' => 0,
			));
			$topid = $this->db->insert_id();
			foreach ($menu['member'] as $left) { // 分组菜单名称
				$this->db->insert('member_menu', array(
					'uri' => '',
					'url' => '',
					'pid' => $topid,
					'mark' => 'module-'.$dir,
					'name' => $left['name'],
					'target' => 0,
					'displayorder' => 0,
				));
				$leftid = $this->db->insert_id();
				if ($left['menu']) {
					foreach ($left['menu'] as $link) { // 链接菜单
						$this->db->insert('member_menu', array(
							'pid' => $leftid,
							'url' => '',
							'uri' => strpos($link['uri'], '{id}') === FALSE ? trim($dir.'/'.$link['uri'], '/') : str_replace('{id}', $id, $link['uri']),
							'mark' => 'module-'.$dir,
							'name' => $link['name'],
							'target' => 0,
							'displayorder' => 0,
						));
					}
				}
			}
		}
		return $id;
	}
	
	// 模块的导出
	public function export($dir, $name) {
	
		if (!is_dir(FCPATH.$dir)) return '模块目录不存在';
		
		// 模块信息
		$module = $this->db
					   ->limit(1)
					   ->where('dirname', $dir)
					   ->get('module')
					   ->row_array();
		if (!$module) return '模块不存在或者尚未安装';
		
		// 模块配置文件
		$config = require FCPATH.$dir.'/_config/module.php';
		$config['key'] = 0;
		$config['name'] = $name ? $name : $config['name'];
		$config['author'] = SITE_NAME;
		$config['version'] = '';
		$this->load->library('dconfig');
		$size = $this->dconfig
					 ->file(FCPATH.$dir.'/config/module.php')
					 ->note('模块配置文件')
					 ->space(24)
					 ->to_require_one($config);
		if (!$size) return '目录'.$dir.'不可写！';
		
		// 主表字段
		$db = $this->site[SITE_ID];
		$file = FCPATH.$dir.'/config/main.table.php';
		$table = array();
		$header = $this->dconfig
					   ->file($file)
					   ->note('主表结构（由开发者定义）')
					   ->to_header();
		$sql = $db->query("SHOW CREATE TABLE `".$this->db->dbprefix(SITE_ID.'_'.$dir)."`")->row_array();
		$table['sql'] = str_replace(array($sql['Table'], 'CREATE TABLE'), array('{tablename}', 'CREATE TABLE IF NOT EXISTS'), $sql['Create Table']);
		$field = $this->db
					  ->where('relatedname', 'module')
					  ->where('relatedid', $module['id'])
					  ->where('ismain', 1)
					  ->get('field')
					  ->result_array();
		if (!$field) return '此模块无主表字段，不支持导出';
		foreach ($field as $t) {
			$t['textname'] = $t['name'];
			unset($t['id'], $t['name']);
			$t['issystem'] = 1;
			$t['setting'] = dr_string2array($t['setting']);
			$table['field'][] = $t;
		}
		file_put_contents($file, $header.PHP_EOL.'return '.var_export($table, true).';?>');
		
		// 附表字段
		$file = FCPATH.$dir.'/config/data.table.php';
		$table = array();
		$header = $this->dconfig
					   ->file($file)
					   ->note('附表结构（由开发者定义）')
					   ->to_header();
		$sql = $db->query("SHOW CREATE TABLE `".$this->db->dbprefix(SITE_ID.'_'.$dir.'_data_0')."`")->row_array();
		$table['sql'] = str_replace(array($sql['Table'], 'CREATE TABLE'), array('{tablename}', 'CREATE TABLE IF NOT EXISTS'), $sql['Create Table']);
		$field = $this->db
					  ->where('relatedname', 'module')
					  ->where('relatedid', $module['id'])
					  ->where('ismain', 0)
					  ->get('field')
					  ->result_array();
		if ($field) {
			foreach ($field as $t) {
				$t['textname'] = $t['name'];
				unset($t['id'], $t['name']);
				$t['issystem'] = 1;
				$t['setting'] = dr_string2array($t['setting']);
				$table['field'][] = $t;
			}
		}
		file_put_contents($file, $header.PHP_EOL.'return '.var_export($table, true).';?>');
		
		if ($config['extend']) {
			// 内容扩展表字段
			$file = FCPATH.$dir.'/config/extend.table.php';
			$table = array();
			$header = $this->dconfig
						   ->file($file)
						   ->note('内容扩展表结构（由开发者定义）')
						   ->to_header();
			$sql = $db->query("SHOW CREATE TABLE `".$this->db->dbprefix(SITE_ID.'_'.$dir.'_extend')."`")->row_array();
			$table['sql'] = str_replace(array($sql['Table'], 'CREATE TABLE'), array('{tablename}', 'CREATE TABLE IF NOT EXISTS'), $sql['Create Table']);
			$field = $this->db
						  ->where('relatedname', 'extend')
						  ->where('relatedid', $module['id'])
						  ->get('field')
						  ->result_array();
			if ($field) {
				foreach ($field as $t) {
					$t['textname'] = $t['name'];
					unset($t['id'], $t['name']);
					$t['issystem'] = 1;
					$t['setting'] = dr_string2array($t['setting']);
					$table['field'][] = $t;
				}
			}
			file_put_contents($file, $header.PHP_EOL.'return '.var_export($table, true).';?>');
		}
		// 导出表单
		$this->export_form($dir);
		
		return NULL;
	}
	
	// 导出表单
	public function export_form($dir) {
		
		$db = $this->site[SITE_ID];
		$form = $this->db
					 ->where('disabled', 0)
					 ->order_by('id ASC')
					 ->get(SITE_ID.'_'.$dir.'_form')
					 ->result_array();
		
		if ($form) {
			$fdata = array();
			foreach ($form as $t) {
				$sql = $db->query("SHOW CREATE TABLE `".$this->db->dbprefix(SITE_ID.'_'.$dir.'_form_'.$t['id'])."`")->row_array();
				$sql = str_replace(array($sql['Table'], 'CREATE TABLE'), array('{tablename}', 'CREATE TABLE IF NOT EXISTS'), $sql['Create Table']);
				// 模块表单的自定义字段
				$field = $this->db
							  ->where('disabled', 0)
							  ->where('relatedid', $t['id'])
							  ->where('relatedname', 'mform-'.$dir)
							  ->order_by('displayorder ASC, id ASC')
							  ->get('field')
							  ->result_array();
				$fdata[$t['id']] = array(
					'sql' => $sql,
					'data' => $t,
					'field' => $field,
				);
			}
			$file = FCPATH.$dir.'/config/form.php';
			$this->load->library('dconfig');
			$header = $this->dconfig
						   ->file($file)
						   ->note('表单的结构（此文件由导出产生，无需开发者定义）')
						   ->to_header();
			file_put_contents($file, $header.PHP_EOL.'return '.var_export($fdata, true).';?>');
		}
	}
	
	// 导入表单
	public function import_form($dir) {
		
		$file = FCPATH.$dir.'/config/form.php';
		if (!is_file($file)) return FALSE;
		
		$data = require_once $file;
		if (!$data) return FALSE;
				
		$db = $this->site[SITE_ID];
		$table = $this->db->dbprefix(SITE_ID.'_'.$dir.'_form');
		foreach ($data as $id => $form) {
			// 插入表单
			$db->replace($table, $form['data']);
			// 创建表
			$db->query('DROP TABLE IF EXISTS `'.$table.'_'.$id.'`');
			$db->query(trim(str_replace('{tablename}', $table.'_'.$id, $form['sql'])));
			// 添加数据库
			foreach ($form['field'] as $t) {
				unset($t['id']);
				$t['relatedname'] = 'mform-'.$dir;
				$this->db->insert('field', $t);
			}
			$name = 'Form_'.SITE_ID.'_'.$id;
			// 创建管理控制器
			$file = FCPATH.$dir.'/controllers/admin/'.$name.'.php';
			if (!file_exists($file)) {
				file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
				.'require FCPATH.\'dayrui/core/D_Admin_Form.php\';'.PHP_EOL.PHP_EOL
				.'class '.$name.' extends D_Admin_Form {'.PHP_EOL.PHP_EOL
				.'	public function __construct() {'.PHP_EOL
				.'		parent::__construct();'.PHP_EOL
				.'	}'.PHP_EOL
				.'}');
			}
			// 会员控制器
			$file = FCPATH.$dir.'/controllers/member/'.$name.'.php';
			if (!file_exists($file)) {
				file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
				.'require FCPATH.\'dayrui/core/D_Member_Form.php\';'.PHP_EOL.PHP_EOL
				.'class '.$name.' extends D_Member_Form {'.PHP_EOL.PHP_EOL
				.'	public function __construct() {'.PHP_EOL
				.'		parent::__construct();'.PHP_EOL
				.'	}'.PHP_EOL
				.'}');
			}
			// 前端发布控制器
			$file = FCPATH.$dir.'/controllers/'.$name.'.php';
			if (!file_exists($file)) {
				file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
				.'require FCPATH.\'dayrui/core/D_Home_Form.php\';'.PHP_EOL.PHP_EOL
				.'class '.$name.' extends D_Home_Form {'.PHP_EOL.PHP_EOL
				.'	public function __construct() {'.PHP_EOL
				.'		parent::__construct();'.PHP_EOL
				.'	}'.PHP_EOL
				.'}');
			}
		}
		
		return TRUE;
	}
	
	/**
	 * 字段入库
	 *
	 * @param	intval	$id		模块id
	 * @param	array	$field	字段信息
	 * @param	intval	$ismain	是否主表
	 * @param	intval	$extend	是否是扩展表
	 * @return	bool
	 */
	private function add_field($id, $field, $ismain, $extend = 0) {
		$this->db->insert('field', array(
			'name' => $field['textname'],
			'ismain' => $ismain,
			'setting' => dr_array2string($field['setting']),
			'issystem' => isset($field['issystem']) ? (int)$field['issystem'] : 1,
			'ismember' => isset($field['ismember']) ? (int)$field['ismember'] : 1,
			'disabled' => isset($field['disabled']) ? (int)$field['disabled'] : 0,
			'fieldname' => $field['fieldname'],
			'fieldtype' => $field['fieldtype'],
			'relatedid' => $id,
			'relatedname' => $extend ? 'extend' : 'module',
			'displayorder' => (int)$field['displayorder'],
		));
	}
	
	/**
	 * 安装到站点
	 *
	 * @param	intval	$id		模块id
	 * @param	string	$dir	模块目录
	 * @param	array	$siteid	站点id
	 * @return	void
	 */
	public function install($id, $dir, $siteid) {
	
		if (!$id || !$dir || !$siteid || !isset($this->site[$siteid])) return NULL;
		
		$config = require FCPATH.$dir.'/config/module.php'; // 配置信息
		$extend = (int)$config['extend'];
		$install = NULL; // 初始化数据
		
		// 表前缀部分：站点id_模块目录[_表名称]
		$db = $this->site[$siteid];
		$prefix = $this->db->dbprefix($siteid.'_'.$dir);
		
		// 主表
		$data = require FCPATH.$dir.'/config/main.table.php'; // 主表信息
		$db->query('DROP TABLE IF EXISTS `'.$prefix.'`');
		$db->query(trim(str_replace('{tablename}', $prefix, $data['sql'])));
		
		// 附表
		$data = require FCPATH.$dir.'/config/data.table.php'; // 附表信息
		$db->query('DROP TABLE IF EXISTS `'.$prefix.'_data_0'.'`');
		$db->query(trim(str_replace('{tablename}', $prefix.'_data_0', $data['sql'])));
		
		if ($extend) {
			// 扩展表
			$data = require FCPATH.$dir.'/config/extend.table.php'; // 附表信息
			$db->query('DROP TABLE IF EXISTS `'.$prefix.'_extend_0'.'`');
			$db->query(trim(str_replace('{tablename}', $prefix.'_extend_0', $data['sql'])));
		}
		
		// 系统默认表
		foreach ($this->system_table as $table => $sql) {
			$db->query('DROP TABLE IF EXISTS `'.$prefix.'_'.$table.'`');
			$db->query(trim(str_replace('{tablename}', $prefix.'_'.$table, $sql)));
		}
		
		// 插入初始化数据
		if (is_file(FCPATH.$dir.'/config/install.sql') && $install = file_get_contents(FCPATH.$dir.'/config/install.sql')) {
			$_sql = str_replace(
				array('{tablename}', '{dbprefix}', '{moduleid}', '{moduledir}', '{siteid}'), 
				array($prefix, $this->db->dbprefix, $id, $dir, SITE_ID), 
				$install
			);
			$sql_data = explode(';SQL_FINECMS_EOL', trim(str_replace(array(PHP_EOL, chr(13), chr(10)), 'SQL_FINECMS_EOL', $_sql)));
			foreach($sql_data as $query) {
				if (!$query) continue;
				$ret = '';
				$queries = explode('SQL_FINECMS_EOL', trim($query));
				foreach($queries as $query) {
					$ret .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
				}
				if (!$ret) continue;
				$db->query($ret);
			}
			unset($query, $sql_data, $_sql, $queries, $ret);
		}
		
		// 导入表单
		$this->import_form($dir);
	}
	
	/**
	 * 从站点中卸载
	 *
	 * @param	intval	$id		模块id
	 * @param	string	$dir	模块目录
	 * @param	array	$siteid	站点id
	 * @param	intval	$delete	是否删除菜单
	 * @return	void
	 */
	public function uninstall($id, $dir, $siteid, $delete = 0) {
	
		if (!$id || !$dir || !$siteid || !isset($this->site[$siteid])) return NULL;
		
		$config = require FCPATH.$dir.'/config/module.php'; // 配置信息
		$extend = (int)$config['extend'];
		
		// 表前缀部分：站点id_模块目录[_表名称]
		$prefix = $this->db->dbprefix($siteid.'_'.$dir);
		$db = $this->site[$siteid];
		
		// 清空附件
		$this->load->model('attachment_model');
		$this->attachment_model->delete_for_table($prefix, TRUE);
		
		// 主表
		$db->query('DROP TABLE IF EXISTS `'.$prefix.'`');
		
		// 附表
		for ($i = 0; $i < 100; $i ++) {
			if (!$db->query("SHOW TABLES LIKE '%".$prefix.'_data_'.$i."%'")->row_array()) break;
			$db->query('DROP TABLE IF EXISTS '.$prefix.'_data_'.$i);
		}
		
		if ($extend) {
			// 扩展表
			for ($i = 0; $i < 100; $i ++) {
				if (!$db->query("SHOW TABLES LIKE '%".$prefix.'_extend_'.$i."%'")->row_array()) break;
				$db->query('DROP TABLE IF EXISTS '.$prefix.'_extend_'.$i);
			}
		}
		
		// 表单数据表
		$form = $db->get($prefix.'_form')->result_array();
		if ($form) {
			foreach ($form as $t) {
				$db->query('DROP TABLE IF EXISTS '.$prefix.'_form_'.$t['id']);
				$this->attachment_model->delete_for_table($prefix.'_form_'.$t['id'], TRUE);
			}
		}
		
		// 系统默认表
		foreach ($this->system_table as $table => $sql) {
			$db->query('DROP TABLE IF EXISTS `'.$prefix.'_'.$table.'`');
		}
		
		// 删除栏目字段
		$this->db
			 ->where('relatedname', $dir.'-'.$siteid)
			 ->delete('field');
			 
		// 当站点数量小于2时删除菜单
		if ($delete < 2) {	 
			// 删除后台菜单
			$this->db
				 ->where('mark', 'module-'.$dir)
				 ->delete('admin_menu');
			$this->db
				 ->like('mark', 'module-'.$dir.'-%')
				 ->delete('admin_menu');
				 
			// 删除会员菜单
			$this->db
				 ->where('mark', 'module-'.$dir)
				 ->delete('member_menu');
			$this->db
				 ->like('mark', 'module-'.$dir.'-%')
				 ->delete('member_menu');
		}
		
		// 插入初始化数据
		if (is_file(FCPATH.$dir.'/config/uninstall.sql') && $uninstall = file_get_contents(FCPATH.$dir.'/config/uninstall.sql')) { 
			$_sql = str_replace(
				array('{tablename}', '{dbprefix}', '{moduleid}', '{moduledir}', '{siteid}'), 
				array($prefix, $this->db->dbprefix, $id, $dir, SITE_ID), 
				$uninstall
			);
			$sql_data = explode(';SQL_FINECMS_EOL', trim(str_replace(array(PHP_EOL, chr(13), chr(10)), 'SQL_FINECMS_EOL', $_sql)));
			foreach($sql_data as $query){
				if (!$query) continue;
				$queries = explode('SQL_FINECMS_EOL', trim($query));
				$ret = '';
				foreach($queries as $query) {
					$ret .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
				}
				if (!$ret) continue;
				$db->query($ret);
			}
			unset($query, $sql_data, $_sql, $queries, $ret);
		}
		
		// 删除应用相关表
		$app = $this->ci->get_cache('app');
		if ($app) {
			foreach ($app as $adir) {
				if (is_file(FCPATH.'app/'.$adir.'/models/'.$adir.'_model.php')) {
					$this->load->add_package_path(FCPATH.'app/'.$adir.'/');
					$this->load->model($adir.'_model', 'app_model');
					$this->app_model->delete_for_module($dir, $siteid);
				}
			}
		}
	}
	
	/**
	 * 清空当前站点的模块数据
	 *
	 * @param	string	$dir	模块目录
	 * @return	void
	 */
	public function clear($dir) {
	
		if (!$dir) return NULL;
		
		$config = require FCPATH.$dir.'/config/module.php'; // 配置信息
		$extend = (int)$config['extend'];
		
		// 表前缀部分：站点id_模块目录[_表名称]
		$prefix = $this->db->dbprefix(SITE_ID.'_'.$dir);
		$db = $this->site[SITE_ID];
		
		// 主表
		$db->query('TRUNCATE TABLE `'.$prefix.'`');
		
		// 附表
		for ($i = 0; $i < 100; $i ++) {
			if (!$db->query("SHOW TABLES LIKE '%".$prefix.'_data_'.$i."%'")->row_array()) break;
			$db->query('TRUNCATE TABLE '.$prefix.'_data_'.$i);
		}
		
		if ($extend) {
			// 扩展表
			for ($i = 0; $i < 100; $i ++) {
				if (!$db->query("SHOW TABLES LIKE '%".$prefix.'_extend_'.$i."%'")->row_array()) break;
				$db->query('TRUNCATE TABLE '.$prefix.'_extend_'.$i);
			}
		}
		
		// 系统默认表
		foreach ($this->system_table as $table => $sql) {
			$db->query('TRUNCATE TABLE `'.$prefix.'_'.$table.'`');
		}
		
		// 删除应用相关表
		$app = $this->ci->get_cache('app');
		if ($app) {
			foreach ($app as $adir) {
				if (is_file(FCPATH.'app/'.$adir.'/models/'.$adir.'_model.php')) {
					$this->load->add_package_path(FCPATH.'app/'.$adir.'/');
					$this->load->model($adir.'_model', 'app_model');
					$this->app_model->delete_for_module($dir, $siteid);
				}
			}
		}
	}
	
	/**
	 * 修改
	 *
	 * @param	array	$_data	老数据
	 * @param	array	$data	新数据
	 * @return	void
	 */
	public function edit($_data, $data) {
	
		$this->db
             ->where('id', $_data['id'])
             ->update('module', array(
				 'site' => dr_array2string($data['site']),
				 'setting' => dr_array2string($data['setting'])
			 ));
		if ($data['site'] === $_data['site']) return NULL; // 站点无变动时不处理
		
		// 新提交的站点不在老配置中时，表示新增
		foreach ($data['site'] as $siteid => $cfg) {
			if (!isset($_data['site'][$siteid]['use']) || !$_data['site'][$siteid]['use']) {
				$this->install($_data['id'], $_data['dirname'], $siteid);
			}
		}
		
		// 老站点不在新提交的配置中时，表示删除
		foreach ($_data['site'] as $siteid => $cfg) {
			if (!isset($data['site'][$siteid]['use']) || !$data['site'][$siteid]['use']) {
				$this->uninstall($_data['id'], $_data['dirname'], $siteid, 3);
			}
		}
		
		return NULL;
	}
	
	/**
	 * 删除
	 *
	 * @param	intval	$id
	 * @return	void
	 */
	public function del($id) {
		// 模块信息
		$data = $this->get($id);
		if (!$data) return NULL;
		// 删除模块数据和卸载全部站点
		$this->db
			 ->where('id', $id)
			 ->delete('module');
		foreach ($data['site'] as $siteid => $url) {
			$this->uninstall($data['id'], $data['dirname'], $siteid);
		}
		// 删除模块字段
		$this->db
			 ->where('relatedname', 'module')
			 ->where('relatedid', $id)
			 ->delete('field');
		// 删除扩展字段
		$this->db
			 ->where('relatedname', 'extend')
			 ->where('relatedid', $id)
			 ->delete('field');
		// 删除表单字段
		$this->db
			 ->where('relatedname', 'mform-'.$data['dirname'])
			 ->delete('field');
	}
	
	/**
	 * 格式化字段数据
	 *
	 * @param	array	$data	新数据
	 * @return	array
	 */
	private function get_field_value($data) {
		if (!$data) return NULL;
		$data['setting'] = dr_string2array($data['setting']);
		return $data;
	}
	
	/**
	 * 模块缓存
	 *
	 * @param	string	$dirname	模块名称
	 * @param	intval	$update		是否更新数量
	 * @return	NULL
	 */
	public function cache($dirname, $update = 1) {
		
		$data = $this->db // 模块
					 ->where('disabled', 0)
					 ->where('dirname', $dirname)
					 ->get('module')
					 ->row_array();
		if (!$data) return NULL;
		
		$site_domain = require FCPATH.'config/domain.php'; // 加载站点域名配置文件
		$data['site'] = dr_string2array($data['site']);
		$data['setting'] = dr_string2array($data['setting']);
		
		// 按站点生成缓存
		foreach ($this->SITE as $siteid => $t) {
			
			$cache = $data;
			$this->cache->delete('module-'.$siteid.'-'.$dirname);
			$this->ci->clear_cache('module-'.$siteid.'-'.$dirname);
			
			if (isset($data['site'][$siteid]['use']) && $data['site'][$siteid]['use']) {
			
				$domain = $data['site'][$siteid]['domain'];
				if ($domain) $site_domain[$domain] = $siteid; // 将站点保存至域名配置文件
				
				$cache['url'] = $domain ? 'http://'.$domain.'/' : $this->SITE[$siteid]['SITE_URL'].$dirname.'/'; // 模块的URL地址
				$cache['html'] = $data['site'][$siteid]['html'];
				$cache['theme'] = $data['site'][$siteid]['theme'];
				$cache['domain'] = $data['site'][$siteid]['domain'];
				$cache['template'] = $data['site'][$siteid]['template'];
				
				// 非主站开启静态生成时，创建新的入口文件
				if ($cache['html'] && $siteid > 1) {
					$path = FCPATH.$cache['dirname'].'/html/'.$siteid;
					if (!file_exists($path)) dr_mkdirs($path, TRUE);
					copy(FCPATH.$cache['dirname'].'/html.php', $path.'/index.php');
				}
				
				// 模块的自定义字段
				$field = $this->db
							  ->where('disabled', 0)
							  ->where('relatedid', $data['id'])
							  ->where('relatedname', 'module')
							  ->order_by('displayorder ASC, id ASC')
							  ->get('field')
							  ->result_array();
				foreach ($field as $f) {
					$cache['field'][$f['fieldname']] = $this->get_field_value($f);
				}
				
				if ($data['extend']) {
					// 模块扩展的自定义字段
					$field = $this->db
								  ->where('disabled', 0)
								  ->where('relatedid', $data['id'])
								  ->where('relatedname', 'extend')
								  ->order_by('displayorder ASC, id ASC')
								  ->get('field')
								  ->result_array();
					$cache['extend'] = array();
					if ($field) {
						foreach ($field as $f) {
							$cache['extend'][$f['fieldname']] = $this->get_field_value($f);
						}
					}
				} else {
					$cache['extend'] = 0;
				}
				
				// 模块表单缓存
				$form = $this->site[$siteid]
							 ->where('disabled', 0)
							 ->order_by('id ASC')
							 ->get($siteid.'_'.$dirname.'_form')
							 ->result_array();
				if ($form) {
					foreach ($form as $t) {
						$t['field'] = array();
						// 模块表单的自定义字段
						$field = $this->db
									  ->where('disabled', 0)
									  ->where('relatedid', $t['id'])
									  ->where('relatedname', 'mform-'.$data['dirname'])
									  ->order_by('displayorder ASC, id ASC')
									  ->get('field')
									  ->result_array();
						if ($field) {
							foreach ($field as $f) {
								$t['field'][$f['fieldname']] = $this->get_field_value($f);
							}
						}
						$t['setting'] = dr_string2array($t['setting']);
						$t['permission'] = dr_string2array($t['permission']);
						$cache['form'][$t['id']] = $t;
					}
				}
				
				// 模块的栏目分类
				$category = $this->site[$siteid]
								 ->order_by('displayorder ASC, id ASC')
								 ->get($siteid.'_'.$dirname.'_category')
								 ->result_array();
				
				if ($category) {
					$CAT = $CAT_DIR = $level = array();
					foreach ($category as $c) {
						if ($update == 1) {
							if (!$c['child']) {
								$c['total'] = $this->site[$siteid]
												   ->where('status', 9)
												   ->where('catid', $c['id'])
												   ->count_all_results($siteid.'_'.$dirname.'_index');
							} else {
								$c['total'] = 0;
							}
						} else {
							$c['total'] = $this->ci->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $c['id'], 'total');
						}
						$pid = explode(',', $c['pids']);
						$level[] = substr_count($c['pids'], ',');
						$c['topid'] = isset($pid[1]) ? $pid[1] : $c['id'];
						$c['catids'] = explode(',', $c['childids']);
						$c['setting'] = dr_string2array($c['setting']);
						$c['permission'] = dr_string2array($c['permission']);
						$c['url'] = dr_category_url($cache, $c);
						$CAT[$c['id']] = $c;
						$CAT_DIR[$c['dirname']] = $c['id'];
					}
					
					// 更新父栏目数量
					if ($update == 1) {
						foreach ($category as $c) {
							if ($c['child']) {
								$arr = explode(',', $c['childids']);
								$CAT[$c['id']]['total'] = 0;
								foreach ($arr as $i) {
									$CAT[$c['id']]['total']+= $CAT[$i]['total'];
								}
							}
						}
					}
					
					// 栏目自定义字段，把父级栏目的字段合并至当前栏目
					$field = $this->db
								  ->where('disabled', 0)
								  ->where('relatedname', $dirname.'-'.$siteid)
								  ->order_by('displayorder ASC, id ASC')
								  ->get('field')
								  ->result_array(); 
					if ($field) {
						foreach ($field as $f) {
							if (isset($CAT[$f['relatedid']]['childids']) && $CAT[$f['relatedid']]['childids']) {
								// 将该字段同时归类至其子栏目
								$child = explode(',', $CAT[$f['relatedid']]['childids']);
								foreach ($child as $catid) {
									if ($CAT[$catid]) {
										$CAT[$catid]['field'][$f['fieldname']] = $this->get_field_value($f);
									}
								}
							}
						}
					}
					
					$cache['category'] = $CAT;
					$cache['category_dir'] = $CAT_DIR;
					$cache['category_level'] = $level ? max($level) : 0;
				} else {
					$cache['category'] = NULL;
					$cache['category_dir'] = NULL;
					$cache['category_level'] = 0;
				}
				
				// 模块名称
				$name = $this->db
							 ->select('name')
							 ->where('mark', 'module-'.$dirname)
							 ->where('pid', 0)
							 ->limit(1)
							 ->get('admin_menu')
							 ->row_array();
				$cache['name'] = $name['name'];
				$this->dcache->set('module-'.$siteid.'-'.$dirname, $cache);
			}
		}
		
		$this->load->library('dconfig');
		$this->dconfig
			 ->file(FCPATH.'config/domain.php')
			 ->note('站点域名文件')
			 ->space(32)
			 ->to_require_one($site_domain);
	}
}