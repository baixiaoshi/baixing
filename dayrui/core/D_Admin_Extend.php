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

class D_Admin_Extend extends M_Controller {

	public $catid; // 栏目参数id
	public $catrule; // 栏目权限规则
	public $content; // 内容数据
	protected $field; // 自定义字段+含系统字段
	protected $sysfield; // 系统字段
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$cid = (int)$this->input->get('cid');
		$catid = (int)$this->input->get('catid');
		$this->content = $this->content_model->get($cid);
		if (!$this->content) $this->admin_msg(lang('019'));
		// 判断管理组是否具有此栏目的管理权限
		$this->catrule = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR,'category',$this->content['catid'],'setting','admin',$this->admin['adminid']);
		if ($this->admin['adminid'] > 1 && !$this->catrule['show']) {
			$this->admin_msg(lang('257'));
		} else {
			$this->catrule['show'] = $this->catrule['add'] = $this->catrule['edit'] = $this->catrule['del'] = 1;
		}
		$this->load->library('Dfield', array(APP_DIR));
		$this->content['type'] = dr_string2array($this->content['type']);
		$this->sysfield = array(
			'inputtime' => array(
				'name' => lang('104'),
				'ismain' => 1,
				'fieldtype' => 'Date',
				'fieldname' => 'inputtime',
				'setting' => array(
					'option' => array(
						'width' => 140
					),
					'validate' => array(
						'formattr' => '',
					)
				)
			)
		);
		$field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend');
		$this->field = $field ? array_merge($field, $this->sysfield) : $this->sysfield;
		$this->template->assign(array(
			'cid' => $cid,
            'menu' => $this->get_menu(array(
				lang('mod-29') => APP_DIR.'/admin/extend/index/cid/'.$cid.'/catid/'.$catid,
				'<font color=red><b>'.lang('mod-37').'</b></font>' => APP_DIR.'/admin/extend/add/cid/'.$cid.'/catid/'.$catid,
				lang('mod-36') => APP_DIR.'/admin/home/index/catid/'.$catid,
			)),
			'catid' => $catid,
			'catrule' => $this->catrule,
			'content' => $this->content,
		));
	}

    /**
     * 管理
     */
    public function index() {
		
		if (IS_POST && !$this->input->post('search')) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			$row = $this->link
						->select('tableid')
						->where('cid', (int)$this->content['cid'])
						->limit(1)
						->get($this->content_model->prefix.'_extend')
						->row_array();
			$tableid = (int)$row['tableid'];
			switch($this->input->post('action')) {
		
				case 'del':
					if ($this->catrule['del']) {
						$this->content_model->delete_extend_for_ids($this->content['id'], $this->content['uid'], $this->content['catid'], $tableid, $ids);
					} else {
						exit(dr_json(0, lang('160')));
					}
					exit(dr_json(1, lang('000')));
					break;
					
				case 'order':
					if (!$this->catrule['edit']) exit(dr_json(0, lang('160')));
					$_data = $this->input->post('data');
					foreach ($ids as $id) {
						$this->link
							 ->where('id', $id)
							 ->update($this->content_model->prefix.'_extend_'.$tableid, $_data[$id]);
					}
					exit(dr_json(1, lang('000')));
					break;
					
				case 'move':
					
					$type = $this->input->post('type');
					if (!$type) exit(dr_json(0, lang('160')));
					if (!$this->catrule['edit']) exit(dr_json(0, lang('160')));
					$this->content_model->extend_move($this->content['id'], $tableid, $ids, $type);
					exit(dr_json(1, lang('000')));
					break;
					
				default :
				
					exit(dr_json(0, lang('000')));
					break;
			}
		}
		
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) $param['search'] = 1;
		if ($this->input->get('type')) $param['type'] = $this->input->get('type');
		
		// 数据库中分页查询
		list($list, $param)	= $this->content_model->extend_limit_page($this->content['id'], $param);
		
		// 搜索参数
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->content_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		$param['cid'] = $this->content['id'];
		$param['catid'] = $this->content['catid'];
		
		$this->template->assign(array(
			'app' => $app,
			'list' => $list,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/extend/index', $param), $param['total']),
		));
		$this->template->display('content_extend_index.html');
    }
    
	/**
     * 添加
     */
    public function add() {
		
		if (!$this->catrule['add']) $this->admin_msg(lang('160'));
		
		$type = (int)$this->input->get('type');
		$error = $data = array();
		$result = '';
		
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
				if ($id = $this->content_model->add_extend($data[1])) {
					$mark = $this->content_model->prefix.'-'.$this->content['uid'].'-'.$id;
					$member = $this->member_model->get_base_member($this->content['uid']);
					$markrule = $member['markrule'];
					$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $this->content['catid']);
					$rule = $category['permission'][$markrule];
					// 积分处理
					if ($rule['extend_experience']) {
						$this->member_model->update_score(0, $this->content['uid'], $rule['extend_experience'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					// 虚拟币处理
					if ($rule['extend_score']) {
						$this->member_model->update_score(1, $this->content['uid'], $rule['extend_score'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					
					// 操作成功处理附件
					$this->attachment_handle($this->content['uid'], $mark, $this->field);
					$create = MODULE_HTML ? dr_module_create_show_file($this->content['id'], 1) : '';
					if ($this->input->post('action') == 'back') {
						$this->admin_msg(lang('000').($create ? "<script src='".$create."'></script>" : ''), dr_url(APP_DIR.'/extend/index', array('cid' => $this->content['id'],'catid' => (int)$_GET['catid'],'type' => (int)$_GET['type'])), 1, 0);
					} else {
						$type = $data[1]['mytype'];
						unset($data);
						$data['mytype'] = (int)$type;
						$result = lang('000');
					}
				} else {
					$error = array('error' => $id);
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'result' => $result,
			'create' => $create,
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));
		$this->template->display('content_extend_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		if (!$this->catrule['edit']) $this->admin_msg(lang('160'));
		
		$id = (int)$this->input->get('id');
		$data = $this->content_model->get_extend($id);
		if (!$data) $this->admin_msg(lang('019'));
		
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
					$this->admin_msg(lang('000').(MODULE_HTML ? dr_module_create_show_file($this->content['id']) : ''), dr_url(APP_DIR.'/extend/index', array('cid' => $this->content['id'],'catid' => (int)$_GET['catid'],'type' => (int)$_GET['type'])), 1, 0);
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
   
}