<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * 后台Api调用类
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/controllers/admin/api.php
 */
 
class Api extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 查看资料
     */
	public function member() {
	
		$data = $this->db
					 ->where('username', urldecode($this->input->get('username', TRUE)))
					 ->limit(1)
					 ->get('member')
					 ->row_array();
		if (!$data) exit(lang('236'));
		
		$this->template->assign(array(
			'data' => $data,
		));
		$this->template->display('member.html');
	}
	
	/**
     * 测试ftp链接状态
     */
	public function testftp() {
	
		$host = $this->input->get('host');
		$port = $this->input->get('port');
		$username = $this->input->get('username');
		$password = $this->input->get('password');
		$pasv = $this->input->get('pasv');
		$path = $this->input->get('path');
		$mode = $this->input->get('mode');
		
		if (!$host || !$username || !$password) exit(lang('035'));
		$this->load->library('ftp');
		if (!$this->ftp->connect(array(
			'hostname' => $host,
			'username' => $username,
			'password' => $password,
			'port' => $port ? $prot : 21,
			'passive' => $pasv ? TRUE : FALSE,
			'debug' => FALSE
		))) exit(lang('036'));
		
		if (!$this->ftp->upload(FCPATH.'index.php', $path.'/test.ftp', $mode, 0775)) exit(lang('037'));
		if (!$this->ftp->delete_file($path.'/test.ftp')) exit(lang('039'));
		$this->ftp->close();
		
		exit('ok');
	}
}