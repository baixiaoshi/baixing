<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/news/core/M_Controller.php
 */
	
require FCPATH.'dayrui/core/D_Module.php';

class M_Controller extends D_Module {

    /**
     * 构造函数继承公共Module类
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 规格缓存数据
     */
	protected function _get_format() {
	
		$data = $this->get_cache('format-'.SITE_ID);
		
		if (!$data) {
			$this->load->model('format_model');
			$data = $this->format_model->cache();
		}
		
		return $data;
	}
	
	/**
     * 动态组合商品规格
     */
	protected function _format_value() {
	
		$catid = (int)$this->input->post('catid');
		$check = $this->input->post('value');
		if (!$check) exit('<div class="onError">您需要选择所有的类目下的某一个属性，才能组合成完整的规格信息</div>');
		
		$format = $this->_get_format();
		$data = $format[$catid]['data'];
		$list = $format[$catid]['list'];
		if (!$list) exit('0');
		
		$value = $this->input->post('data');
		$check = @explode(',', $check);
		if (!$check) exit('<div class="onError">您需要选择所有的类目下的某一个属性，才能组合成完整的规格信息</div>');
		
		// 格式化选中的选项
		$checked = array();
		foreach ($check as $t) {
			if ($t) {
				list($pid, $id) = explode('|', $t);
				$checked[$pid][] = $id;
			}
		}
		$total = count($checked);
		if (count($list) != $total) exit('<div class="onError">您需要选择所有的类目下的某一个属性，才能组合成完整的规格信息</div>');
		
		// 组合商品规格
		$merge = $this->_sku($total, $checked);
		echo '<div class="dr_format_table" style="max-height:350px;overflow-x: hidden;overflow-y: auto;width: auto;">';
		echo '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
		echo '<tr>';
		foreach ($checked as $pid => $v) {
			echo '<th scope="col">'.$data[$pid]['name'].'</th>';
		}
		echo '<th scope="col">价格</th>';
		echo '<th scope="col">数量</th>';
		echo '<th scope="col">商品编号</th>';
		echo '</tr>';
		foreach ($merge as $val) {
			echo '<tr>';
			$v = explode('-', $val);
			foreach ($v as $id) {
				echo '<td>';
				echo $data[$id]['name'];
				echo '</td>';
			}
			echo '<td><input type="text" style="width:50px;margin:0;" value="'.$value['price'][$val].'" name="data[format][price]['.$val.']" class="input-text"></td>';
			echo '<td><input type="text" style="width:50px;margin:0;" value="'.$value['quantity'][$val].'" name="data[format][quantity]['.$val.']" class="input-text"></td>';
			echo '<td><input type="text" style="width:150px;margin:0;" value="'.$value['number'][$val].'" name="data[format][number]['.$val.']" class="input-text"></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}
	
	private function _sku($total, $checked) {
	
		$checked = array_values($checked);
		
		switch ($total) {
			case 1:
				$data = array();
				foreach ($checked[0] as $id1) {
					$data[] = $id1;
				}
				return $data;
				break;
				
			case 2:
				$data = array();
				foreach ($checked[0] as $id1) {
					foreach ($checked[1] as $id2) {
						$data[] = $id1.'-'.$id2;
					}
				}
				return $data;
				break;
				
			case 3:
				$data = array();
				foreach ($checked[0] as $id1) {
					foreach ($checked[1] as $id2) {
						foreach ($checked[2] as $id3) {
							$data[] = $id1.'-'.$id2.'-'.$id3;
						}
					}
				}
				return $data;
				break;
				
			case 4:
				$data = array();
				foreach ($checked[0] as $id1) {
					foreach ($checked[1] as $id2) {
						foreach ($checked[2] as $id3) {
							foreach ($checked[3] as $id4) {
								$data[] = $id1.'-'.$id2.'-'.$id3.'-'.$id4;
							}
						}
					}
				}
				return $data;
				break;
				
			case 5:
				$data = array();
				foreach ($checked[0] as $id1) {
					foreach ($checked[1] as $id2) {
						foreach ($checked[2] as $id3) {
							foreach ($checked[3] as $id4) {
								foreach ($checked[4] as $id5) {
									$data[] = $id1.'-'.$id2.'-'.$id3.'-'.$id4.'-'.$id5;
								}
							}
						}
					}
				}
				return $data;
				break;
		}
	}
	
	/**
     * 动态调用栏目规格
     */
	protected function _format() {
		
		$catid = (int)$this->input->post('catid');
		$format = $this->_get_format();
		$data = $format[$catid]['data'];
		$list = $format[$catid]['list'];
		if (!$list) exit('0');
		
		$value = dr_string2array(dr_string2array($this->input->post('data')));
		
		echo '<div class="dr_format_wrap">';
		echo '	<div class="dr_format_group">';
		foreach ($list as $pid => $v) {
			echo '<label class="dr_format_label">'.$data[$pid]['name'].'：</label>';
			echo '<ul class="dr_format_list">';
			foreach ($v as $id) {
				echo '<li>';
				echo '	<input type="checkbox" '.(@in_array($id, $value['id']) ? 'checked' : '').' value="'.$id.'" pid="'.$pid.'" name="data[format][id][]" class="dr_format_checkbox" />';
				echo '	<label title="'.$data[$id]['name'].'" class="labelname">'.$data[$id]['name'].'</label>';
				echo '</li>';
			}
			echo '</ul>';
		}
		echo '</div>';		
		echo '</div>';
		echo '<div class="dr_format_wrapper">
				<div id="dr_format_value" class="dr_format_map">
					<img src="'.SITE_URL.'dayrui/statics/images/loading.gif" />
				</div>
			</div>
			<script type="text/javascript">
			$(function() {
				dr_format_load_value();
				$(".dr_format_checkbox").click(function(){
					dr_format_load_value();
				});
				// 移除编号、价格与数量字段
				$("#dr_row_price").hide();
				$("#dr_price").attr("required", false);
				$("#dr_row_number").hide();
				$("#dr_quantity").attr("required", false);
				$("#dr_row_quantity").hide();
			});
			function dr_format_load_value() {
				var value = "";
				$(".dr_format_checkbox").each(function(){
					if ($(this).attr("checked")) {
						value+= ","+$(this).attr("pid")+"|"+$(this).attr("value");
					}
				});
				$.post("'.dr_url(APP_DIR.'/home/format_value').'&"+Math.random(),{ catid:'.$catid.', value:value, data:'.json_encode($value).' }, function(data){
					$("#dr_format_value").html(data);
				});
			}
			</script>
			';
	}
	
}