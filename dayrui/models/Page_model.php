<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.1.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Page_model extends CI_Model {
	
	public $link;
	public $tablename;
	private	$categorys;
	
	/*
	 * 单页模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->tablename = $this->link->dbprefix(SITE_ID.'_page');
    }
	
	/*
	 * 单页数据
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
	
	/*
	 * 全部数据
	 *
	 * @return	array
	 */
	public function get_data() {
	
		$data = array();
		$_data = $this->link
					  ->where('module', APP_DIR)
					  ->order_by('displayorder ASC,id ASC')
					  ->get($this->tablename)
					  ->result_array();
		if (!$_data) return $data;
		
		foreach ($_data as $t) {
			$data[$t['id']]	= $t;
		}
		
		return $data;
	}
	
	/*
	 * 更新url
	 *
	 * @param	intval	$id
	 * @return	void
	 */
	private function _update_url($id) {
	
		$data = $this->get($id);
		if ($data['urllink']) {
			$url = $data['urllink'];
		} elseif ($data['urlrule']) {
			$url = dr_page_url($data);
		} else {
			if ($data['module']) {
				$url = $this->ci->get_cache('MODULE-'.SITE_ID.'-'.$data['module'], 'url').'index.php?c=page&id='.$id;
			} else {
				$url = SITE_URL.'index.php?c=page&id='.$id;
			}
		}
		
		$this->link->where('id', $id)->update($this->tablename, array('url' => $url));
	}
	
	/*
	 * 添加
	 *
	 * @param	array	$data
	 * @return	intval
	 */
	public function add($data) {
	
		if (!$data) return NULL;
		if ($this->dirname_exitsts($data['dirname'])) return lang('111');
		
		$this->link->insert($this->tablename, array(
			'url' => '',
			'pid' => (int)$data['pid'],
			'pids' => '',
			'name' => $data['name'],
			'show' => (int)$data['show'],
			'child' => 0,
			'thumb' => $data['thumb'],
			'title' => $data['title'],
			'module' => APP_DIR,
			'seojoin' => '', // 已废弃
			'urlrule' => (int)$data['urlrule'],
			'urlpage' => '', // 已废弃
			'urllink' => $data['urllink'],
			'dirname' => $data['dirname'],
			'content' => $data['content'],
			'pdirname' => '',
			'childids' => '',
			'template' => $data['template'],
			'keywords' => $data['keywords'],
			'getchild' => (int)$data['getchild'],
			'attachment' => $data['attachment'],
			'description' => $data['description'],
			'displayorder' => (int)$data['displayorder']
		));
		
		$id = $this->link->insert_id();
		$this->repair();
		$this->_update_url($id);
		
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
	
		if (!$data || !$data['dirname']) return lang('019');
		if ($this->dirname_exitsts($data['dirname'], $id)) return lang('111');
		
		$this->link->where('id', $id)->update($this->tablename, array(
			'pid' => (int)$data['pid'],
			'name' => $data['name'],
			'show' => (int)$data['show'],
			'thumb' => $data['thumb'],
			'title' => $data['title'],
			'module' => APP_DIR,
			'dirname' => $data['dirname'],
			'content' => $data['content'],
			'urllink' => $data['urllink'],
			'urlrule' => (int)$data['urlrule'],
			'getchild' => (int)$data['getchild'],
			'template' => $data['template'],
			'keywords' => $data['keywords'],
			'attachment' => $data['attachment'],
			'description' => $data['description'],
			'displayorder' => (int)$data['displayorder']
		));
		
		$this->repair();
		$this->_update_url($id);
		
		return $id;
	}
	
	/**
	 * 同步配置
	 *
	 * @param	array	$syn
	 * @param	array	$data
	 * @return	NULL
	 */
	public function syn($syn, $urlrule) {
	
		if (!$urlrule || !$syn) return NULL;
		
		foreach ($syn as $id) {
			$_data = $this->get($id);
			$this->link
				 ->where('id', $id)
				 ->update($this->tablename, array(
					'urlrule' => (int)$urlrule
				 ));
			$this->_update_url($id);
		}
	}
	
	/**
	 * 目录是否存在
	 *
	 * @param	array	$data
	 * @return	bool
	 */
	private function dirname_exitsts($dir, $id = 0) {
	
		return $dir ? $this->link
						   ->where('dirname', $dir)
						   ->where('module', APP_DIR)
						   ->where('id<>', $id)
						   ->count_all_results($this->tablename) : 1;
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
	 * 获取父栏目路径
	 * 
	 * @param	$catid	栏目ID
	 * @return	string
	 */
	public function get_pdirname($catid) {
	
		if ($this->categorys[$catid]['pid']==0) return '';
		
		$t = $this->categorys[$catid];
		$pids = $t['pids'];
		$pids = explode(',', $pids);
		$catdirs = array();
		krsort($pids);
		
		foreach ($pids as $id) {
			if ($id == 0) continue;
			$catdirs[] = $this->categorys[$id]['dirname'];
			if ($this->categorys[$id]['pdirname'] == '') break;
		}
		krsort($catdirs);
		
		return implode('/', $catdirs).'/';
	}
	
	/**
     * 修复栏目数据
	 */
	public function repair() {
	
		$this->categorys = $categorys = array();
		$this->categorys = $categorys = $this->get_data(); // 全部栏目数据
		$this->get_categorys($categorys); // 查找子目录
		
		if (is_array($this->categorys)) {
			foreach ($this->categorys as $catid => $cat) {
				$pids = $this->get_pids($catid);
				$childids = $this->get_childids($catid);
				$child = is_numeric($childids) ? 0 : 1;
				$pdirname = $this->get_pdirname($catid);
				if ($categorys[$catid]['pdirname'] != $pdirname 
				|| $categorys[$catid]['pids'] != $pids 
				|| $categorys[$catid]['childids'] != $childids 
				|| $categorys[$catid]['child'] != $child) {
					// 当库中与实际不符合才更新数据表
					$this->link->where('id', $cat['id'])->update($this->tablename, array(
						'pids' => $pids,
						'child' => $child,
						'childids' => $childids,
						'pdirname' => $pdirname
					));
				}
			}
		}
	}
	
	/*
	 * 缓存
	 *
	 * @param	int		$id
	 * @return	NULL
	 */
	public function cache($siteid = SITE_ID) {
		
		$this->ci->clear_cache('page-'.$siteid);
		$this->dcache->delete('page-'.$siteid);
		
		$data = $this->link
					 ->order_by('displayorder ASC,id ASC')
					 ->get($this->tablename)
					 ->result_array();
		if (!$data) return NULL;
		
		$cache = array();
		foreach ($data as $t) {
			$attachment = dr_string2array($t['attachment']);
			$t['attachment'] = NULL;
			if ($attachment) {
				foreach ($attachment['file'] as $i => $file) {
					$t['attachment'][] = array(
						'file' => $file, // 对应文件或附件id
						'title' => $attachment['title'][$i] // 对应标题描述
					);
				}
			}
			
			if ($t['module']) {
				$cache['data'][$t['module']][$t['id']] = $t;
			} else {
				$cache['data']['index'][$t['id']] = $t;
			}
			$this->_update_url($t['id']);
			$cache['dir'][$t['dirname']] = $t['id'];
		}
		
		$this->dcache->set('page-'.$siteid, $cache);
	}
}