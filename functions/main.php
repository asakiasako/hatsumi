<?php


/*================================================
 *  添加wordpress功能支持,引入必要文件
 *================================================*/

//替换wordpress默认的jquery
add_action( 'init', 'jquery_register' );
function jquery_register() {
if ( !is_admin() ) {
wp_deregister_script( 'jquery' );
wp_register_script( 'jquery', ( 'http://cdn.staticfile.org/jquery/2.1.4/jquery.min.js' ), false, null, true );
}
}

//加载脚本文件
add_action('wp_enqueue_scripts', 'yukimoe_scripts');
function yukimoe_scripts(){
    global $wp_styles, $wp_scripts;
    wp_enqueue_style('style', get_bloginfo('stylesheet_url'));

    /**
     * Load our IE specific stylesheet for a range of newer versions:
     * <!--[if gt IE 8]> ... <![endif]-->
     * <!--[if gte IE 9]> ... <![endif]-->
     * NOTE: You can use the 'greater than' or the 'greater than or equal to' syntax here interchangeably.
     */
    wp_enqueue_style('yukimoe-ie8', yukimoe_static("css/ie8.css"), array( 'style' ));
    $wp_styles->add_data( 'yukimoe-ie8', 'conditional', 'lt IE 9' );

    // conditional statement for older versions of IE
    wp_register_script('html5_shiv', yukimoe_static("js/html5shiv.js"), '', '', false);
    $wp_scripts->add_data('html5_shiv', 'conditional', 'lt IE 9');

    if( !is_admin() ){
		
        wp_enqueue_script( 'jquery' );
		
        if( is_home() ){
		wp_enqueue_script( 'index', home_url('/min/').'?b=wp-content/themes/YukiMoe%20Theme/static/js&f=fastclick.js,index.js,slider-min.js,view-history.js',array('jquery'), YUKIMOE_VERSION, false);
        }
		
		if( is_archive() || is_search() ) {
		wp_enqueue_script( 'archive', home_url('/min/').'?b=wp-content/themes/YukiMoe%20Theme/static/js&f=fastclick.js,index.js,view-history.js',array('jquery'), YUKIMOE_VERSION, false);
        }

        if( is_page() ){
			wp_enqueue_script( 'single', home_url('/min/').'?b=wp-content/themes/YukiMoe%20Theme/static/js&f=fastclick.js,single.js',array('jquery'), YUKIMOE_VERSION, true);
		}
		
		if( is_single() ){
			wp_enqueue_script( 'single', home_url('/min/').'?b=wp-content/themes/YukiMoe%20Theme/static/js&f=fastclick.js,single.js,view-history.js,single-add.js',array('jquery'), YUKIMOE_VERSION, true);
		}

    }
}

//全局数据,缩略图不重复
global $thumb_arr;

// Add custom background
add_custom_background();

// Add rss feed
add_theme_support( 'automatic-feed-links' );

// 声明文章形式
add_theme_support( 'post-formats', array('status', 'gallery') );

// Enable link manager
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 添加特色图片支持
add_theme_support( 'post-thumbnails' );

// 禁止半角符号自动转换为全角
remove_filter('the_content', 'wptexturize');

// 只搜索文章，排除页面
 function search_filter($query) {
    if ($query->is_search) {$query->set('post_type', 'post');}
    return $query;
}
 add_filter('pre_get_posts','search_filter');
 
 // 删除 Wordpress 默认的无效信息
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');

// 删除 Wordpress 默认加载的小工具
add_action('widgets_init', 'yukimoe_default_wp_widgets', 1);
function yukimoe_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Nav_Menu');
    unregister_widget('WP_Widget_Tag_Cloud');
}

//主题菜单声明
if ( function_exists('register_nav_menus') ) {
    register_nav_menus(
        array(
            'top-menu' => __( '顶部菜单' ),
			'top-menu-mob' => __( '移动设备首页菜单' ),
			'menu-foo-mob' => __( '移动设备左上弹窗底部链接' )
        )
    );
}

