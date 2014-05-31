
/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
function dr_tips(msg, time, mark) {
	art.dialog.tips(msg, time, mark);
}

function dr_confirm_url(title, url) {
	art.dialog.confirm(title, function() {
		dr_tips(lang['waiting'], 3, 1);
		window.location.href = url;
		return true;
	});
}

function dr_dialog_msg(msg) {
	var throughBox = art.dialog.through;
	throughBox({
		content: msg,
		lock: true,
		opacity: 0.1
	});
}

function dr_add_favorite(url, title) {
	try {
		window.external.addFavorite(url, title);
	} catch (e){
		try {
			window.sidebar.addPanel(title, url, '');
        	} catch (e) {
			dr_dialog_msg(fc_lang[28]);
		}
	}
}

function dr_set_homepage(url) {
	if ($.browser.msie) {
		document.body.style.behavior = 'url(#default#homepage)';
		document.body.setHomePage(url);
	} else {
		dr_tips(fc_lang[29], 3);
	}
}

function dr_remove_file(name, id) {
	art.dialog.confirm(lang['confirm'], function() {
		var fileid = $('#fileid_'+name+'_'+id).val();
		var value = $('#dr_'+name+'_del').val();
		$('#files_'+name+'_'+id).remove();
		$('#dr_'+name+'_del').val(value+'|'+fileid);
	});
}

function dr_edit_file(url, name, id) {
	art.dialog.open(url, {
		title: lang['upload'],
		opacity: 0.1,
		width: 550,
		height:400,
		ok: function () {
			var iframe = this.iframe.contentWindow;
			if (!iframe.document.body) {
				alert("iframe loading")
				return false;
			};
			var value = iframe.document.getElementById("att-status").innerHTML;
			if (value == "" || value == undefined) {
				alert(lang['notselectfile']);
				return false;
			} else {
				var file = value.split("|");
				var info = file[1].split(",");
				$("#fileid_"+name+"_"+id).val(info[0]); // id或者引用文件地址
				$('#span_'+name+'_'+id).html("<a href=\"javascript:;\" onclick=\"dr_show_file_info(\'"+info[0]+"\')\"><img align=\"absmiddle\" src="+info[1]+"></a><div class=\"onCorrect\">"+info[2]+"&nbsp;</div>"); // 扩展名图标
				$("."+name+"_"+id+"_pan input").val("");
				return true;
			}
		},
		cancel: true
	});
}

// 上传多文件
function dr_upload_files(name, url, pan, count) {
	var size = $('#'+name+'-sort-items li').size();
	var total = count - size;
	pan = decodeURIComponent(pan);
	art.dialog.open(url+'&count='+total, {
		title: lang['upload'],
		opacity: 0.1,
		width: 550,
		height:400,
		ok: function () {
			var iframe = this.iframe.contentWindow;
			if (!iframe.document.body) {
				alert("iframe loading")
				return false;
			};
			var value = iframe.document.getElementById("att-status").innerHTML;
			if (value == "" || value == undefined) {
				return false;
			} else {
				var file = value.split("|");
				for (var i in file) {
					var filepath = file[i];
					var id = parseInt(size)+parseInt(i);
					if (filepath) {
						var info = filepath.split(",");
						if ($('#'+name+'-sort-items [value="'+info[0]+'"]').length>0) {
							alert(fc_lang[27]);
							return false;
						}
						if (!info[3]) info[3] = info[0];
						info[3] = dr_remove_ext(info[3]);
						var c ='';
						c += '<li id="files_'+name+'_'+id+'" list="'+id+'" style="cursor:move;"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
						c += '<td width="80" style="text-align:right">';
						c += '<a href="javascript:;" onclick="dr_remove_file(\''+name+'\',\''+id+'\')">';
						c += '<img align="absmiddle" src="'+homeurl+'dayrui/statics/images/b_drop.png"></a>';
						c += '</td><td>';
						c += '<input type="hidden" value="'+info[0]+'" name="data['+name+'][file][]" id="fileid_'+name+'_'+id+'" />';
						c += '<input type="hidden" value="" id="pan_'+name+'_'+id+'" name="data['+name+'][pan][]" />';
						c += '<input type="text" class="input-text" style="width:300px;" value="'+info[3]+'" name="data['+name+'][title][]" />';
						c += '<span id="span_'+name+'_'+id+'">&nbsp;';
						c += '<a href="javascript:;" onclick="dr_show_file_info(\''+info[0]+'\')">';
						c += '<img align="absmiddle" src="'+info[1]+'"></a>&nbsp;';
						c += '<div class="onCorrect">'+info[2]+'&nbsp;</div></span></td><tr>';
						if (pan != "undefined") {
							var _pan = pan.replace(/\+/g, ' ');
							_pan = _pan.replace(/{id}/g, id);
							c += _pan;
						}
						c += '</table></li>';
						$('#'+name+'-sort-items').append(c);
					}
				}
				return true;
			}
		},
		cancel: true
	});
}

