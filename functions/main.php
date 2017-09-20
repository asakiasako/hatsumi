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
add_action('wp_enqueue_scripts', 'hatsumi_scripts');
function hatsumi_scripts(){
    global $wp_styles, $wp_scripts;
    wp_enqueue_style('style', get_bloginfo('stylesheet_url'),array(),HATSUMI_VERSION );

    /**
     * Load our IE specific stylesheet for a range of newer versions:
     * <!--[if gt IE 8]> ... <![endif]-->
     * <!--[if gte IE 9]> ... <![endif]-->
     * NOTE: You can use the 'greater than' or the 'greater than or equal to' syntax here interchangeably.
     */

    if( !is_admin() ){
		
        wp_enqueue_script( 'jquery' );
		
        if( is_home() ){
		wp_enqueue_script( 'index', home_url('/min/').'?b=wp-content/themes/Hatsumi theme/static/js&f=fastclick.js,index.js,view-history.js,bdpush.js',array('jquery'), HATSUMI_VERSION, true);
        }
		
		if( is_archive() || is_search() ) {
		wp_enqueue_script( 'archive', home_url('/min/').'?b=wp-content/themes/Hatsumi theme/static/js&f=fastclick.js,index.js,view-history.js',array('jquery'), HATSUMI_VERSION, true);
        }

        if( is_page() ){
		wp_enqueue_script( 'page', home_url('/min/').'?b=wp-content/themes/Hatsumi theme/static/js&f=fastclick.js,single.js,OwO.min.js',array('jquery'), HATSUMI_VERSION, true);
		wp_enqueue_style('owo', hatsumi_static('css/OwO.min.css'),array(),HATSUMI_VERSION );
		}
		
		if( is_single() ){
		wp_enqueue_script( 'single', home_url('/min/').'?b=wp-content/themes/Hatsumi theme/static/js&f=fastclick.js,single.js,view-history.js,single-add.js,highlight.js,OwO.min.js,bdpush.js',array('jquery'), HATSUMI_VERSION, true);
		wp_enqueue_style('owo', hatsumi_static('css/OwO.min.css'),array(),HATSUMI_VERSION );
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
add_theme_support( 'post-formats', array('image', 'status', 'gallery') );

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
add_action('widgets_init', 'hatsumi_default_wp_widgets', 1);
function hatsumi_default_wp_widgets() {
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
			'top-menu-more' => __( '顶部菜单扩展' ),
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

//编辑器添加自定义标签
add_action('admin_print_scripts', 'ym_quicktags');
function ym_quicktags() {
    wp_enqueue_script(
        'ym-quicktags',
        hatsumi_static('js/ym-quicktags.js'),
        array('quicktags')
    );
    };

//前台禁用工具条
if ( !is_admin() ) {  
    add_filter('show_admin_bar', '__return_false'); 
}

// 移除自动保存和修订版本
add_action( 'wp_print_scripts', 'hatsumi_disable_autosave' );
remove_action('pre_post_update', 'wp_save_post_revision' );
function hatsumi_disable_autosave() {
    wp_deregister_script('autosave');
}

//禁用Google字体
add_filter( 'gettext_with_context', 'hatsumi_disable_open_sans', 888, 4 );
function hatsumi_disable_open_sans( $translations, $text, $context, $domain ) {
    if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
        $translations = 'off';
    }
    return $translations;
}

//添加img标签支持
function auto_comment_image( $comment ) {// by https://mufeng.me
    global $allowedtags;
    $content = $comment["comment_content"];
    // alt部分自行填写      
    $content = preg_replace('/((https|http|ftp):\/\/){1}.+?.(jpg|gif|bmp|bnp|png)$/is','<img src="$0" alt="" />',$content);
    //允许发布img标签      
    $allowedtags['img'] = array('src' => array (), 'alt' => array ());
    // 重新给$comment赋值      
    $comment["comment_content"] = $content;
    return $comment;
}
add_filter('preprocess_comment', 'auto_comment_image');

//禁用emoji渲染
remove_action('admin_print_scripts',	'print_emoji_detection_script');
remove_action('admin_print_styles',	'print_emoji_styles');

remove_action('wp_head',		'print_emoji_detection_script',	7);
remove_action('wp_print_styles',	'print_emoji_styles');

remove_action('embed_head',		'print_emoji_detection_script');

remove_filter('the_content_feed',	'wp_staticize_emoji');
remove_filter('comment_text_rss',	'wp_staticize_emoji');
remove_filter('wp_mail',		'wp_staticize_emoji_for_email');

//替换头像服务器
function hatsumi_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"avatar.swiity.com",$avatar);
	$avatar = str_replace("https","http",$avatar);
	
	return $avatar;
}
add_filter( 'get_avatar', 'hatsumi_avatar', 10, 3 );


//将静态文件定向到CDN
$ali_cdn = hatsumi_get_option( 'ali_cdn' );
if ( (!is_admin()) && $ali_cdn ) {
	add_action('wp_loaded','ym_ob_start');
	
	function ym_ob_start() {
		ob_start('ym_aliyun_cdn_replace');
	}
	
	function ym_aliyun_cdn_replace($html){
	$local_host = home_url(); //博客域名
	$aliyun_host = hatsumi_get_option( 'ali_cdn_dm' ); //阿里云域名
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
        hatsumi_commentlist($comment, array('avatar_size' => 42));
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
	if (hatsumi_get_option( 'oss_on')) 
	get_template_part('functions/cloudstore');
 
/*=============================================
 *  主题组件与功能
 *=============================================*/

//网页的头文件
function hatsumi_head(){
    ?>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta property="wb:webmaster" content="9209080a81408994" />
    <meta name="baidu-site-verification" content="HZLo0l0V0v" />
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <!-- 各页面Title设定 -->
    <?php if ( is_home() ) { ?><title><?php bloginfo('name'); ?></title><?php } ?>
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
        $keywords = hatsumi_get_option( 'keywords', 'no entry' );
        $description = hatsumi_get_option( 'description', 'no entry' );
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
    $favicon = hatsumi_get_option( 'favicon' );
    $favicon = $favicon ? $favicon : hatsumi_static('image/favicon.png');
    ?>
    <script type="text/x-mathjax-config">
	MathJax.Hub.Config({
		"HTML-CSS": {
			showMathMenu: false
		},
		showProcessingMessages: false,
    	messageStyle: "none"
	});
	</script>
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
	$menu['page_title'] = 'hatsumi 设置';
	$menu['menu_title'] = 'hatsumi 设置';
	$menu['menu_slug'] = 'hatsumi-options';
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
function hatsumi_static($path, $display=0){
    $path = HATSUMI_THEMEROOT . "/static/" . $path;

    if( $display ){
        echo $path;
    }else{
        return $path;
    }
}

function hatsumi_headimg() {
	$img_src = trim(hatsumi_get_option( 'headimg' ));
	$img_src = $img_src ? $img_src : hatsumi_static('image/header-img.jpg');
	?>
    <div class="head-img home m-header">
    	<div style="background-image: url('<?php echo $img_src?>');filter: progid: DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $img_src?>', sizingMethod='scale');">
        </div>
    </div>
        <?php
}
	
//截取文章内容
	function hatsumi_content($number=500){
		global $post;
		echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, $number, "……");
	}
	
//一个月内文章显示为几天前
function hatsumi_time_before(){
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
add_filter('the_time','hatsumi_time_before');
	
//导航按钮(wp>=4.1)
function hatsumi_nav( ){?>
		<div id="ajax-load-list">
        	<?php next_posts_link('加载更多','');//下一页的链接 ?>
            <div class="loader-inner">
              <div></div>
            </div>
        </div>
	<?php }
	
function hatsumi_com_nav(){?>
		<div id="ajax-load-com">
        	<?php previous_comments_link('更多评论');//下一页的链接 ?>
            <div class="loader-inner">
              <div></div>
            </div>
        </div>
	<?php }


//排除文章分类
	function hatsumi_cat_dis(){
		$catdis_array = hatsumi_get_option('catdis');
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
function hatsumi_postimg_list($width=181, $height=201, $num=4 ){
	global $post;
	ob_start();
    ob_end_clean();
	preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content, $match);
	$match_num = count($match[1]);
	if( !empty($match) ){
		$num= ($num<=$match_num)?$num:$match_num;
		for ($i=0;$i<$num;$i++){
            $post_img = hatsumi_thumb($match[1][$i], $width, $height);//都没有则匹配第一张图片
			?>
            <img class="img-gallery img-<?php echo $i+1?>" src="<?php echo $post_img?>" width="<?php echo $width?>" height="<?php echo $height?>"/>
            <?php
		}
        }else{
            return false;//无图空值
        }
}
		
//缩略图输出函数
function hatsumi_thumbnail($width=400, $height=240){
    global $post;
    if( has_post_thumbnail() ){
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_img = hatsumi_thumb($timthumb_src[0], $width, $height);
    }else if( $asako_thumb = get_post_custom_values('a-thumb') ) {
		$asako_thumb = get_post_custom_values('a-thumb');
		$post_img = hatsumi_thumb($asako_thumb[0], $width, $height);//外链缩略图
	}else{
            return false;//无图空值
    }
    return $post_img;
}

//缩略图处理函数(暂留）
function hatsumi_thumb($url, $width, $height, $force=false){
	$user_aliyun = hatsumi_get_option( 'oss_on');
	$width = 1.5*$width;
	$width = round($width);
	$height = 1.5*$height;
	$height = round($height);
	if( $force || (!$user_aliyun) ){
		$url = HATSUMI_THEMEROOT ."/timthumb.php&#63;src={$url}&#38;w={$width}&#38;h={$height}&#38;zc=1&#38;q=100";
	}else{
			$url .= "?x-oss-process=image/resize,m_mfit,h_{$height},w_{$width},limit_0/crop,h_{$height},w_{$width},g_center/format,gif/interlace,1/quality,Q_80";
	}
    return $url;
}

// 搜索关键词高亮
add_filter("the_title", "hatsumi_search_highlight", 200);
add_filter("the_excerpt", "hatsumi_search_highlight", 200);
add_filter("the_content", "hatsumi_search_highlight", 200);
function hatsumi_search_highlight($buffer){
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
function hatsumi_sincopy(){
	global $post;
	$sincopy = hatsumi_get_option('sincopy');
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
            <p>声明：本站原创文章采用<a title="署名-非商业性使用-相同方式共享 3.0 中国大陆" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cn/" target="_blank" rel="external nofollow">&nbsp;BY-NC-SA&nbsp;</a>创作共用协议，转载时请以链接形式标明本文地址；非原创（转载）文章版权归原作者所有。&nbsp;<a title="版权声明" href="<?php home_url()?>/copyright/">©查看版权声明</a></p>
        </div>
        <?php }
		else return;
}

//主题文章页添加来源名称和链接
add_action( 'add_meta_boxes', 'hatsumi_single_from' );

add_action( 'save_post', 'hatsumi_save_postdata' );

function hatsumi_single_from() {
	add_meta_box('come-from','文章来源','hatsumi_single_disp','post','side');
}
 
function hatsumi_single_disp( $post ) {

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
 
function hatsumi_save_postdata( $post_id ) {
	
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
	$mainten = hatsumi_get_option( 'maintenance');
	$mainten = $mainten?$mainten:0;
	if ($mainten):
		$maint_word=hatsumi_get_option( 'maintword');
		$maint_word=$maint_word?$maint_word:'稍';
		if(!current_user_can('edit_themes') || !is_user_logged_in()){
        wp_die($maint_word, '施工中……', array('response' => '503'));
    }
	endif;
}

add_action('get_header', 'maintenance_mode');
	

//相关文章
function hatsumi_rel_post($post_num = 3) {
    global $post;
    $exclude_id = $post->ID;
    $posttags = get_the_tags(); 
	$i = 0;
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
		if (have_posts()) echo '<ul id="related-posts" class="clearfix">';
        while( have_posts() ) { the_post(); ?>
            <li class="left related-post<?php if($i%3==0 && $i>0) echo " related-post-last";?>">
                <a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><img src="<?php 
				$rel_thumb = hatsumi_thumbnail( 256,240 );
				$rel_thumb = $rel_thumb ? $rel_thumb : hatsumi_thumb('http://oss.swiity.com/images/single_default/single-default'.rand(0,7).'.jpg', 256, 240);
				echo $rel_thumb; ?>"/></a>
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
		if (($i == 0) && have_posts()) echo '<ul id="related-posts" class="clearfix">';
        while( have_posts() ) { the_post(); ?>
            <li class="left related-post<?php if($i%3==0 && $i>0) echo " related-post-last";?>">
                <a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><img src="<?php 
				$rel_thumb = hatsumi_thumbnail( 256,240 );
				$rel_thumb = $rel_thumb ? $rel_thumb : hatsumi_thumb('http://oss.swiity.com/images/single_default/single-default'.rand(0,7).'.jpg', 256, 240);
				echo $rel_thumb;?>"/></a>
                <a href="<?php the_permalink(); ?>" ><div class="rel-over"></div></a>
                <a class="related-post-tittle" href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
            </li>
        <?php $i++;
        } wp_reset_query();
    }
	if ($i == 0) return;
    echo '</ul>';
}

//禁止直接进入登录页
add_action('login_enqueue_scripts','login_protection');
    function login_protection(){
        if($_GET['user'] != 'sdtclass')  header('Location: /');
    }

//ajax提醒
function hatsumi_ajax_error($text) { 
    header('HTTP/1.0 500 Internal Server Error');
	header('Content-Type: text/plain;charset=UTF-8');
    echo $text;
    exit;
}

//面包屑导航
function hatsumi_breadcrumbs() {
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
