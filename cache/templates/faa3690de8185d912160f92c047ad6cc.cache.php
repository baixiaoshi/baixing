<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script language="javascript">
// 选中导航菜单
$("#dr_nav_0").attr("class", "curr");
</script>
<div class="fls">
	<div class="fls_l">
    	<!--首页幻灯 begin-->
		<div style="height:281px; overflow:hidden;">
			<link href="<?php echo HOME_THEME_PATH; ?>images/slide.css" type="text/css" rel="stylesheet">
			<div id="myslide" style="width:710px;height:325px">
                <table width="100%" cellSpacing="0" cellPadding="0">
                <tr>
                <td class="pic" id="bimg" style="height:281px">
                    <?php $return = $this->list_tag("action=navigator type=4 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                    <div class="<?php if ($key==0) { ?>dis<?php } else { ?>undis<?php } ?>" name="f">
                    <a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><img alt="<?php echo $t['name']; ?>" style="width:710px;height:281px;" src="<?php echo dr_thumb($t['thumb'], 710, 280); ?>" border="0"></a>
                    </div>
                    <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删除-->
                    <table id="font_hd" width="100%" cellSpacing="0" cellPadding="0">
                    <tr>
                    <td class="title" id="info">
                    <?php $return = $this->list_tag("action=navigator type=4 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                    <div class="<?php if ($key==0) { ?>dis<?php } else { ?>undis<?php } ?>" name="f">
                    <a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['name']; ?></a>
                    </div>
                    <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删-->
                    </td>
                    <td id="simg" nowrap="nowrap" style="text-align:right">
                    <?php $return = $this->list_tag("action=navigator type=4 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                    <div class="<?php if ($key==0) {  } else { ?>f1<?php } ?>" onclick=play(x[<?php echo $key; ?>],<?php echo $key; ?>) name="f"><?php echo $key+1; ?></div>
                    <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删-->
                    </td>
                    </tr>
                    </table>
                    <script src="<?php echo HOME_THEME_PATH; ?>js/slide.js"></script>
                </td>
                </tr>
                </table>
            </div>
		</div>
        <!--首页幻灯 end-->
        
		<div class="clear"></div>
		<div class="left01">
			<div class="tit">
				<h4>图文精选</h4>
				<div class="r"><a href="/photo/">更多+</a></div>
			</div>
			<div class="clear"></div>
			<ul>
                <!--查询图片模块推荐位1（图文精选）的内容，field需要用到的字段（不填表示全部），按displayorder（后台指定排序）排序-->
                <?php $return = $this->list_tag("action=module module=photo flag=1 field=title,url,thumb order=displayorder,updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <li><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><img src="<?php echo dr_thumb($t['thumb'], 125, 110); ?>" /><br /><?php echo dr_strcut($t['title'], 20, ''); ?></a></li>
                <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删-->
            </ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
        
		<div class="left02">
			<dl>
                <div class="l">
                    <dt class="tit">新闻焦点</dt>
                </div>									
            </dl>
			<div class="display productline">
				<ul>
                	<!--查询news模块的推荐位5（新闻焦点）的内容，field需要用到的字段（不填表示全部），按displayorder（后台指定排序）排序-->
                    <?php $return = $this->list_tag("action=module module=news flag=5 field=thumb,title,url,description,updatetime order=displayorder,updatetime num=6"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) {  if ($key==0) { ?>
                    <div class="left">
                    	<a href="<?php echo $t['url']; ?>"><img src="<?php echo dr_thumb($t['thumb'], 260, 225); ?>" height="225" /><div class="li_tit"><?php echo $t['title']; ?></div></a>
                    </div>
                    <div class="right">
						<ul>
                        <li class="lione"><a href="<?php echo $t['url']; ?>" class="li_title" title="<?php echo $t['title']; ?>"><?php echo $t['title']; ?></a><div class="intro"><?php echo dr_strcut($t['description'], 90); ?><a href="<?php echo $t['url']; ?>">[详细]</a></div></li>
                    <?php } else { ?>
                   		<li><span><?php echo dr_date($t['_updatetime'], 'm-d'); ?></span><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 50); ?></a></li>
                    <?php }  } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删-->
                    	</ul>
                    </div>
                </ul>
			</div>
        </div>
		<div class="clear"></div>
		<div class="left03">
			<div class="tit">
				<h4>好图推荐</h4>
			</div>
			<div class="run">
				<div class="runlf"><img id="lfbut" src="<?php echo HOME_THEME_PATH; ?>images/1.gif"></div>
				<div id="gdq">
					<div>
						<ul>
							<!--查询图片模块推荐位2（好图推荐）的内容，field需要用到的字段（不填表示全部），按displayorder（后台指定排序）排序-->
							<?php $return = $this->list_tag("action=module module=photo flag=1 field=title,url,thumb order=displayorder,updatetime num=8"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) {  if ($key%4==0) { ?><li><?php } ?><!--每4条数据显示一个li标签，这个不用多说，会程序逻辑的都知道-->
								<dl onMouseOver="$(this,'span')[0].style.backgroundColor=''" onmouseout="$(this,'span')[0].style.backgroundColor=''">
									<dt>
										<a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><img src="<?php echo dr_thumb($t['thumb'], 150, 135); ?>"></a>
										<span id=span0></span>
									</dt>
									<a href="<?php echo $t['url']; ?>"><dd><?php echo dr_strcut($t['title'], 20); ?></dd></a>
								</dl>
							<?php if ($key%4==3) { ?></li><?php } ?><!--每4条数据显示一个li标签，这个不用多说，会程序逻辑的都知道-->
							<?php } }  if ($count%4!=0) { ?></li><?php } ?><!--每4条数据显示一个li标签，这个不用多说，会程序逻辑的都知道-->
							<?php echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议delete-->
						</ul>
					</div>
				</div>
				<div class="runrg"><img id="rgbut" src="<?php echo HOME_THEME_PATH; ?>images/2.gif"></div>
			</div>
			<!--图片滚动特效start--> 
			<script src="<?php echo HOME_THEME_PATH; ?>js/tp.js" type="text/javascript"></script>
			<link href="<?php echo HOME_THEME_PATH; ?>images/tp.css" rel="stylesheet" />
			<!--图片滚动特效end--> 
			<style>
			.fr{width:980px;margin:0 auto;margin-top:15px; line-height:22px; border-top:1px solid #ddd;padding-top:10px}
			.fr h4{font-size:14px;float:left;padding-left:10px}
			.fr a{padding:0px 5px;}
			</style>
			<script>Effect.HtmlMove("gdq","div/li","scrollLeft",18,"rgbut","lfbut",7000);</script>
		</div>
	</div>
    
	<div class="fls_r">
		<div class="right01">
			<dl>
				<div class="l">
					<dt class="tit">新闻排行榜</dt>
				</div>					
			</dl>
			<div class="display productline">
				<ul>
					<!--新闻排行榜-->
					<?php $return = $this->list_tag("action=module field=hits,title,url module=news num=7"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
					<li><span><?php echo $t['hits']; ?></span><em class="one"><?php echo $key+1; ?></em><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 28, '...'); ?></a></li>
					<?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删除-->
				</ul>	
			</div>
		</div>
		<div class="right02">
			<div class="tit">
				<h4 class="l">最新房源</h4>
				<div class="r"><a href="/fang/">更多+</a></div>
			</div>
			<ul>
			<!--我们按房源的更新时间排-->
			<?php $return = $this->list_tag("action=module field=hits,title,url module=fang num=8"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
				<li class="nopic"><span><?php echo dr_date($t['_updatetime'], 'm-d'); ?></span><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo dr_strcut($t['title'], 28, '...'); ?></a></li>
			<?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删除-->
			</ul>
		</div>
		<div class="right03">
			<div class="tit">
				<h4 class="l">最新下载</h4>
				<div class="r"><a href="/down/">更多+</a></div>
			</div>
			<ul>
				<!--查询推荐位3（装机必备）的内容，field需要用到的字段（不填表示全部），按displayorder（后台指定排序）排序-->
				<?php $return = $this->list_tag("action=module module=down flag=1 field=thumb,title,url,hits,description order=displayorder,updatetime num=4"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>	
				<li>
					<img src="<?php echo dr_thumb($t['thumb'],70,70); ?>" align="left" />
					<div class="r_ct">
						<a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>"><?php echo $t['title']; ?></a>
						<div class="into">
							<?php echo dr_strcut($t['description'], 38); ?><a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>">[详细]</a>
						</div>
						<span><?php echo $t['hits']; ?>次</span>
					</div>
				</li>
				<?php } }  echo $error; ?>
			</ul>
		</div>
	</div>
</div></div>
<div class="clear"></div>
<div class="fr">
    <h4>友情链接：</h4>
        <?php $return = $this->list_tag("action=navigator type=3 num=10"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
        <a href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['name']; ?></a>
        <?php } }  echo $error; ?><!--如果查询为空，error变量会返回错误提示，正式上线建议删除-->
</div>
<div class="clear"></div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?><script type="text/javascript" src="http://www.baixing.cc/index.php?c=cron"></script>