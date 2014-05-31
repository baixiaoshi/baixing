<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/models/tag_model.php
 */
	
class Tag_model extends CI_Model {

	public $link;
	public $tablename;
	
	/*
	 * Tag模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->tablename = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_tag');
    }
	
	/*
	 * 数据分页显示
	 *
	 * @param	string	$kw		关键字参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($kw, $page, $total) {
	
		if (!$total) {
			$select	= $this->link->select('count(*) as total');
			if ($kw) $select->like('name', urldecode($kw));
			$data = $select->get($this->tablename)->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0, 'kw' => $kw));
		}
		
		$select	= $this->link->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		if ($kw) $select->like('name', urldecode($kw));
		$data = $select->order_by('hits DESC, id DESC')
					   ->get($this->tablename)
					   ->result_array();
					   
		return array($data, array('total' => $total, 'kw' => $kw));
	}
	
	/*
	 * 获取标签详细
	 *
	 * @param	string	$code
	 * @return	array
	 */
	public function tag($code) {
	
		if (!$code) return NULL;
		
		$this->link->where('code', $code)->set('hits','hits+1',FALSE)->update($this->tablename);
		
		return $this->link
					->where('code', $code)
					->get($this->tablename)
					->result_array();
	}
	
	/*
	 * 获取标签
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get($id) {
	
		if (!$id) return NULL;
		
		return $this->link
					->where('id', $id)
					->limit(1)
					->get($this->tablename)
					->row_array();
	}
	
	/*
	 * 添加tag
	 *
	 * @return	id
	 */
	public function add($data) {
	
		if (!$data) return -1;
		if ($this->link->where('name', $data['name'])->count_all_results($this->tablename)) return -2;
		
		$this->db->insert($this->tablename, array(
			'name' => $data['name'],
			'code' => $data['code'],
			'hits' => (int)$data['hits']
		));
		
		return $this->db->insert_id();
	}
	
	/*
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	intavl
	 */
	public function edit($id, $data) {
		
		if (!$data || !$id) return -1;
		if ($this->link->where('id<>', $id)->where('name', $data['name'])->count_all_results($this->tablename)) return -2;
		
		$this->db->where('id', $id)->update($this->tablename, array(
			'name' => $data['name'],
			'code' => $data['code'],
			'hits' => (int)$data['hits']
		));
		
		return $id;
	}
	
}