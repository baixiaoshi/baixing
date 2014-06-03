<div class="end" >
	<div class="idx">
        <?php $return = $this->list_tag("action=navigator type=2 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) {  if ($key==0) { ?>-<?php } ?><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['name']; ?></a>-
        <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删除-->
    </div>
</div>
<div class="copyright">
    <div class="idx">
    	Powered by FineCMS v<?php echo DR_VERSION; ?> © 2011-2013 Dayrui Inc.<!--非授权用户请保留FineCMS的字样-->
        <br><b style="color:#F00">需求信息发布信息</b>
        <br>
        <a href="http://www.dayrui.com" target="_blank"><img border="0" src="<?php echo HOME_THEME_PATH; ?>images/love.gif" alt="我们一直用心在做"></a>
    </div>
</div>
</body>
</html>