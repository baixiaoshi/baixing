<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	<?php if ($error === 1) { ?>
	dr_tips("<?php echo lang('m-101'); ?>", 3, 1);
	<?php }  if (strlen($error) > 3) { ?>
	dr_tips("<font color=red><?php echo $error; ?></font>", 3);
	<?php } ?>
	
});
function validOldPwd() {
	$.post('<?php echo dr_member_url('account/cpassword'); ?>', {password: $("#oldPwd").val()}, function(data){
		$("#err_oldPwd").html(data);
	});
}
function validCheckPwd() {
	if ($("#newPwd").val() == "") {
		$("#err_checkPwd").html("密码不能为空");
	}else if ($("#newPwd").val() != $("#checkPwd").val()) {
		$("#err_checkPwd").html("两次密码不正确");
	} else {
		$("#err_checkPwd").html("正确");
	}
}
</script>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
    	<div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main">
                <ul class="form_ul">
                    <form method="post" action="">
                    <li>
                    <strong>当前密码</strong>
                    <input type="password" onblur="javascript:validOldPwd();" value="" name="password" id="oldPwd" class="input_text" />
                    <span class="attention">请输入您当前正在使用的密码</span>
                    <span style="display: block;" class="error_tips" id="err_oldPwd"></span>
                    </li>
                    <li>
                    <strong>新密码</strong>
                    <input type="password" value="" name="password1" id="newPwd" class="input_text" />
                    </li>
                    <li>
                    <strong>确认新密码</strong>
                    <input type="password" onblur="javascript:validCheckPwd();" value="" name="password2" id="checkPwd" class="input_text" />
                    <span class="error_tips" id="err_checkPwd"> </span>
                    </li>
                    <li class="mbutton">
                    <button value="" type="submit" class="blue_button">确认修改</button>
                    </li>
                    </form>
                </ul>
            </div>
        </div>
    </div>
</div>



<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>