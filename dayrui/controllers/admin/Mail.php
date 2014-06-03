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
	
class Mail extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('190') => 'admin/mail/index',
		    lang('206') => 'admin/mail/add_js',
		    lang('325') => 'admin/mail/send',
		    lang('112') => 'admin/mail/log',
		    lang('001') => 'admin/mail/cache'
		)));
    }
	
	/**
     * 管理
     */
    public function index() {
	
		if (IS_POST) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if (!$this->is_auth('admin/mail/del')) exit(dr_json(0, lang('160')));
			
			$this->db
				 ->where_in('id', $ids)
				 ->delete('mail_smtp');
				 
			$this->cache(1);
			
			exit(dr_json(1, lang('014')));
		}
		
		$this->template->assign(array(
			'list' => $this->db
						   ->get('mail_smtp')
						   ->result_array(),
		));
		$this->template->display('mail_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			
			$data = $this->input->post('data', TRUE);
			$data['port'] = (int)$data['port'];
			
			$this->db->insert('mail_smtp', $data);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->display('mail_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('mail_smtp')
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
		
			$data = $this->input->post('data', TRUE);
			$data['port'] = (int)$data['port'];
			if ($data['pass'] == '******') unset($data['pass']);
			
			$this->db
				 ->where('id', $id)
				 ->update('mail_smtp', $data);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->assign(array(
			'data' => $data,
        ));
		$this->template->display('mail_add.html');
    }
	
	/**
     * 发送
     */
    public function send() {
	
		$this->template->display('mail_send.html');
    }
	
	/**
     * 发送
     */
    public function ajaxsend() {
		
		$all = $this->input->post('is_all');
		$data = $this->input->post('data', true);
		$mail = $data['mail'];
		
		if ($data['mails'] && $all) {
			$mail = str_replace(array(PHP_EOL, chr(13), chr(10)), ',', $data['mails']);
			$mail = str_replace(',,', ',', $mail);
			$mail = trim($mail, ',');
		}
		if (!$mail) exit(dr_json(0, lang('328')));
		
		$i = $j = 0;
		$mail = @explode(',', $mail);
		if (!$data['title'] || !$data['message']) exit(dr_json(0, lang('329')));
		
		foreach ($mail as $tomail) {
			if ($this->member_model->sendmail($tomail, $data['title'], $data['message'])) {
				$i ++;
			} else {
				$j ++;
			}
		}
		exit(dr_json(1, dr_lang('327', $i, $j)));
    }
	
	
	/**
     * 日志
     */
    public function log() {
	
		if (IS_POST) {
			@unlink(FCPATH.'cache/mail_error.log');
			exit(dr_json(1, lang('000')));
		}
		
		$data = $list = array();
		$file = @file_get_contents(FCPATH.'cache/mail_error.log');
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
			'pages'	=> $this->get_pagination(dr_url('mail/log'), $total)
		));
		$this->template->display('mail_log.html');
    }
	
	/**
     * test
     */
    public function test() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('mail_smtp')
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		$this->load->library('Dmail');
		$this->dmail->set(array(
			'host' => $data['host'],
			'user' => $data['user'],
			'pass' => $data['pass'],
			'port' => $data['port'],
			'from' => $data['user']
		));
		
		if ($this->dmail->send(SYS_EMAIL, 'test', 'test for '.SITE_NAME)) {
			echo 'ok';
		} else {
			echo 'Error: '.$this->dmail->error();
		}
	}
    
    /**
     * 缓存
     */
    public function cache($update = 0) {
	
		$this->clear_cache('email');
		$this->dcache->delete('email');
		
		$data = $this->db->get('mail_smtp')->result_array();
		if ($data) $this->dcache->set('email', $data);
		
		((int)$this->input->get('admin') || $update) or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}