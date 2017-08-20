<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'hatsumi-options';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// Test data
	$test_array = array(
		'one' => __( 'One', 'theme-textdomain' ),
		'two' => __( 'Two', 'theme-textdomain' ),
		'three' => __( 'Three', 'theme-textdomain' ),
		'four' => __( 'Four', 'theme-textdomain' ),
		'five' => __( 'Five', 'theme-textdomain' )
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => __( 'French Toast', 'theme-textdomain' ),
		'two' => __( 'Pancake', 'theme-textdomain' ),
		'three' => __( 'Omelette', 'theme-textdomain' ),
		'four' => __( 'Crepe', 'theme-textdomain' ),
		'five' => __( 'Waffle', 'theme-textdomain' )
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories('hide_empty=0');
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}


	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();
	
	/*第一页*/
	
	$options[] = array(
		'name' => '基本设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '关键字',
		'desc' => '请输入你网站的关键字',
		'id' => 'keywords',
		'placeholder' => '如：动漫，新番，Cosplay',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '网站描述',
		'desc' => '请输入网站描述',
		'id' => 'description',
		'placeholder' => '用一段话简要描述你的网站',
		'type' => 'textarea'
	);
		
	$options[] = array(
		'name' => __( 'favicon' ),
		'desc' => __( '请上传一张图片作为网站的favicon图标' ),
		'id' => 'favicon',
		'type' => 'upload'
	);	
	
		
	$options[] = array(
		'name' => __( '首页横幅' ),
		'desc' => __( '请上传一张图片作为网站的首页横幅' ),
		'id' => 'headimg',
		'type' => 'upload'
	);	
	
	$options[] = array(
		'name' => __( 'LOGO' ),
		'desc' => __( '请上传一张图片作为网站LOGO' ),
		'id' => 'logo',
		'type' => 'upload'
	);	
	
	$options[] = array(
		'name' => __( 'LOGO(小)' ),
		'desc' => __( '请上传一张图片作为网站LOGO(小，用于手机等小屏设备)' ),
		'id' => 'logo-small',
		'type' => 'upload'
	);	
		
	$options[] = array(
		'name' => '版权年份',
		'desc' => '底栏版权部分的年份信息',
		'id' => 'yearfrom',
		'placeholder' => '如：2015-2016',
		'type' => 'text',
		'class' => 'mini'
	);
	
	/* 第二页 */
	
	$options[] = array(
		'name' => '高级设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '从首页文章中排除分类',
		'desc' => '勾选后该分类中的文章将不会在首页文章列表中显示',
		'id' => 'catdis',
		'type' => 'multicheck',
		'options' => $options_categories
	);
	
	$options[] = array(
		'name' => '文章来源与版权信息',
		'desc' => '勾选以在内页文章内容的底部显示来源链接和版权信息',
		'id' => 'sincopy',
		'std' => '1',
		'type' => 'checkbox'
		
	);
	
	$options[] = array(
		'name' => '反垃圾评论',
		'desc' => '防止垃圾评论。勾选以启用。',
		'id' => 'anti-junk',
		'std' => '1',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => '评论回复邮件',
		'desc' => '勾选以启用。',
		'id' => 'send_mail',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => '统计代码',
		'desc' => '请输入统计代码（保存时会过滤多余的javascript标签）',
		'id' => 'analysis',
		'placeholder' => '如需统计网站数据，请将统计工具（如CNZZ）的javascript代码粘贴在此处。添加的统计工具不会在网页中显示出来。',
		'type' => 'textarea'
	);
	
		$options[] = array(
		'name' => '维护模式',
		'desc' => '勾选以开启维护模式，仅管理员用户可正常访问',
		'id' => 'maintenance',
		'std' => '0',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name' => '维护提示',
		'desc' => '请输入维护时在网站显示的维护提示信息',
		'id' => 'maintword',
		'placeholder' => '如：“网站正在维护中，请稍后访问”',
		'type' => 'text',
		'class' => 'text'
	);
	
	//第三页 OSS/CDN
		$options[] = array(
		'name' => '阿里云存储&CDN设置',
		'type' => 'heading'
	);
		
		$options[] = array(
		'name' => '阿里云存储（OSS）',
		'desc' => '使用阿里云OSS及其图片服务。勾选以启用。',
		'id' => 'oss_on',
		'type' => 'checkbox'
	);
		
		$options[] = array(
		'desc' => '请输入你在阿里云oss中设置的bucket名称',
		'id' => 'oss_bucket',
		'placeholder' => '例如：a-bucket',
		'type' => 'text'
	);
	
		$options[] = array(
		'desc' => '请输入对应的access key',
		'id' => 'oss_access_key',
		'placeholder' => '请在阿里云控制台密钥管理页面获取',
		'type' => 'text'
	);
	
		$options[] = array(
		'desc' => '请输入对应的secret key',
		'id' => 'oss_secret_key',
		'placeholder' => '请在阿里云控制台密钥管理洁面获取',
		'type' => 'text'
	);
	
		$options[] = array(
		'desc' => '请参考XXXX输入节点对应的end point',
		'id' => 'oss_end_point',
		'placeholder' => '例如（以上海为例）：oss-cn-shanghai.aliyuncs.com',
		'type' => 'text'
	);
	
		$options[] = array(
		'desc' => '文件在oss bucket中的保存路径',
		'id' => 'oss_path',
		'placeholder' => '例如：/upload (若留空则保存在bucket根目录)',
		'type' => 'text'
	);
	
		$options[] = array(
		'desc' => 'oss的访问域名（支持绑定到oss bucket的独立域名，留空后只能使用本地资源）',
		'id' => 'oss_access_url',
		'placeholder' => '例如： ',
		'type' => 'text'
	);
	
		$options[] = array(
		'name' => '阿里云CDN',
		'desc' => '使用阿里云CDN加速静态资源。勾选以启用。',
		'id' => 'ali_cdn',
		'type' => 'checkbox'
	);
		
		$options[] = array(
		'desc' => '阿里云CDN域名',
		'id' => 'ali_cdn_dm',
		'placeholder' => '例如： ',
		'type' => 'text'
	);
		
	
	return $options;
}