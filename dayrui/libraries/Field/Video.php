<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Video extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('310') : ''; // 字段名称
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
		$option['uploadpath'] = isset($option['uploadpath']) ? $option['uploadpath'] : '';
		
		$member = '<table>';
		$MEMBER = $this->ci->get_cache('member');
		$member.= '<tr>';
		$member.= '	<td align="left" width="210">'.lang('guest').'</td>';
		$member.= '	<td align="left">';
		$member.= ' <input type="text" class="input-text" size="5" name="data[setting][option][time][0]" value="'.$option['time'][0].'" />'.lang('311');
		$member.= ' </td>';
		$member.= '</tr>';
		foreach ($MEMBER['group'] as $group) {
			if ($group['id'] > 2) {
				$member.= '<tr>';
				$member.= '	<td align="left" width="210">'.$group['name'].'</td>';
				$member.= '	<td align="left"></td>';
				$member.= '</tr>';
				foreach ($group['level'] as $level) {
					$id = $group['id'].'_'.$level['id'];
					$member.= '<tr>';
					$member.= '<td align="left" width="210" style="padding-left:40px">'.$level['name'].'&nbsp;&nbsp;'.dr_show_stars($level['stars']).'</td>';
					$member.= '<td align="left">';
					$member.= '<input type="text" class="input-text" size="5" name="data[setting][option][time]['.$id.']" value="'.$option['time'][$id].'" />'.lang('311');
					$member.= '</td>';
					$member.= '</tr>';
				}
			} else {
				$member.= '<tr>';
				$member.= '	<td align="left" width="210">'.$group['name'].'</td>';
				$member.= '	<td align="left">';
				$member.= ' <input type="text" class="input-text" size="5" name="data[setting][option][time]['.$group['id'].']" value="'.$option['time'][$group['id']].'" />'.lang('311');
				$member.= ' </td>';
				$member.= '</tr>';
			}
		}
		$member.= '<tr>';
		$member.= '	<td align="left" style="border:none">'.lang('html-598').'</td>';
		$member.= '</tr>';
		$member.= '</table>';
		
		$form = $this->ci->get_cache('form-'.SITE_ID);
		$adsense = '<select name="data[setting][option][table]">';
		if ($form) {
			foreach ($form as $t) {
				$adsense.= '<option value="'.$t['id'].'" '.($option['table'] == $t['id'] ? 'selected' : '').'>'.$t['name'].'</option>';
			}
		}
		$adsense.= '</select>';
		
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
                </tr>
				<tr>
                    <th>'.lang('html-600').'：</th>
                    <td>
					'.$adsense.'<div class="onShow">'.lang('html-601').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('html-597').'：</th>
                    <td>'.$member.'</td>
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
		if ($data['time']) {
			foreach ($data['time'] as $i => $t) {
				if ($data['title'][$i]) $value['point'][$t] = $data['title'][$i];
			}
		}
		$value['file'] = $data['file'];
		$this->ci->data[$field['ismain']][$field['fieldname']] = dr_array2string($value);
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
		if ($data && $data['file'] === $_data['file']) {
			return NULL;
		}
		
		// 当无新数据且有旧数据表示删除旧附件
		if (!$data['file'] && $_data['file']) {
			return array(
				array(),
				array($_data['file'])
			);
		}
		
		// 当无旧数据且有新数据表示增加新附件
		if ($data && $data['file'] && !$_data['file']) {
			return array(
				array($data['file']),
				array()
			);
		}
		
		// 剩下的情况就是删除旧文件增加新文件
		return array(
			array($data['file']),
			array($_data['file'])
		);
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
		// 当字段必填时，加入html5验证标签
		if (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1) $attr .= ' required="required"';
		// 表单选项
		$disabled = !IS_ADMIN && $id && $value && isset($cfg['validate']['isedit']) && $cfg['validate']['isedit'] ? 'disabled' : ''; 
		// 上传的URL
		$url = MEMBER_PATH.'index.php?c=api&m=upload&name='.$name.'&count=1&code='.str_replace('=', '', dr_authcode($cfg['option']['size'].'|'.$cfg['option']['ext'].'|'.$this->get_upload_path($cfg['option']['uploadpath']), 'ENCODE'));
		// 文件值
		$file = $info = '';
		$value = dr_string2array($value);
		if ($value['file']) {
			$file = $value['file'];
			$data = dr_file_info($file);
			if ($data) {
				$fsize = $data['size'] ? ' ('.$data['size'].')' : '';
				$info = '<a href="javascript:;" onclick="dr_show_file_info(\''.$file.'\')"><img align="absmiddle" src="'.$data['icon'].'"></a><div class="onCorrect">'.$data['filename'].$size.'&nbsp;</div>';
			}
			unset($data);
			$default = '';
			if ($value['point']) {
				$i = 0;
				foreach ($value['point'] as $time => $title) {
					$default.= '
					<li id="dr_items_'.$name.'_'.$i.'">
					时间(秒)：<input type="text" class="input-text" style="width:70px;" value="'.$time.'" name="data['.$name.'][time][]">&nbsp;&nbsp;提示文字：<input type="text" class="input-text" style="width:250px;" value="'.$title.'" name="data['.$name.'][title][]\">&nbsp;&nbsp;<a href="javascript:;" onclick="$(\'#dr_items_'.$name.'_'.$i.'\').remove()">'.lang('del').'</a>
					</li>';
					$i++;
				}
			}
		}
		// 显示框宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : '80%';
		$str = '<fieldset class="blue pad-10" style="width:'.$width.(is_numeric($width) ? 'px' : '').';">
					<legend>'.$cname.'</legend>
					<div class="picList">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td style="text-align:left;padding-left:0;">
							<span>'.dr_lang('m-138', str_replace('|', '、', $cfg['option']['ext'])).'</span>&nbsp;&nbsp;
							<input type="hidden" id="dr_'.$name.'" name="data['.$name.'][file]" value="'.$file.'" '.$attr.' />
							<input type="button" style="cursor:pointer;" '.$disabled.' class="button" onclick="dr_upload_file(\''.$name.'\', \''.$url.'\')" value="' . lang('m-119') . '" />
							<span id="show_'.$name.'" />'.$info.'</span>'.$tips.'
							</td>
						</tr>
						</table>
						<ul id="'.$name.'-sort-items" style="margin-top:8px;">
						'.$default.'
						</ul>
					</div>
				<div class="picBut cu">
					<a href="javascript:;" onClick="dr_add_video_'.$name.'()">添加提示点</a>
				</div>
				<div class="onShow" style="margin-top:2px;">鼠标经过进度栏N秒时，N秒会提示相应的文字</div>
				<script type="text/javascript">
				$("#'.$name.'-sort-items").sortable();
				var id=$("#'.$name.'-sort-items li").size();
				function dr_add_video_'.$name.'() {
					id ++;
					var html = "<li id=\"dr_items_'.$name.'_"+id+"\">";
					html+= "时间(秒)：<input type=\"text\" class=\"input-text\" style=\"width:70px;\" value=\"\" name=\"data['.$name.'][time][]\">&nbsp;&nbsp;";
					html+= "提示文字：<input type=\"text\" class=\"input-text\" style=\"width:250px;\" value=\"\" name=\"data['.$name.'][title][]\">&nbsp;&nbsp;";
					html+= "<a href=\"javascript:;\" onclick=\"$(\'#dr_items_'.$name.'_"+id+"\').remove()\">'.lang('del').'</a>";
					html+= "</li>";
					$("#'.$name.'-sort-items").append(html);
				}
				</script>
				</fieldset>
		';
		return $this->input_format($name, $text, $str);
	}
	
}