//小工具区域声明
if ( function_exists('register_sidebar') ){
    register_sidebar(array(
        'name'=>'边栏',
        'id' => 'sidebar-home',
		'description' => '只在首页和分类列表中显示',
        'before_widget' => '<ul class="widget-container"><li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li></ul>',
        'before_title' => '<h3 class="widgettitle"><span></span>',
        'after_title' => '</h3>'
    ));
	/*
	register_sidebar(array(
        'name'=>'顶部区域',
        'id' => 'head-area',
		'description' => '显示在首页中内容列表之前',
        'before_widget' => '<ul class="widget-container"><li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li></ul>',
        'before_title' => '<h3 class="widgettitle"><span></span>',
        'after_title' => '</h3>'
    ));
		register_sidebar(array(
        'name'=>'底部区域',
		'description' => '在底栏中显示（所有页面）',
        'id' => 'foot-area',
        'before_widget' => '<ul class="widget-container"><li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li></ul>',
        'before_title' => '<h3 class="widgettitle"><span></span>',
        'after_title' => '</h3>'
    ));
	*/
}

//为wordpess添加emoji支持
//首先补全wp的表情库
function smilies_reset() {
	global $wpsmiliestrans, $wp_smiliessearch;

	// don't bother setting up smilies if they are disabled
	if (!get_option('use_smilies')) {
		return;
	}

	$wpsmiliestrans_fixed = array(
		':mrgreen:' => "\xf0\x9f\x98\xa2",
		':smile:' => "\xf0\x9f\x98\xa3",
		':roll:' => "\xf0\x9f\x98\xa4",
		':sad:' => "\xf0\x9f\x98\xa6",
		':arrow:' => "\xf0\x9f\x98\x83",
		':-(' => "\xf0\x9f\x98\x82",
		':-)' => "\xf0\x9f\x98\x81",
		':(' => "\xf0\x9f\x98\xa7",
		':)' => "\xf0\x9f\x98\xa8",
		':?:' => "\xf0\x9f\x98\x84",
		':!:' => "\xf0\x9f\x98\x85",
	);
	$wpsmiliestrans = array_merge($wpsmiliestrans, $wpsmiliestrans_fixed);
}

//替换emoji路径
function static_emoji_url() {

	return yukimoe_static('emoji/');

}
//让文章内容和评论支持emoji并去除emoji加载的乱七八糟的脚本
function reset_emojis() {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	add_filter('the_content', 'wp_staticize_emoji');
	add_filter('comment_text', 'wp_staticize_emoji',50); //在转换为表情后再转为静态图片
        smilies_reset();
        add_filter('emoji_url', 'static_emoji_url');
}
add_action('init', 'reset_emojis');

function ym_get_wpsmiliestrans(){
    global $wpsmiliestrans;
    $wpsmilies = array_unique($wpsmiliestrans);
    foreach($wpsmilies as $alt => $src_path){
        $emoji = str_replace(array('&#x', ';'), '', wp_encode_emoji($src_path));
        $output .= '<a class="add-smily" data-smilies="'.$alt.'"><img class="wp-smiley" src="'.yukimoe_static('emoji/'). $emoji .'.png" /></a>';
    }
    return $output;
}

//文章编辑器添加表情
add_action('media_buttons_context', 'ym_smilies_custom_button');
function ym_smilies_custom_button($context) {
    $context .= '<style>.smilies-wrap{background:#fff;border: 1px solid #ccc;box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.24);padding: 10px;position: absolute;top: 60px;width: 400px;display:none}.smilies-wrap img{height:24px;width:24px;cursor:pointer;margin-bottom:5px} .is-active.smilies-wrap{display:block}</style><a id="insert-media-button" style="position:relative" class="button insert-smilies add_smilies" title="添加表情" data-editor="content" href="javascript:;">
<span class="dashicons dashicons-admin-users"></span>
添加表情
</a><div class="smilies-wrap">'. ym_get_wpsmiliestrans() .'</div><script>jQuery(document).ready(function(){jQuery(document).on("click", ".insert-smilies",function() { if(jQuery(".smilies-wrap").hasClass("is-active")){jQuery(".smilies-wrap").removeClass("is-active");}else{jQuery(".smilies-wrap").addClass("is-active");}});jQuery(document).on("click", ".add-smily",function() { send_to_editor(" " + jQuery(this).data("smilies") + " ");jQuery(".smilies-wrap").removeClass("is-active");return false;});});</script>';
    return $context;
}

