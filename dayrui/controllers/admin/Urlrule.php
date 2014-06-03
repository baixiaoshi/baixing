<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.1.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Urlrule extends M_Controller {

	private $type;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->type = array(
			0 => lang('128'),
			1 => lang('html-010'),
		);
		$this->template->assign('type', $this->type);
		$this->template->assign('menu', $this->get_menu(array(
		    lang('124') => 'admin/urlrule/index',
		    lang('add') => 'admin/urlrule/add',
		    lang('001') => 'admin/urlrule/cache'
		)));
    }
	
	/**
     * 管理
     */
    public function index() {
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if (!$this->is_auth('admin/urlrule/del')) exit(dr_json(0, lang('160')));
            $this->db->where_in('id', $ids)->delete('urlrule');
			$this->cache(1);
			exit(dr_json(1, lang('000')));
		}
		$this->template->assign(array(
			'list' => $this->db->get('urlrule')->result_array(),
			'color' => array(
				0 => 'green',
				1 => 'blue',
			),
		));
		$this->template->display('urlrule_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
		if (IS_POST) {
			$this->db->insert('urlrule', array(
				'type' => $this->input->post('type'),
				'name' => $this->input->post('name'),
				'value' => dr_array2string($this->input->post('data')),
			 ));
			$this->cache(1);
			$this->admin_msg(lang('000'), dr_url('urlrule/index'), 1);
		}
		$this->template->display('urlrule_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('urlrule')
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		if (IS_POST) {
			$this->db->where('id', $id)->update('urlrule', array(
				'name' => $this->input->post('name'),
				'value' => dr_array2string($this->input->post('data')),
			 ));
			$this->cache(1);
			$this->admin_msg(lang('000'), dr_url('urlrule/index'), 1);
		}
		$data['value'] = dr_string2array($data['value']);
		$this->template->assign(array(
			'data' => $data,
        ));
		$this->template->display('urlrule_add.html');
    }
	
    /**
     * 缓存
     */
    public function cache($update = 0) {
		$this->dcache->delete('urlrule');
		$data = $this->db->get('urlrule')->result_array();
		if ($data) {
			$cache = array();
			foreach ($data as $t) {
				$t['value'] = dr_string2array($t['value']);
				$cache[$t['id']] = $t;
			}
			$this->dcache->set('urlrule', $cache);
		}
		((int)$this->input->get('admin') || $update) or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}