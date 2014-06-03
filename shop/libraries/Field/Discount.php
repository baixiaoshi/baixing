<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Discount extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = '商品折扣'; // 字段名称
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
		return '';
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
		return dr_string2array($value);
	}
	
	/**
	 * 字段入库值
	 */
	public function insert_value($field) {
	
		$data = $this->ci->post[$field['fieldname']];
		
		if ($data['use']) {
			$zk = 0;
			foreach ($data as $i => $t) {
				if (strpos($i, '_') !== FALSE && $t) {
					$zk = 1;
					break;
				}
			}
			if (!$zk || !$data['star'] || !$data['end']) {
				$data = NULL;
			}
		} else {
			$data = NULL;
		}
		
		$this->ci->data[$field['ismain']][$field['fieldname']] = dr_array2string($data);
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
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_discount_tips">'.$cfg['validate']['tips'].'</div>' : '<div class="onTime" id="dr_discount_tips"></div>';
		// 字段默认值
		$value = $value ? dr_string2array($value) : NULL;
		$str = '<input type="radio" name="data[discount][use]" onclick="$(\'#dr_div_discount\').hide()" value="0" '.($value ? '' : 'checked').' />&nbsp;不折扣&nbsp;&nbsp;&nbsp;&nbsp;';
		$str.= '<input type="radio" name="data[discount][use]" onclick="$(\'#dr_div_discount\').show()" value="1" '.($value ? 'checked' : '').' />&nbsp;折扣';
		$str.= '<div class="dr_format_wrap" id="dr_div_discount" style="margin-top:10px;'.($value ? '' : 'display:none').'"><table width="100%">';
		$MEMBER = $this->ci->get_cache('member');
		foreach ($MEMBER['group'] as $group) {
			if ($group['id'] > 2) {
				$str.= '<tr>';
				$str.= '	<td align="left" width="250">'.$group['name'].'</td>';
				$str.= '	<td align="left"></td>';
				$str.= '</tr>';
				foreach ($group['level'] as $level) {
					$id = $group['id'].'_'.$level['id'];
					$str.= '<tr>';
					$str.= '<td align="left" width="250" style="padding-left:40px">'.$level['name'].'&nbsp;&nbsp;'.dr_show_stars($level['stars']).'</td>';
					$str.= '<td align="left">';
					$str.= '<input type="text" class="input-text" size="5" name="data[discount]['.$id.']" value="'.$value[$id].'" />';
					$str.= '</td>';
					$str.= '</tr>';
				}
			}
		}
		if (!defined('DAYRUI_DATE_LD')) {
			$str.= '
			<link href="'.MEMBER_PATH.'statics/js/calendar/jscal2.css" type="text/css" rel="stylesheet">
			<link href="'.MEMBER_PATH.'statics/js/calendar/border-radius.css" type="text/css" rel="stylesheet">
			<link href="'.MEMBER_PATH.'statics/js/calendar/win2k.css" type="text/css" rel="stylesheet">
			<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/calendar/calendar.js"></script>
			<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/calendar/'.SITE_LANGUAGE.'.js"></script>';
			define('DAYRUI_DATE_LD', 1);//防止重复加载JS
		}
		$str.= '<tr>';
		$str.= '	<td colspan="2">开始时间：<input type="hidden" value="'.$value['star'].'" name="data[discount][star]" id="dr_discount_star" />
		<input type="text" readonly="" class="date input-text" style="width:150px;" value="'.($value['star'] ? date('Y-m-d H:i:s', $value['star']) : '').'" id="calendar_discount_star" />
		<script type="text/javascript">
			Calendar.setup({
			weekNumbers : true,
			inputField  : "calendar_discount_star",
			trigger     : "calendar_discount_star",
			dateFormat  : "%Y-%m-%d %H:%M:%S",
			showTime    : true,
			minuteStep  : 1,
			onSelect    : function() {
				this.hide();
				var time = $("#calendar_discount_star").val();
				var date = (new Date(Date.parse(time.replace(/-/g,"/")))).getTime() / 1000;
				$("#dr_discount_star").val(date);
			}
			});
		</script><div class="onShow">必填选项</div></td>';
		$str.= '</tr>';
		$str.= '<tr>';
		$str.= '	<td colspan="2">结束时间：<input type="hidden" value="'.$value['end'].'" name="data[discount][end]" id="dr_discount_end" />
		<input type="text" readonly="" class="date input-text" style="width:150px;" value="'.($value['end'] ? date('Y-m-d H:i:s', $value['end']) : '').'" id="calendar_discount_end" />
		<script type="text/javascript">
			Calendar.setup({
			weekNumbers : true,
			inputField  : "calendar_discount_end",
			trigger     : "calendar_discount_end",
			dateFormat  : "%Y-%m-%d %H:%M:%S",
			showTime    : true,
			minuteStep  : 1,
			onSelect    : function() {
				this.hide();
				var time = $("#calendar_discount_end").val();
				var date = (new Date(Date.parse(time.replace(/-/g,"/")))).getTime() / 1000;
				$("#dr_discount_end").val(date);
			}
			});
		</script><div class="onShow">必填选项</div></td>';
		$str.= '</tr>';
		$str.= '<tr>';
		$str.= '	<td colspan="2" style="border:none;color:#777777">折扣值不填写或者0表示不打折，例如1表示一折；9.5表示九五折</td>';
		$str.= '</tr>';
        $str.='</table></div>';
		return $this->input_format($name, $text, $str);
	}
	
}