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
	
class Login extends M_Controller {
    
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
	
		if (IS_POST) {
		
			if (get_cookie('admin_login')) $this->admin_msg(lang('167'));
			if (SITE_ADMIN_CODE && !$this->check_captcha('code')) $this->admin_msg(lang('168'));
			
			$uid = $this->member_model->admin_login($this->input->post('username', TRUE), $this->input->post('password', TRUE));
			if ($uid > 0) {
				$url = $this->input->get('backurl') ? urldecode($this->input->get('backurl')) : dr_url('home');
				$url = pathinfo($url);
				$url = $url['basename'] ? $url['basename'] : dr_url('home/index');
				$this->session->unset_userdata('error_admin_login');
				$this->admin_msg(lang('042'), $url, 1);
			}
			
			$error = (int)$this->session->userdata('error_admin_login');
			$error ++;
			if ($error > 10) {
				set_cookie('admin_login', 1, 900);
				$this->session->unset_userdata('error_admin_login');
				$this->admin_msg(lang('041'));
			}
			
			$this->session->set_userdata('error_admin_login', $error);
			if ($uid == -1) {
				$this->admin_msg(lang('043'));
			} elseif ($uid == -2) {
				$this->admin_msg(lang('044'));
			} elseif ($uid == -3) {
				$this->admin_msg(lang('045'));
			} elseif ($uid == -4) {
				$this->admin_msg(lang('046'));
			} else {
				$this->admin_msg(lang('047'));
			}
		}
		
		$this->template->assign('username', $this->member['username']);
		$this->template->display('login.html');	
    }
	
	public function logout() {
		$this->session->unset_userdata('admin');
		$this->session->unset_userdata('siteid');
		$this->admin_msg(lang('048'), dr_url(''), 1);
	}
}