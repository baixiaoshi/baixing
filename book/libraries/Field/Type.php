<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Type extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = '类别自定义'; // 字段名称
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
                    <th>'.lang('m-062').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">'.lang('m-096').'</div>
                    </td>
                </tr>
				<tr>
                    <th></th>
                    <td>
                    此字段需要跟类别字段（Mytype）配合使用
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
		$value = array();
		if ($data) {
			foreach ($data as $t) {
				$value[$t['id']] = array(
					'name' => $t['name'],
					'content' => $t['content'],
				);
			}
		}
		$this->ci->data[$field['ismain']][$field['fieldname']] = dr_array2string($value);
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
		// 显示框宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '80%';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '<div class="onTime" id="dr_'.$name.'_tips"></div>';
		// 字段默认值
		if ($value) {
			$value = dr_string2array($value);
		} else {
			$value = NULL;
		}
		// 当字段必填时，加入html5验证标签
		if (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1) $attr .= ' required="required"';
		// 禁止修改
		if (!IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit']) $attr.= ' disabled'; 
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
		if ($value) {
			$k = 0;
			foreach ($value as $id => $v) {
				$str.= '<li id="dr_items_'.$name.'_'.$k.'">';
				$str.= 'ID：<input type="text" '.$disabled.' class="input-text" style="width:50px;" value="'.$id.'" name="data['.$name.']['.$k.'][id]">&nbsp;&nbsp;';
				$str.= '名称：';
				$str.= '<input type="text" '.$disabled.' class="input-text" style="width:200px;" value="'.$v['name'].'" name="data['.$name.']['.$k.'][name]" />&nbsp;&nbsp;';
				$str.= '<a href="javascript:;" onclick="$(\'#dr_items_'.$name.'_'.$k.'\').remove()">'.lang('del').'</a>';
				$str.= '<textarea name="data['.$name.']['.$k.'][content]" placeholder="描述简介" style="height:90px; width:98%;margin-top:10px;">'.$v['content'].'</textarea>';
				$str.= '</li>';
				$k++;
			}
		}
		$str.= '		</ul>';
		$str.= '	</div>';
		$str.= '</fieldset>';
		$str.= '<div class="bk10"></div>';
		$str.= '<div class="picBut cu">';
		$str.= '	<a href="javascript:;" onClick="dr_add_property_'.$name.'()">添加</a>';
		$str.= '</div>';
		$str.= '<script type="text/javascript">
		$("#'.$name.'-sort-items").sortable();
		var id=$("#'.$name.'-sort-items li").size();
		function dr_add_property_'.$name.'() {
			id ++;
			var html = "<li id=\"dr_items_'.$name.'_"+id+"\">";
			html+= "ID：<input type=\"text\" class=\"input-text\" style=\"width:50px;\" value=\""+id+"\" name=\"data['.$name.']["+id+"][id]\">&nbsp;&nbsp;";
			html+= "名称：<input type=\"text\" class=\"input-text\" style=\"width:200px;\" value=\"\" name=\"data['.$name.']["+id+"][name]\">&nbsp;&nbsp;";
			html+= "<a href=\"javascript:;\" onclick=\"$(\'#dr_items_'.$name.'_"+id+"\').remove()\">'.lang('del').'</a>";
			html+= "<textarea name=\"data['.$name.']["+id+"][content]\" placeholder=\"描述简介\" style=\"height:90px; width:98%;margin-top:10px;\"></textarea>";
			html+= "</li>";
			$("#'.$name.'-sort-items").append(html);
		}
		</script>'.$tips;
		return $this->input_format($name, $text, $str);
	}
	
}