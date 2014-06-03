<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
/**
 * FineCMS模板标签解析
 */
	
class Template {

    public $ci; // ci控制器对象
    public $cron; //执行计划任务代码
	public $mobile; // 是否是手机访问
	
    private $_dir; // 模板目录
    private $_cache; // 模板缓存目录
	
    private $_root;	// 默认前端项目模板目录
    private $_mroot; // 默认会员项目模板目录
    private $_aroot; // 默认后台项目模板目录
	private $_tpl_dir; // 模板目录名称
	
    private $_options; // 模板变量
    private $_filename; // 主模板名称

    /**
     * 构造函数
     */
    public function __construct() {
		
		$this->_root = VIEWPATH.'{templates}/'.SITE_TEMPLATE.'/'; // 默认主项目模板目录
		$this->_cache = FCPATH.'cache/templates/'; // 模板缓存目录
		$this->_mroot = FCPATH.'member/{templates}/member/'.MEMBER_TEMPLATE.'/'; // 默认会员项目模板目录
		$this->_aroot = VIEWPATH.'{templates}/admin/'; // 默认后台模板目录
		$this->_tpl_dir = SITE_TEMPLATE;
		
		// 当前项目模板目录
		if (IS_ADMIN) {
			$this->_dir = APPPATH.'{templates}/admin/';
			$this->cron = FALSE;
		} elseif (IS_MEMBER) {
			$this->_dir = APPPATH.'{templates}/member/'.MEMBER_TEMPLATE.'/';
			$this->cron = FALSE;
		} else {
			$this->_dir = APPPATH.'{templates}/'.$this->_tpl_dir.'/';
			$this->cron = TRUE;
		}
    }
	
	/**
     * 强制设置为后台模板目录
     */
	public function admin() {
		$this->_dir = $this->_aroot;
	}
	
	/**
     * 强制设置模会员中心模板目录
	 *
     * @param	string	$dir	模板名称
     */
	public function space($dir) {
		$this->_dir = $this->_mroot = FCPATH.'member/templates/'.$dir.'/';
	}
	
	/**
     * 强制设置模块模板目录
	 *
     * @param	string	$dir	模板名称
     */
	public function module($dir) {
		
		if (IS_ADMIN || IS_MEMBER) return NULL;
		
		$this->_dir = APPPATH.'{templates}/'.$dir.'/';
		$this->_tpl_dir = $dir;
	}

    /**
     * 设置模块/应用的模板目录
	 *
     * @param	string	$file		文件名
     * @param	string	$dir		模块/应用名称
     * @param	string	$include	是否使用的是include标签
     */
    public function get_file_name($file, $dir = NULL, $include = FALSE) {
		
		if (IS_ADMIN || $dir == 'admin') { // 后台操作时，不需要加载风格目录，如果文件不存在可以尝试调用主项目模板
			if (@is_file($this->_dir.$file)) return $this->_dir.$file; // 当前项目目录模板存在时调用当前的
			if (@is_file($this->_aroot.$file)) return $this->_aroot.$file; // 当前项目目录模板不存在时调用主项目的
			$error = $this->_dir.$file;
		} elseif (IS_MEMBER || $dir == 'member') { // 会员操作时，需要加载风格目录，如果文件不存在可以尝试调用主项目模板
			if ($dir === '/' && is_file($this->_root.$file)) return $this->_root.$file;
			if (@is_file($this->_dir.$file)) return $this->_dir.$file;
			if (@is_file($this->_mroot.$file)) return $this->_mroot.$file;
			$error = $this->_dir.$file;
		} else {
			if ($dir === '/' && is_file($this->_root.$file)) return $this->_root.$file;
			if (@is_file($this->_dir.$file)) return $this->_dir.$file;
			if (@is_file($this->_root.$file)) return $this->_root.$file;
			if (@is_file(str_replace('/'.$this->_tpl_dir.'/', '/default/', $this->_dir.$file))) return str_replace('/'.$this->_tpl_dir.'/', '/default/', $this->_dir.$file);
			$error = $dir === '/' ? $this->_root.$file : $this->_dir.$file;
		}
		
		if ($this->mobile && get_cookie('mobile')) set_cookie('mobile', 0);
		show_error('模板文件 ('.str_replace(array(APPPATH, FCPATH), '', $error).') 不存在', 500, '模板解析错误');
		
    }
	
    /**
     * 输出模板
     *
     * @param	string	$_name		模板文件名称（含扩展名）
     * @param	string	$_dir		模块名称
     * @return  void
     */
    public function display($_name, $_dir = NULL) {
		
		$this->_options['ci'] = $this->ci;
		extract($this->_options, EXTR_PREFIX_SAME, 'data');
		$this->_options = NULL;
		$this->_filename = $_name;
		
		$template = $this->mobile ? 'mobiles' : 'templates';
		if ($this->mobile && !is_file(str_replace('{templates}', 'mobiles', $this->_dir).$_name)) $template = 'templates';
		$this->_dir = str_replace('{templates}', $template, $this->_dir);
		$this->_root = str_replace('{templates}', $template, $this->_root);
		$this->_mroot = str_replace('{templates}', $template, $this->_mroot);
		$this->_aroot = str_replace('{templates}', $template, $this->_aroot);
		
        // 加载编译后的缓存文件
        include $this->load_view_file($this->get_file_name($_name, $_dir));
    }

