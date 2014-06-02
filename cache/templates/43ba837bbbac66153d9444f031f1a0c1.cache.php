<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	$(".main table tr:last td").css('border-bottom','none');
});
</script>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
        <div class="message message_info">仅记录最近的10次登陆记录</div>
        <div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main dr_table">
				<table style="table-layout:fixed;">
					<thead>
						<tr>
							<th style="width:140px;" class="algin_l">登录时间</th>
							<th style="width:80px;" class="algin_c">登录类型</th>
							<th class="algin_l">登录Ip</th>
						</tr>
					</thead>
					<tbody>
					<?php if (is_array($list)) { $count=count($list);foreach ($list as $t) { ?>
					<tr>
						<td class="algin_l"><?php echo dr_date($t['login_time']); ?></td>
						<td class="algin_c"><?php if ($t['login_type']) {  echo $t['login_type'];  } else { ?>默认<?php } ?></td>
						<td class="algin_l"><a href="http://www.baidu.com/baidu?wd=<?php echo $t['login_ip']; ?>" target="_blank"><?php echo $t['login_ip']; ?></a></td>
					</tr>
					<?php } } ?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>