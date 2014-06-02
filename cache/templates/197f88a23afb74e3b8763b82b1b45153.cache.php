<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="wp" id="wp">
    <div class="wp cl w" id="ct">
        <div class="nfl">
            <div class="f_c altw">
                <div class="<?php if ($mark==1) { ?>alert_right<?php } else if ($mark==2) { ?>alert_info<?php } else { ?>alert_error<?php } ?>"" id="messagetext">
                	<p><?php echo $msg; ?></p>
               		<p class="alert_btnleft">
                    <?php if ($url) { ?>
                    <a href="<?php echo $url; ?>">如果您的浏览器没有自动跳转，请点击此链接</a>
                    <meta http-equiv="refresh" content="<?php echo $time; ?>; url=<?php echo $url; ?>">
                    <?php } else { ?>
                    <a href="javascript:history.back();" >[返回上一页]</a>
                    <?php } ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>