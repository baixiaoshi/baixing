<?php

/**
 * 基于Cookie的Session管理驱动程序 for CI 3.0
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class CI_Session_dcookie extends CI_Session_driver {

	public $now; // 当前时间
	public $sess_encrypt_cookie		= FALSE; // 是否要加密的会话cookie
	public $sess_use_database		= FALSE; // 是否使用用于会话存储会员uid到数据库中
	public $sess_table_name			= ''; // 存储session的数据库表
	public $sess_expiration			= 7200; // 会话过期的时间（秒）
	public $sess_expire_on_close	= FALSE; // 是否关闭浏览器窗口时结束会话
	public $sess_match_ip			= FALSE; // IP地址匹配
	public $sess_match_useragent	= TRUE; // 客户端匹配
	public $sess_cookie_name		= 'ci_session'; // 会话cookie的名称
	public $cookie_prefix			= ''; // 会话cookie前缀
	public $cookie_path				= ''; // cookie路径
	public $cookie_domain			= ''; // cookie的域
	public $cookie_secure			= FALSE; // 是否设置cookie只在HTTPS连接
	public $cookie_httponly 		= FALSE; // cookie是否应该只允许由服务器发送
	public $sess_time_to_update		= 300; // 更新会话间隔
	public $encryption_key			= ''; // 加密会话cookie的安全密钥
	public $time_reference			= 'local'; // 使用时区
	public $userdata				= array(); // Session数据
	protected $defaults = array(
		'session_id' => NULL,
		'ip_address' => NULL,
		'user_agent' => NULL,
		'last_activity' => NULL
	); // 默认session数据数组
	protected $data_dirty = FALSE; // 数据库更新的标志

	/**
	 * 初始化
	 *
	 * @return	void
	 */
	protected function initialize() {
		$prefs = array(
			'sess_encrypt_cookie',
			'sess_use_database',
			'sess_table_name',
			'sess_expiration',
			'sess_expire_on_close',
			'sess_match_ip',
			'sess_match_useragent',
			'sess_cookie_name',
			'cookie_path',
			'cookie_domain',
			'cookie_secure',
			'cookie_httponly',
			'sess_time_to_update',
			'time_reference',
			'cookie_prefix',
			'encryption_key'
		);
		foreach ($prefs as $key) {
			$this->$key = isset($this->_parent->params[$key]) ? $this->_parent->params[$key] : $this->CI->config->item($key);
		}
		if (!$this->encryption_key) show_error('请设置一个安全密钥！');
		if ($this->sess_encrypt_cookie === TRUE) $this->CI->load->library('encrypt');
		$this->sess_use_database = defined('SYS_TIME') ? TRUE : FALSE;
		if ($this->sess_use_database === TRUE && $this->sess_table_name !== '') { // 当开启数据库存储时加载数据库驱动
			$this->CI->load->database();
			$this->sess_table_name = $this->CI->db->dbprefix($this->sess_table_name);
			register_shutdown_function(array($this, '_update_db'));
		}
		$this->now = $this->_get_time(); // 当前时间，作为session活动时间
		if ($this->sess_expiration === 0) $this->sess_expiration = (60*60*24*365*2); // 默认session生命周期
		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name; // cookie name
		if (!$this->_sess_read()) {
			$this->_sess_create(); // session会话不存在时创建新会话
		} else {
			$this->_sess_update(); // 更新会话
		}
		$this->_sess_gc(); // 删除过期的会话
	}
	
	/**
	 * 写Session数据
	 *
	 * @return	void
	 */
	public function sess_save() {
		if ($this->sess_use_database === TRUE && isset($this->userdata['uid'])) $this->data_dirty = TRUE; // 数据库开启时，标识更新数据表
		$this->_set_cookie(); // 会话数据写入cookie
	}

	/**
	 * 摧毁当前Session
	 *
	 * @return	void
	 */
	public function sess_destroy() {
		
		if ($this->sess_use_database === TRUE && isset($this->userdata['session_id']) && isset($this->userdata['uid'])) {
			$this->CI->db->delete($this->sess_table_name, array('uid' => $this->userdata['uid'])); // 销毁数据库中记录
			$this->data_dirty = FALSE;
		}
		$this->_setcookie($this->sess_cookie_name, '', ($this->now - 31500000), $this->cookie_path, $this->cookie_domain, 0); // 销毁cookie数据
		$this->userdata = array();
	}
	
	/**
	 * 重新生成当前session
	 *
	 * @param	bool	销毁会话数据标识 (默认FALSE)
	 * @return	void
	 */
	public function sess_regenerate($destroy = FALSE) {
		if ($destroy) {
			$this->sess_destroy(); // 摧毁旧的会话并创建新的
			$this->_sess_create();
		} else {
			$this->_sess_update(TRUE); // 强制更新重建的session_id
		}
	}

	/**
	 * 获取用户数据数组
	 *
	 * @return	array	
	 */
	public function &get_userdata() {
		return $this->userdata;
	}

	/**
	 * 获取当前Session的数据
	 *
	 * @return	bool
	 */
	protected function _sess_read() {
		$session = $this->CI->input->cookie($this->sess_cookie_name); // 取cookie
		if ($session === NULL) {
			log_message('debug', 'Session cookie数据不存在！'); // session为空时退出
			return FALSE;
		}
		$len = strlen($session) - 40;
		if ($len < 0) {
			log_message('debug', 'Session没有注册！');
			return FALSE;
		}
		$hmac = substr($session, $len);
		$session = substr($session, 0, $len);
		if ($hmac !== hash_hmac('sha1', $session, $this->encryption_key)) { // session安全匹配
			log_message('debug', 'Session不匹配！');
			$this->sess_destroy();
			return FALSE;
		}
		if ($this->sess_encrypt_cookie === TRUE) $session = $this->CI->encrypt->decode($session); // 解密cookie数据
		$session = $this->_unserialize($session); // 反序列化
		if (!is_array($session) || !isset($session['session_id'], $session['ip_address'], $session['user_agent'], $session['last_activity'])) {
			log_message('debug', '反序列化后数据格式不正确时销毁session');
			$this->sess_destroy(); // 反序列化后数据格式不正确时销毁session
			return FALSE;
		}
		if (($session['last_activity'] + $this->sess_expiration) < $this->now || $session['last_activity'] > $this->now) {
			$this->sess_destroy(); // 会话过期销毁
			log_message('debug', '会话过期销毁');
			return FALSE;
		}
		if ($this->sess_match_ip === TRUE && $session['ip_address'] !== $this->CI->input->ip_address()) {
			$this->sess_destroy(); // Ip验证失败时销毁
			log_message('debug', 'Ip验证失败时销毁');
			return FALSE;
		}
		if ($this->sess_match_useragent === TRUE && trim($session['user_agent']) !== trim(substr($this->CI->input->user_agent(), 0, 120))) {
			$this->sess_destroy(); // 客户端验证失败时销毁
			log_message('debug', 'Ip验证失败时销毁');
			return FALSE;
		}
		$this->userdata = $session;
		return TRUE;
	}

	/**
	 * 创建新session
	 *
	 * @return	void
	 */
	protected function _sess_create() {
		$this->userdata = array(
			'session_id' => $this->_make_sess_id(),
			'ip_address' => $this->CI->input->ip_address(),
			'user_agent' => trim(substr($this->CI->input->user_agent(), 0, 120)),
			'last_activity'	=> $this->now
		); // 初始化session数据
		log_message('debug', '创建一个会话');
		$this->_set_cookie(); // 写入cookie
	}
	
	/**
	 * 更新存在的session
	 *
	 * @param	bool	强制更新标志
	 * @return	void
	 */
	protected function _sess_update($force = FALSE) {
		if (!$force && ($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now) return;  // 默认每五分钟更新会话
		$this->userdata['last_activity'] = $this->now; // 更新活动时间
		$old_sessid = $this->userdata['session_id']; // 保存旧的会话ID用于数据库记录查询
		// 更改SessionID在AJAX调用会导致问题
		if (!$this->CI->input->is_ajax_request()) {
			$this->userdata['session_id'] = $this->_make_sess_id(); // 获取新的ID
			if ($this->sess_use_database === TRUE && $this->data_dirty === TRUE && isset($this->userdata['uid'])) {
				// 更新新的ID
				$this->CI->db->reset_query();
				$this->CI->db->where('session', $old_sessid);
				$this->CI->db->update($this->sess_table_name, array(
					'time' => $this->userdata['last_activity'],
					'session' => $this->userdata['session_id']
				));
				$this->data_dirty = FALSE; // 清除数据更新标志，以避免双重更新
				log_message('debug', '更新新id');
			}
		}
		$this->_set_cookie(); // 写入cookie
	}
	
	/**
	 * 当前数据更新至数据库
	 *
	 * @return	void
	 */
	public function _update_db() {
		if ($this->sess_use_database === TRUE && $this->data_dirty === TRUE && isset($this->userdata['uid'])) {
			// 更新活动时间和uid
			$this->CI->db->reset_query();
			$this->CI->db->replace($this->sess_table_name, array(
				'uid' => $this->userdata['uid'],
				'time' => $this->userdata['last_activity'],
				'session' => $this->userdata['session_id']
			));
			$this->data_dirty = FALSE; // 清除数据更新标志，以避免双重更新
			log_message('debug', 'CI_Session Data Saved To DB');
		}
	}
	
	/**
	 * 生成新的session_id
	 *
	 * @return	string	Hashed session id
	 */
	protected function _make_sess_id() {
		$new_sessid = '';
		do {
			$new_sessid .= mt_rand();
		}
		while (strlen($new_sessid) < 32);
		$new_sessid .= $this->CI->input->ip_address(); // 为了使会话ID更加安全结合用户的IP
		return md5(uniqid($new_sessid, TRUE)); // 返回哈希值
	}

	/**
	 * 当前时间
	 *
	 * @return	int	 Time
	 */
	protected function _get_time() {
		if ($this->time_reference === 'local' OR $this->time_reference === date_default_timezone_get()) return time();
		$datetime = new DateTime('now', new DateTimeZone($this->time_reference));
		sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	
	/**
	 * 写入session到cookie
	 *
	 * @return	void
	 */
	protected function _set_cookie() {
		if ($this->sess_use_database === TRUE && isset($this->userdata['uid'])) { // uid 存储数据库
			if ($this->userdata['uid']) {
				$this->CI->db->replace($this->sess_table_name, array(
					'uid' => (int)$this->userdata['uid'],
					'time' => $this->userdata['last_activity'],
                    'session' => $this->userdata['session_id']
				));
			} else {
				$this->CI->db->where('session', $this->userdata['session_id'])->delete($this->sess_table_name);
			}
		}
		$cookie_data = $this->_serialize($this->userdata);
		if ($this->sess_encrypt_cookie === TRUE) $cookie_data = $this->CI->encrypt->encode($cookie_data); // 加密cookie
		$cookie_data .= hash_hmac('sha1', $cookie_data, $this->encryption_key);
		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();
		$this->_setcookie($this->sess_cookie_name, $cookie_data, $expire, $this->cookie_path, $this->cookie_domain, $this->cookie_secure, $this->cookie_httponly);
	}
	
	/**
	 * Set a cookie with the system
	 *
	 * @param	string	Cookie name
	 * @param	string	Cookie value
	 * @param	int	Expiration time
	 * @param	string	Cookie path
	 * @param	string	Cookie domain
	 * @param	bool	Secure connection flag
	 * @param	bool	HTTP protocol only flag
	 * @return	void
	 */
	protected function _setcookie($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = FALSE, $httponly = FALSE) {
		setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}
	
	/**
	 * 序列化数组
	 *
	 * @param	mixed	Data to serialize
	 * @return	string	Serialized data
	 */
	protected function _serialize($data) {
		if (is_array($data)) {
			array_walk_recursive($data, array(&$this, '_escape_slashes'));
		} elseif (is_string($data)) {
			$data = str_replace('\\', '{{slash}}', $data);
		}
		return serialize($data);
	}
	
	/**
	 * Escape slashes
	 *
	 * This function converts any slashes found into a temporary marker
	 *
	 * @param	string	Value
	 * @param	string	Key
	 * @return	void
	 */
	protected function _escape_slashes(&$val, $key) {
		if (is_string($val)) $val = str_replace('\\', '{{slash}}', $val);
	}

	/**
	 * 反序列化
	 *
	 * @param	mixed	Data to unserialize
	 * @return	mixed	Unserialized data
	 */
	protected function _unserialize($data) {
		$data = @unserialize(trim($data));
		if (is_array($data)) {
			array_walk_recursive($data, array(&$this, '_unescape_slashes'));
			return $data;
		}
		return is_string($data) ? str_replace('{{slash}}', '\\', $data) : $data;
	}

	/**
	 * Unescape slashes
	 *
	 * This function converts any slash markers back into actual slashes
	 *
	 * @param	string	Value
	 * @param	string	Key
	 * @return	void
	 */
	protected function _unescape_slashes(&$val, $key) {
		if (is_string($val)) $val = str_replace('{{slash}}', '\\', $val);
	}

	/**
	 * 从数据库中删除过期的会话
	 *
	 * @return	void
	 */
	protected function _sess_gc() {
		if ($this->sess_use_database !== TRUE) return;
		$probability = ini_get('session.gc_probability');
		$divisor = ini_get('session.gc_divisor');
		if ((mt_rand(0, $divisor) / ($divisor/2)) < $probability) {
			//$expire = $this->now - $this->sess_expiration;
			$expire = $this->now - 1800; // 会员活动情况 30 分钟一次
			$this->CI->db->delete($this->sess_table_name, 'time < '.$expire);
			log_message('debug', 'Session garbage collection performed.');
		}
	}

}
