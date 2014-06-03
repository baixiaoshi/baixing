<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/Dvalidate.php
 */

/**
 * 表单数据校验
 */

class Dvalidate {
	
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
	 * @return  true不通过 , false通过
	 */
	public function __test($value,  $p1) {
		return TRUE;
	}
	
	/**
	 * 验证会员名称是否存在
	 *
	 * @param   $value	当前字段提交的值
	 * @param   自定义字段参数1
	 * @param   自定义字段参数2
	 * @param   自定义字段参数3 ...
	 * @return  true不通过 , false通过
	 */
	public function check_member($value) {
		if (!$value) return TRUE;
		return $this->ci->db->where('username', $value)->count_all_results($this->ci->db->dbprefix('member')) ? FALSE : TRUE;
	}
	
	/**
	 * 验证商品规格字段
	 *
	 * @param   $value	当前字段提交的值
	 * @return  true不通过 , false通过
	 */
	public function format($value) {
		// 当栏目无商品规格就不验证
		if (!isset($this->ci->format[$_POST['catid']]) || !$this->ci->format[$_POST['catid']]) return FALSE;
		return FALSE;
	}
}