<?php get_header();?>
    <div id="main">
        <div id="main-wrap" class="clearfix">
        <?php get_sidebar(); ?>
        <div id="content">
             <div id="container">
             <?php if(have_posts()):?>
             	<div id="cat-header">
                	<div class="cat-title">
						<?php $cat = single_cat_title('',false);
						echo $cat;
						?>
                    </div>
                    <div class="cat-disc">
                    	<?php 
							echo category_description(get_cat_id($cat));
							?>
                    </div>
                    <div class="cat-line"></div>
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