//编辑器添加自定义标签
add_action('admin_print_scripts', 'ym_quicktags');
function ym_quicktags() {
    wp_enqueue_script(
        'ym-quicktags',
        yukimoe_static('js/ym-quicktags.js'),
        array('quicktags')
    );
    };

//前台禁用工具条
if ( !is_admin() ) {  
    add_filter('show_admin_bar', '__return_false'); 
}

// 移除自动保存和修订版本
add_action( 'wp_print_scripts', 'yukimoe_disable_autosave' );
remove_action('pre_post_update', 'wp_save_post_revision' );
function yukimoe_disable_autosave() {
    wp_deregister_script('autosave');
}

// 禁用Google字体
add_filter( 'gettext_with_context', 'yukimoe_disable_open_sans', 888, 4 );
function yukimoe_disable_open_sans( $translations, $text, $context, $domain ) {
    if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
        $translations = 'off';
    }
    return $translations;
}

//gravatar换到多说源
add_filter( 'get_avatar', 'duoshuo_avatar', 10, 3 );
function duoshuo_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"), "gravatar.duoshuo.com", $avatar);
    return $avatar;
}
//将静态文件定向到CDN
$ali_cdn = yukimoe_get_option( 'ali_cdn' );
if ( (!is_admin()) && $ali_cdn ) {
	add_action('wp_loaded','ym_ob_start');
	
	function ym_ob_start() {
		ob_start('ym_aliyun_cdn_replace');
	}
	
	function ym_aliyun_cdn_replace($html){
	$local_host = home_url(); //博客域名
	$aliyun_host = yukimoe_get_option( 'ali_cdn_dm' ); //阿里云域名
	$cdn_exts   = 'js|css|png|jpg|jpeg|gif|ico'; //扩展名（使用|分隔）
	$cdn_dirs   = 'wp-content|wp-includes'; //目录（使用|分隔）
	
	$cdn_dirs   = str_replace('-', '\-', $cdn_dirs);

	if ($cdn_dirs) {
		$regex	=  '/' . str_replace('/', '\/', $local_host) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
		$html =  preg_replace($regex, $aliyun_host . '/$1$4', $html);
	} else {
		$regex	= '/' . str_replace('/', '\/', $local_host) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
		$html =  preg_replace($regex, $aliyun_host . '/$1$3', $html);
	}
	return $html;
}
}

//ajax评论
define('AC_VERSION','1.0.0');

if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	wp_die('请升级到4.4以上版本');
}

if(!function_exists('fa_ajax_comment_scripts')) :

    function fa_ajax_comment_scripts(){
        wp_enqueue_style( 'ajax-comment', get_template_directory_uri() . '/static/css/ajax-com.css', array(), AC_VERSION );
        wp_enqueue_script( 'ajax-comment', get_template_directory_uri() . '/static/js/ajax-com.js', array( 'jquery' ), true );
        wp_localize_script( 'ajax-comment', 'ajaxcomment', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'order' => get_option('comment_order'),
            'formpostion' => 'bottom', //默认为bottom，如果你的表单在顶部则设置为top。
        ) );
    }

endif;

if(!function_exists('fa_ajax_comment_err')) :

    function fa_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }

endif;

