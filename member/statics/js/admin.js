/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

function dr_member_rule(id, url, title) {
	var throughBox = $.dialog.through; // 创建窗口
	var dr_Dialog = throughBox({title: title});
	// ajax调用窗口内容
	$.ajax({type: "GET", url:url, dataType:'text', success: function (text) {
			var win = $.dialog.top;
			dr_Dialog.content(text);
			// 添加按钮
			dr_Dialog.button({name: fc_lang[36], callback:function() {
					win.$("#mark").val("0"); // 标示可以提交表单
					if (win.dr_form_check()) { // 按钮返回验证表单函数
						var _data = win.$("#myform").serialize();
						$.ajax({type: "POST",dataType:"json", url: url, data: _data, // 将表单数据ajax提交验证
							success: function(data) {
								$("#dr_status_"+id).attr("class", "onCorrect");
								$("#dr_status_"+id).html("&nbsp;&nbsp;");
								$.dialog.tips(fc_lang[37], 2, 1);
								dr_Dialog.close();
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
 
function dr_install(text, url) {
	var throughBox	= $.dialog.through;
	var dr_Dialog	= throughBox({title: fc_lang['33']});
	dr_Dialog.content("<div style='width:500px;line-height:23px;font-size:13px;padding-bottom:10px'>"+text+"</div>");
	dr_Dialog.button({name: fc_lang['34'],
		callback:function() {
			dr_tips(lang['waiting'], 99, 1);
			dr_goto_url(url);
		},
		focus: true
	}
	,
	{name: fc_lang['35'],
		callback:function() {
			dr_Dialog.close();
		},
		focus: false
	});
}

function dr_dialog_member(id) {
	var name = $("#dr_"+id).val();
	art.dialog.open(siteurl+'?c=api&m=member&username='+name,{title: lang['smember']});
}

function dr_dialog_ip(id) {
	var name = $("#dr_"+id).val();
	if (name) {
		art.dialog.open('http://www.baidu.com/baidu?wd='+name, {title: 'IP', width:700, height:400});
	} else {
		art.dialog.tips('['+id+'] '+lang['iperror'], 3);
	}
}
 
(function (config) {
    config['lock']	= true;
    config['fixed'] = true;
	config['drag']	= true;
	config['esc']	= true;
	config['resize'] = false;
    config['opacity'] = 0.1;
	config['padding'] = '5px 10px 5px 10px';
    // [more..]
})(art.dialog.defaults);

function dr_page_rule() {
	var body = '<style>.table-list2 tbody td, .table-list2 .btn {padding-bottom:1px;padding-top:1px;}</style><table border="0" cellpadding="1" cellspacing="0" class="table-list table-list2">';
	body +='<tr><td>{id}</td><td>Id</td></tr><tr>';
	body +='<tr><td>{page}</td><td>'+lang['page']+'</td></tr>';
	body +='<tr><td>{dirname}</td><td>'+lang['dirname']+'</td></tr>';
	body +='<tr><td>{pdirname}</td><td>'+lang['pdirname']+'</td></tr>';
	body +='</table>&nbsp;';
	var throughBox = art.dialog.through;
	throughBox({
		content: body,
		title: lang['tagurl']
	});
}

function dr_url_rule() {
	var body = '<style>.table-list2 tbody td, .table-list2 .btn {padding-bottom:1px;padding-top:1px;}</style><table border="0" cellpadding="1" cellspacing="0" class="table-list table-list2">';
	body +='<tr><td width="15%">'+lang['tag']+'</td><td width="85%">&nbsp;</td></tr>';
	body +='<tr><td>{id}</td><td>Id</td></tr><tr>';
	body +='<tr><td>{page}</td><td>'+lang['page']+'</td></tr>';
	body +='<tr><td>{dirname}</td><td>'+lang['dirname']+'</td></tr>';
	body +='<tr><td>{pdirname}</td><td>'+lang['pdirname']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['tagvalue']+'</td></tr>';
	body +='<tr><td>'+lang['function']+'</td><td>&nbsp;</td></tr>';
	body +='<tr><td>{md5({id})}</td><td>'+lang['funcvalue1']+'</td></tr>';
	body +='<tr><td>{test($data)}</td><td>'+lang['funcvalue2']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['tagmore']+'</td></tr>';
	body +='</table>';
	var throughBox = art.dialog.through;
	throughBox({
		content: body,
		title: lang['tagurl']
	});
}

function dr_seo_rule() {
	var body = '<style>.table-list tbody td, .table-list .btn {height:25px;line-height:25px;padding-bottom:1px;padding-top:1px;}</style><table border="0" cellpadding="1" cellspacing="0" class="table-list">';
	body +='<tr><td width="15%">'+lang['tag']+'</td><td width="85%">&nbsp;</td></tr>';
	body +='<tr><td>{join}</td><td>'+lang['seojoin']+'</td></tr><tr>';
	body +='<tr><td>{modulename}</td><td>'+lang['seoname']+'</td></tr><tr>';
	body +='<tr><td>[{page}]</td><td>'+lang['seopage']+'</td></tr>';
	body +='<tr><td>{SITE_NAME}</td><td>'+lang['seositename']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['seovalue1']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['seovalue2']+'</td></tr>';
	body +='<tr><td>'+lang['function']+'</td><td>&nbsp;</td></tr>';
	body +='<tr><td>{test($data)}</td><td>'+lang['seofuncvalue']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['seodiy']+'</td></tr>';
	body +='<tr><td>&nbsp;</td><td>'+lang['tagmore']+'</td></tr>';
	body +='</table>';
	var throughBox = art.dialog.through;
	throughBox({
		content: body,
		title: lang['tagseo']
	});
}

function set_frontop(v) {
	if (v==1) {
		$(".tabBut li:gt(1)").show();
	} else {
		$(".tabBut li:gt(1)").hide();
	}
}

function set_urlmode(v) {
	if (v==1) {
		$("#urlmode").show();
	} else {
		$("#urlmode").hide();
	}
}

function set_sitemode(v) {
	if (v==1) {
		$("#sitemode").show();
	} else {
		$("#sitemode").hide();
	}
}

function set_urltohtml(v) {
	if (v==1) {
		$("#html").show();
	} else {
		$("#html").hide();
	}
}

function SwapTab(id) {
	$("#myform .tabBut").children("li").removeClass("on");
	$(".tabBut li:eq("+id+")").attr("class", "on");
	$("#myform .dr_hide").hide();
	$("#cnt_"+id).show();
	$("#myform #page").val(id);
}

// 表单提示
function dr_form_tips(name, status, code) {
	var obj = $("#dr_"+name+"_tips");
	if (status) {
		obj.attr("class", "onCorrect");
	} else {
		obj.attr("class", "onError");
	}
}

function dr_selected() {
	if ($("#dr_select").attr('checked')) {
		$(".dr_select").attr("checked",true);
	} else {
		$(".dr_select").attr("checked",false);
	}
}

function dr_selected_by(id) {
	if ($("#"+id).attr('checked')) {
		$("."+id).attr("checked",true);
	} else {
		$("."+id).attr("checked",false);
	}
}

function dr_goto_url(url) {
	window.location.href = url;
}

function dr_waiting() {
	art.dialog.tips(lang['waiting'], 3, 1);
}

function dr_dialog_show(title, url) {
	var throughBox	= $.dialog.through;
	var dr_Dialog	= throughBox({title: title});
	$.ajax({type: "POST", dataType:"text", url: url, data: {},
		success: function(data) {
			dr_Dialog.content(data);
		},
		error: function(HttpRequest, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
		}
	});
}

function dr_dialog_set(text, url) {
	var throughBox	= $.dialog.through;
	var dr_Dialog	= throughBox({title: lang['tips']});
	dr_Dialog.content('<img src='+memberpath+'statics/js/skins/icons/question.png>&nbsp;&nbsp;'+text);
	// 添加按钮
	dr_Dialog.button({name: lang['ok'],
		callback:function() {
				// 将表单数据ajax提交验证
				$.ajax({type: "POST", dataType:"json", url: url, data: {},
					success: function(data) {
						if (data.status == 1) {
							dr_Dialog.close();
							$.dialog.tips(data.code, 2, 1);
							// 刷新页
							setTimeout('window.location.reload(true)', 2000);
						} else {
							$.dialog.tips(data.code, 2, 1);
						}
					},
					error: function(HttpRequest, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
					}
				});
		},
		focus: true
	}
	,
	{name: lang["cancel"],
		callback:function() {
			dr_Dialog.close();
		},
		focus: false
	});
}

function dr_confirm_set_all(title, del) {
	art.dialog.confirm(title, function(){
		var _data = $("#myform").serialize();
		var _url = window.location.href;
		if ((_data.split('ids')).length-1 <= 0) {
			$.dialog.tips(lang['select_null'], 2);
			return true;
		}
		// 将表单数据ajax提交验证
		$.ajax({type: "POST",dataType:"json", url: _url, data: _data,
			success: function(data) {
				//验证成功
				if (data.status == 1) {
					$.dialog.tips(data.code, 3, 1);
					if (del == 1) {
						$(".dr_select").each(function(){ 
							if ($(this).attr('checked')) { 
								$("#dr_row_"+$(this).val()).remove();
							} 
						});
					} else {
						setTimeout('window.location.reload(true)', 3000); // 刷新页
					}
				} else {
					$.dialog.tips(data.code, 3, 2);
					return true;
				}
			},
			error: function(HttpRequest, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
			}
		});
		return true;
	});
	return false;
}

function dr_dialog_del(text, url) {
	var throughBox	= $.dialog.through;
	var dr_Dialog	= throughBox({title: lang['del']});
	dr_Dialog.content('<img src='+memberpath+'statics/js/skins/icons/question.png>&nbsp;&nbsp;'+text);
	// 添加按钮
	dr_Dialog.button({name: lang['del'],
		callback:function() {
				// 将表单数据ajax提交验证
				$.ajax({type: "POST", dataType:"json", url: url, data: {},
					success: function(data) {
						if (data.status == 1) {
							dr_Dialog.close();
							$.dialog.tips(data.code, 2, 1);
							// 刷新页
							setTimeout('window.location.reload(true)', 2000);
						} else {
							$.dialog.tips(data.code, 2, 2);
						}
					},
					error: function(HttpRequest, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
					}
				});
		},
		focus: true
	}
	,
	{name: lang["cancel"],
		callback:function() {
			dr_Dialog.close();
		},
		focus: false
	});
}

function dr_confirm_del_all() {
	art.dialog.confirm(lang['confirm'], function(){
		var _data = $("#myform").serialize();
		var _url = window.location.href;
		if ((_data.split('ids')).length-1 <= 0) {
			$.dialog.tips(lang['select_null'], 2);
			return true;
		}
		// 将表单数据ajax提交验证
		$.ajax({type: "POST",dataType:"json", url: _url, data: _data,
			success: function(data) {
				//验证成功
				if (data.status == 1) {
					// 刷新页
					$.dialog.tips(data.code, 2, 1);
					setTimeout('window.location.reload(true)', 2000);
				} else {
					$.dialog.tips(data.code, 2, 2);
					return true;
				}
			},
			error: function(HttpRequest, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + HttpRequest.statusText + "\r\n" + HttpRequest.responseText);
			}
		});
		return true;
	});
	return false;
}

function dr_dialog(url, func) {
	// 判断窗口类型
	switch(func){
		case 'add':
			var _title = lang['add'];
			break;
		case 'edit':
			var _title = lang['edit'];
			break;
		default:
			return false;
			break;
	}
	// 创建窗口
	var throughBox	= $.dialog.through;
	var dr_Dialog	= throughBox({title: _title});
	// ajax调用窗口内容
	$.ajax({type: "GET", url:url, dataType:'text',
	    success: function (text) {
			var win = $.dialog.top;
			dr_Dialog.content(text);
			// 添加按钮
			dr_Dialog.button({name: _title,
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
									dr_Dialog.close();
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