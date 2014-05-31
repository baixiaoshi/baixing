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
	
class Pm_model extends CI_Model{
	
	/**
     * 短消息模型类
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 数据显示
	 *
	 * @param	intval	$uid	uid
	 * @param	intval	$page	页数
	 * @return	array	
	 */
	public function limit_page($uid, $page) {
        
		$members = $touidarr = $tousernamearr = array();
		
		$data = $this->db
					 ->query("SELECT * FROM ".$this->db->dbprefix('pm_members')." m LEFT JOIN ".$this->db->dbprefix('pm_lists')." t ON t.plid=m.plid WHERE m.uid=$uid ORDER BY m.lastdateline DESC LIMIT ".SITE_ADMIN_PAGESIZE * ($page - 1).", ".SITE_ADMIN_PAGESIZE)
					 ->result_array();
		foreach ($data as $member) {
			if ($member['pmtype'] == 1) {
				$users = explode('_', $member['min_max']);
				$member['touid'] = $users[0] == $uid ? $users[1] : $users[0];
			} else {
				$member['touid'] = 0;
			}
			$touidarr[$member['touid']] = $member['touid'];
			$members[] = $member;
		}

		$this->db->query("DELETE FROM ".$this->db->dbprefix('newpm')." WHERE uid=$uid");

		$array = array();
		if ($members) {
			$today = SYS_TIME - SYS_TIME % 86400;
			foreach ($members as $key => $data) {
				$daterange = 5;
				$data['founddateline'] = $data['dateline'];
				$data['dateline'] = $data['lastdateline'];
				$data['pmid'] = $data['plid'];
				$lastmessage = dr_string2array($data['lastmessage']);
				if ($lastmessage['firstauthorid']) {
					$data['firstauthorid'] = $lastmessage['firstauthorid'];
					$data['firstauthor'] = $lastmessage['firstauthor'];
					$data['firstsummary'] = $lastmessage['firstsummary'];
				}
				if ($lastmessage['lastauthorid']) {
					$data['lastauthorid'] = $lastmessage['lastauthorid'];
					$data['lastauthor'] = $lastmessage['lastauthor'];
					$data['lastsummary'] = $lastmessage['lastsummary'];
				}
				$data['msgfromid'] = $lastmessage['lastauthorid'];
				$data['msgfrom'] = $lastmessage['lastauthor'];
				$data['message'] = $lastmessage['lastsummary'];

				$data['new'] = $data['isnew'];

				$data['msgtoid'] = $data['touid'];
				if($data['lastdateline'] >= $today) {
					$daterange = 1;
				} elseif($data['lastdateline'] >= $today - 86400) {
					$daterange = 2;
				} elseif($data['lastdateline'] >= $today - 172800) {
					$daterange = 3;
				} elseif($data['lastdateline'] >= $today - 604800) {
					$daterange = 4;
				}
				$data['daterange'] = $daterange;

				$data['tousername'] = get_member_value($data['touid']);
				unset($data['min_max']);
				$array[] = $data;
			}
		}
		
		return $array;
		
	}
	
	/**
	 * 数据显示
	 *
	 * @param	intval	$touid	touid
	 * @param	intval	$page	页数
	 * @return	array
	 */
	public function read_limit_page($touid, $page) {
	
		$uid = $this->uid;
        $min_max = $this->_relationship($touid, $uid);
		$plist = $this->db
					  ->where('min_max', $min_max)
					  ->get('pm_lists')
					  ->row_array();
		if (!$plist) return NULL;
		
		$plid =  $plist['plid'];
		$addsql = array();
		$addsql[] = "p.plid=$plid";
		if ($plist['authorid'] == $uid) {
			$addsql[] = 'p.delstatus IN (0,2)';
		} else {
			$addsql[] = 'p.delstatus IN (0,1)';
		}
		if ($addsql) {
			$addsql = implode(' AND ', $addsql);
		} else {
			$addsql = '';
		}
		$users = explode('_', $plist['min_max']);
		$touid = $users[0] == $uid ? $users[1] : $users[0];
		
		$sql = "SELECT t.*, p.*, t.authorid as founderuid, t.dateline as founddateline FROM ".$this->db->dbprefix($this->_pm_tablename($plid))." p LEFT JOIN ".$this->db->dbprefix('pm_lists')." t ON p.plid=t.plid WHERE $addsql ORDER BY p.dateline DESC LIMIT ".SITE_ADMIN_PAGESIZE * ($page - 1).", ".SITE_ADMIN_PAGESIZE;
		$list = $this->db->query($sql)->result_array();
		if (!$list) return NULL;
		
		$this->db->query("UPDATE ".$this->db->dbprefix('pm_members')." SET isnew=0 WHERE plid=$plid AND uid=$uid AND isnew=1");
		
		return array($touid, array_reverse($list));
	}
	