if(!function_exists('fa_ajax_comment_callback')) :

    function fa_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
            	fa_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
		$pid=$comment->comment_post_ID;
		$hot= get_post_meta($pid,'um_post_hot',true);
		$hot+=4;
		update_post_meta($pid,'um_post_hot',$hot);
		?>
        <div class="ym-new-comment">
        <?php 
        yukimoe_commentlist($comment, array('avatar_size' => 42));
		?>
        </div>
        <?php die();
    }

endif;

add_action( 'wp_enqueue_scripts', 'fa_ajax_comment_scripts' );
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');




// 引入其他 function
	get_template_part('functions/comment');
	get_template_part('functions/widget');
	if (yukimoe_get_option( 'oss_on')) 
	get_template_part('functions/cloudstore');
 
/*=============================================
 *  主题组件与功能
 *=============================================*/

//网页的头文件
function yukimoe_head(){
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=9;IE=8;IE=7" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <!-- 各页面Title设定 -->
    <?php if ( is_home() ) { ?><title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title><?php } ?>
    <?php if ( is_search() ) { ?><title><?php _e('Search&#34;');the_search_query();echo "&#34;";?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_single() ) { ?><title><?php echo trim(wp_title('',0)); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_author() ) { ?><title><?php wp_title(""); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_archive() ) { ?><title><?php single_cat_title(); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_year() ) { ?><title><?php the_time('Y'); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_month() ) { ?><title><?php the_time('F'); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_page() ) { ?><title><?php echo trim(wp_title('',0)); ?> - <?php bloginfo('name'); ?></title><?php } ?>
    <?php if ( is_404() ) { ?><title>404 Not Found - <?php bloginfo('name'); ?></title><?php } ?>
    <!--各页面基础信息设定-->
    <?php
    global $post;
    if (is_home()){
        $keywords = yukimoe_get_option( 'keywords', 'no entry' );
        $description = yukimoe_get_option( 'description', 'no entry' );
    }elseif (is_single()){
        $keywords = get_post_meta($post->ID, "keywords", true);
        if($keywords == ""){
            $tags = wp_get_post_tags($post->ID);
            foreach ($tags as $tag){
                $keywords = $keywords.$tag->name.",";
            }
            $keywords = rtrim($keywords, ',');
        }
        $description = get_post_meta($post->ID, "description", true);
        if($description == ""){
            if($post->post_excerpt){
                $description = $post->post_excerpt;
            }else{
                $description = mb_strimwidth(strip_tags($post->post_content),0,160,'');
                $description = preg_replace("/\r\n/", "", $description);
            }
        }
    }elseif (is_page()){
        $keywords = get_post_meta($post->ID, "keywords", true);
        $description = get_post_meta($post->ID, "description", true);
    }elseif (is_category()){
        $keywords = single_cat_title('', false);
        $description = category_description();
    }elseif (is_tag()){
        $keywords = single_tag_title('', false);
        $description = tag_description();
    }
    $keywords = trim(strip_tags($keywords));
    $description = trim(strip_tags($description));
    $favicon = yukimoe_get_option( 'favicon' );
    $favicon = $favicon ? $favicon : yukimoe_static('image/favicon.ico');
    ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
	<meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Shortcut Icon" href="<?php echo $favicon;?>" type="image/x-icon" />
    <?php wp_head(); ?>
    <?php 
 }

//主题设置项名称设定
function prefix_options_menu_filter( $menu ) {
	$menu['mode'] = 'menu';
	$menu['page_title'] = 'YukiMoe 设置';
	$menu['menu_title'] = 'YukiMoe 设置';
	$menu['menu_slug'] = 'yukimoe-options';
	return $menu;
}

add_filter( 'optionsframework_menu', 'prefix_options_menu_filter' );

//主题设置-幻灯片设置隐藏
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#slider_img_art').click(function() {
  		jQuery('#section-slider_img_art-content').fadeToggle(400);
	});

	if (jQuery('#slider_img_art:checked').val() !== undefined) {
		jQuery('#slider_img_art-content').show();
	}

});
</script>
<?php
}

