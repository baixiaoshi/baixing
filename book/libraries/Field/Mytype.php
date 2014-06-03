<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Mytype extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = '类别'; // 字段名称
		$this->fieldtype = array(
			'TEXT' => ''
		); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'TEXT'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
	
		return '<tr>
                    <th></th>
                    <td>
                    此字段需要跟类别自定义字段（Type）配合使用
                    </td>
                </tr>';
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
		return $value;
	}
	
	/**
	 * 字段入库值
	 */
	public function insert_value($field) {
		$this->ci->data[$field['ismain']][$field['fieldname']] = (int)$this->ci->post[$field['fieldname']];
	}
	
	/**
	 * 字段表单输入
	 *
	 * @param	string	$cname	字段别名
	 * @param	string	$name	字段名称
	 * @param	array	$cfg	字段配置
	 * @param	string	$value	值
	 * @return  string
	 */
	public function input($cname, $name, $cfg, $value = NULL, $id = 0) {
		if (!$this->ci->content['type']) return '';
		// 字段显示名称
		$text = '<font color="red">*</font>&nbsp;'.$cname.'：';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '<div class="onTime" id="dr_'.$name.'_tips"></div>';
		// 当字段必填时，加入html5验证标签
		$attr .= ' required="required"';
		// 表单选项
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		$str = '<select '.$disabled.' name="data['.$name.']" id="dr_'.$name.'" '.$attr.' >';
		$value = $value ? $value : (int)$_GET['type'];
		foreach ($this->ci->content['type'] as $i => $v) {
			$selected = $i==$value ? ' selected' : '';
			$str.= '<option value="'.$i.'" ' . $selected . '>'.$v['name'].'</option>';
		}
		$str.= '</select>'.$tips;
		return $this->input_format($name, $text, $str);
	}
	
}