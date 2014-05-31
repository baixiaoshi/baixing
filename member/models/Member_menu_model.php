<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.3
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Member_menu_model extends CI_Model{
    
	/**
	 * 初始化
	 */
    public function __construct() {
        parent::__construct();
	}
	
	/**
	 * 添加菜单
	 *
	 * @param	array	$data	添加数据
	 * @return	void
	 */
	public function add($data) {
	
		if (!$data) return NULL;
		
		$insert	= array(
			'pid' => $data['pid'],
			'url' => $data['url'],
			'uri' => trim($data['dir'].'/'.$data['class'].'/'.$data['method'], '/'),
			'name' => $data['name'],
			'target' => (int)$data['target'],
			'displayorder' => 0,
		);
		$this->db->insert('member_menu', $insert);
		
		$this->cache();
		
		return TRUE;
	}
	
	/**
	 * 修改菜单
	 *
	 * @param	intval	$id		
	 * @param	array	$data	数据
	 * @return	void
	 */
	public function edit($id, $data) {
	
		if (!$data || !$id) return NULL;
		
		$this->db
			 ->where('id', $id)
			 ->update('member_menu', array(
				'pid' => $data['pid'],
				'url' => $data['url'],
				'uri' => trim($data['dir'].'/'.$data['class'].'/'.$data['method'], '/'),
				'name' => $data['name'],
				'target' => (int)$data['target'],
			)
		);
		
		$this->cache();
		
		return $id;
	}
	
	/**
	 * 顶级菜单id
	 *
	 * @return	array
	 */
	public function get_top_id() {
	
		$_data = $this->db
					  ->select('id')
					  ->where('pid=0')
					  ->order_by('id ASC')
					  ->get('member_menu')
					  ->result_array();
		if (!$_data) return NULL;
		
		$data = array();
		foreach ($_data as $t) {
			$data[] = $t['id'];
		}
		
		return $data;
	}
	
	/**
	 * 分组菜单id
	 *
	 * @return	array
	 */
	public function get_left_id() {
	
		$_data = $this->db
					  ->select('id')
					  ->where_in('pid', $this->get_top_id())
					  ->order_by('id ASC')
					  ->get('member_menu')
					  ->result_array();
		if (!$_data) return NULL;
		
		$data = array();
		foreach ($_data as $t) {
			$data[] = $t['id'];
		}
		
		return $data;
	}
	
	/**
	 * 父级菜单选择
	 *
	 * @param	intval	$level	级别
	 * @param	intval	$id		选中项id
	 * @param	intval	$name	select部分
	 * @return	string
	 */
	public function parent_select($level, $id = NULL, $name = NULL) {
	
		$select = $name ? $name : '<select name="data[pid]">';
		
		switch ($level) {
			case 0: // 顶级菜单
				$select.= '<option value="0">'.lang('016').'</option>';
				break;
			case 1: // 分组菜单
				$topdata = $this->db
								->select('id,name')
								->where('pid=0')
								->get('member_menu')
								->result_array();
				foreach ($topdata as $t) {
					$select.= '<option value="'.$t['id'].'"'.($id == $t['id'] ? ' selected' : '').'>'.$t['name'].'</option>';
				}
				break;
			case 2: // 链接菜单
				$topdata = $this->db
								->select('id,name')
								->where('pid=0')
								->get('member_menu')
								->result_array();
				foreach ($topdata as $t) {
					$select.= '<optgroup label="'.$t['name'].'">';
					$linkdata = $this->db
									 ->select('id,name')
									 ->where('pid='.$t['id'])
									 ->get('member_menu')
									 ->result_array();
					foreach ($linkdata as $c) {
						$select.= '<option value="'.$c['id'].'"'.($id == $c['id'] ? ' selected' : '').'>'.$c['name'].'</option>';
					}
					$select.= '</optgroup>';
				}
				break;
		}
		
		$select.= '</select>';
		
		return $select;
	}
	
	/**
	 * 更新缓存
	 *
	 * @return	array
	 */
	public function cache() {
	
		$data = $this->db
					 ->order_by('displayorder ASC,id ASC')
					 ->get('member_menu')
					 ->result_array();
		$cache = array();
		
		if ($data) {
			foreach ($data as $t) {
				if ($t['pid'] == 0) {
					$cache['data'][$t['id']] = $t;
					foreach ($data as $m) {
						if ($m['pid'] == $t['id']) {
							$cache['data'][$t['id']]['left'][$m['id']] = $m;
							foreach ($data as $n) {
								if ($n['pid'] == $m['id']) {
									$cache['data'][$t['id']]['left'][$m['id']]['link'][$n['id']] = $n;
									if ($n['uri']) {
										$n['tid'] = $t['id'];
										$cache['uri'][$n['uri']] = $n;
									}
								}
							}
						}
					}
				}
			}
			$this->dcache->set('member-menu', $cache);
		} else {
			$this->dcache->delete('member-menu');
		}
		
		$this->ci->clear_cache('member-menu');
		
		return $cache;
	}
	
	public function init() {
	
		// 清空菜单
		$this->db->query('TRUNCATE `'.$this->db->dbprefix('member_menu').'`');
		
		// 导入初始化菜单数据
		$this->ci->sql_query(str_replace(
			'{dbprefix}',
			$this->db->dbprefix,
			file_get_contents(FCPATH.'cache/install/member_menu.sql')
		));
		
		// 按模块安装菜单
		$module = $this->db->get('module')->result_array();
		if ($module) {
			foreach ($module as $m) {
				$id = $m['id'];
				$dir = $m['dirname'];
				// 菜单
				if (is_file(FCPATH.$dir.'/config/menu.php')) {
					$config = require FCPATH.$dir.'/config/module.php';
					$name = $config['name']; // 顶部菜单名称
					$menu = require FCPATH.$dir.'/config/menu.php';
					if ($menu['member']) {
						// 插入的顶级菜单
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
							if ($left['menu']) {
								$leftid = $this->db->insert_id();
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
				}
			}
		}
		// 按应用安装菜单
		$app = $this->db->get('application')->result_array();
		if ($app) {
			foreach ($app as $a) {
				$dir = $a['dirname'];
				if (is_file(FCPATH.'app/'.$dir.'/models/'.$dir.'_model.php')) {
					$this->load->add_package_path(FCPATH.'app/'.$dir.'/');
					$this->load->model($dir.'_model', 'app_model');
					$this->app_model->install_member_menu($dir, $a['id']);
					$this->load->remove_package_path(FCPATH.'app/'.$dir.'/');
				}
			}
		}
		// 安装空间模型
		$space = $this->db
					  ->get('space_model')
					  ->result_array();
		if ($space) {
			foreach ($space as $t) {
				$id = $t['id'];
				$uri = 'space'.$id.'/index';
				if (!$this->db->where('uri', $uri)->count_all_results('member_menu')) {
					$this->db->insert('member_menu', array(
						'pid' => 26,
						'uri' => $uri,
						'url' => '',
						'mark' => 'space-'.$id,
						'name' => $t['name'].'管理',
						'target' => 0,
						'displayorder' => $id + 5,
					));
				}
			}
		}
	}
}