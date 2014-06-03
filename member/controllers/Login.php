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
	
class Login extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 登录
     */
    public function index() {
	
		$data = $error = '';
		$MEMBER = $this->get_cache('member');
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			if ($MEMBER['setting']['logincode'] && !$this->check_captcha('code')) {
				$error = lang('m-000');
			} elseif (!$data['password'] || !$data['username']) {
				$error = lang('m-001');
			} else {
				$code = $this->member_model->login($data['username'], $data['password'], $data['auto'] ? 31104000 : 86400);
				if (strlen($code) > 3) { // 登录成功
					$url = $this->input->get('backurl') ? urldecode($this->input->get('backurl')) : dr_member_url('home/index');
					$this->member_msg(lang('m-002').$code, $url, 1, 3);
				} elseif ($code == -1) {
					$error = lang('m-003');
				} elseif ($code == -2) {
					$error = lang('m-004');
				} elseif ($code == -3) {
					$error = lang('m-005');
				} elseif ($code == -4) {
					$error = lang('m-006');
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'code' => $MEMBER['setting']['logincode'],
			'error' => $error,
			'meta_name' => lang('m-007')
		));
		$this->template->display('login.html');
    }
	
	/**
     * Ajax 登录
     */
	public function ajax() {
	
		$data = $error = '';
		$MEMBER = $this->get_cache('member');
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			if ($MEMBER['setting']['logincode'] && !$this->check_captcha('code')) {
				$error = lang('m-000');
			} elseif (!$data['password'] || !$data['username']) {
				$error = lang('m-001');
			} else {
				$code = $this->member_model->login($data['username'], $data['password'], $data['auto'] ? 31104000 : 86400);
				if (strlen($code) > 3) { // 登录成功
					echo(lang('m-077').$code);
					exit;
				} elseif ($code == -1) {
					$error = lang('m-003');
				} elseif ($code == -2) {
					$error = lang('m-004');
				} elseif ($code == -3) {
					$error = lang('m-005');
				} elseif ($code == -4) {
					$error = lang('m-006');
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'code' => $MEMBER['setting']['logincode'],
			'error' => $error,
			'meta_name' => lang('m-007')
		));
		$this->template->display('login_ajax.html');
		$this->output->enable_profiler(FALSE);
	}
	
	/**
     * 找回密码
     */
    public function find() {
	
		$step = max(1, (int)$this->input->get('step'));
		$error = '';
		
		if (IS_POST) {
			switch ($step) {
				case 1:
					if ($uid = get_cookie('find')) {
						$this->member_msg(lang('m-093'), dr_member_url('login/find', array('step' => 2, 'uid' => $uid)), 1);
					} else {
						$name = $this->input->post('name');
						$value = $this->input->post('value');
						$data = $this->db
									 ->select('uid,username,randcode')
									 ->where($name, $value)
									 ->limit(1)
									 ->get('member')
									 ->row_array();
						if ($data) {
							$randcode = rand(1000, 9999);
							if ($name == 'email') {
								$this->load->helper('email');
								if (!$this->sendmail($value, lang('m-014'), dr_lang('m-187', $data['username'], $randcode, $this->input->ip_address()))) {
									$this->member_msg(lang('m-189'));
								}
								set_cookie('find', $data['uid'], 300);
								$this->db->where('uid', $data['uid'])->update('member', array('randcode' => $randcode));
								$this->member_msg(lang('m-093'), dr_member_url('login/find', array('step' => 2, 'uid' => $data['uid'])), 1);
							} else {
								$result = $this->member_model->sendsms($value, dr_lang('m-088', $randcode));
								if ($result['status']) { // 发送成功
									set_cookie('find', $data['uid'], 300);
									$this->db->where('uid', $data['uid'])->update('member', array('randcode' => $randcode));
									$this->member_msg(lang('m-093'), dr_member_url('login/find', array('step' => 2, 'uid' => $data['uid'])), 1);
								} else { // 发送失败
									$this->member_msg($result['msg']);
								}
							}
						} else {
							$error = $name == 'phone' ? lang('m-182') : lang('m-185');
						}
					}
					break;
				case 2:
					$uid = (int)$this->input->get('uid');
					$code = (int)$this->input->post('code');
					$data = $this->db
									 ->where('uid', $uid)
									 ->where('randcode', $code)
									 ->select('salt,uid,username,email')
									 ->limit(1)
									 ->get('member')
									 ->row_array();
					if (!$data) $this->member_msg(lang('m-000'));
					
					$password1 = $this->input->post('password1');
					$password2 = $this->input->post('password2');
					if ($password1 != $password2) {
						$error = lang('m-019');
					} elseif (!$password1) {
						$error = lang('m-018');
					} else {
						// 修改密码
						$this->db
							 ->where('uid', $data['uid'])
							 ->update('member', array(
								'randcode' => 0,
								'password' => md5(md5($password1).$data['salt'].md5($password1))
							 ));
						$this->member_msg(lang('m-052'), dr_url('login/index'), 1);
					}
					break;
			}
		}
		
		$this->template->assign(array(
			'step' => $step,
			'error' => $error,
			'action' => 'find',
			'mobile' => $this->get_cache('member', 'setting','ismobile'),
			'meta_name' => lang('m-014')
		));
		$this->template->display('find.html');
    }
	
	/**
     * 审核会员
     */
    public function verify() {
		$data = $this->member_model->get_decode($this->input->get('code'));
		if (!$data) $this->member_msg(lang('m-190'));
		list($time, $uid, $code) = explode(',', $data);
		if (!$this->db->where('uid', $uid)->where('randcode', $code)->count_all_results('member')) {
			$this->member_msg(lang('m-193'));
		}
		$this->db->where('uid', $uid)->update('member', array('randcode' => 0, 'groupid' => 3));
		$this->member_msg(lang('m-194'), dr_member_url('login/index'), 1);
    }
	
	/**
     * 重发邮件审核
     */
    public function resend() {
		if ($this->member['groupid'] != 1) $this->member_msg(lang('m-233'));
		if ($this->get_cache('MEMBER', 'setting', 'regverify') != 1) $this->member_msg(lang('m-230'));
		if (get_cookie('resend') && $this->member['randcode']) $this->member_msg(lang('m-232'));
		$url = MEMBER_URL.'index.php?c=login&m=verify&code='.$this->member_model->get_encode($this->uid);
		$this->sendmail($this->member['email'], lang('m-191'), dr_lang('m-192', $this->member['username'], $url, $url, $this->input->ip_address()));
		$this->input->set_cookie('resend', $this->uid, 3600);
		$this->member_msg(dr_lang('m-231', $this->member['email']), dr_url('home/index'), 1);
    }
	
	/**
     * 退出
     */
    public function out() {
		$this->member_msg(lang('m-015').$this->member_model->logout(), SITE_URL, 1, 3);
    }
	
}