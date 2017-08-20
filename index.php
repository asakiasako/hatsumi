<?php get_header();?>
    <div id="main">
		<?php yukimoe_slider();?>
        <?php wp_nav_menu(array(
            'theme_location' => 'top-menu-mob',
            'container' => 'nav',
            'container_id' => 'topnav-small'
        )); ?>
        <div id="main-wrap" class="clearfix">
        <?php get_sidebar(); ?>
        <div id="content">
             <div id="container">
                <ul class="postlist">
                    <?php if(have_posts()): query_posts($query_string.$p_catdis=yukimoe_cat_dis()); while (have_posts()):the_post();
                        get_template_part( 'loop/home/content', get_post_format() );
                    endwhile; 
					else:get_template_part( 'loop/home/content', 'noresult' );
					endif;?>
                </ul>
                <?php yukimoe_nav();?>
            </div>
         </div>
    </div>
    </div>
<?php get_footer();?>