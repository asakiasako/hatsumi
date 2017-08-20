//single页脚本 底部加载

//百度分享按钮
var art_title = document.getElementsByTagName("title")[0].innerHTML;
window._bd_share_config = {
	common : {
		bdText : art_title
	},
	share : [{
		"bdSize" : 16
	}]
}
with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];

//jquery
$(function() { 
	$("#showinfo").click(function(){
		$(this).hide();
		$("#hideinfo").show();
		$("#comboxinfo").stop().slideDown("fast").css({"opacity":"1","transform":"translateY(0)","-ms-transform":"translateY(0)","-moz-transform":"translateY(0)","-webkit-transform":"translateY(0)","-o-transform":"translateY(0)"});
		}); 
	$("#hideinfo").click(function(){
		$(this).hide();
		$("#showinfo").show();
		$("#comboxinfo").stop().slideUp("fast").css({"opacity":"0","transform":"translateY(-12px)","-ms-transform":"translateY(-12px)","-moz-transform":"translateY(-12px)","-webkit-transform":"translateY(-12px)","-o-transform":"translateY(-12px)"});
		}); 
	$(".comment-reply-link").click(function(){
		$("#respond").stop().hide(0).fadeIn("slow");
		});
	/****************************
		  jquery加载文章列表
	 ****************************/	  
	//点击下一页的链接(即那个a标签)   
	$('div#ajax-load-com a').click( function() {   
		var $this = $(this);   
		$this.parent().addClass('loading'); //给a标签加载一个loading的class属性，可以用来添加一些加载效果   
		var href = $this.attr("href"); //获取下一页的链接地址   
		if (href != undefined) { //如果地址存在   
			$.ajax( { //发起ajax请求   
				url: href, //请求的地址就是下一页的链接   
				type: "get", //请求类型是get   
				error: function(request) {   
				   alert(request.responseText);//如果发生错误怎么处理   
			},   
			success: function(data) { //请求成功   
				$this.parent().removeClass('loading'); //移除loading属性   
				var $res = $(data).find("li.comment"); //从数据中挑出文章数据，请根据实际情况更改   
				$res.hide().appendTo('ol.comment-list').fadeIn('slow');//将数据加载加进posts-loop的标签中。 
				var newhref = $(data).find("#ajax-load-com a").attr("href"); //找出新的下一页链接   
				if( newhref != undefined ){   
					$("#ajax-load-com a").attr("href",newhref); 
				}else{   
					$("#ajax-load-com").hide();
					//如果没有下一页了，隐藏 
				}   
			}   
			});   
		}   
		return false;   
	});    
	/*******************
	   END
	******************/	
	
	/**添加表情功能**/
	var OwO_demo = new OwO({
		logo: 'OωO表情',
		container: document.getElementsByClassName('OwO')[0],
		target: document.getElementById('comment'),
		api: 'http://swiity.com/wp-content/themes/Hatsumi theme/static/js/OwO.json',
		position: 'down',
		width: '100%',
		maxHeight: '250px'
	});





});