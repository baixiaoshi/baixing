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
 
class Cron extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->output->enable_profiler(FALSE);
    }
	
    /**
     * 执行任务和队列
     */
    public function index() {
		
		if (SYS_CRON_QUEUE && !(PHP_SAPI === 'cli' || defined('STDIN'))) exit(); // 第三方执行队列时，非命令行不执行
		
		if (get_cookie('cron')) exit(); //未到发送时间
		
		$pernum = defined('SYS_CRON_NUMS') && SYS_CRON_NUMS ? SYS_CRON_NUMS : 10; //一次执行的任务数量
		set_cookie('cron', 1, SYS_CRON_TIME); //用户每多少秒调用本程序
		
		$queue = $this->db
					  ->order_by('status ASC,id ASC')
					  ->limit($pernum)
					  ->get('cron_queue')
					  ->result_array();
		if (!$queue) {
			$this->db->query('TRUNCATE `'.$this->db->dbprefix('cron_queue').'`');
			exit(); // 所有任务执行完毕
		}
		
		foreach ($queue as $data) {
			$this->cron_model->execute($data);
		}
		
		exit(); //本次任务执行完毕
	}
}