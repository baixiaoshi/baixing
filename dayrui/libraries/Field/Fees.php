<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Fees extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = '阅读收费'; // 字段名称
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
		$option['width'] = isset($option['width']) ? $option['width'] : '80%';
		return '<tr>
                    <th>'.lang('265').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">'.lang('290').'</div>
                    </td>
                </tr>
				<tr>
                    <th>适用范围：</th>
                    <td>
                    <font color="red">此字段只能用于模块内容</font>
                    </td>
                </tr>';
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
			foreach ($data as $i => $t) {
				$data[$i] = abs(intval($t));
			}
		} else {
			$data = '';
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
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_discount_tips">'.$cfg['validate']['tips'].'</div>' : '';
		
		// 显示框宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '80%';
		
		// 字段默认值
		$value = $value ? dr_string2array($value) : NULL;
		
		$str = '<input type="radio" name="data['.$name.'][use]" onclick="$(\'#dr_div_'.$name.'\').hide()" value="0" '.($value ? '' : 'checked').' />&nbsp;<label>关闭</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$str.= '<input type="radio" name="data['.$name.'][use]" onclick="$(\'#dr_div_'.$name.'\').show()" value="1" '.($value ? 'checked' : '').' />&nbsp;<label>开启</label>'.$tips;
		$str.= '<div class="dr_format_wrap" id="dr_div_'.$name.'" style="width:'.$width.(is_numeric($width) ? 'px' : '').';padding:5px;margin-top:10px;'.($value ? '' : 'display:none').'">';
		$str.= '<table width="100%">';
		
		$MEMBER = $this->ci->get_cache('member');
		foreach ($MEMBER['group'] as $group) {
			if ($group['id'] > 2) {
				$str.= '<tr>';
				$str.= '	<td align="left" width="250"><b>'.$group['name'].'</b></td>';
				$str.= '	<td align="left"><b>'.SITE_SCORE.'</b></td>';
				$str.= '</tr>';
				foreach ($group['level'] as $level) {
					$id = $group['id'].'_'.$level['id'];
					$str.= '<tr>';
					$str.= '<td align="left" width="250" style="padding-left:40px">'.$level['name'].'&nbsp;&nbsp;'.dr_show_stars($level['stars']).'</td>';
					$str.= '<td align="left">';
					$str.= '<input type="text" class="input-text" style="width:70px;" name="data['.$name.']['.$id.']" value="'.$value[$id].'" />';
					$str.= '</td>';
					$str.= '</tr>';
				}
			}
		}
		
		$str.= '</tr>';
		$str.= '<tr>';
		$str.= '	<td colspan="2" style="border:none;color:#777777">'.SITE_SCORE.'不填写或者0表示免费，只能填写正整数值</td>';
		$str.= '</tr>';
        $str.= '</table>';
		$str.= '</div>';
		
		return $this->input_format($name, $text, $str);
	}
	
}