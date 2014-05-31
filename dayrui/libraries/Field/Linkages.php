<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Linkages extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = '联动菜单多选'; // 字段名称
		$this->fieldtype = array(
			'VARCHAR' => 255
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
		$linkage = isset($option['linkage']) ? $option['linkage'] : '';
		$option['width'] = isset($option['width']) ? $option['width'] : '80%';
		$str = '<select name="data[setting][option][linkage]">';
		$data = $this->ci->db->get('linkage')->result_array();
		if ($data) {
			foreach ($data as $t) {
				$str.= '<option value="'.$t['code'].'" '.($linkage == $t['code'] ? 'selected' : '').'> '.$t['name'].' </option>';
			}
		}
		$str.= '</select>';
		return '<tr>
                    <th><font color="red">*</font>&nbsp;'.lang('298').'：</th>
                    <td>'.$str.' </td>
                </tr>
				<tr>
                    <th>'.lang('265').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">'.lang('290').'</div>
                    </td>
                </tr>
				';
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
		
		$data = $this->ci->post[$field['fieldname']];
		//重建键值
		$value = '';
		if ($data) {
			$value = ',';
			foreach ($data as $t) {
				$value.= $t.',';
			}
		}
		$this->ci->data[$field['ismain']][$field['fieldname']] = $value;
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
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '80%';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 联动菜单缓存
		$linkage = $this->ci->dcache->get('linkage-'.SITE_ID.'-'.$cfg['option']['linkage']);
		$linklevel = $this->ci->dcache->get('linklevel-'.SITE_ID);
		$linklevel = $linklevel[$cfg['option']['linkage']] + 1;
		
		//
		$tpl = '<span id="files_'.$name.'_{id}" style="padding-right:10px;"><a href="javascript:;" onclick="dr_remove_file(\''.$name.'\',\'{id}\')"><img align="absmiddle" src="'.SITE_URL.'dayrui/statics/images/b_drop.png"></a>&nbsp;{name}<input id="dr_data_'.$name.'_{value}" type="hidden" name="data['.$name.'][]" value="{value}"></span>';
		$str = '<fieldset class="blue pad-10" style="width:'.$width.(is_numeric($width) ? 'px' : '').';">
			<legend>多项选择</legend>
			<div class="picList" id="dr_list_'.$name.'">';
		if ($value) {
			$value = trim($value, ',');
			$value = explode(',', $value);
			foreach ($value as $id => $value) {
				$str.= str_replace(
					array('{id}', '{value}', '{name}'),
					array($id, $value, $linkage[$value]['name']),
					$tpl
				);
			}
		}	
		$str.= '</div><div class="bk10"></div>';
		$str.= '<input type="hidden" id="dr_'.$name.'_value" value="">';
		$str.= '<input type="hidden" id="dr_'.$name.'_text" value="">';
		if(!defined('FINECMS_LINKAGE_INIT_LD')) {
			define('FINECMS_LINKAGE_INIT_LD', 1);
			$str.= '<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/jquery.ld.js"></script>';
		}
		$level = 1;
		$default = '';
		// 禁止修改
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		// 输出默认菜单
		for ($i = 1; $i <= $linklevel; $i++) {
			$style = $i > $level ? 'style="display:none"' : '';
			$str.= '<div style="float:left">
			<select multiple class="finecms-select-'.$name.'" '.$disabled.' name="'.$name.'-'.$i.'" id="'.$name.'-'.$i.'" '.$style.'></select>
			&nbsp;&nbsp;&nbsp;
			</div>';
		}
		$str.= '<div class="picBut"><a href="javascript:;" onClick="dr_add_linkage_'.$name.'()">添加</a></div>';
		$str.= '
		<script type="text/javascript">
			$(function(){
				var $ld5 = $(".finecms-select-'.$name.'");					  
				$ld5.ld({
					ajaxOptions:{"url":memberpath+"index.php?c=api&m=linkage&code='.$cfg['option']['linkage'].'"},
					defaultParentId:0,
					drevent:"click"
				});	 
				var ld5_api = $ld5.ld("api");
				ld5_api.selected('.$default.');
				$ld5.bind("change",onchange);
				function onchange(e){
					var $target = $(e.target);
					var index = $ld5.index($target);
					//$("#'.$name.'-'.$i.'").remove();
					$("#dr_'.$name.'_value").val($ld5.eq(index).show().val());
					$("#dr_'.$name.'_text").val($ld5.eq(index).show().find("option:selected").text());
					index ++;
					$ld5.eq(index).show();
				}
			})
			var c = $("#dr_list_'.$name.' span").size();
			function dr_add_linkage_'.$name.'() {
				var id = c * 10;
				var value = $("#dr_'.$name.'_value").val();
				var text = $("#dr_'.$name.'_text").val();
				if ($("#dr_data_'.$name.'_"+value).size() > 0) {
					dr_tips("抱歉，已经存在了");
					return;
				}
				var html = \''.addslashes(str_replace(array("\r", "\n", "\t", chr(13)), '', $tpl)).'\';
				html = html.replace(/{id}/g, id);
				html = html.replace(/{name}/g, text);
				html = html.replace(/{value}/g, value);
				$("#dr_list_'.$name.'").append(html);
				c++;
			}
		</script>
		</fieldset>'.$tips;
		return $this->input_format($name, $text, $str);
	}
}