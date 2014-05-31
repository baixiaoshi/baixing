<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/member/controllers/admin/pm.php
 */
/*/$this->output->enable_profiler(TRUE);


*/
class Pm extends D_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		// 管理菜单
		$_menu =  array(
		    array('name' => '配置',		'url' => dr_admin_url('pm/index', '', 'member')),
		    array('name' => '发送',		'url' => dr_admin_url('pm/send', '', 'member')),
		    array('name' => '记录',		'url' => dr_admin_url('pm/sendlist', '', 'member'))
		);
		$this->template->assign('menu', $this->get_menu($_menu));
        $this->load->model('member_pms_model');
    }

    /**
     * 配置
     */
    public function index() {
		$this->template->display('pm_index.html');
    }
	
	/**
     * 发送
     */
    public function send() {
		$page	= max((int)$this->input->get('page'), 1);
		$total	= $type	= 0;
		if (IS_POST) {
			$type	= $this->input->post('type');
			if ($type == 0) {
				// 目标会员
				$type	= 1;
				$post	= $this->input->post('data');
				$data	= array(1, 2); // 保存sql语句，以及数量
				$total	= count($data);
				$error	= $total > 0 ? '' : '没有找到相关会员';
				$this->cache->file->save('send_member', $data, 500);
			}
		}
		if ($this->input->get('is_send') || $this->input->post('is_send')) {
			// 发送消息
			$data	= $page > 1 ? $this->cache->file->get('send_data') : $this->input->post('data');
			$member	= $this->cache->file->get('send_member');
			if (empty($data)) $this->admin_msg('发送内容不存在');
			if (empty($member)) $this->admin_msg('发送目标会员不存在');
			$total	= $this->member_pms_model->send($this->admin['uid'], $member, $data['title'], $data['content'], 1);
			var_dump($total);
			exit;
		}
		$this->template->assign(array(
			'type'	=> $type,
			'total'	=> $total,
			'error'	=> $error,
			'submit'=> $total > 0 ? lang('a-send') : lang('a-search')
		));
		$this->template->display('pm_send.html');
    }
	
	/**
     * 记录
     */
    public function sendlist() {
		$this->template->display('pm_index.html');
    }
}