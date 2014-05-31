<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>admin</title>
<link href="<?php echo SITE_URL; ?>dayrui/statics/css/index.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_URL; ?>dayrui/statics/css/table_form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var siteurl = "<?php echo SITE_PATH;  echo SELF; ?>";var memberpath = "<?php echo MEMBER_PATH; ?>";</script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/<?php echo SITE_LANGUAGE; ?>.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/jquery.artDialog.js?skin=default"></script> 
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/validate.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/admin.js"></script>
<script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/dayrui.js"></script>
<script type="text/javascript">
$(function() {
	$(".table-list tr").last().addClass("dr_border_none");
	$(".subnav .content-menu span").last().remove();
	art.dialog.close();
	$("input[name='dr_select']").click(function(){
		$(".dr_select").attr("checked",$(this).attr("checked"));
	});
}); 
</script>
</head>
<body>