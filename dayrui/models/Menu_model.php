<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Menu_model extends CI_Model{
    
	/**
	 * 菜单模型类
	 */
    public function __construct() {
        parent::__construct();
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
					  ->get('admin_menu')
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
					  ->get('admin_menu')
					  ->result_array();
		if (!$_data) return NULL;
		
		$data = array();
		foreach ($_data as $t) {
			$data[] = $t['id'];
		}
		
		return $data;
	}
	
	/**
	 * 添加菜单
	 *
	 * @param	array	$data	添加数据
	 * @return	void
	 */
	public function add($data) {
	
		if (!$data) return NULL;
		
		$uri = '/';
		$data['dir'] && $uri .= $data['dir'].'/';
		$data['directory'] && $uri .= $data['directory'].'/';
		$data['class'] && $uri .= $data['class'].'/';
		$data['method'] && $uri .= $data['method'].'/';
		$data['param'] && $uri .= $data['param'].'/';
		
		$insert	= array(
			'uri' => trim($uri, '/'),
			'url' => $data['url'],
			'pid' => $data['pid'],
			'name' => $data['name'],
			'displayorder' => 0,
		);
		
		$this->db->insert('admin_menu', $insert);
		$insert['id'] = $this->db->insert_id();
		$this->cache();
		
		return $insert;
	}
	
	/**
	 * 修改菜单
	 *
	 * @param	array	$_data	旧数据
	 * @param	array	$data	数据
	 * @return	void
	 */
	public function edit($_data, $data) {
	
		if (!$data || !$_data) return NULL;
		
		$uri = '/';
		$data['dir'] && $uri .= $data['dir'].'/';
		$data['directory'] && $uri .= $data['directory'].'/';
		$data['class'] && $uri .= $data['class'].'/';
		$data['method'] && $uri .= $data['method'].'/';
		$data['param'] && $uri .= $data['param'].'/';
		
		$this->db
			 ->where('id', $_data['id'])
			 ->update('admin_menu', array(
				'uri' => trim($uri, '/'),
				'url' => $data['url'],
				'pid' => $data['pid'],
				'name' => $data['name']
			)
		);
		
		$this->cache();
		
		return $_data['id'];
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
								->get('admin_menu')
								->result_array();
				foreach ($topdata as $t) {
					$select.= '<option value="'.$t['id'].'"'.($id == $t['id'] ? ' selected' : '').'>'.$t['name'].'</option>';
				}
				break;
			case 2: // 链接菜单
				$topdata = $this->db->select('id,name')->where('pid=0')->get('admin_menu')->result_array();
				foreach ($topdata as $t) {
					$select.= '<optgroup label="'.$t['name'].'">';
					$linkdata = $this->db
									 ->select('id,name')
									 ->where('pid='.$t['id'])
									 ->get('admin_menu')
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
					 ->get('admin_menu')
					 ->result_array();
		$list = array();
		if ($data) {
			foreach ($data as $t) {
				if ($t['pid'] == 0) {
					$list[$t['id']] = $t;
					foreach ($data as $m) {
						if ($m['pid'] == $t['id']) {
							$list[$t['id']]['left'][$m['id']] = $m;
							foreach ($data as $n) {
								if ($n['pid'] == $m['id']) $list[$t['id']]['left'][$m['id']]['link'][$n['id']] = $n;
							}
						}
					}
				}
			}
			$this->dcache->set('menu', $list);
		} else {
			$this->dcache->delete('menu');
		}
		$this->ci->clear_cache('menu');
		return $list;
	}
	
	/**
	 * 初始化菜单
	 *
	 * @return	array
	 */
	public function init() {
		// 清空菜单
		$this->db->query('TRUNCATE `'.$this->db->dbprefix('admin_menu').'`');
		// 导入初始化菜单数据
		$this->ci->sql_query(str_replace(
			'{dbprefix}',
			$this->db->dbprefix,
			file_get_contents(FCPATH.'cache/install/admin_menu.sql')
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
					$this->app_model->install_admin_menu($dir, $a['id']);
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
				$uri = 'member/content/index/mid/'.$id;
				if (!$this->db->where('mark', 'space-'.$id)->count_all_results('admin_menu')) {
					$this->db->insert('admin_menu', array(
						'pid' => 80,
						'uri' => $uri,
						'url' => '',
						'mark' => 'space-'.$id,
						'name' => $t['name'].'管理',
						'displayorder' => $id + 5,
					));
				}
			}
		}
	}
}