<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_File extends M_Controller {
	
	public $_dir;
	public $path;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->_dir = array('css', 'images', 'watermark', 'avatar', 'js', 'OAuth', 'admin');
    }
	
	/**
     * 文件管理
     */
	public function index() {
	
		$this->load->helper('directory');
		$dir = trim(str_replace('.', '', $this->input->get('dir')), '/');
		$path = $dir ? $this->path.$dir.'/' : $this->path;
		$data = directory_map($path, 1);
		$list = array();
		
		if ($data) {
			foreach ($data as $t) {
				$ext = strrchr($t, '.');
				if ($ext && !in_array($ext, array('.html', '.js', '.css'))) continue;
				if (!$dir && in_array(basename($t), $this->_dir)) continue;
				$list[] = $t;
			}
		}
		
		$this->template->assign(array(
			'dir' => $dir,
			'path' => $path,
			'list' => $list,
			'parent' => dirname($dir),
		));
		$this->template->display('file_index.html');
	}
	
	/**
     * 创建文件或者目录
     */
	public function add() {
	
		$dir = trim(str_replace('.', '', $this->input->get('dir')), '/');
		$path = $dir ? $this->path.$dir.'/' : $this->path;
		$error = $file = '';
		if (!is_dir($path)) exit('<p style="padding:10px 20px 20px 20px">'.lang('221').'</p>');
		
		if (IS_POST) {
		
			$file = trim(str_replace(array('/', '\\'), '', $this->input->post('file')), '/');
			
			if (file_exists($path.$file)) exit(dr_json(0, lang('227'), 'file'));
			
			$ext = strrchr($file, '.');
			
			if ($ext) {
				// 创建文件
				if (in_array($ext, array('.html', '.js', '.css'))) {
					if (file_put_contents($path.$file, '') === FALSE) {
						exit(dr_json(0, lang('226'), 'file'));
					} else {
						exit(dr_json(1, lang('224'), 'file'));
					}
				} else {
					exit(dr_json(0, lang('223'), 'file'));
				}
			} else {
				// 创建目录
				if (mkdir($path.$file)) {
					exit(dr_json(1, lang('222'), 'file'));
				} else {
					exit(dr_json(0, lang('225'), 'file'));
				}
			}
		}
		
		$this->template->display('file_add.html');
	}
	
	/**
     * 修改文件内容
     */
	public function edit() {
	
		$file = str_replace(array('../', '\\'), array('', '/'), $this->input->get('file'));
		if (!is_file($this->path.$file)) $this->admin_msg(lang('229'));
		
		if (IS_POST) {
			$code = $this->input->post('code');
			file_put_contents($this->path.$file, $code);
		}
		
		$furi = $this->template->get_value('furi');
		$this->template->assign(array(
			'path' => $this->path.$file,
			'back' => dr_url($furi.'index', array('dir'=> dirname($file))),
			'body' => file_get_contents($this->path.$file),
		));
		$this->template->display('file_edit.html');
		
	}
	
	/**
     * 删除
     */
	public function del() {
		
		$file = trim(str_replace(array('../', '\\'), array('', '/'), $this->input->get('file')), '/');
		if (!$file) exit(dr_json(0, lang('228')));
		
		if (is_dir($this->path.$file)) {
			$this->load->helper('file');
			delete_files($this->path.$file, TRUE);
			@rmdir($this->path.$file);
			exit(dr_json(1, lang('000')));
		} else {
			@unlink($this->path.$file);
			exit(dr_json(1, lang('000')));
		}
	}
	
	/**
     * 标签向导
     */
	public function tag() {
		
		if (SYS_DEBUG) $this->output->enable_profiler(FALSE);
		
		if (IS_AJAX) {
			echo '<div style="border: 1px solid #DCE3ED;padding:10px;width:650px;">';
			$data = $this->input->post('data');
			switch ($this->input->post('action')) {
				case 'navigator':
					echo '<li>{list action=navigator type='.$data['type'].((int)$data['num'] ? ' num='.(int)$data['num'] : '').' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix('1_navigator')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'page_list':
					echo '<li>{list action=page module='.$data['module'].((int)$data['num'] ? ' num='.(int)$data['num'] : '').' pid='.(int)$data['pid'].' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix(SITE_ID.'_page')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'page_show':
					echo '<div style="max-height:400px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="400">调用方式<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix(SITE_ID.'_page')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$ci->get_cache(\'page-'.SITE_ID.'\', \'data\', \''.$data['module'].'\', '.(int)$data['id'].', \''.$t['name'].'\')}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					echo '</div>';
					break;
				case 'linkage_list':
					echo '<li>{list action=linkage code='.$data['code'].((int)$data['num'] ? ' num='.(int)$data['num'] : '').' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '<li>知道id显示菜单名字：{dr_linkagepos(\''.$data['code'].'\', ID, \'\')}</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix('linkage_data_1')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'form_list':
					echo '<li>{list action=form form='.$data['form'].((int)$data['num'] ? ' num='.(int)$data['num'] : '').' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}</li>';
					echo '<li>字段名称在“系统->系统维护->数据备份”中单击以站点id_form_表单表名称的表就知道了</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '<li>{$sql}返回这段查询的SQL代码，调试开发期间很有用处</li>';
					echo '</div>';
					break;
				case 'module_list':
					echo '<li>{list action=module module='.APP_DIR.((int)$data['num'] ? ' num='.(int)$data['num'] : '').' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '<li>{$sql}返回这段查询的SQL代码，调试开发期间很有用处</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix(SITE_ID.'_'.APP_DIR)]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'category_list':
					echo '<li>{list action=category module='.APP_DIR.((int)$data['num'] ? ' num='.(int)$data['num'] : '').' pid='.(int)$data['pid'].' return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_category')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'category_show':
					echo '<div style="max-height:400px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="400">调用方式<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_category')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$ci->get_cache(\'module-'.SITE_ID.'-'.APP_DIR.'\', \'category\', '.(int)$data['id'].', \''.$t['name'].'\')}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					echo '</div>';
					break;
				case 'member_group':
					echo '<li>{list action=cache name=member.group return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix('member_group')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				case 'member_level':
					echo '<li>{list action=cache name=member.group.'.(int)$data['gid'].'.level return='.$data['return'].'}</li>';
					echo '<li>当前循环序号：{$key}（从0开始）</li>';
					echo '<li>等级星星调用：{dr_show_stars($'.$data['return'].'.stars)}</li>';
					echo '<li>字段调用方式：{$'.$data['return'].'.字段名称}（字段名称下面介绍）</li>';
					echo '<li>{/list}</li>';
					echo '<li>{$error}返回错误提示代码</li>';
					echo '</div>';
					echo '<div style="border: 1px solid #DCE3ED;margin-top:10px;padding:10px;width:650px;max-height:300px;overflow:auto;">';
					echo '<table width="100%"><tbody>';
					echo '<tr><th width="200">字段<br></th><th align="left">说明</th></tr>';
					$cache = $this->dcache->get('table');
					$table = $cache[$this->db->dbprefix('member_level')]['field'];
					if ($table) {
						foreach ($table as $t) {
							echo '<tr><td>{$'.$data['return'].'.'.$t['name'].'}<br></td><td align="left">'.$t['note'].'</td></tr>';
						}
					}
					echo '</tbody></table>';
					break;
				default:
					echo '未知操作';
					break;
			}
			echo '</div>';
			
		} else {
			
			switch (APP_DIR) {
				case '':
					$nav = explode(',', SITE_NAVIGATOR);
					$navigator = array();
					foreach ($nav as $i => $name) {
						if ($name) {
							$navigator[$i] = $name;
						}
					}
					$this->template->assign(array(
						'form' => $this->db
									   ->get(SITE_ID.'_form')
									   ->result_array(),
						'linkage' => $this->db
										  ->order_by('id ASC')
										  ->get('linkage')
										  ->result_array(),
						'navigator' => $navigator,
					));
					$tpl = '';
					break;
				case 'member':
					$tpl = '_member';
					break;
				default:
					$tpl = '_module';
					break;
			}
			
			$this->template->assign(array(
				'return_var' => 't',
				'navigator' => $navigator,
			));
			$this->template->display('file_tag'.$tpl.'.html');
		}
	}
}