<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

/**
 * 伪静态代码处理
 *
 * @param	array	$params	参数数组
 * @return	string
 */
function dr_rewrite_encode($params) {
	
	if (!$params) return '';
	
	$url = '';
	foreach ($params as $i => $t) {
		$url.= '-'.$i.'-'.$t;
	}
	
	return trim($url, '-');
}
 
/**
 * 伪静态代码转换为数组
 *
 * @param	string	$params	参数字符串
 * @return	array
 */
function dr_rewrite_decode($params) {
	
	if (!$params) return NULL;
	
	$i = 0;
	$array = explode('-', $params);
	
	$return = array();
	foreach ($array as $k => $t) {
		if ($i%2 == 0) {
			$return[str_replace('$', '_', $t)] = isset($array[$k+1]) ? $array[$k+1] : '';
		}
		$i ++;
	}
	
	return $return;
}
 
/**
 * 空间搜索url组合
 *
 * @param	array	$params		搜索参数数组
 * @param	string	$name		当前参数名称
 * @param	string	$value		当前参数值
 * @param	string	$urlrule	搜索url规则
 * @return	string
 */
function dr_space_search_url($params = NULL, $name = NULL, $value = NULL, $urlrule = NULL) {
	
	$params = $params ? $params : array();
	
	if ($name) {
		if ($value) {
			$params[$name] = $value;
		} else {
			unset($params[$name]);
		}
	}
	if ($params) {
		foreach ($params as $i => $t) {
			if (strlen($t) == 0) unset($params[$i]);
		}
	}
	
	$ci	= &get_instance();
	$space = $ci->get_cache('member', 'setting', 'space');
	if ($params && $space['rewrite']) {
		return ($space['domain'] ? $space['domain'] : '').'search-'.dr_rewrite_encode($params).'.html';
	} else {
		return 'index.php?'.http_build_query($params);
	}
	
}
 
 
/**
 * 搜索url组合
 *
 * @param	array	$params		搜索参数数组
 * @param	string	$name		当前参数名称
 * @param	string	$value		当前参数值
 * @param	string	$urlrule	搜索url规则
 * @param	string	$moddir		强制定位到模块
 * @return	string
 */
function dr_search_url($params = NULL, $name = NULL, $value = NULL, $urlrule = NULL, $moddir = NULL) {
	
	$dir = APP_DIR;
	if (!is_array($params) && $params && is_dir(FCPATH.$params)) {
		$dir = (string)$params;
		$params = array();
	} else {
		$params = is_array($params) ? $params : array();
	}
	$dir = $moddir ? $moddir : $dir;
	
	if ($name) {
		if ($value) {
			$params[$name] = $value;
		} else {
			unset($params[$name]);
		}
	}
	if (is_array($params)) {
		foreach ($params as $i => $t) {
			if (strlen($t) == 0) unset($params[$i]);
		}
	}
	
	$ci	= &get_instance();
	$mod = $ci->get_cache('module-'.SITE_ID.'-'.$dir);
	if ($params && $mod['setting']['search']['rewrite']) {
		return $mod['url'].'search-'.dr_rewrite_encode($params).'.html';
	} else {
		return $mod['url'].'index.php?c=search&'.http_build_query($params);
	}
	
}

/**
 * tag的url
 *
 * @param	array	$module
 * @param	string	关键字
 * @return	string	地址
 */
function dr_tag_url($module, $name) {

	if (!$name) return '?name参数为空';
	if (!$module) return '?module参数为空';
	
	$name = dr_word2pinyin($name);
	$rule = $module['setting']['tag']['url'];
	$turl = $rule ? str_replace('{tag}', $name, $rule) : 'index.php?c=tag&name='.$name;
	
	return ($module['url'] ? $module['url'] : MODULE_URL).$turl;
}


/**
 * 会员空间url
 *
 * @param	intval	$uid
 * @return	string	地址
 */
function dr_space_url($uid) {
	
	$ci	= &get_instance();
	if ($ci->get_cache('member', 'setting', 'space', 'rewrite')) {
		return MEMBER_URL.$uid.'/';
	} else {
		return MEMBER_URL.'index.php?uid='.$uid;
	}
}

