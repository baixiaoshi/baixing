<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function() {
	SwapTab(<?php echo $page; ?>);
	set_required(<?php echo intval($data['setting']['validate']['required']); ?>);
	show_field_option("<?php echo $data['fieldtype']; ?>");
	<?php if ($result) { ?>
	art.dialog.tips('<font color=red><?php echo $result; ?></font>', 3);
	<?php }  if ($code) { ?>
	d_tips('<?php echo $code; ?>', 0);
	<?php } ?>
});
function dr_form_check() {
	if (d_required('name')) return false;
	if (d_required('fieldname')) return false;
	return true;
}
function show_field_option(type) {
	$("#dr_loading").show();
	$.post('<?php echo MEMBER_PATH; ?>index.php?c=api&m=field&rand='+Math.random(),{ type:type, module:'<?php echo $module; ?>', relatedid:'<?php echo $relatedid; ?>', relatedname:'<?php echo $relatedname; ?>', id:<?php echo intval($data['id']); ?>}, function(data){
		$('#dr_option').html(data);
		$("#dr_loading").hide();
	});
}
function set_required(id) {
	if (id == 0) {
		$('#required').hide();
	} else {
		$('#required').show();
	}
}
</script>
<form action="" method="post" name="myform" id="myform" onsubmit="return dr_form_check()">
<input name="page" id="page" type="hidden" value="<?php echo $page; ?>" />
<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<?php echo $menu; ?><span>|</span><a href="<?php echo SYS_HELP_URL; ?>15.html" target="_blank"><em><?php echo lang('help'); ?></em></a>
	</div>
	<div class="bk10"></div>
	<div class="table-list col-tab">
		<ul class="tabBut cu-li">
			<li onclick="SwapTab(0)"><?php echo lang('html-083'); ?></li>
			<li onclick="SwapTab(1)"><?php echo lang('html-184'); ?></li>
			<li onclick="SwapTab(2)"><?php echo lang('html-185'); ?></li>
		</ul>
		<div class="contentList pad-10">
			<div id="cnt_0" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
				<tr>
					<th width="200"><font color="red">*</font>&nbsp;<?php echo lang('html-176'); ?>： </th>
					<td><input class="input-text" type="text" name="data[name]" value="<?php echo $data['name']; ?>" id="dr_name" onblur="d_topinyin('fieldname','name');" size="20" />
					<div class="onShow" id="dr_name_tips"><?php echo lang('html-186'); ?></div>
					</td>
				</tr>
				<tr>
					<th><font color="red">*</font>&nbsp;<?php echo lang('html-177'); ?>： </th>
					<td><input class="input-text" type="text" name="data[fieldname]" value="<?php echo $data['fieldname']; ?>" <?php if ($data['id']) { ?>disabled<?php } ?> id="dr_fieldname" size="30" />
					<div class="onShow" id="dr_fieldname_tips"><?php echo lang('html-187'); ?></div>
					</td>
				</tr>
				<?php if ($ismain) { ?>
				<input name="data[ismain]" type="hidden" value="1" />
				<?php } else { ?>
				<tr>
                    <th><font color="red">*</font>&nbsp;<?php echo lang('html-180'); ?>：</th>
                    <td>
                    <input type="radio" name="data[ismain]" value="0" <?php if ($id) { ?>disabled<?php }  echo dr_set_radio('ismain', $data['ismain'], '0', TRUE); ?> />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[ismain]" value="1" <?php if ($id) { ?>disabled<?php }  echo dr_set_radio('ismain', $data['ismain'], '1'); ?> />&nbsp;<?php echo lang('yes'); ?>
					<div class="onShow"><?php echo lang('html-188'); ?></div>
                    </td>
                </tr>
				<?php }  if ($issearch) { ?>
				<tr>
                    <th><font color="red">*</font>&nbsp;<?php echo lang('html-415'); ?>：</th>
                    <td>
                    <input type="radio" name="data[issearch]" value="0" <?php echo dr_set_radio('issearch', $data['issearch'], '0'); ?> />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[issearch]" value="1" <?php echo dr_set_radio('issearch', $data['issearch'], '1', TRUE); ?> />&nbsp;<?php echo lang('yes'); ?>
					<div class="onShow"><?php echo lang('html-416'); ?></div>
                    </td>
                </tr>
				<?php } ?>
				<tr>
					<th><font color="red">*</font>&nbsp;<?php echo lang('html-178'); ?>： </th>
					<td>
					<select id="dr_fieldtype" name="data[fieldtype]" onChange="show_field_option(this.value)" <?php if ($id) { ?>disabled<?php } ?>>
					<option value=""> -- </option>
					<?php if (is_array($ftype)) { $count=count($ftype);foreach ($ftype as $t) { ?>
					<option value="<?php echo $t['id']; ?>" <?php if ($t['id']==$data['fieldtype']) { ?> selected="selected"<?php } ?>> <?php echo $t['name']; ?> </option>
					<?php } } ?>
					</select>
                    <span id="dr_loading" style="display:none"><img src="<?php echo SITE_URL; ?>dayrui/statics/images/onLoad.gif" height="10" /></span>
					</td>
				</tr>
				<tbody id="dr_option">
				</tbody>
				</table>
			</div>
			<div id="cnt_1" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
                <tr>
                    <th width="200"><?php echo lang('html-552'); ?>：</th>
                    <td>
                    <input type="radio" name="data[setting][validate][xss]" value="0" <?php if ($data['setting']['validate']['xss']==0) { ?>checked<?php } ?>  />&nbsp;<?php echo lang('close'); ?>&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[setting][validate][xss]" value="1" <?php if ($data['setting']['validate']['xss']==1) { ?>checked<?php } ?>  />&nbsp;<?php echo lang('open'); ?><div class="onShow"><?php echo lang('html-553'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th width="200"><?php echo lang('html-189'); ?>：</th>
                    <td>
                    <input type="radio" name="data[setting][validate][required]" value="0" <?php if ($data['setting']['validate']['required']==0) { ?>checked<?php } ?> onclick="set_required(0)" />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[setting][validate][required]" value="1" <?php if ($data['setting']['validate']['required']==1) { ?>checked<?php } ?> onclick="set_required(1)" />&nbsp;<?php echo lang('yes'); ?>
                    </td>
                </tr>
				<tbody id="required" style="display:none">
				<tr>
                    <th><?php echo lang('html-190'); ?>：</th>
                    <td><input class="input-text" type="text" name="data[setting][validate][pattern]" value="<?php echo $data['setting']['validate']['pattern']; ?>" id="dr_pattern" size="30" />
					<select onchange="javascript:$('#dr_pattern').val(this.value)" name="pattern_select">
					<option value=""><?php echo lang('html-190'); ?></option>
					<option value="/^[0-9.-]+$/"><?php echo lang('html-191'); ?></option>
					<option value="/^[0-9-]+$/"><?php echo lang('html-192'); ?></option>
					<option value="/^[a-z]+$/i"><?php echo lang('html-193'); ?></option>
					<option value="/^[0-9a-z]+$/i"><?php echo lang('html-194'); ?></option>
					<option value="/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/">E-mail</option>
					<option value="/^[0-9]{5,20}$/">QQ</option>
					<option value="/^http:\/\//"><?php echo lang('html-195'); ?></option>
					<option value="/^(1)[0-9]{10}$/"><?php echo lang('html-196'); ?></option>
					<option value="/^[0-9-]{6,13}$/"><?php echo lang('html-197'); ?></option>
					<option value="/^[0-9]{6}$/"><?php echo lang('html-198'); ?></option>
					</select>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-199'); ?>：</th>
                    <td><input class="input-text" type="text" name="data[setting][validate][errortips]" value="<?php echo $data['setting']['validate']['errortips']; ?>" id="dr_errortips" size="30" />
					<div class="onShow"><?php echo lang('html-200'); ?></div>
                    </td>
                </tr>
				</tbody>
				<tr>
                    <th><?php echo lang('html-220'); ?>：</th>
                    <td>
                    <input type="radio" name="data[setting][validate][isedit]" value="0" <?php if (!$data['setting']['validate']['isedit']) { ?>checked<?php } ?> />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[setting][validate][isedit]" value="1" <?php if ($data['setting']['validate']['isedit']) { ?>checked<?php } ?> />&nbsp;<?php echo lang('yes'); ?>
					<div class="onShow"><?php echo lang('html-225'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-201'); ?>：</th>
                    <td><input class="input-text" type="text" name="data[setting][validate][check]" value="<?php echo $data['setting']['validate']['check']; ?>" id="dr_check" size="30" />
					<div class="onShow"><?php echo lang('html-202'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-203'); ?>：</th>
                    <td><input class="input-text" type="text" name="data[setting][validate][filter]" value="<?php echo $data['setting']['validate']['filter']; ?>" id="dr_filter" size="30" />
					<div class="onShow"><?php echo lang('html-204'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-205'); ?>：</th>
                    <td><input class="input-text" type="text" name="data[setting][validate][tips]" value="<?php echo $data['setting']['validate']['tips']; ?>" size="30" />
					<div class="onShow"><?php echo lang('html-206'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-207'); ?>：</th>
                    <td>
					<textarea style="height:40px; width:50%" cols="20" rows="2" name="data[setting][validate][formattr]"><?php echo $data['setting']['validate']['formattr']; ?></textarea><br />
                    <font color="gray"><?php echo lang('html-208'); ?></font>
                    </td>
                </tr>
				</table>
			</div>
			<div id="cnt_2" style="display:none" class="dr_hide">
				<table width="100%" class="table_form">
				<tr>
                    <th width="200"><?php echo lang('html-181'); ?>：</th>
                    <td>
                    <input type="radio" name="data[ismember]" value="0" <?php echo dr_set_radio('ismember', $data['ismember'], '0'); ?> />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[ismember]" value="1" <?php echo dr_set_radio('ismember', $data['ismember'], '1', TRUE); ?> />&nbsp;<?php echo lang('yes'); ?>
					<div class="onShow"><?php echo lang('html-209'); ?></div>
                    </td>
                </tr>
				<tr>
                    <th><?php echo lang('html-160'); ?>：</th>
                    <td>
                    <input type="radio" name="data[disabled]" value="0" <?php echo dr_set_radio('disabled', $data['disabled'], '0', TRUE); ?> />&nbsp;<?php echo lang('no'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="data[disabled]" value="1" <?php echo dr_set_radio('disabled', $data['disabled'], '1'); ?> />&nbsp;<?php echo lang('yes'); ?>
					<div class="onShow"><?php echo lang('html-210'); ?></div>
                    </td>
                </tr>
				</table>
			</div>
			<table width="100%" class="table_form">
			<tr>
				<th width="200"> </th>
				<td><input value="<?php echo lang('save'); ?>" type="submit" name="submit" class="button" />
				</td>
			</tr>
			</table>
		</div>
	</div>
</div>
</form>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>