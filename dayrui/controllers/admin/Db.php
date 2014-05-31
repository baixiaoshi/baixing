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

class Db extends M_Controller {
	
	private $link;
	private $siteid;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->siteid = (int)$this->input->get('siteid');
		$this->link = $this->siteid ? $this->site[$this->siteid] : $this->db;
    }

    /**
     * 数据维护
     */
    public function index() {
	
		$list = $this->siteid ? $this->system_model->get_site_table($this->siteid) : $this->system_model->get_system_table();
		
		if (IS_POST) {
			
			$tables = $this->input->post('select');
			if (!$tables) $this->admin_msg(lang('196'));
			
			switch ((int)$this->input->post('action')) {
			
				case 1: // 优化表
					foreach ($tables as $table) {
						$this->link->query("OPTIMIZE TABLE `$table`");
					}
					$result = lang('000');
					break;
					
				case 2: // 修复表
					foreach ($tables as $table) {
						$this->link->query("REPAIR TABLE `$table`");
					}
					$result = lang('000');
					break;
					
				case 3: // 备份表
					$this->cache->file->save('backup', array($tables, SYS_TIME), 7200);
					$this->admin_msg(lang('197'), dr_url('db/backup', array('siteid' => $this->siteid)), 2);
					break;
			}
		}
		
		$menu = array();
		$menu[lang('194')] = 'admin/db/index';
		foreach ($this->SITE as $id => $s) {
			$menu[$s['SITE_NAME']] = 'admin/db/index/siteid/'.$id;
		}
		$this->template->assign(array(
			'menu' => $this->get_menu($menu),
			'list' => $list,
			'result' => $result,
		));
		$this->template->display('db_index.html');
	}
	
	/**
     * 数据恢复
     */
	public function recovery() {
		
		$this->load->helper('directory');
		$dir = FCPATH.'cache/backup/';
		$data = array();
		$backup = directory_map($dir, 1);
		
		if ($backup) {
			$this->load->helper('file');
			foreach ($backup as $name) {
				$name = basename($name);
				list($time, $siteid) = explode('_', $name);
				if ($siteid == $this->siteid && is_file($dir.$name.'/version.txt')) {
					$files = get_dir_file_info($dir.$name.'/');
					$size = 0;
					foreach ($files as $t) {
						$size+= $t['size'];
					}
					$data[$name] = array(
						'time' => $time,
						'version' => file_get_contents($dir.$name.'/version.txt'),
						'filesize' => $size,
					);
				}
			}
		}
		
		if (IS_POST) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			$this->load->helper('file');
			foreach ($ids as $name) {
				delete_files($dir.'/'.$name, TRUE);
				@rmdir($dir.'/'.$name);
			}
			
			exit(dr_json(1, lang('000')));
		}
		
		$menu = array();
		$menu[lang('194')] = 'admin/db/recovery';
		foreach ($this->SITE as $id => $s) {
			$menu[$s['SITE_NAME']] = 'admin/db/recovery/siteid/'.$id;
		}
		$this->template->assign(array(
			'menu' => $this->get_menu($menu),
			'list' => $data,
			'siteid' => $this->siteid,
			'result' => $result,
		));
		$this->template->display('db_recovery.html');
	}
	
	/**
     * 数据备份
     */
	public function backup() {
		list($tables, $dirname) = $this->cache->file->get('backup');
		if (!$tables) $this->admin_msg(lang('198'));
		$this->export_database($tables, $dirname, $this->input->get('fileid'), $this->input->get('tableid'), $this->input->get('startfrom'));
	}
	
	/**
     * 数据库恢复
     */
	public function import() {
		
		$dir = FCPATH.'cache/backup/';
		$fid = max(1, (int)$this->input->get('fid'));
		$name = basename($this->input->get('name'));
		if (!is_dir($dir.$name)) $this->admin_msg(lang('html-470'));
		
		$this->load->helper('directory');
		$data = directory_map($dir.$name, 1);
		
	    $list = array();
	    foreach ($data as $t) {
	        if (substr($t, -3) == 'sql') {
			    $id = substr(strrchr($t, '_'), 1, -4);
	            $list[$id] = $t;
	        }
	    }
		
		if (!isset($list[$fid])) $this->admin_msg(lang('202'), dr_url('db/recovery', array('siteid' => $this->siteid)), 1);
		
		$file = $list[$fid];
		$this->sql_execute(file_get_contents($dir.$name.'/'.$file));
		$fid++;
		$this->admin_msg(dr_lang('201', $file), dr_url('db/import', array('siteid' => $this->siteid, 'name' => $name, 'fid' => $fid)), 2, 0);
		
	}
	
	/**
	 * 执行SQL
	 * @param  $sql
	 */
 	private function sql_execute($sql) {
	    $sqls = $this->sql_split($sql);
		if (is_array($sqls)) {
			foreach($sqls as $sql) {
				if (trim($sql) != '') {
					$this->link->query($sql);
				}
			}
		} else {
			$this->link->query($sqls);
		}
	}
	
 	private function sql_split($sql) {
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query) {
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}			
	
	/**
	 * 数据库导出方法
	 * @param  $tables 表名称
	 * @param  $dirname 备份目录名称
	 * @param  $fileid 卷标
	 * @param  $tableid 
	 * @param  $startfrom 
	 */
	private function export_database($tables, $dirname, $fileid, $tableid, $startfrom) {
	
	    set_time_limit(0);
		
		$sizelimit = 5120;
		$dumpcharset = 'utf8';
		$fileid = ($fileid != '') ? $fileid : 1;
		
		$this->link->query("SET NAMES 'utf8';\n\n");
		$tabledump = '';
		$tableid = ($tableid!= '') ? $tableid : 0;
		$startfrom = ($startfrom != '') ? intval($startfrom) : 0;
		
		for ($i = $tableid; $i < count($tables) && strlen($tabledump) < $sizelimit * 1000; $i++) {
			$offset = 100;
			if (!$startfrom) {
				$tabledump .= "DROP TABLE IF EXISTS `$tables[$i]`;\n"; 
				$createtable = $this->link->query("SHOW CREATE TABLE `$tables[$i]` ")->row_array();
				$tabledump .= $createtable['Create Table'] . ";\n\n";
				$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=[a-zA-Z0-9]+/", "DEFAULT CHARSET=utf8", $tabledump);
			}
			
			$numrows = $offset;
			while (strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) {
				$sql = "SELECT * FROM `$tables[$i]` LIMIT $startfrom, $offset";
				$numfields = $this->link->query($sql)->num_fields();
				$numrows = $this->link->query($sql)->num_rows();
				//获取表字段
				$fields_data = $this->link->query("SHOW COLUMNS FROM `$tables[$i]`")->result_array();
				$fields_name = array();
				foreach($fields_data as $r) {
					$fields_name[$r['Field']] = $r['Type'];
				}
				$rows = $this->link->query($sql)->result_array();
				$name = array_keys($fields_name);
				$r = array();
				if ($rows) {
					foreach ($rows as $row) {
						$r[] = $row;
						$comma = "";
						$tabledump .= "INSERT INTO `$tables[$i]` VALUES(";
						for($j = 0; $j < $numfields; $j++) {
							$tabledump .= $comma . "'" . mysql_real_escape_string($row[$name[$j]]) . "'";
							$comma  = ",";
						}
						$tabledump .= ");\n";
					}
				}
				$startfrom += $offset;
			}
			$tabledump .= "\n";
			$startfrom = $numrows == $offset ? $startfrom : 0;
		}
		
		$i = $startfrom ? $i - 1 : $i;
		$bakfile_path = FCPATH.'cache/backup/'.$dirname.'_'.$this->siteid.'/';
		if (!is_dir($bakfile_path)) dr_mkdirs($bakfile_path, 0777);
		
		if (trim($tabledump)) {
			$tabledump = "# finecms bakfile\n# version:".DR_VERSION." \n# time:".date('Y-m-d', $dirname)."\n# http://www.dayrui.com\n# --------------------------------------------------------\n\n\n".$tabledump;
			$tableid = $i;
			$filename = 'backup_'.$fileid.'.sql';
			$altid = $fileid;
			$fileid++;
			$bakfile = $bakfile_path.$filename;
			file_put_contents($bakfile, $tabledump);
			@chmod($bakfile, 0777);
			$this->admin_msg(dr_lang('199', $filename), dr_url('db/backup', array('siteid' => $this->siteid, 'fileid' => $fileid, 'tableid' => $tableid, 'startfrom' => $startfrom)), 2, 0);
		} else {
			file_put_contents($bakfile_path . 'index.html', '');
			file_put_contents($bakfile_path . 'version.txt', DR_VERSION);
		    $this->cache->delete('bakup');
		    $this->admin_msg(lang('200'), dr_url('db/recovery', array('siteid' => $this->siteid)), 1);
		}
	}
	
	/**
     * 表结构
     */
    public function tableshow() {
		$name = $this->input->get('name');
		$cache = $this->dcache->get('table');
		$this->template->assign('table', $cache[$name]);
		$this->template->display('db_table.html');
	}

}