<?php

if (! class_exists(Alibaba))
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'SDK/alioss.class.php');
	

$oss_options = hatsumi_oss_options();

function hatsumi_oss_options(){
	$options  = array(
        'bucket'			=> 	"",
        'access_key'  	=> 	"",
        'secret_key'		=> 	"",
        'end_point'		=> 	"",
        'path'			=> 	"",
        'access_url'		=> 	"",
        'img_url'		=> 	""
    );//初始化oss设置数组；
	
	$options['bucket'] 		= 	trim(stripslashes(hatsumi_get_option('oss_bucket')));
	$options['access_key']	= 	trim(stripslashes(hatsumi_get_option('oss_access_key')));
	$options['secret_key'] 	= 	trim(stripslashes(hatsumi_get_option('oss_secret_key')));
	$options['end_point'] 	= 	trim(stripslashes(hatsumi_get_option('oss_end_point')));
	$options['path'] 		= 	trim(trim(stripslashes(hatsumi_get_option('oss_path'))), '/').'/';
	$options['access_url'] 	= 	trim(stripslashes(hatsumi_get_option('oss_access_url')));
	$options['img_url'] 		= 	trim(stripslashes(hatsumi_get_option('oss_img_url')));
	//从设置中获取值存入数组；
	
	return $options;
}


//上传时同时将本地文件上传至oss
function upload_to_oss($file)
{
    if ($_GET["action"] == 'upload-plugin' || $_GET["action"] == 'upload-theme') 
        return $file;

    $wp_uploads = wp_upload_dir();
    $oss_options = hatsumi_oss_options();
    $config = array(
            'id'     => esc_attr($oss_options['access_key']),
            'key'    => esc_attr($oss_options['secret_key']),
            'bucket' => esc_attr($oss_options['bucket']),
            'end_point' => esc_attr($oss_options['end_point'])
        );
    $oss_upload_path = trim($oss_options['path'],'/');

    $object = str_replace($wp_uploads['basedir'], '', $file['file']);
    $object = ltrim($oss_upload_path . '/' .ltrim($object, '/'), '/');

    if(!is_object($aliyun_oss))
        $aliyun_oss = Alibaba::Storage($config);

    $opt['Expires'] = 'access plus 1 years';
    $aliyun_oss->saveFile( $object, $file['file'], $opt);

    return $file;

}
add_filter('wp_handle_upload', 'upload_to_oss', 30);

//删除文件时同时删除oss上的文件
function delete_from_oss($file)
{
    if(!false == strpos($file, '@!'))
        return $file;

    $oss_options = hatsumi_oss_options();
    $config = array(
            'id'     => esc_attr($oss_options['access_key']),
            'key'    => esc_attr($oss_options['secret_key']),
            'bucket' => esc_attr($oss_options['bucket']),
            'end_point' => esc_attr($oss_options['end_point'])
        );
    $oss_upload_path = trim($oss_options['path'],'/');
    $wp_uploads = wp_upload_dir();

    $del_file = str_replace($wp_uploads['basedir'], '', $file);
    $del_file = ltrim($oss_upload_path . '/' .ltrim($del_file, '/'), '/');

    if(!is_object($aliyun_oss))
        $aliyun_oss = Alibaba::Storage($config);

    $aliyun_oss->delete($del_file);

    return $file;
}
add_action('wp_delete_file', 'delete_from_oss');

//修正无法删除本地缩略图
function delete_thumb_img($file)
{
    if(!false == strpos($file, '@!')) //todo
        return $file;

    $file_t = substr($file, 0, strrpos($file, '.'));
    array_map('_delete_local_file', glob($file_t.'-*'));
    return $file;
}
if(!$oss_options['img_url'] == "")
    add_action('wp_delete_file', 'delete_thumb_img', 99);

function _delete_local_file($file){
    try{
        //文件不存在
        if(!@file_exists($file))
            return TRUE;
        //删除文件
        if(!@unlink($file))
            return FALSE;
        return TRUE;
    }
    catch(Exception $ex){
        return FALSE;
    }
}

//更改缩略图文件后缀
function modefiy_img_meta($data) {
    $filename = basename($data['file']);

    if(isset($data['sizes']['thumbnail'])) {
        $data['sizes']['thumbnail']['file'] = $filename.'@!thumbnail';
    }
    if(isset($data['sizes']['post-thumbnail'])) {
        $data['sizes']['post-thumbnail']['file'] = $filename.'@!post-thumbnail';
    }
    if(isset($data['sizes']['medium'])) {
        $data['sizes']['medium']['file'] = $filename.'@!medium';
    }
    if(isset($data['sizes']['large'])) {
        $data['sizes']['large']['file'] = $filename.'@!large';
    }

    return $data;
}
if(!$oss_options['img_url'] == "")
    add_filter('wp_get_attachment_metadata', 'modefiy_img_meta', 990);

//更改图片文件路径
function modefiy_img_url($url, $post_id) {
    $wp_uploads = wp_upload_dir();
    $oss_options = hatsumi_oss_options();

    if(wp_attachment_is_image($post_id)){
        $img_baseurl = rtrim($oss_options['img_url'], '/');
        if(rtrim($oss_options['path'], '/') != ""){
            $img_baseurl = $img_baseurl .'/'. rtrim($oss_options['path'], '/');
        }
        $url = str_replace(rtrim($wp_uploads['baseurl'], '/'), $img_baseurl, $url);
    }
    return $url;
}
if(!$oss_options['img_url'] == "")
    add_filter('wp_get_attachment_url', 'modefiy_img_url', 30, 2);
	
//更改文件上传路径
function reset_upload_url_path( $uploads ) {
    $oss_options = hatsumi_oss_options();
	if ($oss_options['access_url'] != "") {
        $baseurl = rtrim($oss_options['access_url'], '/');
        if(rtrim($oss_options['path'], '/') != ""){
            $baseurl = $baseurl .'/'. rtrim($oss_options['path'], '/');
        }
        $uploads['baseurl'] = $baseurl;
    }
    return $uploads;
}
add_filter( 'upload_dir', 'reset_upload_url_path', 30 );

