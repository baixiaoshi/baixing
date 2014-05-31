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
	
class Attachment_model extends CI_Model {

	/**
	 * 附件操作模型类
	 */
    public function __construct() {
        parent::__construct();
    }
	
    /**
	 * 会员附件
	 *
	 * @param	intval	$uid	uid
	 * @return	array
	 */
    public function limit($uid, $page, $pagesize, $ext, $table) {
    	
    	$sql = 'SELECT * FROM';
    	$sql.= ' `'.$this->db->dbprefix('attachment').'` AS `a`,`'.$this->db->dbprefix('attachment_'.(int)substr((string)$uid, -1, 1)).'` AS `b`';
    	$sql.= ' WHERE (`a`.`id`=`b`.`id` AND `a`.`siteid`='.SITE_ID.' AND `a`.`uid`='.$uid.')';
    	if ($ext) {
			$data = explode(',', $ext);
			$where = array();
			foreach ($data as $e) {
				$where[] = '`b`.`fileext`="'.$e.'"';
			}
			$sql .= ' AND ('.implode(' OR ', $where).')';
		}
		if ($table) {
			$sql.= ' AND `b`.`related` LIKE "'.$this->db->dbprefix(SITE_ID.'_'.$table).'-%"';
		}
		$sql.= ' ORDER BY `b`.`inputtime` DESC LIMIT '. $pagesize * ($page - 1).','.$pagesize;
		
		$data = $this->db->query($sql)->result_array();
		
		return $this->_get_format_data($data);
    }
    
    /**
	 * Api附件
	 *
	 * @param	intval	$uid	uid
	 * @param	string	$ext	扩展
	 * @param	intval	$total	总数
	 * @param	intval	$page	当前页
	 * @return	array
	 */
    public function limit_page($uid, $ext, $total, $page) {
    	
    	$sql = 'FROM `'.$this->db->dbprefix('attachment').'` AS `a`,`'.$this->db->dbprefix('attachment_'.(int)substr((string)$uid, -1, 1)).'` AS `b` ';
    	$sql.= 'WHERE (`a`.`id`=`b`.`id` AND `a`.`siteid`='.SITE_ID.' AND `a`.`uid`='.$uid.')';
    	
    	if ($ext) {
			$data = explode('|', $ext);
			$where = array();
			foreach ($data as $e) {
				$where[] = '`b`.`fileext`="'.$e.'"';
			}
			$sql .= ' AND ('.implode(' OR ', $where).')';
		}
    	
    	if (!$total) {
			$data = $this->db->query('SELECT count(*) as total '.$sql)->row_array();
			$total = (int)$data['total'];
			if (!$total) return array(array(), 0);
		}
		
		$sql.= ' ORDER BY `b`.`inputtime` DESC LIMIT '. 7 * ($page - 1).',7';
		
		$data = $this->db->query('SELECT * '.$sql)->result_array();
		
		return array($this->_get_format_data($data), $total);
    }
    
	/**
	 * 将未使用附件更新至附件表
	 *
	 * @param	intval	$uid		uid
	 * @param	string	$related	相关表
	 * @param	array	$attach		附件id集合
	 * @return	void
	 */
	public function replace_attach($uid, $related, $attach) {
		
		$info = $this->db
					 ->where('uid', $uid)
					 ->where_in('id', $attach)
					 ->get('attachment_unused')
					 ->result_array();
		if (!$info) return NULL;
		
		foreach ($info as $t) {
			
			// 归档附表id
			$id = $t['id'];
			$tableid = (int)substr((string)$uid, -1, 1);
			
			// 更新主索引表
			$this->db->where('id', $id)->update('attachment', array(
				'tableid' => $tableid,
				'related' => $related
			));
			
			// 更新至附表
			$this->db->replace('attachment_'.$tableid, array(
				'id' => $t['id'],
				'uid' => $t['uid'],
				'remote' => $t['remote'],
				'author' => $t['author'],
				'related' => $related,
				'fileext' => $t['fileext'],
				'filesize' => $t['filesize'],
				'filename' => $t['filename'],
				'inputtime' => $t['inputtime'],
				'attachment' => $t['attachment'],
				'attachinfo' => $t['attachinfo'],
			));
			
			// 删除未使用附件
			$this->db->delete('attachment_unused', 'id='.$id);
		}
		
		return NULL;
	}
	
