<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

 
/**
 * 删除目录及目录下面的所有文件
 * 
 * @param	string	$dir		路径
 * @return	bool	如果成功则返回 TRUE，失败则返回 FALSE
 */
function dr_dir_delete($dir) {
	
	$dir = str_replace('\\', '/', $dir);
	if(substr($dir, -1) != '/') $dir = $dir.'/';
	if (!is_dir($dir)) return FALSE;
	
	$list = glob($dir.'*');
	foreach($list as $v) {
		is_dir($v) ? dr_dir_delete($v) : @unlink($v);
	}
	
    return @rmdir($dir);
}
 
/**
 * discuz加密/解密
 */
function dr_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	if (!$string) return '';
	
	$ckey_length = 4;
	
	$key = md5($key ? $key : SYS_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}
 
/**
 * 统计模块表单数量
 *
 * @param	intval	$cid	模块内容id
 * @param	intval	$mid	模块表单id
 * @param	string	$module	模块目录
 * @param	intval	$cache	缓存时间
 * @return	string
 */
function dr_mform_total($cid, $mid, $module = APP_DIR, $cache = 10000) {
	
	$ci	= &get_instance();
	$name = 'mform-total-'.$module.'-'.$mid.'-'.$cid;
	$data = $ci->get_cache_data($name);
	if (!$data) {
		$data = $ci->site[SITE_ID]
				   ->where('cid', (int)$cid)
				   ->count_all_results(SITE_ID.'_'.$module.'_form_'.$mid);
		$ci->set_cache_data($name, $data, $cache ? $cache : 10000);
	}
	
	return $data;
}
 
/**
 * 调用会员详细信息
 *
 * @param	intval	$uid	会员uid
 * @param	intval	$cache	缓存时间
 * @return	string
 */
function dr_member_info($uid, $cache = 10000) {
	
	$ci	= &get_instance();
	$data = $ci->get_cache_data('member-info-'.$uid);
	if (!$data) {
		$data = $ci->member_model->get_member($uid);
		$ci->set_cache_data('member-info-'.$uid, $data, $cache ? $cache : 10000);
	}
	
	return $data;
}

/**
 * 模块内容收费内容js调用
 *
 * @param	intval	$id
 * @return	string
 */
function dr_show_buy($id) {
	return "<script type=\"text/javascript\" src=\"".MODULE_URL."index.php?c=api&m=buy&id={$id}\"></script>";
}
 
/**
 * 检测会员在线情况
 */
function dr_member_online($uid, $type) {
	return "<script type=\"text/javascript\" src=\"".MEMBER_URL."index.php?c=api&m=online&uid={$uid}&type={$type}\"></script>";
}

/**
 * 用于视频播放器字段输出
 *
 * @param	string	$name		字段名称
 * @param	array	$value		字段值
 * @param	intval	$width		宽度
 * @param	intval	$height		高度
 * @param	string	$next_url	下一集url
 * @param	string	$title		视频分享标题
 * @param	string	$url		视频分享url
 * @param	string	$desc		视频分享描述
 * @param	string	$thumb		视频分享图片
 * @return	array
 */
