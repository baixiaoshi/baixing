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
	
class Content extends M_Controller {

	public $mid; // 模型id
	public $model; // 模型

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->mid = (int)$this->input->get('mid');
		$this->model = $this->get_cache('space-model', $this->mid);
		if (!$this->model) $this->admin_msg(lang('159'));
		$this->template->assign(array(
			'mid' => $this->mid,
			'menu' => $this->get_menu(array(
				$this->model['name'] => 'member/admin/content/index/mid/'.$this->mid
			))
		));
		$this->load->model('space_content_model');
		$this->space_content_model->tablename = $this->db->dbprefix('space_'.$this->model['table']);
    }
	
	/**
     * 管理
     */
    public function index() {
	
		if (IS_AJAX && $this->input->post('action')) {
			$ids = $this->input->post('ids');
			if ($this->input->post('action') == 'delete') {
				if ($ids) {
					foreach ($ids as $id) {
						$data = $this->db
									 ->where('id', (int)$id)
									 ->select('uid')
									 ->limit(1)
									 ->get($this->space_content_model->tablename)
									 ->row_array();
						if ($data) {
							$this->db->where('id', (int)$id)->delete($this->space_content_model->tablename);
							$this->load->model('attachment_model');
							$this->attachment_model->delete_for_table($this->space_content_model->tablename.'-'.$id); // 删除附件
							$member = $this->member_model->get_member($data['uid']);
							$markrule = $member ? $member['mark'] : 0;
							$experience = (int)$this->model['setting'][$markrule]['experience'];
							$score = (int)$this->model['setting'][$markrule]['score'];
							// 积分处理
							if ($experience > 0) {
								$this->member_model->update_score(0, $data['uid'], -$experience, '', "delete");
							}
							// 虚拟币处理
							if ($score > 0) {
								$this->member_model->update_score(1, $data['uid'], -$score, '', "delete");
							}
						}
					}
				}
				exit(dr_json(1, lang('000')));
			} else {
				if ($ids) {
					$status = (int)$this->input->post('status');
					foreach ($ids as $id) {
						$data = $this->db
									 ->where('id', (int)$id)
									 ->select('uid')
									 ->limit(1)
									 ->get($this->space_content_model->tablename)
									 ->row_array();
						$this->db->where('id', (int)$id)->update($this->space_content_model->tablename, array('status' => $status));
						if ($status) {
							$member = $this->member_model->get_member($data['uid']);
							$markrule = $member ? $member['mark'] : 0;
							$experience = (int)$this->model['setting'][$markrule]['experience'];
							$score = (int)$this->model['setting'][$markrule]['score'];
							$mark = $this->space_content_model->tablename.'-'.$id;
							// 积分处理
							if ($experience) {
								$this->member_model->update_score(0, $data['uid'], $experience, $mark, "add", 1);
							}
							// 虚拟币处理
							if ($score) {
								$this->member_model->update_score(1, $data['uid'], $score, $mark, "add", 1);
							}
						}
					}
				}
				exit(dr_json(1, lang('000')));
			}
		}
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) $param['search'] = 1;
		// 数据库中分页查询
		list($data, $param)	= $this->space_content_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		// 搜索参数
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->space_content_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		$this->template->assign(array(
			'list' => $data,
			'param'	=> $_param,
			'field' => $this->model['field'],
			'pages'	=> $this->get_pagination(dr_url('member/content/index', $param), $param['total']),
		));
		$this->template->display(is_file(FCPATH.'member/templates/admin/content_'.$this->mid.'.html') ? 'content_'.$this->mid.'.html' : 'content_index.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->space_content_model->tablename)
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		
		$this->load->model('space_category_model');
		$this->load->model('space_content_model');
		$category = $this->space_category_model->get_data($this->mid, $data['uid']);
		
		if (IS_POST) {
		
			$post = $this->validate_filter($this->model['field']);
			$catid = (int)$this->input->post('catid'); // 栏目参数
			
			// 验证出错信息
			if (isset($post['error'])) {
				$error = $post;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-300'));
			} elseif ($category[$catid]['child'] || $category[$catid]['modelid'] != $this->mid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-301'));
			} else {
			
				// 设定文档默认值
				$post[1]['catid'] = $catid;
				$post[1]['status'] = (int)$this->input->post('status');
				
				// 修改文档
				if (($id = $this->space_content_model->edit($id, $data['uid'], $post[1])) != FALSE) {
					$mark = $this->space_content_model->tablename.'-'.$id;
					if ($post[1]['status']) {
					
						$member = $this->member_model->get_member($data['uid']);
						$markrule = $member ? $member['mark'] : 0;
						$experience = (int)$this->model['setting'][$markrule]['experience'];
						$score = (int)$this->model['setting'][$markrule]['score'];
						
						// 积分处理
						if ($experience) {
							$this->member_model->update_score(0, $data['uid'], $experience, $mark, "lang,m-151,{$category[$catid]['name']}", 1);
						}
						// 虚拟币处理
						if ($score) {
							$this->member_model->update_score(1, $data['uid'], $score, $mark, "lang,m-151,{$category[$catid]['name']}", 1);
						}
					}
					$this->attachment_handle($data['uid'], $mark, $this->model['field'], $data, $post[1]['status'] ? TRUE : FALSE);
					$this->member_msg(lang('000'), dr_url('member/content/index', array('mid' => $this->mid, 'tid' => $this->tid)), 1);
				}
			}
			$data = $data[1];
			unset($data['id']);
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'select' => $this->select_space_category($category, (int)$data['catid'], 'name=\'catid\'', NULL, 1),
			'myfield' => $this->field_input($this->model['field'], $data),
		));
		$this->template->display('content_edit.html');
    }
	
}