<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Color extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('276') : ''; // 字段名称
		$this->fieldtype = array(
			'VARCHAR' => 10
		); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'VARCHAR'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
		$option['value'] = isset($option['value']) ? $option['value'] : '';
		return '<tr>
                    <th>'.lang('277').'：</th>
                    <td>
                    <input id="field_default_value" type="text" class="input-text" size="20" value="'.$option['value'].'" name="data[setting][option][value]">
					'.$this->member_field_select().'
					<div class="onShow">'.lang('278').'</div>
                    </td>
                </tr>
				';
	}
	
	/**
	 * 创建sql语句
	 */
	public function create_sql($name, $option) {
		$sql = 'ALTER TABLE `{tablename}` ADD `'.$name.'` VARCHAR( 10 ) DEFAULT NULL';
		return $sql;
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
		return $value;
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
		// 字段显示名称
		$text = (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1 ? '<font color="red">*</font>' : '').'&nbsp;'.$cname.'：';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 字段默认值
		$value = $value ? $value : $this->get_default_value($cfg['option']['value']);
		$str = '';
		if (!defined('DAYRUI_COLOR_LD')) {
			$str.= '
			<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/spectrum/spectrum.js"></script>
			<link href="'.MEMBER_PATH.'statics/js/spectrum/spectrum.css" type="text/css" rel="stylesheet">';
			define('DAYRUI_COLOR_LD', 1);//防止重复加载JS
		}
		$str.= '
		<input type="hidden" name="data['.$name.']" id="dr_'.$name.'" value="' . $value . '" />
		<script type="text/javascript">
			$("#dr_'.$name.'").spectrum({
			preferredFormat: "hex",
			showInput: true,
			change: function(color) {
				$("#dr_'.$name.'").val(color);
			}
		});
		</script>'.$tips;
		return $this->input_format($name, $text, $str);
	}
	
}