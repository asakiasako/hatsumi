<?php get_header()?>
    <div id="main">
    	<div id="main-wrap" class="clearfix">
        <?php get_sidebar(); ?>
        <div id="content">
            <div id="container">
             	<div class="search-box">            
                <form class="search-form" method="get" action="<?php bloginfo('home');?>/">
                    <label for="page-search"><i class="hatsumi">&#xe618;</i></label>
                    <input class="search-text" name="s" id="page-search" type="text" placeholder="搜索..." />
            	</form>
            </div>
                <div class="content-desc"><p>搜索关键词「<?php the_search_query(); ?>」的所有结果</p></div>
                <?php if (have_posts()) :
                    echo '<ul class="postlist">';
                    while (have_posts()) : the_post(); ?>
                        <?php get_template_part( 'loop/home/content', get_post_format() ); ?>
                    <?php endwhile;
                    echo '</ul>';
                	else: get_template_part( 'loop/home/content', 'noresult' );
				 endif; ?>
                 <?php hatsumi_nav();?>
            </div>
        </div>
        </div>
    </div>
<?php get_footer();?>