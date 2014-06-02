	<div class="page_url"><a href="<?php echo SITE_URL; ?>">首页</a> <span>&gt;</span> <a href="<?php echo dr_member_url('home/index'); ?>">会员中心</a> <span>&gt;</span> <?php echo $meta_name; ?></div>
    <div class="aside">
		<div class="round memberinfo">
            <table width="178" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td rowspan="2" width="50"><div class="memberinfo_avatar"><a href="<?php echo dr_member_url('account/avatar'); ?>"><img src="<?php echo dr_avatar($uid); ?>"></a></div></td>
                <td><b>&nbsp;<?php echo $member['username']; ?></b></td>
                </tr>
              <tr>
                <td colspan="2"><div class="dr_stars"><a href="<?php echo dr_member_url('account/permission'); ?>" title="<?php echo $member['level']['name']; ?>"><?php echo dr_show_stars($member['level']['stars']); ?></a></div></td>
              </tr>
            </table>   
		</div>
        <?php if (is_array($menu[$menu_tid]['left'])) { $count=count($menu[$menu_tid]['left']);foreach ($menu[$menu_tid]['left'] as $m) { ?>
        <div class="menu round">
            <h4><strong><?php echo $m['name']; ?></strong></h4>
            <ul>
				<?php if (is_array($m['link'])) { $count=count($m['link']);foreach ($m['link'] as $t) { ?>
                <li><a href="<?php if ($t['url']) {  echo $t['url'];  } else {  echo dr_member_url($t['uri']);  } ?>" <?php if ($t['target']) { ?>target="_blank"<?php }  if ($menu_id==$t['id']) { ?>class="cur"<?php } ?>><?php echo $t['name']; ?></a></li>
                <?php } } ?>
            </ul>
        </div>
		<?php } } ?>
    </div>