function dr_space_list_url($uid, $id, $page = FALSE) {

	$ci	= &get_instance();
	if ($ci->get_cache('member', 'setting', 'space', 'rewrite')) {
		if ($page) {
			return MEMBER_URL.$uid.'/category-'.$id.'-[page].html';
		}
		return MEMBER_URL.$uid.'/category-'.$id.'.html';
	} else {
		if ($page) {
			return MEMBER_URL.'index.php?uid='.$uid.'&action=category&id='.$id.'&page=[page]';
		}
		return MEMBER_URL.'index.php?uid='.$uid.'&action=category&id='.$id;
	}
}

function dr_space_show_url($uid, $mid, $id, $page = FALSE) {

	$ci	= &get_instance();
	if ($ci->get_cache('member', 'setting', 'space', 'rewrite')) {
		if ($page) {
			return MEMBER_URL.$uid.'/show-'.$mid.'-'.$id.'-[page].html';
		}
		return MEMBER_URL.$uid.'/show-'.$mid.'-'.$id.'.html';
	} else {
		if ($page) {
			return MEMBER_URL.'index.php?uid='.$uid.'&action=show&mid='.$mid.'&id='.$id.'&page='.$page;
		}
		return MEMBER_URL.'index.php?uid='.$uid.'&action=show&mid='.$mid.'&id='.$id;
	}
}

/**
 * 模块内容分页链接
 *
 * @param	string	$urlrule
 * @param	intval	$page
 * @return	string	地址
 */
function dr_content_page_url($urlrule, $page) {
	return str_replace('{page}', $page, $urlrule);
}

/**
 * 联动菜单包屑导航
 *
 * @param	string	$code	联动菜单代码
 * @param	intval	$id		id
 * @param	string	$symbol	间隔符号
 * @param	string	$url	url地址格式，必须存在{linkage}，否则返回不带url的字符串
 * @return	string
 */
function dr_linkagepos($code, $id, $symbol = ' > ', $url = NULL) {

	if (!$code || !$id) return NULL;
	
	$ci	= &get_instance();
	$url = $url ? urldecode($url) : NULL;
	$link = $ci->get_cache('linkage-'.SITE_ID.'-'.$code);
	$data = $link[$id];
	$pids = @explode(',', $data['pids']);
	$name = array();
	
	foreach ($pids as $pid) {
		if ($pid) {
			$name[] = $url ? "<a href=\"".str_replace('{linkage}', $pid, $url)."\">{$link[$pid]['name']}</a>" : $link[$pid]['name'];
		}
	}
	$name[] = $url ? "<a href=\"".str_replace('{linkage}', $id, $url)."\">{$data['name']}</a>" : $data['name'];
	
	return implode($symbol, $name);
}

/**
 * 模块栏目面包屑导航
 *
 * @param	intval	$catid	栏目id
 * @param	string	$symbol	面包屑间隔符号
 * @param	string	$url	是否显示URL
 * @return	string
 */
function dr_catpos($catid, $symbol = ' > ', $url = TRUE) {

	if (!$catid) return NULL;
	
	$ci	= &get_instance();
	$cat = $ci->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
	if (!isset($cat[$catid])) return NULL;
	
	$name = array();
	$array = explode(',', $cat[$catid]['pids']);
	
	foreach ($array as $id) {
		if ($id && $cat[$id]) {
			$name[] = $url ? "<a href=\"{$cat[$id]['url']}\">{$cat[$id]['name']}</a>" : $cat[$id]['name'];
		}
	}
	
	$name[] = $url ? "<a href=\"{$cat[$catid]['url']}\">{$cat[$catid]['name']}</a>" : $cat[$catid]['name'];
	
	return implode($symbol, $name);
}
 
/**
 * 模块栏目层次关系
 *
 * @param	array	$mod
 * @param	array	$cat
 * @param	string	$symbol
 * @return	string
 */
function dr_get_cat_pname($mod, $cat, $symbol = '_') {

	if (!$cat['pids']) return $cat['name'];
	
	$name = array();
	$array = explode(',', $cat['pids']);
	
	foreach ($array as $id) {
		if ($id && $mod['category'][$id]) {
			$name[] = $mod['category'][$id]['name'];
		}
	}
	$name[] = $cat['name'];
	krsort($name);
	
	return implode($symbol, $name);
}

/**
 * 单页面包屑导航
 *
 * @param	intval	$id
 * @param	string	$symbol
 * @return	string
 */
