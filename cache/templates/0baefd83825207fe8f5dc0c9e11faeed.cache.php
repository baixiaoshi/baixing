<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function() {
	SwapTab(<?php echo $page; ?>);
	<?php if ($result) { ?>
	art.dialog.tips('<?php echo $result; ?>', 3, 1);
	<?php } ?>
	dr_set_mw_type(<?php echo intval($data['SITE_IMAGE_TYPE']); ?>);
});
function dr_form_check() {
	if (d_required('name')) return false;
	if (d_isdomain('domain')) return false;
	return true;
}
function dr_set_mw_type(id) {
	$(".dr_mw_1").hide();
	$(".dr_mw_0").hide();
	$(".dr_mw_"+id).show();
}
</script>
<form action="" method="post" name="myform" id="myform" onsubmit="return dr_form_check()">
<input name="page" id="page" type="hidden" value="<?php echo $page; ?>" />
<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<?php echo $menu; ?><span>|</span><a href="<?php echo SYS_HELP_URL; ?>11.html" target="_blank"><em><?php echo lang('help'); ?></em></a>
	</div>
	<div class="bk10"></div>
	<div class="table-list col-tab">
		<ul class="tabBut cu-li">
			<li onclick="SwapTab(0)"><?php echo lang('html-083'); ?></li>
			<li onclick="SwapTab(1)"><?php echo lang('html-084'); ?></li>
			<li onclick="SwapTab(2)"><?php echo lang('html-085'); ?></li>
			<li onclick="SwapTab(3)"><?php echo lang('html-086'); ?></li>
			<li onclick="SwapTab(4)"><?php echo lang('html-542'); ?></li>
		</ul>
		<div class="contentList pad-10">
			<div id="cnt_0" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
                <tr>
                    <th width="200"><?php echo lang('html-089'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="domain" id="dr_domain" value="<?php echo $data['SITE_DOMAIN']; ?>" size="25" />
                    <div class="onShow" id="dr_domain_tips"><?php echo lang('html-090'); ?></div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo lang('html-087'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_NAME]" id="dr_name" value="<?php echo $data['SITE_NAME']; ?>" size="40" />
                    <div class="onShow" id="dr_name_tips"><?php echo lang('html-088'); ?></div>
                    </td>
                </tr>
                <tr>
					<th><?php echo lang('html-093'); ?>： </th>
					<td>
					<input class="input-text" type="text" name="data[SITE_TIME_FORMAT]" value="<?php echo $data['SITE_TIME_FORMAT']; ?>" size="15" />
					<div class="onShow"><?php echo lang('html-094'); ?></div>
					</td>
				</tr>
                <tr>
					<th><?php echo lang('html-095'); ?>： </th>
					<td>
					<select name="data[SITE_LANGUAGE]">
                    <option value="zh-cn"> -- </option>
                    <?php if (is_array($lang)) { $count=count($lang);foreach ($lang as $t) { ?>
                    <option<?php if ($t==$data['SITE_LANGUAGE']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
                    <div class="onShow"><?php echo lang('html-096'); ?></div>
					</td>
				</tr>
                <tr>
					<th><?php echo lang('html-097'); ?>： </th>
					<td>
					<select name="data[SITE_THEME]">
                    <option value="default"> -- </option>
                    <?php if (is_array($theme)) { $count=count($theme);foreach ($theme as $t) { ?>
                    <option<?php if ($t==$data['SITE_THEME']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
					<div class="onShow"><?php echo lang('html-098'); ?></div>
					</td>
				</tr>
               <tr>
					<th><?php echo lang('html-099'); ?>： </th>
					<td>
					<select name="data[SITE_TEMPLATE]">
                    <option value="default"> -- </option>
                    <?php if (is_array($template_path)) { $count=count($template_path);foreach ($template_path as $t) { ?>
                    <option<?php if ($t==$data['SITE_TEMPLATE']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
					<div class="onShow"><?php echo lang('html-100'); ?></div>
					</td>
				</tr>
				<tr>
                    <th><?php echo lang('html-075'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_MOBILE]" value="1" <?php if ($data['SITE_MOBILE']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_MOBILE]" value="0" <?php if (empty($data['SITE_MOBILE'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
					<div class="onShow"><?php echo lang('html-674'); ?></div>
                    </td>
                </tr>
                <tr class="">
					<th><?php echo lang('html-101'); ?>： </th>
					<td>
					<select name="data[SITE_TIMEZONE]">
					<option value=""> -- </option>
					<option value="-12" <?php if ($data['SITE_TIMEZONE']=="-12") { ?>selected<?php } ?>>(GMT -12:00)</option>
					<option value="-11" <?php if ($data['SITE_TIMEZONE']=="-11") { ?>selected<?php } ?>>(GMT -11:00)</option>
					<option value="-10" <?php if ($data['SITE_TIMEZONE']=="-10") { ?>selected<?php } ?>>(GMT -10:00)</option>
					<option value="-9" <?php if ($data['SITE_TIMEZONE']=="-9") { ?>selected<?php } ?>>(GMT -09:00)</option>
					<option value="-8" <?php if ($data['SITE_TIMEZONE']=="-8") { ?>selected<?php } ?>>(GMT -08:00)</option>
					<option value="-7" <?php if ($data['SITE_TIMEZONE']=="-7") { ?>selected<?php } ?>>(GMT -07:00)</option>
					<option value="-6" <?php if ($data['SITE_TIMEZONE']=="-6") { ?>selected<?php } ?>>(GMT -06:00)</option>
					<option value="-5" <?php if ($data['SITE_TIMEZONE']=="-5") { ?>selected<?php } ?>>(GMT -05:00)</option>
					<option value="-4" <?php if ($data['SITE_TIMEZONE']=="-4") { ?>selected<?php } ?>>(GMT -04:00)</option>
					<option value="-3.5" <?php if ($data['SITE_TIMEZONE']=="-3.5") { ?>selected<?php } ?>>(GMT -03:30)</option>
					<option value="-3" <?php if ($data['SITE_TIMEZONE']=="-3") { ?>selected<?php } ?>>(GMT -03:00)</option>
					<option value="-2" <?php if ($data['SITE_TIMEZONE']=="-2") { ?>selected<?php } ?>>(GMT -02:00)</option>
					<option value="-1" <?php if ($data['SITE_TIMEZONE']=="-1") { ?>selected<?php } ?>>(GMT -01:00)</option>
					<option value="0" <?php if ($data['SITE_TIMEZONE']=="0") { ?>selected<?php } ?>>(GMT)</option>
					<option value="1" <?php if ($data['SITE_TIMEZONE']=="1") { ?>selected<?php } ?>>(GMT +01:00)</option>
					<option value="2" <?php if ($data['SITE_TIMEZONE']=="2") { ?>selected<?php } ?>>(GMT +02:00)</option>
					<option value="3" <?php if ($data['SITE_TIMEZONE']=="3") { ?>selected<?php } ?>>(GMT +03:00)</option>
					<option value="3.5" <?php if ($data['SITE_TIMEZONE']=="3.5") { ?>selected<?php } ?>>(GMT +03:30)</option>
					<option value="4" <?php if ($data['SITE_TIMEZONE']=="4") { ?>selected<?php } ?>>(GMT +04:00)</option>
					<option value="4.5" <?php if ($data['SITE_TIMEZONE']=="4.5") { ?>selected<?php } ?>>(GMT +04:30)</option>
					<option value="5" <?php if ($data['SITE_TIMEZONE']=="5") { ?>selected<?php } ?>>(GMT +05:00)</option>
					<option value="5.5" <?php if ($data['SITE_TIMEZONE']=="5.5") { ?>selected<?php } ?>>(GMT +05:30)</option>
					<option value="5.75" <?php if ($data['SITE_TIMEZONE']=="5.75") { ?>selected<?php } ?>>(GMT +05:45)</option>
					<option value="6" <?php if ($data['SITE_TIMEZONE']=="6") { ?>selected<?php } ?>>(GMT +06:00)</option>
					<option value="6.5" <?php if ($data['SITE_TIMEZONE']=="6.6") { ?>selected<?php } ?>>(GMT +06:30)</option>
					<option value="7" <?php if ($data['SITE_TIMEZONE']=="7") { ?>selected<?php } ?>>(GMT +07:00)</option>
					<option value="8" <?php if ($data['SITE_TIMEZONE']=="" || $data['SITE_TIMEZONE']=="8") { ?>selected<?php } ?>>(GMT +08:00)</option>
					<option value="9" <?php if ($data['SITE_TIMEZONE']=="9") { ?>selected<?php } ?>>(GMT +09:00)</option>
					<option value="9.5" <?php if ($data['SITE_TIMEZONE']=="9.5") { ?>selected<?php } ?>>(GMT +09:30)</option>
					<option value="10" <?php if ($data['SITE_TIMEZONE']=="10") { ?>selected<?php } ?>>(GMT +10:00)</option>
					<option value="11" <?php if ($data['SITE_TIMEZONE']=="11") { ?>selected<?php } ?>>(GMT +11:00)</option>
					<option value="12" <?php if ($data['SITE_TIMEZONE']=="12") { ?>selected<?php } ?>>(GMT +12:00)</option>
					</select>
					<div class="onShow"><?php echo lang('html-102'); ?></div>
					</td>
				</tr>
				</table>
			</div>
			<div id="cnt_1" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
                <tr>
                    <th width="200"><?php echo lang('html-171'); ?>： </th>
                    <td><input class="input-text" type="text" name="data[SITE_SEOJOIN]" value="<?php echo $data['SITE_SEOJOIN'] ? $data['SITE_SEOJOIN'] : '_'; ?>" size="7" />
                    <div class="onShow"><?php echo lang('html-172'); ?></div></td>
                </tr>
                <tr>
                    <th><?php echo lang('html-103'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_TITLE]" value="<?php echo $data['SITE_TITLE']; ?>" style="width:525px;" />
                    </td>
                </tr>
                <tr>
                    <th><?php echo lang('html-104'); ?>： </th>
                    <td>
                    <textarea class="text" style="width:520px;height:50px;" name="data[SITE_KEYWORDS]"><?php echo $data['SITE_KEYWORDS']; ?></textarea>
                    </td>
                </tr>
                <tr class="">
                    <th><?php echo lang('html-105'); ?>： </th>
                    <td>
                    <textarea class="text" style="width:520px;height:80px;" name="data[SITE_DESCRIPTION]"><?php echo $data['SITE_DESCRIPTION']; ?></textarea>
                    </td>
                </tr>
                </table>
            </div>
			<div id="cnt_2" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
				<tr>
                    <th width="200"><?php echo lang('html-085'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_ATTACH_REMOTE]" value="1" <?php if ($data['SITE_ATTACH_REMOTE']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_ATTACH_REMOTE]" value="0" <?php if (empty($data['SITE_ATTACH_REMOTE'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
					<div class="onShow"><?php echo lang('html-106'); ?></div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo lang('html-107'); ?>： </th>
                    <td>
                    <input id="host" class="input-text" type="text" name="data[SITE_ATTACH_HOST]" value="<?php echo $data['SITE_ATTACH_HOST']; ?>" size="25" />
					<div class="onShow"><?php echo lang('html-108'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-109'); ?>： </th>
                    <td>
                    <input id="port" class="input-text" type="text" name="data[SITE_ATTACH_PORT]" value="<?php echo $data['SITE_ATTACH_PORT']; ?>" size="10" />
					<div class="onShow"><?php echo lang('html-110'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-111'); ?>： </th>
                    <td>
                    <input id="username" class="input-text" type="text" name="data[SITE_ATTACH_USERNAME]" value="<?php echo $data['SITE_ATTACH_USERNAME']; ?>" size="25" />
					<div class="onShow"><?php echo lang('html-112'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-113'); ?>： </th>
                    <td>
                    <input id="password" class="input-text" type="password" name="data[SITE_ATTACH_PASSWORD]" value="<?php echo $data['SITE_ATTACH_PASSWORD']; ?>" size="25" />
					<div class="onShow"><?php echo lang('html-114'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-115'); ?>： </th>
                    <td>
                    <input id="path" class="input-text" type="text" name="data[SITE_ATTACH_PATH]" value="<?php echo $data['SITE_ATTACH_PATH']; ?>" size="25" />
					<div class="onShow"><?php echo lang('html-116'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-117'); ?>： </th>
                    <td>
					<input class="pasv" type="radio" name="data[SITE_ATTACH_PASV]" value="1" <?php if ($data['SITE_ATTACH_PASV']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input class="pasv" type="radio" name="data[SITE_ATTACH_PASV]" value="0" <?php if (empty($data['SITE_ATTACH_PASV'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
					<div class="onShow"><?php echo lang('html-118'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-119'); ?>： </th>
                    <td>
					<input class="mode" type="radio" name="data[SITE_ATTACH_MODE]" value="auto" <?php if (empty($data['SITE_ATTACH_MODE']) || $data['SITE_ATTACH_MODE'] == 'auto') { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('html-120'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input class="mode" type="radio" name="data[SITE_ATTACH_MODE]" value="ascii" <?php if ($data['SITE_ATTACH_MODE']=='ascii') { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('html-121'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input class="mode" type="radio" name="data[SITE_ATTACH_MODE]" value="binary" <?php if ($data['SITE_ATTACH_MODE']=='binary') { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('html-122'); ?></label>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-123'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_ATTACH_EXTS]" value="<?php echo $data['SITE_ATTACH_EXTS']; ?>" size="25" />
					<div class="onShow"><?php echo lang('html-124'); ?></div>
                    </td>
                </tr>
				<tr class="">
                    <th><?php echo lang('html-125'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_ATTACH_URL]" value="<?php echo $data['SITE_ATTACH_URL']; ?>" size="25" />
					<input class="button" type="button" name="button" id="ftptest" onclick="ftp_test()" value="<?php echo lang('html-127'); ?>" />
					<div class="onShow"><?php echo lang('html-126'); ?></div>
                    </td>
                </tr>
                </table>
            </div>
			<div id="cnt_3" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
                <tr>
                    <th width="200"><?php echo lang('html-570'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_IMAGE_RATIO]" value="1" <?php if ($data['SITE_IMAGE_RATIO']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_IMAGE_RATIO]" value="0" <?php if (empty($data['SITE_IMAGE_RATIO'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
					<div class="onShow"><?php echo lang('html-571'); ?></div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo lang('html-128'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_IMAGE_WATERMARK]" value="1" <?php if ($data['SITE_IMAGE_WATERMARK']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_IMAGE_WATERMARK]" value="0" <?php if (empty($data['SITE_IMAGE_WATERMARK'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-129'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_IMAGE_REMOTE]" value="1" <?php if ($data['SITE_IMAGE_REMOTE']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('open'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_IMAGE_REMOTE]" value="0" <?php if (empty($data['SITE_IMAGE_REMOTE'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('close'); ?></label>
					<div class="onShow"><?php echo lang('html-130'); ?></div>
                    </td>
                </tr>
				<tr>
					<th><?php echo lang('html-143'); ?>： </th>
					<td>
					<select name="data[SITE_IMAGE_VRTALIGN]">
                    <?php if (is_array($wm_vrt_alignment)) { $count=count($wm_vrt_alignment);foreach ($wm_vrt_alignment as $t) { ?>
                    <option<?php if ($t==$data['SITE_IMAGE_VRTALIGN']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
					&nbsp;&nbsp;
					<select name="data[SITE_IMAGE_HORALIGN]">
                    <?php if (is_array($wm_hor_alignment)) { $count=count($wm_hor_alignment);foreach ($wm_hor_alignment as $t) { ?>
                    <option<?php if ($t==$data['SITE_IMAGE_HORALIGN']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
					</td>
				</tr>
				<tr>
                    <th><?php echo lang('html-144'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_IMAGE_VRTOFFSET]" value="<?php echo $data['SITE_IMAGE_VRTOFFSET']; ?>" size="7" />
					&nbsp;&nbsp;
					<input class="input-text" type="text" name="data[SITE_IMAGE_HOROFFSET]" value="<?php echo $data['SITE_IMAGE_HOROFFSET']; ?>" size="7" />
					<div class="onShow"><?php echo lang('html-145'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-131'); ?>： </th>
                    <td>
                    <input type="radio" name="data[SITE_IMAGE_TYPE]" value="1" onclick="dr_set_mw_type(1)" <?php if ($data['SITE_IMAGE_TYPE']) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('html-132'); ?></label>
                    &nbsp;&nbsp;&nbsp;
					<input type="radio" name="data[SITE_IMAGE_TYPE]" value="0" onclick="dr_set_mw_type(0)" <?php if (empty($data['SITE_IMAGE_TYPE'])) { ?>checked<?php } ?> />&nbsp;<label><?php echo lang('html-133'); ?></label>
                    </td>
                </tr>
				<tr class="dr_mw_1" style="display:none">
					<th><?php echo lang('html-134'); ?>： </th>
					<td>
					<select name="data[SITE_IMAGE_OVERLAY]">
                    <?php if (is_array($wm_opacity)) { $count=count($wm_opacity);foreach ($wm_opacity as $t) { ?>
                    <option<?php if ($t==$data['SITE_IMAGE_OVERLAY']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
                    <div class="onShow"><?php echo lang('html-135'); ?></div>
					</td>
				</tr>
				<tr class="dr_mw_1" style="display:none">
                    <th><?php echo lang('html-136'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_IMAGE_OPACITY]" value="<?php echo $data['SITE_IMAGE_OPACITY']; ?>" size="7" />
					<div class="onShow"><?php echo lang('html-137'); ?></div>
                    </td>
                </tr>
				<tr class="dr_mw_0" style="display:none">
					<th><?php echo lang('html-138'); ?>： </th>
					<td>
					<?php if ($wm_font_path) { ?>
					<select name="data[SITE_IMAGE_FONT]">
                    <?php if (is_array($wm_font_path)) { $count=count($wm_font_path);foreach ($wm_font_path as $t) { ?>
                    <option<?php if ($t==$data['SITE_IMAGE_FONT']) { ?> selected=""<?php } ?> value="<?php echo $t; ?>"><?php echo $t; ?></option>
                    <?php } } ?>
                    </select>
					<?php } ?>
                    <div class="onShow"><?php echo lang('html-139'); ?></div>
					</td>
				</tr>
				<tr class="dr_mw_0" style="display:none">
                    <th><?php echo lang('html-140'); ?>： </th>
                    <td>
					<?php echo dr_field_input('SITE_IMAGE_COLOR', 'Color', array('option'=>array('value'=>$data['SITE_IMAGE_COLOR']))); ?>
                    </td>
                </tr>
				<tr class="dr_mw_0" style="display:none">
                    <th><?php echo lang('html-141'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_IMAGE_SIZE]" value="<?php echo $data['SITE_IMAGE_SIZE']; ?>" size="5" />
                    </td>
                </tr>
				<tr class="dr_mw_0" style="display:none">
                    <th><?php echo lang('html-142'); ?>： </th>
                    <td>
                    <input class="input-text" type="text" name="data[SITE_IMAGE_TEXT]" value="<?php echo $data['SITE_IMAGE_TEXT']; ?>" size="33" />
                    </td>
                </tr>
                </table>
			</div>
			<div id="cnt_4" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
                <?php for ($i = 0; $i <= 9; $i ++) { ?>
                <tr>
                    <th width="200"></th>
                    <td align="left">
                    <input class="input-text" name="navigator[<?php echo $i; ?>]" type="text" value="<?php echo $navigator[$i]; ?>" />
					&nbsp;type=<?php echo $i; ?>
                    </td>
                </tr>
                <?php } ?>
				</table>
			</div>
			<table width="100%" class="table_form">
			<tr>
				<th width="200" style="border:none;">&nbsp;</th>
				<td>
				<input class="button" type="submit" name="submit" value="<?php echo lang('submit'); ?>" />
				</td>
			</tr>
			</table>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
<!--
function ftp_test() {
	var host = $('#host').val();
	var port = $('#port').val();
	var username = $('#username').val();
	var password = $('#password').val();
	var path = $("#path").val();
	var pasv = $(".pasv:checked").val();
	var mode = $(".mode:checked").val();
	$("#ftptest").val('Loading');
	$.get("<?php echo dr_url('api/testftp'); ?>",{host:host,port:port,username:username,password:password,pasv:pasv,path:path,mode:mode}, function(data){
		alert(data);
		$("#ftptest").val('<?php echo lang('html-127'); ?>');
	})
}
//-->
</script>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>