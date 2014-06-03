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
	
class Mform extends M_Controller {

	public $dir;
	public $link;
	public $table;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->dir = $this->input->get('dir');
		$module = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
		if (!$module) $this->admin_msg(lang('100'));
		$this->link = $this->site[SITE_ID];
		$this->table = $this->db->dbprefix(SITE_ID.'_'.$this->dir.'_form');
		$this->template->assign('menu', $this->get_menu(array(
		    dr_lang('330', $module['name']) => 'admin/mform/index/dir/'.$this->dir,
		    lang('add') => 'admin/mform/add/dir/'.$this->dir,
		    lang('334') => 'admin/mform/export/dir/'.$this->dir,
		    lang('333') => 'admin/mform/import/dir/'.$this->dir,
		    lang('001') => 'admin/module/cache/dir/'.$this->dir
		)));
		$this->load->model('mform_model');
    }
	
	/**
     * 管理
     */
    public function index() {
		$this->template->assign(array(
			'dir' => $this->dir,
			'list' => $this->link->get($this->table)->result_array()
		));
		$this->template->display('mform_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			$result = $this->mform_model->add($this->input->post('data', TRUE));
			if ($result === FALSE) {
				$this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
			} else {
				$this->admin_msg($result);
			}
		}
		
		$this->template->assign(array(
			'data' => array()
		));
		$this->template->display('mform_add.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->link
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->table)
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$this->link->where('id', $id)->update($this->table, array(
				'name' => $data['name'],
				'setting' => dr_array2string($data['setting']),
				'permission' => dr_array2string($data['permission']),
			));
			$this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
		}
		
		$data['setting'] = dr_string2array($data['setting']);
		$data['permission'] = dr_string2array($data['permission']);
		
		$this->template->assign(array(
			'data' => $data,
		));
		$this->template->display('mform_add.html');
    }
	
	/**
     * 禁用/可用
     */
    public function disabled() {
		if ($this->is_auth('mform/edit')) {
			$id = (int)$this->input->get('id');
			$data = $this->link
						 ->select('disabled')
						 ->where('id', $id)
						 ->limit(1)
						 ->get($this->table)
						 ->row_array();
			$value = $data['disabled'] == 1 ? 0 : 1;
			$this->link
				 ->where('id', $id)
				 ->update($this->table, array('disabled' => $value));
		}
		exit(dr_json(1, lang('014')));
    }
	
	// 导出表单
	public function export() {
		if ($this->input->get('todo')) {
			$this->load->model('module_model');
			$this->module_model->export_form($this->dir);
			$this->template->assign('size', dr_format_file_size(filesize(FCPATH.$this->dir.'/config/form.php')));
			$this->template->display('mform_export.html');
		} else {
			$this->admin_msg(lang('332'), dr_url('mform/export', array('dir' => $this->dir, 'todo' => 1)), 2);
		}
	}
	
	// 导入表单
	public function import() {
		if (IS_POST) {
			$file = FCPATH.$this->dir.'/config/form.php';
			if (!is_file($file)) $this->admin_msg(dr_lang('335', $this->dir.'/config/form.php'));
			$this->load->model('module_model');
			if ($this->module_model->import_form($this->dir)) {
				$this->admin_msg(lang('014'), dr_url('mform/index', array('dir' => $this->dir)), 2);
			} else {
				$this->admin_msg(lang('336'));
			}
		}
		$this->template->assign('size', dr_format_file_size(@filesize(FCPATH.$this->dir.'/config/form.php')));
		$this->template->display('mform_import.html');
	}
	
	/**
     * 删除
     */
    public function del() {
		$this->mform_model->del((int)$this->input->get('id'));
		$this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
	}
}