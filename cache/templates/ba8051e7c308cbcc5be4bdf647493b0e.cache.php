<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function() {
	$(".table-list td").last().css('border-bottom','1px solid #EEEEEE');
	$.getScript("http://www.dayrui.com/index.php?c=sys&m=app_update&data=<?php echo $store; ?>&admin=<?php echo SELF; ?>");
}); 
</script>
<style>
.dr_none td {background-color: infobackground;}
</style>
<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<?php echo $menu; ?>
	</div>
	<div class="bk10"></div>
	<div class="explain-col">
		<font color="gray"><?php echo lang('html-567'); ?></font>
	</div>
	<div class="bk10"></div>
	<div class="table-list">
		<table width="100%">
		<thead>
		<tr>
			<th width="40" align="center"><?php echo lang('html-626'); ?></th>
			<th width="150" align="left"><?php echo lang('html-026'); ?></th>
			<th width="90" align="left"><?php echo lang('html-046'); ?></th>
			<th width="50" align="left"><?php echo lang('html-158'); ?></th>
			<th width="180" align="left"><?php echo lang('html-159'); ?></th>
			<th align="left"><?php echo lang('option'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if (is_array($list[1])) { $count=count($list[1]);foreach ($list[1] as $dir=>$t) { ?>
		<tr>
			<td align="center"><?php if ($this->ci->is_auth('application/config')) { ?><a href="javascript:;" onClick="return dr_dialog_set('<?php echo $t['disabled'] ? lang('html-161') : lang('html-162'); ?>','<?php echo dr_url('application/disabled',array('id'=>$t['id'])); ?>');"><img src="<?php echo SITE_PATH; ?>dayrui/statics/images/<?php echo $t['disabled'] ? 0 : 1 ?>.gif"></a><?php } else { ?><img src="<?php echo SITE_PATH; ?>dayrui/statics/images/<?php echo $t['disabled'] ? 0 : 1 ?>.gif"></a><?php } ?></td>
			<td align="left"><?php echo $t['name']; ?></td>
			<td align="left"><?php echo $dir; ?></td>
            <td align="left"><?php echo $t['version']; ?></td>
			<td align="left"><?php echo $t['author']; ?></a></td>
			<td align="left">
			<?php if ($this->ci->is_auth('application/config')) { ?><a style="color:#00F" href="<?php echo dr_url($dir.'/home/index'); ?>"><?php echo lang('061'); ?></a>&nbsp;&nbsp;<?php }  if ($this->ci->is_auth('application/uninstall')) { ?><a href="javascript:;" onClick="return dr_confirm_url('<?php echo lang('015'); ?>','<?php echo dr_url($dir.'/home/uninstall'); ?>');"><?php echo lang('html-164'); ?></a>&nbsp;&nbsp;<?php }  if ($this->ci->is_auth('application/update')) { ?><a class="dr_update_<?php echo $t['key']; ?>" style="display:none; color:#090" href="<?php echo dr_url('application/update',array('id'=>$dir)); ?>"><?php echo lang('html-167'); ?></a>&nbsp;&nbsp;<?php }  if ($t['key']) { ?><span class="dr_check_<?php echo $t['key']; ?>">正在云端检查版本...</span><?php } ?>
			</td>
		</tr>
		<?php } }  if (is_array($list[0])) { $count=count($list[0]);foreach ($list[0] as $dir=>$t) { ?>
		<tr class="dr_none">
			<td align="center">
				<?php if ($this->ci->is_auth('application/install')) { ?>
				<a href="javascript:void(0);" onclick="dr_install('<?php echo lang('html-000'); ?>', '<?php echo dr_url($dir.'/home/install'); ?>')" style="color:#00F">
				<?php } else { ?>
				<a href="javascript:;" style="color:#999">
				<?php }  echo lang('html-163'); ?></a>
			</td>
			<td align="left"><?php echo $t['name']; ?></td>
			<td align="left"><?php echo $dir; ?></td>
            <td align="left"><?php echo $t['version']; ?></td>
			<td align="left"><?php echo $t['author']; ?></td>
			<td align="left">
			
			</td>
		</tr>
		<?php } } ?>
		</tbody>
		</table>
	</div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>