<?php if (is_array($list)) { $count=count($list);foreach ($list as $t) { ?>
<tr id="dr_row_<?php echo $t['id']; ?>">
	<td class="algin_l"><a href="<?php echo $t['attachment']; ?>" target="_blank"><?php echo $t['filename']; ?></a></td>
	<td class="algin_c"><?php echo dr_format_file_size($t['filesize']); ?></td>
	<td class="algin_c"><?php echo $t['fileext']; ?></td>
	<td class="algin_c"><?php echo dr_date($t['inputtime']); ?></td>
	<td class="algin_c"><a href="javascript:;" onclick="dr_delete('<?php echo $t['id']; ?>')">删除</a></td>
</tr>
<?php } } ?>