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
	
class Model extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('158') => 'member/admin/model/index',
		    lang('add') => 'member/admin/model/add',
		    lang('001') => 'member/admin/model/cache'
		)));
		$this->load->model('space_model_model');
    }
	
	/**
     * 管理
     */
    public function index() {
		$this->template->assign(array(
			'list' => $this->db
						   ->get($this->db->dbprefix('space_model'))
						   ->result_array(),
		));
		$this->template->display('model_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			
			$data = $this->input->post('data', TRUE);
			$result = $this->space_model_model->add($data);
			if ($result === TRUE) {
				
				/* 更新相关缓存 */
				$this->space_model_model->cache();
				$this->load->model('menu_model');
				$this->menu_model->cache();
				$this->load->model('member_model');
				$this->member_model->cache();
				/* 更新相关缓存 */
				
				$this->admin_msg(lang('000'), dr_url('member/model/index'), 1);
			}
			
		}
		
		$this->template->assign(array(
			'data' => $data,
			'result' => $result,
		));
		$this->template->display('model_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->db->dbprefix('space_model'))
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$this->space_model_model->edit($id, $data);
			$this->space_model_model->cache();
			$this->admin_msg(lang('000'), dr_url('member/model/index'), 1);
		}
		
		$data['setting'] = dr_string2array($data['setting']);
		
		$this->template->assign(array(
			'data' => $data,
			'result' => $result,
		));
		$this->template->display('model_add.html');
    }
	
	/**
     * 删除
     */
    public function del() {
		$this->space_model_model->del((int)$this->input->get('id'));
		$this->admin_msg(lang('000'), dr_url('member/model/index'), 1);
	}
	
	/**
     * 缓存
     */
    public function cache() {
		$this->space_model_model->cache();
		$this->input->get('admin') or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}