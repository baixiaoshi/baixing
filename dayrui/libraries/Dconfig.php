<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

/**
 * 生成配置文件
 */
 
class Dconfig {
	
	private $note;
	private $file;
	private $space;
	private $header;
	
	/**
	 * 生成配置文件
	 */
	public function __construct() {
		$this->note	 = '';
		$this->space = 32;
	}
	
	/**
	 * 配置文件
	 *
	 * @param	string	$file	文件绝对地址
	 * @return	object
	 */
	public function file($file) {
		$this->file = $file;
		$this->header = '<?php'.PHP_EOL.PHP_EOL.
		'/**'.PHP_EOL.
		' * Dayrui Website Management System'.PHP_EOL.
		' * '.PHP_EOL.
		' * @since			version '.DR_VERSION.PHP_EOL.
		' * @author			Dayrui <dayrui@gmail.com>'.PHP_EOL.
		' * @license     	http://www.dayrui.com/license'.PHP_EOL.
		' * @copyright		Copyright (c) 2011 - 9999, Dayrui.Com, Inc.'.PHP_EOL.
		' */'.PHP_EOL.PHP_EOL
		;
		return $this;
	}
	
	/**
	 * 备注信息
	 *
	 * @param	string	$note	备注
	 * @return	object
	 */
	public function note($note) {
		$this->note = '/**'.PHP_EOL.
		' * '.$note.PHP_EOL.
		' */'.PHP_EOL.PHP_EOL
		;
		return $this;
	}
	
	/**
	 * 空格数量
	 *
	 * @param	int	$num	变量名称与值间的空格数量
	 * @return	object
	 */
	public function space($num) {
		$this->space = $num;
		return $this;
	}
	
	public function to_header() {
		return $this->header.$this->note;
	}
	
	/**
	 * 生成require一维数组文件
	 *
	 * @param	array	$var	变量标识	array('变量名称' => '备注信息'), ...
	 * @param	array	$data	对应值数组	array('变量名称' => '变量值'), ... 为空时直接生成$var
	 * @return	int
	 */
	public function to_require_one($var, $data = NULL) {
		if (!$var) return NULL;
		$body = $this->header.$this->note.'return array('.PHP_EOL.PHP_EOL;
		if ($data) {
			foreach ($var as $name => $note) {
				$val = is_numeric($data[$name]) ? $data[$name] : '\''.$data[$name].'\'';
				$body .= '	\''.$name.'\''.$this->_space($name).'=> '.$val.', //'.$note.PHP_EOL;
			}
		} else {
			foreach ($var as $name => $val) {
				$val = is_numeric($val) ? $val : '\''.$val.'\'';
				$body .= '	\''.$name.'\''.$this->_space($name).'=> '.$val.','.PHP_EOL;
			}
		}
		$body .= PHP_EOL.');';
		if (!is_dir(dirname($this->file))) dr_mkdirs(dirname($this->file));
		return file_put_contents($this->file, $body);
	}
	
	
	/**
	 * 生成require N维数组文件
	 *
	 * @param	array	data
	 * @return	int
	 */
	public function to_require($data) {
		$body = $this->header.$this->note.'return ';
		$body .= str_replace(array('  ', ' 
    '), array('    ', ' '), var_export($data, TRUE));
		$body .= ';';
		if (!is_dir(dirname($this->file))) dr_mkdirs(dirname($this->file));
		return file_put_contents($this->file, $body);
	}
	
	/**
	 * 补空格
	 *
	 * @param	string	$name	变量名称
	 * @return	string
	 */
	private function _space($name) {
		$len = strlen($name) + 2;
	    $cha = $this->space - $len;
	    $str = '';
	    for ($i = 0; $i < $cha; $i ++) $str .= ' ';
	    return $str;
	}
}