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
 
class Install extends CI_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		if (is_file(FCPATH.'cache/install.lock')) exit('安装程序已经锁定，如果重新安装请删除cache/install.lock文件');
		$this->load->library('template');
		define('SITE_PATH', str_replace(array('\\', '//'), '/', str_replace(array('/'.APP_DIR, 'member'), '', dirname($_SERVER['SCRIPT_NAME'])).'/'));
		$config = require FCPATH.'config/version.php'; // 加载系统版本更新文件
		foreach ($config as $var => $value) {
			if (!defined($var)) define($var, $value); // 将配置文件数组转换为常量
		}
		$this->load->library('dcache');
		$this->dcache->set('install', TRUE);
    }

    /**
     * 安装程序
     */
    public function index() {
		
		$step = max(1, (int)$this->input->get('step'));
		switch ($step) {
		
			case 1:
				break;
				
			case 2:
			
				$lowestEnvironment = $this->_getLowestEnvironment();
				$currentEnvironment = $this->_getCurrentEnvironment();
				$recommendEnvironment = $this->_getRecommendEnvironment();
				$writeAble = $this->_checkFileRight();
				
				$check_pass = true;
				foreach ($currentEnvironment as $key => $value) {
					if (false !== strpos($key, '_ischeck') && false === $value) $check_pass = false;
				}
				foreach ($writeAble as $value) {
					if (false === $value) $check_pass = false;
				}
				
				$this->template->assign(array(
					'writeAble' => $writeAble,
					'lowestEnvironment' => $lowestEnvironment,
					'currentEnvironment' => $currentEnvironment,
					'recommendEnvironment' => $recommendEnvironment,
				));
				break;
				
			case 3:
				if ($_POST) {
					$data = $this->input->post('data');
					if (!preg_match('/^[\x7f-\xff\dA-Za-z\.\_]+$/', $data['admin'])) {
						exit(dr_json(0, '管理员账号格式不正确'));
					}
					if (!$data['password']) {
						exit(dr_json(0, '管理员密码不能为空'));
					}
					if (!$data['dbname']) {
						exit(dr_json(0, '数据库名称不能为空'));
					}
					$this->load->helper('email');
					if (!$data['email'] || !valid_email($data['email'])) {
						exit(dr_json(0, 'Email格式不正确'));
					}
					if (!@mysql_connect($data['dbhost'], $data['dbuser'], $data['dbpw'])) {
						exit(dr_json(0, '无法连接到数据库服务器，请检查用户名和密码是否正确'));
					}
					if (!@mysql_select_db($data['dbname'])) {
						if (!@mysql_query('CREATE DATABASE '.$data['dbname'])) {
							exit(dr_json(0, '指定的数据库('.$data['dbname'].')不存在，系统尝试创建失败，请通过其他方式建立数据库'));
						}
					}
					mysql_query('SET NAMES utf8');
					
					list($data['dbhost'], $data['dbport']) = explode(':', $data['dbhost']);
					$data['dbport'] = !empty($data['dbport']) ? intval($data['dbport']) : 3306;
					
					$config = "<?php".PHP_EOL.PHP_EOL;
					$config.= "if (!defined('BASEPATH')) exit('No direct script access allowed');".PHP_EOL.PHP_EOL;
					$config.= "\$active_group	= 'default';".PHP_EOL;
					$config.= "\$query_builder	= TRUE;".PHP_EOL.PHP_EOL;
					$config.= "\$db['default']	= array(".PHP_EOL;
					$config.= "	'dsn'		=> '',".PHP_EOL;
					$config.= "	'hostname'	=> '{$data['dbhost']}',".PHP_EOL;
					$config.= "	'username'	=> '{$data['dbuser']}',".PHP_EOL;
					$config.= "	'password'	=> '{$data['dbpw']}',".PHP_EOL;
					$config.= "	'port'		=> '{$data['dbport']}',".PHP_EOL;
					$config.= "	'database'	=> '{$data['dbname']}',".PHP_EOL;
					$config.= "	'dbdriver'	=> 'mysql',".PHP_EOL;
					$config.= "	'dbprefix'	=> '{$data['dbprefix']}',".PHP_EOL;
					$config.= "	'pconnect'	=> FALSE,".PHP_EOL;
					$config.= "	'db_debug'	=> TRUE,".PHP_EOL;
					$config.= "	'cache_on'	=> FALSE,".PHP_EOL;
					$config.= "	'cachedir'	=> 'cache/sql/',".PHP_EOL;
					$config.= "	'char_set'	=> 'utf8',".PHP_EOL;
					$config.= "	'dbcollat'	=> 'utf8_general_ci',".PHP_EOL;
					$config.= "	'swap_pre'	=> '',".PHP_EOL;
					$config.= "	'autoinit'	=> FALSE,".PHP_EOL;
					$config.= "	'encrypt'	=> FALSE,".PHP_EOL;
					$config.= "	'compress'	=> FALSE,".PHP_EOL;
					$config.= "	'stricton'	=> FALSE,".PHP_EOL;
					$config.= "	'failover'	=> array(),".PHP_EOL;
					$config.= ");".PHP_EOL;
					
					if (!file_put_contents(FCPATH.'config/database.php', $config)) {
						exit(dr_json(0, '数据库配置文件保存失败，请检查文件config/database.php权限！'));
					}
					
					$this->load->database();
					$salt = substr(md5(rand(0, 999)), 0, 10);
					$password = md5(md5($data['password']).$salt.md5($data['password']));
					// 导入表结构
					$this->_query(str_replace(
						array('{dbprefix}', '{username}', '{password}', '{salt}', '{email}'), 
						array($this->db->dbprefix, $data['admin'], $password, $salt, $data['email']), 
						file_get_contents(FCPATH.'cache/install/install.sql')
					));
					// 导入后台菜单数据
					$this->_query(str_replace(
						'{dbprefix}',
						$this->db->dbprefix,
						file_get_contents(FCPATH.'cache/install/admin_menu.sql')
					));
					// 导入会员菜单数据
					$this->_query(str_replace(
						'{dbprefix}',
						$this->db->dbprefix,
						file_get_contents(FCPATH.'cache/install/member_menu.sql')
					));
					
					// 系统配置文件
					$this->load->model('system_model');
					$config = array(
						'SYS_LOG' => 'FALSE',
						'SYS_KEY' => 'finecms',
						'SYS_DEBUG'	=> 'FALSE',
						'SYS_HELP_URL' => 'http://help.dayrui.com/',
						'SYS_EMAIL' => $data['email'],
						'SYS_MEMCACHE' => 'FALSE',
						'SYS_CRON_QUEUE' => 0,
						'SYS_CRON_NUMS' => 20,
						'SYS_CRON_TIME' => 300,
						
						'SITE_EXPERIENCE' => '经验值',
						'SITE_SCORE' => '虚拟币',
						'SITE_MONEY' => '金钱',
						'SITE_CONVERT' => 10,
						'SITE_ADMIN_CODE' => 'FALSE',
						'SITE_ADMIN_PAGESIZE' => 8,
						
					);
					$this->system_model->save_config($config, $config);
					
					// 站点配置文件
					$this->load->model('site_model');
					$this->load->library('dconfig');
					$config = require FCPATH.'config/site/1.php';
					$config['SITE_DOMAIN'] = $config['SITE_ATTACH_HOST'] = $config['SITE_ATTACH_URL'] = strtolower($_SERVER['HTTP_HOST']);
					$site = array(
						'name' => 'FineCMS',
						'domain' => strtolower($_SERVER['HTTP_HOST']),
						'setting' => $config,
					);
					$this->site_model->add_site($site);
					$this->dconfig
						 ->file(FCPATH.'config/site/1.php')
						 ->note('站点配置文件')
						 ->space(32)
						 ->to_require_one($this->site_model->config, $config);
					
					// 导入默认数据
					$this->_query(str_replace(
						array('{dbprefix}', '{site_url}'),
						array($this->db->dbprefix, 'http://'.strtolower($_SERVER['HTTP_HOST'])),
						file_get_contents(FCPATH.'cache/install/default.sql')
					));
					
					exit(dr_json(1, dr_url('install/index', array('step' => $step + 1))));
				}
				break;
				
			case 4:
				$log = array();
				$sql = file_get_contents(FCPATH.'cache/install/install.sql');
				preg_match_all('/`\{dbprefix\}(.+)`/U', $sql, $match);
				if ($match) {
					$log = array_unique($match[1]);
				}
				$this->template->assign(array(
					'log' => implode('<finecms>', $log),
				));
				break;
				
			case 5:
				file_put_contents(FCPATH.'cache/install.lock', time());
				break;
		}
		
        $this->template->assign(array(
            'step' => $step,
        ));
        $this->template->display('install_'.$step.'.html', 'admin');
    }
	
	// 执行sql
	private function _query($sql) {
		
		if (!$sql) return NULL;
		
		$sql_data = explode(';SQL_FINECMS_EOL', trim(str_replace(array(PHP_EOL, chr(13), chr(10)), 'SQL_FINECMS_EOL', $sql)));
		
		foreach($sql_data as $query){
			if (!$query) continue;
			$ret = '';
			$queries = explode('SQL_FINECMS_EOL', trim($query));
			foreach($queries as $query) {
				$ret .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
			}
			if (!$ret) continue;
			$this->db->query($ret);
		}
	}
	
	/**
	 * 获得当前的环境信息
	 *
	 * @return array
	 */
	private function _getCurrentEnvironment() {
		$lowestEnvironment = $this->_getLowestEnvironment();
		$space = floor(@disk_free_space(FCPATH) / (1024 * 1024));
		$space = !empty($space) ? $space . 'M': 'unknow';
		$currentUpload = ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow';
		$upload_ischeck = intval($currentUpload) >= intval($lowestEnvironment['upload']) ? true : false;
		$space_ischeck = intval($space) >= intval($lowestEnvironment['space']) ? true : false;
		$version_ischeck = version_compare(phpversion(), $lowestEnvironment['version']) < 0 ? false : true;
		$pdo_mysql_ischeck = extension_loaded('pdo_mysql');
		if (function_exists('mysql_get_client_info')) {
			$mysql = mysql_get_client_info();
			$mysql_ischeck = true;
		} elseif (function_exists('mysqli_get_client_info')) {
			$mysql = mysqli_get_client_info();
			$mysql_ischeck = true;
		} elseif ($pdo_mysql_ischeck) {
			$mysql_ischeck = true;
			$mysql = 'unknow';
		} else {
			$mysql_ischeck = false;
			$mysql = 'unknow';
		}
		if (function_exists('gd_info')) {
			$gdinfo = gd_info();
			$gd = $gdinfo['GD Version'];
			$gd_ischeck = version_compare($lowestEnvironment['gd'], $gd) < 0 ? false : true;
		} else {
			$gd_ischeck = false;
			$gd = 'unknow';
		}
		return array(
			'gd' => $gd,
			'os' => PHP_OS,
			'json' => function_exists('json_encode'),
			'space' => $space,
			'mysql' => $mysql,
			'upload' => $currentUpload,
			'version' => phpversion(),
			'pdo_mysql' => $pdo_mysql_ischeck,
			'gd_ischeck' => $gd_ischeck,
			'os_ischeck' => true,
			'space_ischeck' => $space_ischeck,
			'mysql_ischeck' => $mysql_ischeck,
			'version_ischeck' => $version_ischeck,
			'upload_ischeck' => $upload_ischeck,
			'pdo_mysql_ischeck' => $pdo_mysql_ischeck,
		);
	}

	/**
	 * 获取推荐的环境配置信息
	 *
	 * @return array 
	 */
	private function _getRecommendEnvironment() {
		return array(
			'os' => 'Linux',
			'gd' => '>2.0.28',
			'json' => '支持',
			'mysql' => '>5.x.x',
			'space' => '>50M',
			'upload' => '>2M',
			'version' => '>5.3.x',
			'pdo_mysql' => '支持',
		);
	}

	/**
	 * 获取环境的最低配置要求
	 *
	 * @return array
	 */
	private function _getLowestEnvironment() {
		return array(
			'os' => '不限制',
			'gd' => '2.0',
			'json' => '必须支持',
			'space' => '50M',
			'mysql' => '4.2',
			'upload' => '不限制',
			'version' => '5.2.0',
			'pdo_mysql' => '不限制',
		);
	}

	/**
	 * 检查目录权限
	 *
	 * @return array
	 */
	private function _checkFileRight() {
	
		$files_writeble[] = FCPATH . 'cache/';
		$files_writeble[] = FCPATH . 'member/uploadfile/';
		$files_writeble[] = FCPATH . 'config/site/';
		$files_writeble[] = FCPATH . 'config/domain.php';
		$files_writeble[] = FCPATH . 'config/system.php';
		$files_writeble[] = FCPATH . 'config/database.php';
		
		$files_writeble = array_unique($files_writeble);
		sort($files_writeble);
		$writable = array();
		
		foreach ($files_writeble as $file) {
			$key = str_replace(FCPATH, '', $file);
			$isWritable = $this->_checkWriteAble($file) ? true : false;
			if ($isWritable) {
				$flag = false;
				foreach ($writable as $k=>$v) {
					if (0 === strpos($key, $k)) $flag = true;
				}
				$flag || $writable[$key] = $isWritable;
			} else {
				$writable[$key] = $isWritable;
			}
		}
		return $writable;
	}

	/**
	 * 检查目录可写
	 *
	 * @param string $pathfile
	 * @return boolean
	 */
	private function _checkWriteAble($pathfile) {
		if (!$pathfile) return false;
		$isDir = in_array(substr($pathfile, -1), array('/', '\\')) ? true : false;
		if ($isDir) {
			if (is_dir($pathfile)) {
				mt_srand((double) microtime() * 1000000);
				$pathfile = $pathfile . 'dr_' . uniqid(mt_rand()) . '.tmp';
			} elseif (@mkdir($pathfile)) {
				return self::_checkWriteAble($pathfile);
			} else {
				return false;
			}
		}
		@chmod($pathfile, 0777);
		$fp = @fopen($pathfile, 'ab');
		if ($fp === false) return false;
		fclose($fp);
		$isDir && @unlink($pathfile);
		return true;
	}
}