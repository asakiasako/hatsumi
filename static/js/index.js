$(function() { 
  
	$(document).on("click", ".tag-icon", function(){
		$(this).next().toggleClass("show-tags");
		}); //首页标签开关
		
	var containHeight = $("#sidebar-area").height()+60;
	$("#content").css( 'min-height', containHeight);//解决2栏布局背景长度问题
	
/****************************
      jquery加载文章列表
 ****************************/	  
//点击下一页的链接(即那个a标签)   
$('div#ajax-load-list a').click( function() {   
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
            var $res = $(data).find(".post"); //从数据中挑出文章数据，请根据实际情况更改   
			$res.hide().appendTo('.postlist').fadeIn('slow');//将数据加载加进posts-loop的标签中。 
            var newhref = $(data).find("#ajax-load-list a").attr("href"); //找出新的下一页链接   
            if( newhref != undefined ){   
                $("#ajax-load-list a").attr("href",newhref); 
            }else{   
                $("#ajax-load-list").addClass('post-no-more').html('木有更多了');
				//如果没有下一页了，显示提醒  
            }   
        }   
        });   
    }   
    return false;   
});    
/*******************
   END
******************/



});