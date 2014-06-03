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

require APPPATH.'core/D_Order.php';
 
class Order extends D_Order {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 我的订单
     */
	public function index() {
	
		if (IS_POST && $this->input->post('action')) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'del') {
				if (!$this->is_auth(APP_DIR.'admin/format/del')) exit(dr_json(0, lang('160')));
				$this->link
					 ->where_in('id', $ids)
					 ->delete($this->order_model->tablename);
				$this->link
					 ->where_in('fid', $ids)
					 ->delete($this->order_model->dataname);
				$this->order_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				if (!$this->is_auth(APP_DIR.'admin/format/edit')) exit(dr_json(0, lang('160')));
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->link
						 ->where('id', $id)
						 ->update($this->order_model->tablename, $_data[$id]);
				}
				$this->order_model->cache();
				exit(dr_json(1, lang('000')));
			}			
		}
	
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) $param['search'] = 1;
		
		// 数据库中分页查询
		list($data, $param)	= $this->order_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->order_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$this->template->assign(array(
			'list' => $data,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/order/index', $param), $param['total']),
			'param'	=> $_param,
			'menu' => $this->get_menu(array(
				lang('my-31') => APP_DIR.'/admin/order/index'
			))
		));
		$this->template->display('order_index.html');
	}
	
	/**
     * 卖家改价
     */
	public function price() {
		$this->_member_price();
	}
	
	/**
     * 卖家发货
     */
	public function send() {
		$this->_member_send();
	}
	
	/**
     * 订单详情
     */
	public function show() {
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('my-31') => APP_DIR.'/admin/order/index',
				lang('my-38') => APP_DIR.'/admin/order/show/id/'.$this->input->get('id'),
			))
		));
		$this->_member_show();
	}
}