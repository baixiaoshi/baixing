<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<link href="<?php echo MEMBER_THEME; ?>images/reg.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(function(){
	<?php if ($error) { ?>
	dr_tips("<font color=red><?php echo $error; ?></font>", 3);
	<?php } ?>
});
</script>
<div class="content">
    <div class="login_wp clearfix">
        <div class="login_mod">
            <h3>会员登录<span>Login</span></h3>
            <form method="post" action="">
            <ul>
                <li>
                	<strong>会员：</strong>
                    <input class="input_text" type="text" maxlength="64" autofocus="autofocus"  id="dr_username" name="data[username]" />
                </li>
                <li>
                	<strong>密码：</strong>
                    <input class="input_text" type="password" id="dr_password" name="data[password]" />
                </li>
                <?php if ($code) { ?>
                <li class="check_code" id="dr_code">
                	<strong>验证：</strong>
                    <input class="input_text" type="text" maxlength="30" id="code"  name="code"  value="" />
                    <span style="float:left"><img align="absmiddle" style="cursor:pointer;" onclick="this.src='<?php echo dr_member_url('api/captcha', array('width' => 90, 'height' => 32)); ?>&'+Math.random();" src="<?php echo dr_member_url('api/captcha', array('width' => 90, 'height' => 32)); ?>" /></span>
                </li>
                <?php } ?>
                <li class="auto_login">
                    <input type="checkbox" name="data[auto]" value="1" checked="checked" />
                    <span>下次自动登录</span>
                </li>
                <li class="mbutton">
                    <button type="submit" value="">
                         登录
                    </button>
                    <a href="<?php echo dr_member_url('login/find'); ?>">忘记密码？</a>
                </li>
            </ul>
            </form>
            <div class="login_other"> 
                <strong>使用合作网站帐号登录：</strong> 
                <?php $return = $this->list_tag("action=cache name=OAUTH"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?> 
                <span class="dr_oauth_style dr_oauth_<?php echo $t['icon']; ?>" title="<?php echo $t['name']; ?>" onclick="window.location.href='<?php echo SITE_URL; ?>member/index.php?c=api&m=oauth&id=<?php echo $t['id']; ?>'"></span>
                <?php } } ?>
            </div>
        </div>
        <div class="login_banner"></div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>