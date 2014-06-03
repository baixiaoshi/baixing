<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/member/controllers/admin/spacetpl.php
 */

require FCPATH.'dayrui/core/D_File.php';

class Spacetpl extends D_File {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->path = FCPATH.'member/templates/';
        $this->_dir = array('member', 'admin');
		$this->template->assign(array(
			'path' => $this->path,
			'furi' => 'member/spacetpl/',
			'auth' => 'member/admin/spacetpl/',
			'menu' => $this->get_menu(array(
				lang('008') => 'member/admin/spacetpl/index',
			)),
			'space' => 1
		));
    }
    
	/**
     * 会员权限划分
     */
	public function permission() {
		
		$dir = trim(str_replace('.', '', $this->input->get('dir')), '/');
		$file = $this->path.$dir.'/rule.php';
		
		if (IS_POST) {
			
			file_put_contents($file, dr_array2string($this->input->post('data')));
			echo dr_json(1, lang('000'));exit;
		}
		
		$this->template->assign('data', is_file($file) ? dr_string2array(file_get_contents($file)) : array());
		$this->template->assign('space', $dir);
		$this->template->display('space_permission.html');
	}
}