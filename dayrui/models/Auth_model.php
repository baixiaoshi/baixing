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
	
class Auth_model extends CI_Model{
    
	/**
	 * 认证控制模型类
	 */
    public function __construct() {
        parent::__construct();
    }
    
    /**
	 * 审核流程
	 *
     * @param   intval
	 * @return	array
	 */
	public function get_verify($id) {
	
		$data = $this->db
                     ->where('id', $id)
                     ->limit(1)
                     ->get('admin_verify')
                     ->row_array();
        if (!$data) return NULL;
		
        $data['verify'] = dr_string2array($data['verify']);
		
        return $data;
	}
	
    /**
	 * 审核流程
	 *
	 * @return	array
	 */
	public function get_verify_all() {
	
		$data = $this->db
                     ->order_by('id ASC')
                     ->get('admin_verify')
                     ->result_array();
        if (!$data) return NULL;
		
        foreach ($data as $i => $t) {
            $t['verify'] = dr_string2array($t['verify']);
            $t['num'] = count($t['verify']);
            $data[$i] = $t;
        }
		
        return $data;
	}
    
	/**
	 * 管理员角色组
	 *
	 * @return	array	所有角色
	 */
	public function get_admin_role_all() {
	
		return $this->db
					->order_by('id ASC')
					->get('admin_role')
					->result_array();
	}
	
	/**
	 * 添加角色组
	 *
	 * @param	array	$data	添加数据
	 * @return	int		$id		角色id
	 */
	public function add_role($data) {
	
		if (!$data) return NULL;
		
		$this->db->insert('admin_role', array(
			'name' => $data['name'],
			'site' => dr_array2string($data['site']),
			'system' => '',
			'module' => '',
			'application' => '',
		));
		
		return $this->db->insert_id();
	}
	
	/**
	 * 修改角色组
	 *
	 * @param	array	$_data	老数据
	 * @param	array	$data	修改数据
	 * @return	int		$id		角色id
	 */
	public function edit_role($_data, $data) {
	
		if (!$data || !$_data) return NULL;
		
		$this->db
			 ->where('id', $_data['id'])
			 ->update('admin_role', array(
				'site' => dr_array2string($data['site']),
				'name' => $data['name'],
			 )
		);
		
		return $_data['id'];
	}
	
	/**
	 * 更新权限
	 *
	 * @param	intval	$id		主键id
	 * @param	string	$name	权限名称
	 * @param	array	$data	权限数据
	 * @return	void
	 */
	public function update_auth($id, $name, $data) {
	
		if (!$id || !$name) return NULL;
		
		$this->db
			 ->where('id', $id)
			 ->update('admin_role', array($name => dr_array2string($data)));
		$this->role_cache();
	}
	
	/**
	 * 角色组数据
	 *
	 * @param	int		$id		主键id
	 * @return	array
	 */
	public function get_role($id) {
	
		if (!$id) return NULL;
		
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('admin_role')
					 ->row_array();
		if (!$data) return NULL;
		
		$data['site'] = dr_string2array($data['site']);
		$data['system'] = dr_string2array($data['system']);
		$data['module'] = dr_string2array($data['module']);
		$data['application'] = dr_string2array($data['application']);
		
		return $data;
	}
	
	/**
	 * 批量删除角色组
	 *
	 * @param	array	$ids	主键id
	 * @return	NULL
	 */
	public function del_role_all($ids) {
	
		if (!$ids) return NULL;
		
		$this->db
			 ->where_in('id', $ids)
			 ->where('id<>1')
			 ->delete('admin_role');
			 
		return NULL;
	}
	
	/**
	 * 删除角色组
	 *
	 * @param	int	$id	主键id
	 * @return	NULL
	 */
	public function del_role($id) {
	
		if (!$id || $id == 1) return NULL;
		
		$this->db
			 ->where('id', $id)
			 ->delete('admin_role');
			 
		return NULL;
	}
	
	/**
	 * 获取权限选项部分
	 *
	 * @param	array	$config		配置数据
	 * @param	string	$app_dir	应用/模块目录
	 * @return	array
	 */
	private function get_auth_uri($config, $app_dir = NULL) {
	
		if (!$config || !$config['auth']) return NULL;
		
		$data = array();
		foreach ($config['auth'] as $x) {
			if (!$x['auth']) continue;
			foreach ($x['auth'] as $uri => $xx) {
				$data[] = ($app_dir ? $app_dir.'/' : '').trim($uri, '/');
			}
		}
		
		return $data;
	}
	
	/**
	 * 获取所有权限选项
	 *
	 * @return	array
	 */
	public function get_auth_all() {
	
		// 系统权限
		require FCPATH.'config/auth.php';
		$data = $this->get_auth_uri($config);
		unset($config);
		
		// 模块权限
		$module = $this->ci->get_module(SITE_ID);
		if ($module) {
			foreach ($module as $t) {
				if (is_file(FCPATH.$t['dirname'].'/config/auth.php')) {
					unset($config);
					require FCPATH.$t['dirname'].'/config/auth.php';
					$data = array_merge($data, $this->get_auth_uri($config, $t['dirname']));
				}
			}
		}
		
		return $data;
	}
	
	/**
	 * 更新角色缓存
	 *
	 * @return	void
	 */
	public function role_cache() {
	
		$data = $this->get_admin_role_all();
		if (!$data) return NULL;
		
		$this->dcache->delete('role');
		$this->ci->clear_cache('role');
		
		$cahce = array();
		foreach ($data as $t) {
			$t['site'] = dr_string2array($t['site']);
			$t['system'] = dr_string2array($t['system']);
			$t['module'] = dr_string2array($t['module']);
			$t['application'] = dr_string2array($t['application']);
			$cache[$t['id']] = $t;
		}
		
		$this->dcache->set('role', $cache);
		
		return $cache;
	}
}