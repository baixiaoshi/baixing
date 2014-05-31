<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/Dfilter.php
 */

/**
 * 表单数据过滤
 */

class Dfilter {

	private $ci;

	/**
     * 构造函数
     */
    public function __construct() {
		$this->ci = &get_instance();
    }

	/**
	 * 举例测试
	 *
	 * @param   $value	当前字段提交的值
	 * @param   自定义字段参数1
	 * @param   自定义字段参数2
	 * @param   自定义字段参数3 ...
	 * @return  返回处理后的$value值
	 */
	public function __test($value,  $p1) {
		return $value;
	}
	
	/**
	 * 商品规格字段（目的是根据规格字段获取商品价格与数量字段内容）
	 *
	 * @param   $value	当前字段提交的值
	 * @return  返回处理后的$value值
	 */
	public function format($value) {
		
		$FORMAT = $this->ci->get_cache('format-'.SITE_ID);
		
		// 当栏目无商品规格就返回空
		if (!isset($FORMAT[$_POST['catid']]) || !$FORMAT[$_POST['catid']]) return FALSE;
		
		$_POST['data']['price'] = min($value['price']);
		$_POST['data']['quantity'] = array_sum($value['quantity']);
		
		return $value;
	}
	
	/**
	 * 折扣处理
	 *
	 * @param   $value		当前字段提交的值
	 * @param   $discount	折扣规则数组
	 * @return  返回处理后的$value值
	 */
	public function discount($value) {
		return $value;
	}
}
