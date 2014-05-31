<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Category extends M_Controller {

	private $thumb;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->_is_space();
		$this->thumb = array(
			array(
				'ismain' => 1,
				'fieldtype' => 'File',
				'fieldname' => 'thumb',
				'setting' => array(
					'option' => array(
						'size' => 10,
						'ext' => 'jpg,gif,png',
					)
				)
			)
		);
		$this->load->model('space_category_model');
    }
	
    /**
     * 首页
     */
    public function index() {
	
		if (IS_POST) {
			
			$ids = $this->input->post('ids', TRUE);
			
			if ($this->input->post('action') == 'order') {
				if (!$ids) exit(dr_json(0, lang('013')));
				$data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->db
						 ->where('id', (int)$id)
						 ->where('uid', (int)$this->uid)
						 ->update($this->space_category_model->tablename, $data[$id]);
				}
				exit(dr_json(1, lang('000')));
			} elseif ($this->input->post('action') == 'del') {
				if (!$ids) exit(dr_json(0, lang('013')));
				$this->space_category_model->del($ids);
				exit(dr_json(1, lang('000')));
			}
		}
	
		$this->load->library('dtree');
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
		$tree = array();
		$this->space_category_model->repair($this->uid);
		$data = $this->space_category_model->get_data(0, 0, 1);
		
		if ($data) {
			foreach($data as $t) {
				
				switch ($t['showid']) {
					case 0:
						$t['show'] = lang('m-293');
						break;
					case 1:
						$t['show'] = lang('m-294');
						break;
					case 2:
						$t['show'] = lang('m-295');
						break;
					case 3:
						$t['show'] = lang('m-296');
						break;
				}
				
				switch ($t['type']) {
					case 0:
						$t['model'] = lang('m-313');
						break;
					case 1:
						$t['model'] = $this->get_cache('space-model', $t['modelid'], 'name');
						break;
					case 2:
						$t['model'] = lang('m-314');
						break;
				}
				
				$t['option'] = '<a href="'.dr_space_list_url($this->uid, $t['id']).'" target="_blank">'.lang('m-345').'</a>';
				$t['add'] = $t['type'] ? "&nbsp;&nbsp;<a href='".dr_member_url('category/add')."&pid=".$t['id']."&type=".$t['type']."&mid=".$t['modelid']."'>[ ".lang('m-298')." ]</a>" : '';
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<tr class='dr_border_none' style='height:35px;'>";
		$str.= "<td align='right'><input name='ids[]' type='checkbox' class='dr_select' value='\$id' /></td>";
		$str.= "<td align='center'><input class='input-text displayorder' type='text' name='data[\$id][displayorder]' value='\$displayorder' /></td>";
		$str.= "<td class='ajax'>\$spacer<a href='".dr_member_url('category/edit')."&id=\$id'>\$name</a>  \$add</td>";
		$str.= "<td align='center'>\$model</td>";
		$str.= "<td align='center'>\$show</td>";
		$str.= "<td align='center'>\$option</td>";
		$str.= "</tr>";
		
		$this->dtree->init($tree);
		
		$this->template->assign(array(
			'list' => $this->dtree->get_tree(0, $str),
            'page' => (int)$this->input->get('page')
		));
		$this->template->display('category_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		$data = array(
			'pid' => (int)$this->input->get('pid'),
			'type' => (int)$this->input->get('type'),
			'showid' => 3,
			'modelid' => (int)$this->input->get('mid'),
		);
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$result = $this->space_category_model->add($data);
			if ($result === TRUE) {
				$this->member_msg(lang('000'), dr_member_url('category/index'), 1);
			}
			if (IS_AJAX) exit(dr_json(0, $result));
		} else {
			$result	= '';
		}
		
		$this->template->assign(array(
			'data' => $data,
			'result' => $result,
			'method' => $this->router->method,
			'meta_name' => lang('m-291'),
		));
		$this->template->display('category_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->space_category_model->get($id);
		if (!$data)	$this->member_msg(lang('019'));
		
		if (IS_POST) {
			$post = $this->input->post('data', TRUE);
			$post['type'] = $data['type']; 
			$post['modelid'] = $data['modelid']; 
			$result	= $this->space_category_model->edit($id, $post);
			if ($result === TRUE) $this->member_msg(lang('000'), dr_member_url('category/index'), 1);
			$post['id'] = $id;
			$data = $post;
			if (IS_AJAX) exit(dr_json(0, $result));
		} else {
			$result	= '';
		}
		
		$this->template->assign(array(
			'data' => $data,
			'result' => $result,
			'method' => $this->router->method,
		));
		$this->template->display('category_add.html');
	}
	
	/**
     * 栏目分类
     */
    public function select() {
	
		$pid = (int)$this->input->get('pid');
		$mid = (int)$this->input->get('mid');
		$type = (int)$this->input->get('type');
		
		$this->db->where('uid', (int)$this->uid);
		
		switch ($type) {
			
			case 0: // 外链
				$this->db->where('type>', 0);
				break;
			
			case 1: // 模型
				$this->db->where('(type=1 and modelid='.$mid.') or type=2');
				break;
				
			case 2: // 单页
				$this->db->where('type>', 0);
				break;
		}
		
		$data = $this->db->get($this->db->dbprefix('space_category'))->result_array();
		
		echo $this->select_space_category($data, $pid, 'name=\'data[pid]\'', lang('m-297'));
    }
	
}