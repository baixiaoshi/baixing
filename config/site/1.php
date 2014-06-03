<?php

/**
 * Dayrui Website Management System
 * 
 * @since			version 2.1.0
 * @author			Dayrui <dayrui@gmail.com>
 * @license     	http://www.dayrui.com/license
 * @copyright		Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

/**
 * 站点配置文件
 */

return array(

	'SITE_NAME'                     => '百姓网', //网站的名称
	'SITE_DOMAIN'                   => 'www.baixing.cc', //网站的域名
	'SITE_MOBILE'                   => 0, //是否开启手机端模板
	'SITE_LANGUAGE'                 => 'zh-cn', //网站的语言
	'SITE_THEME'                    => 'default', //网站的主题风格
	'SITE_TEMPLATE'                 => 'default', //网站的模板目录
	'SITE_TIMEZONE'                 => 8, //所在的时区常量
	'SITE_TIME_FORMAT'              => 'Y-m-d H:i:s', //时间显示格式，与date函数一致，默认Y-m-d H:i:s
	'SITE_TITLE'                    => '百姓网，一个发布信息的需求信息的网站', //网站首页SEO标题
	'SITE_SEOJOIN'                  => '_', //网站SEO间隔符号
	'SITE_KEYWORDS'                 => '关键字', //网站SEO关键字
	'SITE_DESCRIPTION'              => '描述', //网站SEO描述信息
	'SITE_NAVIGATOR'                => '主导航,顶部导航,底部导航,友情链接,首页幻灯,合作伙伴,,,,', //网站导航信息，多个导航逗号分开
	'SITE_ATTACH_REMOTE'            => 0, //是否开启远程附件
	'SITE_ATTACH_HOST'              => 'www.baixing.cc', //附件服务器地址
	'SITE_ATTACH_PORT'              => 21, //附件服务器端口
	'SITE_ATTACH_USERNAME'          => '', //附件服务器用户名
	'SITE_ATTACH_PASSWORD'          => 'dayrui', //附件服务器密码
	'SITE_ATTACH_PATH'              => '/wwwroot', //附件服务器目录
	'SITE_ATTACH_PASV'              => 0, //被动模式(pasv)连接
	'SITE_ATTACH_URL'               => 'www.baixing.cc', //附件远程访问URL
	'SITE_ATTACH_MODE'              => 'auto', //传输模式
	'SITE_ATTACH_EXTS'              => 'jpg,gif,png', //允许的附件扩展名
	'SITE_IMAGE_RATIO'              => 1, //保持原始的纵横比例
	'SITE_IMAGE_WATERMARK'          => 1, //图片水印功能开关
	'SITE_IMAGE_REMOTE'             => 1, //远程附件水印开关
	'SITE_IMAGE_TYPE'               => 0, //图片水印方式
	'SITE_IMAGE_OVERLAY'            => 'default.png', //水印图片
	'SITE_IMAGE_OPACITY'            => 80, //图像不透明度，这将使水印模糊化，从而不会掩盖住底层原始图片的细节
	'SITE_IMAGE_FONT'               => 'default.ttf', //水印字体文件
	'SITE_IMAGE_TEXT'               => 'FineCMS', //水印文字
	'SITE_IMAGE_SIZE'               => 20, //字体大小
	'SITE_IMAGE_COLOR'              => '#9f6a19', //字体颜色
	'SITE_IMAGE_VRTALIGN'           => 'middle', //垂直对齐方式
	'SITE_IMAGE_HORALIGN'           => 'center', //水平对齐方式
	'SITE_IMAGE_VRTOFFSET'          => -15, //垂直偏移量
	'SITE_IMAGE_HOROFFSET'          => -5, //水平偏移量

);