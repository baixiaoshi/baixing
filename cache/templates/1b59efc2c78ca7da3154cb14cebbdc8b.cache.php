<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $meta_name; ?>-<?php echo SITE_NAME; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo SITE_URL; ?>dayrui/statics/css/table_form.css" rel="stylesheet" type="text/css" />
<link href="<?php echo MEMBER_PATH; ?>statics/<?php echo MEMBER_THEME; ?>/images/reset.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo MEMBER_PATH; ?>statics/<?php echo MEMBER_THEME; ?>/images/member.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo MEMBER_PATH; ?>statics/OAuth/OAuth.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var memberpath = "<?php echo MEMBER_PATH; ?>";</script>
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/<?php echo SITE_LANGUAGE; ?>.js"></script>
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.artDialog.js?skin=default"></script> 
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/validate.js"></script>
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/admin.js"></script>
<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/dayrui.js"></script>

	<!--引入bootstrap-->
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap-theme.css">
	<script type="text/javascript" src="<?php echo SITE_URL; ?>news/statics/bootstrap/js/bootstrap.js"></script>
	<link href="<?php echo MEMBER_PATH; ?>statics/<?php echo MEMBER_THEME; ?>/images/member.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(function(){
	$(".account").bind({
		'mouseover':function(){
			$(this).addClass("account_mouseover");
		},
		'mouseout':function(){
			$(this).removeClass('account_mouseover');
		}
	});
	$("#back-to-top").hide();
	$(window).scroll(function() {
		if ($(window).scrollTop()>100) {
			$("#back-to-top").fadeIn(1500);
		} else {
			$("#back-to-top").fadeOut(1500);
		}
	});
	$("#back-to-top").click(function(){
		$('body,html').animate({scrollTop:0},1000);
		return false;
	});
});

var dr_url = "<?php echo MEMBER_URL; ?>index.php?c=api&m=notice&"+Math.random();
var dr_step = 0;
var dr_callbacktitle = "【新提醒】"+document.title;
var dr_caltitle = "【　　　】"+document.title;

$.ajax({type: "GET", url:dr_url, dataType:'jsonp',jsonp:"callback",async: false,
	success: function (data) {
		if (data.status) {
			$("#dr_notece_img").show();
			dr_flash_title();
		} else {
			$("#dr_notece_img").hide();
		}
	},
	error: function(HttpRequest, ajaxOptions, thrownError) {
		
	}
});

function dr_flash_title() {
	dr_step++;
	if (dr_step==3) {
		dr_step=1;
	}
	if (dr_step==1) {
		document.title=dr_callbacktitle;
	}
	if (dr_step==2) {
		document.title=dr_caltitle;	
	}
	setTimeout("dr_flash_title()", 500);
}
</script>
</head>
<body>
<div class="topnav_w">
    <div class="topnav">
		<!--获取城市信息-->
		<a target="_blank" href="<?php echo SITE_URL; ?>" class="site">网站首页</a>
	 <span id="city"><?php echo get_city(); ?></span>[<a href="<?php echo SITE_URL; ?>news/index.php?c=city&m=get_city_list">切换城市</a>]
    	
        <div class="login">
        <?php if ($member) { ?>
            <strong>欢迎回来，<?php echo $member['username']; ?>，<?php echo $member['group']['name']; ?>，<?php echo $member['level']['name']; ?>，<a href="<?php echo MEMBER_URL; ?>index.php?c=pm"><?php if ($newpm) { ?><img src="<?php echo MEMBER_THEME_PATH; ?>images/new_pm.gif" align="absmiddle" style="margin-right:3px;" /><?php } ?>短消息</a>，<a href="<?php echo MEMBER_URL; ?>index.php?c=notice"><img id="dr_notece_img" src="<?php echo MEMBER_THEME_PATH; ?>images/notice.gif" align="absmiddle" style="margin-right:3px;display:none" />提醒</a></strong>
            <div class="account"><span>账户</span> 
                <ul>
                    <li><a href="<?php echo dr_member_url('account/index'); ?>">基本资料</a></li>
                    <li><a href="<?php echo dr_member_url('account/password'); ?>">修改密码</a></li>
                    <li><a href="<?php echo dr_member_url('account/avatar'); ?>">上传头像</a></li>
                    <li><a href="<?php echo dr_member_url('login/out'); ?>">退出</a></li>
                </ul>
            </div>
        <?php } else { ?>
        	<a class="bt_l" href="<?php echo dr_member_url('login/index'); ?>">登录</a>
       		<a class="bt_r" href="<?php echo dr_member_url('register/index'); ?>">注册</a>
        <?php } ?>
        </div>
    </div>
</div>
<div class="header">
    <div class="logo"><a href="<?php echo SITE_URL; ?>">FineCMS</a> </div>
    <!--搜索框-->
	<div class="search_wrap">
		<div class="search_box">
			<input type="text" class="form-control" value="搜索">
			<span class="glyphicon glyphicon-search"></span>
		</div>

		<div class="hotkey">
			热搜词:
			<?php $return = $this->list_tag("action=tag module=news num=8"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
				<a href="<?php echo $t['url']; ?>" title="点击量：<?php echo $t['hits']; ?>"><?php echo $t['name']; ?></a>
			<?php } }  echo $error; ?>
		</div>
	</div>
	<div class="fabu">
		<button type="button" class="btn btn-warning">
		<span class="glyphicon glyphicon-pencil"></span>免费发布信息</button>
	</div>

	<div class="clearfix"></div>
</div>

<div id="back-to-top" style="display: block;"><a href="#top">TOP</a></div>