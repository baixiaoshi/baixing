// JavaScript Document
jQuery(document).ready(function(){
    $('.btn-search').bind("mouseover", function(event) { $(this).addClass('on'); });
	$('.btn-search').bind("mouseout", function(event) { $(this).removeClass('on'); });
    
	$('input#js-company').bind("focus", function(event) { $('label[for=js-company]').addClass('none'); }); 
	$('input#js-company').bind("blur", function(event) {
		if($(this).val()==''){
		 $('label[for=js-company]').removeClass('none'); 
		}
	});
	
	$('.selectbar').bind("mouseover", function(event) { 
		$(this).height('auto'); 
	});
	$('.selectbar').bind("mouseout", function(event) { 
		$(this).height(44); 
	});
	
	$('.selectbar ul li').bind("click", function(event) { 
		var onHtml = $(this).html();
		var Html = $('.selectbar ul li.selected').html();
		var searchfor = $(this).find('a').attr('name');
		$('input#searchfor').val(searchfor);
		$(this).html(Html);
		$('.selectbar ul li.selected').html(onHtml);
		$('.selectbar').height(44);	
	});
	
	$('button.btn-search').bind("click", function(event) { 
		if($('input#js-company').val()==''){
			//alert('璇峰～鍐欏叧閿瓧');
			return false;
		}else{
			$('#searchForm').submit();
		}
	});
	
	if($('input#js-company').val()!=''){
		$('label[for=js-company]').addClass('none');
	}
});