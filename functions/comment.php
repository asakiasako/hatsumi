<?php

    //反垃圾评论
	function hatsumi_antijunk( $incoming_comment ) {
		$max_lenth = 20 ;
		$http = '/href=|url=|rel="nofollow"|<\/a>/u'; 
		$pattern = '/[一-龥]|furafura/u';
		$jpattern ='/[ぁ-ん]+|[ァ-ヴ]+/u';
		$anti_open = hatsumi_get_option('anti-junk') ;
		if($anti_open) {
			if(preg_match($http, $incoming_comment['comment_content'])) {
				hatsumi_ajax_error( "为防止垃圾评论，不能使用文字超链接，请使用明文链接。" );
				return;
			}
			else if(strlen(trim($incoming_comment['comment_content']))>$max_lenth) {
				if(!preg_match($pattern, $incoming_comment['comment_content'])) {
					hatsumi_ajax_error( "为防止垃圾评论，超过20个字符的评论，必须包含中文。" );
					return;
				}
				else if(preg_match($jpattern, $incoming_comment['comment_content'])) {
					hatsumi_ajax_error( "为防止垃圾评论，超过20个字符的评论，不能包含日文字符。" );
					return;
				}
			}
		}
		return( $incoming_comment );
	}
	add_filter('preprocess_comment', 'hatsumi_antijunk');

	//评论回复邮件提醒
	function hatsumi_comment_mail($comment_id) {
	    $comment = get_comment($comment_id);
	    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	    $spam_confirmed = $comment->comment_approved;

	    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
	    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
	    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

	    if (($parent_id != '') && ($spam_confirmed != 'spam')) {
	        $to = trim(get_comment($parent_id)->comment_author_email);
	        $subject = '你在 [' . get_option("blogname") . '] 的留言有了新回复';
	        $message = '
					<div style="background-color:#fcfbe8; border:2px dotted #8dccff; color:#663c00; padding:0 15px;border-radius:5px -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">
					<p><strong>' . trim(get_comment($parent_id)->comment_author) . ', 你好!</strong></p>
					<p><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言为:</strong><br />'
	            . trim(get_comment($parent_id)->comment_content) . '</p>
					<p><strong>' . trim($comment->comment_author) . ' 给你的回复是:</strong><br />'
	            . trim($comment->comment_content) . '<br /></p>
					<p>你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" style="color:#35a3ed;">查看完整内容</a></p><br />
					<p>欢迎再次来访<a href="' . get_option('home') . '" style="color:#35a3ed; ">' . get_option('blogname') . '</a></p>
					<p>(此邮件为系统自动发送，请勿直接回复.)</p>
					</div>';

	        wp_mail( $to, $subject, $message, $headers );
	    }
	}
	
	if ( hatsumi_get_option( 'send_mail' ) )  add_action('comment_post', 'hatsumi_comment_mail');
	
	// 清除评论里的Track计数
	add_filter('get_comments_number', 'hatsumi_comment_count', 0);
	function hatsumi_comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}
	
	//获取已经批准的评论数量
	function hatsumi_count_comments($post_id=false,$echo=false){
		global $post;
		if(!$post_id) $post_id=$post->ID;
		$comments_count = wp_count_comments( $post_id );
		if ($echo==false) return $comments_count->approved;
		else echo $comments_count->approved;
	}
	
	//回调函数
	function hatsumi_commentlist($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>		
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
		<?php endif; ?>
        <div class="comment-info clearfix">
            <div class="comment-author vcard">
            <?php 
			if ($args['avatar_size'] != 0) {
				if ($comment->user_id) echo um_get_avatar( $comment->user_id , $args['avatar_size'], um_get_avatar_type($comment->user_id));
				else echo get_avatar( $comment, $args['avatar_size'] ); 
			}
			?>
            <?php 
			if ($comment->user_id):?> <a href="<?php the_author_meta( 'user_url', $comment->user_id );?>" rel="external nofollow" class="url"><?php the_author_meta( 'display_name', $comment->user_id );?></a>
			<?php else: printf(__('<cite>%s</cite>'), get_comment_author_link()); 
			endif;?>
            </div>
            <div class="comment-meta commentmetadata">
                <?php
                    /* translators: 1: date, 2: time */
                    printf( __('%1$s'), get_comment_date()) ?>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
                <span class="com-moderate">审核中</span>
            <?php endif; ?>
		</div>
		<?php comment_text() ?>
        
		<div>
		<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
<?php
        }
		
		
//显示父评论内容
function hatsumi_add_comment_parent( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
	$comment_prt_id = $comment->comment_parent;
	$comment_prt = get_comment( $comment_prt_id );
	$comment_prt_auth = $comment_prt->comment_author;
	$comment_prt_date = get_comment_date( '',$comment_prt_id );
	$comment_prt_text = $comment_prt->comment_content;
    $comment_text = $comment_text.'<div class="comment_child"><div class="clearfix"><span class="comment_c_auth">'.$comment_prt_auth.'</span><span class="comment_c_date">'.$comment_prt_date.'</span></div><p>'.$comment_prt_text.'</p></div>';
  }
  return $comment_text;
}
add_filter( 'comment_text' , 'hatsumi_add_comment_parent', 20, 2);