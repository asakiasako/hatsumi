/* <![CDATA[ */
// 如果 ViewHistory 的实例存在，则可以将页面信息写入。
if(viewHistory) {
var page = {
"title": document.getElementById("single-title").innerHTML,
"url": location.href, // 这是 primaryKey
"time": new Date().getTime()
// "time": new Date()
// "author": ...
// 这里可以写入更多相关内容作为浏览记录中的信息
};
viewHistory.addHistory(page);
}
/* ]]> */

$(function() { 
	$('.prev-info i').click(function(){
		$('#prev-post').addClass('hide-im');
		});
	$('#comment').focus(function(){
		$('#prev-post').addClass('hide-im');
		});
	$(window).scroll(function(){
		var disTop = $('.singinfo').offset().top - $(window).height()+100;
		if ($(window).scrollTop() > disTop ) {
			if ($('#prev-post').hasClass('hide'))
			$('#prev-post').removeClass('hide');
			}
		else {
			if (!$('#prev-post').hasClass('hide'))
			$('#prev-post').addClass('hide');
			}
		});
	});