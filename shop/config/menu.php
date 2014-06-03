<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理后台菜单分布
 *
 * array(
 *		'name' => '分组菜单名称',
 *		'menu' => array(
 *			array(
 *				'name' => '链接菜单名称',
 *				'uri' => '链接菜单的uri'
 *			)
 *			......
 *		)
 * )
 * .......
 */


return array(

	// 后台菜单部分
	
	'admin' => array(
		array(
			'name' => '商品管理',
			'menu' => array(
				array(
					'name' => '商品列表',
					'uri' => 'admin/home/index'
				),
				array(
					'name' => '商品分类',
					'uri' => 'admin/category/index'
				),
				array(
					'name' => '属性规格',
					'uri' => 'admin/format/index'
				),
				array(
					'name' => 'Tag标签',
					'uri' => 'admin/tag/index'
				),
				array(
					'name' => '单页管理',
					'uri' => 'admin/page/index'
				),
			),
		),
		array(
			'name' => '交易管理',
			'menu' => array(
				array(
					'name' => '订单管理',
					'uri' => 'admin/order/index'
				),
			),
		),
		array(
			'name' => '相关功能',
			'menu' => array(
				array(
					'name' => '更新地址',
					'uri' => 'admin/home/url'
				),
				array(
					'name' => '生成静态',
					'uri' => 'admin/home/html'
				),
				array(
					'name' => '自定义字段',
					'uri' => 'admin/field/index/rname/module/rid/{id}'
				),
			),
		),
		array(
			'name' => '模板风格',
			'menu' => array(
				array(
					'name' => '模板管理',
					'uri' => 'admin/tpl/index'
				),
				array(
					'name' => '风格管理',
					'uri' => 'admin/theme/index'
				),
			),
		)
	),
	
	//  会员菜单部分
	
	'member' => array(
		array(
			'name' => '我的交易',
			'menu' => array(
				array(
					'name' => '我的订单',
					'uri' => 'order/index',
				),
				array(
					'name' => '收货地址',
					'uri' => 'address/index',
				),
				array(
					'name' => '我收藏的商品',
					'uri' => 'home/favorite',
				),
			),
		),
	),
	
);
