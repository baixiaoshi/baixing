<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Linkage extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('185') : ''; // 字段名称
		$this->fieldtype = array(
			'mediumint' => 8
		); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'mediumint'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
		$linkage = isset($option['linkage']) ? $option['linkage'] : '';
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
		$sql = 'ALTER TABLE `{tablename}` ADD `'.$name.'` mediumint( 8 ) UNSIGNED NULL';
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
		// 表单宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '150';
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 联动菜单缓存
		$linkage = $this->ci->dcache->get('linkage-'.SITE_ID.'-'.$cfg['option']['linkage']);
		$linklevel = $this->ci->dcache->get('linklevel-'.SITE_ID);
		// 
		$value = $value ? $value : $this->get_default_value($cfg['option']['value']);
		$linklevel = $linklevel[$cfg['option']['linkage']] + 1;
		$str = '<input type="hidden" name="data['.$name.']" id="dr_'.$name.'" value="'.(int)$value.'">';
		if(!defined('FINECMS_LINKAGE_INIT_LD')) {
			define('FINECMS_LINKAGE_INIT_LD', 1);
			$str.= '<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/jquery.ld.js"></script>';
		}
		$level = 1;
		$default = '';
		if ($value) {
			$pids = substr($linkage[$value]['pids'], 2);
			$level = substr_count($pids, ',') + 1;
			$default = !$pids ? '["'.$value.'"]' : '["'.str_replace(',', '","', $pids).'","'.$value.'"]';
		}
		// 禁止修改
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		// 输出默认菜单
		for ($i = 1; $i <= $linklevel; $i++) {
			$style = $i > $level ? 'style="display:none"' : '';
			$str.= '<select class="finecms-select-'.$name.'" '.$disabled.' name="'.$name.'-'.$i.'" id="'.$name.'-'.$i.'" width="100" '.$style.'><option value=""> -- </option></select>&nbsp;&nbsp;';
		}
		$str.= '
		<script type="text/javascript">
			$(function(){
				var $ld5 = $(".finecms-select-'.$name.'");					  
				$ld5.ld({ajaxOptions:{"url":memberpath+"index.php?c=api&m=linkage&code='.$cfg['option']['linkage'].'"},defaultParentId:0})	 
				var ld5_api = $ld5.ld("api");
				ld5_api.selected('.$default.');
				$ld5.bind("change",onchange);
				function onchange(e){
					var $target = $(e.target);
					var index = $ld5.index($target);
					//$("#'.$name.'-'.$i.'").remove();
					$("#dr_'.$name.'").val($ld5.eq(index).show().val());
					index ++;
					$ld5.eq(index).show();
				}
			})
		</script>'.$tips;
		return $this->input_format($name, $text, $str);
	}
}