//主题路径函数
function yukimoe_static($path, $display=0){
    $path = YUKIMOE_THEMEROOT . "/static/" . $path;

    if( $display ){
        echo $path;
    }else{
        return $path;
    }
}

//顶栏幻灯片
function yukimoe_slider(){
    $slider_active  = yukimoe_get_option( 'slider_img_art');
    $slider_content = yukimoe_get_option('slider_img_art-content');
    $slider_array = explode(',', $slider_content);
    $slider_count = count($slider_array);
    if( $slider_active && ( $slider_content )){?>
        <div id="slider_img_art">
            <div class="center clearfix">
                <div class="flexslider">
                	<ul class="slides">
                    <?php $posts = query_posts(array(
                            'post__in' => $slider_array
                        ));
                        while(have_posts()) : the_post(); ?>
                        <li>
                            <div class="slider-thumbnail" itemscope itemtype="http://schema.org/Article">
                            	<div class="slider-img-bg" style="background-image: url('<?php echo yukimoe_thumbnail(1280,800);?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo yukimoe_thumbnail(1280,800);?>', sizingMethod='scale');"></div>
                                <div class="slider-img-cover">
                                <div>
                                	<div class="slider-inner">
                                        <span class="slider-cat"><?php the_category(' ,');?></span>
                                        <h2 class="slider-title" itemprop="headline"><a href="<?php the_permalink() ?>" rel="bookmark" itemprop="url"><?php the_title(); ?></a></h2>
                                        <a class="slider-link" href="<?php the_permalink() ?>"><span>阅读文章</span><span class="yukimoe slider-button">&#xe622;</span></a>
                                	</div>
                                </div>
                                </div>
                            </div>
                        </li>
                        <?php endwhile; 
                        $posts = null;
                        wp_reset_query();?>
                    </ul>
                    	<div class="slide-navigation">
                          <a href="#" class="flex-next yukimoe right">&#xe624;</a>
                          <a href="#" class="flex-prev yukimoe right">&#xe623;</a>
                        </div>
                    	<div class="slide-control-contain"></div>
                </div>
            </div>
        </div>
    <?php }
}
/*
function yukimoe_headimg() {
	$img_src = trim(yukimoe_get_option( 'headimg' ));
	$img_title = trim(yukimoe_get_option( 'headimgtitle' ));
	$img_disc = trim(yukimoe_get_option( 'headimgdisc' ));
	$img_src = $img_src ? $img_src : yukimoe_static('image/header-img.jpg');
	$img_title = $img_title ? $img_title : '初・はつ';
	$img_disc = $img_disc ? $img_disc : '終わらない夜に願いはひとつ';
	?>
    <div class="head-img">
    	<div style="background-image: url('<?php echo $img_src?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $img_src?>', sizingMethod='scale');">
        </div>
        <div class="over">
        </div>
        <div class="head-img-title">	
        	<h2><?php echo $img_title?></h2>
        	<p><?php echo $img_disc?></p>
        </div>
    </div>
        <?php
}
*/	
	

//截取文章内容
	function yukimoe_content($number=500){
		global $post;
		echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, $number, "……");
	}
	
//一个月内文章显示为几天前
function yukimoe_time_before(){
	global $post ;
	$to = time();
	$from = get_the_time('U') ;
	$diff = (int) abs($to - $from);
	if ($diff <= 3600) {
		$mins = round($diff / 60);
		if ($mins <= 1) {
			$mins = 1;
		}
		$time = sprintf(_n('%s 分钟', '%s 分钟', $mins), $mins) . __( '前' , 'Bing' );
	}
	else if (($diff <= 86400) && ($diff > 3600)) {
		$hours = round($diff / 3600);
		if ($hours <= 1) {
			$hours = 1;
		}
		$time = sprintf(_n('%s 小时', '%s 小时', $hours), $hours) . __( '前' , 'Bing' );
	}
	elseif ($diff >= 86400) {
		$days = round($diff / 86400);
		if ($days <= 1) {
			$days = 1;
			$time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'Bing' );
		}
		elseif( $days > 29){
			$time = get_the_time(get_option('date_format'));
		}
		else{
			$time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'Bing' );
		}
	}
	return $time;
}
add_filter('the_time','yukimoe_time_before');
	
