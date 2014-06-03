<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Chunjie <chunjie@dayrui.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Check extends M_Controller {

	private $step;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		// 检测步骤
		$this->step = array(
			1  => '_admin_file',
			2  => '_dir_write',
			3  => '_template_theme',
			4  => '_cookie_code',
			5  => '_url_fopen',
			6  => '_curl_init',
			7  => '_fsockopen',
			8  => '_php',
			9  => '_mysql',
			10 => '_email',
			11 => '_memcache',
			12 => '_mcryp',
			13 => '_tableinfo',
			14 => '_unzip',
			98 => '_version',
			99 => '_result'
		);
    }

	/**
     * 系统体检
     */
    public function index() {
		$this->template->assign(array(
			'step' => $this->step,
		));
		$this->template->display('check_index.html');
	}
	
	/**
     * PHP环境
     */
    public function phpinfo() {
		phpinfo();
		$this->output->enable_profiler(TRUE);
	}
	
	/**
     * 执行检测
     */
    public function todo() {
		$step = max(1, (int)$this->input->get('step'));
		if (isset($this->step[$step]) && method_exists($this, $this->step[$step])) {
			echo @call_user_func_array(array($this, $this->step[$step]), array());
		}
		
	}
	
	/**
     * 版本检测
     */
    private function _version() {
		$id = (int)dr_catcher_data('http://www.dayrui.com/index.php?c=sys&m=now');
		if ($id && DR_VERSION_ID < $id) {
			return $this->halt("您的当前版本过低，为了您网站的安全性，请立即升级到官方最新版本，<a style='color:red' href='".dr_url('upgrade/index')."'><b>这里升级</b></a>", 0);
		}
	}
	
	/**
     * 解压函数检测
     */
    private function _unzip() {
		if (!function_exists('gzopen')) {
			return $this->halt("未开启zlib扩展，您将无法进行在线升级、无法下载模块/应用、无法进行模块/应用升级更新，解决方案：Google/百度一下", 0);
		}
	}
	
	/**
     * 后台入口名称检测
     */
    private function _admin_file() {
		if (SELF == 'admin.php') {
			return $this->halt("如果管理帐号泄漏，后台容易遭受攻击，为了系统安全，请修改根目录admin.php的文件名", 0);
		}
	}
	
	/**
     * 目录是否可写
     */
    private function _dir_write() {
	
		$dir = array(
			FCPATH.'cache/' => '无法生成系统缓存文件',
			FCPATH.'config/' => '无法生成系统配置文件',
			FCPATH.'member/uploadfile/' => '无法上传附件',
			FCPATH.'member/uploadfile/' => '无法上传附件',
		);
		
		$str = '';
		foreach ($dir as $file => $note) {
			if (!$this->_check_write_able($file)) {
				$str.= $this->halt(str_replace(FCPATH, '/', $file)." ".$note, 0);
			}
		}
		
		return $str;
	}
	
	/**
     * 风格与模板是否重名
     */
    private function _template_theme() {
		if (SITE_TEMPLATE == SITE_THEME) {
			return $this->halt("模板和风格目录同名可能导致模板被下载，建议模板和风格使用不相同的目录名称", 0);
		}
	}
	
	/**
     * Cookie安全码验证
     */
    private function _cookie_code() {
		if (SYS_KEY == 'finecms') {
			return $this->halt("请重新生成安全密钥，否则网站数据有被盗的风险", 0);
		}
	}
	
	/**
     * allow_url_fopen
     */
    private function _url_fopen() {
		if (!ini_get('allow_url_fopen')) {
			return $this->halt("远程图片无法保存，网络图片无法上传，一键登录无法登录。解决方案：在php.ini文件中allow_url_fopen设置为On", 0);
		}
	}
	
	/**
     * curl_init
     */
    private function _curl_init() {
		if (!function_exists('curl_init')) {
			return $this->halt("CURL扩展未开启，一键登录可能无法登录。解决方案：将php.ini中的;extension=php_curl.dll中的分号去掉", 0);
		}
	}
	
	/**
     * fsockopen
     */
    private function _fsockopen() {
		if (!function_exists('fsockopen')) {
			return $this->halt("fsockopen不支持，可能充值接口无法使用、手机短信无法发送、电子邮件无法发送、一键登录无法登录", 0);
		}
	}
	
	/**
     * php
     */
    private function _php() {
	
		if (version_compare(PHP_VERSION, '5.2.8', '<')) {
			return $this->halt("您的当前PHP版本是".PHP_VERSION."，会导致某些功能无法正常使用，建议PHP版本在5.3.0以上，最低支持5.2.8", 0);
		}
		
		if (version_compare(PHP_VERSION, '5.5.0', '>')) {
			return $this->halt("您的当前PHP版本是".PHP_VERSION."，您的版本过高部分功能可能无法使用，建议PHP版本在5.3.x~5.4.x之间", 0);
		}
		
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			return $this->halt("您的当前PHP版本是".PHP_VERSION."，建议PHP版本在5.3.0以上，性能会大大提高", 1);
		}
	}
	
	/**
     * mysql
     */
    private function _mysql() {
		if ($this->db->dbdriver == 'mysql') {
			return $this->halt("建议将数据库驱动设置为 mysqli 或 pdo ，设置方式：config/database.php中的dbdriver选项", 1);
		}
	}
	
	/**
     * email
     */
    private function _email() {
		if (!$this->db->count_all_results($this->db->dbprefix('mail_smtp'))) {
			return $this->halt("邮件服务器尚未设置，可能系统无法发送邮件通知，设置方式：系统->系统功能->邮件系统->添加SMTP服务器", 0);
		}
	}
	
	/**
     * memcache
     */
    private function _memcache() {
	
		if (!extension_loaded('memcached') && !extension_loaded('memcache')) {
			return $this->halt("服务器不支持memcache，安装memcache可以大大提高缓存数据的读取速度", 1);
		}
		
		if (!$this->cache->memcached->is_supported()) {
			return $this->halt("无法连接Memcache服务器，配置方式：系统->系统功能->系统配置->Memcache缓存", 0);
		}
		
		$this->cache->memcached->save('memcache_test', 'ok', 10);
		if ($this->cache->memcached->get('memcache_test') != 'ok') {
			return $this->halt("memcache尚未生效，请检查服务器地址与端口号是否配置正确", 0);
		} else {
			return $this->halt("Memcache缓存已经生效，可以大大提高缓存数据的读取速度", 1);
		}
	}
	
	/**
     * mcryp
     */
    private function _mcryp() {
		if (!function_exists('mcrypt_encrypt')) {
			return $this->halt("Mcrypt扩展未开启，邮件验证无法使用、密码找回不能使用，文件上传安全系数降低", 0);
		}
	}
	
	/**
     * 表结构检测
     */
    private function _tableinfo() {
	
		$sql = "SHOW TABLE STATUS FROM `{$this->db->database}`";
		$table = $this->db->query($sql)->result_array();
		if (!$table) return $this->halt("无法通过( $sql )获取到数据表结构，系统模块无法使用，解决方案：为Mysql账号开启SHOW TABLE STATUS权限", 0);
		
		$sql = 'SHOW FULL COLUMNS FROM `'.$this->db->dbprefix('admin').'`';
		$field = $this->db->query($sql)->result_array();
		if (!$field) return $this->halt("无法通过( $sql )获取到数据表字段结构，系统模块无法使用，解决方案：为Mysql账号开启SHOW FULL COLUMNS权限", 0);
	}
	
	/**
     * 检测结果
     */
    private function _result() {
		return $this->halt("系统检查完成", 1);
	}
	
	/**
     * 消息提示
     */
	private function halt($msg, $status = 1) {
	
		return $status ? "<tr><td align=\"left\"><font color=green><img width=\"16\" src=\"".SITE_URL."dayrui/statics/images/ok.png\">&nbsp;&nbsp;".$msg."</font></td></tr>" : "<tr><td align=\"left\"><font color=red><img width=\"16\" src=\"".SITE_URL."dayrui/statics/images/b_drop.png\">&nbsp;&nbsp;".$msg."</font></td></tr>";
	}
}