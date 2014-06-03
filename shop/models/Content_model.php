<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.5
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

require FCPATH.'dayrui/core/C_Model.php';
 
class Content_model extends C_Model {

	/**
	 * 构造函数
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 用于商品订单
	 */
	public function get_item_data($id) {
	
		if (!$id) return NULL;
		
		$data1 = $this->link // 主表
					  ->where('id', $id)
					  ->where('status', 9)
					  ->where('onsale', 1)
					  ->select('id,catid,tableid,title,thumb,price,uid,author,url,quantity')
					  ->limit(1)
					  ->get($this->prefix)
					  ->row_array();
		if (!$data1) return NULL;
		
		$data2 = $this->link // 副表
					  ->where('id', $id)
					  ->select('discount,format')
					  ->limit(1)
					  ->get($this->prefix.'_data_'.$data1['tableid'])
					  ->row_array();
		$data1['format'] = dr_string2array($data2['format']);
		$data1['discount'] = dr_string2array($data2['discount']);
		
		return $data1;
	}
	
}