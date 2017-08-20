<style type="text/css">
#header, #footer, .pagebg{display:none}
body, html{
	background:transparent !important}
</style>
<?php get_header()?>
    <div id="errorimg"><img src="<?php yukimoe_static('image/404.jpg', 1 )?>"/></div>
    <div id="errortext">
        <h1 class="errorcode">I miss U at <span id="time-running"></span></h1>
        <a id="go-last" href="javascript:history.go(-1);">返回上一页</a>
        <a id="go-home" href="<?php bloginfo('url');?>">返回首页</a>
    </div>
<script type="text/javascript">
window.onload=function startTime() {
		var today=new Date()
		var h=today.getHours()
		var m=today.getMinutes()
		var s=today.getSeconds()
		// add a zero in front of numbers<10
		m=checkTime(m)
		s=checkTime(s)
		document.getElementById('time-running').innerHTML=h+":"+m+":"+s
		}
		function checkTime(i)
		{
		if (i<10) 
		  {i="0" + i}
		  return i
}
</script>
<?php get_footer();?>