//导航按钮(wp>=4.1)
function yukimoe_nav( ){?>
		<div id="ajax-load-list">
        	<?php next_posts_link('加载更多','');//下一页的链接 ?>
            <div class="loader-inner">
              <div></div>
            </div>
        </div>
	<?php }
	
function yukimoe_com_nav(){?>
		<div id="ajax-load-com">
        	<?php previous_comments_link('更多评论');//下一页的链接 ?>
            <div class="loader-inner">
              <div></div>
            </div>
        </div>
	<?php }


//排除文章分类
	function yukimoe_cat_dis(){
		$catdis_array = yukimoe_get_option('catdis');
			foreach ($catdis_array as $key=>$catdis){
				if ($catdis==1) $catdis_string = $catdis_string.'-'.$key.',';
			}
			$catdis_string = rtrim($catdis_string,',');
			if ($catdis_string=='') return;
			else {$catdis_string = '&cat='.$catdis_string;
			return $catdis_string;
			}
		}

//禁用响应式图片（防止干扰图片链接）
	function disable_srcset( $sources ) {
		return false;
	}
	add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );

//输出文章內的图片
function yukimoe_postimg_list($width=181, $height=201, $num=4 ){
	global $post;
	ob_start();
    ob_end_clean();
	preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content, $match);
	$match_num = count($match[1]);
	if( !empty($match) ){
		$num= ($num<=$match_num)?$num:$match_num;
		for ($i=0;$i<$num;$i++){
            $post_img = yukimoe_thumb($match[1][$i], $width, $height);//都没有则匹配第一张图片
			?>
            <img class="img-gallery img-<?php echo $i+1?>" src="<?php echo $post_img?>" width="<?php echo $width?>" height="<?php echo $height?>"/>
            <?php
		}
        }else{
            return false;//无图空值
        }
}
		
