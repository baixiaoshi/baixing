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
	
class Application_model extends CI_Model {
	
	/**
	 * 应用模型类
	 */
    public function __construct() {
        parent::__construct();
	}
	
	/**
	 * 所有应用
	 *
	 * @return	array
	 */
	public function get_data() {
	
		$data = $this->db
					 ->order_by('id ASC')
					 ->get($this->db->dbprefix('application'))
					 ->result_array();
		if (!$data) return NULL;
		
		$app = array();
		foreach ($data as $t) {
			$t['module'] = dr_string2array($t['module']);
			$t['setting'] = dr_string2array($t['setting']);
			$app[$t['dirname']] = $t;
		}
		
		return $app;
	}
	
	/**
	 * 应用数据
	 *
	 * @param	string	$dir
	 * @return	array
	 */
	public function get($dir) {
	
		$data = $this->db
					 ->limit(1)
					 ->where('dirname', $dir)
					 ->get($this->db->dbprefix('application'))
					 ->row_array();
		if (!$data) return NULL;
		
		$data['module'] = dr_string2array($data['module']);
		$data['setting'] = dr_string2array($data['setting']);
		
		return $data;
	}
	
	/**
	 * 应用入库
	 *
	 * @param	string	$dir
	 * @return	intval
	 */
	public function add($dir) {
	
		if (!$dir) return NULL;
		
		$this->db->insert($this->db->dbprefix('application'), array(
			'module' => '',
			'dirname' => $dir,
			'setting' => '',
			'disabled' => 0,
		));
		$id = $this->db->insert_id();
		if (!$id) return NULL;
		
		return $id;
	}
	
	/**
	 * 修改应用配置
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	bool
	 */
	public function edit($id, $data) {
	
		if (!$id) return FALSE;
		
		$this->db
			 ->where('id', (int)$id)
			 ->update($this->db->dbprefix('application'), $data);
		
		return TRUE;
	}
	
	/**
	 * 删除应用
	 *
	 * @param	intval	$id
	 * @return	bool
	 */
	public function del($id) {
	
		if (!$id) return FALSE;
		
		$this->db
			 ->where('id', (int)$id)
			 ->delete($this->db->dbprefix('application'));
		$this->cache();
			 
		return TRUE;
	}
	
	/**
	 * 应用缓存
	 */
	public function cache() {
		
		$this->dcache->delete('app');
		
		$data = $this->db
					 ->order_by('id ASC')
					 ->select('dirname')
					 ->where('disabled', 0)
					 ->get($this->db->dbprefix('application'))
					 ->result_array();
		if (!$data) return NULL;
		
		$cache = array();
		foreach ($data as $t) {
			$cache[] = $t['dirname'];
		}
		
		$this->dcache->set('app', $cache);
		
	}
}