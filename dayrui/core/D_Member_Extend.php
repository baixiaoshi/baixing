<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Member_Extend extends M_Controller {

	public $catrule; // 栏目权限规则
	public $content; // 内容数据
	public $tableid; // 附表id
	protected $field; // 自定义字段+含系统字段
	protected $sysfield; // 系统字段
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$cid = (int)$this->input->get('cid');
		$this->content = $this->content_model->get($cid);
		if (!$this->content) $this->member_msg(lang('019'));
		if ($this->content['uid'] != $this->uid) $this->member_msg(lang('mod-05'));
		// 查询附表id
		$row = $this->link
					->select('tableid')
					->where('id', $cid)
					->limit(1)
					->get(SITE_ID.'_'.APP_DIR.'_extend')
					->row_array();
		$this->tableid = (int)$row['tableid'];
		// 判断具有此栏目的管理权限
		$this->catrule = $this->module_rule[$this->content['catid']];
		$this->load->library('Dfield', array(APP_DIR));
		$this->content['type'] = dr_string2array($this->content['type']);
		$this->field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend');
		$this->template->assign(array(
			'cid' => $cid,
			'catrule' => $this->catrule,
			'content' => $this->content,
		));
	}

    /**
     * 管理
     */
    public function index() {
		
		if (IS_POST) {
		
			// 判断id是否为空
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('019')));
			
			if ($this->input->post('action') == 'update') {
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->link
						 ->where('id', $id)
						 ->update(SITE_ID.'_'.APP_DIR.'_extend_'.$this->tableid, $_data[$id]);
				}
				exit(dr_json(1, lang('000')));
			}
		}
		
		$this->link
             ->where('uid', $this->uid)
             ->where('cid', (int)$this->content['id']);
			 
        // 搜索关键字
		$kw = $this->input->get('kw', TRUE);
		if ($kw) $this->link->like('name', $kw);
		
		// 搜索类型
		$type = (int)$this->input->get('type');
		if ($type) $this->link->where('mytype', $type);
		
        // 排序
		$order = $this->input->get('order', TRUE);
		$order = $order ? $order : 'displayorder desc';
		$this->link->order_by($order.',id desc');
		
		if ($this->input->get('action') == 'search') {
			// ajax搜索数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->link
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get(SITE_ID.'_'.APP_DIR.'_extend_'.$this->tableid)
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign(array(
				'kw' => $kw,
                'list' => $data
            ));
			$this->template->display('content_extend_data.html');
		} else {
			$url = IS_AJAX ? "index.php?s=".APP_DIR."&c=extend&m=index&cid=".$this->content['id']."&action=search" : "index.php?s=".APP_DIR."&c=extend&m=index&cid=".$this->content['id']."&action=search&kw=$kw&order=$order&type=$type";
			$this->template->assign(array(
				'kw' => $kw,
				'list' => $this->link
							   ->limit($this->pagesize)
							   ->get(SITE_ID.'_'.APP_DIR.'_extend_'.$this->tableid)
							   ->result_array(),
				'total' => $this->total[1],
				'order' => $order,
				'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
				'moreurl' => $url,
				'searchurl' => $url,
				'meta_name' => lang('mod-01'),
			));
			$this->template->display('content_extend_index.html');
		}
    }
    
	/**
     * 添加
     */
    public function add() {
		
		if (!$this->catrule['add']) $this->member_msg(lang('160'));
		
		$type = (int)$this->input->get('type');
		$error = $data = array();
		$result = '';
		
		// 虚拟币检查
		if ($this->catrule['extend_score'] + $this->member['score'] < 0) {
			$this->member_msg(dr_lang('mod-44', abs($this->catrule['extend_score']), $this->member['score']));
		}
		
		if (IS_POST) {
			$type = (int)$this->input->post('type');
			$_POST['data']['cid'] = $this->content['id'];
			$_POST['data']['uid'] = $this->content['uid'];
			$data = $this->validate_filter($this->field);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
				$data[1]['cid'] = $this->content['id'];
				$data[1]['uid'] = $this->content['uid'];
				$data[1]['catid'] = $this->content['catid'];
				$data[1]['inputtime'] = SYS_TIME;
				if ($id = $this->content_model->add_extend($data[1])) {
					$mark = $this->content_model->prefix.'-'.$this->content['uid'].'-'.$id;
					$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $this->content['catid']);
					// 积分处理
					if ($this->catrule['extend_experience']) {
						$this->member_model->update_score(0, $this->content['uid'], $this->catrule['extend_experience'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					// 虚拟币处理
					if ($this->catrule['extend_score']) {
						$this->member_model->update_score(1, $this->content['uid'], $this->catrule['extend_score'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					
					// 操作成功处理附件
					$this->attachment_handle($this->content['uid'], $mark, $this->field);
					if (IS_AJAX) exit(dr_json(1, lang('m-340'), dr_member_url(APP_DIR.'/extend/index', array('cid'=>$this->content['id']))));
					$this->template->assign(array(
						'url' => SITE_URL.APP_DIR.'/index.php?c=extend&id='.$id,
						'add' => dr_member_url(APP_DIR.'/extend/add', array('cid' => $this->content['id'], 'type'=>$data[1]['mytype'])),
						'edit' => 0,
						'html' => MODULE_HTML ? dr_module_create_show_file($this->content['id']) : '',
						'list' => dr_member_url(APP_DIR.'/extend/index', array('cid' => $this->content['id'])),
						'meta_name' => lang('mod-19')
					));
					$this->template->display('success.html');
					exit;
				} else {
					$error = array('error' => $id);
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'result' => $result,
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));
		$this->template->display('content_extend_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		if (!$this->catrule['edit']) $this->member_msg(lang('160'));
		
		$id = (int)$this->input->get('id');
		$data = $this->content_model->get_extend($id);
		if (!$data) $this->member_msg(lang('019'));
		
		$error = array();
		$result = '';
		
		if (IS_POST) {
			$_data = $data;
			$type = (int)$this->input->post('type');
			$_POST['data']['cid'] = $this->content['id'];
			$_POST['data']['uid'] = $this->content['uid'];
			$data = $this->validate_filter($this->field, $_data);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
				$data[1]['cid'] = $this->content['id'];
				$data[1]['uid'] = $this->content['uid'];
				$data[1]['catid'] = $this->content['catid'];
				if ($id = $this->content_model->edit_extend($id, $_data['tableid'], $data[1])) {
					$mark = $this->content_model->prefix.'-'.$this->content['uid'].'-'.$id;
					// 操作成功处理附件
					$this->attachment_handle($this->content['uid'], $mark, $this->field, $_data);
					if (IS_AJAX) exit(dr_json(1, lang('m-340'), dr_member_url(APP_DIR.'/extend/index', array('cid'=>$this->content['id']))));
					$this->template->assign(array(
						'url' => SITE_URL.APP_DIR.'/index.php?c=extend&id='.$id,
						'add' => dr_member_url(APP_DIR.'/extend/add', array('cid' => $this->content['id'], 'type'=>$data[1]['mytype'])),
						'edit' => 1,
						'html' => MODULE_HTML ? dr_module_create_show_file($this->content['id']) : '',
						'list' => dr_member_url(APP_DIR.'/extend/index', array('cid' => $this->content['id'])),
						'meta_name' => lang('mod-19')
					));
					$this->template->display('success.html');	
					exit;
				} else {
					$error = array('error' => $id);
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'result' => $result,
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));
		$this->template->display('content_extend_add.html');
    }
    
	/**
     * 删除
     */
    public function del() {
		
		if (!$this->catrule['del']) $this->member_msg(lang('160'));
		
		$id = (int)$this->input->post('id');
		if ($id) {
			$this->content_model->delete_extend_for_ids($this->content['id'], $this->content['uid'], $this->content['catid'], $this->tableid, array($id));
		}
		exit(dr_json(1, lang('000')));
	}
}