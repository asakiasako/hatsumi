<aside id="sidebar-area">
<?php wp_reset_query(); 
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('边栏') ) : ?>
    <section class="no-side">
    <h1>未添加边栏小工具</h1>
    <p>请在后台小工具设置页面"边栏"区域内添加小工具</p>
    </section>
	<?php endif; ?>
</aside>