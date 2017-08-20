<div class="sosorry"> 对不起，没有你想要的结果，请重新搜索……或者看点别的</div>
<ul class="no-results">
	<?php
	$args = array(
						'post_type' => 'post',
						'tax_query' => array( array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => 'post-format-status',
							'operator' => 'NOT IN',
						) ),
						'ignore_sticky_posts' => 1,
						'orderby' => 'rand',
						'numberposts' => 20,
						'post_password' => '', 
						'post_status' => 'publish', // 只选公开的文章. 
					);
	$rand_posts = get_posts( $args );$numrand=1;foreach( $rand_posts as $post ) : 
	?><li><a href="<?php the_permalink(); ?>"><?php echo ($numrand++).'.'; the_title(); ?></a></li><?php endforeach; ?>
</ul>