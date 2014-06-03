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

class Space extends M_Controller {
	
	private $flag;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$menu = array(lang('110') => 'member/admin/space/index');
		$this->flag = $this->get_cache('member', 'setting', 'space', 'flag');
		if ($this->flag) {
			foreach ($this->flag as $i => $t) {
				if ($t['name']) {
					$menu[$t['name'].'('.$this->db->where('flag', $i)->count_all_results('space_flag').')'] = 'member/admin/space/index/flag/'.$i;
				}
			}
		}
		$this->template->assign('menu', $this->get_menu($menu));
		$this->load->model('space_model');
    }

    /**
     * 首页
     */
    public function index() {
	
		if (IS_AJAX) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'del') {
				$this->space_model->delete($ids);
				exit(dr_json(1, lang('000')));
				break;
			} elseif ($this->input->post('action') == 'order') {
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->db
						 ->where('uid', (int)$id)
						 ->update('space', $_data[$id]);
				}
				exit(dr_json(1, lang('000')));
				break;
			} elseif ($this->input->post('action') == 'flag') {
				$flag = $this->input->post('flagid');
				foreach ($ids as $uid) {
					if ($flag > 0) {
						// 增加推荐位
						if (!$this->db->where('uid', (int)$uid)->where('flag', $flag)->count_all_results('space_flag')) {
							$this->db->replace('space_flag', array(
								'uid' => $uid,
								'flag' => $flag,
							));
						}
					} elseif ($flag < 0) {
						// 取消推荐位
						$this->db
							 ->where('uid', (int)$uid)
							 ->where('flag', abs($flag))
							 ->delete('space_flag');
					}
				}
				exit(dr_json(1, lang('000')));
			} else {
				if (!$this->is_auth('member/admin/space/edit')) exit(dr_json(0, lang('160')));
				$this->db
					 ->where_in('uid', $ids)
					 ->update('space', array('status' => (int)$this->input->post('status')));
				exit(dr_json(1, lang('000')));
			}
		}
	
		// 根据参数筛选结果
		$param = IS_POST ? $this->input->post('data') : $this->input->get(TRUE);
		if ($this->input->get('flag')) $param['flag'] = (int)$this->input->get('flag');
		unset($param['page']);
		
		// 数据库中分页查询
		list($data, $param)	= $this->space_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		$this->template->assign(array(
			'list' => $data,
			'flag' => isset($param['flag']) ? $param['flag'] : '',
			'flags' => $this->flag,
			'param'	=> $param,
			'pages'	=> $this->get_pagination(dr_url('member/space/index', $param), $param['total'])
		));
		$this->template->display('space_index.html');
    }
	
    /**
     * 空间修改
     */
    public function edit() {
    	
    	$uid = (int)$this->input->get('uid');
    	$data = $this->db
    				 ->where('uid', $uid)
    				 ->limit(1)
    				 ->get('space')
    				 ->row_array();
    	if (!$data) {
			// 创建空间
			$this->db->insert('space', array(
				'uid' => $uid,
				'name' => '',
				'logo' => '',
				'code' => '',
				'hits' => 0,
				'style' => 'default',
				'title' => '',
				'footer' => '',
				'status' => 0,
				'regtime' => SYS_TIME,
				'keywords' => '',
				'description' => '',
			));
			$this->space_model->init($uid);
		}
    	
    	if (IS_POST) {
		
    		$post = $this->input->post('data');
			
    		if ($this->db->where('uid <>', $uid)->where('name', $post['name'])->count_all_results('space')) {
    			$error = lang('html-539');
    		} else {
    			$this->db
					 ->where('uid', $uid)
					 ->update($this->db->dbprefix('space'), $post);
					 
    			$this->attachment_handle($uid, $this->db->dbprefix('space').'-'.$uid, array(
    				array(
    					'fieldname' => 'logo',
    					'fieldtype' => 'File'
    				)
    			), $data);
    			$this->member_msg(lang('000'), dr_url('member/space/index'), 1);
    		}
    	}
    	
    	$this->template->assign(array(
    		'data' => $data,
    		'error' => $error,
    	));
    	$this->template->display('space_edit.html');
    }
}