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
	
class Space_category_model extends CI_Model {
	
	public $tablename;
	private	$categorys;
	
	/**
	 * 栏目模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->tablename = $this->db->dbprefix('space_category');
    }
	
	/**
	 * 删除栏目数据
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function del($ids) {
		
		if (!$ids) return NULL;
		
		$this->load->model('attachment_model');
		
		foreach ($ids as $id) {
			$data = $this->get($id);
			$catid = explode(',', $data['childids']);
			if ($catid) {
				foreach ($catid as $_id) {
					$_data = $this->get($_id);
					if ($_data) {
						$model = $this->ci->get_cache('space-model', $_data['modelid'], 'table');
						if ($model) {
							$table = $this->db->dbprefix('space_'.$model.'_'.(int)substr((string)$this->uid, -1, 1));
							
							$content = $this->db
											->where('catid', (int)$_data['catid'])
											->get($table)
											->result_array();
							if ($content) {
								foreach ($content as $c) {
									if ($c['id']) $this->attachment_model->delete_for_table($table.'-'.$c['id']); // 删除附件
								}
							}
							// 删除内容
							$this->db->where('catid', (int)$_data['catid'])->delete($table);
						}
						// 删除栏目
						$this->db->where('id', (int)$_id)->delete($this->tablename);
					}
				}
			}
		}
		
	}
	
	/**
	 * 栏目数据
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get($id) {
	
		$data = $this->db
					 ->where('id', $id)
					 ->where('uid', (int)$this->uid)
					 ->limit(1)
					 ->get($this->tablename)
					 ->row_array();
		
		return $data;
	}
	
	/**
	 * 栏目全部数据
	 *
	 * @param	intval	$mid
	 * @param	intval	$uid
	 * @param	intval	$all
	 * @return	array
	 */
	public function get_data($mid = 0, $uid = 0, $all = 0) {
		
		$uid = $uid ? $uid : $this->uid;
		
		if ($mid) $this->db->where('modelid', (int)$mid);
		$_data = $this->db
					  ->where('uid', (int)$uid)
					  ->order_by('displayorder ASC,id ASC')
					  ->get($this->tablename)
					  ->result_array();
		if (!$_data) return $data;
		
		$data = array();
		foreach ($_data as $t) {
			if ($all || (!$all && $t['type'])) {
				$data[$t['id']] = $t;
			}
		}
		
		return $data;
	}

	/**
	 * 添加
	 *
	 * @param	array	$data
	 * @return	intval
	 */
	public function add($data) {
	
		if (!$data['name']) return lang('m-292');
		
		switch ((int)$data['type']) {
			
			case 0:
				if (!$data['link']) return lang('m-312');
				$data['modelid'] = 0;
				break;
				
			case 1:
				if (!$data['modelid']) return lang('m-308');
				break;
			
			case 2:
				$data['modelid'] = 0;
				break;
		}
		
		$this->db->insert($this->tablename, array(
			'uid' => $this->uid,
			'pid' => (int)$data['pid'],
			'type' => (int)$data['type'],
			'name' => trim($data['name']),
			'body' => trim($data['body']),
			'link' => trim($data['link']),
			'title' => trim($data['title']),
			'showid' => (int)$data['showid'],
			'modelid' => (int)$data['modelid'],
			'keywords' => trim($data['keywords']),
			'description' => trim($data['description']),
			'displayorder' => (int)$data['displayorder']
		));
		
		$this->repair($this->uid);
		
		return TRUE;
	}
	
	/**
	 * 修改
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	string
	 */
	public function edit($id, $data) {
	
		if (!$data['name']) return lang('m-292');
		
		switch ((int)$data['type']) {
			case 0:
				if (!$data['link']) return lang('m-312');
				break;
			case 1:
				if (!$data['modelid']) return lang('m-308');
				break;
		}
		
		$this->db->where('id', (int)$id)->where('uid', (int)$this->uid)->update($this->tablename, array(
			'pid' => (int)$data['pid'],
			'name' => trim($data['name']),
			'body' => trim($data['body']),
			'link' => trim($data['link']),
			'title' => trim($data['title']),
			'showid' => (int)$data['showid'],
			'keywords' => trim($data['keywords']),
			'description' => trim($data['description']),
			'displayorder' => (int)$data['displayorder']
		));
		
		$this->repair($this->uid);
		
		return TRUE;
	}

	
	/**
	 * 找出子目录列表
	 *
	 * @param	array	$data
	 * @return	bool
	 */
	private function get_categorys($data = array()) {
	
		if (is_array($data) && !empty($data)) {
			foreach ($data as $catid => $c) {
				$this->categorys[$catid] = $c;
				$result = array();
				foreach ($this->categorys as $_k => $_v) {
					if ($_v['pid']) $result[] = $_v;
				}
			}
		} 
		
		return true;
	}
	
	
	/**
	 * 获取父栏目ID列表
	 * 
	 * @param	integer	$catid	栏目ID
	 * @param	array	$pids	父目录ID
	 * @param	integer	$n		查找的层次
	 * @return	string
	 */
	private function get_pids($catid, $pids = '', $n = 1) {
	
		if ($n > 5 || !is_array($this->categorys) || !isset($this->categorys[$catid])) return FALSE;
		
		$pid = $this->categorys[$catid]['pid'];
		$pids = $pids ? $pid.','.$pids : $pid;
		
		if ($pid) {
			$pids = $this->get_pids($pid, $pids, ++$n);
		} else {
			$this->categorys[$catid]['pids'] = $pids;
		}
		
		return $pids;
	}
	
	/**
	 * 获取子栏目ID列表
	 * 
	 * @param	$catid	栏目ID
	 * @return	string
	 */
	private function get_childids($catid) {
	
		$childids = $catid;
		
		if (is_array($this->categorys)) {
			foreach ($this->categorys as $id => $cat) {
				if ($cat['pid'] && $id != $catid && $cat['pid'] == $catid) {
					$childids .= ','.$this->get_childids($id);
				}
			}
		}
		
		return $childids;
	}

	/**
     * 修复栏目数据
	 */
	public function repair($uid) {
	
		$this->categorys = $categorys = array();
		$this->categorys = $categorys = $this->get_data(0, $uid, 1); // 全部栏目数据
		$this->get_categorys($categorys); // 查找子目录
		
		if (is_array($this->categorys)) {
		
			foreach ($this->categorys as $catid => $cat) {
				$pids = $this->get_pids($catid);
				$childids = $this->get_childids($catid);
				$child = is_numeric($childids) ? 0 : 1;
				if ($categorys[$catid]['pids'] != $pids 
				|| $categorys[$catid]['childids'] != $childids 
				|| $categorys[$catid]['child'] != $child) {
				
					// 当库中与实际不符合才更新数据表
					$this->db->where('id', $cat['id'])->update($this->tablename, array(
						'pids' => $pids,
						'child' => $child,
						'childids' => $childids
					));
				}
			}
		}
	}
}