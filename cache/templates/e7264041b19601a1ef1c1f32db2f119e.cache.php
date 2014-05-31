<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="clear"></div>
<!---->

<!--引入bootstrap-->
<script type="text/javascript" src="<?php echo MODULE_URL; ?>statics/bootstrap/js/bootstrap.js"></script>
<div class="fabu">
<button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span>免费发布信息</button>
<!-- <span class="fabu_btn"><i class="fabu_ico"></i>免费发布信息</span> -->
</div>
<div class="idx"><img src="<?php echo HOME_THEME_PATH; ?>images/ad.jpg" width="100%" /></div>
<!-- <div class="news">
  <link href="<?php echo HOME_THEME_PATH; ?>images/slide.css" type="text/css" rel="stylesheet">
        <div id="myslide" style="width:305px;height:325px">
            <table width="100%" cellSpacing="0" cellPadding="0">
            <tr>
            <td class="pic" id="bimg" style="height:281px">
          
                <?php $return = $this->list_tag("action=module flag=2 field=thumb,title,url order=displayorder,updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <div class="<?php if ($key==0) { ?>dis<?php } else { ?>undis<?php } ?>" name="f">
                <a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><img alt="<?php echo $t['title']; ?>" style="width:305px;height:281px;" src="<?php echo dr_thumb($t['thumb'], 300, 280); ?>" border="0"></a>
                </div>
                <?php } } ?>
                <table id="font_hd" width="100%" cellSpacing="0" cellPadding="0">
                <tr>
                <td class="title" id="info">
                <?php $return = $this->list_tag("action=module flag=2 field=thumb,title,url order=displayorder,updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <div class="<?php if ($key==0) { ?>dis<?php } else { ?>undis<?php } ?>" name="f">
                <a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['title']; ?></a>
                </div>
                <?php } } ?>
                </td>
                <td id="simg" nowrap="nowrap" style="text-align:right">
               <?php $return = $this->list_tag("action=module flag=2 field=thumb,title,url order=displayorder,updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <div class="<?php if ($key==0) {  } else { ?>f1<?php } ?>" onclick=play(x[<?php echo $key; ?>],<?php echo $key; ?>) name="f"><?php echo $key+1; ?></div>
                <?php } } ?>
                </td>
                </tr>
                </table>
                <script src="<?php echo HOME_THEME_PATH; ?>js/slide.js"></script>
            </td>
            </tr>
            </table>
        </div>
  </div> -->
<div class="clear"></div>	

<div class="clear"></div>
<!--循环输出顶级栏目下面的子栏目及其内容，运用到了双list循环标签因此需要定义返回值return=c（都懂得）-->
<!--引入图标库-->
<div class="box">
<?php $return_c = $this->list_tag("action=category pid=0  return=c"); if ($return_c) extract($return_c); $count_c=count($return_c); if (is_array($return_c)) { foreach ($return_c as $key_c=>$c) { ?>
    <div class="tree">
        <div class="tree_title">

            <span class="tree_ico_<?php echo $key_c; ?>"></span>
            <span><?php echo $c['name']; ?></span>
        </div>
    	<div class="tree_name">
                <!--循环输出当前栏目下面的子栏目，运用到了双list循环标签因此需要定义返回值return=c（都懂得）-->
                <?php $return_c2 = $this->list_tag("action=category pid=$c[id]  return=c2"); if ($return_c2) extract($return_c2); $count_c2=count($return_c2); if (is_array($return_c2)) { foreach ($return_c2 as $key_c2=>$c2) { ?>
                <div class="tree_box">
                <a href="<?php echo $c2['url']; ?>" title="<?php echo $c2['name']; ?>"><span class="tree_word">&nbsp;&nbsp;<?php echo $c2['name']; ?>&nbsp;&nbsp;</span></a>
                </div>
                <?php } } ?>
    	</div>
    </div>
<?php } } ?>
</div>
<div class="idx"><img src="<?php echo HOME_THEME_PATH; ?>images/ad.jpg" width="100%" /></div>

<?php if ($fn_include = $this->_include("footer.html", "/")) include($fn_include); ?><script type="text/javascript" src="http://www.baixing.cc/index.php?c=cron"></script>