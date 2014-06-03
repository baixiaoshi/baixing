<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class System_model extends CI_Model {

	public $config;

	/*
	 * 系统模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->config = array(
			'SYS_LOG' => '后台操作日志开关',
			'SYS_KEY' => '安全密钥',
			'SYS_DEBUG'	=> '调试器开关',
			'SYS_HELP_URL' => '系统帮助url前缀部分',
			'SYS_EMAIL' => '系统收件邮箱，用于接收系统信息',
			'SYS_MEMCACHE' => 'Memcache缓存开关',
			'SYS_ATTACHMENT_DIR' => '系统附件目录名称',
			'SYS_CRON_QUEUE' => '任务队列方式',
			'SYS_CRON_NUMS' => '每次执行任务数量',
			'SYS_CRON_TIME' => '每次执行任务间隔',
			
			'SITE_EXPERIENCE' => '经验值名称',
			'SITE_SCORE' => '虚拟币名称',
			'SITE_MONEY' => '金钱名称',
			'SITE_CONVERT' => '虚拟币兑换金钱的比例',
			'SITE_ADMIN_CODE' => '后台登录验证码开关',
			'SITE_ADMIN_PAGESIZE' => '后台数据分页显示数量',
			
		);
    }
	
	/*
	 * 保存配置文件
	 *
	 * @param	array	$system	旧数据
	 * @param	array	$config	新数据
	 * @return	void
	 */
	public function save_config($system, $config) {
		
		$data = array();
		$this->load->library('dconfig');
		
		foreach ($this->config as $i => $note) {
			$value = isset($config[$i]) ? $config[$i] : $system[$i];
			if ($value == 'TRUE') $value = 1;
			if ($value == 'FALSE') $value = 0;
			if ($i == 'SYS_HELP_URL') $value = $system['SYS_HELP_URL'];
			if ($i == 'SYS_KEY' && strpos($value, '***') !== FALSE) $value = $system['SYS_KEY'];
			$data[$i] = $value;
		}
		
		$this->dconfig
			 ->file(FCPATH.'config/system.php')
			 ->note('系统配置文件')
			 ->space(32)
			 ->to_require_one($this->config, $data);
			 
		return $data;
	}
	
	/*
	 * 缓存表
	 *
	 * @return	array
	 */
	public function cache() {
	
		$table = array();
		
		// 主数据库表查询
		$_table = $this->db->query("SHOW TABLE STATUS FROM `{$this->db->database}`")->result_array();
		foreach ($_table as $t) {
			if (strpos($t['Name'], $this->db->dbprefix) === 0) {
				$_field = $this->db->query('SHOW FULL COLUMNS FROM '.$t['Name'])->result_array();
				foreach ($_field as $c) {
					$t['field'][$c['Field']] = array(
						'name' => $c['Field'],
						'type' => $c['Type'],
						'note' => $c['Comment']
					);
				}
				$table[$t['Name']]	= array(
					'name' => $t['Name'],
					'rows' => $t['Rows'],
					'note' => $t['Comment'],
					'free' => $t['Data_free'], // 多余空间
					'field' => $t['field'],
					'siteid' => 0, // 主数据库
					'update' => $t['Update_time'],
					'filesize' => $t['Data_length'] + $t['Index_length'],
					'collation'	=> $t['Collation'],
				);
			}
		}
		
		// 按站点查询
		if ($this->SITE) {
			foreach ($this->SITE as $sid => $x) {
				$db = $this->site[$sid];
				if ($db !== $this->db && $_table = $db->query("SHOW TABLE STATUS FROM {$db->database}")->result_array()) {
					foreach ($_table as $t) {
						if (strpos($t['Name'], $this->db->dbprefix) === 0) {
							$_field = $this->db->query('SHOW FULL COLUMNS FROM '.$t['Name'])->result_array();
							foreach ($_field as $c) {
								$t['field'][$c['Field']] = array(
									'name' => $c['Field'],
									'type' => $c['Type'],
									'note' => $c['Comment']
								);
							}
							$table[$t['Name']]	= array(
								'name' => $t['Name'],
								'rows' => $t['Rows'],
								'note' => $t['Comment'],
								'free' => $t['Data_free'], // 多余空间
								'field' => $t['field'],
								'siteid' => $sid, // 分站点数据库
								'update' => $t['Update_time'],
								'filesize' => $t['Data_length'] + $t['Index_length'],
								'collation'	=> $t['Collation'],
							);
						}
					}
				}
			}
		}
		
		$this->dcache->set('table', $table);
		
		return $table;
	}
	
	/*
	 * 系统表
	 * 
	 * @return	array
	 */
	public function get_system_table() {
	
		$list = array();
		$data = $this->dcache->get('table');
		if (!$data) $data = $this->cache();
		
		foreach ($data as $t) {
			if (!preg_match('/'.$this->db->dbprefix.'[0-9]+_/', $t['name'])) $list[] = $t;
		}
		
		return $list;
	}
	
	/*
	 * 站点表
	 * 
	 * @param	intval	$siteid
	 * @return	array
	 */
	public function get_site_table($siteid) {
	
		$list = array();
		$data = $this->dcache->get('table');
		if (!$data) $data = $this->cache();
		
		foreach ($data as $t) {
			if (preg_match('/'.$this->db->dbprefix.$siteid.'_/', $t['name'])) $list[] = $t;
		}
		
		return $list;
	}
}