<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/controllers/admin/verify.php
 */
	
class Verify extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('030') => 'admin/verify/index',
		    lang('add') => 'admin/verify/add_js',
		    lang('001') => 'admin/verify/cache'
		)));
    }
	
	/**
     * 管理
     */
    public function index() {
	
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
            $this->db->where_in('id', $ids)->delete($this->db->dbprefix('admin_verify'));
			exit(dr_json(1, lang('014')));
		}
		
		$this->template->assign('list', $this->auth_model->get_verify_all());
		$this->template->display('verify_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
		
			$data = $this->input->post('data', TRUE);
            if (count($data['role']) > 8) exit(dr_json(0, lang('119')));
			
			exit(dr_json(1, lang('014'), $this->db->insert('admin_verify', array(
				'name' => $data['name'],
				'verify' => dr_array2string($data['role'])
			))));
		}
		
        $role = $this->dcache->get('role');
        $select = '';
        foreach ($role as $t) {
            if ($t['id'] > 1) $select.= '<option value="'.$t['id'].'">'.$t['name'].'</option>';
        }
		
		$this->template->assign(array(
            'role' => $role,
            'select' => $select
        ));
		$this->template->display('verify_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->auth_model->get_verify($id);
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
		
			$data = $this->input->post('data', TRUE);
		    if (count($data['role']) > 8) exit(dr_json(0, lang('119')));
			
			exit(dr_json(1, lang('014'), $this->db->where('id', $id)->update('admin_verify', array(
				'name' => $data['name'],
				'verify' => dr_array2string($data['role'])
			))));
		}
		
        $role = $this->dcache->get('role');
        $select = '';
        foreach ($role as $t) {
            if ($t['id'] > 1) $select.= '<option value="'.$t['id'].'">'.$t['name'].'</option>';
        }
		
		$this->template->assign(array(
			'data' => $data,
            'role' => $role,
            'select' => $select
        ));
		$this->template->display('verify_add.html');
    }
	
	/**
     * 删除
     */
    public function del() {
        $this->db
             ->where('id', (int)$this->input->get('id'))
             ->delete($this->db->dbprefix('admin_verify'));
		exit(dr_json(1, lang('014')));
	}
    
    /**
     * 流程查看
     */
    public function show() {
        echo '<div style="width:200px;padding-left:90px;padding-bottom:20px">';
        $num = (int)$this->input->get('num');
		for ($i = 1; $i <= $num; $i++) {
            echo '
            <div class="fillet ">'.lang('05'.$i).'</div>
            <div class="fillet-x ">↓</div>
            ';
		}
        echo '<div class="fillet ">'.lang('059').'</div>';
        echo '</div>';
	}
    
    /**
     * 缓存
     */
    public function cache() {
	
		$admin = $this->input->get('admin') ? $this->input->get('admin') : $this->input->get('admin');
		$_data = $this->db
					  ->order_by('id ASC')
					  ->get($this->db->dbprefix('admin_verify'))
					  ->result_array();

		$data = array();
		if ($_data) {
			foreach ($_data as $t) {
				$t['verify'] = dr_string2array($t['verify']);
                $t['num'] = count($t['verify']);
				$data[$t['id']] = $t;
			}
			$this->dcache->set('verify', $data);
		} else {
			$this->dcache->delete('verify');
		}
		
		$this->clear_cache('verify');
		
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}