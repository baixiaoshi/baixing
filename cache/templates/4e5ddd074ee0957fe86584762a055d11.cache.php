<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<div class="content clearfix">
	<?php if ($fn_include = $this->_include("navigator.html")) include($fn_include); ?>
    <div class="article">
    	<div class="section">
            <div class="title"><strong><?php echo $meta_name; ?></strong></div>
            <div class="main">
                <?php if ($ucenter) { ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="72%">
                        <h1>头像保存后，您可能需要刷新一下本页面(按F5键)，才能查看最新的头像效果</h1><br>
                        <?php echo uc_avatar($ucenter); ?>
                    </td>
                    <td width="28%" valign="top"><img src="<?php echo dr_avatar($uid, 90); ?>"></td>
                  </tr>
                </table>
                <?php } else { ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="72%">
                        <script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/avatar/swfobject.js"></script>
                        <script type="text/javascript">
                            var flashvars = {
                                'upurl':"<?php echo base64_encode(dr_member_url('account/upload')); ?>&callback=return_avatar&"
                            }; 
                            var params = {
                                'align':'middle',
                                'play':'true',
                                'loop':'false',
                                'scale':'showall',
                                'wmode':'window',
                                'devicefont':'true',
                                'id':'Main',
                                'bgcolor':'#ffffff',
                                'name':'Main',
                                'allowscriptaccess':'always'
                            }; 
                            var attributes = {
                                
                            }; 
                            swfobject.embedSWF("<?php echo MEMBER_PATH; ?>statics/avatar/main.swf", "myContent", "490", "434", "9.0.0","<?php echo MEMBER_PATH; ?>statics/avatar/expressInstall.swf", flashvars, params, attributes);
                            function return_avatar(data) {
                                if(data == 1) {
                                    alert('上传成功');
                                    window.location.reload();
                                } else {
                                    alert('上传失败');
                                }
                            }
                        </script>
                        <div id="myContent"> 
                          <p>Loading ... </p> 
                        </div>
                    </td>
                    <td width="28%" valign="top"><img src="<?php echo dr_avatar($uid, 90); ?>"><br><img src="<?php echo dr_avatar($uid, 45); ?>"></td>
                  </tr>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>