	/**
	 * 标注为已读
	 *
	 * @param	intval	$uid
	 * @param	array	$ids
	 * @return	void
	 */
	public function set_read($uid, $ids) {
	
		if (!$ids) return NULL;
		
		foreach ($ids as $plid) {
			$this->db
				 ->query("UPDATE ".$this->db->dbprefix('pm_members')." SET isnew=0 WHERE plid=$plid AND uid=$uid AND isnew=1");
		}
	}
	
	/**
	 * 批量删除会话
	 *
	 * @param	intval	$uid
	 * @param	array	$ids
	 * @param	intval	$isuser
	 * @return	void
	 */
	public function deletes($uid, $plids, $isuser = 0) {
		if ($plids) {
			foreach($plids as $plid) {
				$this->delete_by_plid($uid, $plid, $isuser);
			}
		}
		return 1;
	}
	
	/**
	 * 删除整个会话
	 *
	 * @param	intval	$uid
	 * @param	array	$ids
	 * @param	intval	$isuser
	 * @return	void
	 */
	public function delete_by_plid($uid, $plid, $isuser = 0) {
		
		if (!$uid || !$plid) return NULL;

		if ($isuser) {
			$relationship = $this->_relationship($uid, $plid);
			$sql = "SELECT * FROM ".$this->db->dbprefix('pm_lists')." WHERE min_max='$relationship'";
		} else {
			$sql = "SELECT * FROM ".$this->db->dbprefix('pm_lists')." WHERE plid='$plid'";
		}

		$list = $this->db->query($sql)->row_array();
		if ($list) {
			$user = explode('_', $list['min_max']);
			if (!in_array($uid, $user)) return NULL;
		} else {
			return NULL;
		}
		
		$msg_table = $this->db->dbprefix($this->_pm_tablename($list['plid']));
		if ($uid == $list['authorid']) {
			$this->db->query("DELETE FROM ".$msg_table." WHERE plid='$list[plid]' AND delstatus=2");
			$this->db->query("UPDATE ".$msg_table." SET delstatus=1 WHERE plid='$list[plid]' AND delstatus=0");
		} else {
			$this->db->query("DELETE FROM ".$msg_table." WHERE plid='$list[plid]' AND delstatus=1");
			$this->db->query("UPDATE ".$msg_table." SET delstatus=2 WHERE plid='$list[plid]' AND delstatus=0");
		}
		
		$count = $this->db->where('plid', $list['plid'])->count_all_results($msg_table);
		if(!$count) {
			$this->db->query("DELETE FROM ".$this->db->dbprefix('pm_lists')." WHERE plid='$list[plid]'");
			$this->db->query("DELETE FROM ".$this->db->dbprefix('pm_members')." WHERE plid='$list[plid]'");
			$this->db->query("DELETE FROM ".$this->db->dbprefix('pm_indexes')." WHERE plid='$list[plid]'");
		} else {
			$this->db->query("DELETE FROM ".$this->db->dbprefix('pm_members')." WHERE plid='$list[plid]' AND uid=$uid");
		}
		
		return 1;
	}
	
