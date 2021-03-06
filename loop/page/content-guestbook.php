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
                <i class="hatsumi">&#xe626;</i>
                <h1 id="single-title" class="sptitle" itemprop="headline"><a href="<?php the_permalink() ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h1>
            </div>  
            </div>
        </div>
        <div class="post-body">
            <div class="wall">
                <ul>
                    <?php global $wpdb; $my_email = "'" . get_bloginfo ('admin_email') . "'"; $query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 10 MONTH ) AND user_id='0' AND comment_author_email != $my_email AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT 27"; $wall = $wpdb->get_results($query); foreach ($wall as $comment) { if( $comment->comment_author_url ) $url = $comment->comment_author_url; else $url=""; if ($url=="http://暂无"||$url=="") {$add_url="";$g_link="暂无链接";}else {$add_url="href='".$url."'";$g_link="访问";} $tmp = "
                    <li>
                        <div class='ch-item'>
                            <div class='ch-info-wrap'>
                                <div class='ch-info'>
                                    <div class='ch-info-front'><a ".$add_url." target='_blank' rel='nofollow' title='".$comment->comment_author." - ".$comment->cnt." 条评论'>".get_avatar($comment->comment_author_email, 70)."</a></div>
                                    <div class='ch-info-back'><a ".$add_url." target='_blank' rel='nofollow' title='".$comment->comment_author." - ".$comment->cnt." 条评论'>".$g_link."</a></div>
                                </div>
                            </div>
                        </div>
                        <span>".$comment->comment_author."</span>
                    </li>";
                    $output .= $tmp; } echo $output ; ?>
                </ul>
            </div>
            <div class="guest-com">
            	<?php comments_template( '', true );?>
                <script type="text/javascript">
						document.getElementById('resp-title').innerHTML="留言";
						document.getElementById('com-title').innerHTML="最新留言";
						document.getElementById('submit').value="发表留言";
						document.getElementById('ajax-load-com').getElementsByTagName('a')[0].innerHTML='更多留言';
                </script>
            </div>
</div>
</article>