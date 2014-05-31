<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class City_model extends C_Model {

	/*
	 * 构造函数
	 */
    public function __construct() {
        parent::__construct();
    }
    public function get_provinces() {
       $provinces = $this->db->where('parentid',0)->get('district')->result_array();
        return $provinces;
    }
    /**
     * 获取获取城市列表
     */
    public function city_list(){
        //获取省份
        $provinces = $this->get_provinces();
        $city_list_array = array();
        foreach($provinces as $k=>$v){
            $city_list_array[$k]= $this->db->where('parentid='.trim($v['id']))->get('district')->result_array();
        }
        return $city_list_array;
    }
	
}