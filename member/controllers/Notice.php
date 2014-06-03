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
	
class Notice extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 系统提醒
     */
    public function index() {
		$this->_notice(1);
    }
	
	/**
     * 会员提醒
     */
    public function member() {
		$this->_notice(2);
    }
	
	/**
     * 模块提醒
     */
    public function module() {
		$this->_notice(3);
    }
	
	/**
     * 应用提醒
     */
    public function app() {
		$this->_notice(4);
    }
	
	/**
     * 提醒查看
     */
    private function _notice($type) {
	
		$this->db
             ->where('uid', (int)$this->uid)
			 ->where('type', (int)$type)
             ->order_by('inputtime DESC');
			 
		if (IS_POST) {
			$this->db
				 ->where_in('id', $this->input->post('ids'))
				 ->delete('member_notice_'.$this->member['tableid']);
			exit(dr_json(1, lang('000')));
		}
		
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get('member_notice_'.$this->member['tableid'])
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign('list', $data);
			$this->template->display('notice_data.html');
		} else {
			$url = 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&action=more';
			$this->template->assign(array(
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get('member_notice_'.$this->member['tableid'])
							   ->result_array(),
				'moreurl' => $url,
				'searchurl' => $url,
			));
			// 更新新提醒
			$this->db
				 ->where('uid', (int)$this->uid)
				 ->where('type', (int)$type)
				 ->update('member_notice_'.$this->member['tableid'], array('isnew' => 0));
			$this->db
				 ->where('uid', (int)$this->uid)
				 ->delete('member_new_notice');
			$this->template->display('notice_index.html');
		}
		
	}
}