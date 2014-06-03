<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/controllers/admin/role.php
 */
	
class Role extends M_Controller {
	
	private $_menu;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->_menu = array(
		    lang('026') => 'admin/role/index',
		    lang('add') => 'admin/role/add_js',
		    lang('001') => 'admin/role/cache'
		);
		$this->template->assign('menu', $this->get_menu($this->_menu));
    }
	
	/**
     * 权限组管理
     */
    public function index() {
	
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			exit(dr_json(1, lang('014'), $this->auth_model->del_role_all($ids)));
		}
		
		$this->template->assign('list', $this->auth_model->get_admin_role_all());
		$this->template->display('role_index.html');
    }
	
	/**
     * 添加组
     */
    public function add() {
		if (IS_POST) exit(dr_json(1, lang('014'), $this->auth_model->add_role($this->input->post('data', TRUE))));
		$this->template->display('role_add.html');
    }

	/**
     * 修改组
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->db->dbprefix('admin_role'))
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		if (IS_POST) exit(dr_json(1, lang('014'), $this->auth_model->edit_role($data, $this->input->post('data', TRUE))));
		$data['site'] = dr_string2array($data['site']);
		
		$this->template->assign('data', $data);
        $this->template->display('role_add.html');
    }
	
	/**
     * 删除组
     */
    public function del() {
		$this->auth_model->del_role((int)$this->input->get('id'));
		exit(dr_json(1, lang('014')));
	}
	
	/**
     * 权限划分
     */
	public function auth() {
	
		$id = (int)$this->input->get('id');
		if ($id == 1) $this->admin_msg(lang('027'));
		
		if (IS_POST) $this->auth_model->update_auth($id, 'system', $this->input->post('data', TRUE));
		
		$data = $this->auth_model->get_role($id);
		$this->config->load('auth');
		$auth = $this->config->item('auth');
		$this->_menu[$data['name']] = 'admin/role/auth/id/'.$id;
		
		$this->template->assign(array(
			'menu' => $this->get_menu($this->_menu),
			'list' => $auth,
			'data' => $data
		));
        $this->template->display('role_auth.html');
	}
	
	/**
     * 缓存
     */
	public function cache() {
		$admin = $this->input->get('admin') ? $this->input->get('admin') : $this->input->get('admin');
		$this->auth_model->role_cache();
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}

}