function dr_page_catpos($id, $symbol = ' > ') {

	if (!$id) return NULL;
	
	$ci	= &get_instance();
	$page = $ci->get_cache('page-'.SITE_ID, 'data');
	$page = APP_DIR ? $data[APP_DIR] : $page['index'];
	if (!isset($page[$id])) return NULL;
	
	$name = array();
	$array = explode(',', $page[$id]['pids']);
	foreach ($array as $i) {
		if ($i && $page[$i]) {
			$name[] = "<a href=\"{$page[$i]['url']}\">{$page[$i]['name']}</a>";
		}
	}
	
	$name[] = "<a href=\"{$page[$id]['url']}\">{$page[$id]['name']}</a>";
	
	return implode($symbol, $name);
}
 
/**
 * 单页层次关系
 *
 * @param	intval	$id
 * @param	string	$symbol
 * @return	string
 */
function dr_get_page_pname($id, $symbol = '_') {

	$ci	= &get_instance();
	$page = $ci->get_cache('page-'.SITE_ID, 'data');
	$page = APP_DIR ? $data[APP_DIR] : $page['index'];
	if (!$page[$id]['pids']) return $page[$id]['name'];
	
	$name = array();
	$array = explode(',', $page[$id]['pids']);
	
	foreach ($array as $i) {
		if ($i && $page[$i]) {
			$name[] = $page[$i]['name'];
		}
	}
	
	$name[] = $page[$id]['name'];
	krsort($name);
	
	return implode($symbol, $name);
}

/**
 * 会员空间模型栏目面包屑导航
 *
 * @param	intval	$uid	会员id
 * @param	intval	$catid	栏目id
 * @param	string	$symbol	面包屑间隔符号
 * @param	string	$url	是否显示URL
 * @return	string
 */
function dr_space_catpos($uid, $catid, $symbol = ' > ', $url = TRUE) {

	if (!$uid || !$catid) return NULL;
	
	$ci	= &get_instance();
	$ci->load->model('space_category_model');
	$cat = $ci->space_category_model->get_data(0, $uid, 1);
	if (!isset($cat[$catid])) return NULL;
	
	$name = array();
	$array = explode(',', $cat[$catid]['pids']);
	
	foreach ($array as $id) {
		if ($id && $cat[$id]) {
			$name[] = $url ? "<a href=\"".dr_space_list_url($uid, $id)."\">{$cat[$id]['name']}</a>" : $cat[$id]['name'];
		}
	}
	
	$name[] = $url ? "<a href=\"".dr_space_list_url($uid, $catid)."\">{$cat[$catid]['name']}</a>" : $cat[$catid]['name'];
	
	return implode($symbol, $name);
}

/**
 * 模块内容SEO信息
 *
 * @param	array	$mod
 * @param	array	$cat
 * @param	intval	$page
 * @return	array
 */
