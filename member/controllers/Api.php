<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Api调用类
 * Dayrui Website Management System
 *
 * @since		version 2.0.3
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class Api extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 内容关联字段数据读取
     */
	public function related() {
		
		// 强制将模板设置为后台
		$this->template->admin();
		
		// 登陆判断
		if (!$this->uid) $this->admin_msg(lang('m-039'));
		
		// 参数判断
		$dirname = $this->input->get('module');
		if (!$dirname) $this->admin_msg(lang('m-101'));
		
		// 模块缓存判断
		$module = $this->get_cache('module-'.SITE_ID.'-'.$dirname);
		if (!$module) $this->admin_msg(dr_lang('m-102', $dirname));
		
		// 加载后台用到的语言包
		$this->lang->load('admin');
		$this->lang->load('template');
		
		$db = $this->site[SITE_ID];
		$field = $module['field'];
		
		$field['catid'] = array(
			'name' => '栏目id',
			'ismain' => 1,
			'fieldtype' => 'Text',
			'fieldname' => 'catid',
		);
		
		if ($this->member['admin']) {
			$field['author'] = array(
				'name' => lang('101'),
				'ismain' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'author',
			);
		} else {
			$db->where('uid', $this->uid);
		}
		
		if (IS_POST) {
			$data = $this->input->post('data');
			if (isset($data['keyword']) && $data['keyword'] && $data['field'] && isset($field[$data['field']])) {
				$db->like($data['field'], urldecode($data['keyword']));
			}
		}
		
		$list = $db->limit(30)
				   ->order_by('updatetime DESC')
				   ->select('id,title,updatetime,url')
				   ->get(SITE_ID.'_'.$dirname)
				   ->result_array();
				   
		$this->template->assign(array(
			'list' => $list,
			'param' => $data,
			'field' => $field,
		));
		$this->template->display('related.html', 'admin');
	}
	
	/**
     * 检查新提醒
     */
	public function notice() {
		if ($this->uid) {
			$value = $this->db->where('uid', (int)$this->uid)->count_all_results('member_new_notice');
		} else {
			$value = 0;
		}
		$callback = isset($_GET['callback']) ? $_GET['callback'] : 'callback';
		exit($callback . '(' . json_encode(array('status' => $value)) . ')');
	}
	
	/**
     * 检测会员在线情况
     */
	public function online() {
		
		$uid = (int)$this->input->get('uid');
		$type = (int)$this->input->get('type');
		$icon = MEMBER_THEME_PATH.'images/';
		$online = 0;
		
		if ($this->db->where('uid', $uid)->count_all_results('member_session')) {
			$icon.= 'web'.$type.'.gif';
			$online = 1;
		} else {
			$icon.= 'web'.$type.'-off.gif';
			$online = 0;
		}
		
		$member = $this->db
					   ->select('username')
					   ->where('uid', $uid)
					   ->limit(1)
					   ->get('member')
					   ->row_array();
		
		$string = '<img src="'.$icon.'" align="absmiddle" style="cursor:pointer" onclick="dr_chat(this)" username="'.$member['username'].'" uid='.$uid.' online='.$online.'>';
		
		exit("document.write('$string');");
	}
	
	/**
     * 会员模块数据统计
     */
	public function chart_module() {
		
		$uid = (int)$this->input->get('uid');
		$data = array();
		include FCPATH.'dayrui/libraries/Chart/open-flash-chart.php';
		
		$title = new title(lang('m-333'));
		$title->set_style("{font-size: 16px; color:#000000; font-family: 微软雅黑; text-align: center;}");
		$color = array(
			'#0000FF',
			'#CC2EFA',
			'#04B486',
			'#B43104',
			'#CECEF6',
			'#F6CED8',
			'#8A0868'
		);
		$module = $this->get_module(SITE_ID);
		if ($module) {
			$i = 0;
			$db = $this->site[SITE_ID];
			foreach ($module as $dir => $m) {
				if (!$this->_module_post_catid($m)) continue;
				$total = $db->where('uid', $uid)->count_all_results(SITE_ID.'_'.$dir.'_index');
				$tmp = new pie_value($total, "");
				$tmp->set_colour($color[$i]);
				$tmp->set_label($m['name'], $color[$i], 12);
				$data[] = $tmp;
				$i ++;
				if (!isset($color[$i])) $i = 0;
			}
		}
		
		$pie = new pie();
		$pie->start_angle(35)
			//->alpha(99)
			->add_animation( new pie_fade() )
			->add_animation( new pie_bounce(count($module)) )
			->gradient_fill()
			->tooltip( '#val#' )
			->colours($color);

		$pie->set_values( $data );

		$chart = new open_flash_chart();
		$chart->set_bg_colour('#ffffff');
		$chart->set_title($title);
		$chart->add_element($pie);

		echo $chart->toPrettyString();
	
	}
	
	/**
     * 会员空间模型数据统计
     */
	public function chart_space() {
		
		$uid = (int)$this->input->get('uid');
		$data = array();
		$model = $this->get_cache('space-model');
		include FCPATH.'dayrui/libraries/Chart/open-flash-chart.php';
		
		$title = new title(lang('m-335'));
		$title->set_style("{font-size: 16px; color:#000000; font-family: 微软雅黑; text-align: center;}");
		$color = array(
			'#0000FF',
			'#CC2EFA',
			'#04B486',
			'#B43104',
			'#CECEF6',
			'#F6CED8',
			'#8A0868'
		);
		if ($model) {
			$i = 0;
			foreach ($model as $m) {
				if (!$m['setting'][$this->markrule]['use']) continue;
				$total = $this->db->where('uid', $uid)->count_all_results('space_'.$m['table']);
				$tmp = new pie_value($total, "");
				$tmp->set_colour($color[$i]);
				$tmp->set_label($m['name'], $color[$i], 12);
				$data[] = $tmp;
				$i ++;
				if (!isset($color[$i])) $i = 0;
			}
		}
		
		$pie = new pie();
		$pie->start_angle(35)
			//->alpha(99)
			->add_animation( new pie_fade() )
			->add_animation( new pie_bounce(count($module)) )
			->gradient_fill()
			->tooltip( '#val#' )
			->colours($color);

		$pie->set_values( $data );

		$chart = new open_flash_chart();
		$chart->set_bg_colour('#ffffff');
		$chart->set_title($title);
		$chart->add_element($pie);

		echo $chart->toPrettyString();
	
	}
	
	/**
     * 附件空间统计
     */
	public function chart_attachment() {
		$uid = (int)$this->input->get('uid');
		$data = array();
		include FCPATH.'dayrui/libraries/Chart/open-flash-chart.php';
		$member = $this->member_model->get_base_member($uid);
		$acount = (int)$this->get_cache('member', 'setting', 'permission', $member['markrule'], 'attachsize');
		$acount = $acount ? $acount : 1024000;
		$ucount = $this->db->select('sum(`filesize`) as total')->where('uid', $uid)->limit(1)->get('attachment')->row_array();
		$ucount = (int)$ucount['total'];
		$acount = $acount * 1024 * 1024;
		$scount = max($acount - $ucount, 0);
		$title = new title(lang('m-334'));
		$title->set_style("{font-size: 16px; color:#000000; font-family: 微软雅黑; text-align: center;}");
		$color = array(
			'#FF2D2D',
			'#0000C6'
		);
		$tmp = new pie_value(round($ucount/1024/1024, 2), "");
		$tmp->set_colour($color[0]);
		$tmp->set_label(lang('m-336'), $color[0], 12);
		$data[] = $tmp;
		$tmp = new pie_value(round($scount/1024/1024, 2), "");
		$tmp->set_colour($color[1]);
		$tmp->set_label(lang('m-337'), $color[1], 12);
		$data[] = $tmp;
		$pie = new pie();
		$pie->start_angle(35)
			->add_animation( new pie_fade() )
			->add_animation( new pie_bounce(count($module)) )
			->gradient_fill()
			->tooltip( '#val#Mb' )
			->colours($color);

		$pie->set_values( $data );
		$chart = new open_flash_chart();
		$chart->set_bg_colour('#ffffff');
		$chart->set_title($title);
		$chart->add_element($pie);
		echo $chart->toPrettyString();
	
	}
	
	/**
     * 站点间的同步登录
     */
	public function synlogin() {
		$this->api_synlogin();
	}
	
	/**
     * 站点间的同步退出
     */
	public function synlogout() {
		$this->api_synlogout();
	}
	
	/**
     * 自定义信息JS调用
     */
	public function template() {
		$this->api_template();
	}
	
	/**
	 * 伪静态测试
	 */
	public function test() {
		header('Content-Type: text/html; charset=utf-8');
		echo '服务器支持伪静态';
	}
	
	/**
	 * 联动栏目分类调用
	 */
	public function category() {
	    
		$dir = $this->input->get('module');
	    $pid = (int)$this->input->get('parent_id');
	    $json = array();
		$category = $this->get_cache('module-'.SITE_ID.'-'.$dir, 'category');
		
		foreach ($category as $k => $v) {
			if ($v['pid'] == $pid) {
				if (!$v['child'] && !$v['permission'][$this->member['mark']]['add']) continue;
				$json[] = array(
					'region_id' => $v['id'],
					'region_name' => $v['name'],
					'region_child' => $v['child']
				);
			}
		}
		
		echo json_encode($json);	
	}
	
	/**
	 * 联动菜单调用
	 */
	public function linkage() {
	    $pid = (int)$this->input->get('parent_id');
	    $code = $this->input->get('code');
	    $json = array();
		$linkage = $this->get_cache('linkage-'.SITE_ID.'-'.$code);
		foreach ($linkage as $k => $v) {
			if ($v['pid'] == $pid) {
				$json[] = array('region_id' => $v['id'], 'region_name' => $v['name']);
			}
		}
		echo json_encode($json);	
	}


	
	/**
	 * 会员登录信息JS调用
	 */
	public function userinfo() {
	    ob_start();
		$this->template->display('api.html');
		$html = ob_get_contents();
		ob_clean();
		$html = addslashes(str_replace(array("\r", "\n", "\t", chr(13)), array('', '', '', ''), $html));
	    echo 'document.write("' . $html . '");';
	}
	
	/**
     * Ajax调用字段属性表单
	 *
	 * @return void
     */
	public function field() {
	
		$id = (int)$this->input->post('id');
		$type = $this->input->post('type');
		
		$this->load->model('field_model');
		$this->relatedid = $this->input->post('relatedid');
		$this->relatedname = $this->input->post('relatedname');
		
		$data = $this->field_model->get($id);
		$fields = $this->field_model->get_data();
		if ($data) {
			$value = dr_string2array($data['setting']);
			$value = $value['option'];
		} else {
			$value = array();
		}
		
		$this->lang->load('admin');
		$this->lang->load('template');
		$this->load->library('Dfield', array($this->input->post('module')));
		$return	= $this->dfield->option($type, $value, $fields);
		
		if ($return !== 0) echo $return;
	}
	
	/**
     * 百度地图调用
	 *
	 * @return void
     */
	public function baidumap() {
		$list = $this->input->get('city') ? explode(',', urldecode($this->input->get('city'))) : NULL;
		$city = isset($list[0]) ? $list[0] : '';
		$value = $this->input->get('value');
		$value = strlen($value) > 10 ? $value : '';
		$this->template->assign(array(
			'city' => $city,
			'value' => $value,
			'list' => $list,
			'name' => $this->input->get('name'),
			'level'	=> (int)$this->input->get('level'),
			'height' => $this->input->get('height') - 30
		));
		$this->template->display('baidumap.html', 'admin');
	}
	
	/**
     * 文件上传
	 *
	 * @return void
     */
	public function upload() {
		$this->load->model('attachment_model');
		$code = str_replace(' ', '+', $this->input->get('code'));
		list($size, $ext, $path) = explode('|', dr_authcode($code, 'DECODE'));
		$notused = $this->attachment_model->get_unused($this->uid, $ext);
		$this->template->assign(array(
			'ext' => str_replace(',', '|', $ext),
			'code' => $code,
			'page' => $notused ? 4 : 0,
			'size' => (int)$size * 1024,
			'name' => $this->input->get('name'),
			'types' => '*.'.str_replace(',', ';*.', $ext),
			'fileid' => $this->input->get('filename'),
			'fcount' => (int)$this->input->get('count'),
			'notused' => $notused,
			'session' => dr_authcode($this->uid, 'ENCODE'),
		));
		$this->template->display('upload.html', 'admin');
	}
	
	/**
     * 文件上传处理
	 *
	 * @return void
     */
	public function swfupload() {
		$uid = (int)dr_authcode(str_replace(' ', '+', $this->input->post('session')), 'DECODE');
		if (!$uid) exit('0,'.lang('m-142')); // 根据页面传入的session来获取当前登录uid，未获取到uid时提示游客无法上传
		$this->member = $this->member_model->get_member($uid); // 获取会员信息
		if (!$this->member) exit('0,'.lang('m-142')); // 游客不允许上传，未获取到会员信息时提示游客无法上传
		$member_rule = $this->get_cache('member', 'setting', 'permission', $this->member['mark']); // 会员组权限
		if (!$this->member['adminid'] && !$member_rule['is_upload']) exit('0,'.lang('m-143')); // 是否允许上传附件
		if (!$this->member['adminid'] && $member_rule['attachsize']) { // 附件总大小判断
			$data = $this->db
						 ->select_sum('filesize')
						 ->where('uid', $uid)
						 ->get($this->db->dbprefix('attachment'))
						 ->row_array();
			$filesize = (int)$data['filesize'];
			if ($filesize > $member_rule['attachsize'] * 1024 * 1024) exit('0,'.dr_lang('m-147', $member_rule['attachsize'].'MB', dr_format_file_size($filesize)));
		}
		if (IS_POST) {
			$code = str_replace(' ', '+', $this->input->post('code'));
			list($size, $ext, $path) = explode('|', dr_authcode($code, 'DECODE'));
			if ($path) {
				$path = FCPATH.'member/uploadfile/'.$path.'/';
			} else {
				$path = FCPATH.'member/uploadfile/'.date('Ym', SYS_TIME).'/';
			}
			if (!is_dir($path)) dr_mkdirs($path);
			$this->load->library('upload', array(
				'max_size' => (int)$size * 1024,
				'overwrite' => FALSE,
				'file_name' => substr(md5(time()), rand(0, 20), 10),
				'upload_path' => $path,
				'allowed_types' => str_replace(',', '|', $ext),
				'file_ext_tolower' => TRUE,
			));
			if ($this->upload->do_upload('Filedata')) {
				$info = $this->upload->data();
				$this->load->model('attachment_model');
				$result = $this->attachment_model->upload($uid, $info);
				if (!is_array($result)) exit('0,'.$result);
				list($id, $file, $_ext) = $result;
				$icon = is_file(FCPATH.'dayrui/statics/images/ext/'.$_ext.'.gif') ? SITE_URL.'dayrui/statics/images/ext/'.$_ext.'.gif' : SITE_URL.'dayrui/statics/images/ext/blank.gif';
				//唯一ID,文件全路径,图标,文件名称,文件大小,扩展名
				exit($id.','.dr_file($file).','.$icon.','.str_replace(array('|', '.'.$_ext), '', $info['client_name']).','.dr_format_file_size($info['file_size'] * 1024).','.$_ext);
			} else {
				exit('0,'.$this->upload->display_errors('', ''));
			}
        }
	}
	
	/**
     * 我的附件浏览
	 *
	 * @return void
     */
	public function myfile() {
		if (!$this->uid) exit('');
		$ext = $this->input->get('ext');
		$page = max(1, (int)$this->input->get('page'));
		$total = (int)$this->input->get('total');
		$fcount = max(1, (int)$this->input->get('fcount'));
		$this->load->model('attachment_model');
		list($data, $total) = $this->attachment_model->limit_page($this->uid, $ext, $total, $page);
		$this->template->assign(array(
			'list' => $data,
			'total' => $total,
			'pages'	=> $this->get_pagination(MEMBER_PATH.'index.php?c=api&m=myfile&ext='.$ext.'&fcount='.$fcount, $total),
			'fcount' => $fcount,
		));
		$this->template->display('myfile.html', 'admin');
	}
	
	/**
	 * 网站附件浏览
	 */
	public function myattach() {
		if (!$this->member['adminid']) exit(lang('m-311'));
        $this->load->helper('directory');
		$dir = trim(trim(str_replace('.', '', $this->input->get('dir')), '/'), DIRECTORY_SEPARATOR);
		$root = SYS_ATTACHMENT_DIR ? (trim(FCPATH.trim(SYS_ATTACHMENT_DIR, '/').'/', '/').'/') : FCPATH;
		$path = $dir ? $root.$dir.'/' : $root;
		$list = array();
		$data = directory_map($path, 1);
		$fext = $this->input->get('ext');
		$exts = explode('|', $fext);
		$fcount = max(1, (int)$this->input->get('fcount'));
		$furl = dr_url('api/myattach', array('ext' => $fext, 'fcount' => $fcount));
		if ($data) {
			foreach ($data as $t) {
				if (is_dir($path.'/'.$t)) {
					$name = trim($t, DIRECTORY_SEPARATOR);
					$list[] = array(
						'type' => 'dir',
						'name' => $name,
						'icon' => SITE_URL.'dayrui/statics/images/ext/dir.gif',
						'file' => $furl.'&dir='.str_replace($root, '', $path.$name),
					);
				} else {
					$ext = trim(strrchr($t, '.'), '.');
					if ($ext != 'php' && in_array($ext, $exts)) {
						$list[] = array(
							'type' => 'file',
							'name' => $t,
							'size' => dr_format_file_size(@filesize($path.$t)),
							'file' => SITE_URL.str_replace(FCPATH, '', $path).$t,
							'icon' => is_file(FCPATH.'dayrui/statics/images/ext/'.$ext.'.gif') ? SITE_URL.'dayrui/statics/images/ext/'.$ext.'.gif' : SITE_URL.'dayrui/statics/images/ext/blank.gif',
						);
					}
				}
			}
		}
		$this->template->assign(array(
			'list' => $list,
			'path' => str_replace(FCPATH, '/', $path),
			'purl' => $furl.'&dir='.dirname(str_replace(FCPATH, '/', $path)),
			'parent' => $dir,
			'fcount' => $fcount,
		));
		$this->template->display('myattach.html', 'admin');
	}
	
	/**
     * Ueditor上传(图片)
	 * 向浏览器返回数据json数据
     * {
     *   'url'      :'a.jpg',   //保存后的文件路径
     *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
     *   'original' :'b.jpg',   //原始文件名
     *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
     * }
	 * @return void
     */
	public function ueupload() {
		if (!$this->uid) exit("{'url':'','title':'','original':'','state':'".lang('m-039')."'}");
		if (!$this->member['adminid'] && !$this->member_rule['is_upload']) exit("{'url':'','title':'','original':'','state':'".lang('m-143')."'}"); // 是否允许上传附件
		if (!$this->member['adminid'] && $this->member_rule['attachsize']) { // 附件总大小判断
			$data = $this->db
						 ->select_sum('filesize')
						 ->where('uid', $this->uid)
						 ->get($this->db->dbprefix('attachment'))
						 ->row_array();
			$filesize = (int)$data['filesize'];
			if ($filesize > $this->member_rule['attachsize'] * 1024 * 1024) {
				exit("{'url':'','title':'','original':'','state':'".dr_lang('m-147', $this->member_rule['attachsize'].'MB', dr_format_file_size($filesize))."'}");
			}
		}
		$path = FCPATH.'member/uploadfile/'.date('Ym', SYS_TIME).'/';
		if (!is_dir($path)) dr_mkdirs($path);
		$this->load->library('upload', array(
			'max_size' => '999999',
			'overwrite' => FALSE, // 是否覆盖
			'file_name' => substr(md5(time()), 0, 10), // 文件名称
			'upload_path' => $path, // 上传目录
			'allowed_types' => 'gif|jpg|png',
		));
		if ($this->upload->do_upload('upfile')) {
			$info = $this->upload->data();
			$this->load->model('attachment_model');
			$result = $this->attachment_model->upload($this->uid, $info);
			if (!is_array($result)) exit('0,'.$result);
			list($id, $file, $_ext) = $result;
			$title = htmlspecialchars($this->input->post('pictitle', TRUE), ENT_QUOTES);
			exit("{'id':'".$id."', 'url':'".dr_file($file)."','title':'".$title."','original':'" . str_replace('|', '_', $info['client_name']) . "','state':'SUCCESS'}");
		} else {
			exit("{'url':'','title':'','original':'','state':'".$this->upload->display_errors('', '')."'}");
		}
	}
	
	/**
     * Ueditor下载远程图片
	 * 返回数据格式
	 * {
	 *   'id'   : '新图片id一ue_separate_ue新地址二ue_separate_ue新地址三',
	 *   'url'   : '新地址一ue_separate_ue新地址二ue_separate_ue新地址三',
	 *   'srcUrl': '原始地址一ue_separate_ue原始地址二ue_separate_ue原始地址三'，
	 *   'tip'   : '状态提示'
	 * }
	 * @return void
     */
	public function uecatcher() {
		if (!$this->uid) return NULL;
		if (!$this->member['adminid'] && !$this->member_rule['is_upload']) return NULL; // 是否允许上传附件
		if (!$this->member['adminid'] && $this->member_rule['attachsize']) { // 附件总大小判断
			$data = $this->db
						 ->select_sum('filesize')
						 ->where('uid', $this->uid)
						 ->get($this->db->dbprefix('attachment'))
						 ->row_array();
			$filesize = (int)$data['filesize'];
			if ($filesize > $this->member_rule['attachsize'] * 1024 * 1024) return NULL;
		}
		$this->load->model('attachment_model');
		$uri = str_replace("&amp;", "&", htmlspecialchars($this->input->post('upfile', TRUE)));
		$urls = explode("ue_separate_ue", $uri);
        $down = $src = $id = array();
		foreach ($urls as $url) {
			$result = $this->attachment_model->catcher($this->uid, $url);
			if (is_array($result)) {
				$id[] = $result[0];
				$src[] = $url;
				$down[] = dr_file($result[1]);
			}
		}
		echo "{'id':'" . implode("ue_separate_ue" , $id ) . "', 'url':'" . implode("ue_separate_ue" , $down ) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
	}
	
	/**
     * Ueditor未使用的图片
	 * 图片id|地址一ue_separate_ue图片id|地址二ue_separate_ue图片id|地址三
	 * @return void
     */
	public function uemanager() {
		if (!$this->uid) return NULL;
		$this->load->model('attachment_model');
		$data = $this->attachment_model->get_unused($this->uid, 'jpg,png,gif');
		if (!$data) return NULL;
		$result = array();
		foreach ($data as $t) {
			$result[] = dr_file($t['attachment']).'?dr_image_id='.$t['id'];
		}
		echo implode('ue_separate_ue', $result);
	}
	
	/**
     * 汉字转换拼音
     */
	public function pinyin() {
		$name = $this->input->get('name', TRUE);
		if (!$name) exit('');
        $this->load->library('pinyin');
		exit($this->pinyin->result($name));
	}
	
	/**
     * 标题检查
     */
	public function checktitle() {
		$id = (int)$this->input->get('id');
		$title = $this->input->get('title', TRUE);
		$module = $this->input->get('module');
		if (!$title || !$module) exit('');
		$num = $this->site[SITE_ID]
					->where('id<>', $id)
					->where('title', $title)
					->count_all_results(SITE_ID.'_'.$module);
		if ($num) {
			exit(lang('m-146'));
		} else {
			exit('√');
		}
	}
	
	/**
     * 提取关键字
     */
	public function getkeywords() {
		$kw = $this->input->get('kw', TRUE);
		$data = @file_get_contents('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title='.rawurlencode($kw).'&content='.rawurlencode($kw));
		if ($data) {
			$parser = xml_parser_create();
			$kws = array();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $index);
			xml_parser_free($parser);
			foreach ($values as $valuearray) {
				$kw = trim($valuearray['value']);
				if(strlen($kw) > 5 && ($valuearray['tag'] == 'kw' || $valuearray['tag'] == 'ekw')) $kws[]  = $kw;
			}
			echo @implode(',', $kws);
		}
		exit('');
	}
	
	/**
     * 文件信息
     */
	public function fileinfo() {
		$this->load->helper('system');
		$key = $this->input->get('name');
		$info = dr_file_info($key);
		$file = count($info) > 2 ? dr_get_file($info['attachment']) : $key;
		if (in_array(strtolower(trim(substr(strrchr($file, '.'), 1, 10))), array('jpg', 'jpeg', 'gif', 'png'))) {
			echo '<img src="'.$file.'" onload="if(this.width>$(window).width()/2)this.width=$(window).width()/2;">';
		} else {
			echo '<a href="'.$file.'" target=_blank>'.($info['filename'] ? $info['filename'] : $file).'</a><br>&nbsp;';
		}
	}
	
	/**
     * 图片处理
     */
	public function thumb() {
		$info = get_attachment((int)$this->input->get('id')); // 图片信息
		$file = $info && in_array($info['fileext'], array('jpg', 'gif', 'png')) ? $info['attachment'] : 'dayrui/statics/images/nopic.gif'; // 图片判断
		// 参数设置
		$water = (int)$this->input->get('water');
		$width = (int)$this->input->get('width');
		$height = (int)$this->input->get('height');
		// 远程图片下载到本地缓存目录
		if (isset($info['remote']) && $info['remote']) {
			$file = FCPATH.'cache/attach/'.time().'_'.basename($info['attachment']);
			file_put_contents($file, dr_catcher_data($info['attachment']));
		} else {
			$file = FCPATH.$file;
		}
		// 处理宽高
		list($_width, $_height) = getimagesize($file);
		$width = $width ? $width : $_width;
		$height = $height ? $height : $_height;
		// 
		$iswater = (bool)SITE_IMAGE_WATERMARK;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['create_thumb'] = TRUE;
		$config['source_image'] = $file;
		$config['is_watermark'] = $iswater && $water ? TRUE : FALSE; // 开启水印
		$config['image_library'] = 'gd2';
		$config['dynamic_output'] = TRUE; // 输出到浏览器
		$config['maintain_ratio'] = (bool)SITE_IMAGE_RATIO; // 使图像保持原始的纵横比例
		if (isset($info['remote']) && $info['remote'] && !SITE_IMAGE_REMOTE) $config['is_watermark'] = FALSE; // 远程附件图片水印关闭
		// 水印参数
		$config['wm_type'] = SITE_IMAGE_TYPE ? 'overlay' : 'text';
		$config['wm_vrt_offset'] = SITE_IMAGE_VRTOFFSET;
		$config['wm_hor_offset'] = SITE_IMAGE_HOROFFSET;
		$config['wm_vrt_alignment'] = SITE_IMAGE_VRTALIGN;
		$config['wm_hor_alignment'] = SITE_IMAGE_HORALIGN;
		// 文字模式
		$config['wm_text'] = SITE_IMAGE_TEXT;
		$config['wm_font_size'] = SITE_IMAGE_SIZE;
		$config['wm_font_path'] = FCPATH.'dayrui/statics/watermark/'.(SITE_IMAGE_FONT ? SITE_IMAGE_FONT : 'default.ttf');
		$config['wm_font_color'] = str_replace('#', '', SITE_IMAGE_COLOR);
		// 图片模式
		$config['wm_opacity'] = SITE_IMAGE_OPACITY ? SITE_IMAGE_OPACITY : 80;
		$config['wm_overlay_path'] = FCPATH.'dayrui/statics/watermark/'.(SITE_IMAGE_OVERLAY ? SITE_IMAGE_OVERLAY : 'default.png');
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
		if (isset($info['remote']) && $info['remote']) @unlink($file);
	}
	
	/**
     * 下载文件
     */
	public function file() {
		$id = (int)$this->input->get('id');
		$info = get_attachment($id);
		$this->template->admin();
		if (!$info) $this->admin_msg(lang('m-326'));
		if (!$this->member['adminid'] && !$this->member_rule['is_download']) $this->admin_msg(lang('m-322')); // 是否允许下载附件
		// 虚拟币与经验值检查
		$mark = 'attachment-'.$id;
		$table = $this->db->dbprefix('member_scorelog_'.(int)substr((string)$this->uid, -1, 1));
		if ($this->member_rule['download_score'] && !$this->db->where('type', 1)->where('mark', $mark)->count_all_results($table)) {
			// 虚拟币不足时，提示错误
			if ($this->member_rule['download_score'] + $this->member['score'] < 0) {
				$this->admin_msg(dr_lang('m-324', SITE_SCORE, abs($this->member_rule['download_score'])));
			}
			// 虚拟币扣减
			$this->member_model->update_score(1, $this->uid, (int)$this->member_rule['download_score'], $mark, "lang,m-325");
		}
		if ($this->member_rule['download_experience'] && !$this->db->where('type', 0)->where('mark', $mark)->count_all_results($table)) {
			// 经验值扣减
			$this->member_model->update_score(0, $this->uid, (int)$this->member_rule['download_experience'], $mark, "lang,m-325");
		}
		$file = $info['attachment'];
		if (strpos($file, ':/')) { //远程文件
			header("Location: $file");
		} else { //本地文件
			$file = FCPATH.$file;
			header('Pragma: public');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');
			header('Content-Transfer-Encoding: binary');
			header('Content-Encoding: none');
			header('Content-type: ' . $info['fileext']);
			header('Content-Disposition: attachment; filename="' . $info['filename'].'.'.$info['fileext'] . '"');
			header('Content-length: ' . sprintf("%u", $info['filesize']));
			readfile($file);
		}
	}
	
	
	/**
     * OAuth2授权登录
     */
	public function oauth() {
		if ($this->uid) $this->member_msg(lang('m-013'), $_SERVER['HTTP_REFERER']);
		$appid = $this->input->get('id');
		$oauth = require FCPATH.'config/oauth.php';
		$config	= $oauth[$appid];
		if (!$config) $this->member_msg(lang('m-047'));
		$config['url'] = SITE_URL.'member/index.php?c=api&m=oauth&id='.$appid; // 回调地址设置
		$this->load->library('OAuth2');
		// OAuth
        $code = $this->input->get('code', TRUE);
		$oauth = $this->oauth2->provider($appid, $config);
		if (!$code) { // 登录授权页
			try {
				$oauth->authorize();
			} catch (OAuth2_Exception $e) {
				$this->member_msg(lang('m-048').' _ '.$e);
			}
		} else { // 回调返回数据
			try {
				$token = $oauth->access($code);
	        	$user = $oauth->get_user_info($token);
				if (is_array($user)) {
					$code = $this->member_model->OAuth_login($appid, $user);
					$this->member_msg(lang('m-002').$code, dr_url('home/index'), 1, 3);
				} else {
					$this->member_msg(lang('m-051'));
				}
			} catch (OAuth2_Exception $e) {
                $this->member_msg(lang('m-051').' - '.$e);
			}
		}
	}
	
	/**
	 * 更新模型浏览数
	 */
	public function hits() {
	    $id = (int)$this->input->get('id');
	    $mid = (int)$this->input->get('mid');
		$mod = $this->get_cache('space-model', $mid);
		if (!$mod) exit('0');
		$table = $this->db->dbprefix('space_'.$mod['table']);
		$name = $table.'-space-hits-'.$id;
		$hits = (int)$this->get_cache_data($name);
		if (!$hits) {
			$data = $this->db
						 ->where('id', $id)
						 ->select('hits')
						 ->limit(1)
						 ->get($table)
						 ->row_array();
			$hits = (int)$data['hits'];
		}
		$hits++;
		$this->set_cache_data($name, $hits, 360000);
		$this->db
			 ->where('id', $id)
			 ->update($table, array('hits' => $hits));
		exit("document.write('$hits');");
	}
}