	/**
	 * 更新时的删除附件
	 *
	 * @param	intval	$uid		uid	用户id
	 * @param	string	$related	当前关联字符串
	 * @param	intval	$id			id	附件id
	 * @return	NULL
	 */
	public function delete_for_handle($uid, $related, $id) {
	
		if (!$id || !$uid) return NULL;
		
		// 查询附件
		$data = $this->db
					 ->where('id', $id)
					 ->get('attachment')
					 ->row_array();
		
		// 判断附件归属权限
		if ($related != $data['related']) return NULL;

		// 删除附件数据
		$this->db->delete('attachment', 'id='.(int)$id);
		
		// 查询附件附表
		$tableid = (int)$data['tableid'];
		$info = $this->db
					 ->select('attachment,remote')
					 ->where('id', (int)$id)
					 ->limit(1)
					 ->get('attachment_'.$tableid)
					 ->row_array();
		if (!$info) return NULL;
		
		// 删除附件文件
		$info['id'] = $id;
		$info['tableid'] = $tableid;
		$this->_delete_attachment($info);
		
		return TRUE;
	}
	
	/**
	 * 删除附件
	 *
	 * @param	intval	$uid		uid	用户id
	 * @param	string	$related	当前关联字符串
	 * @param	intval	$id			id	附件id
	 * @return	NULL
	 */
	public function delete($uid, $related, $id) {
	
		if (!$id || !$uid) return NULL;
		
		// 查询附件
		$data = $this->db
					 ->select('tableid,related')
					 ->where('id', $id)
					 ->get('attachment')
					 ->result_array();
		// 删除附件数据
		$this->db->delete('attachment', 'id='.(int)$id);
		
		// 查询附件附表
		$tableid = (int)$data['tableid'];
		$info = $this->db
					 ->select('attachment,remote')
					 ->where('id', (int)$id)
					 ->limit(1)
					 ->get('attachment_'.$tableid)
					 ->row_array();
		if (!$info) return NULL;
		
		// 删除附件文件
		$info['id'] = $id;
		$info['tableid'] = $tableid;
		$this->_delete_attachment($info);
		
		return TRUE;
	}
	
	// 删除文件
	public function _delete_attachment($info) {
		
		if ($info['remote']) {
			// 删除远程文件
		} else {
			// 删除本地文件
			@unlink(FCPATH.$info['attachment']);
		}
		
		if (isset($info['tableid'])) $this->db->delete('attachment_'.(int)$info['tableid'], 'id='.(int)$info['id']);
		
		// 清空附件缓存
		$this->ci->clear_cache('attachment-'.$info['id']);
	}
	
	/**
	 * 按表删除附件
	 *
	 * @param	string	$related	相关表标识
	 * @param	intval	$is_all		是否全部表附件
	 * @return	NULL
	 */
	public function delete_for_table($related, $is_all = FALSE) {
		
		if (!$related) return NULL;
		
		if ($is_all) {
			$data = $this->db
						 ->select('id,tableid')
						 ->like('related', $related.'-%')
						 ->get('attachment')
						 ->result_array();
		} else {
			$data = $this->db
						 ->select('id,tableid')
						 ->where('related', $related)
						 ->get('attachment')
						 ->result_array();
		}
		
		if (!$data) return NULL;
		
		// 删除附件
		foreach ($data as $t) {
		
            if (!isset($t['id'])) continue;
			
			$this->db->delete('attachment', 'id='.$t['id']);
			
			$info = $this->db
						 ->select('attachment,remote')
						 ->where('id', $t['id'])
						 ->limit(1)
						 ->get('attachment_'.(int)$t['tableid'])
					 ->row_array();
			if (!$info) return NULL;
			
			$info['id'] = $t['id'];
			$info['tableid'] = $t['tableid'];
			$this->_delete_attachment($info);
		}
		
		return 1;
	}
	
