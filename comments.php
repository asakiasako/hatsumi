<?php
if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
	return;
}
?>
<!-- 评论输入框 -->
<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php echo '评论已关闭' ?></p>
	<?php endif; ?>

<?php if(comments_open()) : ?>
<div id="respond">
	<h2 id="resp-title" class="sing_foo_title">
			发表评论
		</h2>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="comment-form">
        <?php if($user_ID) : ?>
            <!--已登录-->
            <div class="welcomediv clearfix">
                <div class="welcometip">
                    <?php global $current_user;get_currentuserinfo(); echo '当前登陆用户：'.$current_user->display_name; ?><a href="<?php echo wp_logout_url(home_url(add_query_arg())); ?>" class="logout">退出</a>
                </div>
            </div>

        <?php else : ?>
            <!--未登录-->
            <div class="welcomediv clearfix">
            	<script type="text/javascript">function setStyleDisplay(id, status){document.getElementById(id).style.display = status;}</script>
                <?php if ( $comment_author != "" ) : ?>   
                    <div class="welcometip"><?php printf(__('你好，%s，欢迎回来！'), $comment_author) ?></div>
                    <div id="showinfo"><a><?php _e('修改资料'); ?></a></div>
                    <div id="hideinfo"><a><?php _e('关闭'); ?></a></div>
                <?php else : ?>
                    <div class="welcometip">
                    <div id="com-log-box" class="clearfix">
                    <?php printf(__('你目前的身份是游客，请输入昵称和邮箱！')) ?>
                    <a class="user-login" style="margin-right:5px">登录</a>
                    <a id="showinfo"><?php _e('输入资料'); ?></a>
                    <a id="hideinfo"><?php _e('关闭'); ?></a>
                    </div>
                    </div>
                <?php endif; ?>
            </div>
			<?php $req = get_option('require_name_email');?>
            <section id="comboxinfo" class="clearfix">
                <div class="cominfodiv cominfodiv-author"><label for="author" class="author">昵 称：</label><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" placeholder="请输入昵称<?php if ($req) echo "（必填）"; ?>" tabindex="1" /></div>
                <div class="cominfodiv cominfodiv-email"><label for="email" class="email">邮 箱：</label><input type="email" name="email" id="email" value="<?php echo $comment_author_email; ?>" placeholder="请输入邮箱地址<?php if ($req) echo "（必填）"; ?>" tabindex="2" /></div>
                <div class="cominfodiv cominfodiv-url"><label for="url" class="url">网 址：</label><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" placeholder="请输入你的网站地址" tabindex="3" /></div>
            </section>
                <script type="text/javascript">
					setStyleDisplay('hideinfo','none');
					setStyleDisplay('comboxinfo','none');
					var comBoxInfo=document.getElementById("comboxinfo");
					comBoxInfo.style.opacity="0";
					comBoxInfo.style.transform="translateY(-12px)";
					comBoxInfo.style.msTransform="translateY(-12px)";
					comBoxInfo.style.mozTransform="translateY(-12px)";
					comBoxInfo.style.webkitTransform="translateY(-12px)";
					comBoxInfo.style.oTransform="translateY(-12px)";
                </script>
        <?php endif; ?>

        <div id="text-area">
            <textarea name="comment" id="comment" rows="10" tabindex="4" placeholder="输入评论内容..." onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
        </div>

        <div class="submitdiv clearfix">
        	<div class="OwO">
   				
            </div>
            <div class="submitcom"><input name="submit" type="submit" id="submit" tabindex="5" value="提交评论" /><?php comment_id_fields(); ?></div>
            <div id="cancel_comment_reply"><?php cancel_comment_reply_link('取消回复') ?></div>
        </div>
        <?php do_action('comment_form', $post->ID); ?>

    </form>

</div><!--end respond-->

<?php endif; ?>

<div id="comments" class="comments-area" itemscope itemtype="http://schema.org/Comment">
	<?php if ( have_comments() ) : ?>
		<h2 class="sing_foo_title" id="com-title">
			<?php hatsumi_count_comments(false, true);?>条评论
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
					'type' => 'comment',
					'callback' => 'hatsumi_commentlist'
				) );
			?>
		</ol><!-- .comment-list -->

		<?php hatsumi_com_nav() ?>

	<?php endif; // Check for have_comments(). ?>

	
</div><!-- .comments-area -->
