<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 系统功能权限选项
 *
 * 格式：$config['auth'][] = array('name' => 选项名称, 'auth' => array(uri => 权限名称, ....)) , ...
 *
 */

$config['auth'][] = array(
	'name' => lang('193'),
	'auth' => array(
		'admin/system/oplog' => lang('195'),
		'admin/system/index' => lang('061'),
		'admin/upgrade/index' => lang('115'),
		'admin/check/index' => lang('154'),
		'admin/cron/index' => lang('108'),
	)
);
 
$config['auth'][] = array(
	'name' => lang('190'),
	'auth' => array(
		'admin/mail/index' => lang('admin'),
		'admin/mail/add' => lang('add'),
		'admin/mail/edit' => lang('edit'),
		'admin/mail/del' => lang('del'),
		'admin/mail/send' => lang('325'),
		'admin/mail/log' => lang('195'),
	)
);

$config['auth'][] = array(
	'name' => lang('315'),
	'auth' => array(
		'admin/sms/index' => lang('324'),
		'admin/sms/send' => lang('319'),
		'admin/sms/log' => lang('195'),
	)
);

$config['auth'][] = array(
	'name' => lang('012'),
	'auth' => array(
		'admin/menu/index' => lang('admin'),
		'admin/menu/add' => lang('add'),
		'admin/menu/edit' => lang('edit'),
		'admin/menu/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('031'),
	'auth' => array(
		'admin/db/index' => lang('admin'),
		'admin/db/recovery' => lang('032'),
		'admin/db/backup' => lang('033'),
	)
);

$config['auth'][] = array(
	'name' => lang('026'),
	'auth' => array(
		'admin/role/index' => lang('admin'),
		'admin/role/auth' => lang('028'),
		'admin/role/add' => lang('add'),
		'admin/role/edit' => lang('edit'),
		'admin/role/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('029'),
	'auth' => array(
		'admin/root/index' => lang('admin'),
		'admin/root/add' => lang('add'),
		'admin/root/edit' => lang('edit'),
		'admin/root/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('030'),
	'auth' => array(
		'admin/verify/index' => lang('admin'),
		'admin/verify/add' => lang('add'),
		'admin/verify/edit' => lang('edit'),
		'admin/verify/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('060'),
	'auth' => array(
		'admin/site/index' => lang('admin'),
		'admin/site/add' => lang('add'),
		'admin/site/config' => lang('061'),
		'admin/site/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('038'),
	'auth' => array(
		'admin/navigator/index' => lang('admin'),
		'admin/navigator/add' => lang('add'),
		'admin/navigator/edit' => lang('edit'),
		'admin/navigator/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('097'),
	'auth' => array(
		'admin/field/index' => lang('admin'),
		'admin/field/add' => lang('add'),
		'admin/field/edit' => lang('edit'),
		'admin/field/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('219'),
	'auth' => array(
		'admin/application/index' => lang('admin'),
		'admin/application/store' => lang('084'),
		'admin/application/config' => lang('061'),
		'admin/application/install' => lang('170'),
		'admin/application/uninstall' => lang('171'),
	)
);

$config['auth'][] = array(
	'name' => lang('073'),
	'auth' => array(
		'admin/module/index' => lang('admin'),
		'admin/module/store' => lang('084'),
		'admin/module/config' => lang('061'),
		'admin/module/install' => lang('170'),
		'admin/module/uninstall' => lang('171'),
	)
);

$config['auth'][] = array(
	'name' => lang('211'),
	'auth' => array(
		'admin/attachment/index' => lang('admin'),
		'admin/attachment/unused' => lang('212'),
		'admin/attachment/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('152'),
	'auth' => array(
		'admin/page/index' => lang('admin'),
		'admin/page/add' => lang('add'),
		'admin/page/edit' => lang('edit'),
		'admin/page/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('185'),
	'auth' => array(
		'admin/linkage/index' => lang('admin'),
		'admin/linkage/add' => lang('add'),
		'admin/linkage/edit' => lang('edit'),
		'admin/linkage/data' => lang('074'),
		'admin/linkage/adds' => lang('153'),
		'admin/linkage/edits' => lang('157'),
		'admin/linkage/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('203'),
	'auth' => array(
		'admin/block/index' => lang('admin'),
		'admin/block/add' => lang('add'),
		'admin/block/edit' => lang('edit'),
		'admin/block/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('245'),
	'auth' => array(
		'admin/form/index' => lang('admin'),
		'admin/form/add' => lang('add'),
		'admin/form/edit' => lang('edit'),
		'admin/form/del' => lang('del'),
		'admin/form/listc' => lang('246'),
	)
);

$config['auth'][] = array(
	'name' => lang('038'),
	'auth' => array(
		'admin/urlrule/index' => lang('admin'),
		'admin/urlrule/add' => lang('add'),
		'admin/urlrule/edit' => lang('edit'),
		'admin/urlrule/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('230'),
	'auth' => array(
		'admin/tpl/index' => lang('admin'),
		'admin/tpl/add' => lang('add'),
		'admin/tpl/edit' => lang('edit'),
		'admin/tpl/del' => lang('del'),
		'admin/tpl/tag' => lang('233'),
	)
);

$config['auth'][] = array(
	'name' => lang('231'),
	'auth' => array(
		'admin/theme/index' => lang('admin'),
		'admin/theme/add' => lang('add'),
		'admin/theme/edit' => lang('edit'),
		'admin/theme/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-031'),
	'auth' => array(
		'member/admin/home/index' => lang('admin'),
		'member/admin/home/add' => lang('add'),
		'member/admin/home/edit' => lang('edit'),
		'member/admin/home/del' => lang('del'),
		'member/admin/experience/index' => SITE_EXPERIENCE,
		'member/admin/score/index' => SITE_SCORE,
	)
);

$config['auth'][] = array(
	'name' => lang('m-032'),
	'auth' => array(
		'member/admin/group/index' => lang('admin'),
		'member/admin/group/add' => lang('add'),
		'member/admin/group/edit' => lang('edit'),
		'member/admin/group/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-034'),
	'auth' => array(
		'member/admin/level/index' => lang('admin'),
		'member/admin/level/add' => lang('add'),
		'member/admin/level/edit' => lang('edit'),
		'member/admin/level/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-035'),
	'auth' => array(
		'member/admin/setting/oauth' => 'OAuth2',
		'member/admin/setting/index' => lang('m-035'),
		'member/admin/setting/permission' => lang('028'),
		'member/admin/setting/pay' => lang('m-161'),
		'member/admin/setting/space' => lang('255'),
	)
);

$config['auth'][] = array(
	'name' => lang('234'),
	'auth' => array(
		'member/admin/menu/index' => lang('admin'),
		'member/admin/menu/add' => lang('add'),
		'member/admin/menu/edit' => lang('edit'),
		'member/admin/menu/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-158'),
	'auth' => array(
		'member/admin/pay/index' => lang('admin'),
		'member/admin/pay/card' => lang('m-164'),
		'member/admin/pay/addcard' => lang('m-165'),
		'member/admin/pay/add' => lang('m-162'),
		'member/admin/menu/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('230'),
	'auth' => array(
		'member/admin/tpl/index' => lang('admin'),
		'member/admin/tpl/add' => lang('add'),
		'member/admin/tpl/edit' => lang('edit'),
		'member/admin/tpl/del' => lang('del'),
		'member/admin/tpl/tag' => lang('233'),
	)
);

$config['auth'][] = array(
	'name' => lang('231'),
	'auth' => array(
		'member/admin/theme/index' => lang('admin'),
		'member/admin/theme/add' => lang('add'),
		'member/admin/theme/edit' => lang('edit'),
		'member/admin/theme/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-236'),
	'auth' => array(
		'member/admin/space/index' => lang('admin'),
		'member/admin/space/edit' => lang('edit'),
		'member/admin/space/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('m-256'),
	'auth' => array(
		'member/admin/spacetpl/index' => lang('admin'),
		'member/admin/spacetpl/add' => lang('add'),
		'member/admin/spacetpl/edit' => lang('edit'),
		'member/admin/spacetpl/del' => lang('del'),
		'member/admin/spacetpl/permission' => lang('028'),
	)
);

$config['auth'][] = array(
	'name' => lang('158'),
	'auth' => array(
		'member/admin/model/index' => lang('admin'),
		'member/admin/model/add' => lang('add'),
		'member/admin/model/edit' => lang('edit'),
		'member/admin/model/del' => lang('del')
	)
);

$config['auth'][] = array(
	'name' => lang('242'),
	'auth' => array(
		'member/admin/content/index' => lang('admin'),
		'member/admin/content/edit' => lang('114'),
		'member/admin/content/del' => lang('del')
	)
);