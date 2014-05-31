<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo dr_lang('html-001', SITE_NAME); ?></title>
		<meta name="robots" content="noindex,nofollow">
		<link href="<?php echo SITE_PATH; ?>dayrui/statics/css/admin_login.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo SITE_PATH; ?>member/statics/js/jquery.min.js"></script>
		<script type="text/javascript">
        $(function() {
            <?php if ($username) { ?>
            $("#password").focus();
            <?php } else { ?>
            $("#username").focus();
            <?php } ?>
        }); 
        </script>
	</head>
<body>
<div class="wrap">
    <h1><a href="<?php echo SITE_PATH; ?>"><?php echo dr_lang('html-001', SITE_NAME); ?></a></h1>
    <form method="post" name="login" action="" autoComplete="off">
        <div class="login">
            <ul>
                <li>
                    <input id="username" class="input" required name="username" value="<?php echo $username; ?>" type="text" placeholder="<?php echo lang('html-532'); ?>" title="<?php echo lang('html-532'); ?>" />
                </li>
                <li>
                    <input id="password" class="input" type="password" required name="password" placeholder="<?php echo lang('html-061'); ?>" title="<?php echo lang('html-061'); ?>" />
                </li>
                <?php if (SITE_ADMIN_CODE) { ?>
                <li>
                    <input style="width:130px" class="input" type="text" name="code" placeholder="<?php echo lang('html-077'); ?>" />
					<?php echo dr_code(90,32); ?>
                </li>
                <?php } ?>
            </ul>
            <button type="submit" name="submit" class="btn"><?php echo lang('html-076'); ?></button>
        </div>
    </form>
</div>
</body>
</html>