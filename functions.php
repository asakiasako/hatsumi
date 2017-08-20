<?php
/**
 * Theme functions file
 *
 *
 * @package Hatsumi
 * @author 夏娜酱拌饭
 */
 
 
 //函数引用常量定义
 define('YUKIMOE_THEMEROOT', get_bloginfo('template_directory'));
 define('YUKIMOE_VERSION', '0.8.0');
 
//主题设置项函数声明
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';
	$optionsfile = locate_template( 'options.php' );
	load_template( $optionsfile );
	
 //引用主函数文件
 get_template_part('functions/main');