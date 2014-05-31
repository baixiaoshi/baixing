<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.11
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Navigator_model extends CI_Model {
	
	public $link;
	public $tablename;
	private	$categorys;
	
	/**
	 * 网站导航模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->tablename = $this->link->dbprefix(SITE_ID.'_navigator');
    }
	
	/**
	 * 单数据
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get($id) {
		return $this->link
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->tablename)
					 ->row_array();
	}
	
	/**
	 * 全部数据
	 *
	 * @return	array
	 */
	public function get_data($type) {
	
		$data = $this->link
					  ->where('type', $type)
					  ->order_by('displayorder ASC,id ASC')
					  ->get($this->tablename)
					  ->result_array();
		if (!$data) return array();
		
		$nav = array();
		foreach ($data as $t) {
			$nav[$t['id']] = $t;
		}
		
		return $nav;
	}
	
	/*
	 * 添加
	 *
	 * @param	array	$data
	 * @return	intval
	 */
	public function add($data) {
	
		if (!$data) return NULL;
		
		$this->link->insert($this->tablename, array(
			'pid' => $data['pid'],
			'url' => $data['url'],
			'type' => $data['type'],
			'name' => $data['name'],
			'show' => (int)$data['show'],
			'title' => $data['title'],
			'thumb' => $data['thumb'],
			'child' => 0,
			'target' => (int)$data['target'],
			'displayorder' => (int)$data['displayorder']
		));
		
		$id = $this->link->insert_id();
		$this->repair();
		
		return $id;
	}
	
	/*
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	string
	 */
	public function edit($id, $data) {
		
		$this->link->where('id', $id)->update($this->tablename, array(
			'pid' => $data['pid'],
			'url' => $data['url'],
			'name' => $data['name'],
			'show' => (int)$data['show'],
			'title' => $data['title'],
			'thumb' => $data['thumb'],
			'target' => (int)$data['target'],
			'displayorder' => (int)$data['displayorder']
		));
		
		$this->repair();
		
		return $id;
	}
	
	/**
     * 修复数据
	 */
	public function repair($site = SITE_ID) {
	
		$data = $this->link
					  ->get($site.'_navigator')
					  ->result_array();
		if (!$data) return array();
		
		$list = array();
		foreach ($data as $t) {
			$list[$t['id']] = $t;
		}
		
		foreach ($list as $t) {
			$child = 0;
			foreach ($list as $c) {
				if ($c['pid'] == $t['id']) {
					$child = 1; // 说明有子类
					break;
				}
			}
			// 当库中与实际不符合才更新数据表
			if ($child != $t['child']) {
				$this->link->where('id', $t['id'])->update($site.'_navigator', array('child' => $child));
			}
		}
	}
}