// 会员登录
function dr_login() {
	art.dialog.open(memberpath+"index.php?c=login&m=ajax", {
		title: lang['login'],
		opacity: 0.1,
		lock: true,
		width: 380,
		height:350,
		ok: function () {
			window.location.reload(true);
		},
		cancel: true
	});
}

// 聊天窗口
function dr_chat(_this) {
	var uid = $(_this).attr("uid");
	var online = $(_this).attr("online");
	var username = $(_this).attr("username");
	if (online == 1) {
		var title = '正在与'+username+'聊天中... [在线]';
	} else {
		var title = '正在与'+username+'聊天中... [离线]';
	}
	var throughBox = $.dialog.through;
	var dr_dialog = throughBox({id: 'dr_webchat', title: title, padding:0,width: 420,height: 480});
	var url = memberpath+"index.php?c=pm&m=webchat&username="+username+"&uid="+uid+"&online="+online+"&"+Math.random();
	$.ajax({type: "GET", url:url, dataType:'jsonp',jsonp:"callback",async: false,
	    success: function (text) {
			dr_dialog.content(text.html);
	    },
	    error: function(HttpRequest, ajaxOptions, thrownError) {
			dr_dialog.close();
			dr_login();
		}
	});
}

// 上传单文件
function dr_upload_file(name, url) {
	art.dialog.open(url, {
		title: lang['upload'],
		opacity: 0.1,
		width: 550,
		height:400,
		ok: function () {
			var iframe = this.iframe.contentWindow;
			if (!iframe.document.body) {
				alert("iframe loading")
				return false;
			};
			var value = iframe.document.getElementById("att-status").innerHTML;
			if (value == "" || value == undefined) {
				alert(lang['notselectfile']);
				return false;
			} else {
				var file = value.split("|");
				var info = file[1].split(",");
				var finfo = info[3]+" ("+info[2]+")";
				if (!info[3]) {
					finfo = "";
				}
				$("#dr_"+name).val(info[0]); // id或者引用文件地址
				$("#show_"+name).html("<a href=\"javascript:;\" onclick=\"dr_show_file_info(\'"+info[0]+"\')\"><img align=\"absmiddle\" src="+info[1]+"></a><div class=\"onCorrect\">"+finfo+"&nbsp;</div>"); // 扩展名图标
				return true;
			}
		},
		cancel: true
	});
}

function dr_show_file_info(name) {
	var throughBox = $.dialog.through;
	var dr_dialog = throughBox({title: lang['fileinfo']});
	var url = memberpath+"index.php?c=api&m=fileinfo&name="+name+"&rand="+Math.random();
	// ajax调用窗口内容
	$.ajax({type: "GET", url:url, dataType:'text',
	    success: function (text) {
			var win = $.dialog.top;
			dr_dialog.content(text);
	    },
	    error: function(HttpRequest, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
		}
	});
}

// 文件上传
function dr_upload(name, ext, size, count) {
	alert('此函数已废弃');return;
	var throughBox = $.dialog.through;
	var dr_dialog = throughBox({title: "upload"});
	var url = memberpath+"index.php?c=api&m=upload&name"+name+"&ext="+ext+"&size="+size+"&count="+count+"&rand="+Math.random();
	// ajax调用窗口内容
	$.ajax({type: "GET", url:url, dataType:'text',
	    success: function (text) {
			var win = $.dialog.top;
			dr_dialog.content(text);
			// 添加按钮
			dr_dialog.button({name: _title,
				callback:function() {
					// 标示可以提交表单
					win.$("#mark").val("0");
					// 按钮返回验证表单函数
					if (win.dr_form_check()) {
						var _data = win.$("#myform").serialize();
						// 将表单数据ajax提交验证
						$.ajax({type: "POST",dataType:"json", url: url, data: _data,
							success: function(data) {
								//验证成功
								if (data.status == 1) {
									dr_dialog.close();
									$.dialog.tips(data.code, 2, 1);
									var _url = window.location.href;
									var _id = window.location.hash;
									//如果url中已经存在#了，就替换掉
									if (_id) {
										_url = _url.replace(_id, '');
									}
									// 赋值并刷新页
									art.dialog.data('dr_row', _url+"#dr_row_"+data.id);
									window.location.reload(true);
								} else {
									//验证失败
									win.d_tips(data.id, false, data.code);
									return false;
								}
							},
							error: function(HttpRequest, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
							}
						});
					}
					return false;
				},
				focus: true
			});
	    },
	    error: function(HttpRequest, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
		}
	});
}

// 去掉扩展名
function dr_remove_ext(str){
	var reg = /\.\w+$/;
	return str.replace(reg,'');
} 