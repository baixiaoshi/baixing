<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class City extends M_Controller{
	/**
	 * 继承父类的构造函数
	 */
	public function __construct(){
		parent::__construct();
	}

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
}
