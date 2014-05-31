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

class Pay extends M_Controller {

	private $userinfo;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('pay_model');
    }

    /**
     * 首页
     */
    public function index() {
	
		$uid = (int)$this->input->get('uid');
		$userinfo = $this->member_model->get_member($uid);
		if (!$userinfo) $this->admin_msg(lang('130'));
		
		// 根据参数筛选结果
		$param = array('uid' => $uid);
		if ($this->input->get('search')) $param['search'] = 1;
		
		// 数据库中分页查询
		list($data, $param)	= $this->pay_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		$param['uid'] = $uid;
		
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->pay_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('back') => 'member/admin/home/index',
				SITE_MONEY => 'member/admin/pay/index/uid/'.$uid,
				lang('add') => 'member/admin/pay/add/uid/'.$uid.'_js'
			)),
			'list' => $data,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url('member/pay/index', $param), $param['total']),
			'userinfo' => $userinfo
		));
		$this->template->display('pay_index.html');
    }
	
	/**
     * 充值
     */
    public function add() {
		
		$uid = (int)$this->input->get('uid');
		$userinfo = $this->member_model->get_member($uid);
		if (!$userinfo) exit(dr_json(1, lang('130')));
		
		if (IS_POST) {
			$data = $this->input->post('data');
			$value = intval($data['value']);
			if (!$value) exit(dr_json(0, lang('131'), 'value'));
			
			$this->pay_model->add($uid, $data['value'], $data['note']);
			$this->member_model->add_notice($this->userinfo['uid'], 1, dr_lang('m-080', SITE_MONEY, $value, $this->member['username']));
			exit(dr_json(1, lang('000')));
		}
		
		$this->template->assign('userinfo', $userinfo);
		$this->template->display('score_add.html');
    }
	
	/**
     * 虚拟卡
     */
    public function card() {
	
		if (IS_POST && $this->input->post('action')) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'del') {
				if (!$this->is_auth('member/admin/pay/del')) exit(dr_json(0, lang('160')));
				$this->db
					 ->where_in('id', $ids)
					 ->delete($this->db->dbprefix('member_paycard'));
				exit(dr_json(1, lang('000')));
			} else {
				$data = $this->db
							 ->where_in('id', $ids)
							 ->get($this->db->dbprefix('member_paycard'))
							 ->result_array();
				$print = '<div style="padding:5px 15px 15px">';
				foreach ($data as $t) {
					$print .= lang('html-403').'：' . $t['card'] . '   '.lang('html-061').'：' . $t['password'] . '   '.lang('html-408').'：' . $t['money'] . '<br>';
				}
				echo $print.'</div>';
				exit;
			}
		}
		
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) $param['search'] = 1;
		
		// 数据库中分页查询
		list($data, $param)	= $this->pay_model->card_limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->pay_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('m-164') => 'member/admin/pay/card',
				lang('add') => 'member/admin/pay/addcard_js',
				lang('m-161') => 'member/admin/setting/pay',
			)),
			'list' => $data,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url('member/pay/card', $param), $param['total']),
			'userinfo' => $userinfo
		));
		$this->template->display('pay_card.html');
    }
	
	/**
     * 添加虚拟卡
     */
    public function addcard() {
		
		if (IS_POST) {
		
			$data = $this->input->post('data');
			$value = intval($data['money']);
			if (!$value > 0) exit(dr_json(0, '&nbsp;', 'money'));
			
			for ($i = 0; $i < $data['num']; $i++) {
				$this->pay_model->card($value, $data['endtime']);
			}
			
			exit(dr_json(1, lang('000')));
		}
		
		$this->template->display('pay_addcard.html');
    }
}