	/**
	 * 按站点删除附件
	 *
	 * @param	intval	$siteid	站点id
	 * @return	NULL
	 */
	public function delete_for_site($siteid) {
		
		if (!$siteid) return NULL;
		
		$data = $this->db
					 ->select('id,tableid')
					 ->where('siteid', $siteid)
					 ->get('attachment')
					 ->result_array();
		if (!$data) return NULL;
		
		// 删除附件
		foreach ($data as $t) {
		
			$this->db->delete('attachment', 'id='.$t['id']);
			
			$info = $this->db
						 ->select('attachment,remote')
						 ->where('id', $t['id'])
						 ->limit(1)
						 ->get('attachment_'.(int)$t['tableid'])
					 ->row_array();
			if (!$info) continue;
			
			$info['id'] = $t['id'];
			$info['tableid'] = $t['tableid'];
			$this->_delete_attachment($info);
		}
		
		// 删除未使用
		$data = $this->db
					 ->where('siteid', $siteid)
					 ->get('attachment_unused')
					 ->result_array();
		if (!$data) return NULL;
		
		// 删除附件
		foreach ($data as $t) {
			$this->db->delete('attachment_unused', 'id='.$t['id']);
			$this->_delete_attachment($t);
		}
	}
	
	/**
	 * 按会员删除附件
	 *
	 * @param	intval	$siteid	站点id
	 * @return	NULL
	 */
	public function delete_for_uid($uid) {
		
		if (!$uid) return NULL;
		
		$data = $this->db
					 ->select('id,tableid')
					 ->where('uid', $uid)
					 ->get('attachment')
					 ->result_array();
		if (!$data) return NULL;
		
		// 删除附件
		foreach ($data as $t) {
			
			$this->db->delete('attachment', 'id='.$t['id']);
			
			$info = $this->db
						 ->select('attachment,remote')
						 ->where('id', $t['id'])
						 ->limit(1)
						 ->get('attachment_'.$t['tableid'])
						 ->row_array();
			if (!$info) continue;
			
			$info['id'] = $t['id'];
			$info['tableid'] = $t['tableid'];
			$this->_delete_attachment($info);
		}
		
		// 删除未使用
		$data = $this->db
					 ->where('uid', $uid)
					 ->get('attachment_unused')
					 ->result_array();
		if (!$data) return NULL;
		
		// 删除附件
		foreach ($data as $t) {
			$this->db->delete('attachment_unused', 'id='.$t['id']);
			$this->_delete_attachment($t);
		}
	}
	
	/**
	 * 查询未使用附件
	 *
	 * @param	intval	$uid	uid	用户id
	 * @param	string	$ext	扩展名
	 * @return	NULL
	 */
	public function get_unused($uid, $ext) {
	
		$data = $this->db // 查询未使用的文件
					 ->where('uid', $uid)
					 ->where('siteid', SITE_ID)
					 ->where_in('fileext', explode(',', $ext))
					 ->order_by('inputtime DESC')
					 ->get('attachment_unused')
					 ->result_array();
					 
		return $this->_get_format_data($data);
	}
	
	/**
	 * 下载远程文件
	 *
	 * @param	intval	$uid	uid	用户id
	 * @param	string	$url	文件url
	 * @return	array
	 */
	public function catcher($uid, $url) {
	
		if (!$uid || !$url) return NULL;
		
		// 域名验证
		$domain = require FCPATH.'config/domain.php';
		if (SITE_ATTACH_URL) $domain[SITE_ATTACH_URL] = TRUE;
		$domain['baidu.com'] = TRUE;
		$domain['google.com'] = TRUE;
		foreach ($domain as $uri => $t) {
			if (stripos($url, $uri) !== FALSE) return NULL;
		}
	
		$path = FCPATH.'member/uploadfile/'.date('Ym', SYS_TIME).'/';
		if (!is_dir($path)) dr_mkdirs($path);
		
		$filename = substr(md5(time()), 0, 7).rand(100, 999);
		$data = dr_catcher_data($url);
		if (!$data) return NULL;
		
		$fileext = strtolower(trim(substr(strrchr($url, '.'), 1, 10))); //扩展名
		if (file_put_contents($path.$filename.'.'.$fileext, $data)) {
			$info = array(
				'file_ext' => '.'.$fileext,
				'full_path' => $path.$filename.'.'.$fileext,
				'file_size' => filesize($path.$filename.'.'.$fileext)/1024,
				'client_name' => $url,
			);
			return $this->upload($uid, $info, NULL);
		}
		
		return NULL;
	}
	
