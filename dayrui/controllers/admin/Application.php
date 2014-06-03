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
	
class Application extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('219') => 'admin/application/index',
		    lang('220') => 'admin/application/store'
		)));
		$this->load->model('application_model');
    }
	
	/**
     * 管理
     */
    public function index() {
	
		$store = $data = array();
		$local = dr_dir_map(FCPATH.'app/', 1); // 搜索本地应用
		$application = $this->application_model->get_data(); // 库中已安装应用
		
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.'app/'.$dir.'/config/app.php')) {
					if (isset($application[$dir])) {
						$config = $data[1][$dir] = array_merge($application[$dir], require FCPATH.'app/'.$dir.'/config/app.php');
						if ($config['key']) {
							if (isset($store[$config['key']])) {
								if (version_compare($config['version'], $store[$config['key']], '<')) $store[$config['key']] = $config['version'];
							} else {
								$store[$config['key']] = $config['version'];
							}
						}
					} else {
						$data[0][$dir] = require FCPATH.'app/'.$dir.'/config/app.php';
					}
				}
			}
		}
		
		$this->template->assign(array(
			'list' => $data,
			'store' => dr_base64_encode(dr_array2string($store)),
		));
		$this->template->display('application_index.html');
    }
    
	/**
     * 禁用/可用
     */
    public function disabled() {
	
		if ($this->is_auth('admin/application/config')) {
		
			$id = (int)$this->input->get('id');
			$_data = $this->db
						  ->select('disabled')
						  ->where('id', $id)
						  ->limit(1)
						  ->get('application')
						  ->row_array();
			$this->db
				 ->where('id', $id)
				 ->update('application', array('disabled' => ($_data['disabled'] == 1 ? 0 : 1)));
		}
		
		exit(dr_json(1, lang('014')));
    }
	
	/**
     * 商店
     */
    public function store() {
	
		$data = array();
		$local = dr_dir_map(FCPATH.'app/', 1); // 搜索本地应用
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.'app/'.$dir.'/config/app.php')) {
					$config = require FCPATH.'app/'.$dir.'/config/app.php';
					if ($config['key']) {
						$data[$config['key']] = $config['version'];
					}
				}
			}
		}
		
		$url = 'http://store.dayrui.com/index.php?c=category&id=1&action=application&param='.dr_base64_encode(dr_array2string(array(
			'site' => SITE_URL,
			'name' => SITE_NAME,
			'data' => $data,
			'admin' => SELF,
			'domain' => $this->_get_site_domain(),
			'version' => DR_VERSION_ID,
		)));
		$this->template->assign(array(
			'url' => $url,
		));
		$this->template->display('online.html');
    }
	
	/**
     * 云端下载程序
     */
    public function down() {
    	
    	$dir = $this->input->get('dir');
		if (is_dir(FCPATH.'app/'.$dir.'/')) $this->admin_msg('目录（/app/'.$dir.'/）已经存在');
		
    	$file = dr_base64_decode($this->input->get('file'));
    	$data = dr_catcher_data($file);
    	
    	if (!$data) $this->admin_msg('对不起，您的服务器不支持远程下载');
    	
    	$save = FCPATH.'cache/down/app.zip';
    	$check = FCPATH.'cache/down/app/';
		if (!@file_put_contents($save, $data)) $this->admin_msg('目录（/cache/down/）没有写入权限');
		
		// 解压缩文件
		$this->load->helper('file');
		$this->load->library('Pclzip');
		$this->pclzip->PclFile($save);
		if ($this->pclzip->extract(PCLZIP_OPT_PATH, $check, PCLZIP_OPT_REPLACE_NEWER) == 0) {
			@unlink($save);
			delete_files(FCPATH.'cache/down/', TRUE);
			$this->admin_msg("Error : " . $this->pclzip->errorInfo(true));
		}
		
		// 检查版本文件
    	if (!is_file($check.'config/app.php') || !filesize($check.'config/app.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('文件不完整，请重试');
    	}
    	
    	// 覆盖至网站根目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.'app/'.$dir.'/', PCLZIP_OPT_REPLACE_NEWER);
    	
    	delete_files(FCPATH.'cache/down/', TRUE);
    	
		$this->admin_msg('下载成功，即将为您跳转到应用中心', dr_url('application/index'), 1);
    }
	
	/**
     * 更新
     */
    public function update() {
	
		$key = 0;
		$dir = $this->input->get('id');
		if (is_file(FCPATH.'app/'.$dir.'/config/app.php')) {
			$config = require FCPATH.'app/'.$dir.'/config/app.php';
			$key = (int)$config['key'];
		}
		if (!$key) $this->admin_msg('此应用无法在线更新（key不存在）');
		
		$url = 'http://store.dayrui.com/index.php?c=down&m=update&action=application&param='.dr_base64_encode(dr_array2string(array(
			'site' => SITE_URL,
			'name' => SITE_NAME,
			'data' => array(
				'id' => $key,
				'dir' => $dir,
				'version' => $config['version']
			),
			'admin' => SELF,
			'domain' => $this->_get_site_domain(),
			'version' => DR_VERSION_ID,
		)));
		$this->template->assign(array(
			'url' => $url,
		));
		$this->template->display('online.html');
    }
	
	/**
     * 升级程序
     */
    public function upgrade() {
    	
    	$key = (int)$this->input->get('key');
    	$dir = $this->input->get('dir');
		if (is_file(FCPATH.'app/'.$dir.'/config/app.php')) {
			$config = require FCPATH.'app/'.$dir.'/config/app.php';
			if ((int)$config['key'] != $key) $this->admin_msg('此应用无法在线升级，key不匹配');
		} else {
			 $this->admin_msg('此应用无法在线升级，目录（/app/'.$dir.'/）不存在');
		}
		
    	$file = dr_base64_decode($this->input->get('file'));
    	$data = dr_catcher_data($file);
    	
    	if (!$data) $this->admin_msg('对不起，您的服务器不支持远程下载');
    	
    	$save = FCPATH.'cache/down/app.zip';
    	$check = FCPATH.'cache/down/app/';
		if (!@file_put_contents($save, $data)) $this->admin_msg('目录/cache/down/没有写入权限');
		
		// 解压缩文件
		$this->load->helper('file');
		$this->load->library('Pclzip');
		$this->pclzip->PclFile($save);
		if ($this->pclzip->extract(PCLZIP_OPT_PATH, $check, PCLZIP_OPT_REPLACE_NEWER) == 0) {
			@unlink($save);
			delete_files(FCPATH.'cache/down/', TRUE);
			$this->admin_msg("Error : " . $this->pclzip->errorInfo(true));
		}
		
		// 检查版本文件
    	if (!is_file($check.'config/app.php') || !filesize($check.'config/app.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('升级文件不完整，请重试');
    	}
    	
    	// 覆盖至网站目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.'app/'.$dir, PCLZIP_OPT_REPLACE_NEWER);
    	delete_files(FCPATH.'cache/down/', TRUE);
    	
    	// 运行SQL语句
    	if (is_file(FCPATH.'app/'.$dir.'/update.sql')) {
    		$sql = file_get_contents(FCPATH.'app/'.$dir.'/update.sql');
			$sql = str_replace('{dbprefix}', $this->db->dbprefix, $sql);
			$this->sql_query($sql);
			@unlink(FCPATH.'app/'.$dir.'/update.sql');
    	}
    	
    	//检查update控制器
		if (is_file(FCPATH.'app/'.$dir.'/controllers/admin/Update.php')) $this->admin_msg('正在升级数据，请稍候...', dr_url($dir.'/update/index'), 2);
		
		$this->admin_msg('升级完成，请重新检测一次版本', dr_url('application/index'), 1);
    }
}