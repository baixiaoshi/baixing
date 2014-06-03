<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<div class="clear"></div>
<div class="Dg">您当前位置：<a href="<?php echo SITE_URL; ?>">首页</a>  > <a href="<?php echo MODULE_URL; ?>"><?php echo MODULE_NAME; ?></a>  > <?php echo dr_catpos($catid, ' > '); ?><!--面包屑导航--></div>
<!--搜索条件筛选-->

<div class="condition_box" id="condition_box">
	<table class="condition_table">
	  <tr class="tb_head "><td class="td_left">分类:</td><td class="td_right">
	  		<?php $return_c = $this->list_tag("action=category pid=$cat[pid]  return=c"); if ($return_c) extract($return_c); $count_c=count($return_c); if (is_array($return_c)) { foreach ($return_c as $key_c=>$c) { ?>
					<a href="<?php echo $c['url']; ?>" <?php if ($c['id']==$catid) { ?> style="color:#00F;font-weight:bold;"<?php } ?>><?php echo $c['name']; ?></a>
	 		<?php } } ?> 
	  </td></tr>
	  <tr><td class="td_left">价格:</td><td class="td_right"></td></tr>
	  <tr><td class="td_left">社会/校园</td><td class="td_right"></td></tr>
	  <tr><td class="td_left">公司/个人</td><td class="td_right"></td></tr>
	  <tr><td class="td_left">性别</td><td class="td_right"></td></tr>
	  <tr><td class="td_left"></td><td class="td_right"></td></tr>
	</table>

</div>

<div class="digg" style="margin-top:5px">
	<div class="digg_l">
		<div class="left03">
			<h4>栏目索引</h4>
			<ul>
				<!--循环输出当前栏目的同级栏目，定义返回值return=c，你也可以使用默认的（都懂得）-->
				<?php $return_c = $this->list_tag("action=category pid=$cat[pid]  return=c"); if ($return_c) extract($return_c); $count_c=count($return_c); if (is_array($return_c)) { foreach ($return_c as $key_c=>$c) { ?>
				<li><a href="<?php echo $c['url']; ?>" <?php if ($c['id']==$catid) { ?> style="color:#00F;font-weight:bold;"<?php } ?>><?php echo $c['name']; ?></a></li>
 				<?php } } ?>
            </ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="left01">
			<h4>猜你喜欢</h4>
			<ul>
                <!--我们按缩略图排序，因为第一行和第二行要输出一个图片，你懂得！-->
                <?php $return = $this->list_tag("action=module catid=$catid field=thumb,title,url,description order=thumb,updatetime num=12"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) {  if ($key<2) { ?>
                <li class="pic"><img src="<?php echo dr_thumb($t['thumb'], 70, 60); ?>" width="70" height="60" align="left" /><span><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 20); ?></a></span><div class="into"><?php echo dr_strcut($t['description'], 38); ?></div></li>
                <?php } else { ?>
                <li class="nopic"><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 27); ?></a></li>
                <?php }  } }  echo $error; ?>
            </ul>	
        </div>
		<div class="left02">
			<h4>热门排行榜</h4>
			<ul>
                <!--我们按点击排序-->
                <?php $return = $this->list_tag("action=module catid=$catid field=title,url order=hits num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <li><span <?php if ($key<3) { ?>class="one"<?php } ?>><?php echo $key+1; ?></span><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 27); ?></a></li>
                <?php } }  echo $error; ?>
			</ul>	
        </div>
    </div>
	<div class="lmlist">
		<h4><?php echo $cat['name']; ?></h4>
		<ul>
        <!--分页显示列表数据-->
		<?php $return = $this->list_tag("action=module catid=$catid field=title,url,updatetime order=updatetime page=1"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
        <li><span><?php echo $t['updatetime']; ?></span><a title="<?php echo $t['title']; ?>" href="<?php echo $t['url']; ?>"><?php echo $t['title']; ?></a></li>
        <?php } }  echo $error; ?>
        </ul>
        <div class="n_fy" style="padding-left:0px">
            <div class="fenye" id="fenye">
            <?php echo $pages; ?>
            </div>
		<div>
	</div>
	</div></div>
		<div class="clear"></div>
<?php if ($fn_include = $this->_include("footer.html", "/")) include($fn_include); ?><script type="text/javascript" src="http://www.baixing.cc/index.php?c=cron"></script>