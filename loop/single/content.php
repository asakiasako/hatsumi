<?php
	$single_thumbnail = yukimoe_thumbnail(1600, 480);
	$single_thumbnail = $single_thumbnail ? $single_thumbnail : yukimoe_static('image/single-default.jpg');
?>
<article class="single-content" itemscope itemtype="http://schema.org/Article">
		<div class="head-img">
        		<div class="bg" style="background-image: url('<?php echo $single_thumbnail?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $single_thumbnail?>', sizingMethod='scale');"></div>
        		<div class="overlay"></div>
                <div class="single-img-title">
                <div>
                <h1 id="single-title" class="sptitle" itemprop="headline"><a href="<?php the_permalink() ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>
                <section class="singleinfo">
                	<?php echo um_get_avatar( get_the_author_meta( 'ID' ) , '64' , um_get_avatar_type(get_the_author_meta( 'ID' )));?>
                    <ul class="postinfo">
                        <li class="postauth" itemprop="author"><?php the_author(); ?></li><li class="info_sep">&nbsp;&middot;&nbsp;</li>
                        <li class="posttime" itemprop="dateCreated"><?php the_time(); ?>&nbsp;&nbsp;</li>
                        <li class="postcat"><?php the_category(' ,');?></li>
                    </ul>
            </section> 
            </div>  
            </div>
        </div>
        <div class="post-body">
            <div id="single-content" class="postcont"><?php the_content("");?></div>
        </div>
</article>