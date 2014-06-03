<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Date extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('279') : ''; // 字段名称
		$this->fieldtype = array(
			'INT' => 10
		); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'INT'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
		$option['width'] = isset($option['width']) ? $option['width'] : 150;
		$option['format'] = isset($option['format']) ? $option['format'] : '';
		return '<tr>
                    <th>'.lang('265').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">px</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('280').'：</th>
                    <td>
                    <input type="text" class="input-text" size="30" name="data[setting][option][format]" value="'.$option['format'].'">
					<div class="onShow">'.lang('281').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('277').'：</th>
                    <td>
                    <input id="field_default_value" type="text" class="input-text" size="20" value="'.$option['value'].'" name="data[setting][option][value]">
					'.$this->member_field_select().'
					<div class="onShow">'.lang('278').'</div>
                    </td>
                </tr>
				'.$this->field_type($option['fieldtype'], $option['fieldlength']);
	}
	
	/**
	 * 创建sql语句
	 */
	public function create_sql($name, $option) {
		// 无符号int 10位
		$sql = 'ALTER TABLE `{tablename}` ADD `'.$name.'` INT( 10 ) UNSIGNED NULL';
		return $sql;
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
		return dr_date($value, NULL, 'red');
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
		// 表单宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '150';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 字段默认值
		if (is_null($value)) {
			$value = $cfg['option']['value'] === '0' ? 0 : SYS_TIME;
		} else {
			$value = $value ? $value : (strlen($value) == 1 && $value == 0 ? '' : SYS_TIME);
		}
		$format = isset($cfg['option']['format']) && $cfg['option']['format'] ? $cfg['option']['format'] : SITE_TIME_FORMAT;
		$format = str_replace(array('i', 's'), array('M', 'S'), $format); //%Y-%m-%d %H:%M:%S
		$format = @preg_replace('/([a-z]+)/i', '%$1', $format);
		$show = $value ? date(str_replace(array('%','M','S'), array('','i','s'), $format), $value) : '';
		
		$str = '';
		if (!defined('DAYRUI_DATE_LD')) {
			$str.= '
			<link href="'.MEMBER_PATH.'statics/js/calendar/jscal2.css" type="text/css" rel="stylesheet">
			<link href="'.MEMBER_PATH.'statics/js/calendar/border-radius.css" type="text/css" rel="stylesheet">
			<link href="'.MEMBER_PATH.'statics/js/calendar/win2k.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/calendar/calendar.js"></script>
			<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/calendar/'.SITE_LANGUAGE.'.js"></script>';
			define('DAYRUI_DATE_LD', 1);//防止重复加载JS
		}
		$str.= '
		<input type="hidden" value="'.$value.'" name="data['.$name.']" id="dr_'.$name.'" '.$attr.' />
		<input type="text" readonly="" class="date input-text" style="width:' . $width . 'px;" value="' . $show . '" id="calendar_' . $name . '" />
		<script type="text/javascript">
			Calendar.setup({
			weekNumbers : true,
			inputField  : "calendar_' . $name . '",
			trigger     : "calendar_' . $name . '",
			dateFormat  : "' . $format . '",
			showTime    : true,
			minuteStep  : 1,
			onSelect    : function() {
				this.hide();
				var time = $("#calendar_' . $name . '").val();
				var date = (new Date(Date.parse(time.replace(/-/g,"/")))).getTime() / 1000;
				$("#dr_' . $name . '").val(date);
			}
			});
		</script>'.$tips;
		return $this->input_format($name, $text, $str);
	}
}