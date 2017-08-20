<?php get_header()?>
<div id="main">
<div id="content">
		<div id="container">
            <div id="singlepost">
			<?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'loop/single/content', get_post_format() ); ?>
			<?php endwhile; ?>
            <div class="single-center">
			<?php hatsumi_sincopy()?>
				<div class="singinfo">
	                <div class="posttags left"><?php if ( get_the_tags() ) { the_tags('' , ''); } else{ echo "暂无标签"; } ?></div>
                    <div class="bdsharebuttonbox right" data-tag="share_1">
                    	<a class="bds_weixin hatsumi" data-cmd="weixin">&#xe61d;</a>
                        <a class="bds_tsina hatsumi" data-cmd="tsina">&#xe61b;</a>
                        <a class="bds_qzone hatsumi" data-cmd="qzone" href="#">&#xe61c;</a>
                        <a class="bds_twi hatsumi" data-cmd="twi">&#xe61a;</a>
					</div>
	            </div>
                </div>
            </div>
            <div class="single-center">
            	<div class="sin-author">
                	<?php echo um_get_avatar( get_the_author_meta( 'ID' ) , '64' , um_get_avatar_type(get_the_author_meta( 'ID' )));
					$user_info = get_userdata(get_the_author_meta( 'ID' ));
					if ($user_info->um_weixin) {
					?>
                    <img class="wec-2wm" src="<?php echo $user_info->um_weixin;?>"/>
                    <?php }?>
                    <div class="sin-author-info">
                    	<li><?php the_author(); ?></li>
                    	<li><?php the_author_description(); ?></li>
                    </div>
                </div>
            	<?php 
					$status_obj = get_term_by( 'slug', 'post-format-status', 'post_format');
					$status_id =  $status_obj->term_id;
					if (get_previous_post( false, $status_id, 'post_format')):?>
                    <div id="prev-post" class="hide">
                        <div class="prev-info"><span class="prev-in-a">继续阅读下一篇文章</span><span class="prev-in-b">继续阅读</span><i class="hatsumi">&#xe629;</i></div>
                        <div class="prev-title">
                            <?php previous_post_link( '%link', '%title', false, $status_id, 'post_format');?>
                        </div>
                        <div class="prev-button">
                            <?php previous_post_link( '%link', '阅读文章', false, $status_id, 'post_format');?>
                        </div>
                    </div>
				<?php endif;?>
            
            
            <?php hatsumi_rel_post();?>
			<?php if ( $comments || comments_open() ) :  comments_template( '', true );?><?php endif; ?>
            </div>
        </div>
</div>
<?php get_sidebar();?>
</div>
<?php get_footer();?>