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

class Level extends M_Controller {

	public $groupid;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->groupid = (int)$this->input->get('gid');
		if (!$this->groupid) $this->admin_msg(lang('m-126'));
		if ($this->groupid < 2) $this->admin_msg(lang('m-127'));
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('m-032') => 'member/admin/group/index',
				lang('m-034') => 'member/admin/level/index/gid/'.$this->groupid,
				lang('add') => 'member/admin/level/add/gid/'.$this->groupid,
				lang('001') => 'member/admin/setting/cache'
			)),
			'groupid' => $this->groupid
		));
		$this->load->model('level_model');
    }

    /**
     * 管理
     */
    public function index() {
		if (IS_POST) {
			if ($this->input->post('action') == 'del') { // 删除
				$this->level_model->del($this->input->post('ids'));
				exit(dr_json(1, lang('014')));
			} elseif ($this->input->post('action') == 'edit') { // 修改
				$_ids = $this->input->post('ids');
				$_data = $this->input->post('data');
				foreach ($_ids as $id) {
					$this->db->where('id', $id)->update('member_level', array('displayorder' => (int)$_data[$id]['displayorder']));
				}
				unset($_ids, $_data);
				exit(dr_json(1, lang('014')));
			}
		}
		$this->template->assign(array(
			'list' => $this->level_model->get_data(),
		));
		$this->template->display('level_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
		$page = (int)$this->input->get('page');
		$error = 0;
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$page = (int)$this->input->post('page');
			if (!$data['name']) {
				$error = lang('m-033');
			} else {
				$this->level_model->add($data);
				$this->admin_msg(lang('014'), dr_url('member/level/index', array('gid' => $this->groupid)), 1);
			}
		}
		$this->template->assign(array(
			'page' => $page,
			'error' => $error,
		));
		$this->template->display('level_add.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
		$id = (int)$this->input->get('id');
		$data = $this->level_model->get($id);
		if (!$data) $this->admin_msg(lang('019'));
		$page = (int)$this->input->get('page');
		$error = 0;
		if (IS_POST) {
			$_data = $data;
			$data = $this->input->post('data', TRUE);
			$page = (int)$this->input->post('page');
			if (!$data['name']) {
				$error = lang('m-033');
			} else {
				$this->level_model->edit($_data, $data);
				$this->admin_msg(lang('014'), dr_url('member/level/index', array('gid' => $this->groupid)), 1);
			}
		}
		$this->template->assign(array(
			'page' => $page,
			'data' => $data,
			'error' => $error
		));
		$this->template->display('level_add.html');
    }
	
	/**
     * 删除
     */
    public function del() {
		$this->level_model->del((int)$this->input->get('id'));
		exit(dr_json(1, lang('014')));
	}
}