    /**
     * 设置模板变量
     */
    public function assign($key, $value = NULL) {
	
        if (!$key) return FALSE;
		
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->_options[$k] = $v;
            }
        } else {
            $this->_options[$key] = $value;
        }
    }
	
	/**
     * 获取模板变量
     */
    public function get_value($key) {
	
        if (!$key) return NULL;
		
        return $this->_options[$key];
    }
	
    /**
     * 模板标签include/template
     *
     * @param	string	$name	模板文件
     * @param	string	$dir	应用、模块目录
     * @return  bool
     */
    public function _include($name, $dir = NULL) {
		return $this->load_view_file($this->get_file_name($name, $dir, TRUE));
    }
	
    /**
     * 加载
     *
     * @param	string
     * @return  string
     */
    private function load_view_file($name) {
	
        $cache_file	= $this->_cache.md5($name).($this->mobile ? '.mobile.' : '').'.cache.php';
        
		// 当缓存文件不存在时或者缓存文件创建时间少于了模板文件时,再重新生成缓存文件
        if (!is_file($cache_file) || (is_file($cache_file) && is_file($name) && filemtime($cache_file) < filemtime($name))) {
			$content = $this->handle_view_file(file_get_contents($name));
			// 执行任务队列代码
			if ($this->cron && basename($name) == basename($this->_filename)) {
				$content.= '<script type="text/javascript" src="'.SITE_URL.'index.php?c=cron"></script>';
			}
            if (@file_put_contents($cache_file, $content, LOCK_EX) === FALSE) {
            	show_error('请将模板缓存目录（/cache/templates/）权限设为777', 404, '无写入权限');
            }
        }
		
        return $cache_file;
    }

    /**
     * 解析模板文件
     *
     * @param	string
     * @param	string
     * @return  string
     */
    private function handle_view_file($view_content) {
	
        if (!$view_content) return '';
		
        // 正则表达式匹配的模板标签
        $regex_array = array(
			
			// 站点缓存数据变量
			'#{([A-Z\-]+)\.(.+)}#U',
			
			// 3维数组变量
			'#{\$(\w+?)\.(\w+?)\.(\w+?)\.(\w+?)}#i',
			// 2维数组变量
			'#{\$(\w+?)\.(\w+?)\.(\w+?)}#i',
			// 1维数组变量
			'#{\$(\w+?)\.(\w+?)}#i',
			
			// 3维数组变量
			'#\$(\w+?)\.(\w+?)\.(\w+?)\.(\w+?)#Ui',
			// 2维数组变量
			'#\$(\w+?)\.(\w+?)\.(\w+?)#Ui',
			// 1维数组变量
			'#\$(\w+?)\.(\w+?)#Ui',
			
            // PHP函数
            '#{([a-z_0-9]+)\((.*)\)}#Ui',
            // PHP常量
            '#{([A-Z_]+)}#',
            // PHP变量
            '#{\$(.+?)}#i',
			
            // 引入模板
            '#{\s*template\s+"([\$_\/\w\.]+)",\s*"(.+)"\s*}#Uis',
            '#{\s*template\s+"([\$_\/\w\.]+)"\s*}#Uis',
            '#{\s*template\s+([\$_\/\w\.]+)\s*}#Uis',
            
			// php标签
            '#{php\s+(.+?)}#is',
			
			// list标签
            '#{list\s+(.+?)return=(.+?)\s?}#i',
            '#{list\s+(.+?)\s?}#i',
            '#{\s?\/list\s?}#i',
           
		   // if判断语句
            '#{\s?if\s+(.+?)\s?}#i',
            '#{\s?else\sif\s+(.+?)\s?}#i',
            '#{\s?else\s?}#i',
            '#{\s?\/if\s?}#i',
			
            // 循环语句
            '#{\s?loop\s+\$(.+?)\s+\$(\w+?)\s?\$(\w+?)\s?}#i',
            '#{\s?loop\s+\$(.+?)\s+\$(\w+?)\s?}#i',
            '#{\s?loop\s+\$(.+?)\s+\$(\w+?)\s?=>\s?\$(\w+?)\s?}#i',
            '#{\s?\/loop\s?}#i',
            
			// 结束标记
            '#{\s?php\s?}#i',
            '#{\s?\/php\s?}#i',
            '#\?\>\s*\<\?php\s#s',
        );

        // 替换直接变量输出
        $replace_array = array(
			
			"<?php \$cache = \$this->_cache_var('\\1'); eval('echo \$cache'.\$this->_get_var('\\2').';');unset(\$cache); ?>",
			
            "<?php echo \$\\1['\\2']['\\3']['\\4']; ?>",
            "<?php echo \$\\1['\\2']['\\3']; ?>",
            "<?php echo \$\\1['\\2']; ?>",
			
            "\$\\1['\\2']['\\3']['\\4']",
            "\$\\1['\\2']['\\3']",
            "\$\\1['\\2']",
			
            "<?php echo \\1(\\2); ?>",
            "<?php echo \\1; ?>",
            "<?php echo \$\\1; ?>",
			
            "<?php if (\$fn_include = \$this->_include(\"\\1\", \"\\2\")) include(\$fn_include); ?>",
            "<?php if (\$fn_include = \$this->_include(\"\\1\")) include(\$fn_include); ?>",
            "<?php if (\$fn_include = \$this->_include(\"\\1\")) include(\$fn_include); ?>",
			
            "<?php \\1 ?>",
			
            "<?php \$return_\\2 = \$this->list_tag(\"\\1 return=\\2\"); if (\$return_\\2) extract(\$return_\\2); \$count_\\2=count(\$return_\\2); if (is_array(\$return_\\2)) { foreach (\$return_\\2 as \$key_\\2=>\$\\2) { ?>",
            "<?php \$return = \$this->list_tag(\"\\1\"); if (\$return) extract(\$return); \$count=count(\$return); if (is_array(\$return)) { foreach (\$return as \$key=>\$t) { ?>",
            "<?php } } ?>",
			
            "<?php if (\\1) { ?>",
            "<?php } else if (\\1) { ?>",
            "<?php } else { ?>",
            "<?php } ?>",
			
            "<?php if (is_array(\$\\1)) { \$count=count(\$\\1);foreach (\$\\1 as \$\\2=>\$\\3) { ?>",
            "<?php if (is_array(\$\\1)) { \$count=count(\$\\1);foreach (\$\\1 as \$\\2) { ?>",
            "<?php if (is_array(\$\\1)) { \$count=count(\$\\1);foreach (\$\\1 as \$\\2=>\$\\3) { ?>",
            "<?php } } ?>",
			
            "<?php ",
            " ?>",
            " ",
        );
		
		$view_content = preg_replace($regex_array, $replace_array, $view_content);
		
		// 兼容php5.5
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			$view_content = preg_replace_callback("/_get_var\('(.*)'\)/Ui", "php55_replace_cache_array", $view_content);
			$view_content = preg_replace_callback("/list_tag\(\"(.*)\"\)/Ui", "php55_replace_array", $view_content);
		} else {
			$view_content = preg_replace("/_get_var\('(.*)'\)/Uie", "\$this->_replace_cache_array('\\1')", $view_content);
			$view_content = preg_replace("/list_tag\(\"(.*)\"\)/Uie", "\$this->_replace_array('\\1')", $view_content);
		}
		
        return $view_content;
    }
	
	// 替换cache标签中的单引号数组
	private function _replace_cache_array($string) {
		return "_get_var('".preg_replace('#\[\'(\w+)\'\]#Ui', '.\\1', $string)."')";
	}
	
	// 替换list标签中的单引号数组
	private function _replace_array($string) {
		return "list_tag(\"".preg_replace('#\[\'(\w+)\'\]#Ui', '[\\1]', $string)."\")";
	}
	
	// list 标签解析
	public function list_tag($_params) {
		
		if (!$this->ci) return NULL;
		
		$system = array(
			'num' => '', // 显示数量
			'form' => '', // 表单
			'page' => '', // 是否分页
			'site' => '', // 站点id
			'flag' => '', // 推荐位id
			'more' => '', // 是否显示栏目附加表
			'cache' => 3600, // 缓存时间
			'catid' => '', // 栏目id，支持多id
			'field' => '', // 显示字段
			'order' => '', // 排序
			'space' => '', // 空间uid
			'action' => '', // 动作标识
			'module' => APP_DIR, // 模块名称
			'modelid' => '', // 模型id
			'return' => '', // 返回变量
			'keyword' => '', // 关键字
			'urlrule' => '', // 自定义分页规则
			'pagesize' => '', // 自定义分页数量
		);
		$param = $where = array();
		$params = explode(' ', $_params);
		$sysadj = array('IN', 'BEWTEEN', 'BETWEEN', 'LIKE');
		foreach ($params as $t) {
			$var = substr($t, 0, strpos($t, '='));
			$val = substr($t, strpos($t, '=') + 1);
			if (!$var) continue;
			if (isset($system[$var])) { // 系统参数，只能出现一次，不能添加修饰符
				$system[$var] = $val;
			} else {
				if (preg_match('/^([A-Z_]+)(.+)/', $var, $match)) { // 筛选修饰符参数
					$_pre = explode('_', $match[1]);
					$_adj = '';
					foreach ($_pre as $p) {
						if (in_array($p, $sysadj)) {
							$_adj = $p;
						}
					}
					$where[] = array(
						'adj' => $_adj,
						'name' => $match[2],
						'value' => $val
					);
				} else {
					$where[] = array(
						'adj' => '',
						'name' => $var,
						'value' => $val
					);
				}
				$param[$var] = $val; // 用于特殊action
			}
		}
		
		// action
		switch ($system['action']) {
			
			case 'cache': // 系统缓存数据
			
				if (!isset($param['name'])) return $this->_return($system['return'], 'name参数不存在');
				
				$pos = strpos($param['name'], '.');
				if ($pos !== FALSE) {
					$_name = substr($param['name'], 0, $pos);
					$_param = substr($param['name'], $pos + 1);
				} else {
					$_name = $param['name'];
					$_param = NULL;
				}
				$cache = $this->_cache_var($_name, !$system['site'] ? SITE_ID : $system['site']);
				if (!$cache) return $this->_return($system['return'], "缓存({$_name})不存在!");
				
				if ($_param) {
					@eval('$data=$cache'.$this->_get_var($_param).';');
					if (!$data) return $this->_return($system['return'], "缓存({$_name})参数不存在!!");
				} else {
					$data = $cache;
				}
				
				return $this->_return($system['return'], $data, '');
				break;
			
			case 'content': // 模块文档内容
			
				if (!isset($param['id'])) return $this->_return($system['return'], 'id参数不存在');
				
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) return $this->_return($system['return'], "模块({$system['module']})缓存不存在");
				
				$file = FCPATH.$module['dirname'].'/models/Content_model.php';
				if (!is_file($file)) return $this->_return($system['return'], "模块({$system['module']})文件models/Content_model.php不存在");
				
				
				require_once $file;
				$db = new Content_model();
				$db->link = $this->ci->site[$system['site']];
				$db->prefix = $this->ci->db->dbprefix($system['site'].'_'.$module['dirname']);
				
				return $this->_return($system['return'], $db->get($param['id']), '');
				break;
			
			case 'category': // 栏目
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module || count($module['category']) == 0) return $this->_return($system['return'], "模块({$system['module']})缓存不存在");
				
				$i = 0;
				$show = isset($param['show']) ? 1 : 0; // 有show参数表示显示隐藏栏目
				$return = array();
				foreach ($module['category'] as $t) {
					if ($system['num'] && $i >= $system['num']) break;
					if (!$t['show'] && !$show) continue;
					if (isset($param['pid']) && $t['pid'] != (int)$param['pid']) continue;
					if (isset($param['letter']) && $t['letter'] != $param['letter']) continue;
					if (isset($param['id']) && !in_array($t['id'], explode(',', $param['id']))) continue;
					if (isset($system['more']) && !$system['more']) unset($t['field'], $t['setting']);
					$return[] = $t;
					$i ++;
				}
				
				if (!$return) return $this->_return($system['return'], '没有匹配到内容');
				
				return $this->_return($system['return'], $return, '');
				break;
			
			case 'linkage': // 联动菜单
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$linkage = $this->ci->get_cache('linkage-'.$system['site'].'-'.$param['code']);
				if (!$linkage) {
					return $this->_return($system['return'], "联动菜单{$param['code']}不存在");
				}
				
				$i = 0;
				$return = array();
				foreach ($linkage as $t) {
					if ($system['num'] && $i >= $system['num']) break;
					if (isset($param['pid']) && $t['pid'] != (int)$param['pid']) continue;
					if (isset($param['id']) && !in_array($t['id'], explode(',', $param['id']))) continue;
					$return[] = $t;
					$i ++;
				}
				
				if (!$return) {
					foreach ($linkage as $t) {
						if ($t['pid'] == (int)$linkage[$param['pid']]['pid']) {
							if ($system['num'] && $i >= $system['num']) break;
							if (isset($param['id']) && !in_array($t['id'], explode(',', $param['id']))) continue;
							$return[] = $t;
							$i ++;
						}
					}
					if (!$return) return $this->_return($system['return'], '没有匹配到内容');
				}
				
				return $this->_return($system['return'], $return, '');
				break;
			
			case 'search': // 搜索字段筛选
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				$catid = $system['catid'];
				if (!$module || count($module['category'][$catid]['field']) == 0) return $this->_return($system['return'], '模块缓存不存在或者此栏目无附加字段');
				
				$return = array();
				foreach ($module['category'][$catid]['field'] as $t) {
					if ($t['issearch'] && $t['ismain'] && ($t['fieldtype']=='Select' || $t['fieldtype']=='Radio')) {
						$data = @explode(PHP_EOL, $t['setting']['option']['options']);
						if ($data) {
							$list = array();
							foreach ($data as $c) {
								list($name, $value) = @explode('|', $c);
								if ($name && !is_null($value)) {
									$list[] = array(
										'name' => trim($name),
										'value' => trim($value)
									);
								}
							}
							
							if ($list) {
								$return[] = array(
									'name' => $t['name'],
									'field' => $t['fieldname'],
									'data' => $list,
								);
							}
						}
						
					}
				}
				
				return $this->_return($system['return'], $return, '');
				break;
			
			case 'navigator': // 网站导航
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$navigator = $this->ci->get_cache('navigator-'.$system['site']); // 导航缓存
				if (!$navigator) return $this->_return($system['return'], '导航缓存不存在');
				
				$i = 0;
				$show = isset($param['show']) ? 1 : 0; // 有show参数表示显示隐藏栏目
				$data = $navigator[(int)$param['type']];
				if (!$data) return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
				
				$return = array();
				foreach ($data as $t) {
					if ($system['num'] && $i >= $system['num']) break;
					if (isset($param['pid']) && $t['pid'] != (int)$param['pid']) continue;
					if (!$t['show'] && !$show) continue;
					$return[] = $t;
					$i ++;
				}
				
				if (!$return) return $this->_return($system['return'], '没有匹配到内容');

				return $this->_return($system['return'], $return, '');
				break;
				
			case 'page': // 单页调用
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$name = $system['module'] ? $system['module'] : 'index';
				$data = $this->ci->get_cache('page-'.$system['site'], 'data', $name); // 单页缓存
				if (!$data) return $this->_return($system['return'], '没有查询到内容');
				
				$i = 0;
				$show = isset($param['show']) ? 1 : 0; // 有show参数表示显示隐藏栏目
				$return = array();
				foreach ($data as $id => $t) {
					if (!is_numeric($id)) continue;
					if ($system['num'] && $i >= $system['num']) break;
					if (!$t['show'] && !$show) continue;
					if (isset($param['pid']) && $t['pid'] != (int)$param['pid']) continue;
					if (isset($param['id']) && !in_array($t['id'], explode(',', $param['id']))) continue;
					$return[] = $t;
					$i ++;
				}
				
				if (!$return) return $this->_return($system['return'], '没有匹配到内容');
				
				return $this->_return($system['return'], $return, $sql);
				break;
				
			case 'related': // 相关文章
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) {
					return $this->_return($system['return'], "模块({$system['module']})缓存不存在"); // 没有模块数据时返回空
				}
				if (!$param['tag']) {
					return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
				} else {
					$where = array();
					$array = explode(',', $param['tag']);
					foreach ($array as $name) {
						if ($name) $where[] = '(`title` LIKE "%'.$this->ci->db->escape_str($name).'%" OR `keywords` LIKE "%'.$this->ci->db->escape_str($name).'%")';
					}
					$where = implode(' OR ', $where);
				}
				
				$table = $this->ci->db->dbprefix($system['site'].'_'.$module['dirname']); // 模块主表
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM {$table} WHERE {$where} ORDER BY updatetime DESC LIMIT ".($system['num'] ? $system['num'] : 10);
				$data = $this->_query($sql, $system['site'], $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-sql-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					$fields = $module['field'];
					// 模块表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					$fields['updatetime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql);
				break;
			
			case 'tag': // 调用tag
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) return $this->_return($system['return'], "模块({$system['module']})缓存不存在"); // 没有模块数据时返回空
				
				$table = $this->ci->db->dbprefix($system['site'].'_'.$module['dirname'].'_tag'); // tag表
				$sql = "SELECT id,name,code,hits FROM {$table} ORDER BY hits DESC LIMIT ".($system['num'] ? $system['num'] : 10);
				$data = $this->_query($sql, $system['site'], $system['cache']);
				
				if (!$data) return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
				
				// 缓存查询结果
				$name = 'list-action-tag-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					foreach ($data as $i => $t) {
						$data[$i]['url'] = dr_tag_url($module, $t['name']);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql);
				break;
			
			case 'sql':	// 直接sql查询
			
				if (preg_match('/sql=\'(.+)\'/sU', $_params, $sql)) {
				
					$db = !$system['site'] ? ($system['module'] ? $this->ci->site[SITE_ID] : $this->ci->db) : $this->ci->site[$system['site']]; // 数据库对象
					$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
					$sql = str_replace('@#', $db->dbprefix, trim($sql[1]));
					if (stripos($sql, 'SELECT') !== 0) return $this->_return($system['return'], 'SQL语句只能是SELECT查询语句');
					
					$total = 0;
					$pages = '';
					
					if ($system['page'] && $system['urlrule']) { // 如存在分页条件才进行分页查询
						$page = max(1, (int)$this->ci->input->get('page'));
						$row = $this->_query(preg_replace('/select(.*)from/iUs', 'SELECT count(*) as c FROM', $sql), $system['site'], $system['cache'], FALSE);
						$total = (int)$row['c'];
						$pagesize = $system['pagesize'] ? $system['pagesize'] : 10;
						if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
						$sql .= ' LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
						$pages = $this->_get_pagination(str_replace('[page]', '{page}', $system['urlrule']), $pagesize, $total);
					}
					
					$data = $this->_query($sql, $system['site'], $system['cache']);
					$fields = NULL;
					
					if ($system['module'] && $module = get_module($system['module'], $system['site'])) {
						$fields = $module['field']; // 模块主表的字段
					} elseif ($system['modelid'] && $model = $this->ci->get_cache('space-model', $system['modelid'])) {
						$fields = $model['field']; // 空间模型的字段
					}
					
					if ($fields) {
						// 缓存查询结果
						$name = 'list-action-sql-'.md5($sql);
						$cache = $this->ci->get_cache_data($name);
						if (!$cache) {
							// 模块表的系统字段
							$fields['inputtime'] = array('fieldtype' => 'Date');
							$fields['updatetime'] = array('fieldtype' => 'Date');
							// 格式化显示自定义字段内容
							foreach ($data as $i => $t) {
								$data[$i] = $this->ci->field_format_value($fields, $t);
							}
							$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
						}
						$data = $cache;
					}
					return $this->_return($system['return'], $data, $sql, $total, $pages);
				} else {
					return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
				}
				break;
				
			case 'model': // 空间模型
				
				$uid = (int)$system['space'];
				$mid = (int)$system['modelid'];
				if (!$mid) {
					return $this->_return($system['return'], "modelid参数必须存在"); // 参数判断
				}
				$model = $this->ci->get_cache('space-model', $mid);
				if (!$model) {
					return $this->_return($system['return'], "空间模型({$system['modelid']})缓存不存在"); // 没有模型数据时返回空
				}
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
				$system['order'] = !$system['order'] ? 'updatetime' : $system['order']; // 默认排序参数
				if ($uid) $where[] = array(
					'adj' => '',
					'name' => 'uid',
					'value' => $uid
				);
				$table = $this->ci->db->dbprefix('space_'.$model['table']); // 模块主表
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				
				$total = 0;
				$fields = $model['field']; // 主表的字段
				$sql_from = $table; // sql的from子句
				$sql_limit = $pages = '';
				$sql_where = $this->_get_where($where); // sql的where子句
				
				if ($this->ci->uid == $uid) $system['cache'] = 0; // 当前作者不缓存
				
				if ($system['page'] && $system['urlrule']) {
					$page = max(1, (int)$this->ci->input->get('page'));
					$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
					$pagesize = (int)$system['pagesize'];
					$pagesize = $pagesize ? $pagesize : 10;
					$sql = "SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL";
					$row = $this->_query($sql, 0, $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					$sql_limit = 'LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination($urlrule, $pagesize, $total);
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ".($system['order'] ? "ORDER BY {$system['order']}" : "")." $sql_limit";
				$data = $this->_query($sql, 0, $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-space-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 模块表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					$fields['updatetime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
			
			case 'extend': // 子内容调用
				
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) {
					return $this->_return($system['return'], "模块({$system['module']})缓存不存在"); // 没有模块数据时返回空
				}
				$db = $this->ci->site[$system['site']];
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
				$system['order'] = !$system['order'] ? 'displayorder desc,inputtime asc' : $system['order']; // 默认排序参数
				$index = $this->ci->db->dbprefix($system['site'].'_'.$module['dirname'].'_extend'); // 表名称
				// 查询附表id
				$row = $db->select('tableid')
						  ->where('cid', (int)$field['cid'])
						  ->limit(1)
						  ->get($index)
						  ->row_array();
				$tableid = (int)$row['tableid'];
				$table = $index.'_'.$tableid;
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				
				$total = 0;
				$fields = $module['extend']; // 主表的字段
				$sql_from = $table; // sql的from子句
				$sql_limit = $pages = '';
				$sql_where = $this->_get_where($where); // sql的where子句
				
				if (defined('MODULE_HTML') && MODULE_HTML) $system['cache'] = 0;
				
				if ($system['page'] && $system['urlrule']) {
					$page = max(1, (int)$this->ci->input->get('page'));
					$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
					$pagesize = (int)$system['pagesize'];
					$pagesize = $pagesize ? $pagesize : 10;
					$sql = "SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL";
					$row = $this->_query($sql, 0, $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					$sql_limit = 'LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination($urlrule, $pagesize, $total);
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ".($system['order'] ? "ORDER BY {$system['order']}" : "")." $sql_limit";
				$data = $this->_query($sql, 0, $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-extend-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
				
			case 'form': // 表单调用
				
				$mid = $system['form'];
				$site = $system['site'] ? $system['site'] : SITE_ID;
				$form = $this->ci->get_cache('form-'.$site, $mid);
				if (!$form) {
					return $this->_return($system['return'], "表单($mid)缓存不存在"); // 参数判断
				}
				$db = $this->ci->site[$site];
				
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
				$system['order'] = !$system['order'] ? 'inputtime' : $system['order']; // 默认排序参数
				$table = $this->ci->db->dbprefix($site.'_form_'.$form['table']); // 表单表名称
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				
				$total = 0;
				$fields = $form['field']; // 主表的字段
				$sql_from = $table; // sql的from子句
				$sql_limit = $pages = '';
				$sql_where = $this->_get_where($where); // sql的where子句
				
				if ($system['page'] && $system['urlrule']) {
					$page = max(1, (int)$this->ci->input->get('page'));
					$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
					$pagesize = (int)$system['pagesize'];
					$pagesize = $pagesize ? $pagesize : 10;
					$sql = "SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL";
					$row = $this->_query($sql, 0, $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					$sql_limit = 'LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination($urlrule, $pagesize, $total);
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ".($system['order'] ? "ORDER BY {$system['order']}" : "")." $sql_limit";
				$data = $this->_query($sql, 0, $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-form-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
				
			case 'mform': // 模块表单调用
				
				$site = $system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) {
					return $this->_return($system['return'], "模块({$system['module']})缓存不存在"); // 没有模块数据时返回空
				}
				
				$fid = $system['form'];
				$form = $module['form'][$fid];
				if (!$form) {
					return $this->_return($system['return'], "模块表单($fid)缓存存在"); // 参数判断
				}
				
				$db = $this->ci->site[$site];
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
				
				$system['order'] = !$system['order'] ? 'inputtime' : $system['order']; // 默认排序参数
				$table = $this->ci->db->dbprefix($site.'_'.$module['dirname'].'_form_'.$fid); // 表单表名称
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				
				$total = NULL;
				$fields = $form['field']; // 主表的字段
				$sql_from = $table; // sql的from子句
				$sql_where = $this->_get_where($where); // sql的where子句
				$sql_limit = $pages = '';
				
				if ($system['page'] && $system['urlrule']) {
					$page = max(1, (int)$this->ci->input->get('page'));
					$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
					$pagesize = (int)$system['pagesize'];
					$pagesize = $pagesize ? $pagesize : 10;
					$sql = "SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL";
					$row = $this->_query($sql, 0, $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					$sql_limit = 'LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination($urlrule, $pagesize, $total);
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ".($system['order'] ? "ORDER BY {$system['order']}" : "")." $sql_limit";
				$data = $this->_query($sql, 0, $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-mform-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
				
			case 'space': // 空间数据
			
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
			
				$table = $this->ci->db->dbprefix('space'); // 空间主表
				$system['order'] = !$system['order'] ? 'displayorder' : $system['order']; // 默认排序参数
				
				if ($system['keyword']) {
					$where[] = array(
						'adj' => 'LIKE',
						'name' => 'name',
						'value' => '%'.$system['keyword'].'%'
					);
				}
				
				$where[] = array(
					'adj' => '',
					'name' => 'status',
					'value' => 1
				);
				
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				
				$sql_from = $table; // sql的from子句
				
				if ($system['more']) { // 会员附表
					$more = $this->ci->db->dbprefix('member_data'); // 附表
					$where = $this->_set_where_field_prefix($where, $tableinfo[$more]['field'], $more); // 给条件字段加上表前缀
					$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$more]['field'], $more); // 给显示字段加上表前缀
					$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$more]['field'], $more); // 给排序字段加上表前缀
					$sql_from .= " LEFT JOIN $more ON `$table`.`uid`=`$more`.`uid`"; // sql的from子句
				}
				if ($system['more'] == 2) { // 会员主表
					$more2 = $this->ci->db->dbprefix('member'); // 附表
					$where = $this->_set_where_field_prefix($where, $tableinfo[$more2]['field'], $more2); // 给条件字段加上表前缀
					if ($system['field']) {
						$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$more2]['field'], $more2); // 给显示字段加上表前缀
					} else {
						$system['field'] = "`{$table}`.*".($more ? ",`{$more}`.*" : "").",`{$more2}`.`username`,`{$more2}`.`groupid`";
					}
					$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$more2]['field'], $more2); // 给排序字段加上表前缀
					$sql_from .= " LEFT JOIN $more2 ON `$table`.`uid`=`$more2`.`uid`"; // sql的from子句
				}
				
				$total = 0;
				$sql_limit = '';
				$sql_where = $this->_get_where($where); // sql的where子句
				
				if ($system['flag']) { // 推荐位调用
					$_ids = $this->_query("select uid from {$table}_flag where `flag`=".(int)$system['flag'], $system['site'], $system['cache']);
					$in = array();
					foreach ($_ids as $t) {
						$in[] = $t['uid'];
					}
					if (!$in) return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
					$sql_where.= " AND `$table`.`uid` IN (".implode(',', $in).")";
				}
				
				if ($system['page'] && $system['urlrule']) { // 如存在分页条件才进行分页查询
				
					$page = max(1, (int)$this->ci->input->get('page'));
					$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
					$pagesize = (int)$system['pagesize'];
					$pagesize = $pagesize ? $pagesize : 10;
					$row = $this->_query("SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL", $system['site'], $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					
					$sql_limit = ' LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination(str_replace('[page]', '{page}', $urlrule), $pagesize, $total);
					
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ".($system['order'] == "null" ? "" : " ORDER BY {$system['order']}")." $sql_limit";
				$data = $this->_query($sql, $system['site'], $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-space-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 系统字段
					$fields['regtime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
			
			case 'module': // 模块数据
			
				$system['site'] = !$system['site'] ? SITE_ID : $system['site']; // 默认站点参数
				$module = get_module($system['module'] ? $system['module'] : APP_DIR, $system['site']);
				if (!$module) {
					return $this->_return($system['return'], "模块({$system['module']})缓存不存在"); // 没有模块数据时返回空
				}
				$tableinfo = $this->ci->get_cache('table');
				if (!$tableinfo) {
					$this->ci->load->model('system_model');
					$tableinfo = $this->ci->system_model->cache(); // 表结构缓存
				}
				if (!$tableinfo) {
					return $this->_return($system['return'], '表结构缓存不存在'); // 没有表结构缓存时返回空
				}
				$table = $this->ci->db->dbprefix($system['site'].'_'.$module['dirname']); // 模块主表
				if (!$system['order'] && $where[0]['adj'] == 'IN' && $where[0]['name'] == 'id') {
					$system['order'] = 'instr("'.$where[0]['value'].'", `'.$table.'`.`id`)';
				} else {
					$system['order'] = !$system['order'] ? 'updatetime' : $system['order']; // 默认排序参数
				}
				if ($system['catid']) { // 栏目
					if (strpos($system['catid'], ',') !== FALSE) {
						$where[] = array(
							'adj' => 'IN',
							'name' => 'catid',
							'value' => $system['catid']
						);
					} elseif ($module['category'][$system['catid']]['child']) {
						$where[] = array(
							'adj' => 'IN',
							'name' => 'catid',
							'value' => $module['category'][$system['catid']]['childids']
						);
					} else {
						$where[] = array(
							'adj' => '',
							'name' => 'catid',
							'value' => (int)$system['catid']
						);
					}
				}
				
				$fields = $module['field']; // 主表的字段
				$where = $this->_set_where_field_prefix($where, $tableinfo[$table]['field'], $table); // 给条件字段加上表前缀
				$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table]['field'], $table); // 给显示字段加上表前缀
				$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table]['field'], $table); // 给排序字段加上表前缀
				$sql_from = $table; // sql的from子句
				
				if ($system['more'] && $module['category'][$system['catid']]['field']) { // 关联栏目附加表
					$fields = array_merge($fields, $module['category'][$system['catid']]['field']);
					$table_more = $table.'_category_data'; // 栏目附加表
					$where = $this->_set_where_field_prefix($where, $tableinfo[$table_more]['field'], $table_more); // 给条件字段加上表前缀
					$system['field'] = $this->_set_select_field_prefix($system['field'], $tableinfo[$table_more]['field'], $table_more); // 给显示字段加上表前缀
					$system['order'] = $this->_set_order_field_prefix($system['order'], $tableinfo[$table_more]['field'], $table_more); // 给排序字段加上表前缀
					$sql_from .= " LEFT JOIN $table_more ON `$table_more`.`id`=`$table`.`id`"; // sql的from子句
				}
				
				$total = 0;
				$sql_limit = $pages = '';
				$sql_where = $this->_get_where($where); // sql的where子句
				
				if ($system['flag']) { // 推荐位调用
					$_ids = $this->_query("select id from {$table}_flag where `flag`=".(int)$system['flag'], $system['site'], $system['cache']);
					$in = array();
					foreach ($_ids as $t) {
						$in[] = $t['id'];
					}
					if (!$in) return $this->_return($system['return'], '没有查询到内容'); // 没有查询到内容
					$sql_where.= " AND `$table`.`id` IN (".implode(',', $in).")";
				}
				
				if ($system['page']) {
					$page = max(1, (int)$this->ci->input->get('page'));
					if (is_numeric($system['catid'])) {
						$urlrule = dr_category_url($module, $module['category'][$system['catid']], '{page}');
						$pagesize = (int)$module['category'][$system['catid']]['setting']['template']['pagesize'];
					} else {
						$urlrule = str_replace('[page]', '{page}', $system['urlrule']);
						$pagesize = (int)$system['pagesize'];
					}
					$pagesize = $pagesize ? $pagesize : 10;
					$sql = "SELECT count(*) as c FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "")." ORDER BY NULL";
					$row = $this->_query($sql, $system['site'], $system['cache'], FALSE);
					$total = (int)$row['c'];
					if (!$total) return $this->_return($system['return'], "没有查询到内容", $sql, 0); // 没有数据时返回空
					$sql_limit = 'LIMIT ' . $pagesize * ($page - 1) . ',' . $pagesize;
					$pages = $this->_get_pagination($urlrule, $pagesize, $total);
				} elseif ($system['num']) {
					$sql_limit = "LIMIT {$system['num']}";
				}
				
				$sql = "SELECT ".($system['field'] ? $system['field'] : "*")." FROM $sql_from ".($sql_where ? "WHERE $sql_where" : "").($system['order'] == "null" ? "" : " ORDER BY {$system['order']}")." $sql_limit";
				$data = $this->_query($sql, $system['site'], $system['cache']);
				
				// 缓存查询结果
				$name = 'list-action-module-'.md5($sql);
				$cache = $this->ci->get_cache_data($name);
				if (!$cache) {
					// 模块表的系统字段
					$fields['inputtime'] = array('fieldtype' => 'Date');
					$fields['updatetime'] = array('fieldtype' => 'Date');
					// 格式化显示自定义字段内容
					foreach ($data as $i => $t) {
						$data[$i] = $this->ci->field_format_value($fields, $t);
					}
					$cache = $this->ci->set_cache_data($name, $data, $system['cache']);
				}
				
				return $this->_return($system['return'], $cache, $sql, $total, $pages);
				break;
				
			default :
				return $this->_return($system['return'], 'list标签必须含有参数action');
				break;
		}
	}
	
	/**
     * 查询缓存
     */
	private function _query($sql, $site, $cache, $all = TRUE) {
		
		// 数据库对象
		$db = $site ? $this->ci->site[$site] : $this->ci->db;
		// 缓存存在时读取缓存文件
		if ($cache && $data = $this->ci->get_cache_data(md5($sql))) return $data;
		// 查询结果
		$data = $all ? $db->query($sql)->result_array() : $db->query($sql)->row_array();
		// 开启缓存时，重新存储缓存数据
		if ($cache) $s = $this->ci->set_cache_data(md5($sql), $data, $cache);
		
		return $data;
	}
	
	/**
     * 分页
     */
    private function _get_pagination($url, $pagesize, $total) {
		
		$this->ci->load->library('pagination');
		
		$this->ci->config->load('pagination');
		$config = $this->ci->config->item('pagination');
		
		$config['base_url'] = $url;
		$config['per_page'] = $pagesize;
		$config['total_rows'] = $total;
		$config['use_page_numbers'] = TRUE;
		$config['query_string_segment']	= 'page';
		$this->ci->pagination->initialize($config);
		
		return $this->ci->pagination->dr_links();
    }
	
	// 条件子句格式化
	private function _get_where($where) {
		if ($where) {
			$string = '';
			foreach ($where as $t) {
				$join = $string ? ' AND' : '';
				switch ($t['adj']) {
					case 'LIKE':
						if ($t['value']) $string.= $join." {$t['name']} LIKE \"".$this->ci->db->escape_str($t['value'])."\"";
						break;
					case 'IN':
						if ($t['value']) $string.= $join." {$t['name']} IN (".$this->ci->db->escape_str($t['value']).")";
						break;
					case 'BETWEEN':
						if ($t['value']) $string.= $join." {$t['name']} BETWEEN ".str_replace(',', ' AND ', $t['value'])."";
						break;
					case 'BEWTEEN':
						if ($t['value']) $string.= $join." {$t['name']} BETWEEN ".str_replace(',', ' AND ', $t['value'])."";
						break;
					default:
						$string.= $join.(is_numeric($t['value']) ? " {$t['name']} = ".$t['value'] : " {$t['name']} = \"".$this->ci->db->escape_str($t['value'])."\"");
						break;
				}
			}
			return trim($string);
		}
		return 1;
	}
	
	// 给条件字段加上表前缀
	private function _set_where_field_prefix($where, $field, $prefix) {
		if ($where) {
			foreach ($where as $i => $t) {
				if (isset($field[$t['name']])) {
					$where[$i]['name'] = "`$prefix`.`{$t['name']}`";
				}
			}
		}
		return $where;
	}
	
	// 给显示字段加上表前缀
	private function _set_select_field_prefix($select, $field, $prefix) {
		if ($select) {
			$array = explode(',', $select);
			foreach ($array as $i => $t) {
				if (isset($field[$t])) {
					$array[$i] = "`$prefix`.`$t`";
				}
			}
			return implode(',', $array);
		}
		return $select;
	}
	
	// 给排序字段加上表前缀
	private function _set_order_field_prefix($order, $field, $prefix) {
		if ($order) {
			$array = explode(',', $order);
			foreach ($array as $i => $t) {
				list($a, $b) = explode('_', $t);
				if (isset($field[$a])) {
					$array[$i] = "`$prefix`.`$a` ".($b ? $b : "DESC");
				}
			}
			return implode(',', $array);
		}
		return $order;
	}
	
	// list 返回
	private function _return($return, $data = NULL, $sql = NULL, $total = NULL, $pages = NULL) {
		$error = '';
		if ($data && !is_array($data)) {
			$error = $data;
			$data = NULL;
		}
		if ($return) {
		    return array(
				'sql_'.$return => $sql,
				'error_'.$return => $error,
			    'pages_'.$return => $pages,
				'total_'.$return => isset($total) ? $total : count($data),
				'return_'.$return => $data
		    );
		} else {
			return array(
				'sql' => $sql,
				'error' => $error,
				'pages' => $pages,
				'total' => isset($total) ? $total : count($data),
				'return' => $data
			);
		}
	}
	
	private function _get_var($param) {
		$array = explode('.', $param);
		if (!$array) return '';
		$string = '';
		foreach ($array as $var) {
			$string.= '[';
			if (strpos($var, '$') === 0) {
				$string.= preg_replace('/\[(.+)\]/U', '[\'\\1\']',$var);
			} elseif (preg_match('/[A-Z_]+/', $var)) {
				$string.= ''.$var.'';
			} else {
				$string.= '\''.$var.'\'';
			}
			$string.= ']';
		}
		return $string;
	}
	
	// 公共变量参数
	private function _cache_var($name, $site = SITE_ID) {
		$data = NULL;
		$name = strtoupper($name);
		switch ($name) {
			case 'SPACE-MODEL':
				$data = $this->ci->get_cache('SPACE-MODEL');
				break;
			case 'MEMBER':
				$data = $this->ci->get_cache('member');
				break;
			case 'URLRULE':
				$data = $this->ci->get_cache('urlrule');
				break;
			case 'MODULE':
				$site = $site ? $site : SITE_ID;
				$data = $this->ci->get_module($site);
				if (!$data) { // 修复获取不到模块缓存问题
					$MOD = $this->ci->dcache->get('module');
					if ($MOD[$site]) {
						foreach ($MOD[$site] as $dir) {
							$data[$dir] = $this->ci->dcache->get('module-'.$site.'-'.$dir);
						}
					}
				}
				break;
			case 'CATEGORY':
				$site = $site ? $site : SITE_ID;
				$data = $this->ci->get_cache('module-'.$site.'-'.APP_DIR, 'category');
				break;
			case 'OAUTH':
				$oauth = require FCPATH.'config/oauth.php';
				if ($oauth) {
					foreach ($oauth as $id => $value) {
						if ($value['use'] == 1) {
							$value['id'] = $id;
							$data[] = $value;
						}
					}
				}
				break;
			case 'PAGE':
				$site = $site ? $site : SITE_ID;
				$name = APP_DIR ? APP_DIR : 'index';
				$data = $this->ci->get_cache('PAGE-'.$site, 'data');
				break;
			default:
				$data = $this->ci->get_cache($name.'-'.$site);
				break;
		}
		return $data;
	}
}

// 替换cache标签中的单引号数组 for php5.5
function php55_replace_cache_array($string) {
	return "_get_var('".preg_replace('#\[\'(\w+)\'\]#Ui', '.\\1', $string[1])."')";
}

// 替换list标签中的单引号数组
function php55_replace_array($string) {
	return "list_tag(\"".preg_replace('#\[\'(\w+)\'\]#Ui', '[\\1]', $string[1])."\")";
}