<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function(){
	$(".main table tr:last td").css('border-bottom','none');
});
</script>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
        <div class="message message_info">OAuth2快捷登录绑定</div>
        <div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main dr_table">
                <table style="table-layout:fixed;">
                    <thead>
                        <tr>
                            <th style="width:40px;" class="algin_c">服务商</th>
                            <th style="width:50px;" class="algin_l">头像</th>
                            <th style="width:120px;" class="algin_l">昵称</th>
                            <th style="width:120px;" class="algin_l">到期时间</th>
                            <th class="algin_r"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $return = $this->list_tag("action=cache name=OAUTH"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) {  $id=$t['id']; ?>
                    <tr>
                        <td class="algin_c"><img align="absmiddle" src="<?php echo MEMBER_PATH; ?>statics/OAuth/<?php echo $t['icon']; ?>.png"></td>
                        <td class="algin_c memberinfo_avatar" style="padding:10px 0;">
                        <?php if (isset($list[$id])) { ?>
                        <img align="absmiddle" src="<?php echo $list[$id]['avatar']; ?>">
                        <?php } ?>
                        </td>
                        <td class="algin_l"><?php if (isset($list[$id])) {  echo $list[$id]['nickname'];  } ?></td>
                        <td class="algin_l"><?php if (isset($list[$id])) {  echo dr_date($list[$id]['expire_at']);  } ?></td>
                        <td>
                        <?php if (isset($list[$id])) {  if (!$regoauth) { ?>
                        <a href="javascript:;" style="color:red;" onclick="dr_confirm_url('您确认要解除绑定吗？', '<?php echo dr_member_url('account/jie', array('id' => $id)); ?>')">解除绑定</a>
						<?php }  } else { ?>
                        <a href="javascript:;" style="color:blue;" onclick="window.location.href='<?php echo SITE_URL; ?>member/index.php?c=account&m=bang&id=<?php echo $id; ?>'">立即绑定</a>
                        <?php } ?>
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