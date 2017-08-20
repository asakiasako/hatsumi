<?php
	$single_thumbnail = hatsumi_thumbnail(1600, 800);
	$single_thumbnail = $single_thumbnail ? $single_thumbnail : hatsumi_static('image/page-default.jpg');
?>
<article class="page-content" itemscope itemtype="http://schema.org/Article">
		<div class="head-img">
        		<div class="bg" style="background-image: url('<?php echo $single_thumbnail?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $single_thumbnail?>', sizingMethod='scale');"></div>
        		<div class="overlay"></div>
                <div class="single-img-title">
                <div>
                <i class="hatsumi">&#xe627;</i>
                <h1 id="single-title" class="sptitle" itemprop="headline"><a href="<?php the_permalink() ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>
            </div>  
            </div>
        </div>
        <div class="post-body">
        	<div class="post-links">
                    <ul class="linkpage">
                        <?php $linkcats = $wpdb->get_results("SELECT T1.name AS name FROM $wpdb->terms T1, $wpdb->term_taxonomy T2 WHERE T1.term_id = T2.term_id AND T2.taxonomy = 'link_category'");?>
                        <?php if($linkcats) : foreach($linkcats as $linkcat) : ?>
                            <li class="linkmain">
                                <h3 class="linkpage"><?php echo $linkcat->name; ?></h3>
                                <ul class="linkcon">
                                    <?php $bookmarks = get_bookmarks('orderby=date&category_name=' . $linkcat->name);if ( !empty($bookmarks) ) {foreach ($bookmarks as $bookmark) {echo '<li><a href="' . $bookmark->link_url . '" target="_blank" >' . $bookmark->link_name . '</a><span class="linkdsc">' . $bookmark->link_description . '</span></li>';}} ?>
                                </ul>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
            </div>
        </div>
</article>