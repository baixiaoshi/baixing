$(function(){
//ajax取回市列表信息
$('#form_provinces').bind('change',function(){
	//获取省份id
	var $id = parseInt($(this).val());
	$.ajax({
    url:"/news/index.php?c=city&m=getcity",    //请求的url地址
    dataType:"html",   //返回格式为json
    async:true,//请求是否异步，默认为异步，这也是ajax重要特性
    data:{"id":$id},    //参数值
    type:"POST",   //请求方式
    success:function(data){

       $('.citys').fadeIn("slow").html(data);
    },
    
	});
});

});


//ajax取回县的数据
function getcitys(id){
	//获取城市ID
	var $id = parseInt(id);
	$.ajax({
    url:"/news/index.php?c=city&m=getarea",    //请求的url地址
    dataType:"html",   //返回格式为json
    async:true,//请求是否异步，默认为异步，这也是ajax重要特性
    data:{"id":$id},    //参数值
    type:"POST",   //请求方式
    success:function(data){
 
       $('.areas').fadeIn("slow").html(data);
    },
    
	});
	}