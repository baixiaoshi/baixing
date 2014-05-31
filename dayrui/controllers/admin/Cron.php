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
	
class Cron extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('108') => 'admin/cron/index'
		)));
    }
	
	/**
     * 管理
     */
    public function index() {
	
		$param = array();
		if ($this->input->get('search') == 1) $param['search'] = 1;
		list($list, $param)	= $this->cron_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		$this->template->assign(array(
			'list' => $list,
			'total' => (int)$param['total'],
			'type' => $this->cron_model->get_type(),
			'pages'	=> $this->get_pagination(dr_url('cron/index', $param), $param['total']),
		));
		$this->template->display('cron_index.html');
    }
	
	/**
     * 查看值
     */
    public function show() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('cron_queue')
					 ->row_array();
		if (!$data) exit(lang('019'));
		echo '<pre style="width:500px;max-height:400px;overflow:auto;margin-bottom:10px;">';
		print_r(dr_string2array($data['value']));
		echo '</pre>';
    }

	/**
     * 执行
     */
    public function execute() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('cron_queue')
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		
		$this->cron_model->execute($data);
		$this->admin_msg(lang('000'), dr_url('cron/index'), 1);
	}
}