	/**
	 * 上传
	 *
	 * @param	intval	$uid	uid	用户id
	 * @param	array	$info	ci 文件上传成功返回数据
	 * @return	array
	 */
	public function upload($uid, $info) {
	
		$_ext = strtolower(substr($info['file_ext'], 1));
		$author = $this->_get_member_name($uid);
		
		// 入库附件
		$this->db->replace('attachment', array(
			'uid' => $uid,
			'author' => $author,
			'siteid' => SITE_ID,
			'tableid' => 0,
			'related' => '',
			'fileext' => $_ext,
			'download' => 0,
			'filesize' => $info['file_size'] * 1024,
		));
		$id = $this->db->insert_id();
		
		if (!$id) { // 入库失败，返回错误且删除附件
			@unlink($info['full_path']);
			return lang('m-145');
		}
		
		$remote = 0;
		$attachment = $file = substr($info['full_path'], strlen(FCPATH));
		
		if (SITE_ATTACH_REMOTE && SITE_ATTACH_EXTS && ($_exts = explode(',', SITE_ATTACH_EXTS)) && in_array($_ext, $_exts)) { // 远程附件模式
			set_time_limit(0);
			$this->load->library('ftp');
			if ($this->ftp->connect(array(
				'port' => SITE_ATTACH_PORT,
				'debug' => FALSE,
				'passive' => SITE_ATTACH_PASV,
				'hostname' => SITE_ATTACH_HOST,
				'username' => SITE_ATTACH_USERNAME,
				'password' => SITE_ATTACH_PASSWORD,
			))) { // 连接ftp成功
				$dir = SITE_ATTACH_PATH.'/'.basename(dirname($info['full_path'])).'/';
				$this->ftp->mkdir($dir);
				if ($this->ftp->upload($info['full_path'], $dir.basename($info['full_path']), SITE_ATTACH_MODE, 0775)) {
					$remote = 1;
					$attachment = str_replace('member/uploadfile/', '', $attachment);
					$file = SITE_ATTACH_URL.'/'.$attachment;
					unlink($info['full_path']);
				}
				$this->ftp->close();
			}
		}
		
		$pos = strrpos($info['client_name'], '.');
		$filename = strpos($info['client_name'], 'http://') === 0 ? trim(strrchr($info['client_name'], '/'), '/') : $info['client_name'];
		$filename = $pos ? substr($filename, 0, $pos) : $filename;
		
		// 增加至未使用附件表
		$this->db->replace('attachment_unused', array(
			'id' => $id,
			'uid' => $uid,
			'author' => $author,
			'siteid' => SITE_ID,
			'remote' => $remote,
			'fileext' => $_ext,
			'filename' => $filename,
			'filesize' => $info['file_size'] * 1024,
			'inputtime' => SYS_TIME,
			'attachment' => $attachment,
			'attachinfo' => '',
		));
		
		return array($id, $file, $_ext);
	}
	
	// 会员名称
	private function _get_member_name($uid) {
	
		$data = $this->db
					 ->where('uid', $uid)
					 ->select('username')
					 ->limit(1)
					 ->get('member')
					 ->row_array();
					 
		return isset($data['username']) ? $data['username'] : '';
	}
	
	// 格式化输出数据
	private function _get_format_data($data) {
		
		if (!$data) return NULL;
		
		foreach ($data as $i => $t) {
			$data[$i]['ext'] = $t['fileext'];
			$data[$i]['attachment'] = $t['remote'] ? SITE_ATTACH_URL.'/'.$t['attachment'] : dr_file($t['attachment']);
			if (in_array($t['fileext'], array('jpg', 'gif', 'png'))) {
				$data[$i]['show'] = $data[$i]['attachment'];
				$data[$i]['icon'] = SITE_URL.'dayrui/statics/images/ext/jpg.gif';
			} else {
				$data[$i]['show'] = is_file(FCPATH.'dayrui/statics/images/ext/'.$t['fileext'].'.png') ? SITE_URL.'dayrui/statics/images/ext/'.$t['fileext'].'.png' : SITE_URL.'dayrui/statics/images/ext/blank.png';
				$data[$i]['icon'] = is_file(FCPATH.'dayrui/statics/images/ext/'.$t['fileext'].'.gif') ? SITE_URL.'dayrui/statics/images/ext/'.$t['fileext'].'.gif' : SITE_URL.'dayrui/statics/images/ext/blank.gif';
			}
			$data[$i]['size'] = dr_format_file_size($t['filesize']);
		}
		
		return $data;
	}
}