<?php
	$single_thumbnail = yukimoe_thumbnail(1600, 480);
	$single_thumbnail = $single_thumbnail ? $single_thumbnail : yukimoe_static('image/page-default.jpg');
?>
<article class="page-content" itemscope itemtype="http://schema.org/Article">
		<div class="head-img">
        		<div class="bg" style="background-image: url('<?php echo $single_thumbnail?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $single_thumbnail?>', sizingMethod='scale');"></div>
        		<div class="overlay"></div>
                <div class="single-img-title">
                <div>
                <i class="yukimoe">&#xe625;</i>
                <h1 id="single-title" class="sptitle" itemprop="headline"><a href="<?php the_permalink() ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>
            </div>  
            </div>
        </div>
        <div class="post-body">
            <div class="postcont page-content"><?php the_content("");?></div>
            <div class="page-avatar"><?php echo um_get_avatar( get_the_author_meta( 'ID' ) , '80' , um_get_avatar_type(get_the_author_meta( 'ID' )));?></div>
        </div>
</article>