<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_File extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('282') : ''; // 字段名称
		$this->fieldtype = array('VARCHAR' => '255'); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'VARCHAR'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
		$option['width'] = isset($option['width']) ? $option['width'] : 200;
		$option['fieldtype'] = isset($option['fieldtype']) ? $option['fieldtype'] : '';
		$option['uploadpath'] = isset($option['uploadpath']) ? $option['uploadpath'] : '';
		$option['fieldlength'] = isset($option['fieldlength']) ? $option['fieldlength'] : '';
		return '<tr>
                    <th>'.lang('283').'：</th>
                    <td>
                    <input id="field_default_value" type="text" class="input-text" size="10" value="'.$option['size'].'" name="data[setting][option][size]">
					<div class="onShow">'.lang('284').'</div>
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
		return $value;
	}
	
	/**
	 * 获取附件id
	 */
	public function get_attach_id($value) {
	
		$data = array();
		if (!$value || !is_numeric($value)) return $data;
		
		$data[] = $value;
		
		return $data;
	}
	
	/**
	 * 附件处理
	 */
	public function attach($data, $_data) {
		
		// 新旧数据都无附件就跳出
		if (!$data && !$_data) {
			return NULL;
		}
		
		// 新旧数据都一样时表示没做改变就跳出
		if ($data === $_data) {
			return NULL;
		}
		
		// 当无新数据且有旧数据表示删除旧附件
		if (!$data && $_data) {
			return array(
				array(),
				array($_data)
			);
		}
		
		// 当无旧数据且有新数据表示增加新附件
		if ($data && !$_data) {
			return array(
				array($data),
				array()
			);
		}
		
		// 剩下的情况就是删除旧文件增加新文件
		return array(
			array($data),
			array($_data)
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
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 当字段必填时，加入html5验证标签
		if (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1) $attr .= ' required="required"';
		// 禁止修改
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		// 上传的URL
		$url = MEMBER_PATH.'index.php?c=api&m=upload&name='.$name.'&count=1&code='.str_replace('=', '', dr_authcode($cfg['option']['size'].'|'.$cfg['option']['ext'].'|'.$this->get_upload_path($cfg['option']['uploadpath']), 'ENCODE'));
		// 文件值
		$file = $info = '';
		if ($value) {
			$file = $value;
			$data = dr_file_info($file);
			if ($data) {
				$fsize = $data['size'] ? ' ('.$data['size'].')' : '';
				$info = '<a href="javascript:;" onclick="dr_show_file_info(\''.$file.'\')"><img align="absmiddle" src="'.$data['icon'].'"></a><div class="onCorrect">'.$data['filename'].$size.'&nbsp;</div>';
			}
			unset($data);
		}
		// 上传按钮与表单值
		$tool = '<span>'.dr_lang('m-138', str_replace('|', '、', $cfg['option']['ext'])).'</span>&nbsp;&nbsp;
		<input type="hidden" id="dr_'.$name.'" name="data['.$name.']" value="'.$file.'" '.$attr.' />
		<input type="button" style="cursor:pointer;" '.$disabled.' class="button" onclick="dr_upload_file(\''.$name.'\', \''.$url.'\')" value="' . lang('m-119') . '" />
		';
		// 文件信息查看
		$finfo = '<span id="show_'.$name.'" />'.$info.'</span>'.$tips;
		
		return $this->input_format($name, $text, $tool.$finfo);
	}
}