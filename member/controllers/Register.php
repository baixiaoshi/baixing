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
	
class Register extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 注册
     */
    public function index() {
	
		$MEMBER = $this->get_cache('MEMBER');
		if (!$MEMBER['setting']['register']) $this->member_msg(lang('m-016'));
		if ($this->member_model->member_uid()) $this->member_msg(lang('m-017'));
		
		$data = array();
		$error = '';
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			if ($MEMBER['setting']['regcode'] && !$this->check_captcha('code')) {
				$error = array('name' => 'code', 'msg' => lang('m-000'));
			} elseif (!$data['password']) {
				$error = array('name' => 'password', 'msg' => lang('m-018'));
			} elseif ($data['password'] !== $data['password2']) {
				$error = array('name' => 'password2', 'msg' => lang('m-019'));
			} elseif ($result = $this->is_username($data['username'])) {
				$error = array('name' => 'username', 'msg' => $result);
			} elseif ($result = $this->is_email($data['email'])) {
				$error = array('name' => 'email', 'msg' => $result);
			} else {
				$id = $this->member_model->register($data);
				if ($id > 0) { // 注册成功
					$this->member_msg(lang('m-020'), dr_url('login/index'), 1);
				} elseif ($id == -1) {
					$error = array('name' => 'username', 'msg' => lang('m-021'));
				} elseif ($id == -2) {
					$error = array('name' => 'email', 'msg' => lang('m-011'));
				} elseif ($id == -3) {
					$error = array('name' => 'email', 'msg' => lang('m-022'));
				} elseif ($id == -4) {
					$error = array('name' => 'username', 'msg' => lang('m-023'));
				} elseif ($id == -5) {
					$error = array('name' => 'username', 'msg' => lang('m-024'));
				} elseif ($id == -6) {
					$error = array('name' => 'username', 'msg' => lang('m-025'));
				} elseif ($id == -7) {
					$error = array('name' => 'username', 'msg' => lang('m-026'));
				} elseif ($id == -8) {
					$error = array('name' => 'username', 'msg' => lang('m-027'));
				} elseif ($id == -9) {
					$error = array('name' => 'username', 'msg' => lang('m-028'));
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'code' => $MEMBER['setting']['regcode'],
			'error' => $error,
			'meta_name' => lang('m-029')
		));
		$this->template->display('register.html');
    }
}