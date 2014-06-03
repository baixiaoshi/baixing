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
	
class Sms extends M_Controller {

	private $service = 'http://sms.dayrui.com/index.php';

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('316') => 'admin/sms/index',
		    lang('319') => 'admin/sms/send',
		    lang('318') => 'admin/sms/log',
		)));
    }
	
	/**
     * 账号
     */
    public function index() {
	
		$file = FCPATH.'config/sms.php';
		
		if (IS_POST) {
		
			$data = $this->input->post('data');
			if (strlen($data['note']) > 30 ) $this->admin_msg(lang('320'));
			if ($_POST['aa'] == 0) unset($data['third']);
			
			$this->load->library('dconfig');
			$size = $this->dconfig
						 ->file($file)
						 ->note('短信配置文件')
						 ->space(8)
						 ->to_require_one($data);
			if (!$size) $this->admin_msg(lang('066'));
			$this->admin_msg(lang('000'), dr_url('sms/index'), 1);
		}
		
		$data = is_file($file) ? require $file : array();
		$this->template->assign(array(
			'data' => $data,
			'service' => $this->service,
		));
		$this->template->display('sms_index.html');
    }
	
	/**
     * 发送
     */
    public function send() {
	
		$file = FCPATH.'config/sms.php';
		if (!is_file($file)) $this->admin_msg(lang('321'));
		
		$this->template->display('sms_send.html');
    }
	
	/**
     * 发送
     */
    public function ajaxsend() {
	
		$file = FCPATH.'config/sms.php';
		if (!is_file($file)) exit(dr_json(0, lang('321')));
		
		$data = $this->input->post('data', true);
		if (strlen($data['content']) > 150) exit(dr_json(0, lang('322')));
		
		$mobile = $data['mobile'];
		if ($data['mobiles'] && !$data['mobile']) {
			$mobile = str_replace(array(PHP_EOL, chr(13), chr(10)), ',', $data['mobiles']);
			$mobile = str_replace(',,', ',', $mobile);
			$mobile = trim($mobile, ',');
		}
		if (substr_count($mobile, ',') > 40) exit(dr_json(0, lang('326')));
		
		$result = $this->member_model->sendsms($mobile, $data['content']);
		if ($result === FALSE) {
			 exit(dr_json(0, '#0'.lang('323')));
		} else {
			 exit(dr_json($result['status'], $result['msg']));
		}
    }
	
	/**
     * 日志
     */
    public function log() {
	
		if (IS_POST) {
			@unlink(FCPATH.'cache/sms_error.log');
			exit(dr_json(1, lang('000')));
		}
		
		$data = $list = array();
		$file = @file_get_contents(FCPATH.'cache/sms_error.log');
		if ($file) {
			$data = explode(PHP_EOL, $file);
			$data = $data ? array_reverse($data) : array();
			unset($data[0]);
			$page = max(1, (int)$this->input->get('page'));
			$limit = ($page - 1) * SITE_ADMIN_PAGESIZE;
			$i = $j = 0;
			foreach ($data as $v) {
				if ($i >= $limit && $j < SITE_ADMIN_PAGESIZE) {
					$list[] = $v;
					$j ++;
				}
				$i ++;
			}
		}
		
		$total = count($data);
		$this->template->assign(array(
			'list' => $list,
			'total' => $total,
			'pages'	=> $this->get_pagination(dr_url('sms/log'), $total)
		));
		$this->template->display('sms_log.html');
    }
	
}