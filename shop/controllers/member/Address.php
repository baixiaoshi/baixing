<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.5
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Address extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('address_model');
    }
	
    /**
     * 收货地址
     */
    public function index() {
		$this->template->assign(array(
			'list' => $this->db
						   ->where('uid', $this->uid)
						   ->order_by('id asc')
						   ->get('member_address')
						   ->result_array()
		));
		$this->template->display('address_index.html');
    }
	
	/**
     * 添加地址
     */
    public function add() {
	
		if (IS_POST) {
			$data = $this->validate_filter($this->address_model->get_address_field());
			if (isset($data['error'])) {
				if (IS_AJAX) exit(dr_json(0, $data['msg'], $data['error']));
				$error = $data['error'];
			} else {
				$this->address_model->add_address($data[1]);
				$this->member_msg(lang('000'), dr_url('address/index'), 1);
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
		));
		$this->template->display('address_add.html');
    }
	
	/**
	 * 修改收货地址
	 */
	public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->address_model->get_address($id);
		
		if (IS_POST) {
			$data = $this->validate_filter($this->address_model->get_address_field(), $data);
			if (isset($data['error'])) {
				if (IS_AJAX) exit(dr_json(0, $data['msg'], $data['error']));
				$error = $data['error'];
			} else {
				$this->address_model->edit_address($id, $data[1]);
				$this->member_msg(lang('000'), dr_url('address/index'), 1);
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
		));
		$this->template->display('address_add.html');
	}
	
	/**
	 * 删除收货地址
	 */
	public function del() {
		$id = (int)$this->input->get('id');
		$this->db
			 ->where('id', $id)
			 ->where('uid', $this->uid)
			 ->delete('member_address');
		if (IS_AJAX) exit(dr_json(1, lang('000')));
		$this->member_msg(lang('000'), dr_url('address/index'), 1);
	}
}