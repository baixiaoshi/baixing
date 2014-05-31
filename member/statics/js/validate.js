
/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/static/js/validate.js
 */

// 表单提示
function d_tips(name, status, code) {
	var obj = $("#dr_"+name+"_tips");
	if (code) obj.html(code);
	if (status) {
		obj.attr("class", "onCorrect");
	} else {
		obj.attr("class", "onError");
		$("#dr_"+name).focus();
	}
}

function check_title() {
	var val = $("#dr_title").val();
	var mod = $("#dr_module").val();
	var id = $("#dr_id").val();
	$.get(memberpath+'index.php?c=api&m=checktitle&title='+val+'&module='+mod+'&id='+id+'&rand='+Math.random(), function(data){
		$("#dr_title_tips").html(data);
		$("#dr_title_tips").attr("class", "onShow");
	});
}

function get_keywords(to) {
	var title = $("#dr_title").val();
	if ($("#dr_"+to).val()) return false;
	$.get(memberpath+'index.php?c=api&m=getkeywords&title='+title+'&rand='+Math.random(), function(data){
		$("#dr_"+to).val(data);
	});
}

// 转换拼音
function d_topinyin(name, from, letter) {
	var val = $("#dr_"+from).val();
	if ($("#dr_"+name).val()) return false;
	$.get(memberpath+'index.php?c=api&m=pinyin&name='+val+'&rand='+Math.random(), function(data){
		$("#dr_"+name).val(data);
		if (letter) {
			$("#dr_letter").val(data.substr(0, 1));
		}
	});
}

// 验证是否为空
function d_required(name) {
	if ($("#dr_"+name).val() == '') {
		d_tips(name, false);
		return true;
	} else {
		d_tips(name, true);
		return false;
	}
}

// 验证email
function d_isemail(name) {
	var val	= $("#dr_"+name).val();
	var reg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/;
	if (reg.test(val)) {
		d_tips(name, true);
		return false;
	} else {
		d_tips(name, false);
		return true;
	}
}

// 验证url
function d_isurl(name) {
	var val	= $("#dr_"+name).val();
	var reg = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/; 
	var Exp = new RegExp(reg);
	if (Exp.test(val) == true) {
		d_tips(name, true);
		return false;
	} else {
		d_tips(name, false);
		return true;
	}
}

// 验证domain
function d_isdomain(name) {
	var val	= $("#dr_"+name).val();
	if (val.indexOf('/') > 0) {
		d_tips(name, false);
		return true;
	} else {
		d_tips(name, true);
		return false;
	}
}