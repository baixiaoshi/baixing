<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	$(".section_main table tr:last td").css('border-bottom','none');
});
function dr_show_more(id) {
	$(".dr_clear").hide();
	$(".dr_hide_"+id).show();
}
</script>
<style type="text/css">
.dr_table td {
    border-bottom: 1px solid #EEEEEE;
    height: 25px;
    line-height: 25px;
    padding-bottom: 5px;
    padding-top: 5px;
}
</style>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
	<div class="section_wp">
		<div class="message message_info">
			当前会员组：<?php echo $member['group']['name'];  if ($member['levelid']) { ?>，会员等级：<?php echo $member['level']['name'];  }  if ($member['overdue']) { ?>，过期时间：<?php if ($member['overdue']>2000000000) { ?>永久<?php } else {  echo dr_date($member['overdue']);  }  } ?>
		</div>
        <div class="section_title">
        	<?php if (is_array($category)) { $count=count($category);foreach ($category as $p=>$name) { ?>
        	<a href="<?php echo dr_member_url('account/permission', array('groupid' => $groupid, 'levelid' => $levelid, 'page' => $p)); ?>" <?php if ($page==$p) { ?>class="cur"<?php } ?>><?php echo $name; ?></a>
			<?php } } ?>
        </div>
        <div class="section_main">
            <div class="msearch mbutton">
            <?php if (is_array($group['level'])) { $count=count($group['level']);foreach ($group['level'] as $lid=>$t) { ?>
            <a href="<?php echo dr_member_url('account/permission', array('groupid' => $groupid, 'levelid' => $lid, 'page' => $page)); ?>" <?php if ($lid==$levelid) { ?>class="cur"<?php } ?>><?php echo $t['name']; ?></a>
            <?php } } ?>
            </div>
            <div class="bk10"></div>
            <?php echo $content; ?>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>