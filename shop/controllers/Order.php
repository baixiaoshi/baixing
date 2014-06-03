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
     * 购物车
     */
    public function cart() {
        $this->_home_cart();
    }
	
	/**
     * 加入购物车
     */
    public function add() {
        $this->_add_cart();
    }
	
	/**
     * 移出购物车
     */
    public function del() {
        $this->_del_cart();
    }
	
	/**
     * 订单购买确认
     */
    public function buy() {
        $this->_buy();
    }
	
}