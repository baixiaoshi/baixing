<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class F_Baidumap extends A_Field {
	
	/**
     * 构造函数
     */
    public function __construct() {
		parent::__construct();
		$this->name = IS_ADMIN ? lang('264') : ''; // 字段名称
		$this->fieldtype = array('INT' => 10); // TRUE表全部可用字段类型,自定义格式为 array('可用字段类型名称' => '默认长度', ... )
		$this->defaulttype = 'INT'; // 当用户没有选择字段类型时的缺省值
    }
	
	/**
	 * 字段相关属性参数
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function option($option) {
	
		$option['city'] = isset($option['city']) ? $option['city'] : '';
		$option['level'] = isset($option['level']) ? $option['level'] : 15;
		$option['width'] = isset($option['width']) ? $option['width'] : 700;
		$option['height'] = isset($option['height']) ? $option['height'] : 430;
		
		return '<tr>
                    <th>'.lang('265').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][width]" value="'.$option['width'].'">
					<div class="onShow">px</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('266').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][height]" value="'.$option['height'].'">
					<div class="onShow">px</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('267').'：</th>
                    <td>
                    <input type="text" class="input-text" size="10" name="data[setting][option][level]" value="'.$option['level'].'">
					<div class="onShow">'.lang('268').'</div>
                    </td>
                </tr>
				<tr>
                    <th>'.lang('269').'：</th>
                    <td>
                    <input type="text" class="input-text" size="40" name="data[setting][option][city]" value="'.$option['city'].'">
					<div class="onShow">'.lang('270').'</div>
                    </td>
                </tr>
				';
	}
	
	/**
	 * 创建sql语句
	 */
	public function create_sql($name, $option) {
		$sql = 'ALTER TABLE `{tablename}` ADD `'.$name.'_lng` DECIMAL(9,6) NULL , ADD `'.$name.'_lat` DECIMAL(9,6) NULL';
		return $sql;
	}
	
	/**
	 * 修改sql语句
	 */
	public function alter_sql($name, $option) {
		return NULL;
	}
	
	/**
	 * 删除sql语句
	 */
	public function drop_sql($name) {
		$sql = 'ALTER TABLE `{tablename}` DROP `'.$name.'_lng`, DROP `'.$name.'_lat`';
		return $sql;
	}
	
	/**
	 * 字段入库值
	 */
	public function insert_value($field) {
		
		if ($this->ci->post[$field['fieldname']]) {
			$map = explode(',', $this->ci->post[$field['fieldname']]);
			$this->ci->data[$field['ismain']][$field['fieldname'].'_lng'] = $map[0];
			$this->ci->data[$field['ismain']][$field['fieldname'].'_lat'] = $map[1];
		} else {
			$this->ci->data[$field['ismain']][$field['fieldname'].'_lng'] = 0;
			$this->ci->data[$field['ismain']][$field['fieldname'].'_lat'] = 0;
		}
		
	}
	
	/**
	 * 字段值
	 */
	public function get_value($name, $data) {
		return $data[$name.'_lng'] > 0 || $data[$name.'_lat'] > 0 ? $data[$name.'_lng'].','.$data[$name.'_lat'] : '';
	}
	
	/**
	 * 字段输出
	 *
	 * @param	array	$value	值
	 * @return  string
	 */
	public function output($value) {
		
	}
	
	/**
	 * 字段表单输入
	 *
	 * @param	string	$cname	字段别名
	 * @param	string	$name	字段名称
	 * @param	array	$cfg	字段配置
	 * @param	array	$data	值
	 * @return  string
	 */
	public function input($cname, $name, $cfg, $value = NULL, $id = 0) {
		// 字段显示名称
		$text = (isset($cfg['validate']['required']) && $cfg['validate']['required'] == 1 ? '<font color="red">*</font>' : '').'&nbsp;'.$cname.'：';
		// 宽度设置
		$width = isset($cfg['option']['width']) && $cfg['option']['width'] ? $cfg['option']['width'] : 700;
		// 高度设置
		$height = isset($cfg['option']['height']) && $cfg['option']['height'] ? $cfg['option']['height'] : 430;
		// 城市设置
		$city = isset($cfg['option']['city']) && $cfg['option']['city'] ? $cfg['option']['city'] : '';
		// 显示范围
		$level = isset($cfg['option']['level']) && $cfg['option']['level'] ? $cfg['option']['level'] : 15;
		// 表单附加参数
		$attr = isset($cfg['validate']['formattr']) && $cfg['validate']['formattr'] ? $cfg['validate']['formattr'] : '';
		// 字段提示信息
		$tips = isset($cfg['validate']['tips']) && $cfg['validate']['tips'] ? '<div class="onShow" id="dr_'.$name.'_tips">'.$cfg['validate']['tips'].'</div>' : '';
		// 地图默认值
		$value && list($lng, $lat) = explode(',', $value);
		$value = $value == '0,0' ? '' : $value;
		// 字段默认值传递到本站api
		$str = '<script type="text/javascript">
				function map_'.$name.'_mark() {
					art.dialog.open("'.(MEMBER_PATH.'index.php?c=api&m=baidumap&width='.$width.'&height='.$height.'&name='.$name.'&level='.$level.'&value='.$value.'&city='.urlencode($city)).'", {
						title: "BaiduMap",
						opacity: 0.1,
						width:'.$width.',
						height:'.$height.',
						ok: function () {
						var iframe = this.iframe.contentWindow;
						if (!iframe.document.body) {
							alert("iframe loading")
							return false;
						};
						var value = iframe.document.getElementById("'.$name.'").value,
							old = "'.$value.'";
							if (value == "") {
								$("#result_'.$name.'").html("<font color=green>'.lang('m-068').'</font>");
							} else if (value != old) {
								$("#result_'.$name.'").html("<font color=blue>'.lang('m-068').'</font>");
							} else {
								$("#result_'.$name.'").html("<font color=red>'.lang('m-069').'</font>");
								return true;
							}
							$("#dr_'.$name.'").val(value);
							return true;
						},
						cancel: true
					});
				}
				</script>
				<input type="button" name="'.$name.'_mark" onclick="map_'.$name.'_mark()" id="'.$name.'_mark" value="'.lang('m-070').'" class="button" />
				<input name="data['.$name.']" id="dr_'.$name.'" type="hidden" value="'.$value.'">
				<span id="result_' . $name . '"></span>'.$tips;
				
		return $this->input_format($name, $text, $str);
	}
	
}