//缩略图输出函数
function yukimoe_thumbnail($width=400, $height=240){
    global $post;
    if( has_post_thumbnail() ){
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_img = yukimoe_thumb($timthumb_src[0], $width, $height);
    }else if( $asako_thumb = get_post_custom_values('a-thumb') ) {
		$asako_thumb = get_post_custom_values('a-thumb');
		$post_img = yukimoe_thumb($asako_thumb[0], $width, $height);//外链缩略图
	}else{
        ob_start();
        ob_end_clean();
        preg_match('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content, $match);
        if( !empty($match) ){
            $post_img = yukimoe_thumb($match[1], $width, $height);//都没有则匹配第一张图片
        }else{
            return false;//无图空值
        }
    }
    return $post_img;
}

//缩略图处理函数(暂留）
function yukimoe_thumb($url, $width, $height, $force=false){
	$user_aliyun = yukimoe_get_option( 'oss_on');
    $user_qiniu = 0;//get_setting('qiniu');
    $user_youpai = 0;//get_setting('youpai');
	$width = 1.5*$width; 
	$height = 1.5*$height;
	if( $force ){
		$url = YUKIMOE_THEMEROOT ."/timthumb.php&#63;src={$url}&#38;w={$width}&#38;h={$height}&#38;zc=1&#38;q=100";
	}else{
		if( $user_aliyun ){
			$url .= "@{$width}w_{$height}h_1e_1c_gif_60Q";
		}else if( $user_qiniu ){
			$url .= "?imageView/1/w/{$width}/h/{$height}/q/100";
		}else if( $user_youpai ) {
			$url .= "@!{$width}x{$height}";
		}else{
			$url = YUKIMOE_THEMEROOT ."/timthumb.php&#63;src={$url}&#38;w={$width}&#38;h={$height}&#38;zc=1&#38;q=100";
		}
	}
    return $url;
}

// 搜索关键词高亮
add_filter("the_title", "yukimoe_search_highlight", 200);
add_filter("the_excerpt", "yukimoe_search_highlight", 200);
add_filter("the_content", "yukimoe_search_highlight", 200);
function yukimoe_search_highlight($buffer){
    if(is_search()){
        $arr = explode(" ", get_search_query());
        $arr = array_unique($arr);
        foreach($arr as $v){
            if($v){
                $keyword = "<span class='highlight'>$v</span>";
                $pattern= '/(?!<[^>]*)('.$v.')(?![^<]*>)/i';
                $buffer = preg_replace($pattern, $keyword, $buffer);
                $buffer = preg_replace("@&(\w{0,6})?({$keyword})(\w{0,6})?;@","&$1$v$3;", $buffer);
            }
        }
    }
    return $buffer;
}

//文章页底部来源及版权信息
function yukimoe_sincopy(){
	global $post;
	$sincopy = yukimoe_get_option('sincopy');
	if ( $sincopy ) { 
		?>
        <div class="sin-copy">
        <?php 
             $from = get_post_meta($post->ID, 'come-from', true);
             $fref = get_post_meta($post->ID, 'from-link', true);
             if( $from ){
                echo '来源：'."<a href='$fref' target='blank' rel='nofllow'>$from</a>";}
                else echo '来源：'."原创";
        ?>
            <p>声明：本站原创文章采用<a title="署名-非商业性使用-相同方式共享 3.0 中国大陆" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cn/" target="_blank" rel="external nofollow">&nbsp;BY-NC-SA&nbsp;</a>创作共用协议，转载时请以链接形式标明本文地址；非原创（转载）文章版权归原作者所有。&nbsp;<a title="版权声明" href="<?php echo YUKIMOE_THEMEROOT;?>/copyright/">©查看版权声明</a></p>
        </div>
        <?php }
		else return;
}

//主题文章页添加来源名称和链接
add_action( 'add_meta_boxes', 'yukimoe_single_from' );

add_action( 'save_post', 'yukimoe_save_postdata' );

function yukimoe_single_from() {
	add_meta_box('come-from','文章来源','yukimoe_single_disp','post','side');
}
 
function yukimoe_single_disp( $post ) {

  wp_nonce_field( 'come_from_update' , 'come_from_update_nounce' );
 
  $value_1 = get_post_meta( $post->ID, 'come-from', true );
  $value_2 = get_post_meta( $post->ID, 'from-link', true );
  echo '<label for="come_from">';
  echo '请输入来源网站的名称，如"bilibili"';
  echo '</label> ';
  echo '<input type="text" id="come_from" name="come_from" value="'.esc_attr($value_1).'" size="25" />';
  echo '<br>';
  echo '<label for="from_link">';
  echo '请输入来源页面的网址，如"http://live.bilibili.com/2333"';
  echo '</label> ';
  echo '<input type="text" id="from_link" name="from_link" value="'.esc_attr($value_2).'" size="25" />';
}
 
function yukimoe_save_postdata( $post_id ) {
	
  if ( ! current_user_can( 'edit_post', $post_id ) )
        return;

  if ( ! isset( $_POST['come_from_update_nounce'] ) || ! wp_verify_nonce( $_POST['come_from_update_nounce'], 'come_from_update' ) )
      return;

  $post_ID = $_POST['post_ID'];

  $mydata_1 = sanitize_text_field( $_POST['come_from'] );
  $mydata_2 = sanitize_text_field( $_POST['from_link'] );

  add_post_meta($post_ID, 'come-from', $mydata_1, true) or
  update_post_meta($post_ID, 'come-from', $mydata_1);
  add_post_meta($post_ID, 'from-link', $mydata_2, true) or
  update_post_meta($post_ID, 'from-link', $mydata_2);
}

//维护模式
function maintenance_mode($mainten=0) {
	$mainten = yukimoe_get_option( 'maintenance');
	$mainten = $mainten?$mainten:0;
	if ($mainten):
		$maint_word=yukimoe_get_option( 'maintword');
		$maint_word=$maint_word?$maint_word:'稍';
		if(!current_user_can('edit_themes') || !is_user_logged_in()){
        wp_die('网站正在维护中，请'.$maint_word.'后访问', '施工中……', array('response' => '503'));
    }
	endif;
}

add_action('get_header', 'maintenance_mode');
	

//相关文章
function yukimoe_rel_post($post_num = 3) {
    global $post;
    echo '<ul id="related-posts" class="clearfix">';
    $exclude_id = $post->ID;
    $posttags = get_the_tags(); $i = 0;
    if ( $posttags ) {
        $tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->term_id . ',';
        $args = array(
            'post_status' => 'publish',
            'tag__in' => explode(',', $tags),
			'tax_query' => array( array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => 'post-format-status',
				'operator' => 'NOT IN',
			) ),
            'post__not_in' => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'orderby' => 'comment_date',
            'posts_per_page' => $post_num
        );
        query_posts($args);
        while( have_posts() ) { the_post(); ?>
            <li class="left related-post<?php if($i%3==0 && $i>0) echo " related-post-last";?>">
                <a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><img src="<?php echo yukimoe_thumbnail( 256,240 ); ?>"/></a>
                <a href="<?php the_permalink(); ?>" ><div class="rel-over"></div></a>
                <a class="related-post-tittle" href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
            </li>
        <?php
            $exclude_id .= ',' . $post->ID; $i ++;
        } wp_reset_query();
    }
    if ( $i < $post_num ) {
        $cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
        $args = array(
			'tax_query' => array( array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => 'post-format-status',
				'operator' => 'NOT IN',
			) ),
            'category__in' => explode(',', $cats),
            'post__not_in' => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'orderby' => 'comment_date',
            'posts_per_page' => $post_num - $i
        );
        query_posts($args);
        while( have_posts() ) { the_post(); ?>
            <li class="left related-post<?php if($i%3==0 && $i>0) echo " related-post-last";?>">
                <a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><img src="<?php echo yukimoe_thumbnail( 256,240 ); ?>"/></a>
                <a href="<?php the_permalink(); ?>" ><div class="rel-over"></div></a>
                <a class="related-post-tittle" href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
            </li>

        <?php $i++;
        } wp_reset_query();
    }
    if ( $i  == 0 )  return;
    echo '</ul>';
}

