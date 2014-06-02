<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	$(".main table tr:last td").css('border-bottom','none');
});
</script>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
        <div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main dr_table">
				<table style="table-layout:fixed;">
					<thead>
						<tr>
							<th style="width:120px;" class="algin_l">名称</th>
							<th style="width:150px;" class="algin_l">费用</th>
							<th class="algin_l"></th>
						</tr>
					</thead>
					<tbody>
					<?php if (is_array($group)) { $count=count($group);foreach ($group as $t) { ?>
					<tr>
						<td class="algin_l"><?php echo $t['name']; ?></td>
						<td class="algin_l">
						<?php if ($t['unit']==1) {  echo intval($t['price']);  echo SITE_SCORE;  } else {  echo $t['price'];  echo SITE_MONEY;  } ?>
						/
						<?php if ($t['limit']==1) { ?>月
						<?php } else if ($t['limit']==2) { ?>半年
						<?php } else if ($t['limit']==3) { ?>年
						<?php } else if ($t['limit']==4) { ?>永久
						<?php } ?>
						</td>
						<td class="algin_l">
						<a href="<?php echo dr_url('account/permission', array('groupid'=>$t['id'])); ?>">权限查看</a>
						&nbsp;&nbsp;
						<a href="<?php echo dr_url('account/upgrade', array('id'=>$t['id'])); ?>"><?php if ($t['id']==$member['groupid']) { ?><font color=green>立即续费</font><?php } else { ?>立即申请<?php } ?></a>
						</td>
					</tr>
					<?php } } ?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>