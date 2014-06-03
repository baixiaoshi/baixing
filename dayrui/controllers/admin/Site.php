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

class Site extends M_Controller {
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('060') => 'admin/site/index',
				lang('add') => 'admin/site/add_js',
				lang('061') => isset($_GET['id']) && $_GET['id'] ? 'admin/site/config/id/'.(int)$_GET['id'] : 'admin/site/config',
				lang('001') => 'admin/site/cache'
			))
		));
		
		$this->load->model('site_model');
		$this->load->library('dconfig');
    }
	
	/**
     * 切换
     */
    public function select() {
	
		$id	= (int)$this->input->post('id');
		if (!isset($this->SITE[$id])) exit(dr_json(0, dr_lang('062', $id)));
		
		$this->session->set_userdata('siteid', $id); // 保存Session
		$data = array(
			'msg' => dr_lang('063', $this->SITE[$id]['SITE_NAME']),
			'url' => $this->SITE[$id]['SITE_URL'],
			'site' => $this->SITE[$id]['SITE_NAME'],
			'title'	=> dr_lang('html-001', $this->SITE[$id]['SITE_NAME'])
		);
		
		exit(dr_json(1, $data, $id));
	}

    /**
     * 管理
     */
    public function index() {
	
		if (IS_POST) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			$_data = $this->input->post('data');
			foreach ($ids as $id) {
				$this->db
					 ->where('id', $id)
					 ->update($this->db->dbprefix('site'), $_data[$id]);
			}
			
			exit(dr_json(1, lang('014')));
		}
		
		$this->template->assign('list', $this->site_model->get_site_data());
		$this->template->display('site_index.html');
	}
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
		
			$this->load->library('dconfig');
			$data = $this->input->post('data', TRUE);
			$domain	= require FCPATH.'config/domain.php';
			
			if (!$data['name']) exit(dr_json(0, '', 'name'));
			if (!preg_match('/[\w-_\.]+\.[\w-_\.]+/i', $data['domain'])) exit(dr_json(0, '', 'domain'));
			if (in_array($data['domain'], $domain)) exit(dr_json(0, dr_lang('064', $data['domain']), 'domain'));
			if ($this->db->where('domain', $data['domain'])->count_all_results('site')) exit(dr_json(0, lang('249'), 'domain'));
			
			// 初始化网站配置
			$cfg['SITE_NAME'] = $data['name'];
			$cfg['SITE_DOMAIN'] = $data['domain'];
			$cfg['SITE_TIMEZONE'] = '8';
			$cfg['SITE_LANGUAGE'] = 'zh-cn';
			$cfg['SITE_TIME_FORMAT'] = 'Y-m-d H:i:s';
			$cfg['SITE_ATTACH_EXTS'] = 'jpg,gif,png';
			
			// 入库
			$data['setting'] = $cfg;
			$id	= $this->site_model->add_site($data);
			if (!$id) exit(dr_json(0, dr_lang('065')));
			
			$domain[$data['domain']] = $id;
			$size = $this->dconfig
						 ->file(FCPATH.'config/site/'.$id.'.php')
						 ->note('站点配置文件')
						 ->space(32)
						 ->to_require_one($this->site_model->config, $cfg);
			if (!$size) exit(dr_json(0, lang('066')));
			
			$size = $this->dconfig
						 ->file(FCPATH.'config/domain.php')
						 ->note('站点域名文件')
						 ->space(32)
						 ->to_require_one($domain);
			if (!$size) exit(dr_json(0, lang('067')));
			
			exit(dr_json(1, lang('014')));
		} else {
			$this->template->display('site_add.html');
		}
    }
	
	/**
     * 站点配置
     */
    public function config() {
	
		$id = isset($_GET['id']) ? max((int)$this->input->get('id'), 1) : SITE_ID;
		$data = $this->SITE[$id];
		if (!isset($this->SITE[$id])) $this->admin_msg(dr_lang('062', $id));
		
		if (IS_POST) {
		
			$cfg = $this->input->post('data', TRUE);
			$cfg['SITE_DOMAIN'] = $this->input->post('domain', TRUE);
			$cfg['SITE_NAVIGATOR'] = @implode(',', $this->input->post('navigator', TRUE));
			
			$data = array(
				'name' => $cfg['SITE_NAME'],
				'domain' => $cfg['SITE_DOMAIN'],
				'setting' => $cfg
			);
			
			$this->site_model->edit_site($id, $data);
			$domain	= require FCPATH.'config/domain.php';
			$domain[$cfg['SITE_DOMAIN']] = $id;
			$this->dconfig
				 ->file(FCPATH.'config/site/'.$id.'.php')
				 ->note('站点配置文件')
				 ->space(32)
				 ->to_require_one($this->site_model->config, $cfg);
			$this->dconfig
				 ->file(FCPATH.'config/domain.php')
				 ->note('站点域名文件')
				 ->space(32)
				 ->to_require_one($domain);
				 
			$data = $cfg;
			$result	= !$error ? lang('014') : $error;
		} else {
			$result	= '';
		}
		
		$this->load->helper('directory');
		$files = directory_map(FCPATH.'dayrui/statics/watermark/', 1);
		$opacity = array();
		foreach ($files as $t) {
			if (substr($t, -3) == 'ttf') {
				$font[] = $t;
			} else {
				$opacity[] = $t;
			}
		}
		
		$this->template->assign(array(
			'id' => $id,
			'page' => max((int)$this->input->post('page'), 0),
			'data' => $data,
			'lang' => dr_dir_map(FCPATH.'dayrui/language/', 1),
			'theme' => array_diff(dr_dir_map(FCPATH.'dayrui/statics/', 1), array('css', 'images', 'watermark')),
			'result' => $result,
			'navigator' => @explode(',', $data['SITE_NAVIGATOR']),
			'wm_opacity' => $opacity,
			'wm_font_path' => $font,
			'template_path' => array_diff(dr_dir_map(FCPATH.'dayrui/templates/', 1), array('api', 'admin')),
			'wm_vrt_alignment' => array('top', 'middle', 'bottom'),
			'wm_hor_alignment' => array('left', 'center', 'right'),
		));
		$this->template->display('site_config.html');
    }
	
	/**
     * 删除
     */
    public function del() {
		$id = (int)$this->input->get('id');
		if (!$this->SITE[$id]) $this->admin_msg(lang('068'));
		if ($id == 1) $this->admin_msg(lang('069'));
		$db = $this->site[$id];
		// 卸载模块
		$module = $db->get('module')->result_array();
		if ($module) {
			$this->load->model('module_model');
			foreach ($module as $t) {
				$site = dr_string2array($t['site']);
				if (isset($site[$id])) {
					$this->module_model->uninstall($t['id'], $t['dirname'], $id, count($site));
				}
			}
		}
		// 删除单页
		$db->query('DROP TABLE IF EXISTS `'.$this->db->dbprefix($id.'_page').'`');
		// 删除文本块
		$db->query('DROP TABLE IF EXISTS `'.$this->db->dbprefix($id.'_block').'`');
		// 删除导航数据
		$db->query('DROP TABLE IF EXISTS `'.$this->db->dbprefix($id.'_navigator').'`');
		// 删除站点
		$this->db->delete('site', 'id='.$id);
		// 删除该站配置
		unlink(FCPATH.'config/site/'.$id.'.php');
		// 删除该站附件
		$this->load->model('attachment_model');
		$this->attachment_model->delete_for_site($id);
		$this->admin_msg(lang('000'), dr_url('site/index'), 1);
    }
	
	/**
     * 缓存
     */
    public function cache() {
	
		$data = $this->site_model->get_site_data();
		$admin = $this->input->get('admin');
		
		$oldfile = directory_map(FCPATH.'config/site/');
		foreach ($oldfile as $file) {
			@unlink(FCPATH.'config/site/'.$file);
		}
		
		$domain = array();
		foreach ($data as $id => $t) {
			if ($t['domain']) $domain[$t['domain']] = $id;
			$this->dconfig
				 ->file(FCPATH.'config/site/'.$id.'.php')
				 ->note('站点配置文件')
				 ->space(32)
				 ->to_require_one($this->site_model->config, $t['setting']);
		}
		
		// 所有可用模块
		$data = $this->db
					 ->where('disabled', 0)
					 ->select('site,dirname')
					 ->get('module')
					 ->result_array();
		if ($data) {
			$module = array();
			foreach ($data as $t) {
				$site = dr_string2array($t['site']);
				foreach ($site as $sid => $s) {
					if ($s['use']) {
						if ($s['domain']) $domain[$s['domain']] = $sid; // 更新模块域名
						$module[$sid][] = $t['dirname']; // 将模块归类至站点
					}
				}
			}
			$this->dcache->set('module', $module);
		} else {
			$this->dcache->delete('module');
		}
		
		$member = $this->member_model->cache(); // 更新会员域名
		foreach ($member['setting'] as $name => $value) {
			if (strpos($name, 'domain') !== FALSE) {
				list($test, $_site) = explode('-', $name);
				if ($_site && $value) $domain[$value] = $_site;
			}
		}
		
		$this->clear_cache('module');
		
		$this->dconfig
			 ->file(FCPATH.'config/domain.php')
			 ->note('站点域名文件')
			 ->space(32)
			 ->to_require_one($domain);
			 
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}