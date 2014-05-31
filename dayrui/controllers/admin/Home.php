<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.1.0
 * @author		Chunjie <chunjie@dayrui.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Home extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 重置
     */
    public function home() {
		$this->index();
	}
	
    /**
     * 首页
     */
    public function index() {
	
		$top = array();
		$smenu = $this->_get_menu();
		$topid = $id = 0;
		$mymenu = TRUE;
		$sitemap = $string = '';
		
		foreach ($smenu as $t) {
			$select	= 0;
			$selurl	= '';
			$_first	= FALSE;
			$string.= '<div class="d_menu" id="D_M_'.$topid.'"'.($topid == 0 ? '' : 'style="display:none"').'>';
			$sitemap.= '<div class="d_top"><div class="d_name">'.$t['top']['name'].'</div><ul>';
			foreach ($t['data'] as $left) {
				$string.= '<div class="subnav">';
				$string.= '<div class="subnav-title">';
				$string.= '<a href="#" class="toggle-subnav"><i class="icon-angle-down"></i><span>'.$left['left']['name'].'</span></a>';
				$string.= '</div>';
				$string.= '<ul class="subnav-menu">';
				foreach ($left['data'] as $link) {
					$id ++;
					if ($_first == FALSE) {
						$class	= 'dropdown';
						$select	= $id;
						$selurl	= $link['url'];
						$_first	= TRUE;
					} else {
						$class	= '';
					}
					$string.= '<li id="_MP_'.$id.'" class="'.$class.'"><a href="javascript:_MP(\''.$id.'\', \''.$link['url'].'\');" >'.$link['name'].'</a></li>';
					if ($mymenu == TRUE && $this->admin['usermenu']) {
						foreach ($this->admin['usermenu'] as $my) {
							$id ++;
							$string.= '<li id="_MP_'.$id.'"><a href="javascript:_MP(\''.$id.'\', \''.$my['url'].'\');">'.$my['name'].'</a></li>';
						}
						$mymenu = FALSE;
					}
					$sitemap.= '<li><a href="javascript:_MAP(\''.$topid.'\', \''.$id.'\', \''.$link['url'].'\');" >'.$link['name'].'</a></li>';
				}
				$string.= '</ul>';
				$string.= '</div>';
			}
			$string.= '</div>';
			$sitemap.= '</ul></div>';
			$sitemap = $topid == 0 ? '' : $sitemap;
			$t['top']['selurl'] = $selurl;
			$t['top']['select'] = $select;
			$top[$topid] = $t['top'];
			
			$topid ++;
		}
		
		$mysite = array();
		foreach ($this->SITE as $sid => $t) {
			if ($this->admin['adminid'] == 1 || ($this->admin['role']['site'] && in_array($sid, $this->admin['role']['site']))) {
				$mysite[$sid] = $t['SITE_NAME'];
			}
		}
		
		$this->template->assign(array(
			'top' => $top,
			'left' => $string,
			'mysite' => $mysite,
			'sitemap' => $sitemap,
			'install' => $this->dcache->get('install'),
		));
        $this->template->display('index.html');
    }
	
	/**
     * 菜单缓存格式化
     */
	private function _get_menu() {
		$menu = $this->dcache->get('menu');
		$smenu = array();
		if (!$menu) {
			$this->load->model('menu_model');
			$menu = $this->menu_model->cache();
		}
		foreach ($menu as $t) {
			if (is_array($t['left'])) {
				$left = array();
				if ($t['mark'] && strpos($t['mark'], 'module-') === 0) {
					list($a, $dir) = explode('-', $t['mark']);
					if (!$this->get_cache('module-'.SITE_ID.'-'.$dir)) continue;
				}
				foreach ($t['left'] as $m) {
					$link = array();
					if (is_array($m['link'])) {
						foreach ($m['link'] as $n) {
							if ($n['mark'] && strpos($n['mark'], 'app-') === 0) {
								// 应用链接权限判断
								list($a, $dir) = explode('-', $n['mark']);
								$app = $this->get_cache('app-'.$dir);
								if ($this->admin['adminid'] > 1 && !$app['setting']['admin'][$this->admin['adminid']]) continue;
								$n['url'] = $this->duri->uri2url($n['uri']);
								$link[] = $n;
							} elseif (($n['mark'] && strpos($n['mark'], 'space-') === 0) || in_array($n['id'], array(74, 81, 73))) {
								// 空间开启权限判断
								if (!MEMBER_OPEN_SPACE) continue;
								$n['url'] = $this->duri->uri2url($n['uri']);
								$link[] = $n;
							} elseif (!$n['uri'] && $n['url']) {
								$link[] = $n;
							} elseif ($this->is_auth($n['uri'])) {
								$n['url'] = $this->duri->uri2url($n['uri']);
								$link[] = $n;
							}
						}
					}
					if ($link) $left[] = array('left' => $m, 'data' => $link);
				}
				if ($left) $smenu[] = array('top' => $t, 'data' => $left);
			}
		}
		return $smenu;
	}
	
	/**
     * 后台首页
     */
    public function main() {
		
		$store = array();
		$local = @array_diff(dr_dir_map(FCPATH, 1), array('app', 'cache', 'config', 'dayrui', 'member', 'space', 'player')); // 搜索本地模块
		
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.$dir.'/config/module.php')) {
					$config = require FCPATH.$dir.'/config/module.php';
					if ($config['key']) {
						if (isset($store[$config['key']])) {
							if (version_compare($config['version'], $store[$config['key']], '<')) $store[$config['key']] = $config['version'];
						} else {
							$store[$config['key']] = $config['version'];
						}
					}
				}
			}
		}
		
		$local = dr_dir_map(FCPATH.'app/', 1); // 搜索本地应用
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.'app/'.$dir.'/config/app.php')) {
					$config = require FCPATH.'app/'.$dir.'/config/app.php';
					if ($config['key']) {
						$store[$config['key']] = $config['version'];
					}
				}
			}
		}
		
		$this->template->assign(array(
			'store' => dr_base64_encode(dr_array2string($store)),
			'sqlversion' => $this->db->version(),
			'sitedomain' => $this->_get_site_domain(),
		));
		$this->template->display('main.html');
	}
	
	/**
     * 更新全站缓存
     */
    public function cache() {
	
		$url = array(
			array(
				'url' => dr_url('site/cache', array('admin' => 1)),
				'name' => lang('006'),
			),
			array(
				'url' => dr_url('role/cache', array('admin' => 1)),
				'name' => lang('002'),
			),
			array(
				'url' => dr_url('menu/cache', array('admin' => 1)),
				'name' => lang('003'),
			),
			array(
				'url' => dr_url('mail/cache', array('admin' => 1)),
				'name' => lang('191'),
			),
			array(
				'url' => dr_url('verify/cache', array('admin' => 1)),
				'name' => lang('005'),
			),
			array(
				'url' => dr_url('urlrule/cache', array('admin' => 1)),
				'name' => lang('129'),
			),
			array(
				'url' => dr_url('member/menu/cache', array('admin' => 1)),
				'name' => lang('235'),
			),
			array(
				'url' => dr_url('member/model/cache', array('admin' => 1)),
				'name' => lang('241'),
			),
			array(
				'url' => dr_url('member/setting/cache', array('admin' => 1)),
				'name' => lang('010'),
			),
		);
		
		$i = 1;
		$count = count($this->SITE);
		foreach ($this->SITE as $sid => $t) { // 分站点缓存
			$url[] = array(
				'url' => dr_url('form/cache', array('site' => $sid, 'admin' => 1)),
				'name' => lang('248')."($i/$count)"
			);
			$url[] = array(
				'url' => dr_url('block/cache', array('site' => $sid, 'admin' => 1)),
				'name' => lang('204')."($i/$count)"
			);
			$url[] = array(
				'url' => dr_url('page/cache', array('site' => $sid, 'admin' => 1)),
				'name' => lang('164')."($i/$count)"
			);
			$url[] = array(
				'url' => dr_url('linkage/cache', array('site' => $sid, 'admin' => 1)),
				'name' => lang('189')."($i/$count)"
			);
			$url[] = array(
				'url' => dr_url('navigator/cache', array('site' => $sid, 'admin' => 1)),
				'name' => lang('007')."($i/$count)"
			);
			$i ++;
		}
		
		// 模块缓存
		$module = $this->db
					   ->select('disabled,dirname')
					   ->get('module')
					   ->result_array();
		if ($module) {
			foreach ($module as $mod) {
				if ($mod['disabled'] == 0) {
					$url[] = array(
						'url' => dr_url('module/cache', array('dir' => $mod['dirname'], 'admin' => 1)),
						'name' => dr_lang('009', $mod['dirname'])
					);
				}
			}
		}
		
		// 应用缓存
		$app = $this->db
				    ->select('disabled,dirname')
				    ->get('application')
				    ->result_array();
		if ($app) {
			foreach ($app as $a) {
				if ($a['disabled'] == 0) {
					$url[] = array(
						'url' => dr_url($a['dirname'].'/home/cache', array('admin' => 1)),
						'name' => dr_lang('251', $a['dirname'])
					);
				}
			}
		}
		
		$this->load->helper('file');
		if (!IS_AJAX) delete_files(FCPATH.'cache/data/');
		$this->dcache->set('version', DR_VERSION); // 生成版本标识符
		
		$this->template->assign(array(
			'list' => $url,
		));
		$this->template->display('cache.html');
		
    }
	
	// 清除缓存数据
	public function clear() {
		if (IS_AJAX || $this->input->get('todo')) {
			$this->_clear_data();
			if (!IS_AJAX) $this->admin_msg(lang('html-572'), '', 1);
		} else {
			$this->admin_msg('Clear ... ', dr_url('home/clear', array('todo' => 1)), 2);
		}
	}
	
	// 清除缓存数据
	private function _clear_data() {
	
		// 删除全部缓存文件
		$this->load->helper('file');
		delete_files(FCPATH.'cache/sql/');
		delete_files(FCPATH.'cache/file/');
		delete_files(FCPATH.'cache/attach/');
		delete_files(FCPATH.'cache/templates/');
		
		// 删除memcache缓存
		if (SYS_MEMCACHE && $this->cache->memcached->is_supported()) $this->cache->memcached->clean();
		
		// 模块缓存
		$module = $this->db
					   ->select('disabled,dirname')
					   ->get('module')
					   ->result_array();
		if ($module) {
			foreach ($module as $mod) {
				$site = dr_string2array($mod['site']);
				if ($site[SITE_ID]) $this->site[SITE_ID]->where('inputtime<>', 0)->delete(SITE_ID.'_'.$mod['dirname'].'_search');
			}
		}
		
	}
}