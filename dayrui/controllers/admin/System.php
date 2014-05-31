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

class System extends M_Controller {
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('193') => 'admin/system/index',
				lang('205') => 'admin/system/oplog',
			))
		));
    }
	
    /**
     * 配置
     */
    public function index() {
	
		$page = (int)$this->input->get('page');
		$data = require FCPATH.'config/system.php'; // 加载网站系统配置文件
		
		if (IS_POST) {
			$this->system_model->save_config($data, $this->input->post('data'));
			$memcache = $this->input->post('memcache');
			$memcache = "<?php".PHP_EOL
			."if (!defined('BASEPATH')) exit('No direct script access allowed');".PHP_EOL.PHP_EOL
			."\$config = array(".PHP_EOL
			."	'default' => array(".PHP_EOL
			."		'hostname' => '".$memcache['hostname']."',".PHP_EOL
			."		'port'     => '".$memcache['port']."',".PHP_EOL
			."		'weight'   => '1',".PHP_EOL
			."	),".PHP_EOL
			.");".PHP_EOL;
			file_put_contents(FCPATH.'config/memcached.php', $memcache);
			$this->admin_msg(lang('000'), dr_url('system/index', array('page' => (int)$this->input->post('page'))), 1);
		}
		
		$this->config->load('memcached', TRUE);
		$memcache = $this->config->item('memcached');
		
		$this->template->assign(array(
			'page' => $page,
			'data' => $data,
			'config' => $this->system_model->config,
			'memcache' => $memcache['default'],
		));
		$this->template->display('system_index.html');
	}
	
	/**
     * 系统操作日志
     */
    public function oplog() {
	
		$this->load->helper('directory');
		$time = isset($_POST['data']['time']) && $_POST['data']['time'] ? (int)$_POST['data']['time'] : (int)$this->input->get('time');
		$time = $time ? $time : SYS_TIME;
		$path = FCPATH.'cache/optionlog/'.date('Ym', $time).'/';
		$maps = directory_map($path, 1);
		$data = array();
		$total = 0;
		
		if ($maps) {
			$maps = array_reverse($maps);
			$list = array();
			foreach ($maps as $file) {
				$data = @explode(PHP_EOL, file_get_contents($path.$file));
				$data = @array_reverse($data);
				if ($data) $list = array_merge($list, $data);
			}
			unset($file, $maps, $data);
			$page = max(1, (int)$this->input->get('page'));
			$limit = ($page - 1) * SITE_ADMIN_PAGESIZE;
			$i = $j = 0;
			$data = array();
			$total = count($list);
			foreach ($list as $v) {
				if ($v && $i >= $limit && $j < SITE_ADMIN_PAGESIZE) {
					$data[] = $v;
					$j ++;
				}
				$i ++;
			}
		}
		
		$this->template->assign(array(
			'time' => $time,
			'list' => $data,
			'total' => $total,
			'pages'	=> $this->get_pagination(dr_url('system/oplog', array('time' => $time)), $total)
		));
		$this->template->display('system_oplog.html');
	}
	
	/**
     * 生成安全码
     */
    public function syskey() {
		echo strtoupper(substr((md5(SYS_TIME)), rand(0, 10), 15));
	}
	
	/**
     * memcache 检查
     */
	public function memcache() {
	
		if (!SYS_MEMCACHE) exit("没有开启");
		if (!extension_loaded('memcached') && !extension_loaded('memcache')) exit("服务器不支持memcache");
		if (!$this->cache->memcached->is_supported()) exit("无法连接");
		
		$this->cache->memcached->save('memcache_test', 'test', 10);
		if ($this->cache->memcached->get('memcache_test') != 'test') {
			exit("没有生效");
		} else {
			exit("ok");
		}
	}
	
}