function dr_player($name, $value, $width, $height, $next_url = '', $title = '', $url = '', $desc = '', $thumb = '') {
	
	$file = dr_get_file($value['file']);
	$str = '
	<div id="video" style="position:relative;z-index: 51;width:'.$width.'px;height:'.$height.'px;"><div id="a1"></div></div>
	<script type="text/javascript" src="'.SITE_URL.'player/offlights.js"></script>
	<script type="text/javascript" src="'.SITE_URL.'player/ckplayer.js" charset="utf-8"></script>
	<script type="text/javascript">
		var flashvars={
			s:2,
			f:\''.SITE_URL.'player/video.php?url=[$pat]\',
			a:\''.$file.'\',
			c:0,
			b:1,
			h:4,
			p:1,'.PHP_EOL;
	// 定时点处理
	if ($value['point']) {
		$k = $n = '';
		foreach ($value['point'] as $time => $note) {
			$k.= $time.'|';
			$n.= $note.'|';
		}
		$str.='			k:\''.trim($k, '|').'\','.PHP_EOL;
		$str.='			n:\''.trim($n, '|').'\','.PHP_EOL;
	}
	// 广告处理
	$mod = get_module(APP_DIR, SITE_ID);
	$option = '';
	if ($mod['field'][$name]) {
		$option = $mod['field'][$name]['setting']['option'];
	} elseif ($mod['extend'][$name]) {
		$option = $mod['extend'][$name]['setting']['option'];
	}
	if ($option && $option['table']) {
		$ci	= &get_instance();
		if ($option['time'][$ci->markrule]) {
			$time = $option['time'][$ci->markrule];
			// 查询该表的数据
			$form = $ci->get_cache('form-'.SITE_ID, $option['table']);
			if ($form) {
				$data = $ci->site[SITE_ID]
						   ->order_by('id', 'RANDOM')
						   ->limit(1)
						   ->get(SITE_ID.'_form_'.$form['table'])
						   ->row_array();
				if ($data) {
					$str.= '			l:\''.dr_thumb($data['thumb']).'\','.PHP_EOL;
					$str.= '			r:\''.$data['link'].'\','.PHP_EOL;
					$str.= '			t:\''.$time.'\','.PHP_EOL;
					$str.= '			d:\''.dr_thumb($data['thumb']).'\','.PHP_EOL;
					$str.= '			u:\''.$data['link'].'\','.PHP_EOL;
					
					$member = '<style>.dr_adv td, .dr_adv th { border-bottom: 1px solid #EEEEEE;height: 22px;line-height: 22px;padding-bottom: 3px;padding-top: 3px;}.dr_adv td{ text-align:right;}</style><table class="dr_adv">';
					$MEMBER = $ci->get_cache('member');
					$member.= '<tr>';
					$member.= '	<th align="left" width="200">'.lang('m-347').'</th>';
					$member.= '	<td align="left">';
					$member.= ' '.(int)$option['time'][0].lang('m-346');
					$member.= ' </td>';
					$member.= '</tr>';
					foreach ($MEMBER['group'] as $group) {
						if ($group['id'] > 2) {
							$member.= '<tr>';
							$member.= '	<th align="left">'.$group['name'].'</th>';
							$member.= '	<td align="left"></td>';
							$member.= '</tr>';
							foreach ($group['level'] as $level) {
								$id = $group['id'].'_'.$level['id'];
								$member.= '<tr>';
								$member.= '<th align="left" style="padding-left:40px">'.$level['name'].'&nbsp;&nbsp;'.dr_show_stars($level['stars']).'</th>';
								$member.= '<td align="left">';
								$member.= ''.(int)$option['time'][$id].lang('m-346');
								$member.= '</td>';
								$member.= '</tr>';
							}
						} else {
							$member.= '<tr>';
							$member.= '	<th align="left">'.$group['name'].'</th>';
							$member.= '	<td align="left">';
							$member.= ' '.(int)$option['time'][$group['id']].lang('m-346');
							$member.= ' </td>';
							$member.= '</tr>';
						}
					}
					$member.= '<tr>';
					$member.= '	<td style="border:none;text-align:center;"><a href="'.MEMBER_URL.'index.php?c=account&m=upgrade" target="_blank" style="color:red;">'.lang('m-348').'</a></td>';
					$member.= '</tr>';
					$member.= '</table>';
				}
			}
		}
	}
	if ($next_url) {
		$str.= '			e:0,'.PHP_EOL;
	} else {
		$str.= '			e:2,'.PHP_EOL;
	}
	$str.= '			my_title:\''.$title.'\',
			my_url:\''.$url.'\',
			my_summary:\''.str_replace(array('\'', '"'), '', dr_clearhtml(dr_strcut($desc, 200))).'\',
			my_pic:\''.dr_get_file($thumb).'\'
		};
		var params={
			bgcolor:\'#FFF\',
			allowFullScreen:true,
			allowScriptAccess:\'always\'
		};
		CKobject.embedSWF(\''.SITE_URL.'player/ckplayer.swf\',\'a1\',\'ckplayer_a1\',\''.$width.'\',\''.$height.'\',flashvars,params);';
	if ($next_url) {
		$str.= 'function playerstop(){
			location.href="'.$next_url.'";
		}';
	}
	$str.= '
		function ckadjump(){
			var throughBox = art.dialog.through;
			throughBox({
				content: \''.$member.'\',
				lock: true,
				opacity: 0.1
			});
		}
		var box = new LightBox();
		function closelights(){
			box.Show();
			CKobject._K_(\'video\').style.width=\''.$width.'px\';
			CKobject._K_(\'video\').style.height=\''.$height.'px\';
			swfobject.getObjectById(\'ckplayer_a1\').width='.$width.';
			swfobject.getObjectById(\'ckplayer_a1\').height='.$height.';
		}
		function openlights(){
			box.Close();
			CKobject._K_(\'video\').style.width=\''.$width.'px\';
			CKobject._K_(\'video\').style.height=\''.$height.'px\';
			swfobject.getObjectById(\'ckplayer_a1\').width='.$width.';
			swfobject.getObjectById(\'ckplayer_a1\').height='.$height.';
		}
	</script>
	';
	
	return $str;
	
}

/**
 * 验证码图片获取
 */
function dr_code($width, $height, $url = '') {
	$url = $url.'index.php?c=home&m=captcha&width='.$width.'&height='.$height;
	return '<img align="absmiddle" style="cursor:pointer;" onclick="this.src=\''.$url.'&\'+Math.random();" src="'.$url.'" />';
}
 
/**
 * 排序操作
 */
function ns_sorting($name) {

	$value = $_GET['order'] ? $_GET['order'] : '';
	if (!$value) return 'sorting';
	
	if (strpos($value, $name) === 0 && strpos($value, 'asc') !== FALSE) {
		return 'sorting_asc';
	} elseif (strpos($value, $name) === 0 && strpos($value, 'desc') !== FALSE) {
		return 'sorting_desc';
	}
	
	return 'sorting';
}

/**
 * 移除order字符串
 */
function dr_member_order($url) {
	
	$data = @explode('&', $url);
	if ($data) {
		foreach ($data as $t) {
			if (strpos($t, 'order=') === 0) {
				$url = str_replace('&'.$t, '', $url);
			} elseif (strpos($t, 'action=') === 0) {
				$url = str_replace('&'.$t, '', $url);
			}
		}
	}
	
	return $url;
}
 
/**
 * 统计图表调用
 */ 
function dr_chart($file, $width, $height) {
	
	$str = '';
	$id = rand(0, 99999);
	
	if (!defined('FINECMS_CHART')) {
		$str.= '<script type="text/javascript" src="'.MEMBER_URL.'statics/js/chart/js/swfobject.js"></script>';
		define('FINECMS_CHART', 1);//防止重复加载JS
	}
	
	$str.= '<script type="text/javascript">';
	$str.= 'swfobject.embedSWF(';
	$str.= '"'.MEMBER_URL.'statics/js/chart/open-flash-chart.swf", "my_chart_'.$id.'",';
	$str.= '"'.$width.'", "'.$height.'", "9.0.0", "expressInstall.swf",';
	$str.= '{"data-file":"'.$file.'"} );';
	$str.= '</script>';
	$str.= '<div id="my_chart_'.$id.'"></div>';
	
	echo $str;
}
 
/**
 * 百度地图调用
 */
function dr_baidu_map($value, $zoom = 5, $width = 600, $height = 400) {

    if (!$value) return NULL;
	
	$id = 'dr_map_'.rand(0, 99);
	list($lngX, $latY) = explode(',', $value);
	
	return '<script type=\'text/javascript\' src=\'http://api.map.baidu.com/api?v=1.4\'></script>
	<div id="'.$id.'" style="width:' . $width . 'px; height:' . $height . 'px; overflow:hidden"></div>
	<script type="text/javascript">
	var mapObj=null;
	lngX = "' . $lngX . '";
	latY = "' . $latY . '";
	zoom = "' . $zoom . '";		
	var mapObj = new BMap.Map("'.$id.'");
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	mapObj.addControl(ctrl_nav);
	mapObj.enableDragging();
	mapObj.enableScrollWheelZoom();
	mapObj.enableDoubleClickZoom();
	mapObj.enableKeyboard();//启用键盘上下左右键移动地图
	mapObj.centerAndZoom(new BMap.Point(lngX,latY),zoom);
	drawPoints();
	function drawPoints(){
		var myIcon = new BMap.Icon("' . SITE_URL . 'dayrui/statics/images/mak.png", new BMap.Size(27, 45));
		var center = mapObj.getCenter();
		var point = new BMap.Point(lngX,latY);
		var marker = new BMap.Marker(point, {icon: myIcon});
		mapObj.addOverlay(marker);
	}
	</script>';
}
 
/**
 * 模块字段的选项值（用于options参数的字段，如复选框、下拉选择框、单选按钮）
 *
 * @param	string	$name
 * @param	intval	$catid
 * @param	string	$dirname
 * @return	array
 */
function dr_field_options($name, $catid = 0, $dirname = APP_DIR) {
	
	if (!$name) return NULL;
	
	$module = get_module($dirname, SITE_ID);
	if (!$module) return NULL;
	
	$field = $catid && isset($module['category'][$catid]['field'][$name]) ? $module['category'][$catid]['field'][$name] : $module['field'][$name];
	if (!$field) return NULL;
	
	$option = $field['setting']['option']['options'];
	if (!$option) return NULL;
	
	$data = explode(PHP_EOL, str_replace(array(chr(13), chr(10)), PHP_EOL, $option));
	$return = array();
	
	foreach ($data as $t) {
		if ($t) {
			list($i, $v) = explode('|', $t);
			$v = is_null($v) || !strlen($v) ? trim($i) : trim($v);
			$return[trim($i)] = $v;
		}
	}
	
	return $return;
}

/**
 * 会员字段的选项值（用于options参数的字段，如复选框、下拉选择框、单选按钮）
 *
 * @param	string	$name
 * @param	intval	$catid
 * @param	string	$dirname
 * @return	array
 */
function dr_member_field_options($name) {
	
	if (!$name) return NULL;
	
	$ci	= &get_instance();
	$field = $ci->get_cache('member', 'field', $name);
	if (!$field) return NULL;
	
	$option = $field['setting']['option']['options'];
	if (!$option) return NULL;
	
	$data = explode(PHP_EOL, str_replace(array(chr(13), chr(10)), PHP_EOL, $option));
	$return = array();
	
	foreach ($data as $t) {
		if ($t) {
			list($i, $v) = explode('|', $t);
			$v = is_null($v) || !strlen($v) ? trim($i) : trim($v);
			$return[trim($i)] = $v;
		}
	}
	
	return $return;
}
 
/**
 * 文本块内容
 *
 * @param	intval	$id
 * @return	array
 */
function dr_block($id, $type = 0) {
	$ci	= &get_instance();
	return $ci->get_cache('block-'.SITE_ID, $id, $type);
}

/**
 * 联动菜单调用
 *
 * @param	string	$code	菜单代码
 * @param	intval	$id		菜单id
 * @param	intval	$level	调用级别，1表示顶级，2表示第二级，等等
 * @param	string	$name	菜单名称，如果有显示它的值，否则返回数组
 * @return	array
 */
function dr_linkage($code, $id, $level = 0, $name = '') {

	$ci	= &get_instance();
	$link = $ci->get_cache('linkage-'.SITE_ID.'-'.$code);
	
	$data = $link[$id];
	$pids = @explode(',', $data['pids']);
	if ($level == 0) return $name ? $data[$name] : $data;
	
	if (!$pids) return $name ? $data[$name] : $data;
	$i = 1;
	
	foreach ($pids as $pid) {
		if ($pid) {
			if ($i == $level) return $name ? $link[$pid][$name] : $link[$pid];
			$i++;
		}
	}
	
	return $name ? $data[$name] : $data;
}
 
/**
 * 记录信息调用
 *
 * @param	string	$string
 * @return	string
 */
function dr_lang_note($string) {
 
	$string = trim($string);
	if (!$string) return '';
	
	if (strpos($string, 'lang') === 0) {
		$data = explode(',', $string);
		unset($data[0]);
		return call_user_func_array('dr_lang', $data);
	}
	
	return $string;
}
 
/**
 * 会员头像
 *
 * @param	intval	$uid
 * @param	string	$size
 * @return	string
 */
function dr_avatar($uid, $size = '45') {

	if (!$uid) return $size == 45 ? SITE_URL.'dayrui/statics/images/avatar_45.png' : SITE_URL.'dayrui/statics/images/avatar_90.png';
	
	$ci	= &get_instance();
	$member = $ci->get_cache('member');
	
	if ($member['setting']['ucenter']) {
		$data = $ci->db->select('username')->where('uid', $uid)->limit(1)->get($ci->db->dbprefix('member'))->row_array();
		list($ucenter) = uc_get_user($data['username']);
		return UC_API.'/avatar.php?uid='.$ucenter.'&size='.($size == 45 ? 'small' : 'big');
	}
	
	if (is_file(FCPATH.'member/uploadfile/member/'.$uid.'/'.$size.'x'.$size.'.jpg')) return SITE_URL.'member/uploadfile/member/'.$uid.'/'.$size.'x'.$size.'.jpg';
	$data = $ci->db->select('avatar')->where('uid', $uid)->limit(1)->get('member')->row_array();
	
	return isset($data['avatar']) && $data['avatar'] ? $data['avatar'] : ($size == 45 ? SITE_URL.'dayrui/statics/images/avatar_45.png' : SITE_URL.'dayrui/statics/images/avatar_90.png');
}

/**
 * 是否是一个有效的应用
 *
 * @param	string	$name
 * @return	bool
 */
function dr_is_app($name) {

	if (!$name || !is_dir(FCPATH.'app/'.$name)) return FALSE;
	
	return TRUE;
}
 
/**
 * 显示星星
 *
 * @param	intval	$num
 * @param	intval	$starthreshold	星星数在达到此阈值(设为 N)时，N 个星星显示为 1 个月亮、N 个月亮显示为 1 个太阳。
 * @return	string
 */
function dr_show_stars($num, $starthreshold = 4) {

	$str = '';
	$alt = 'alt="Rank: '.$num.'"';
	
	for ($i = 3; $i > 0; $i--) {
		$numlevel = intval($num / pow($starthreshold, ($i - 1)));
		$num = ($num % pow($starthreshold, ($i - 1)));
		for ($j = 0; $j < $numlevel; $j++) {
			$str.= '<img align="absmiddle" src="'.SITE_URL.'dayrui/statics/images/star_level'.$i.'.gif" '.$alt.' />';
		}
	}
	
	return $str;
}

/**
 * 模块内容阅读量显示js
 *
 * @param	intval	$id
 * @return	string
 */
function dr_show_hits($id) {
	return "<script type=\"text/javascript\" src=\"".SITE_URL."index.php?c=api&m=hits&module=".APP_DIR."&id={$id}\"></script>";
}

/**
 * 模型内容阅读量显示js
 *
 * @param	intval	$id
 * @return	string
 */
function dr_space_show_hits($mid, $id) {
	return "<script type=\"text/javascript\" src=\"".MEMBER_URL."index.php?c=api&m=hits&mid=".$mid."&id={$id}\"></script>";
}

/**
 * 调用远程数据
 *
 * @param	string	$url
 * @return	string
 */
function dr_catcher_data($url) {

    if (ini_get('allow_url_fopen')) {
	    return @file_get_contents($url);
	} elseif (function_exists('curl_init') && function_exists('curl_exec')) {
	    $ch = curl_init($url);
	    $data = '';
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	return NULL;
}

/**
 * 附件信息
 *
 * @param	intval	$id
 * @return  array
 */
function get_attachment($id) {
	
	if (!$id) return NULL;
	
	$ci	= &get_instance();
	$info = $ci->get_cache_data("attachment-{$id}");
	if ($info) return $info; // 附件缓存
	
	$info = $ci->db
			   ->select('tableid')
			   ->where('id', (int)$id)
			   ->limit(1)
			   ->get('attachment')
			   ->row_array();
	if (!$info) return NULL;
	
	$info = $ci->db
			   ->select('id,uid,filename,fileext,filesize,attachment,remote')
			   ->where('id', (int)$id)
			   ->limit(1)
			   ->get('attachment_'.(int)$info['tableid'])
			   ->row_array();
			   
	if (!$info) { // 未使用的文件查找
		$info = $ci->db
				   ->select('id,uid,filename,fileext,filesize,attachment,remote')
				   ->where('id', (int)$id)
				   ->limit(1)
				   ->get('attachment_unused')
				   ->row_array();
	}
	if (!$info) return NULL;
	
	$info['attachment'] = $info['remote'] ? SITE_ATTACH_URL.'/'.$info['attachment'] : $info['attachment']; // 远程图片
	$info['_attachment'] = $info['attachment'];
	$ci->set_cache_data("attachment-{$id}", $info, 36000); // 保存附件缓存
	
	return $info;
}
 
/**
 * 图片显示
 *
 * @param	string	$img	图片id或者路径
 * @param	intval	$width	输出宽度
 * @param	intval	$height	输出高度
 * @param	intval	$water	是否水印
 * @return  url
 */
function dr_thumb($img, $width = NULL, $height = NULL, $water = 1) {
	
	if (!$img) return SITE_URL.'dayrui/statics/images/nopic.gif';
	
	if (is_numeric($img)) { // 表示附件id
		$info = get_attachment($img);
		if ($width || $height) return MEMBER_URL."index.php?c=api&m=thumb&id=$img&width=$width&height=$height&water=$water";
		$img = $info['attachment'];
		unset($info);
	}
	
	$img = dr_file($img);
	
	return $img ? $img : SITE_URL.'dayrui/statics/images/nopic.gif';
}
 
/**
 * 下载文件
 *
 * @param	string	$id
 * @return  array
 */
function dr_down_file($id) {
	
	if (!$id) return '';
	
	if (is_numeric($id)) { // 表示附件id
		$info = get_attachment($id);
		if ($info) return MEMBER_URL."index.php?c=api&m=file&id=$id";
	}
	
	$file = dr_file($id);
	
	return $file ? $file : '';
}

/**
 * 文件真实地址
 *
 * @param	string	$id
 * @return  array
 */
function dr_get_file($id) {
	
	if (!$id) return '';
	
	if (is_numeric($id)) { // 表示附件id
		$info = get_attachment($id);
		$id = $info['attachment'] ? $info['attachment'] : '';
	}
	
	$file = dr_file($id);
	
	return $file ? $file : '';
}

/**
 * 完整的文件路径
 *
 * @param	string	$url
 * @return  string
 */
function dr_file($url) {
	
	if (!$url || strlen($url) == 1) return NULL;
    if (substr($url, 0, 7) == 'http://') return $url;
    if (strpos($url, SITE_PATH) !== FALSE && SITE_PATH != '/') return $url;
    if (substr($url, 0, 1) == '/') $url = substr($url, 1);
	
    return SITE_URL.$url;
}

/**
 * 格式化自定义字段内容
 *
 * @param	string	$field	字段类型
 * @param	string	$value	字段值
 * @param	array	$cfg	字段配置信息
 * @return
 */
function dr_get_value($field, $value, $cfg = NULL) {

	$ci	= &get_instance();
	$ci->load->library('dfield', array(APP_DIR));
	
	$obj = $ci->dfield->get($field);
	if (!$obj) return $value;
	
	return $obj->output($value, $cfg);
}
 
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function dr_safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	return $string;
}
 
/**
 * 字符截取
 *
 * @param	string	$str
 * @param	intval	$length
 * @param	string	$dot
 * @return  string
 */
function dr_strcut($string, $length, $dot = '...') {

    $charset = 'utf-8';
	if (strlen($string) <= $length) return $string;
	
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$strcut = '';
	
	if (strtolower($charset) == 'utf-8') {
		$n = $tn = $noc = 0;
		while ($n < strlen($string)) {
			$t = ord($string[$n]);
			if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif (194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif (224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif (240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif (248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif ($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if ($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	} else {
		for ($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
		}
	}
	
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	
	return $strcut.$dot;
}

/**
 * 清除HTML标记
 *
 * @param	string	$str
 * @return  string
 */
function dr_clearhtml($str) {

    $str = str_replace(
		array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'),
		array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'),
		$str
	);
	
    $str = preg_replace("/\<[a-z]+(.*)\>/iU", "", $str);
    $str = preg_replace("/\<\/[a-z]+\>/iU", "", $str);
    $str = preg_replace("/{.+}/U", "", $str);
    $str = str_replace(array(' ','	', chr(13), chr(10), '&nbsp;'), '', $str);
	$str = strip_tags($str);
	
    return trim($str);
}
 
/**
 * 模块缓存数据
 *
 * @param	string	$dirname	名称
 * @param	intval	$siteid		站点id
 * @return  array
 */
function get_module($dirname, $siteid = SITE_ID) {
	
	$ci	= &get_instance();
	$ci->load->library('dcache');
	$data = $ci->get_cache('module-'.$siteid.'-'.$dirname);
	
	if (!isset($data)) {
		$ci->load->model('module_model');
		$ci->module_model->cache($dirname);
		$data = $ci->get_cache('module-'.$siteid.'-'.$dirname);
	}
	
	return $data;
}

/**
 * 随机颜色
 *
 * @return	string
 */
function dr_random_color() {

    $str = '#';
	
    for ($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'A'; break;
            case 11: $randNum = 'B'; break;
            case 12: $randNum = 'C'; break;
            case 13: $randNum = 'D'; break;
            case 14: $randNum = 'E'; break;
            case 15: $randNum = 'F'; break;
        }
        $str .= $randNum;
    }
	
    return $str;
}

/**
 * 友好时间显示函数
 *
 * @param	int		$time	时间戳
 * @return	string
 */
function dr_fdate($time) {

    if (!$time) return '';
	
	$t = time() - $time;
    $f = array(
        '31536000' => '年',
        '2592000' => '个月',
        '604800' => '星期',
        '86400' => '天',
        '3600' => '小时',
        '60' => '分钟',
        '1' => '秒',
    );
	
    foreach ($f as $k => $v) {
        if ( 0 != $c = floor($t / (int)$k) ) {
            $m = floor($t % $k);
            foreach ($f as $x => $y) {
                if ( 0 != $r = floor($m / (int)$x) ) {
                    return $c.$v.$r.$y.'前';
                }
            }
            return $c.$v.'前';
        }
    }
	
}

/**
 * 时间显示函数
 *
 * @param	int		$time	时间戳
 * @param	string	$format	格式与date函数一致
 * @param	string	$color	当天显示颜色
 * @return	string
 */
function dr_date($time = NULL, $format = SITE_TIME_FORMAT, $color = NULL) {
	
	$time = (int)$time;
	if (!$time) return '';
	
	$format	= $format ? $format : SITE_TIME_FORMAT;
	$string = date($format, $time);
	if (strpos($string, '1970') !== FALSE) return '';
	
	return $color && $time >= strtotime(date('Y-m-d 00:00:00')) && $time <= strtotime(date('Y-m-d 23:59:59'))  ? '<font color="'.$color.'">'.$string.'</font>' : $string;
}

/**
 * JSON数据输出
 *
 * @param	int				$status	状态
 * @param	string|array	$code	返回数据
 * @param	string|int		$id		表单名称|返回Id
 * @return	string
 */
function dr_json($status, $code = '', $id = 0) {
	return json_encode(array('status' => $status, 'code' => $code, 'id' => $id));
}

/**
 * 多语言输出
 *
 * @param	多个参数
 * @return	string|NULL
 */
function dr_lang() {

	$param	= func_get_args();
	if (empty($param)) return NULL;
	if (count($param) == 1) return lang($param[0]);
	
	// 取第一个作为语言名称
	$string	= $param[0];
	unset($param[0]);
	
	return vsprintf(lang($string), $param);
}

/**
 * 将对象转换为数组
 *
 * @param	object	$obj	数组对象
 * @return	array
 */
function dr_object2array($obj) {
	$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
	if ($_arr && is_array($_arr)) {
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? dr_object2array($val) : $val;
			$arr[$key] = $val;
		}
	}
	return $arr;
}

/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array
 */
function dr_string2array($data) {
	return $data ? (is_array($data) ? $data : unserialize(stripslashes($data))) : array();
}

/**
 * 将数组转换为字符串
 *
 * @param	array	$data	数组
 * @return	string
 */
function dr_array2string($data) {
	return $data ? addslashes(serialize($data)) : '';
} 
 
/**
 * 递归创建目录
 *
 * @param	string	$dir	目录名称
 * @return	bool|void
 */
function dr_mkdirs($dir) {
	if (!$dir) return FALSE;
    if (!is_dir($dir)) {
        dr_mkdirs(dirname($dir));
        mkdir($dir, 0777);
    }
}

/**
 * 设置表单 input 或者 textarea 字段的值
 *
 * @param	string	$name	表单名称data[$name]
 * @param	string	$value	修改时的值$data[$name]
 * @return	string	
 */
function dr_set_value($name, $value = NULL) {
	return isset($_POST['data'][$name]) ? $_POST['data'][$name] : $value;
}

/**
 * 设置表单 select 字段的值
 *
 * @param	string	$name	表单名称data[$name]
 * @param	string	$value	修改时的值$data[$name]
 * @return	string	
 */
function dr_set_select($name, $value = NULL, $field = NULL, $default = FALSE) {
	$value = dr_set_value($name, $value);
	if ($value === NULL && $default == TRUE) return ' selected';
	if ($value == $field) return ' selected';
}

/**
 * 设置表单 radio 字段的值
 *
 * @param	string	$name		表单名称data[$name]
 * @param	string	$value		修改时的值$data[$name]
 * @param	string	$field		当前选项的value值
 * @param	string	$default	默认选中状态
 * @return	string|void
 */
function dr_set_radio($name, $value = NULL, $field = NULL, $default = FALSE) {
	$value = dr_set_value($name, $value);
	if ($value === NULL && $default == TRUE) return ' checked';
	if ($value == $field) return ' checked';
}

/**
 * 设置表单 checkbox 字段的值
 *
 * @param	string	$name		表单名称data[$name]
 * @param	array	$value		修改时的值$data[$name] 复选框为数组格式值
 * @param	string	$field		当前选项的value值
 * @param	string	$default	默认选中状态
 * @return	string|void
 */
function dr_set_checkbox($name, $value = NULL, $field = NULL, $default = FALSE) {
	$value = dr_set_value($name, $value);
	if ($value === NULL && $default == TRUE) return ' checked';
	if (@is_array($value) && in_array($field, $value)) return ' checked';
}

/**
 * 汉字转为拼音
 *
 * @param	string	$word
 * @return	string
 */
function dr_word2pinyin($word) {
    if (!$word) return '';
	$ci	= &get_instance();
	$ci->load->library('pinyin');
	return $ci->pinyin->result($word);
}

/**
 * 格式化输出文件大小
 *
 * @param	int	$fileSize	大小
 * @param	int	$round		保留小数位
 * @return	string
 */
function dr_format_file_size($fileSize, $round = 2) {

    if (!$fileSize) return 0;
	
	$i = 0;
	$inv = 1 / 1024;
	$unit = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
	
	while ($fileSize >= 1024 && $i < 8) {
		$fileSize *= $inv;
		++$i;
	}
	
	$temp = sprintf("%.2f", $fileSize);
	$value = $temp - (int)$temp ? $temp : $fileSize;
	
	return round($value, $round).$unit[$i];
}

/**
 * 关键字高亮显示
 *
 * @param	string	$string		字符串
 * @param	string	$keyword	关键字
 * @return	string
 */
function dr_keyword_highlight($string, $keyword) {
	return $keyword != '' ? str_ireplace($keyword, '<font color=red><strong>'.$keyword.'</strong></font>', $string) : $string;
}

function dollar($value, $include_cents = TRUE) {
	if (!$include_cents) {
		return "$".number_format($value);
	} else {
		return "$".number_format($value, 2, '.', ',');
	}
}

/**
 * Base64加密
 *
 * @param	string	$string
 * @return	string
 */
function dr_base64_encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+', '/', '='),array('-', '_', ''), $data);
	return $data;
}

/**
 * Base64解密
 *
 * @param	string	$string
 * @return	string
 */
function dr_base64_decode($string) {
	$data = str_replace(array('-', '_'),array('+' ,'/'),$string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

// 兼容老版本

/**
 * 将语言转为实际内容
 *
 * @param	array	$_name	语言名称
 * @param	string	$lang	语言名称
 * @return	string
 */
function dr_lang2name($_name, $lang = SITE_LANGUAGE) {

	if (!$_name) return NULL;
	
	$name = dr_string2array($_name);
	if (!$name) return lang($_name);
	
	return isset($name[$lang]) ? $name[$lang] : $name['zh-cn'];
	
}

/**
 * 将实际内容转为语言
 *
 * @param	string	$value	实际内容
 * @param	array	$data	原语言数据
 * @return	string
 */
function dr_name2lang($value, $data = array()) {

	if (!is_array($data)) $data = dr_string2array($data);
	
	if (!isset($data['zh-cn'])) $data['zh-cn'] = $value;
	$data[SITE_LANGUAGE] = $value;
	
	return dr_array2string($data);
}

/**
 * 将数组转化为xml格式
 *
 * @param	array	$arr		数组
 * @param	bool	$htmlon		是否开启html模式
 * @param	bool	$isnormal	是否不全空格
 * @param	intval	$level		当前级别
 * @return	string
 */
function dr_array2xml($arr, $htmlon = TRUE, $isnormal = FALSE, $level = 1) {
	$space = str_repeat("\t", $level);
	$string = $level == 1 ? "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<result>\r\n" : '';
	foreach ($arr as $k => $v) {
		if (!is_array($v)) {
			$string.= $space."<$k>".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</$k>\r\n";
		} else {
			$name = is_numeric($k) ? 'item'.$k : $k;
			$string.= $space."<$name>\r\n".dr_array2xml($v, $htmlon, $isnormal, $level + 1).$space."</$name>\r\n";
		}
	}
	$string = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $string);
	return $level == 1 ? $string.'</result>' : $string;
}