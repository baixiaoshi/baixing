<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.3
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Score extends M_Controller {

	private $userinfo;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		
		$uid = (int)$this->input->get('uid');
		$this->userinfo = $this->member_model->get_member($uid);
		if (!$this->userinfo) $this->admin_msg(lang('130'));
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('back') => 'member/admin/home/index',
				SITE_SCORE => 'member/admin/score/index/uid/'.$uid,
				lang('add') => 'member/admin/score/add/uid/'.$uid.'_js'
			)),
			'userinfo' => $this->userinfo
		));
		$this->load->model('score_model');
    }

    /**
     * 首页
     */
    public function index() {
	
		$uid = (int)$this->input->get('uid');
		
		// 根据参数筛选结果
		$param = array('uid' => $uid, 'type' => 1);
		if ($this->input->get('search')) $param['search'] = 1;
		
		// 数据库中分页查询
		list($data, $param)	= $this->score_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		$param['uid'] = $uid;
		
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->score_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$this->template->assign(array(
			'list' => $data,
			'name' => SITE_SCORE,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url('member/score/index', $param), $param['total'])
		));
		$this->template->display('score_index.html');
    }
	
	/**
     * 充值
     */
    public function add() {
		
		if (IS_POST) {
			$data = $this->input->post('data');
			$value = intval($data['value']);
			if (!$value) {
				exit(dr_json(0, lang('131'), 'value'));
			}
			$this->member_model->update_score(1, $this->userinfo['uid'], $value, '', $data['note']);
			$this->member_model->add_notice($this->userinfo['uid'], 1, dr_lang('m-080', SITE_SCORE, $value, $this->member['username']));
			exit(dr_json(1, lang('000')));
		}
		
		$this->template->display('score_add.html');
    }
}