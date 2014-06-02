<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	$("#dr_loadmore a").click(function(){
		var page = $("#dr_page").val();
		$("#dr_loading").html("<div style='padding:30px;text-align:center;width:678px;'><img src='<?php echo MEMBER_THEME_PATH; ?>images/loading.gif' /></div>");
		$.get("<?php echo $moreurl; ?>", {page:page, module:<?php echo intval($get['module']); ?>, ext:<?php echo intval($get['ext']); ?> }, function(data){
			if (data != "null") {
				$("#dr_body").append(data);
				$("#dr_page").val(Number(page) + 1);
			}
			$("#dr_loading").html("&nbsp;");
		});
	});
});
function dr_delete(id) {
    art.dialog.confirm("你确认要删除吗？", function(){
		// 将表单数据ajax提交验证
		$.ajax({type: "POST",dataType:"json", url: "<?php echo dr_member_url(APP_DIR.'/account/del_attach'); ?>",data: {id:id}, success: function(data) {
				//验证成功
				if (data.status == 1) {
					$.dialog.tips(data.code, 3, 1);
					$("#dr_row_"+id).hide();
				} else {
					$.dialog.tips(data.code, 3, 0);
				}
                art.dialog.close();
                return false;
			},
			error: function(HttpRequest, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
			}
		});
		return true;
	});
	return false;
}
</script>
<input name="page" id="dr_page" type="hidden" value="2" />
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
		<div class="message message_info">附件总空间：<?php if ($acount) {  echo dr_format_file_size($acount);  } else { ?>不限制<?php } ?>，已使用：<?php echo dr_format_file_size($ucount);  if ($acount) { ?>，剩余：<?php echo dr_format_file_size($scount);  } ?>。</div>
        <div class="section">
            <div class="title"><strong><?php echo $meta_name; ?>（已使用的附件）</strong></div>
            <div class="main dr_table">
				<div class="msearch mbutton">
                	<form method="get" action="">
						<input name="c" type="hidden" value="<?php echo $get['c']; ?>" />
						<input name="m" type="hidden" value="<?php echo $get['m']; ?>" />
						<button type="submit" class="red_button" style="float:right;margin-left:20px">查询</button>
						<input type="text" name="ext" placeholder="输入扩展名" value="<?php echo $get['ext']; ?>" class="input_text" style="color:#999;width:150px;float:right" />
						<span style="color:#333;float:right;padding-right:15px;">模块：
						<select name="module">
							<option value=""> -- </option>
							<?php if (is_array($module)) { $count=count($module);foreach ($module as $dir=>$name) { ?>
							<option value="<?php echo $dir; ?>" <?php if ($get['module']==$dir) { ?>selected<?php } ?>><?php echo $name; ?></option>
							<?php } } ?>
						</select>
						</span>
               		</form>
				</div>
                <div class="bk10"></div>
				<table style="table-layout:fixed;margin-bottom: 0;">
				<thead>
					<tr>
						<th class="algin_l">文件名</th>
						<th style="width:100px;" class="algin_c">大小</th>
						<th style="width:50px;" class="algin_c">扩展名</th>
						<th style="width:120px;" class="algin_c">上传时间</th>
						<th style="width:50px;" class="algin_c">操作</th>
					</tr>
				</thead>
				<tbody id="dr_body">
				<?php if ($fn_include = $this->_include("account_attachment_data.html")) include($fn_include); ?>
				</tbody>
				</table>
				<div id="dr_loading" style="marign-top:10px;">&nbsp;</div>
                <div id="dr_loadmore" class="load-more"><a href="javascript:;">查 看 更 多</a></div>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>