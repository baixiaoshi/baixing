<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.1.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Member_model extends CI_Model {
    
	/**
	 * 初始化
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 会员修改信息
	 * 
	 * @param	array	$main	主表字段
	 * @param	array	$data	附加表字段
	 * @return	void
	 */
	public function edit($main, $data) {
	
		if (isset($main['check']) && $main['check']) {
			$main['ismobile'] = 1;
			$main['randcode'] = '';
			unset($main['check'], $main['phone']);
		}
		
		if (isset($main['check'])) unset($main['check']);
		
		$this->db
			 ->where('uid', $this->uid)
			 ->update('member', $main);
		
		$data['uid'] = $this->uid;
		$data['complete'] = 1;
		
		$this->db->replace('member_data', $data);
		
		return TRUE;
	}
	
	/**
	 * 会员基本信息
	 * 
	 * @param	intval|string	$key
	 * @param	intval			$type 0按id，1按会员名
	 * @return	array
	 */
	public function get_base_member($key, $type = 0) {
		
		if (!$key) return NULL;
		
		if ($type) {
			$this->db->where('username', $key);
		} else {
			$this->db->where('uid', (int)$key);
		}
		
		$data = $this->db
					 ->limit(1)
					 ->select('uid,username,email,levelid,groupid')
					 ->get('member')
					 ->row_array();
		
		if (!$data) return NULL;
		
		$data['markrule'] = $data['groupid'] < 3 ? $data['groupid'] : ($data['groupid'].'_'.$data['levelid']);
		
		return $data;
	}
	
	/**
	 * 会员权限标识
	 * 
	 * @param	intval	uid
	 * @return	string
	 */
	public function get_markrule($uid) {
		
		if (!$uid) return 0;
		
		$data = $this->db
					 ->select('groupid,levelid')
					 ->where('uid', (int)$uid)
					 ->limit(1)
					 ->get($this->db->dbprefix('member'))
					 ->row_array();
		if (!$data) return 0;
		
		return $data['groupid'] < 3 ? $data['groupid'] : ($data['groupid'].'_'.$data['levelid']);
	}
	
	/**
	 * 会员信息
	 * 
	 * @param	intval	uid
	 * @return	array
	 */
	public function get_member($uid) {
		
		if (!$uid) return NULL;
		
		// 查询会员信息
		$data = $this->db
					 ->from($this->db->dbprefix('member').' AS m')
					 ->join($this->db->dbprefix('member_data').' AS a', 'a.uid=m.uid', 'left')
					 ->where('m.uid', $uid)
					 ->limit(1)
					 ->get()
					 ->row_array();
		
		if (!$data) return NULL;
		
		$group = $this->ci->get_cache('member', 'group');
		$data['uid'] = $uid;
		$data['mark'] = $data['groupid'] < 3 ? $data['groupid'] : ($data['groupid'].'_'.$data['levelid']);
		$data['group'] = $group[$data['groupid']];
		$data['level'] = $group[$data['groupid']]['level'][$data['levelid']];
        $data['tableid'] = (int)substr((string)$uid, -1, 1);
		
		// 快捷登陆用户信息提取
		$oauth = $this->db
					  ->where('uid', $uid)
					  ->order_by('expire_at desc')
					  ->get('member_oauth')
					  ->result_array();
		if ($oauth) {
			foreach ($oauth as $t) {
				if (!$data['username']) $data['username'] = $t['nickname'];
				$data['oauth'][$t['oauth']] = $t;
			}
		}
		
		// 会员组过期判断
		if ($data['overdue'] && $group[$data['groupid']]['price'] && $data['overdue'] < SYS_TIME) {
			// 虚拟币自动扣费
			if ($group[$data['groupid']]['unit'] == 1 && $data['score'] - abs(intval($group[$data['groupid']]['price'])) > 0) {
				
				/* 挂钩点：虚拟币自动扣费*/
				$this->update_score(1, $uid, -abs(intval($group[$data['groupid']]['price'])), '', "lang,m-128");
				$time = $this->member_model->upgrade($uid, $data['groupid'], $group[$data['groupid']]['limit'], $data['overdue']);
				$time = $time > 2000000000 ? lang('m-265') : dr_date($time);
				// 邮件提醒
				$this->ci->sendmail_queue(
					$this->member['email'], 
					lang('m-263'), 
					dr_lang('m-264', $data['name'] ? $data['name'] : $data['username'], $group[$data['groupid']]['name'], $time)
				);
				$this->add_notice($uid, 1, lang('m-263'));
			} else {
				// 转为过期的后的会员组
				$this->db
					 ->where('uid', $uid)
					 ->update('member', array(
						'levelid' => 0,
						'overdue' => 0,
						'groupid' => $data['group']['overdue'],
					 ));
				$data['groupid'] = $data['group']['overdue'];
				$data['group'] = $group[$data['group']['overdue']];
				
				/* 挂钩点：会员组过期转为原来的组*/
				$this->add_notice($uid, 1, lang('m-081'));
			}
		}
		
		if ($data['group']['level']) {
			// 会员升级处理
			$level = array_reverse($data['group']['level']); // 倒序判断
			foreach ($level as $t) {
				if ($data['experience'] >= $t['experience']) {
					if ($data['levelid'] != $t['id']) {
						$this->db
							 ->where('uid', $uid)
							 ->update('member', array(
								'levelid' => $t['id']
							 ));
						$data['levelid'] = $t['id'];
						$data['level'] = $group[$data['groupid']]['level'][$data['levelid']];
						
						/* 挂钩点：会员组等级升级*/
						$this->add_notice($uid, 1, lang('m-082'));
					}
					break;
				}
			}
		}
		
		return $data;
	}
	
	/**
	 * 通过会员id取会员名称
	 *
	 * @param	intval	$uid
	 * @return  string
	 */
	function get_username($uid) {
	
		if (!$uid) return NULL;
		
		$data = $hits->db
					 ->select('username')
					 ->where('uid', (int)$uid)
					 ->limit(1)
					 ->get('member')
					 ->row_array();
				   
		return $data['username'];
	}
	
	/**
	 * 会员组续费/升级
	 *
	 * @param	intval	$uid		会员uid
	 * @param	intval	$groupid	组id
	 * @param	intval	$limit		limit值
	 * @param	intval	$time		当前过期时间，为0时表示新开
	 * @return	intval
	*/
	public function upgrade($uid, $groupid, $limit, $time = 0) {
		
		if (!$uid || !$groupid || !$limit) return FALSE;
		
		// 得到增加的时间戳
		switch($limit) {
			
			case 1: // 月
				$limit += 86400 * 30;
				break;
				
			case 2: // 半年
				$limit += 86400 * 30 * 180;
				break;
				
			case 3: // 年
				$limit += 86400 * 30 * 360;
				break;
				
			case 4: // 永久
				$limit = 4294967295;
				break;
		}
		
		$time = $time < SYS_TIME ? SYS_TIME + $limit : $time + $limit;
		
		$this->db
			 ->where('uid', $uid)
			 ->update('member', array(
				'groupid' => $groupid,
				'overdue' => $time,
			 ));
		
		$this->add_notice($uid, 1, lang('m-083'));
		
		return $time;
	}
	
	/**
	 * 后台管理员验证登录
	 *
	 * @param	string	$username	会员名称
	 * @param	string	$password	明文密码
	 * @return	int
	 * int	id	登录成功
	 * int	-1	用户不存在
	 * int	-2	密码不正确
	 * int	-3	您无权限登录管理平台
	 * int	-4	您无权限登录该站点
	*/
	public function admin_login($username, $password) {
	
		$data =	$this->db // 查询用户信息
					 ->select('`password`, `salt`, `adminid`,`uid`')
					 ->where('username', $username)
					 ->limit(1)
					 ->get('member')
					 ->row_array();
					 
		if (!$data) { // 判断用户状态
			return -1;
		} elseif (md5(md5($password).$data['salt'].md5($password)) != $data['password']) {
			return -2;
		} elseif ($data['adminid'] == 0) {
			return -3;
		}
		
		if (!$this->is_admin_auth($data['adminid'])) return -4; // 站点权限判断
		$ip = $this->input->ip_address();
		$admin = $this->db->where('uid', $data['uid'])->limit(1)->get($this->db->dbprefix('admin'))->row_array();
		
		if (!$admin['loginip'] || $admin['loginip'] != $ip) { // 登录记录
			$this->db->update('admin', array(
				'loginip' => $ip,
				'logintime' => time(),
				'lastloginip' => $admin['loginip'], 
				'lastlogintime'	=> $admin['logintime']
			));
		}
		
		// 保存会话
		$this->session->set_userdata('uid', $data['uid']); 
		$this->session->set_userdata('admin', $data['uid']); 
		$this->input->set_cookie('member_uid', $data['uid'], 86400);
		$this->input->set_cookie('member_cookie', substr(md5(SYS_KEY.$data['uid']), 5, 20), 86400);
		
		return $data['uid'];
	}
	
	/**
	 * 管理员用户信息
	 * 
	 * @param	int	$uid	用户id
	 * @param	int	$verify	是否验证该管理员权限
	 * @return	array|int
	 * int	-3	您无权限登录管理平台
	 * int	-4	您无权限登录该站点
	 * array	管理员用户信息数组
	 */
	public function get_admin_member($uid, $verify = 0) {
	
		$data = $this->db // 查询用户信息
					 ->select('m.uid,m.email,m.username,m.adminid,m.groupid,a.realname,a.usermenu,a.lastloginip,a.lastlogintime,a.loginip,a.logintime')
					 ->from($this->db->dbprefix('member').' AS m')
					 ->join($this->db->dbprefix('admin').' AS a', 'a.uid=m.uid', 'left')
					 ->where('m.uid', $uid)
					 ->limit(1)
					 ->get()
					 ->row_array();
		if (!$data) return 0;
		
		if ($verify) { // 判断用户状态
			if ($data['adminid'] == 0) {
				return -3;
			} elseif (!$this->is_admin_auth($data['adminid'])) {
				return -4;
			}
		}
		
		$role = $this->dcache->get('role');
		$data['role'] = $role[$data['adminid']];
		$data['usermenu'] = dr_string2array($data['usermenu']);
		$data['usermenu'] = dr_string2array($data['usermenu']);
		
		return $data;
	}
	
	/**
	 * 管理员权限验证
	 * 
	 * @param	int	$adminid	管理员id
	 * @return	bool
	 */
	public function is_admin_auth($adminid) {
	
		$role = $this->dcache->get('role');
		$role = $role ? $role : $this->auth_model->role_cache();
		
		if ($adminid == 1) return TRUE;
		
		return @in_array(SITE_ID, $role[$adminid]['site']) ? TRUE : FALSE;
	}
	
	/**
	 * 管理人员
	 *
	 * @param	int		$roleid		角色组id
	 * @param	string	$keyword	匹配关键词
	 * @return	array
	 */
	public function get_admin_all($roleid = 0, $keyword = NULL) {
	
		$select	= $this->db
					   ->from($this->db->dbprefix('admin').' AS a')
					   ->join($this->db->dbprefix('member').' AS b', 'a.uid=b.uid', 'left');
					   
		$select->join($this->db->dbprefix('admin_role').' AS c', 'b.adminid=c.id', 'left');
		
		if ($roleid) $select->where('b.adminid', $roleid);
		if ($keyword) $select->like('b.username', $keyword);
		
		return $select->get()->result_array();
	}
	
	/**
	 * 添加管理人员
	 *
	 * @param	array	$insert	入库管理表内容
	 * @param	array	$update	更新会员表内容
	 * @param	int		$uid	uid
	 * @return	void
	 */
	public function insert_admin($insert, $update, $uid) {
		$this->db->where('uid', $uid)->update('member', $update);
		$this->db->replace('admin', $insert);
	}
	
	/**
	 * 修改管理人员
	 *
	 * @param	array	$insert	入库管理表内容
	 * @param	array	$update	更新会员表内容
	 * @param	int		$uid	uid
	 * @return	void
	 */
	public function update_admin($insert, $update, $uid) {
		$this->db->where('uid', $uid)->update('member', $update);
		$this->db->where('uid', $uid)->update('admin', $insert);
	}
    
	/**
	 * 移除管理人员
	 *
	 * @param	int		$uid	uid
	 * @return	void
	 */
	public function del_admin($uid) {
		
		if ($uid == 1) return NULL;
		
		$this->db->where('uid', $uid)->delete('admin');
		$this->db->where('uid', $uid)->update('member', array('adminid' => 0));
	}
	
	/**
	 * 通过OAuth登录
	 *
	 * @param	string	$appid	OAuth服务商名称
	 * @param	array	$data	授权返回数据
	 * @return	sting
	 */
	public function OAuth_login($appid, $data) {
	
		$oauth = $this->db // 判断OAuth是否已经注册到oauth表
					  ->select('id,uid')
					  ->where('oid', $data['oid'])
					  ->where('oauth', $appid)
					  ->limit(1)
					  ->get('member_oauth')
					  ->row_array();
					  
		if ($oauth) { // 已经注册就直接保存登录会话，更新表中的记录
			$uid = $oauth['uid'];
			$this->db
				 ->where('id', $oauth['id'])
				 ->update('member_oauth', $data);
		} else { // 没有注册时，就直接注册会员账号
			$uid = $data['uid']	= $this->_register($data, $appid);
			$this->db->insert('member_oauth', $data); // 保存OAuth数据
		}
		
		$member = $this->db // 查询会员表
					   ->where('uid', $uid)
					   ->select('uid,username,salt,loginlog')
					   ->limit(1)
					   ->get('member')
					   ->row_array();
					   
		$MEMBER = $this->ci->get_cache('member');
		$this->_login($member, $appid);
		if ($MEMBER['setting']['ucenter'] && $member['username'] && $ucdata = uc_get_user($member['username'])) { // Ucenter 验证
			list($uid, $username, $email) = $ucdata;
			return uc_user_synlogin($uid);
		}
		
		$synlogin = '';
		foreach ($MEMBER['synurl'] as $url) {
			$code = $this->encrypt->encode($member['uid'].'-'.$member['salt']);
			$synlogin .= '<script type="text/javascript" src="'.$url.'/index.php?c=api&m=synlogin&expire='.$expire.'&code='.$code.'"></script>';
		}
		
		return $synlogin;
	}
	
	/**
	 * OAuth绑定当前账户
	 *
	 * @param	string	$appid	OAuth服务商名称
	 * @param	array	$data	授权返回数据
	 * @return	sting
	 */
	public function OAuth_bang($appid, $data) {
	
		$oauth = $this->db // 判断OAuth是否已经注册到oauth表
					  ->select('id,uid')
					  ->where('oid', $data['oid'])
					  ->where('oauth', $appid)
					  ->limit(1)
					  ->get('member_oauth')
					  ->row_array();
					  
		if ($oauth) { // 已经存在就直接更新表中的记录
			if ($oauth['uid'] !== $this->uid) return $oauth['uid']; // 其他账户绑定了时返回其他账户uid
			$this->db->where('id', $oauth['id'])->update('member_oauth', $data);
		} else { // 不存在时就保存OAuth数据
			$data['uid'] = $this->uid;
			$this->db->insert($this->db->dbprefix('member_oauth'), $data);
		}
		
		return NULL;
	}
	
	/**
	 * 前端会员验证登录
	 *
	 * @param	string	$username	用户名
	 * @param	string	$password	明文密码
	 * @param	intval	$expire	会话生命周期
	 * @return	string|intval
	 * string	登录js同步代码
	 * int	-1	会员不存在
	 * int	-2	密码不正确
	 * int  -3	Ucenter注册失败
	 * int  -4	Ucenter：会员名称不合法
	*/
	public function login($username, $password, $expire) {
	
		$MEMBER = $this->ci->get_cache('member');
		$ucsynlogin = '';
		$data =	$this->db // 查询会员信息
					 ->select('`uid`, `password`, `salt`, `email`, `username`, `loginlog`')
					 ->where('username', $username)
					 ->limit(1)
					 ->get('member')
					 ->row_array();
					 
		if ($MEMBER['setting']['ucenter']) { // Ucenter 验证
			list($uid, $username, $password, $email) = uc_user_login($username, $password);
			if ($uid > 0) { // 当前会员不存在时就重新注册
				if (!$data) {
					$data['uid'] = $this->_register(array('username' => $username, 'password' => $password, 'email' => $email));
					if (!$data['uid']) return -3;
				} 
				$ucsynlogin = uc_user_synlogin($uid);
			} elseif ($uid == -1) { // Ucenter会员不存在
				if (!$data) return -1;
				// 注册Ucenter会员
				$uid = uc_user_register($data['username'], $password, $data['email']);
				if ($uid > 0) {
					$ucsynlogin = uc_user_synlogin($uid);
				} elseif ($uid == -1) {
					return -4;
				} else {
					return -3;
				}
			} else {
				return -2;
			}
		} else {
			if (!$data) return -1; // 会员不存在
			if (md5(md5($password).$data['salt'].md5($password)) != $data['password']) return -2;
		}
		
		$this->_login($data, '');
		
		if ($ucsynlogin) return $ucsynlogin; // 存在Ucenter时采用Ucenter同步方式
		
		$synlogin = '';
		if ($MEMBER['synurl']) {
			foreach ($MEMBER['synurl'] as $url) {
				$code = $this->encrypt->encode($data['uid'].'-'.$data['salt']);
				$synlogin .= '<script type="text/javascript" src="'.$url.'/index.php?c=api&m=synlogin&expire='.$expire.'&code='.$code.'"></script>';
			}
		} else {
			$code = $this->encrypt->encode($data['uid'].'-'.$data['salt']);
			$synlogin = '<script type="text/javascript" src="'.SITE_URL.'member/index.php?c=api&m=synlogin&expire='.$expire.'&code='.$code.'"></script>';
		}
		
		return $synlogin;
	}
	
	/**
	 * 会员登录记录
	 *
	 * @param	array	$data
	 * @param	string	$OAuth
	*/
	private function _login($data, $OAuth = '') {
	
		$login = dr_string2array($data['loginlog']);
		$total = count($login);
		
		if ($total && $login[$total-1]['login_ip'] == $this->input->ip_address()) {
			// Ip一致就更新登录时间
			$login[total-1]['login_time'] = SYS_TIME;
			$login[total-1]['login_type'] = $OAuth;
		} else {
			$keyid = min($total, 10); // 最大存储10次登录信息
			$login[$keyid] = array(
				'login_ip' => $this->input->ip_address(),
				'login_time' => SYS_TIME,
				'login_type' => $OAuth
			);
		}
		
		$this->db
			 ->where('uid', $data['uid'])
			 ->update('member', array('loginlog' => dr_array2string($login)));
	}
	
	/**
	 * 前端会员退出登录
	 *
	 * @return	string
	*/
	public function logout() {
		
		$MEMBER = $this->ci->get_cache('member');
		if ($MEMBER['setting']['ucenter']) return uc_user_synlogout();
		
		$synlogin = '';
		foreach ($MEMBER['synurl'] as $url) {
			$synlogin .= '<script type="text/javascript" src="'.$url.'/index.php?c=api&m=synlogout"></script>';
		}
		
		return $synlogin;
	}
	
	/**
	 * 注册会员 验证
	 *
	 * @param	array	$data	会员数据
	 * @return	int
	 * int	uid	注册成功
	 * int	-1	会员名称已经存在
	 * int	-2	Email格式有误
	 * int	-3	Email已经被注册
	 * int	-4	同一IP注册限制
	 * int	-5	Ucenter 会员名不合法
	 * int	-6	Ucenter 包含不允许注册的词语
	 * int	-7	Ucenter Email 格式有误
	 * int	-8	Ucenter Email 不允许注册
	 * int	-9	Ucenter Email 已经被注册 
	 */
	public function register($data, $groupid = NULL, $uid = NULL) {
	
		$setting = $this->ci->get_cache('member', 'setting');
		$this->ucsynlogin = $this->synlogin = '';
		
		if (!IS_ADMIN 
		&& !$uid 
		&& $setting['regiptime'] 
		&& $this->db->where('regip', $this->input->ip_address())->where('regtime>', SYS_TIME - 3600 * $setting['regiptime'])->count_all_results('member')) return -4;
		if (!$data['email'] || !preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $data['email'])) return -2;
		if ($this->db->where('email', $data['email'])->count_all_results('member')) return -3;
		if ($this->db->where('username', $data['username'])->count_all_results('member')) return -1;
		if ($setting['ucenter'] && uc_get_user($data['username'])) return -1;
		
		if ($setting['ucenter']) { // Ucenter 注册判断
			$ucid = uc_user_register($data['username'], $data['password'], $data['email']);
			if ($ucid == -1) {
				return -5;
			} elseif ($ucid == -2) {
				return -6;
			} elseif ($ucid == -4) {
				return -7;
			} elseif ($ucid == -5) {
				return -8;
			} elseif ($ucid == -6) {
				return -9;
			}
			$this->ucsynlogin = uc_user_synlogin($ucid);
		}
		
		return $this->_register($data, NULL, $groupid, $uid);
	}
	
	/**
	 * 注册会员 入库
	 *
	 * @param	array	$data		会员数据
	 * @param	string	$OAuth		OAuth名称
	 * @param	intval	$groupid	组id
	 * @return	int
	 */
	private function _register($data, $OAuth = NULL, $groupid = NULL, $uid = NULL) {
		
		$salt = substr(md5(rand(0, 999)), 0, 10); // 随机10位密码加密码
		$regverify = $this->ci->get_cache('member', 'setting', 'regverify');
		
		if ($OAuth) { // OAuth注册时，会员初始化信息
			$username = '';
			$regoauth = $this->ci->get_cache('member', 'setting', 'regoauth');
			if ($regoauth) {
				$username = $this->db->where('username', $data['username'])->count_all_results('member') ? $data['nickname'].'2' : $data['nickname'];
			}
			$this->db->insert('member', array(
				'salt' => $salt,
				'name' => $data['nickname'],
				'phone' => '',
				'regip' => $this->input->ip_address(),
				'email' => '',
				'money' => 0,
				'score' => 0,
				'avatar' => $data['avatar'],
				'freeze' => 0,
				'regtime' => SYS_TIME,
				'groupid' => 2,
				'levelid' => 0,
				'overdue' => 0,
				'username' => $username,
				'password' => '',
				'randcode' => 0,
				'ismobile' => 0,
				'loginlog' => '',
				'experience' => 0,
			));
			$uid = $this->db->insert_id();
		} elseif ($uid) {
			$this->db->where('uid', (int)$uid)->update('member', array(
				'salt' => $salt,
				'email' => $data['email'],
				'groupid' => 3,
				'username' => $data['username'],
				'password' => md5(md5($data['password']).$salt.md5($data['password']))
			));
		} else { // 非OAuth注册，会员初始化信息
			$this->db->insert('member', array(
				'salt' => $salt,
				'name' => '',
				'phone' => '',
				'regip' => $this->input->ip_address(),
				'email' => $data['email'],
				'money' => 0,
				'score' => 0,
				'avatar' => '',
				'freeze' => 0,
				'regtime' => SYS_TIME,
				'groupid' => $groupid ? $groupid : ($regverify ? 1 : 3),
				'levelid' => 0,
				'overdue' => 0,
				'username' => $data['username'],
				'password' => md5(md5($data['password']).$salt.md5($data['password'])),
				'randcode' => 0,
				'ismobile' => 0,
				'loginlog' => '',
				'experience' => 0,
			));
			$uid = $this->db->insert_id();
		}
		
		// 邮件审核
		if (!$OAuth && $regverify == 1) {
			$code = $this->get_encode($uid);
			$url = MEMBER_URL.'index.php?c=login&m=verify&code='.$code;
			$this->sendmail($data['email'], lang('m-191'), dr_lang('m-192', $data['username'], $url, $url, $this->input->ip_address()));
		}
		
		return $uid;
	}
	
	/**
	 * 取会员COOKIE
	 *
	 * @return	int	$uid	会员uid
	 */
	public function member_uid() {
		
		$uid = (int)get_cookie('member_uid');
		$cookie = get_cookie('member_cookie');
		
		if (!$uid) return NULL;
		if (substr(md5(SYS_KEY.$uid), 5, 20) !== $cookie) return NULL;
		if (!$this->session->userdata('uid')) {
			$this->_login($data); // 更新登录时间
			$this->session->set_userdata('uid', $uid); // 更新会员活动时间
		}
		
		return $uid;
	}
	
	/**
	 * 会员配置信息
	 *
	 * @return	array
	 */
	public function setting($lang = SITE_LANGUAGE, $isdomain = FALSE) {
	
		$domain = $data = array();
		$setting = $this->db
						->get('member_setting')
						->result_array();
						
		foreach ($setting as $t) {
			if (strpos($t['name'], '_') !== FALSE) {
				$data['_'.$t['name']] = $t['value'];
			} elseif ($t['name'] == 'permission' || $t['name'] == 'pay' || $t['name'] == 'space') {
				$data[$t['name']] = dr_string2array($t['value']);
			} else {
				$data[$t['name']] = $t['value'];
				if ($isdomain && strpos($t['name'], 'domain-') !== FALSE && $t['value']) $domain[] = 'http://'.$t['value'];
			}
		}
		
		return $isdomain ? array($data, $domain) : $data;
	}
	
	/**
	 * 会员权限
	 *
	 * @param	intval	$id		权限组标识
	 * @param	string	$set	权限组值
	 * @return	array
	 */
	public function permission($id, $set = NULL) {
	
		if (!$id) return NULL;
		
		$data = $this->db
					 ->where('name', 'permission')
					 ->limit(1)
					 ->get('member_setting')
					 ->row_array();
		$data = dr_string2array($data['value']);
		
		if ($set) { // 修改数据
			$data[$id] = $set;
			$update = array('value' => dr_array2string($data));
			$this->db->where('name', 'permission')->update('member_setting', $update);
		}
		
		return isset($data[$id]) ? $data[$id] : NULL;
	}
	
	/**
	 * 支付配置
	 *
	 * @param	array	$set	修改数据
	 * @return	array
	 */
	public function pay($set = NULL) {
	
		$data = $this->db
					 ->where('name', 'pay')
					 ->limit(1)
					 ->get('member_setting')
					 ->row_array();
		$data = dr_string2array($data['value']);
		
		if ($set) { // 修改数据
			$this->db
				 ->where('name', 'pay')
				 ->update('member_setting', array('value' => dr_array2string($set)));
			$data = $set;
		}
		
		return $data;
	}
	
	/**
	 * 会员配置
	 *
	 * @param	array	$set	修改数据
	 * @return	array
	 */
	public function space($set = NULL) {
	
		$data = $this->db
					 ->where('name', 'space')
					 ->limit(1)
					 ->get('member_setting')
					 ->row_array();
		$data = dr_string2array($data['value']);
		
		if ($set) { // 修改数据
			$this->db
				 ->where('name', 'space')
				 ->update('member_setting', array('value' => dr_array2string($set)));
			$data = $set;
		}
		
		return $data;
	}
	
	/**
	 * 会员缓存
	 *
	 * @param	int		$id
	 * @return	NULL
	 */
	public function cache() {
	
		$cache = array();
		$this->dcache->delete('member');
		
		// 会员自定义字段
		$field = $this->db
					  ->where('disabled', 0)
					  ->where('relatedid', 0)
					  ->where('relatedname', 'member')
					  ->order_by('displayorder ASC,id ASC')
					  ->get('field')
					  ->result_array();
		if ($field) {
			foreach ($field as $t) {
				$t['setting'] = dr_string2array($t['setting']);
				$cache['field'][$t['fieldname']] = $t;
			}
		}
		
		// 会员组
		$group = $this->db
					  ->order_by('displayorder ASC, id ASC')
					  ->get('member_group')
					  ->result_array();
		if ($group) {
			foreach ($group as $t) {
				$t['allowfield'] = dr_string2array($t['allowfield']);
				$level = $this->db // 会员等级
							  ->where('groupid', $t['id'])
							  ->order_by('experience ASC')
							  ->get('member_level')
							  ->result_array();
				if ($level) {
					foreach ($level as $l) {
						$t['level'][$l['id']] = $l;
					}
					$cache['group'][$t['id']] = $t;
				} elseif ($t['id'] < 3) {
					$cache['group'][$t['id']] = $t;
				}
			}
		}
		
		$domain = require FCPATH.'config/domain.php'; // 加载站点域名配置文件
		list($cache['setting'], $cache['synurl']) = $this->setting('', TRUE);
		foreach ($this->SITE as $sid => $t) {
			$cache['synurl'][] = 'http://'.$t['SITE_DOMAIN'].'/member';
			foreach ($domain as $url => $site_id) {
				if ($site_id == $sid && $t['SITE_DOMAIN'] != $url) $cache['synurl'][] = 'http://'.$url;
			}
		}
		if ($cache['setting']['space']['domain']) $cache['synurl'][] = prep_url($cache['setting']['space']['domain']);
		$cache['synurl'] = array_unique($cache['synurl']);
		
		if ($cache['setting']['ucenter']) { // 更新Ucenter配置
			$s = '<?php ' . PHP_EOL . '/* UCenter配置 */' . PHP_EOL
				. stripslashes($cache['setting']['ucentercfg']) 
				. PHP_EOL . '/* FineCMS配置 */' . PHP_EOL
				. '$dbhost    = \'' . $this->db->hostname. '\';' . PHP_EOL
				. '$dbuser    = \'' . $this->db->username . '\';' . PHP_EOL
				. '$dbpw      = \'' . $this->db->password . '\';' . PHP_EOL
				. '$dbname    = \'' . $this->db->database . '\';' . PHP_EOL
				. '$pconnect  = 0;' . PHP_EOL
				. '$tablepre  = \'' . $this->db->dbprefix . '\';' . PHP_EOL
				. '$dbcharset = \'utf8\';' . PHP_EOL
				. '/* 同步登录Cookie */' . PHP_EOL
				. 'define(\'SITE_KEY\', \'' . SYS_KEY . '\');' . PHP_EOL
				. 'define(\'SITE_PREFIX\', \'' . config_item('cookie_prefix') . '\');' . PHP_EOL
				. '?>';
			file_put_contents(FCPATH.'member/ucenter/config.inc.php', $s);
		}
		$this->ci->clear_cache('member');
		$this->dcache->set('member', $cache);
		
		return $cache;
	}
	
	/**
	 * 条件查询
	 *
	 * @param	object	$select	查询对象
	 * @param	array	$param	条件参数
	 * @return	array	
	 */
	private function _where(&$select, $param) {
	
		$_param = array();
		$file = md5($this->duri->uri(1).$this->uid.SITE_ID.$this->input->ip_address().$this->input->user_agent()); // 缓存文件名称
		
		// 存在POST提交时，重新生成缓存文件
		if (IS_POST) {
			$data = $this->input->post('data');
			$this->cache->file->save($file, $data, 3600);
			$param['search'] = 1;
		}
		
		// 存在search参数时，读取缓存文件
		if ($param['search'] == 1) {
			$data = $this->cache->file->get($file);
			$_param['search'] = 1;
			if (isset($data['keyword']) && $data['keyword']) {
				$select->like('username', urldecode($data['keyword']));
			}
			if (isset($data['groupid']) && $data['groupid']) {
				$select->where('groupid', $data['groupid']);
			}
		}
		
		return $_param;
	}
	
	/**
	 * 数据分页显示
	 *
	 * @param	array	$param	条件参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($param, $page, $total) {
	
		if (!$total) {
			$select	= $this->db->select('count(*) as total');
			$this->_where($select, $param);
			$data = $select->get('member')->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0));
			$page = 1;
		}
		
		$select	= $this->db->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		$_param	= $this->_where($select, $param);
		$data = $select->order_by('uid DESC')
					   ->get('member')
					   ->result_array();
		$_param['total'] = $total;
		
		return array($data, $_param);
	}
	
	/**
	 * 更新分数
	 *
	 * @param	intval	$type	0积分;1虚拟币
	 * @param	intval	$uid	会员id
	 * @param	intval	$value	分数变动值
	 * @param	string	$mark	标记
	 * @param	string	$note	备注
	 * @param	intval	$count	统计次数
	 * @return	intval
	 */
	public function update_score($type, $uid, $value, $mark, $note = '', $count = 0) {
	
		if (!$uid || !$value) return NULL;
		
		$table = $this->db->dbprefix('member_scorelog_'.(int)substr((string)$uid, -1, 1));
		if ($count && $this->db->where('type', (int)$type)->where('mark', $mark)->count_all_results($table) >= $count) return NULL;
		
		if ($type) {
			$this->db // 更新虚拟币
				 ->set('score', $value > 0 ? 'score+'.$value : 'score-'.abs($value), FALSE)
				 ->where('uid', (int)$uid)
				 ->update('member');
		} else {
			$this->db // 更新积分
				 ->set('experience', $value > 0 ? 'experience+'.$value : 'experience-'.abs($value), FALSE)
				 ->where('uid', (int)$uid)
				 ->update('member');
		}
		
		$this->db->insert($table, array(
			'uid' => $uid,
			'type' => $type,
			'mark' => $mark,
			'note' => $note,
			'value' => $value,
			'inputtime' => SYS_TIME,
		));
		
		return $this->db->insert_id();
    }
	
	/**
	 * 会员初始化处理
	 */
	public function init_member() {
	
		$time = strtotime(date('Y-m-d',strtotime('+1 day'))); // 明天凌晨时间戳
		
		// 每日登录积分处理
		if (!get_cookie('login_experience')
		&& !$this->db
				 ->where('uid', (int)$this->uid)
				 ->where('type', 0)
				 ->where('mark', 'login')
				 ->where('DATEDIFF(from_unixtime(inputtime),now())=0')
				 ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			set_cookie('login_experience', TRUE, $time - SYS_TIME);
			$this->update_score(0, $this->uid, (int)$this->member_rule['login_experience'], 'login', "lang,m-056");
		}
		
		// 每日登录虚拟币处理
		if (!get_cookie('login_score')
		&& !$this->db
				 ->where('uid', (int)$this->uid)
				 ->where('type', 1)
				 ->where('mark', 'login')
				 ->where('DATEDIFF(from_unixtime(inputtime),now())=0')
				 ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			set_cookie('login_score', TRUE, $time - SYS_TIME);
			$this->update_score(1, $this->uid, (int)$this->member_rule['login_score'], 'login', "lang,m-056");
		}
	}
	
	/**
	 * 邮件发送
	 *
	 * @param	string	$tomail
	 * @param	string	$subject
	 * @param	string	$message
	 * @return  bool
	 */
	public function sendmail($tomail, $subject, $message) {
	
		if (!$tomail || !$subject || !$message) return FALSE;
		
		$cache = $this->dcache->get('email');
		if (!$cache) return NULL;
		
		$this->load->library('Dmail');
		foreach ($cache as $data) {
			$this->dmail->set(array(
				'host' => $data['host'],
				'user' => $data['user'],
				'pass' => $data['pass'],
				'port' => $data['port'],
				'from' => $data['user'],
			));
			if ($this->dmail->send($tomail, $subject, $message)) return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * 短信发送
	 *
	 * @param	string	$mobile
	 * @param	string	$content
	 * @return  bool
	 */
	public function sendsms($mobile, $content) {
		
		if (!$mobile || !$content) return FALSE;
		
		$file = FCPATH.'config/sms.php';
		if (!is_file($file)) return FALSE;
		
		$config = require_once $file;
		if ($config['third']) {
			$this->load->helper('sms');
			if (function_exists('my_sms_send')) {
				$result = my_sms_send($mobile, $content.'【'.$config['note'].'】');
			} else{
				return FALSE;
			}
		} else {
			$result = dr_catcher_data('http://sms.dayrui.com/index.php?uid='.$config['uid'].'&key='.$config['key'].'&mobile='.$mobile.'&content='.$content.'【'.$config['note'].'】&domain='.trim(str_replace('http://', '', SITE_URL), '/').'&sitename='.SITE_NAME);
			if (!$result) return FALSE;
			$result = dr_object2array(json_decode($result));
		}
		
		@file_put_contents(FCPATH.'cache/sms_error.log', date('Y-m-d H:i:s').' ['.$mobile.'] ['.$result['msg'].'] （'.str_replace(array(chr(13), chr(10)), '', $content).'）'.PHP_EOL, FILE_APPEND);
		
		return $result;
		
	}
	
	/**
	 * 验证码加密
	 *
	 * @param	intval	$uid
	 * @return  string
	 */
	public function get_encode($uid) {
		$randcode = rand(1000, 9999);
		$this->encrypt->set_cipher(MCRYPT_BLOWFISH);
		$this->db->where('uid', $uid)->update('member', array('randcode' => $randcode));
		return $this->encrypt->encode(SYS_TIME.','.$uid.','.$randcode);
	}
	
	/**
	 * 验证码解码
	 *
	 * @param	string	$code
	 * @return  string
	 */
	public function get_decode($code) {
		$code = str_replace(' ', '+', $code);
		$this->encrypt->set_cipher(MCRYPT_BLOWFISH);
		return $this->encrypt->decode($code);
	}
	
	/**
	 * 会员删除
	 *
	 * @param	intval	$uid
	 * @return  bool
	 */
	public function delete($uids) {
	
		if (!$uids || !is_array($uids)) return NULL;
		
		$this->load->model('attachment_model');
		$app = $this->db->get('application')->result_array();
		
		foreach ($uids as $uid) {
			if ($uid == 1) continue;
			$tableid = (int)substr((string)$uid, -1, 1);
			// 删除会员表
			$this->db->where('uid', $uid)->delete('member');
			// 删除会员附表
			$this->db->where('uid', $uid)->delete('member_data');
			// 删除会员地址表
			$this->db->where('uid', $uid)->delete('member_address');
			// 删除快捷登陆表
			$this->db->where('uid', $uid)->delete('member_oauth');
			// 删除管理员表
			$this->db->where('uid', $uid)->delete('admin');
			// 删除session值
			$this->db->where('uid', $uid)->delete('member_session');
			// 删除支付记录
			$this->db->where('uid', $uid)->delete('member_paylog_'.$tableid);
			// 删除积分记录
			$this->db->where('uid', $uid)->delete('member_scorelog_'.$tableid);
			// 删除附件
			$this->attachment_model->delete_for_uid($uid);
			// 按站点删除模块数据
			foreach ($this->SITE as $siteid => $v) {
				$cache = $this->dcache->get('module-'.$siteid);
				if ($cache) {
					foreach ($cache as $dir => $mod) {
						$table = $this->site[$siteid]->dbprefix($siteid.'_'.$dir);
						if (!$this->site[$siteid]->where('uid', $uid)->count_all_results($table.'_index')) continue;
						// 删除主表
						$this->site[$siteid]->where('uid', $uid)->delete($table);
						// 删除索引表
						$this->site[$siteid]->where('uid', $uid)->delete($table.'_index');
						// 删除审核表
						$this->site[$siteid]->where('uid', $uid)->delete($table.'_verify');
						// 删除标记表
						$this->site[$siteid]->where('uid', $uid)->delete($table.'_flag');
						// 删除栏目表
						$this->site[$siteid]->where('uid', $uid)->delete($table.'_category_data');
						// 删除附表
						for ($i = 0; $i < 125; $i ++) {
							if (!$this->site[$siteid]->query("SHOW TABLES LIKE '%".$table.'_data_'.$i."%'")->row_array()) break;
							$this->site[$siteid]->where('uid', $uid)->delete($table.'_data_'.$i);
						}
						// 删除栏目附表
						for ($i = 0; $i < 125; $i ++) {
							if (!$this->site[$siteid]->query("SHOW TABLES LIKE '%".$table.'_category_data_'.$i."%'")->row_array()) break;
							$this->site[$siteid]->where('uid', $uid)->delete($table.'_category_data_'.$i);
						}
					}
				}
			}
			// 按应用删除
			if ($app) {
				foreach ($app as $a) {
					$dir = $a['dirname'];
					if (is_file(FCPATH.'app/'.$dir.'/models/'.$dir.'_model.php')) {
						$this->load->add_package_path(FCPATH.'app/'.$dir.'/');
						$this->load->model($dir.'_model', 'app_model');
						$this->app_model->delete_for_uid($uid);
						$this->load->remove_package_path(FCPATH.'app/'.$dir.'/');
					}
				}
			}
		}
		// 删除空间
		$this->load->model('space_model');
		$this->space_model->delete($uids);
		// 删除通知
		$this->db->where('uid', $uid)->delete('member_notice_'.$tableid);
	}
	
	/**
	 * 添加一条通知
	 *
	 * @param	string	$uid
	 * @param	intval	$type 1系统，2互动，3模块，4应用
	 * @param	string	$note
	 * @return	null	
	 */
	public function add_notice($uid, $type, $note) {
	
		if (!$uid || !$note) return NULL;
		
		$uids = is_array($uid) ? $uid : explode(',', $uid);
		foreach ($uids as $uid) {
			$tableid = (int)substr((string)$uid, -1, 1);
			$this->db->insert('member_notice_'.$tableid, array(
				'uid' => $uid,
				'type' => $type,
				'isnew' => 1,
				'content' => $note,
				'inputtime' => SYS_TIME,
			));
			$this->db->replace('member_new_notice', array('uid' =>  $uid));
		}
		
		return NULL;
	}
}