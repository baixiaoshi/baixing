<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Form_model extends CI_Model {

	public $link;
	public $prefix;
	
	/**
	 * 内容扩展模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->prefix = $this->db->dbprefix(SITE_ID.'_form');
    }
	
	/**
	 * 添加扩展模型
	 * 
	 * @param	array	$data
	 * @return	string|TRUE
	 */
	public function add($data) {
	
		if (!$data['name'] || !$data['table']) return lang('238');
		
		if (!preg_match('/^[a-z]+[a-z0-9_\-]+$/i', $data['table'])
		|| $this->link
				->where('table', $data['table'])
				->count_all_results($this->prefix)) return lang('239');
		
		$data['setting'] = dr_array2string($data['setting']);
		
		if ($this->link->insert($this->prefix, $data)) {
			
			$id = $this->link->insert_id();
			
			$name = 'Form_'.SITE_ID.'_'.$id;
			$file = FCPATH.'dayrui/controllers/admin/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function add() {'.PHP_EOL
			.'		$this->_addc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function edit() {'.PHP_EOL
			.'		$this->_editc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function index() {'.PHP_EOL
			.'		$this->_listc();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'}')) {
				$this->db->where('id', $id)->delete($this->prefix);
				return dr_lang('243', '/dayrui/controllers/admin/');
			}
			
			$file = FCPATH.'dayrui/controllers/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'	public function index() {'.PHP_EOL
			.'		$this->_post();'.PHP_EOL
			.'	}'.PHP_EOL.PHP_EOL
			.'}')) {
				$this->db->where('id', $id)->delete($this->prefix);
				return dr_lang('243', '/dayrui/controllers/');
			}
			
			$sql = "
			CREATE TABLE IF NOT EXISTS `".$this->prefix.'_'.$data['table']."` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL COMMENT '主题',
			  `inputip` varchar(30) DEFAULT NULL COMMENT '录入者ip',
			  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
			  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
			  PRIMARY KEY `id` (`id`),
			  KEY `inputtime` (`inputtime`),
			  KEY `displayorder` (`displayorder`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".$data['name']."表单表';";
			$this->link->query($sql);
			
			$this->db->insert('field', array(
				'name' => '主题',
				'fieldname' => 'title',
				'fieldtype' => 'Text',
				'relatedid' => $id,
				'relatedname' => 'form-'.SITE_ID,
				'isedit' => 1,
				'ismain' => 1,
				'ismember' => 1,
				'issystem' => 1,
				'issearch' => 1,
				'disabled' => 0,
				'setting' => dr_array2string(array(
					'option' => array(
						'width' => 400, // 表单宽度
						'fieldtype' => 'VARCHAR', // 字段类型
						'fieldlength' => '255' // 字段长度
					),
					'validate' => array(
						'xss' => 1, // xss过滤
						'required' => 1, // 表示必填
					)
				)),
				'displayorder' => 0,
			));
		}
		
		return TRUE;
	}
	
	/**
	 * 修改模型
	 * 
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	void
	 */
	public function edit($id, $data) {
		$this->db->where('id', (int)$id)->update($this->prefix, array(
			'name' => $data['name'],
			'setting' => dr_array2string($data['setting']),
		));
	}
	
	/**
	 * 删除
	 * 
	 * @param	intval	$id
	 * @param	intval	$sid
	 */
	public function del($id, $sid = SITE_ID) {
		
		if (!$id) return NULL;
		
		$sid = $sid ? $sid : SITE_ID;
		$prefix = $this->db->dbprefix($sid.'_form');
		
		// 数据查询
		$data = $this->site[$sid]
					 ->where('id', (int)$id)
					 ->select('table')
					 ->limit(1)
					 ->get($prefix)
					 ->row_array();
		if (!$data) return NULL;
		
		// 删除字段
		$this->db
			 ->where('relatedid', (int)$id)
			 ->where('relatedname', 'form-'.$sid)
			 ->delete($this->db->dbprefix('field'));
		
		$table = $prefix.'_'.$data['table'];
		
		$this->db->query('DROP TABLE IF EXISTS `'.$table.'`');
		
		$this->db
			 ->where('id', (int)$id)
			 ->delete($prefix);
			 
		$this->load->model('attachment_model');
		$this->attachment_model->delete_for_table($table, TRUE);
		
		
		$name = 'Form_'.$sid.'_'.$id;
		@unlink(FCPATH.'dayrui/controllers/'.$name.'.php');
		@unlink(FCPATH.'dayrui/controllers/admin/'.$name.'.php');
		@unlink(FCPATH.'dayrui/templates/admin/form_'.$id.'.html');
		
		return NULL;
	}
	
	/**
	 * 生成缓存
	 * 
	 * @return	void
	 */
	public function cache($siteid = SITE_ID) {
		
		$siteid = $siteid ? $siteid : SITE_ID;
		$this->dcache->delete('form-'.$siteid);
		
		$form = $this->db
					 ->get($this->prefix)
					 ->result_array();
		if (!$form) return NULL;
		
		$cache = array();
		
		foreach ($form as $t) {
			
			$data = $this->db
						 ->where('relatedid', (int)$t['id'])
						 ->where('relatedname', 'form-'.$siteid)
						 ->order_by('displayorder ASC,id ASC')
						 ->get('field')
						 ->result_array();
			if ($data) {
				foreach ($data as $field) {
					$field['setting'] = dr_string2array($field['setting']);
					$t['field'][$field['fieldname']] = $field;
				}
			}
			
			$t['setting'] = dr_string2array($t['setting']);
			$cache[$t['id']] = $t;
		}
		
		$this->dcache->set('form-'.$siteid, $cache);
	}
	
	
	/**
	 * 数据分页显示
	 *
	 * @param	string	$table	表名称
	 * @param	array	$param	参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($table, $param, $page, $total) {
	
		if (!$total || $param['search']) {
		
			$select	= $this->link->select('count(*) as total');
			
			if ($param['keyword']) $this->link->like('title', $param['keyword']);
			
			if (isset($param['start']) && $param['start'] && $param['start'] != $param['end']) {
				$select->where('inputtime BETWEEN '.$param['start'].' AND '. $param['end']);
			}
			
			$data = $select->get($this->prefix.'_'.$table)->row_array();
			unset($select);
			$total = (int)$data['total'];
			unset($param['search']);
			
			if (!$total) return array(array(), 0);
		}
		
		$select	= $this->link->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		if ($param['keyword']) $this->link->like('title', $param['keyword']);
		if (isset($param['start']) && $param['start'] && $param['start'] != $param['end']) {
			$select->where('inputtime BETWEEN '.$param['start'].' AND '. $param['end']);
		}
		
		$data = $select->order_by('displayorder DESC,inputtime DESC')
					   ->get($this->prefix.'_'.$table)
					   ->result_array();
					   
		return array($data, $total);
	}
	
	/**
	 * 添加内容
	 *
	 * @return	id
	 */
	public function addc($table, $data) {
	
		if (!$data || !$table) return NULL;
		
		$this->link->insert($this->prefix.'_'.$table, $data);
		
		return $this->link->insert_id();
	}
	
	/**
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	intavl
	 */
	public function editc($id, $table, $data) {
		
		if (!$data || !$table || !$id) return NULL;
		
		$this->link
			 ->where('id', (int)$id)
			 ->update($this->prefix.'_'.$table, $data);
		
		return $id;
	}
}