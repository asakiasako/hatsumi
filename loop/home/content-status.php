<li class="post post-status">
	<div class="post-inner" itemscope itemtype="http://schema.org/Article">
    <div class="post-body">
        <div class="post-content">
            <?php if( $post_thumbnail ){
                echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 130,"……");
            }else{
                echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 300,"……");
            }
            ?>
        </div>
    </div>
    </div>
</li>