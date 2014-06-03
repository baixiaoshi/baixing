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
	
class Linkage_model extends CI_Model {

	private	$categorys;
	
	/*
	 * 联动菜单模型类
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 联动菜单数据
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get($id) {
		return $this->db
					->where('id', $id)
					->limit(1)
					->get('linkage')
					->row_array();
	}
	
	/**
	 * 联动子菜单数据
	 *
	 * @param	intval	$id
	 * @param	intval	$key	顶级菜单id
	 * @return	array
	 */
	public function gets($id, $key) {
		return $this->db
					->where('id', $id)
					->limit(1)
					->get('linkage_data_'.$key)
					->row_array();
	}
	
	/**
	 * 全部名称数据
	 *
	 * @return	array
	 */
	public function get_data() {
		return $this->db
					->order_by('id ASC')
					->get('linkage')
					->result_array();
	}
	
	/**
	 * 全部子菜单数据
	 *
	 * @param	array	$link
	 * @param	intval	$pid
	 * @return	array
	 */
	public function get_list_data($link, $pid = NULL) {
		$key = (int)$link['id'];
		$data = array();
		if ($link['type'] == 1) $this->db->where('site', SITE_ID);
		if ($pid === NULL) {
			$_data = $this->db
						  ->order_by('displayorder ASC,id ASC')
						  ->get('linkage_data_'.$key)
						  ->result_array();
		} else {
			$_data = $this->db
						  ->where('pid', (int)$pid)
						  ->order_by('displayorder ASC,id ASC')
						  ->get('linkage_data_'.$key)
						  ->result_array();
		}
		if (!$_data) return $data;
		foreach ($_data as $t) {
			$data[$t['id']]	= $t;
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
		if (!$data || !$data['name']) return array('error' => lang('186'), 'name' => 'name');
		if ($this->code_exitsts($data['code'])) return array('error' => lang('187'), 'name' => 'code');
		$this->db->insert('linkage', array(
			'name' => $data['name'],
			'code' => $data['code'],
			'type' => $data['type'],
		));
		$id = $this->db->insert_id();
		$table = $this->db->dbprefix('linkage_data_'.$id);
		$this->db->query('DROP TABLE IF EXISTS `'.$table.'`');
		$this->db->query(trim("CREATE TABLE IF NOT EXISTS `{$table}` (
		  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		  `site` smallint(5) unsigned NOT NULL,
		  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
		  `pids` varchar(255) DEFAULT NULL COMMENT '所有上级id',
		  `name` varchar(30) NOT NULL COMMENT '栏目名称',
		  `child` tinyint(1) unsigned DEFAULT NULL DEFAULT '0' COMMENT '是否有下级',
		  `childids` text DEFAULT NULL COMMENT '下级所有id',
		  `displayorder` tinyint(3) DEFAULT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		  KEY `list` (`site`,`displayorder`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动菜单数据表'"));
		return NULL;
	}
	
	/**
	 * 修改
	 *
	 * @param	array	$_data
	 * @param	array	$data
	 * @return	string
	 */
	public function edit($id, $data) {
		if (!$id) return array('error' => lang('019'), 'name' => 'name');
		if (!$data || !$data['name']) return array('error' => lang('186'), 'name' => 'name');
		if ($this->code_exitsts($data['code'], $id)) return array('error' => lang('187'), 'name' => 'code');
		$this->db->where('id', $id)->update('linkage', array(
			'name' => $data['name'],
			'code' => $data['code'],
			'type' => $data['type'],
		));
		return NULL;
	}
	
	/**
	 * 标示是否存在
	 *
	 * @param	array	$data
	 * @return	bool
	 */
	private function code_exitsts($code, $id = 0) {
		return $code ? $this->db
						    ->where('code', $code)
						    ->where('id<>', $id)
						    ->count_all_results('linkage') : 1;
	}
	
	/**
	 * 批量添加
	 *
	 * @param	array	$key
	 * @param	array	$data
	 * @return	
	 */
	public function adds($key, $data) {
	
		if (!$key) return array('error' => lang('187'), 'name' => 'name');
		if (!$data) return array('error' => lang('186'), 'name' => 'name');
		
		$names = explode(PHP_EOL, $data['name']);
		if (!$names) return array('error' => lang('186'), 'name' => 'name');
		
		foreach ($names as $name) {
			$name = trim($name);
			if (!$name) continue;
			$this->db->insert('linkage_data_'.$key, array(
				'pid' => (int)$data['pid'],
				'name' => $name,
				'site' => SITE_ID,
				'displayorder' => (int)$data['displayorder']
			));
		}
		$this->repair($key);
		
		return NULL;
	}
	
	/**
	 * 修改
	 *
	 * @param	array	$data
	 * @param	array	$_data
	 * @return	
	 */
	public function edits($key, $id, $data) {
		if (!$data || !$data['name']) return array('error' => lang('186'), 'name' => 'name');
		$this->db->where('id', (int)$id)->update('linkage_data_'.$key, array(
			'pid' => (int)$data['pid'],
			'name' => $data['name'],
			'displayorder' => (int)$data['displayorder']
		));
		$this->repair($key);
		return NULL;
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
     * 修复菜单数据
	 */
	public function repair($key) {
		if (!$key) return NULL;
		$table = 'linkage_data_'.$key;
		$this->categorys = $categorys = array();
		$_data = $this->db
					  ->order_by('displayorder ASC,id ASC')
					  ->select('id,pid,pids,child,childids')
					  ->get($table)
					  ->result_array();
		if (!$_data) return NULL;
		foreach ($_data as $t) {
			$categorys[$t['id']] = $t;
		}
		$this->categorys = $categorys; // 全部栏目数据
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
					$this->db->where('id', $cat['id'])->update($table, array(
						'pids' => $pids,
						'child' => $child,
						'childids' => $childids
					));
				}
			}
		}
	}
	
	/**
     * 缓存
	 */
	public function cache($siteid = SITE_ID) {
		$linkage = $this->get_data(); // 所有可用
		if (!$linkage) return NULL;
		$level = array();
		foreach ($linkage as $link) {
			$this->repair($link['id']);
			$data = $lv = array();
			$table = 'linkage_data_'.$link['id'];
			if ($link['type']) {
				// 站点独立
				$list = $this->db
							 ->where('site', $siteid)
							 ->order_by('displayorder ASC,id ASC')
							 ->get($table)
							 ->result_array();
			} else {
				// 全局共享
				$list = $this->db
							 ->order_by('displayorder ASC,id ASC')
							 ->get($table)
							 ->result_array();
			}
			if ($list) {
				foreach ($list as $t) {
					$lv[] = substr_count($t['pids'], ',');
					$data[$t['id']]	= $t;
				}
			}
			$level[$link['code']] = $lv ? max($lv) : 0;
			$this->dcache->set('linkage-'.$siteid.'-'.$link['code'], $data);
		}
		$this->dcache->set('linklevel-'.$siteid, $level);
		return $cache;
	}
}