<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.3
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Module extends M_Controller {
	
	private $_menu;
	private $_to_file;
	private $_from_file;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->_menu = array(
			lang('073') => 'admin/module/index',
			lang('086') => 'admin/module/store',
			lang('001') => 'admin/module/cache'
		);
		$this->template->assign(array(
			'menu' => $this->get_menu($this->_menu),
			'duri' => $this->duri
		));
		$this->load->model('module_model');
    }

    /**
     * 模块
     */
    public function index() {
	
		$store = $data = array();
		$local = @array_diff(dr_dir_map(FCPATH, 1), array('app', 'cache', 'config', 'dayrui', 'member', 'space', 'player')); // 搜索本地模块
		$module = $this->module_model->get_data(); // 库中已安装模块
		
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.$dir.'/config/module.php')) {
					if (isset($module[$dir])) {
						$module[$dir]['url'] = $module[$dir]['site'][SITE_ID]['domain'] ? 'http://'.$module[$dir]['site'][SITE_ID]['domain'] : SITE_URL.$dir;
						$config = $data[1][$dir] = array_merge($module[$dir], require FCPATH.$dir.'/config/module.php');
						if ($config['key']) {
							$store[$dir] = array(
								'key' => $config['key'],
								'version' => $config['version'],
							);
						}
					} else {
						$data[0][$dir] = require FCPATH.$dir.'/config/module.php';
					}
				}
			}
		}
		
		$this->template->assign(array(
			'list' => $data,
			'store' => dr_base64_encode(dr_array2string($store)),
		));
		$this->template->display('module_index.html');
	}
	
	/**
     * 配置
     */
    public function config() {
	
		$id = (int)$this->input->get('id');
		$data = $this->module_model->get($id);
		$result	= 0;
		if (!$data) $this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$_data = $data;
			$data = $this->input->post('data', TRUE);
			foreach ($data['site'] as $i => $t) {
				if (!$t['use']) unset($data['site'][$i]);
			}
			if (!$data['site']) {
				$result = lang('078'); // 如果没有选择站点将不会创建
				$data = $data + $_data;
			} else {
				$this->module_model->edit($_data, $data);
				$this->admin_msg(lang('014'), dr_url('module/index'), 1);
			}
		}
		
		$theme = dr_dir_map(FCPATH.$data['dirname'].'/statics/', 1);
		$this->_menu[lang('061')] = 'admin/module/config/id/'.$id;
		$this->template->assign(array(
			'page' => max((int)$this->input->post('page'), 0),
			'data' => $data,
			'menu' => $this->get_menu($this->_menu),
			'role' => $this->dcache->get('role'),
			'theme' => $theme ? $theme : array('default'),
			'result' => $result,
			'template_path' => @array_diff(dr_dir_map(FCPATH.$data['dirname'].'/templates/', 1), array('admin', 'member')),
		));
		$this->template->display('module_config.html');
    }
	
	/**
     * 权限划分
     */
	public function role() {
	
		$id = (int)$this->input->get('id');
		$dir = $this->input->get('dir');
		
		if ($id == 1) exit(lang('027'));
		if (!is_file(FCPATH.$dir.'/config/auth.php')) exit(dr_lang('174', '/'.$dir.'/config/auth.php'));
		if (is_file(FCPATH.$dir.'/language/'.SITE_LANGUAGE.'/module_lang.php')) {
			require FCPATH.$dir.'/language/'.SITE_LANGUAGE.'/module_lang.php';
			$this->lang->language = $this->lang->language + $lang;
		}
		
		if (IS_POST) {
			$rule = NULL;
			$post = $this->input->post('data', TRUE);
			$data = $this->db
						 ->where('id', $id)
						 ->get('admin_role')
						 ->row_array();;
			if ($data['module']) {
				$rule = dr_string2array($data['module']);
				if ($rule) {
					foreach ($rule as $i => $t) {
						if (strpos($t, $dir.'/admin') === 0) unset($rule[$i]);
					}
				}
			}
			if ($rule) $post = array_merge($rule, $post);
			$this->auth_model->update_auth($id, 'module', $post);
			exit;
		}
		
		$data = $this->auth_model->get_role($id);
		require FCPATH.$dir.'/config/auth.php';
		
		$this->template->assign(array(
			'data' => $data['module'],
			'list' => $config['auth'],
			'prefix' => $dir.'/',
		));
        $this->template->display('admin_auth.html');
	}
	
	/**
     * 禁用/可用
     */
    public function disabled() {
		if ($this->is_auth('admin/module/config')) {
			$id = (int)$this->input->get('id');
			$_data = $this->db
						  ->select('disabled')
						  ->where('id', $id)
						  ->limit(1)
						  ->get('module')
						  ->row_array();
			$this->db
				 ->where('id', $id)
				 ->update('module', array('disabled' => ($_data['disabled'] == 1 ? 0 : 1)));
		}
		exit(dr_json(1, lang('014')));
    }
	
	/**
     * 复制
     */
    public function copy() {
		if ($this->is_auth('admin/module/config')) {
			$dir = $this->input->get('dir');
			if (IS_POST) {
				$data = $this->input->post('data');
				if (!$data['dirname'] || !preg_match('/^[a-z_0-9]+$/iU', $data['dirname'])) {
					exit(dr_json(0, lang('html-519')));
				} elseif (is_dir(FCPATH.$data['dirname'])) {
					exit(dr_json(0, lang('html-520')));
				} elseif ($data['name'] && strpos($data['name'], "'") !== FALSE) {
					exit(dr_json(0, lang('html-091')));
				}
				$this->_copy_file(FCPATH.$dir, FCPATH.$data['dirname']);
				if ($data['name']) {
					$file = FCPATH.$data['dirname'].'/config/module.php';
					$config = require $file;
					$config['name'] = $data['name'];
					$this->load->library('dconfig');
					$this->dconfig
						 ->file($file)
						 ->note('模块配置文件')
						 ->space(24)
						 ->to_require_one($config);
				}
				exit(dr_json(1, lang('html-092')));
			} else {
				$this->template->display('module_copy.html');
			}
		} else {
			exit(dr_json(1, lang('014')));
		}
    }
	
	/**
     * 导出
     */
    public function export() {
		if ($this->is_auth('admin/module/config')) {
			$dir = $this->input->get('dir');
			$name = $this->input->get('name');
			if ($this->input->get('action') == 1) {
				$this->_copy_file(FCPATH.$dir.'/config/', FCPATH.$dir.'/_config/');
				$error = $this->module_model->export($dir, $name);
				if ($error) {
					$this->admin_msg($error);
				} else {
					$this->admin_msg(lang('313'), dr_url('module/index'), 1, 10);
				}
			} else {
				$this->admin_msg(lang('html-476'), dr_url('module/export', array('dir' => $dir, 'name' => $name, 'action' => 1)), 2);
			}
		} else {
			$this->admin_msg(lang('014'));
		}
    }
	
	/**
     * 安装
     */
    public function install() {
	
		$dir = basename($this->input->get('dir'));
		if (!is_file(FCPATH.$dir.'/config/module.php')) $this->admin_msg(dr_lang('089', $dir));
		if (!is_file(FCPATH.$dir.'/config/main.table.php')) $this->admin_msg(dr_lang('090', $dir));
		if (!is_file(FCPATH.$dir.'/config/data.table.php')) $this->admin_msg(dr_lang('091', $dir));
		
		// 入库模块表和字段
		$id = $this->module_model->add($dir);
		if (!$id) $this->admin_msg(dr_lang('092', $dir));
		
		 // 安装当前站点的数据表
		$this->module_model->install($id, $dir, SITE_ID);
		
		// 更新站点到模块表
		$this->db->where('id', $id)->update('module', array('site' => dr_array2string(array(
			SITE_ID => array(
				'use' => 1,
				'html' => 0,
				'theme' => SITE_THEME,
				'domain' => '',
				'template' => SITE_TEMPLATE,
			)
		))));
		
		// 更新后台菜单缓存
		$this->load->model('menu_model');
		$this->menu_model->cache();
		
		// 更新会员菜单缓存
		$this->load->model('member_model');
		$this->member_model->cache();
		
		$this->admin_msg(lang('093'), dr_url('module/index'), 1);
    }
	
	/**
     * 卸载
     */
    public function uninstall() {
		$this->module_model->del((int)$this->input->get('id'));
		$this->admin_msg(lang('094'), dr_url('module/index'), 1);
    }
    
	/**
     * 清空
     */
    public function clear() {
		$this->module_model->clear($this->input->get('dir'));
		$this->admin_msg(lang('000'), dr_url('module/index'), 1);
    }
	
	/**
     * 推荐位收费
     */
    public function flag() {
		$this->template->display('module_flag.html');
		$this->output->enable_profiler(FALSE);
    }
	
	/**
     * 模块商店
     */
    public function store() {
	
		$data = array();
		$local = @array_diff(dr_dir_map(FCPATH, 1), array('app', 'cache', 'config', 'dayrui', 'member', 'space', 'player')); // 搜索本地模块
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.$dir.'/config/module.php')) {
					$config = require FCPATH.$dir.'/config/module.php';
					if ($config['key']) {
						$data[$config['key']] = $config['version'];
					}
				}
			}
		}
		
		$url = 'http://store.dayrui.com/index.php?c=category&id=3&action=module&param='.dr_base64_encode(dr_array2string(array(
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
		if (is_dir(FCPATH.$dir.'/')) $this->admin_msg('目录（/'.$dir.'/）已经存在');
		
    	$file = dr_base64_decode($this->input->get('file'));
    	$data = dr_catcher_data($file);
    	if (!$data) $this->admin_msg('对不起，您的服务器不支持远程下载');
		
    	$save = FCPATH.'cache/down/module.zip';
    	$check = FCPATH.'cache/down/module/';
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
    	if (!is_file($check.'config/module.php') || !filesize($check.'config/module.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('文件不完整，请重试');
    	}
		
    	// 覆盖至网站根目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.$dir.'/', PCLZIP_OPT_REPLACE_NEWER);
    	delete_files(FCPATH.'cache/down/', TRUE);
		
		$this->admin_msg('下载成功，即将为您跳转到应用中心', dr_url('module/index'), 1);
    }
	
	/**
     * 更新
     */
    public function update() {
	
		$key = 0;
		$dir = $this->input->get('id');
		if (is_file(FCPATH.$dir.'/config/module.php')) {
			$config = require FCPATH.$dir.'/config/module.php';
			$key = (int)$config['key'];
		}
		
		if (!$key) $this->admin_msg('此模块无法在线更新（key不存在）');
		$url = 'http://store.dayrui.com/index.php?c=down&m=update&action=module&param='.dr_base64_encode(dr_array2string(array(
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
		
		if (is_file(FCPATH.$dir.'/config/module.php')) {
			$config = require FCPATH.$dir.'/config/module.php';
			if ((int)$config['key'] != $key) $this->admin_msg('此应用无法在线升级，key不匹配');
		} else {
			 $this->admin_msg('此模块无法在线升级，目录（/'.$dir.'/）不存在');
		}
		
    	$file = dr_base64_decode($this->input->get('file'));
    	$data = dr_catcher_data($file);
    	if (!$data) $this->admin_msg('对不起，您的服务器不支持远程下载');
		
    	$save = FCPATH.'cache/down/module.zip';
    	$check = FCPATH.'cache/down/module/';
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
    	if (!is_file($check.'config/module.php') || !filesize($check.'config/module.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('升级文件不完整，请重试');
    	}
		
    	// 覆盖至网站目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.$dir, PCLZIP_OPT_REPLACE_NEWER);
    	delete_files(FCPATH.'cache/down/', TRUE);
		
    	// 运行SQL语句
    	if (is_file(FCPATH.$dir.'update.sql')) {
    		$sql = file_get_contents(FCPATH.$dir.'update.sql');
			$sql = str_replace('{dbprefix}', $this->db->dbprefix, $sql);
			$this->sql_query($sql);
			@unlink(FCPATH.$dir.'update.sql');
    	}
		
    	//检查update控制器
		if (is_file(FCPATH.$dir.'/controllers/admin/Update.php')) $this->admin_msg('正在升级数据，请稍候...', dr_url($dir.'/update/index'), 2);
		$this->admin_msg('升级完成，请重新检测一次版本', dr_url('module/index'), 1);
    }
	
	/**
     * 缓存
	 *
	 * 模块缓存文件格式：module-站点id-模块名称 = array(模块数组);
	 * 模块数据缓存文件：module = array( 模块名称1, 模块名称2, 模块名称3);
	 *
     */
    public function cache($update = 1) {
	
		$dir = $this->input->get('dir');
		$admin = (int)$this->input->get('admin');
		
		if ($dir) {
			$url = $this->input->get('url') ? $this->input->get('url') : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
			$todo = (int)$this->input->get('todo');
			if (!($admin || !$update) && !$todo) {
				$this->admin_msg(lang('004'), dr_url('module/cache', array('dir' => $dir, 'todo' => 1, 'url' => urlencode($url))), 2, 0);
			}
			$this->module_model->cache($dir, $update);
			if ($admin || !$update) return '';
			$this->admin_msg(lang('000'), urldecode($url), 1);
		} else {
			// 模块页面更新缓存
			$step = (int)$this->input->get('step');
			$todo = (int)$this->input->get('todo');
			$module = $this->db
						   ->where('disabled', 0)
						   ->get('module')
						   ->result_array();
			if (!$todo && $module) {
				$cache = array();
				foreach ($module as $t) {
					$site = dr_string2array($t['site']);
					foreach ($site as $_site => $url) {
						$cache[$_site][] = $t['dirname']; // 将模块归类至站点
					}
				}
				$this->dcache->set('module', $cache);
				$this->admin_msg(lang('004'), dr_url('module/cache', array('step' => 0, 'todo' => 1)), 2, 0);
			}
			if (!isset($module[$step])) $this->admin_msg(lang('116'), dr_url('module/index'), 1);
			$this->module_model->cache($module[$step]['dirname'], $update);
			$this->admin_msg(dr_lang('009', $module[$step]['dirname']).' ...', dr_url('module/cache', array('step' => $step + 1, 'todo' => 1)), 2, 0);
		}
	}
	
	/**
	 * $fromFile  要复制谁
	 * $toFile    复制到那
	 */
	private function _copy_file($fromFile, $toFile){
		$this->_create_folder($toFile);
		$folder1 = opendir($fromFile);
		while ($f1 = readdir($folder1)) {
			if ($f1 != "." && $f1 != "..") {
				$path2 = "{$fromFile}/{$f1}";
				if (is_file($path2)) {	
					$file = $path2;
					$newfile = "{$toFile}/{$f1}";
					copy($file, $newfile);
				} elseif (is_dir($path2)) {
					$toFiles = $toFile.'/'.$f1;
					$this->_copy_file($path2, $toFiles);
				}
			}
		}
	}
	
	/**
	 * 递归创建文件夹
	 */
	private function _create_folder($dir, $mode = 0777){
		if (is_dir($dir) || @mkdir($dir, $mode)) {
			return true;
		}	
		if (!$this->_create_folder(dirname($dir), $mode)) {
			return false;
		}
		return @mkdir($dir, $mode);
	}
}