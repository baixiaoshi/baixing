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
	
class Route extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

	/**
     * 更新URL路由 主站单页路由 -> 模块路由 (模块栏目路由 -> 模块单页路由 -> 模块tag -> 模块搜索) -> 会员空间路由
     */
    public function index() {
		
		$name = $code = $note = '';
		$server = strtolower($_SERVER['SERVER_SOFTWARE']);
		
		if (strpos($server, 'apache') !== FALSE) {
			$name = 'Apache';
			$note = '<font color=red><b>将以下内容保存为.htaccess文件，放到网站根目录</b></font>';
			$code = 'RewriteEngine On'.PHP_EOL
			.'RewriteBase /'.PHP_EOL
			.'RewriteCond %{REQUEST_FILENAME} !-f'.PHP_EOL
			.'RewriteCond %{REQUEST_FILENAME} !-d'.PHP_EOL
			.'RewriteRule !.(js|ico|gif|jpe?g|bmp|png|css)$ /index.php [NC,L]';
		} elseif (strpos($server, 'iis/7') !== FALSE || strpos($server, 'iis/8') !== FALSE) {
			$name = $server;
			$note = '<font color=red><b>将以下内容保存为Web.config文件，放到网站根目录</b></font>';
			$code = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
			.'<configuration>'.PHP_EOL
			.'    <system.webServer>'.PHP_EOL
			.'        <rewrite>'.PHP_EOL
			.'            <rules>'.PHP_EOL
			.'		<rule name="finecms" stopProcessing="true">'.PHP_EOL
			.'		    <match url="^(.*)$" />'.PHP_EOL
			.'		    <conditions logicalGrouping="MatchAll">'.PHP_EOL
			.'		        <add input="{HTTP_HOST}" pattern="^(.*)$" />'.PHP_EOL
			.'		        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />'.PHP_EOL
			.'		        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />'.PHP_EOL
			.'		    </conditions>'.PHP_EOL
			.'		    <action type="Rewrite" url="index.php" /> '.PHP_EOL
			.'                </rule>'.PHP_EOL
			.'            </rules>'.PHP_EOL
			.'        </rewrite>'.PHP_EOL
			.'    </system.webServer> '.PHP_EOL
			.'</configuration>';
		} elseif (strpos($server, 'iis/6') !== FALSE) {
			$name = $server;
			$note = '建议使用isapi_rewrite第三版,老版本的rewrite不支持RewriteCond语法（<a style="color:blue" href="http://www.finecms.net/forum.php?mod=viewthread&tid=1823&extra=" target="_blank">老版本httpd.ini请看这里</a>）<br><font color=red><b>将以下内容保存为.htaccess文件，放到网站根目录</b></font>';
			$code = 'RewriteEngine On'.PHP_EOL
			.'RewriteBase /'.PHP_EOL
			.'RewriteCond %{REQUEST_FILENAME} !-f'.PHP_EOL
			.'RewriteCond %{REQUEST_FILENAME} !-d'.PHP_EOL
			.'RewriteRule !.(js|ico|gif|jpe?g|bmp|png|css)$ /index.php';
		} elseif (strpos($server, 'nginx') !== FALSE) {
			$name = $server;
			$note = '<font color=red><b>将以下代码放到Nginx配置文件中去（如果是绑定了域名，所绑定目录也要配置下面的代码），您懂得！</b></font>';
			$code = 'location / { '.PHP_EOL
			.'    if (-f $request_filename) {'.PHP_EOL
			.'           break;'.PHP_EOL
			.'    }'.PHP_EOL
			.'    if ($request_filename ~* "\.(js|ico|gif|jpe?g|bmp|png|css)$") {'.PHP_EOL
			.'        break;'.PHP_EOL
			.'    }'.PHP_EOL
			.'    if (!-e $request_filename) {'.PHP_EOL
			.'        rewrite . /index.php last;'.PHP_EOL
			.'    }'.PHP_EOL
			.'}';
		} else {
			$name = $server;
			$note = '<font color=red><b>当前服务器不提供伪静态规则，请自己将所有页面定向到index.php文件</b></font>';
		}
		
		$this->template->assign(array(
			'name' => $name,
			'code' => $code,
			'note' => $note,
			'count' => $code ? count(explode(PHP_EOL, $code)) : 0,
		));
		$this->template->display('route_index.html');
	}
	
	/**
     * 生成路由临时文件
     */
    public function todo() {
        
		$route = array();
		$string = '';
	    $module = $this->get_cache('module');
		$urlrule = $this->get_cache('urlrule');
        
		foreach ($this->SITE as $siteid => $site) {
			$sitename = $site['SITE_NAME'];
			// 主站单页 [page]
			$page = $this->get_cache('page-'.$siteid);
			if ($page['data']['index']) {
				foreach ($page['data']['index'] as $t) {
					$uid = (int)$t['urlrule'];
					if (!$t['module'] && $uid && $urlrule[$uid]) {
						// 不带分页的单页规则
						$t['urlrule'] = $urlrule[$uid]['value']['page'];
						$route['page'][$t['urlrule']] = '';
						if (strpos($t['urlrule'], '{id}') === FALSE &&
						strpos($t['urlrule'], '{dirname}') === FALSE && 
						strpos($t['urlrule'], '{pdirname}') === FALSE) {
							$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 单页【{$t['name']}】的URL规则【".$urlrule[$uid]['name']."】的单页规则：没有包含标签{id}或者{dirname}或者{pdirname}，可能会无法访问</font></td></tr>";
						}
						// 带有分页的单页规则
						$t['urlpage'] = $urlrule[$uid]['value']['page_page'];
						if ($t['urlpage']) {
							$route['page'][$t['urlpage']] = '';
							if ((strpos($t['urlpage'], '{id}') === FALSE &&
							strpos($t['urlpage'], '{dirname}') === FALSE && 
							strpos($t['urlpage'], '{pdirname}') === FALSE) ||
							strpos($t['urlpage'], '{page}') === FALSE) {
								$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 单页【{$t['name']}】的URL规则【".$urlrule[$uid]['name']."】的单页分页规则：没有包含标签{id}或者{dirname}或者{pdirname}，可能会无法访问</font></td></tr>";
							}
						} else {
							$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 单页【{$t['name']}】的URL规则【".$urlrule[$uid]['name']."】没有设置分页规则，分页链接不会生效</font></td></tr>";
						}
					}
				}
			}
			
			// 模块
			if (isset($module[$siteid]) && $module[$siteid]) {
				foreach ($module[$siteid] as $dir) {
				    $m = $this->get_cache('module-'.$siteid.'-'.$dir);
                    if (!$m) continue;
					
					// 模块单页 [module][page]
					if ($page['data'][$dir]) {
						foreach ($page['data'][$dir] as $p) {
							$uid = (int)$t['urlrule'];
							if ($uid && $urlrule[$uid]) {
								// 检查必填标签
								$p['urlrule'] = $urlrule[$uid]['value']['page'];
								$route[$dir]['page'][$p['urlrule']] = '';
								if (strpos($p['urlrule'], '{id}') === FALSE &&
								strpos($p['urlrule'], '{dirname}') === FALSE && 
								strpos($p['urlrule'], '{pdirname}') === FALSE) {
									$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 单页【{$p['name']}】的URL规则【".$urlrule[$uid]['name']."】的单页规则：没有包含标签{id}或者{dirname}或者{pdirname}，可能会无法访问</font></td></tr>";
								}
								$p['urlpage'] = $urlrule[$uid]['value']['page_page'];
								if ($p['urlpage']) {
									$route[$dir]['page'][$p['urlpage']] = '';
									if ((strpos($p['urlpage'], '{id}') === FALSE &&
									strpos($p['urlpage'], '{dirname}') === FALSE && 
									strpos($p['urlpage'], '{pdirname}') === FALSE) || 
									strpos($p['urlpage'], '{page}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 单页【{$p['name']}】的URL规则【".$urlrule[$uid]['name']."】的单页规则：没有包含标签{page}、{id}或者{dirname}或者{pdirname}，可能会无法访问</font></td></tr>";
									}
								} else {
									$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 单页【{$p['name']}】的URL规则【".$urlrule[$uid]['name']."】的单页规则：没有设置分页规则，分页链接不会生效</font></td></tr>";
								}
							}
						}
					}
					
					// 模块栏目
					if ($m['category']) {
						foreach ($m['category'] as $c) {
							$uid = (int)$c['setting']['urlrule'];
							if ($uid && $urlrule[$uid]) {
								// 列表规则
								$_rule = $urlrule[$uid]['value']['list'];
								if ($_rule) {
									$route[$m['dirname']]['list'][$_rule] = '';
									if (strpos($_rule, '{id}') === FALSE &&
									strpos($_rule, '{dirname}') === FALSE && 
									strpos($_rule, '{pdirname}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】的列表规则：没有包含标签{id}，可能会无法访问</font></td></tr>";
									}
								}
								
								// 列表分页规则
								$_rule = $urlrule[$uid]['value']['list_page'];
								if ($_rule) {
									$route[$m['dirname']]['list'][$_rule] = '';
									if ((strpos($_rule, '{id}') === FALSE &&
									strpos($_rule, '{dirname}') === FALSE && 
									strpos($_rule, '{pdirname}') === FALSE) ||
									strpos($_rule, '{page}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】的列表分页规则：没有包含标签{id}或者{dirname}或者{pdirname}，可能会无法访问</font></td></tr>";
									}
								} elseif ($c['setting']['url']['list']) {
									$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】没有设置列表分页规则，列表分页链接不会生效</font></td></tr>";
								}
								
								// 内容URL规则
								$_rule = $urlrule[$uid]['value']['show'];
								if ($_rule) {
									$route[$m['dirname']]['show'][$_rule] = '';
									if (strpos($_rule, '{id}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】的内容规则：没有包含标签{id}，可能会无法访问</font></td></tr>";
									}
								}
								
								// 内容分页URL规则
								$_rule = $urlrule[$uid]['value']['show_page'];
								if ($_rule) {
									$route[$m['dirname']]['show'][$_rule] = '';
									if (strpos($_rule, '{id}') === FALSE || strpos($_rule, '{page}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】的内容规则：没有包含标签{id}、{page}，可能会无法访问</font></td></tr>";
									}
								} elseif ($c['setting']['url']['show']) {
									$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的URL规则【".$urlrule[$uid]['name']."】没有设置内容分页规则，内容分页链接不会生效</font></td></tr>";
								}
								
								// 内容扩展URL规则
								$_rule = $urlrule[$uid]['value']['extend'];
								if ($_rule) {
									$route[$m['dirname']]['extend'][$_rule] = '';
									if (strpos($_rule, '{id}') === FALSE) {
										$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】-> 栏目【{$c['name']}】的的URL规则【".$urlrule[$uid]['name']."】的扩展规则：没有包含标签{id}，可能会无法访问</font></td></tr>";
									}
								}
							}
						}
					}
					
					// 模块tag
					$_rule = $m['setting']['tag']['url'];
					if ($_rule) {
						if ($_rule) {
							$route[$m['dirname']]['tag'][$_rule] = '';
							if (strpos($_rule, '{tag}') === FALSE) {
								$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】的Tag URL规则没有包含标签{tag}，可能会无法访问</font></td></tr>";
							}
						}
						$_rule = $m['setting']['tag']['url_page'];
						if ($_rule) {
							$route[$m['dirname']]['tag'][$_rule] = '';
							if (strpos($_rule, '{tag}') === FALSE && strpos($_rule, '{page}') === FALSE) {
								$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】的Tag URL分页规则没有包含标签{tag}、{page}，可能会无法访问</font></td></tr>";
							}
						} else {
							$string.= "<tr><td align=\"left\"><font color=red>站点【{$sitename}】-> 模块【{$m['name']}】没有设置Tag分页规则，Tag分页链接不会生效</font></td></tr>";
						}
					}
				}
			}
			// 会员部分
		}
		
		// 生成规则到配置文件中
		if ($route) {
			
			foreach ($route as $name => $data) {
				if ($name == 'page') {
					$_data = $_note = array();
					$string.= "<tr><td align=\"left\"><font color=blue>单页路由生成完毕</font></td></tr>";
					foreach ($data as $rule => $t) {
						list($preg, $value) = $this->_rule_preg_value($rule);
						if (!$preg || !$value) {
							$string.= "<tr><td align=\"left\"><font color=red>单页URL（{$rule}）格式不正确</font></td></tr>";
						} elseif (isset($value['{dirname}'])) { // 目录格式
							if (isset($value['{page}'])) {
								// 分页规则
								$_data[$preg] = 'page/index/dir/$'.$value['{dirname}'].'/page/$'.$value['{page}'];
							} else {
								$_data[$preg] = 'page/index/dir/$'.$value['{dirname}'];
							}
						} elseif (isset($value['{pdirname}'])) { // 层次目录格式
							$dir = $value['{pdirname}'];
							if (isset($value['{page}'])) {
								// 分页规则
								$_data[$preg] = 'page/index/dir/$'.$dir.'/page/$'.$value['{page}'];
							} else {
								$_data[$preg] = 'page/index/dir/$'.$dir;
							}
						} else { // id模式
							if (isset($value['{page}'])) {
								// 分页规则
								$_data[$preg] = 'page/index/id/$'.$value['{id}'].'/page/$'.$value['{page}'];
							} else {
								$_data[$preg] = 'page/index/id/$'.$value['{id}'];
							}
						}
						$_note[$preg] = $rule;
					}
					// 生成文件到主站点
					$this->_to_file('', $_data, $_note);
				} elseif ($name == 'member') {
					$string.= "<tr><td align=\"left\"><font color=blue>会员路由生成完毕</font></td></tr>";
				} else {
					// 模块规则需要判断是否有冲突
					$module = array();
					if ($data['list']) {
						foreach ($data['list'] as $rule => $t) {
							list($preg, $value) = $this->_rule_preg_value($rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】列表URL（{$rule}）格式不正确</font></td></tr>";
							} elseif (isset($value['{dirname}'])) { // 目录格式
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'category/index/dir/$'.$value['{dirname}'].'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'category/index/dir/$'.$value['{dirname}'];
								}
							} elseif (isset($value['{pdirname}'])) { // 层次目录格式
								$dir = $value['{pdirname}'];
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'category/index/dir/$'.$dir.'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'category/index/dir/$'.$dir;
								}
							} else { // id模式
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'category/index/id/$'.$value['{id}'].'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'category/index/id/$'.$value['{id}'];
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】栏目路由生成完毕</font></td></tr>";
					}
					
					if ($data['show']) {
						foreach ($data['show'] as $rule => $t) {
							list($preg, $value) = $this->_rule_preg_value($rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】内容URL（{$rule}）格式不正确</font></td></tr>";
							} else {
								if (isset($value['{page}'])) {
									// 分页规则
									if (isset($module[$preg])) {
										$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】内容分页规则（{$rule}）与其他规则有冲突</font></td></tr>";
									} else {
										$module[$preg] = 'show/index/id/$'.$value['{id}'].'/page/$'.$value['{page}'];
									}
								} else {
									if (isset($module[$preg])) {
										$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】内容URL规则（{$rule}）与其他规则有冲突</font></td></tr>";
									} else {
										$module[$preg] = 'show/index/id/$'.$value['{id}'];
									}
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】内容路由生成完毕</font></td></tr>";
					}
					
					if ($data['extend']) {
						foreach ($data['extend'] as $rule => $t) {
							list($preg, $value) = $this->_rule_preg_value($rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】内容扩展URL格式（{$rule}）不正确</font></td></tr>";
							} else {
								if (isset($module[$preg])) {
									$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】内容扩展URL规则（{$rule}）与其他规则有冲突</font></td></tr>";
								} else {
									$module[$preg] = 'extend/index/id/$'.$value['{id}'];
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】内容扩展路由生成完毕</font></td></tr>";
					}
					
					if ($data['tag']) {
						foreach ($data['tag'] as $rule => $t) {
							list($preg, $value) = $this->_rule_preg_value($rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】TAG URL格式（{$rule}）不正确</font></td></tr>";
							} else {
								if (isset($value['{page}'])) {
									// 分页规则
									if (isset($module[$preg])) {
										$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】TAG分页规则（{$rule}）与其他规则有冲突</font></td></tr>";
									} else {
										$module[$preg] = 'tag/index/name/$'.$value['{tag}'].'/page/$'.$value['{page}'];
									}
								} else {
									if (isset($module[$preg])) {
										$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】TAG URL规则（{$rule}）与其他规则有冲突</font></td></tr>";
									} else {
										$module[$preg] = 'tag/index/name/$'.$value['{tag}'];
									}
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】Tag路由生成完毕</font></td></tr>";
					}
					
					if ($data['search']) {
						foreach ($data['search'] as $rule => $t) {
							$_rule = str_replace('{id}', '{kw}', $rule);
							list($preg, $value) = $this->_rule_preg_value($_rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】搜索URL格式（{$rule}）不正确</font></td></tr>";
							} else {
								if (isset($module[$preg])) {
									$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】搜索分页规则（{$rule}）与其他规则有冲突</font></td></tr>";
								} else {
									$module[$preg] = 'search/index/id/$'.$value['{kw}'].'/page/$'.$value['{page}'];
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】搜索路由生成完毕</font></td></tr>";
					}
					
					if ($data['page']) {
						foreach ($data['page'] as $rule => $t) {
							list($preg, $value) = $this->_rule_preg_value($rule);
							if (!$preg || !$value) {
								$string.= "<tr><td align=\"left\"><font color=red>模块【{$name}】单页URL（{$rule}）格式不正确</font></td></tr>";
							} elseif (isset($value['{dirname}'])) { // 目录格式
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'page/index/dir/$'.$value['{dirname}'].'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'page/index/dir/$'.$value['{dirname}'];
								}
							} elseif (isset($value['{pdirname}'])) { // 层次目录格式
								$dir = $value['{pdirname}'];
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'page/index/dir/$'.$dir.'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'page/index/dir/$'.$dir;
								}
							} else { // id模式
								if (isset($value['{page}'])) {
									// 分页规则
									$module[$preg] = 'page/index/id/$'.$value['{id}'].'/page/$'.$value['{page}'];
								} else {
									$module[$preg] = 'page/index/id/$'.$value['{id}'];
								}
							}
							$_note[$preg] = $rule;
						}
						$string.= "<tr><td align=\"left\"><font color=blue>模块【{$name}】单页路由生成完毕</font></td></tr>";
					}
					
					// 生成文件到对应的模块目录
					$this->_to_file($name, $module, $_note);
				}
			}
			$string.= "<tr><td align=\"left\"><font color=red>规则生成完毕，主站及各个模块下的/config/rewrite.php即是规则文件，如果发生指向错误可以在此文件中排查</font></td></tr>";
		} else {
			$string.= "<tr><td align=\"left\"><font color=red>您尚未在整站点中设置URL规则</font></td></tr>";
		}
		
		echo $string;
    }
	
	// 正则解析
	private function _rule_preg_value($rule) {
		
		$rule = trim(trim($rule, '/'));
		
		if (preg_match_all('/\{(.*)\}/U', $rule, $match)) {
		
			$value = array();
			foreach ($match[0] as $k => $v) {
				$value[$v] = $k + 1;
			}
			
			$preg = preg_replace(
				array(
					'#\{id\}#U',
					'#\{page\}#U',
					
					'#\{pdirname\}#Ui',
					'#\{dirname\}#Ui',
					
					'#\{tag\}#U',
					'#\{kw\}#U',
					
					'#\{y\}#U',
					'#\{m\}#U',
					'#\{d\}#U',
					
					'#\{.+}#U',
					'#/#'
				),
				array(
					'(\d+)',
					'(\d+)',
					
					'(.+)',
					'(\w+)',
					
					'(\w+)',
					'(.+)',
					
					'(\d+)',
					'(\d+)',
					'(\d+)',
					
					'(.+)',
					'\/'
				),
				$rule
			);
			
			return array($preg, $value);
		}
		
		return array(0, 0);
	}
	
	// 将规则生成至文件
	private function _to_file($path, $data, $note) {
		
		$file = $path ? FCPATH.$path.'/config/rewrite.php' : FCPATH.'config/rewrite.php';
		
		$string = '<?php'.PHP_EOL.PHP_EOL;
		$string.= 'if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL.PHP_EOL;
		$string.= '// 当生成伪静态时此文件会被系统覆盖；如果发生页面指向错误，可以调整下面的规则顺序；越靠前的规则优先级越高。'.PHP_EOL.PHP_EOL;
		
		if ($data) {
		
			arsort($data);
			foreach ($data as $key => $val) {
				$string.= '$route[\''.$key.'\']'.$this->_space($key).'= \''.$val.'\'; // '.$this->_get_name($val).' 对应规则：'.$note[$key].PHP_EOL;
			}
		}
		
		file_put_contents($file, $string);
	}
	
	// 获取页面名称
	private function _get_name($rule) {
		if (strpos($rule, 'show/index') !== FALSE) {
			return '【内容页】';
		} elseif (strpos($rule, 'category/index') !== FALSE) {
			return '【栏目页】';
		} elseif (strpos($rule, 'extend/index') !== FALSE) {
			return '【扩展页】';
		} elseif (strpos($rule, 'search/index') !== FALSE) {
			return '【搜索页】';
		} elseif (strpos($rule, 'page/index') !== FALSE) {
			return '【单网页】';
		} elseif (strpos($rule, 'tag/index') !== FALSE) {
			return '【标签页】';
		}
	}
	
	/**
	 * 补空格
	 *
	 * @param	string	$name	变量名称
	 * @return	string
	 */
	private function _space($name) {
		$len = strlen($name) + 2;
	    $cha = 40 - $len;
	    $str = '';
	    for ($i = 0; $i < $cha; $i ++) $str .= ' ';
	    return $str;
	}
}