//ajax提醒
function yukimoe_ajax_error($text) { 
    header('HTTP/1.0 500 Internal Server Error');
	header('Content-Type: text/plain;charset=UTF-8');
    echo $text;
    exit;
}

//面包屑导航
function yukimoe_breadcrumbs() {
	$delimiter = '»'; // 分隔符
	$before = '<span class="current">'; // 在当前链接前插入
	$after = '</span>'; // 在当前链接后插入
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div itemscope itemtype="http://schema.org/WebPage" id="crumbs">'.'当前位置：';
		global $post;
		$homeLink = home_url();
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . '首页'. '</a> ' . $delimiter . ' ';
		if ( is_category() ) { // 分类 存档
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) { // 天 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) { // 月 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) { // 年 存档
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) { // 文章
			if ( get_post_type() != 'post' ) { // 自定义文章类型
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else { // 文章 post
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) { // 附件
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) { // 页面
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) { // 父级页面
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_search() ) { // 搜索结果
			echo $before ;
			echo '搜索“'.(get_search_query()).'”的结果';
			echo  $after;
		} elseif ( is_tag() ) { //标签 存档
			echo $before ;
			echo '标签:'.(single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) { // 作者存档
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			echo '作者:'.($userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) { // 404 页面
			echo $before;
			echo 'Not Found';
			echo  $after;
		}
		if ( get_query_var('paged') ) { // 分页
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo '第'.(get_query_var('paged') ).'页';
		}
		echo '</div>';
	}
}
