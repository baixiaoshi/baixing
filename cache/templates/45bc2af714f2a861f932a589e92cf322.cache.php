<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<?php echo $menu; ?><span>|</span><a href="<?php echo SYS_HELP_URL; ?>15.html" target="_blank"><em><?php echo lang('help'); ?></em></a>
	</div>
	<div class="bk10"></div>
	<div class="explain-col">
		<font color="gray"><?php echo lang('html-393'); ?></font>
	</div>
	<div class="bk10"></div>
	<div class="table-list">
		<form action="" method="post" name="myform" id="myform">
		<input name="action" id="action" type="hidden" value="order" />
		<table width="100%">
		<thead>
		<tr>
			<th width="20" align="right"><input name="dr_select" id="dr_select" type="checkbox" onClick="dr_selected()" />&nbsp;</th>
			<th width="30" align="center"><?php echo lang('order'); ?></th>
			<th width="100" align="left"><?php echo lang('html-176'); ?></th>
			<th width="150" align="left"><?php echo lang('html-177'); ?></th>
			<th width="80" align="left"><?php echo lang('html-178'); ?></th>
			<th width="50" align="center"><?php echo lang('html-179'); ?></th>
			<th width="50" align="center"><?php echo lang('html-180'); ?></th>
			<th width="50" align="center"><?php echo lang('html-181'); ?></th>
			<th width="50" align="center"><?php echo lang('html-626'); ?></th>
			<th align="left"><?php echo lang('option'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if (is_array($list)) { $count=count($list);foreach ($list as $t) { ?>
		<tr style="color:<?php echo $group[$t['fieldname']]; ?>">
			<td align="right"><input name="ids[]" type="checkbox" class="dr_select" value="<?php echo $t['id']; ?>" />&nbsp;</td>
			<td align="center"><input class="input-text displayorder" type="text" name="data[<?php echo $t['id']; ?>][displayorder]" value="<?php echo $t['displayorder']; ?>" /></td>
			<td align="left"<?php if ($t['fieldtype']=='Group') { ?> style="font-weight:bold"<?php } ?>><?php echo $t['name']; ?></td>
			<td align="left"><?php echo $t['fieldname']; ?></td>
			<td align="left"><?php echo $t['fieldtype']; ?></td>
			<td align="center"><img src="<?php echo SITE_URL; ?>dayrui/statics/images/<?php echo $t['issystem']; ?>.gif"></td>
			<td align="center"><img src="<?php echo SITE_URL; ?>dayrui/statics/images/<?php echo $t['ismain']; ?>.gif"></td>
			<td align="center"><img src="<?php echo SITE_URL; ?>dayrui/statics/images/<?php echo $t['ismember']; ?>.gif"></td>
			<td align="center"><?php if ($this->ci->is_auth('admin/field/edit')) { ?><a href="javascript:;" onClick="return dr_dialog_set('<?php echo $t['disabled'] ? lang('html-161') : lang('html-162'); ?>','<?php echo dr_url('field/disabled', array('rname'=>$rname,'rid'=>$rid,'id'=>$t['id'])); ?>');"><img src="<?php echo SITE_URL; ?>dayrui/statics/images/<?php echo $t['disabled'] ? 0 : 1 ?>.gif"></a><?php } else { ?><img src="<?php echo SITE_URL; ?>dayrui/statics/images/<?php echo $t['disabled'] ? 0 : 1 ?>.gif"></a><?php } ?></td>
			<td align="left">
			<?php if ($this->ci->is_auth('admin/field/edit')) { ?><a href="<?php echo dr_url('field/edit', array('rname'=>$rname,'rid'=>$rid,'id'=>$t['id'])); ?>"><?php echo lang('edit'); ?></a>&nbsp;&nbsp;&nbsp;<?php }  if ($this->ci->is_auth('admin/field/del') && !$t['issystem']) { ?><a href="javascript:;" onClick="return dr_dialog_del('<?php echo lang('015'); ?>','<?php echo dr_url('field/del', array('rname'=>$rname,'rid'=>$rid,'id'=>$t['id'])); ?>');"><?php echo lang('del'); ?></a><?php } ?>
			</td>
		</tr>
		<?php } } ?>
		<tr>
			<th align="right"><input name="dr_select" type="checkbox" onClick="dr_selected()" />&nbsp;</th>
			<td colspan="9" align="left"> 
            <?php if ($this->ci->is_auth('admin/field/del')) { ?><input type="button" class="button" value="<?php echo lang('del'); ?>" name="button" onClick="$('#action').val('del');return dr_confirm_set_all('<?php echo lang('015'); ?>')" />&nbsp;<?php }  if ($this->ci->is_auth('admin/field/edit')) { ?><input type="button" class="button" value="<?php echo lang('order'); ?>" name="button" onclick="$('#action').val('order');return dr_confirm_set_all('<?php echo lang('015'); ?>')" /><?php } ?>
            &nbsp;<div class="onShow"><?php if ($this->ci->is_auth('admin/field/edit')) {  echo lang('html-182'); ?>&nbsp;<?php } ?> &nbsp;
            <?php if (count($group)>0) { ?>&nbsp;<?php echo lang('html-183'); ?>&nbsp;<?php } ?></div>
			</td>
		</tr>
		</tbody>
		</table>
		</form>
	</div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>