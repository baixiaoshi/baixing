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
	
class Group_model extends CI_Model{
    
	/**
	 * 初始化
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 所有数据
	 *
	 * @return	void
	 */
	public function get_data() {
		$_data = $this->db
					  ->order_by('displayorder ASC')
					  ->get('member_group')
					  ->result_array();
		if (!$_data) return NULL;
		$data = array();
		foreach ($_data as $t) {
			$t['level'] = $this->db->where('groupid', $t['id'])->count_all_results('member_level');
			$t['allowfield'] = dr_string2array($t['allowfield']);
			$data[] = $t;
		}
		return $data;
	}
	
	/**
	 * 数据
	 *
	 * @param	int		$id
	 * @return	array
	 */
	public function get($id) {
		$_data = $this->db
					  ->limit(1)
					  ->where('id', (int)$id)
					  ->get('member_group')
					  ->row_array();
		if (!$_data) return NULL;
		$_data['allowfield'] = dr_string2array($_data['allowfield']);
		return $_data;
	}
	
	/**
	 * 添加
	 *
	 * @param	array	$data
	 * @return	int		存储表id
	 */
	public function add($data) {
		if (!$data) return NULL;
		$this->db->insert('member_group', array(
			'name' => $data['name'],
			'theme' => $data['theme'],
			'template' => $data['template'],
			'price' => $data['price'],
			'unit' => (int)$data['unit'],
			'limit' => (int)$data['limit'],
			'overdue' => (int)$data['overdue'],
			'allowfield' => dr_array2string($data['allowfield']),
			'allowapply' => (int)$data['allowapply'],
			'allowspace' => (int)$data['allowspace'],
			'displayorder' => (int)$data['displayorder']
		));
		return $this->db->insert_id();
    }
	
	/**
	 * 添加
	 *
	 * @param	array	$data
	 * @return	int		存储表id
	 */
	public function edit($_data, $data) {
		if (!$data || !$_data['id']) return NULL;
		$this->db->where('id', $_data['id'])->update('member_group', array(
			'name' => $data['name'],
			'theme' => $data['theme'],
			'template' => $data['template'],
			'price' => $data['price'],
			'unit' => (int)$data['unit'],
			'limit' => (int)$data['limit'],
			'overdue' => (int)$data['overdue'],
			'allowfield' => dr_array2string($data['allowfield']),
			'allowapply' => (int)$data['allowapply'],
			'allowspace' => (int)$data['allowspace'],
			'displayorder' => (int)$data['displayorder']
		));
    }
	
	/**
	 * 删除
	 *
	 * @param	array	$id
	 */
	public function del($id) {
		if (!$id) return NULL;
		if (!is_array($id)) $id = array($id);
		foreach ($id as $i => $ii) {
			if ($ii <= 3) unset($id[$i]);
		}
		if (!$id) return NULL;
		$this->db->where_in('id', $id)->delete('member_group');
		$this->db->where_in('groupid', $id)->update('member', array('groupid' => 3));
    }
}