	/**
	 * 发送短消息
	 *
	 * @param	intval	$fromuid		发送者uid
	 * @param	string	$fromusername	发送者username
	 * @param	array	$data			发送数据
	 * @return	string
	 */
	public function send($fromuid, $fromusername, $data) {
		
		if (!$fromuid || !$fromusername || !$data['username'] || !$data['message']) {
			return lang('m-062');
		}
		
		$subject = htmlspecialchars($data['subject']);
		$message = htmlspecialchars($data['message']);
		
		// 查询会员数据
		$member = array();
		$username = explode(',', $data['username']);
		foreach ($username as $name) {
			$uid = get_member_id($name);
			if ($uid) $member[$uid] = $name; 
		}
		if (!$member) return lang('m-066');
		
		// 建立对应关系
		$relationship = $existplid = $pm_member_insertsql = array();
		
		// 多个对象是，关系数组为多位数组
		foreach ($member as $key => $value) {
			if ($fromuid == $key) return lang('m-067');
			//$key是接收者id
			$relationship[$key] = $this->_relationship($fromuid, $key);
		}
		
		// 处理标题
		$subject = !$subject ? dr_strcut(dr_clearhtml($message), 80) : $subject;
		// 作为最后一条消息内容
		$lastsummary = dr_strcut(dr_clearhtml($message), 150);
		
		// 短消息会话表中按“对应关系”来查询
		$list = $this->db->select('plid, min_max')->where_in('min_max', $relationship)->get('pm_lists')->result_array();
		if ($list) {
			foreach ($list as $t) {
				$existplid[$t['min_max']] = $t['plid']; // 获取到该关系的plid
			}
		}
		
		// 最后一条消息的数据组装
		$lastmessage = array('lastauthorid' => $fromuid, 'lastauthor' => $fromusername, 'lastsummary' => $lastsummary);
		$lastmessage = dr_array2string($lastmessage);
		 
		// 按照对应关系来插入消息表中，当对应关系在库中不存在时，重新创建一个对应关系表
		foreach($relationship as $key => $value) {
			if(!isset($existplid[$value])) {
				// 插入新的列表id
				$this->db->insert('pm_lists', array(
					'authorid' => $fromuid,
					'author' => $fromusername,
					'pmtype' => 1,
					'subject' => $subject,
					'members' => 2,
					'min_max' => $value,
					'dateline' => SYS_TIME,
					'lastmessage' => $lastmessage,
				));
				// 获取会话列表ID
				$plid = $this->db->insert_id();
				// 将列表id插入到消息索引表中
				$this->db->insert('pm_indexes', array(
					'plid' => $plid,
				));
				// 得到一个短消息ID
				$pmid = $this->db->insert_id();
				// 以回话列表id作为散列存储
				$this->db->insert($this->_pm_tablename($plid), array(
					'pmid' => $pmid,
					'plid' => $plid,
					'authorid' => $fromuid,
					'message' => $message,
					'dateline' => SYS_TIME,
					'delstatus' => 0
				));
				// 存储到“接受者的”消息状态表
				$this->db->insert('pm_members', array(
					'plid' => $plid,
					'uid' => $key,
					'isnew' => 1,
					'pmnum' => 1,
					'lastupdate' => 0,
					'lastdateline' => SYS_TIME,
				));
				// 存储到“发送者的”消息状态表
				$this->db->insert('pm_members', array(
					'plid' => $plid,
					'uid' => $fromuid,
					'isnew' => 0,
					'pmnum' => 1,
					'lastupdate' => SYS_TIME,
					'lastdateline' => SYS_TIME,
				));
			} else {
				// 获取上面查询的会话列表ID
				$plid = $existplid[$value];
				// 将列表id插入到消息索引表中
				$this->db->insert('pm_indexes', array(
					'plid' => $plid,
				));
				// 得到一个短消息ID
				$pmid = $this->db->insert_id();
				// 以回话列表id作为散列存储
				$this->db->insert($this->_pm_tablename($plid), array(
					'pmid' => $pmid,
					'plid' => $plid,
					'authorid' => $fromuid,
					'message' => $message,
					'dateline' => SYS_TIME,
					'delstatus' => 0
				));
				// 存储到“接受者的”消息状态表
				if ($this->db->where('plid', $plid)->where('uid', $key)->count_all_results('pm_members')) {
					// 如果已经存在了就更新状态表
					$this->db
						 ->where('plid', $plid)
						 ->where('uid', $key)
						 ->set('isnew', 1)
						 ->set('lastdateline', SYS_TIME)
						 ->set('pmnum', 'pmnum+1', FALSE)
						 ->update('pm_members');
				} else {
					$this->db->insert('pm_members', array(
						'plid' => $plid,
						'uid' => $key,
						'isnew' => 1,
						'pmnum' => 1,
						'lastupdate' => 0,
						'lastdateline' => SYS_TIME
					));
				}
				// 存储到“发送者的”消息状态表
				if ($this->db->where('plid', $plid)->where('uid', $fromuid)->count_all_results('pm_members')) {
					// 如果已经存在了就更新状态表
					$this->db
						 ->where('plid', $plid)
						 ->where('uid', $fromuid)
						 ->set('isnew', 0)
						 ->set('lastdateline', SYS_TIME)
						 ->set('pmnum', 'pmnum+1', FALSE)
						 ->update('pm_members');
				} else {
					$this->db->insert('pm_members', array(
						'plid' => $plid,
						'uid' => $fromuid,
						'isnew' => 0,
						'pmnum' => 1,
						'lastupdate' => SYS_TIME,
						'lastdateline' => SYS_TIME
					));
				}
				// 更新最后一条消息信息
				$this->db->where('plid', $plid)->set('lastmessage', $lastmessage)->update('pm_lists');
			}
		}
		
		// 插入用户的新短消息表
		foreach($member as $key => $value) {
			$this->db->replace('newpm', array(
				'uid' => $key
			));
		}
		
		return NULL;
		
	}
	
	/**
	 * 建立对应关系
	 *
	 * @param	int		$uid	发送者id
	 * @param	int		$_uid	接收者id
	 * @return	string	对应关系字符
	 */
	private function _relationship($uid, $_uid) {
		if ($uid < $_uid) {
			return $uid.'_'.$_uid;
		} elseif ($uid > $_uid) {
			return $_uid.'_'.$uid;
		} else {
			return '';
		}
	}
	
	/**
	 * 根据消息会话列表id取存储表id
	 *
	 * @param	int		$lid	发送者id
	 * @return	string	存储表
	 */
	private function _pm_tablename($lid) {
		return 'pm_messages_'.substr((string)$lid, -1, 1);
	}
    
}