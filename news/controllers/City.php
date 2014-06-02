<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class City extends M_Controller{
	/**
	 * 继承父类的构造函数
	 */
	public function __construct(){
		parent::__construct();
	}
    /**
     * 切换城市页面
     */
	public function get_city_list(){
		$result = $this->db->where('id<=',532)->order_by('path')->get('district')->result_array();
     
        foreach($result as $k=>$v){
           
            if($v['parentid'] == 0){
                $html .= '<br/><span class="provinces">';
                $html .= $v['name'];
                $html .= '</span>';
            }else{
                $html .= '<a class="city">';
                $html .= $v['name'];
                $html .= '</a>';
            }
           
        }

        $this->template->assign('html',$html);
        $this->template->display('city.html');
	}

    /**
     * ajax获取城市列表
     */
    public function getcity(){
        $id = $this->input->post('id');
        //获取parentid的城市
        $citys = $this->db->where('parentid',$id)->get('district')->result_array();
        $html = '<select id="form_citys" onchange="getcitys(this.value)" name="data[city]">';
        foreach($citys as $k=>$v){
            $html .= '<option value='.$v['id'].','.$v['name'].'>'.$v['name'].'</option>';
        }
        $html .= '</select>';
        echo $html;
    }
     /**
     * ajax获取县列表
     */
    public function getarea(){
        $id = $this->input->post('id');
        //获取parentid的城市
        $areas = $this->db->where('parentid',$id)->get('district')->result_array();
        if(empty($areas)) return;
        $html = '<select id="form_citys" name="data[area]" >';
        foreach($areas as $k=>$v){
            $html .= '<option value='.$v['id'].','.$v['name'].'>'.$v['name'].'</option>';
        }
        $html .= '</select>';
        echo $html;
    }
}
