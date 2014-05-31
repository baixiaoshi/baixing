<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $meta_title; ?></title>
	<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<!--非授权用户请保留FineCMS的信息-->
	<meta name="author" content="dayrui.com" />
	<meta name="Copyright" content="FineCMS v<?php echo DR_VERSION; ?>" />
  
	<!--关键JS--> 
	<script type="text/javascript">var memberpath = "<?php echo MEMBER_PATH; ?>";</script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/<?php echo SITE_LANGUAGE; ?>.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.artDialog.js?skin=default"></script>
	<script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/dayrui.js"></script>
	<!--引入bootstrap-->
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>news/statics/bootstrap/css/bootstrap-theme.css">
	<script type="text/javascript" src="<?php echo SITE_URL; ?>news/statics/bootstrap/js/bootstrap.js"></script>

	  <link href="<?php echo HOME_THEME_PATH; ?>images/home.css" rel="stylesheet" />

	<!--[if IE 6]>
	<script src="<?php echo HOME_THEME_PATH; ?>js/ie6png.js" type="text/javascript"></script>
	<script type="text/javascript">
	   EvPNG.fix('div, ul, img, li, input'); 
	</script>
	<![endif]-->
 <script type="text/javascript">
  $(function() {
    $('img.image1').data('ad-desc', 'Whoa! This description is set through elm.data("ad-desc") instead of using the longdesc attribute.<br>And it contains <strong>H</strong>ow <strong>T</strong>o <strong>M</strong>eet <strong>L</strong>adies... <em>What?</em> That aint what HTML stands for? Man...');
    $('img.image1').data('ad-title', 'Title through $.data');
    $('img.image4').data('ad-desc', 'This image is wider than the wrapper, so it has been scaled down');
    $('img.image5').data('ad-desc', 'This image is higher than the wrapper, so it has been scaled down');
    var galleries = $('.ad-gallery').adGallery();
    $('#switch-effect').change(
      function() {
        galleries[0].settings.effect = $(this).val();
        return false;
      }
    );
    $('#toggle-slideshow').click(
      function() {
        galleries[0].slideshow.toggle();
        return false;
      }
    );
    $('#toggle-description').click(
      function() {
        if(!galleries[0].settings.description_wrapper) {
          galleries[0].settings.description_wrapper = $('#descriptions');
        } else {
          galleries[0].settings.description_wrapper = false;
        }
        return false;
      }
    );
  });
  </script>


	
    <script type="text/javascript">
    $(function(){
		// 回到顶部
		$("#back-to-top").hide();
		$(window).scroll(function(){
		 if ($(window).scrollTop()>100){
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
	
	function dr_head_show_hide(){
		$("#dr_headSel").toggle();
	}
	
	</script>
</head>
<body>
<div id="back-to-top" style="display: block;"><a href="#top">TOP</a></div>
<!--网站头部 begin-->
<div class="head">
	<div class="idx" style="margin-top:0px">
        <div class="l">
            <div id="loginForm">
                <div id="haslogin"><script type="text/javascript" src="<?php echo SITE_URL; ?>member/index.php?c=api&m=userinfo"></script></div>
            </div>
        </div>
</div>
<div class="ilogo">
	<div class="logo"><a href="<?php echo SITE_URL; ?>" title="<?php echo SITE_TITLE; ?>"><img src="<?php echo HOME_THEME_PATH; ?>images/logo.gif" /></a></div>
	<div class="search_box">
		<input type="text" class="form-control" value="搜索">
		<span class="glyphicon glyphicon-search"></span>
	</div>

</div>
<div class="nav">
    <ul class="dr_nav">
		<li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">网站首页</a></li>
        <!---调用type=0的网站导航数据-->
        <!--  <?php $return = $this->list_tag("action=navigator type=0 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
       <li id="dr_nav_<?php echo $t['id']; ?>"><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['name']; ?></a></li> 
        <?php } } ?>-->
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">跑腿公司</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">校园信息</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">社会信息</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">时间当铺</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">求职招聘</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">求职招聘</a></li>
       <li id="dr_nav_0"><a href="<?php echo SITE_PATH; ?>">求职招聘</a></li>
	</ul>
</div>
<!--网站头部 end-->