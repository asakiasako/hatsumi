<li class="post post-gallery">
	<div class="post-inner" itemscope itemtype="http://schema.org/Article">
        <a title="<?php the_title();?>" href="<?php the_permalink() ?>" rel="bookmark">
            <div class="post-thumbnail">
                <?php yukimoe_postimg_list()?>
            </div>
            </a>
    <div class="post-body">
    <a title="<?php the_title();?>" href="<?php the_permalink() ?>" rel="bookmark" itemprop="url">
        <h2 class="post-title" itemprop="headline">
            <?php the_title();?>
        </h2>
    	</a>
        <div class="post-content">
			<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 130,"……");?>
        </div>
        <div class="read-more"><a title="<?php the_title();?>" href="<?php the_permalink() ?>" rel="bookmark">Read more&hellip;</a></div>
    </div>
    <div class="tag-icon left"><i class="yukimoe">&#xe619;&nbsp;</i><span>Tags</span></div>
    <div class="post-tags left"><span class="cat-tag"><?php the_category();?></span><?php the_tags('','','');?></div>
    <div class="post-info right"><a class="yukimoe right" href="<?php the_permalink() ?>#comment">&#xe600;</a><a class="c-count right<?php if(yukimoe_count_comments()!=0){?>" href="<?php the_permalink() ?>#comments"<?php ;} else {?> c-count-no"<?php }?>><?php yukimoe_count_comments( false, true);?>&nbsp;个评论</a></div>
    </div>
</li>