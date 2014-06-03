<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Property extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = lang('176'); // 字段名称
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
		
		$width = isset($option['width']) ? $option['width'] : '80%';
		unset($option['width']);
		
		$str = '
		<tr>
			<th>'.lang('265').'：</th>
			<td>
			<input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$width.'">
			<div class="onShow">'.lang('290').'</div>
			</td>
		</tr>
		<tr id="dr_option_0" class="dr_option">
			<th><a href="javascript:;" onclick="dr_add_option()" style="color:blue">[+]</a>&nbsp;'.lang('177').'：</th>
			<td>'.lang('184').'</td>
		</tr>';
		if ($option) {
			foreach ($option as $i => $t) {
				$str.= '<tr id="dr_option_'.$i.'" class="dr_option">';
				$str.= '<th><a href="javascript:;" onclick="dr_add_option()" style="color:blue">[+]</a>&nbsp;'.lang('178').'：</th>';
				$str.= '<td><input type="text" name="data[setting][option]['.$i.'][name]" value="'.$t['name'].'" style="width:140px;" class="input-text" />';
				$str.= lang('179').'：<select name="data[setting][option]['.$i.'][type]">';
				$str.= '<option value="1" '.($t['type'] == 1 ? "selected" : "").'> - '.lang('180').' - </option>';
				$str.= '<option value="2" '.($t['type'] == 2 ? "selected" : "").'> - '.lang('181').' - </option>';
				$str.= '<option value="3" '.($t['type'] == 3 ? "selected" : "").'> - '.lang('182').' - </option>';
				$str.= '</select>&nbsp;&nbsp;';
				$str.= lang('183').'：<input type="text" name="data[setting][option]['.$i.'][value]" value="'.$t['value'].'" style="width:400px;" class="input-text">&nbsp;&nbsp;<a onclick="$(\'#dr_option_'.$i.'\').remove()" href="javascript:;">'.lang('del').'</a>';
				$str.= '</td></tr>';
			}
		}
		$str.= '
		<script type="text/javascript">
		var id=$(".dr_option").size();
		function dr_add_option() {
			id ++;
			var html = "";
			html+= "<tr id=\"dr_option_"+id+"\" class=\"dr_option\">";
			html+= "<th><a href=\"javascript:;\" onclick=\"dr_add_option()\" style=\"color:blue\">[+]</a>&nbsp;'.lang('178').'：</th>";
			html+= "<td>";
			html+= "<input type=\"text\" name=\"data[setting][option]["+id+"][name]\" value=\"\" style=\"width:140px;\" class=\"input-text\" />";
			html+= "'.lang('179').'：<select name=\"data[setting][option]["+id+"][type]\">";
			html+= "<option value=\"1\"> - '.lang('180').' - </option>";
			html+= "<option value=\"2\"> - '.lang('181').' - </option>";
			html+= "<option value=\"3\"> - '.lang('182').' - </option>";
			html+= "</select>&nbsp;&nbsp;";
			html+= "'.lang('183').'：<input type=\"text\" name=\"data[setting][option]["+id+"][value]\" value=\"\" style=\"width:400px;\" class=\"input-text\">&nbsp;&nbsp;<a onclick=\"$(\'#dr_option_"+id+"\').remove()\" href=\"javascript:;\">'.lang('del').'</a>";
			html+= "</td>";
			html+= "</tr>";
			$("#dr_option").append(html);
		}
		</script>
		';
		return $str;
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
		$this->ci->data[$field['ismain']][$field['fieldname']] = dr_array2string($this->ci->post[$field['fieldname']]);
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
		// 显示框宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '80%';
		unset($cfg['option']['width']);
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 字段默认值
		$value = $value ? dr_string2array($value) : array();
		// 禁止修改
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		$str = '';
		// 加载js
		if (!defined('FINECMS_FILES_LD')) {
			$str.= '<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/jquery-ui.min.js"></script>';
			define('FINECMS_FILES_LD', 1);//防止重复加载JS
		}
		$str.= '<fieldset class="blue pad-10" style="width:'.$width.(is_numeric($width) ? 'px' : '').';">';
        $str.= '	<legend>'.$cname.'</legend>';
        $str.= '	<div class="picList" id="list_'.$name.'_property">';
		$str.= '		<ul id="'.$name.'-sort-items">';
		$i = 1;
		if (isset($cfg['option']) && $cfg['option']) { // 默认属性选项
			foreach ($cfg['option'] as $i => $t) {
				$str.= '<li id="dr_items_'.$name.'_'.$i.'">';
				$str.= '属性：<input type="text" '.$disabled.' class="input-text" style="width:140px;" value="'.$t['name'].'" name="data['.$name.']['.$i.'][name]">&nbsp;&nbsp;';
				$str.= '值：';
				switch ($t['type']) {
					case 1:
						$v = $value[$i]['value'] ? $value[$i]['value'] : $t['value'];
						$str.= '<input '.$disabled.' type="text" class="input-text" style="width:300px;" value="'.$v.'" name="data['.$name.']['.$i.'][value]" />';
						break;
					case 2:
						$v = @explode(',', $t['value']);
						$str.= '<select '.$disabled.' name="data['.$name.']['.$i.'][value]">';
						$str.= '<option value=""> -- </option>';
						if ($v) {
							foreach ($v as $c) {
								$selected = isset($value[$i]['value']) && $value[$i]['value'] == $c ? 'selected' : '';
								$str.= '<option value="'.$c.'" '.$selected.'> '.$c.' </option>';
							}
						}
						$str.= '</select>';
						break;
					case 3:
						$v = @explode(',', $t['value']);
						if ($v) {
							foreach ($v as $c) {
								$selected = isset($value[$i]['value']) && @in_array($c, $value[$i]['value']) ? 'checked' : '';
								$str.= '<input '.$disabled.' type="checkbox" name="data['.$name.']['.$i.'][value][]" value="'.$c.'" ' . $selected . ' />'.$c.'&nbsp;&nbsp;&nbsp;';
							}
						}
				}
			}
		}
		// 剩下自定义属性
		if ($value) {
			foreach ($value as $k => $t) {
				if ($k > $i) {
					$str.= '<li id="dr_items_'.$name.'_'.$k.'">';
					$str.= '属性：<input type="text" '.$disabled.' class="input-text" style="width:140px;" value="'.$t['name'].'" name="data['.$name.']['.$k.'][name]">&nbsp;&nbsp;';
					$str.= '值：';
					$str.= '<input type="text" '.$disabled.' class="input-text" style="width:300px;" value="'.$t['value'].'" name="data['.$name.']['.$k.'][value]" />';
					$str.= '<a href="javascript:;" onclick="$(\'#dr_items_'.$name.'_'.$k.'\').remove()">'.lang('del').'</a></li>';
				}
			}
		}
		
		$str.= '		</ul>';
		$str.= '	</div>';
		$str.= '</fieldset>';
		$str.= '<div class="bk10"></div>';
		$str.= '<div class="picBut cu">';
		$str.= '	<a href="javascript:;" onClick="dr_add_property_'.$name.'()">添加属性</a>';
		$str.= '</div>';
		$str.= '<script type="text/javascript">
		$("#'.$name.'-sort-items").sortable();
		function dr_add_property_'.$name.'() {
			var id=$("#'.$name.'-sort-items li").size() * 10;
			var html = "<li id=\"dr_items_'.$name.'_"+id+"\">";
			html+= "属性：<input type=\"text\" class=\"input-text\" style=\"width:140px;\" value=\"\" name=\"data['.$name.']["+id+"][name]\">&nbsp;&nbsp;";
			html+= "值：<input type=\"text\" class=\"input-text\" style=\"width:300px;\" value=\"\" name=\"data['.$name.']["+id+"][value]\">&nbsp;&nbsp;";
			html+= "<a href=\"javascript:;\" onclick=\"$(\'#dr_items_'.$name.'_"+id+"\').remove()\">'.lang('del').'</a></li>";
			$("#'.$name.'-sort-items").append(html);
		}
		</script>'.$tips;
		return $this->input_format($name, $text, $str);
	}
	
}