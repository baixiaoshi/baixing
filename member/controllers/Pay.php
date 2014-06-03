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
	
class Pay extends M_Controller {
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('pay_model');
    }
	
	/**
     * 在线充值付款跳转
     */
	public function pay() {
		if ($url = $this->pay_model->pay_for_online((int)$this->input->get('id'))) {
			$this->member_msg(lang('m-177').'<div style="display:none">'.$url.'</div>', $url, 2, 0);
		} else {
			$this->member_msg(lang('m-173'));
		}
	}
	
	/**
     * 在线充值
     */
    public function add() {
	
		$money = (double)$this->input->get('money');
		
		if (IS_POST) {
			
			$pay = $this->input->post('pay');
			$money = (double)$this->input->post('money');
			
			if (!$money > 0) {
				$error = lang('m-175');
			} elseif (!$pay) {
				$error = lang('m-176');
			} else {
				if ($url = $this->pay_model->add_for_online($pay, $money)) {
					$this->member_msg(lang('m-177').'<div style="display:none">'.$url.'</div>', $url, 2, 0);
				} else {
					$error = lang('m-173');
				}
			}
			if (IS_AJAX) exit(dr_json(0, $error));
		}
		
		$data = $this->get_cache('member', 'setting', 'pay');
		if ($data) {
			foreach ($data as $i => $t) {
				if (!$t['use'] || !is_dir(APPPATH.'pay/'.$i.'/')) unset($data[$i]);
			}
		}
		
		$this->template->assign(array(
			'list' => $data,
			'money' => $money,
			'error' => $error,
		));
		$this->template->display('pay_add.html');
	}
	
	/**
     * 虚拟卡兑换
     */
    public function convert() {
		
		$error = '';
		if (IS_POST) {
			$score = abs((int)$this->input->post('score'));
			$money = (float)$score/SITE_CONVERT;
			if (!$score) {
				$error = lang('m-227');
			} elseif ($money > $this->member['money']) {
				$error = lang('m-210');
			} else {
				// 虚拟币增加
				$this->member_model->update_score(1, $this->uid, $score, '', 'lang,m-226');
				// 人民币减少
				$this->pay_model->add($this->uid, -$money, 'lang,m-228,'.$score);
				$this->member_msg(lang('m-225'), dr_url('pay/score'), 1);
			}
			if (IS_AJAX) exit(dr_json(0, $error));
		}
	
		$this->template->assign(array(
			'error' => $error,
		));
		$this->template->display('pay_convert.html');
	}
	
	/**
     * 卡密充值
     */
    public function card() {
	
		if (IS_POST) {
			$card = $this->input->post('card', TRUE);
			$password = (int)$this->input->post('password');
			if ($card && $password) {
				$data = $this->db
							 ->where('card', $card)
							 ->where('password', $password)
							 ->limit(1)
							 ->get('member_paycard')
							 ->row_array();
				if (!$data) {
					$error = lang('m-171');
				} elseif ($data['endtime'] < SYS_TIME) {
					$error = lang('m-169');
				} elseif ($data['uid']) {
					$error = lang('m-170');
				} else {
					if ($money = $this->pay_model->add_for_card($data['id'], $data['money'], $card)) {
						$this->member_msg(dr_lang('m-172', $data['money']), dr_member_url('pay/index'), 1);
					}
					$error = lang('m-172');
				}
			} else {
				$error = lang('m-168');
			}
			if (IS_AJAX) exit(dr_json(0, $error));
		}
	
		$this->template->assign(array(
			'card' => $card,
			'error' => $error,
		));
		$this->template->display('pay_card.html');
	}
	
	/**
     * 充值记录
     */
    public function index() {
	
		$this->db
             ->where('uid', $this->uid)
			 ->where('value>0')
             ->order_by('inputtime DESC');
			 
		switch ($this->input->get('where')) {
			case 1: // 一月内
				$this->db->where('inputtime BETWEEN '.strtotime('-30 day').' AND '. SYS_TIME);
				break;
			case 2: // 半年内
				$this->db->where('inputtime BETWEEN '.strtotime('-180 day').' AND '. SYS_TIME);
				break;
			case 3: // 一年内
				$this->db->where('inputtime BETWEEN '.strtotime('-365 day').' AND '. SYS_TIME);
				break;
			case 4: // 三年内
				$this->db->where('inputtime BETWEEN '.strtotime('-1000 day').' AND '. SYS_TIME);
				break;
			default: // 默认一周内
				$this->db->where('inputtime BETWEEN '.strtotime('-7 day').' AND '. SYS_TIME);
				break;
		}
		if ($this->input->get('kw')) $this->db->where('id', (int)$this->input->get('kw'));
		
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get('member_paylog_'.$this->member['tableid'])
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign(array(
				'type' => $this->get_cache('member', 'setting', 'pay'),
				'list' => $data
			));
			$this->template->display('pay_data.html');
		} else {
			$this->template->assign(array(
				'type' => $this->get_cache('member', 'setting', 'pay'),
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get('member_paylog_'.$this->member['tableid'])
							   ->result_array(),
				'moreurl' => 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&action=more'
			));
			$this->template->display('pay_index.html');
		}
	}
	
	/**
     * 消费记录
     */
    public function spend() {
	
		$this->db
             ->where('uid', $this->uid)
			 ->where('value<0')
             ->order_by('inputtime DESC');
			 
		switch ($this->input->get('where')) {
			case 1: // 一月内
				$this->db->where('inputtime BETWEEN '.strtotime('-30 day').' AND '. SYS_TIME);
				break;
			case 2: // 半年内
				$this->db->where('inputtime BETWEEN '.strtotime('-180 day').' AND '. SYS_TIME);
				break;
			case 3: // 一年内
				$this->db->where('inputtime BETWEEN '.strtotime('-365 day').' AND '. SYS_TIME);
				break;
			case 4: // 三年内
				$this->db->where('inputtime BETWEEN '.strtotime('-1000 day').' AND '. SYS_TIME);
				break;
			default: // 默认一周内
				$this->db->where('inputtime BETWEEN '.strtotime('-7 day').' AND '. SYS_TIME);
				break;
		}
		if ($this->input->get('kw')) $this->db->where('id', (int)$this->input->get('kw'));
		
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->db->dbprefix('member_paylog_'.$this->member['tableid']))
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign('list', $data);
			$this->template->display('pay_data.html');
		} else {
			$this->template->assign(array(
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get($this->db->dbprefix('member_paylog_'.$this->member['tableid']))
							   ->result_array(),
				'moreurl' => 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&action=more'
			));
			$this->template->display('pay_index.html');
		}
	}
	
	/**
     * 经验值
     */
    public function experience() {
	
		$this->db
             ->where('uid', $this->uid)
			 ->where('type', 0)
             ->order_by('inputtime DESC');
			 
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->db->dbprefix('member_scorelog_'.$this->member['tableid']))
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign('list', $data);
			$this->template->display('score_data.html');
		} else {
			$this->template->assign(array(
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get($this->db->dbprefix('member_scorelog_'.$this->member['tableid']))
							   ->result_array(),
				'moreurl' => 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&action=more'
			));
			$this->template->display('score.html');
		}
	}
	
	/**
     * 虚拟币
     */
    public function score() {
	
		$this->db
             ->where('uid', $this->uid)
			 ->where('type', 1)
             ->order_by('inputtime DESC');
			 
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->db->dbprefix('member_scorelog_'.$this->member['tableid']))
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign('list', $data);
			$this->template->display('score_data.html');
		} else {
			$this->template->assign(array(
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get($this->db->dbprefix('member_scorelog_'.$this->member['tableid']))
							   ->result_array(),
				'moreurl' => 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&action=more'
			));
			$this->template->display('score.html');
		}
	}
}