function dr_show_seo($mod, $data, $page = 1) {

	$seo = array();
	
	$cat = $mod['category'][$data['catid']];
	$data['join'] = SITE_SEOJOIN ? SITE_SEOJOIN : '_';
	$data['name'] = $data['catname'] = dr_get_cat_pname($mod, $cat, $data['join']);
	$data['modulename'] = $data['modname'] = $mod['name'];
	
	$meta_title = $cat['setting']['seo']['show_title'] ? $cat['setting']['seo']['show_title'] : '[第{page}页{join}]{title}{join}{name}{join}{modulename}{join}{SITE_NAME}';
	
	if ($page > 1) {
		$meta_title = str_replace(array('[', ']'), '', $meta_title);
	} else {
		$meta_title = preg_replace('/\[.+\]/U', '', $meta_title);
	}
	
	// 兼容php5.5
	if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
		$ci	= &get_instance();
		$site = $ci->SITE[SITE_ID];
		
	} else {
		extract($data);
		$seo['meta_title'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$\\1", $meta_title);
		$seo['meta_title'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_title']);
	}
	
	if (is_array($data['keywords'])) {
		foreach ($data['keywords'] as $key => $t) {
			$seo['meta_keywords'].= $key.',';
		}
		$seo['meta_keywords'] = trim($seo['meta_keywords'], ',');
	} else {
		$seo['meta_keywords'] = $data['keywords'];
	}
	$seo['meta_description'] = dr_clearhtml($data['description']);
	
	return $seo;
}

/**
 * 模块栏目SEO信息
 *
 * @param	array	$mod
 * @param	array	$cat
 * @param	intval	$page
 * @return	array
 */
function dr_category_seo($mod, $cat, $page = 1) {

	$seo = array();
	$cat['page'] = $page;
	$cat['join'] = SITE_SEOJOIN ? SITE_SEOJOIN : '_';
	$cat['name'] = $cat['catname'] = dr_get_cat_pname($mod, $cat, $cat['join']);
	$cat['modulename'] = $cat['modname'] = $mod['name'];
	
	$meta_title = $cat['setting']['seo']['list_title'] ? $cat['setting']['seo']['list_title'] : '[第{page}页{join}]{modulename}{join}{SITE_NAME}';
	
	if ($page > 1) {
		$meta_title = str_replace(array('[', ']'), '', $meta_title);
	} else {
		$meta_title = preg_replace('/\[.+\]/U', '', $meta_title);
	}
	
	// 兼容php5.5
	if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
		$ci	= &get_instance();
		$site = $ci->SITE[SITE_ID];
		
	} else {
		$seo['meta_title'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$cat[\\1]", $meta_title);
		$seo['meta_title'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_title']);
		$seo['meta_keywords'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$cat[\\1]", $cat['setting']['seo']['list_keywords']);
		$seo['meta_keywords'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_keywords']);
		$seo['meta_description'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$cat[\\1]", $cat['setting']['seo']['list_description']);
		$seo['meta_description'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_description']);
	}
	$seo['meta_title'] = trim($seo['meta_title'], $cat['join']);
	return $seo;
}

/**
 * 模块SEO信息
 *
 * @param	array	$mod
 * @return	array
 */
function dr_module_seo($mod) {

	$seo = array();
	$mod['join'] = SITE_SEOJOIN ? SITE_SEOJOIN : '_';
	$mod['modulename'] = $mod['modname'] = $mod['name'];
	$meta_title = $mod['setting']['seo']['module_title'] ? $mod['setting']['seo']['module_title'] : $mod['name'].$mod['join'].SITE_TITLE;
	
	// 兼容php5.5
	if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
		$ci	= &get_instance();
		$site = $ci->SITE[SITE_ID];
		
	} else {
		$seo['meta_title'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$mod[\\1]", $meta_title);
		$seo['meta_title'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_title']);
		$seo['meta_keywords'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$mod[\\1]", $mod['setting']['seo']['module_keywords']);
		$seo['meta_keywords'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_keywords']);
		$seo['meta_description'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$mod[\\1]", $mod['setting']['seo']['module_description']);
		$seo['meta_description'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_description']);
	}
	
	return $seo;
}

/**
 * 模块内容URL地址
 *
 * @param	array	$mod
 * @param	array	$data
 * @param	mod	$page
 * @return	string
 */
function dr_show_url($mod, $data, $page = NULL) {

	if (!$mod || !$data) return SITE_URL;
	
	$cat = $mod['category'][$data['catid']];
	if ($page) $data['page'] = $page = is_numeric($page) ? max((int)$page, 1) : $page;
	$ci	= &get_instance();
	$rule = $ci->get_cache('urlrule', (int)$cat['setting']['urlrule'], 'value');
	if ($rule && $rule['show_page'] && $rule['show']) {
		// URL模式为自定义，且已经设置规则
		$cat['pdirname'].= $cat['dirname'];
		$data['dirname'] = $cat['dirname'];
		$inputtime = isset($data['_inputtime']) ? $data['_inputtime'] : $data['inputtime'];
		$data['y'] = date('Y', $inputtime);
		$data['m'] = date('m', $inputtime);
		$data['d'] = date('d', $inputtime);
		$data['pdirname'] = str_replace('/', $rule['catjoin'], $cat['pdirname']);
		$url = $page ? $rule['show_page'] : $rule['show'];
		// 兼容php5.5
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			return $mod['url'].'index.php?c=show&id='.$data['id'].($page ? '&page='.$page : '');
		} else {
			$url = preg_replace('#{([a-z_0-9]+)}#Uei', "\$data[\\1]", $url);
			$url = preg_replace('#{([a-z_0-9]+)\((.*)\)}#Uie', "\\1(dr_safe_replace('\\2'))", $url);
		}
		return $mod['url'].$url;
	}
	
	return $mod['url'].'index.php?c=show&id='.$data['id'].($page ? '&page='.$page : '');
}


/**
 * 模块内容扩展SEO信息
 *
 * @param	array	$mod
 * @param	array	$cat
 * @return	array
 */
function dr_extend_seo($mod, $data) {

	$seo = array();
	
	$cat = $mod['category'][$data['catid']];
	$data['extend'] = $data['name'];
	$data['join'] = SITE_SEOJOIN ? SITE_SEOJOIN : '_';
	$data['name'] = $data['catname'] = dr_get_cat_pname($mod, $cat, $data['join']);
	$data['modulename'] = $data['modname'] = $mod['name'];
	
	$meta_title = $cat['setting']['seo']['extend_title'] ? $cat['setting']['seo']['extend_title'] : '{extend}{join}{title}{join}{name}{join}{modulename}{join}{SITE_NAME}';
	
	if ($page > 1) {
		$meta_title = str_replace(array('[', ']'), '', $meta_title);
	} else {
		$meta_title = preg_replace('/\[.+\]/U', '', $meta_title);
	}
	
	// 兼容php5.5
	if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
		$ci	= &get_instance();
		$site = $ci->SITE[SITE_ID];
		$seo['meta_title'] = $data['name'].$data['join'].$data['title'];
	} else {
		extract($data);
		$seo['meta_title'] = preg_replace('#{([a-z_0-9]+)}#Ue', "\$\\1", $meta_title);
		$seo['meta_title'] = preg_replace('#{([A-Z_]+)}#Ue', "\\1", $seo['meta_title']);
	}
	
	$seo['meta_keywords'] = $data['keywords'];
	$seo['meta_description'] = dr_clearhtml($data['description']);
	
	return $seo;
}

/**
 * 模块扩展内容URL地址
 *
 * @param	array	$mod
 * @param	array	$data
 * @return	string
 */
function dr_extend_url($mod, $data) {

	if (!$mod || !$data) return SITE_URL;
	
	$cat = $mod['category'][$data['catid']];
	$ci	= &get_instance();
	$rule = $ci->get_cache('urlrule', (int)$cat['setting']['urlrule'], 'value');
	if ($rule && $rule['extend']) {
		// URL模式为自定义，且已经设置规则
		$cat['pdirname'].= $cat['dirname'];
		$data['dirname'] = $cat['dirname'];
		$inputtime = isset($data['_inputtime']) ? $data['_inputtime'] : $data['inputtime'];
		$data['y'] = date('Y', $inputtime);
		$data['m'] = date('m', $inputtime);
		$data['d'] = date('d', $inputtime);
		$data['pdirname'] = str_replace('/', $rule['catjoin'], $cat['pdirname']);
		$url = $rule['extend'];
		// 兼容php5.5
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			return $mod['url'].'index.php?c=extend&id='.$data['id'];
		} else {
			$url = preg_replace('#{([a-z_0-9]+)}#Uei', "\$data[\\1]", $url);
			$url = preg_replace('#{([a-z_0-9]+)\((.*)\)}#Uie', "\\1(dr_safe_replace('\\2'))", $url);
		}
		return $mod['url'].$url;
	}
	
	return $mod['url'].'index.php?c=extend&id='.$data['id'];
}

/**
 * 模块栏目URL地址
 *
 * @param	array	$mod
 * @param	array	$data
 * @param	intval	$page
 * @return	string
 */
function dr_category_url($mod, $data, $page = NULL) {

	if (!$mod || !$data) return SITE_URL;
	if ($page) $data['page'] = $page = is_numeric($page) ? max((int)$page, 1) : $page;
	
	$ci	= &get_instance();
	$rule = $ci->get_cache('urlrule', (int)$data['setting']['urlrule'], 'value');
	
	if ($rule && $rule['list'] && $rule['list_page']) {
		// URL模式为自定义，且已经设置规则
		$data['pdirname'].= $data['dirname'];
		$data['pdirname'] = str_replace('/', $rule['catjoin'], $data['pdirname']);
		$url = $page ? $rule['list_page'] : $rule['list'];
		// 兼容php5.5
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			return $mod['url'].'index.php?c=category&id='.$data['id'].($page ? '&page='.$page : '');
		} else {
			$url = preg_replace('#{([a-z_0-9]+)}#Uei', "\$data[\\1]", $url);
			$url = preg_replace('#{([a-z_0-9]+)\((.*)\)}#Uie', "\\1(dr_safe_replace('\\2'))", $url);
		}
		return $mod['url'].$url;
	}
	
	return $mod['url'].'index.php?c=category&id='.$data['id'].($page ? '&page='.$page : '');
}

/*
 * 单页URL地址
 *
 * @param	array	$data
 * @param	intval	$page
 * @return	string
 */
function dr_page_url($data, $page = NULL) {

	if (!$data) return SITE_URL;
	
	if ($page) $data['page'] = $page = is_numeric($page) ? max((int)$page, 1) : $page;
	
	if ($data['module'] && $module = get_module($data['module'])) {
		$path = $module['url'];
	} else {
		$path = SITE_URL;
	}
	
	$ci	= &get_instance();
	$rule = $ci->get_cache('urlrule', (int)$data['urlrule'], 'value');
	
	if ($rule && $rule['page'] && $rule['page_page']) {
		// URL模式为自定义，且已经设置规则
		$data['pdirname'].= $data['dirname'];
		$data['pdirname'] = str_replace('/', $rule['catjoin'], $data['pdirname']);
		$url = $page ? $rule['page_page'] : $rule['page'];
		// 兼容php5.5
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			return $path.'index.php?c=page&id='.$data['id'].($page ? '&page='.$page : '');
		} else {
			$url = preg_replace('#{([a-z_0-9]+)}#Uei', "\$data[\\1]", $url);
			$url = preg_replace('#{([a-z_0-9]+)\((.*)\)}#Uie', "\\1(dr_safe_replace('\\2'))", $url);
		}
		return $path.$url;
	}
	
	return $path.'index.php?c=page&id='.$data['id'].($page ? '&page='.$page : '');
}

/**
 * 加密自定义url中的值value (防采集)
 *
 * @param	string	$value
 * @return	string	
 */
function dr_url_encode($value) {
	if (0) {
		$ci	= &get_instance();
		$ci->encrypt->set_cipher(MCRYPT_BLOWFISH);
		$ci->encrypt->set_mode(MCRYPT_MODE_CFB);
		return $ci->encrypt->encode($value, 'finecms v2');
	}
	return $value;
}

/**
 * 解密自定义url中的值value (防采集)
 *
 * @param	string	$value
 * @return	string	
 */
function dr_url_decode($value) {
	if (0) {
		$ci	= &get_instance();
		$ci->encrypt->set_cipher(MCRYPT_BLOWFISH);
		$ci->encrypt->set_mode(MCRYPT_MODE_CFB);
		return $ci->encrypt->decode(str_replace(' ', '+', $value), 'finecms v2');
	}
	return $value;
}


/**
 * url函数
 *
 * @param	string	$url		URL规则，如home/index
 * @param	array	$query		相关参数
 * @return	string	项目入口文件.php?参数
 */
function dr_url($url, $query = array()) {

	if (!$url) return SELF;
	
	$url = strpos($url, 'admin') === 0 ? substr($url, 5) : $url;
	$url = trim($url, '/');
	$url = explode('/', $url);
	$uri = array();
	
	switch (count($url)) {
		case 1:
			$uri['c'] = 'home';
			$uri['m'] = $url[0];
			break;
		case 2:
			$uri['c'] = $url[0];
			$uri['m'] = $url[1];
			break;
		case 3:
			$uri['s'] = $url[0];
			$uri['c'] = $url[1];
			$uri['m'] = $url[2];
			break;
	}
	
	if ($query)	$uri = @array_merge($uri, $query);
	
	return SELF.'?'.http_build_query($uri);
}

/**
 * 会员url函数
 *
 * @param	string	$url 	URL规则，如home/index
 * @param	array	$query	相关参数
 * @return	string	地址
 */
function dr_member_url($url, $query = array()) {
	return MEMBER_URL.dr_url($url, $query);
}

/**
 * 当前URL
 */
function dr_now_url() {

    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $pageURL.= 's';
	
    $pageURL .= '://';
    if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80') {
        $pageURL.= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
    } else {
        $pageURL.= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
	
    return $pageURL;
}

/**
 * dialog弹出框窗口的URL
 *
 * @param	string	$url	地址
 * @param	string	$func	指向函数，如add，edit等
 * @param	string	$cache	更新缓存地址
 * @return	string
 */
function dr_dialog_url($url, $func) {
	return "javascript:dr_dialog('{$url}', '{$func}');";
}