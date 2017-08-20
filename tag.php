<?php get_header();?>
    <div id="main">
        <div id="main-wrap" class="clearfix">
        <?php get_sidebar(); ?>
        <div id="content">
             <div id="container">
             <?php if(have_posts()):?>
             	<div id="tag-header">
                	<div class="tag-title">
						<?php single_tag_title( '标签：', true );?>
                    </div>
                    </div>
                    <?php endif?>
                <ul class="postlist">
                    <?php if(have_posts()): while (have_posts()):the_post();
                    get_template_part( 'loop/home/content', get_post_format() );
                    endwhile; 
					else:get_template_part( 'loop/home/content', 'noresult' );
					endif;?>
                </ul>
                <?php hatsumi_nav();?>
            </div>
         </div>
    </div>
    </div>
<?php get_footer();?>