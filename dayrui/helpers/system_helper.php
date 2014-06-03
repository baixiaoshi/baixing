<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

/**
 * 删除非空目录（v2.0.12后版本弃用）
 *
 * @param	string	$dir	目录名称
 * @return	bool|void
 */
function dr_rmdir($dir) {
	
	if (!$dir || is_file(trim($dir, DIRECTORY_SEPARATOR).'/index.php')) return FALSE;
    
	@rmdir($dir);
}

/**
 * 模块内容生成静态异步链接
 *
 * @param	intval	$id		文档id
 * @param	intval	$url	是否返回url
 * @return  string
 */
function dr_module_create_show_file($id, $url = 0) {
	$file = SITE_URL.APP_DIR.'/index.php?c=show&m=create_html&id='.$id;
	if ($url) return $file;
	return '<script src="'.$file.'"></script>';
}

/**
 * 模块内容扩展生成静态异步链接
 *
 * @param	intval	$id		文档id
 * @param	intval	$url	是否返回url
 * @return  string
 */
function dr_module_create_extend_file($id, $url = 0) {
	$file = SITE_URL.APP_DIR.'/index.php?c=extend&m=create_html&cid='.$id;
	if ($url) return $file;
	return '<script src="'.$file.'"></script>';
}

/**
 * 管理员权限划分表格
 *
 * @param	array	$role	角色缓存
 * @param	array	$value	值
 * @return  string
 */
function dr_admin_rule($role, $value = NULL) {

	if (!$role) return NULL;
	
	$html = '';
	foreach ($role as $id => $t) {
		if ($id > 1) {
			$html.= '<tr>';
			$html.= '	<th width="200"> '.$t['name'].'：</th>';
			$html.= '	<td>';
			$html.= '		'.lang('admin').'&nbsp;<input name="data[setting][admin]['.$id.'][show]" class="dr_show" type="checkbox" value="1" '.(isset($value[$id]['show']) && $value[$id]['show'] ? 'checked' : '').' />&nbsp;&nbsp&nbsp;';
            $html.= '		'.lang('add').'&nbsp;<input name="data[setting][admin]['.$id.'][add]" class="dr_add" type="checkbox" value="1" '.(isset($value[$id]['add']) && $value[$id]['add'] ? 'checked' : '').' />&nbsp;&nbsp&nbsp;';
            $html.= '		'.lang('edit').'&nbsp;<input name="data[setting][admin]['.$id.'][edit]" class="dr_edit" type="checkbox" value="1" '.(isset($value[$id]['edit']) && $value[$id]['edit'] ? 'checked' : '').' />&nbsp;&nbsp&nbsp;';
            $html.= '		'.lang('del').'&nbsp;<input name="data[setting][admin]['.$id.'][del]" class="dr_del" type="checkbox" value="1" '.(isset($value[$id]['del']) && $value[$id]['del'] ? 'checked' : '').' />&nbsp;&nbsp&nbsp;';
            $html.= '	</td>';
			$html.= '</tr>';
		}
	}
	
	return $html;
}

/**
 * 通过会员名称取会员id
 *
 * @param	string	$username
 * @return  intval
 */
function get_member_id($username) {

	if (!$username) return 0;
	
	$ci	= &get_instance();
	$data = $ci->db
			   ->select('uid')
			   ->where('username', $username)
			   ->limit(1)
			   ->get('member')
			   ->row_array();
			   
	return (int)$data['uid'];
}

/**
 * 通过会员ui取会员OAuth昵称
 *
 * @param	intval	$uid
 * @return  string
 */
function get_member_nickname($uid) {

	if (!$uid) return '';
	
	$ci	= &get_instance();
	$data = $ci->db
			   ->select('nickname')
			   ->where('uid', (int)$uid)
			   ->limit(1)
			   ->get('member_oauth')
			   ->row_array();
			   
	return $data['nickname'];
}

/**
 * 通过会员ui取会员字段
 *
 * @param	intval	$uid
 * @return  string
 */
function get_member_value($uid, $value = 'username') {

	if (!$uid) return '';
	
	$ci	= &get_instance();
	$data = $ci->db
			   ->select($value)
			   ->where('uid', (int)$uid)
			   ->limit(1)
			   ->get('member')
			   ->row_array();
			   
	return $data[$value];
}

/**
 * 附件信息
 *
 * @param	string	$key
 * @return  array
 */
function dr_file_info($key) {

	if (!$key) return NULL;
	
	if (is_numeric($key)) {
		$info = get_attachment($key);
		if (!$info) return NULL;
		if (in_array($info['fileext'], array('jpg', 'gif', 'png'))) {
			$info['icon'] = SITE_URL.'dayrui/statics/images/ext/jpg.gif';
		} else {
			$info['icon'] = is_file(FCPATH.'dayrui/statics/images/ext/'.$info['fileext'].'.gif') ? SITE_URL.'dayrui/statics/images/ext/'.$info['fileext'].'.gif' : SITE_URL.'dayrui/statics/images/ext/blank.gif';
		}
		$info['size'] = dr_format_file_size($info['filesize']);
		return $info;
	} else {
		return array('icon' => SITE_URL.'dayrui/statics/images/ext/url.gif', 'size' => '');
	}
}
 
/**
 * 字段输出表单
 *
 * @param	string	$username
 * @return  intval
 */
function dr_field_input($name, $type, $option, $value = NULL, $id = 0) {

	$ci	= &get_instance();
	$ci->load->library('Dfield', array(APP_DIR));
	$field = $ci->dfield->get($type);
	if (!is_object($field)) return NULL;
	
	A_Field::set_input_format('{value}');
	
	return preg_replace('/(<div class="on.+<\/div>)/U', '', $field->input($name, $name, $option, $value, $id));
}

/**
 * 目录扫描
 *
 * @param	string	$source_dir		Path to source
 * @param	int	$directory_depth	Depth of directories to traverse
 *						(0 = fully recursive, 1 = current dir, etc)
 * @param	bool	$hidden			Whether to show hidden files
 * @return	array
 */
function dr_dir_map($source_dir, $directory_depth = 0, $hidden = FALSE) {

	if ($fp = @opendir($source_dir)) {
	
		$filedata = array();
		$new_depth = $directory_depth - 1;
		$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		
		while (FALSE !== ($file = readdir($fp))) {
			if ($file === '.' OR $file === '..' OR ($hidden === FALSE && $file[0] === '.') OR !@is_dir($source_dir.$file)) {
				continue;
			}
			if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file)) {
				$filedata[$file] = dr_dir_map($source_dir.DIRECTORY_SEPARATOR.$file, $new_depth, $hidden);
			} else {
				$filedata[] = $file;
			}
		}
		closedir($fp);
		return $filedata;
	}
	
	return FALSE;
}