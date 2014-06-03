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

class D_Member_Verify extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
    /**
     * 审核
     */
    public function index() {
		
        if (IS_POST) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('019')));
			
			$this->load->model('attachment_model');
			foreach ($ids as $id) {
				$data = $this->link // 主表状态
							 ->where('uid', (int)$this->uid)
							 ->select('uid,catid')
							 ->limit(1)
							 ->get($this->content_model->prefix.'_index')
							 ->row_array();
				if ($data) {
					// 删除数据
					$this->content_model->del_verify($id);
					// 删除表对应的附件
					$this->attachment_model->delete_for_table($this->content_model->prefix.'_verify-'.$id);
				}
			}
			
			exit(dr_json(1, lang('mod-23')));
		}
        
		$this->link
             ->select('id,inputtime,catid,content')
             ->where('uid', $this->uid)
             ->where('`status` BETWEEN 1 AND 8')
             ->order_by('inputtime DESC');
             
		if ($this->input->get('action') == 'more') { // ajax更多数据
		
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->link
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->content_model->prefix.'_verify')
						 ->result_array();
			if (!$data) exit('null');
			
			$this->template->assign('list', $data);
			$this->template->display('verify_data.html');
		} else {
			$this->template->assign(array(
				'list' => $this->link
							   ->limit($this->pagesize)
							   ->get($this->content_model->prefix.'_verify')
							   ->result_array(),
				'total' => $this->total[2],
				'moreurl' => "index.php?s=".APP_DIR."&c=verify&m=index&action=more",
                'meta_name' => lang('mod-24'),
			));
			$this->template->display('verify_index.html');
		}
    }
	
	/**
     * 修改审核
     */
    public function edit() {
		
		$id = (int)$this->input->get('id');
		$data = $this->content_model->get_verify($id);
		$catid = $data['catid'];
		$error = array();
		if (!$data) $this->member_msg(lang('019'));
		// 禁止修改他人文档
        if ($data['author'] != $this->member['username'] && $data['uid'] != $this->member['uid']) $this->member_msg(lang('mod-05'));
		$field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'field');
		$isedit = (int)$this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'setting', 'edit');
		
		if (IS_POST) {
		
			$_data = $data;
			$catid = $isedit ? $catid : (int)$this->input->post('catid');
			$cat = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid);
			$field = $cat['field'] ? array_merge($field, $cat['field']) : $field;
			
			// 设置uid便于校验处理
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $this->uid;
			$_POST['data']['author'] = $this->member['username'];
			$_POST['data']['inputtime'] = $data['inputtime'];
			$_POST['data']['updatetime'] = SYS_TIME;
			
			$data = $this->validate_filter($field, $_data);
			
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} elseif (!$isedit && !$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('cat-22'));
			} else {
			
				$status = 1; // 修改审核后从头开始审核
				
				$data[1]['uid'] = $this->uid;
				$data[1]['catid'] = $catid;
                $data[1]['status'] = $status;
				$data[1]['author'] = $this->member['username'];
                $data[1]['updatetime'] = SYS_TIME;
				
				// 修改数据
				if ($this->content_model->edit($_data, $data)) {
					if (IS_AJAX) exit(dr_json(1, lang('m-341'), dr_member_url(APP_DIR.'/verify/index')));
					$this->template->assign(array(
						'url' => dr_member_url(APP_DIR.'/verify/index'),
						'add' => dr_member_url(APP_DIR.'/home/add', array('catid' => $catid)),
						'edit' => 1,
						'list' => dr_member_url(APP_DIR.'/home/index'),
						'catid' => $catid,
						'meta_name' => lang('mod-03')
					));
					$this->template->display('verify.html');
				} else {
					$this->member_msg(lang('mod-06'));
				}
				exit;
			}
		}
		
		$backurl = str_replace(MEMBER_URL, '', $_SERVER['HTTP_REFERER']);
		$this->template->assign(array(
			'purl' => dr_url(APP_DIR.'/verify/edit', array('id' => $id)),
			'data' => $data,
			'catid' => $catid,
			'error' => $error,
			'isedit' => $isedit,
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'),$data['catid'],'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"','',1),
			'backurl' => $backurl ? $backurl : dr_url(APP_DIR.'/verify/index'),
			'myfield' => $this->field_input($field, $data, TRUE),
			'listurl' => $backurl ? $backurl : dr_url(APP_DIR.'/verify/index'),
            'meta_name' => lang('mod-25')
		));
		$this->template->display('verify_edit.html');
    }
	
}