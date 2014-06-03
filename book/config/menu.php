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
			'name' => '图书管理',
			'menu' => array(
				array(
					'name' => '已通过图书',
					'uri' => 'admin/home/index'
				),
				array(
					'name' => '待审核图书',
					'uri' => 'admin/home/verify'
				),
				array(
					'name' => '栏目分类',
					'uri' => 'admin/category/index'
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
				array(
					'name' => '自定义章节字段',
					'uri' => 'admin/field/index/rname/extend/rid/{id}'
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
				array(
					'name' => '标签向导',
					'uri' => 'admin/tpl/tag'
				),
			),
		)
	),
	
	//  会员菜单部分
	
	'member' => array(
		array(
			'name' => '图书管理',
			'menu' => array(
				array(
					'name' => '已通过的图书',
					'uri' => 'home/index',
				),
				array(
					'name' => '待审核的图书',
					'uri' => 'verify/index',
				),
				array(
					'name' => '被退回的图书',
					'uri' => 'back/index',
				),
				array(
					'name' => '已推荐的图书',
					'uri' => 'home/flag',
				),
				array(
					'name' => '我收藏的图书',
					'uri' => 'home/favorite',
				),
				array(
					'name' => '我购买的图书',
					'uri' => 'home/buy',
				),
			)
		)
	),
	
);