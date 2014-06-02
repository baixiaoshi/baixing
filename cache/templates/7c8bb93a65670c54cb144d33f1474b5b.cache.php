<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	<?php if ($error) { ?>
	dr_tips('<font color=red><?php echo $error['msg']; ?></font>', 3);
	<?php } ?>
});
function dr_send_sms() {
	$.post("<?php echo dr_member_url('account/sendsms'); ?>&phone="+$("#dr_phone").val(), function(data){
		if (data.status == '1') {
			dr_tips(data.code, 3, 1);
		} else {
			dr_tips(data.code);
		}
	}, 'json');
}
</script>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
    	<div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main">
                <form action="" method="post">
                <table width="100%" class="table_form">
                <?php if ($oauth) { ?>
                <tr>
                    <th width="160"><font color="red">*</font>&nbsp;账号： </th>
                    <td><input class="input-text" type="text" name="member[username]" value="" style="width:200px;" required="required" /></td>
                </tr>
                <tr>
                    <th><font color="red">*</font>&nbsp;邮箱： </th>
                    <td><input class="input-text" type="text" name="member[email]" value="" style="width:200px;" required="required" /></td>
                </tr>
                <tr>
                    <th><font color="red">*</font>&nbsp;密码： </th>
                    <td><input class="input-text" type="text" name="member[password]" value="" style="width:200px;" required="required" /></td>
                </tr>
                <?php } else { ?>
                <tr>
                    <th width="160">邮箱： </th>
                    <td><?php echo $member['email']; ?></td>
                </tr>
                <?php }  echo $myfield; ?>
                <tr>
                    <th style="border-bottom:none">&nbsp;</th>
                    <td style="border-bottom:none;padding-top: 20px;"><div class="mbutton"><button value="" type="submit" class="blue_button">保存</button></div></td>
                </tr>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>