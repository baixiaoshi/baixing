<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.6
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Member_Form extends M_Controller {

	protected $form; // 表单信息
	protected $table; // 表单表名称
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		// 表单验证
		$fid = (int)trim(strrchr($this->router->class, '_'), '_');
		$this->form = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'form', $fid);
		if (!$this->form) $this->member_msg(lang('mod-108'));
		if ($this->form['permission'][$this->markrule]['disabled']) $this->member_msg(lang('mod-101'));
		$this->table = SITE_ID.'_'.APP_DIR.'_form_'.$fid;
	}
	
    /**
     * 管理
     */
    public function index() {
		
		// 接收参数
		$kw = $this->input->get('kw', TRUE);
		$cid = (int)$this->input->get('cid');
		$order = isset($_GET['order']) && $_GET['order'] ? $this->input->get('order', TRUE) : 'inputtime desc';
		
		// 组合条件
		$this->link->where('uid', $this->uid);
		if ($kw) $this->link->like('subject', $kw);
		if ($cid) $this->link->where('cid', $cid);
		$this->link->order_by($order);
		
		$tpl = is_file(APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_data_'.$this->form['id'].'.html') ? 'mform_data_'.$this->form['id'].'.html' : 'mform_data.html';
		
		if ($this->input->get('action') == 'search') {
			// ajax请求
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->link
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->table)
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign(array(
                'list' => $data,
				'isedit' => $this->form['permission'][$this->markrule]['notedit'] ? 0 : 1,
            ));
			$this->template->display($tpl);
		} else {
			// 首次请求
			$url = 'index.php?s='.APP_DIR.'&c='.$this->router->class.'&m=index&action=search'.(IS_AJAX ? '' : '&kw='.$kw.'&order='.$order.'&cid='.$cid);
			$this->template->assign(array(
				'list' => $this->link->limit($this->pagesize)->get($this->table)->result_array(),
				'isedit' => $this->form['permission'][$this->markrule]['notedit'] ? 0 : 1,
				'datatpl' => $tpl,
				'moreurl' => $url,
				'searchurl' => $url,
			));
			$this->template->display(is_file(APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_index_'.$this->form['id'].'.html') ? 'mform_index_'.$this->form['id'].'.html' : 'mform_index.html');
		}
    }
    
	/**
     * 列表
     */
    public function listc() {
		
		// 接收参数
		$kw = $this->input->get('kw', TRUE);
		$cid = (int)$this->input->get('cid');
		$order = isset($_GET['order']) && $_GET['order'] ? $this->input->get('order', TRUE) : 'inputtime desc';
		
		// 相关文档
		$cdata = $this->_get_data($cid);
		if (!$cdata) $this->member_msg(dr_lang('mod-30', $cid));
		if ($cdata['uid'] != $this->uid) $this->member_msg(lang('mod-05'));
		
		// 组合条件
		$this->link->where('cid', $cid);
		if ($kw) $this->link->like('subject', $kw);
		$this->link->order_by($order);
		
		if ($this->input->get('action') == 'search') {
			// ajax请求
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->link
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->table)
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign(array(
                'list' => $data,
            ));
			$this->template->display(is_file(APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_cdata_'.$this->form['id'].'.html') ? 'mform_cdata_'.$this->form['id'].'.html' : 'mform_cdata.html');
		} else {
			// 首次请求
			$this->template->assign(array(
				'list' => $this->link->limit($this->pagesize)->get($this->table)->result_array(),
				'datatpl' => is_file(APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_cdata_'.$this->form['id'].'.html') ? 'mform_cdata_'.$this->form['id'].'.html' : 'mform_cdata.html',
				'searchurl' => 'index.php?s='.APP_DIR.'&c='.$this->router->class.'&m=listc&action=search&cid='.$cid.(IS_AJAX ? '' : '&kw='.$kw.'&order='.$order),
				'meta_name' => $cdata['title'],
			));
			$this->template->display(is_file(APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_listc_'.$this->form['id'].'.html') ? 'mform_index_'.$this->form['id'].'.html' : 'mform_listc.html');
		}
    }
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->link
					 ->where('id', $id)
					 ->where('uid', $this->uid)
					 ->get($this->table)
					 ->row_array();
		if (!$data) $this->admin_msg(dr_lang('mod-109', $id));
		
		if (IS_POST) {
			// 设置uid便于校验处理
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $data['uid'];
			$_POST['data']['author'] = $data['author'];
			$post = $this->validate_filter($this->form['field'], $data);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
				$post[1]['uid'] = $data['uid'];
				$post[1]['author'] = $data['author'];
				$table = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_form_'.$this->form['id']);
				$this->link
					 ->where('id', $id)
					 ->update($table, $post[1]);
				// 操作成功处理附件
				$this->attachment_handle($data['uid'], $table.'-'.$id, $this->form['field'], $post);
				$this->admin_msg(lang('000'), dr_url(APP_DIR.'/'.$this->router->class.'/index'), 1, 0);
			}
		}
		
		$tpl = APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_edit_'.$this->form['id'].'.html';
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'myfield' => $this->field_input($this->form['field'], $data, TRUE)
		));
		$this->template->display(is_file($tpl) ? basename($tpl) : 'mform_edit.html');
    }
    
	/**
     * 删除
     */
    public function del() {
	
		$id = (int)$this->input->post('id');
		$table = SITE_ID.'_'.APP_DIR.'_form_'.$this->form['id'];
		if ($this->link->where('id', $id)->where('uid', $this->uid)->delete($table)) {
			$this->load->model('attachment_model');
			$this->attachment_model->delete_for_table($table.'-'.$id);
		}
		
		exit(dr_json(1, lang('000')));
	}
	
	/**
     * 查看
     */
    public function show() {
	
		$id = (int)$this->input->get('id');
		$data = $this->link
					 ->where('id', $id)
					 ->get($this->table)
					 ->row_array();
		if (!$data) exit('<div style="padding:10px 20px 20px;">'.dr_lang('mod-109', $id).'</div>');
		// 格式化输出自定义字段
		$fields = $this->form['field'];
		$fields['inputtime'] = array('fieldtype' => 'Date');
		$data = $this->field_format_value($fields, $data, 1);
		
		$tpl = APPPATH.'templates/member/'.MEMBER_TEMPLATE.'/mform_show_'.$this->form['id'].'.html';
		$this->template->assign(array(
			'tpl' => str_replace(FCPATH, '/', $tpl),
			'data' => $data,
		));
		$this->template->display(is_file($tpl) ? basename($tpl) : 'mform_show.html');
	}
	
	
	// 内容表内容
	private function _get_data($cid) {
	
		$data = $this->get_cache_data('show'.APP_DIR.SITE_ID.$cid);
		if (!$data) {
			$this->load->model('content_model');
			$data = $this->content_model->get($cid);
		}
		
		return $data;
	}
}