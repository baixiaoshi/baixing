<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Files extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('289') : ''; // 字段名称
		$this->fieldtype = array('TEXT' => ''); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'TEXT'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
	
		$option['count'] = isset($option['count']) ? $option['count'] : 2;
		$option['width'] = isset($option['width']) ? $option['width'] : '80%';
		$option['fieldtype'] = isset($option['fieldtype']) ? $option['fieldtype'] : '';
		$option['uploadpath'] = isset($option['uploadpath']) ? $option['uploadpath'] : '';
		$option['fieldlength'] = isset($option['fieldlength']) ? $option['fieldlength'] : '';
		
		return '<tr>
                    <th>'.lang('265').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">'.lang('290').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('283').'：</th>
                    <td>
                    <input id="field_default_value" type="text" class="input-text" size="10" value="'.$option['size'].'" name="data[setting][option][size]">
					<div class="onShow">'.lang('284').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('291').'：</th>
                    <td>
                    <input id="field_default_value" type="text" class="input-text" size="10" value="'.$option['count'].'" name="data[setting][option][count]">
					<div class="onShow">'.lang('292').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('285').'：</th>
                    <td>
                    <input type="text" class="input-text" size="40" name="data[setting][option][ext]" value="'.$option['ext'].'">
					<div class="onShow">'.lang('286').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('287').'：</th>
                    <td>
                    <input type="text" class="input-text" size="50" name="data[setting][option][uploadpath]" value="'.$option['uploadpath'].'"><br>
					<font color="gray">'.lang('288').'</font>
                    </td>
                </tr>';
	}
	
	/**
	 * 字段输出
	 */
	public function output($value) {
	
		$data = array();
		$value = dr_string2array($value);
		if (!$value) return $data;
		if (!isset($value['file'])) return $value;
		
		foreach ($value['file'] as $i => $file) {
			$data[] = array(
				'file' => $file, // 对应文件或附件id
				'title' => $value['title'][$i] // 对应标题描述
			);
		}
		
		return $data;
	}
	
	/**
	 * 获取附件id
	 */
	public function get_attach_id($value) {
	
		$data = array();
		if (!$value) return $data;
		if (!isset($value['file'])) return $value;
		
		foreach ($value['file'] as $i => $file) {
			if (is_numeric($file)) $data[] = $file;
		}
		
		return $data;
	}
	
	/**
	 * 字段入库值
	 */
	public function insert_value($field) {
		$data = $this->ci->post[$field['fieldname']];
		// 第一张作为缩略图
		if (isset($_POST['data']['thumb']) && !$_POST['data']['thumb'] && $data['file'][0]) {
			$this->ci->data[1]['thumb'] = $data['file'][0];
		}
		$this->ci->data[$field['ismain']][$field['fieldname']] = dr_array2string($data);
	}
	
	/**
	 * 附件处理
	 */
	public function attach($data, $_data) {
		
		$_data = dr_string2array($_data);
		
		// 新旧数据都无附件就跳出
		if (!$data['file'] && !$_data['file']) {
			return NULL;
		}
		
		// 新旧数据都一样时表示没做改变就跳出
		if ($data['file'] === $_data['file']) {
			return NULL;
		}
		
		// 当无新数据且有旧数据表示删除旧附件
		if (!$data['file'] && $_data['file']) {
			return array(
				array(),
				$_data['file']
			);
		}
		
		// 当无旧数据且有新数据表示增加新附件
		if ($data['file'] && !$_data['file']) {
			return array(
				$data['file'],
				array()
			);
		}
		
		// 剩下的情况就是删除旧文件增加新文件
		
		// 新旧附件的交集，表示固定的
		$intersect = @array_intersect($data['file'], $_data['file']);
		
		return array(
			array_diff($data['file'], $intersect), // 固有的与新文件中的差集表示新增的附件
			array_diff($_data['file'], $intersect), // 固有的与旧文件中的差集表示待删除的附件
		);
	}
	
	/**
	 * 字段表单输入
	 *
	 * @param	string	$cname	字段别名
	 * @param	string	$name	字段名称
	 * @param	array	$cfg	字段配置
	 * @param	array	$data	值
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
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 禁止修改
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		// 当字段必填时，加入html5验证标签
		if (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1) $attr .= ' required="required"';
		// 上传的URL
		$url = MEMBER_PATH.'index.php?c=api&m=upload&name='.$name.'&code='.str_replace('=', '', dr_authcode($cfg['option']['size'].'|'.$cfg['option']['ext'].'|'.$this->get_upload_path($cfg['option']['uploadpath']), 'ENCODE'));
		// 字段默认值
		$file_value = '';
		$value && $value = dr_string2array($value);
		// 默认值输出
		if ($value && isset($value['file'])) {
			foreach ($value['file'] as $id => $fileid) {
				$info = dr_file_info($fileid);
				$title = $value['title'][$id];
				$file_value.= '
				<li id="files_'.$name.'_999'.$id.'" list="999'.$id.'" style="cursor:move;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="80" style="text-align:right">
						'.($id+1).'、
						<a href="javascript:;" title="'.lang('edit').'" onclick="dr_edit_file(\''.$url.'&count=1\',\''.$name.'\',\'999'.$id.'\')"><img align="absmiddle" src="'.SITE_URL.'dayrui/statics/images/b_edit.png"></a>
						<a href="javascript:;" title="'.lang('del').'" onclick="dr_remove_file(\''.$name.'\',\'999'.$id.'\')"><img align=\"absmiddle\" src="'.SITE_URL.'dayrui/statics/images/b_drop.png"></a>
					</td>
					<td>
						<input type="hidden" value="'.$fileid.'" name="data['.$name.'][file][]" id="fileid_'.$name.'_999'.$id.'" />
						<input type="text" class="input-text" style="width:300px;" value="'.$title.'" name="data['.$name.'][title][]" />
						<span id="span_'.$name.'_999'.$id.'">
							<a href="javascript:;" onclick="dr_show_file_info(\''.$fileid.'\')"><img align="absmiddle" src="'.$info['icon'].'"></a>
							<div class="onCorrect">'.$info['size'].'&nbsp;</div>
						</span>
					</td>
				</tr>
				</table>
				</li>';
			}
		}
		// 输出变量
		$str ='';
		// 加载js
		if (!defined('FINECMS_FILES_LD')) {
			$str.= '<script type="text/javascript" src="'.MEMBER_PATH.'statics/js/jquery-ui.min.js"></script>';
			$str.= '<script type="text/javascript">var homeurl = "'.SITE_URL.'"</script>';
			define('FINECMS_FILES_LD', 1);//防止重复加载JS
		}
		$str.= '<fieldset class="blue pad-10" style="width:'.$width.(is_numeric($width) ? 'px' : '').';">';
        $str.= '	<legend>'.lang('m-120').'</legend>';
        $str.= '	<div class="picList" id="list_'.$name.'_files">';
		$str.= '		<ul id="'.$name.'-sort-items">';
		$str.= 				$file_value;
		$str.= '		</ul>';
		$str.= '	</div>';
		$str.= '</fieldset>';
		$str.= '<div class="bk10"></div>';
		$str.= '<div class="picBut cu">';
		if (!$disabled) {
			$str.= '<a href="javascript:;" onClick="dr_upload_files(\''.$name.'\',\''.$url.'\', \'\', \''.(int)$cfg['option']['count'].'\')">'.lang('m-119').'</a>';
		}
		$str.= '</div>';
		$str.= '<script type="text/javascript">$("#'.$name.'-sort-items").sortable();</script>'.$tips;
		// 输出最终表单显示
		return $this->input_format($name, $text, $str);
	}
	
}