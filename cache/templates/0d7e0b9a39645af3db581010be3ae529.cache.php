<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $meta_title; ?></title>
	<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<!--非授权用户请保留FineCMS的信息-->
  
	<!--关键JS--> 
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/<?php echo SITE_LANGUAGE; ?>.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.artDialog.js?skin=default"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/validate.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/admin.js"></script>
	
	<!--引入bootstrap-->
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap-theme.css">
	<script type="text/javascript" src="<?php echo SITE_URL; ?>news/statics/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/dayrui.js"></script>
	<script type="text/javascript" src="<?php echo SITE_URL; ?>news/statics/js/common.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/css/common.css">
	<script type="text/javascript">
		$(function() {
			SwapTab(0);
			$(".table_form tr>th").attr("width", 200);
				var catid = $("#dr_catid").val();
			if (catid) {
				show_category_field(catid);
			}
		});
		function show_category_field(catid) {
			$.post(siteurl+'?s=news&c=category&m=field&rand='+Math.random(),{ catid:catid, data:"" }, function(data){
				$('#dr_category_field').html(data);
			});
		}
	</script>
	<!--[if IE 6]>
	<script src="<?php echo HOME_THEME_PATH; ?>js/ie6png.js" type="text/javascript"></script>
	<script type="text/javascript">
	   EvPNG.fix('div, ul, img, li, input'); 
	</script>
	<![endif]-->
</head>
<body>
<!--网站头部 begin-->
<div class="head">
	<div class="head_box">
	    <div id="loginForm">
	        <div id="haslogin">
	   
	       		<script type="text/javascript" src="<?php echo SITE_URL; ?>member/index.php?c=api&m=userinfo"></script>
	        </div>
	    </div>
    </div>
</div>
<div class="logo_box">
	<!--logo-->
	<div class="logo">
		<a href="<?php echo SITE_URL; ?>" title="<?php echo SITE_TITLE; ?>">
			<img src="<?php echo HOME_THEME_PATH; ?>images/logo.gif" />
		</a>
	</div>
	<!--搜索框-->
	<div class="search_wrap">
		<div class="search_box">
			<input type="text" class="form-control" value="搜索">
			<span class="glyphicon glyphicon-search"></span>
		</div>
		<!--热搜索词-->
		<div class="hotkey">
			热搜词:
			<?php $return = $this->list_tag("action=tag module=news num=8"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
				<a href="<?php echo $t['url']; ?>" title="点击量：<?php echo $t['hits']; ?>"><?php echo $t['name']; ?></a>
			<?php } }  echo $error; ?>
		</div>
	</div>
	<!--发布信息按钮-->
	<div class="fabu">
		<button type="button" class="btn btn-warning">
		<span class="glyphicon glyphicon-pencil"></span>免费发布信息</button>
	</div>

	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="site_map">
	<ol class="breadcrumb">
	  <li><a href="<?php echo SITE_URL; ?>news">主页</a></li>
	  <li class="active">发布信息</li>
	</ol>
</div>



<div class="content_box">
	<div class="content_tit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;基本信息</div>
	
	<form class="form-horizontal" role="form" action="" method="post" name="myform" id="myform">

	<input name="action" id="dr_action" type="hidden" value="back" />
	<input name="backurl" type="hidden" value="<?php echo MODULE_URL; ?>index.php?s=news&c=info&m=add_info" />
	<div class="field_box">

	 	<table class="table">
	 		<tr><th>&nbsp;&nbsp;类别:</th><td><?php echo $select; ?></td></tr>
	 		<tr><th>区域:</th><td><span class="form_provinces"><?php echo $provinces; ?></span>&nbsp;&nbsp;&nbsp;<span class="citys"></span>&nbsp;&nbsp;&nbsp;<span class="areas"></span></td></tr>
	 		<?php echo $myfield; ?>
	 		<tr>

	 	<th></th>
	 	<td>
			 <div class="fixed-but text-c">
		        <div class="button">
		        	<input value="发布信息" type="submit" name="submit" class="cu btn btn-warning" onclick="$('#dr_action').val('back')" style="width:100px;" />
		        </div>
		        </div>
			</div>
		</td>
		</tr>
	 	</table>

	</div>
	</form>


</div>
<script type="text/javascript" src="http://www.baixing.cc/index.php?c=cron"></script>