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
		$this->_member(0);
	}
	
	/**
     * 商品订单
     */
	public function item() {
		$this->_member(1);
	}
	
	/**
     * 买家付款
     */
	public function pay() {
		$this->_member_pay();
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
     * 买家确认收货
     */
	public function confirm() {
		$this->_member_confirm();
	}
	
	/**
     * 订单详情
     */
	public function show() {
		$this->_member_show();
	}
}