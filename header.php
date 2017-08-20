<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php hatsumi_head();?>
</head>
<body <?php body_class();?>>
<header id="header">
		<div class="mob-log <?php if(!$user_ID) echo "user-login"; else echo "mob-logged";?> left">
        	<i class="hatsumi">&#xe62a;</i>
        </div>
        <div class="mob-log mob-logclose">
        	<i class="hatsumi">&#xe629;</i>
        </div>
        <?php
            $logo = hatsumi_get_option( 'logo' );
            $logo = $logo ? $logo : hatsumi_static('image/logo.png');
			$logo_sm = hatsumi_get_option( 'logo-small' );
            $logo_sm = $logo_sm ? $logo_sm : hatsumi_static('image/logo-small.png');
        ?>
		<a class="logo" href="<?php bloginfo('url');?>" alt="<?php bloginfo('name'); ?>"><img src="<?php echo $logo;?>" alt="<?php bloginfo('name'); ?>" /><img class="logo-sm" src="<?php echo $logo_sm;?>" alt="<?php bloginfo('name'); ?>" /></a>
		<?php wp_nav_menu(array(
            'theme_location' => 'top-menu',
            'container' => 'nav',
            'container_id' => 'topnav'
        )); 
		
        if(!$user_ID):?>
        <div class="user-login right">登录</div>
        <?php else: ?>
        	<div class="top-avatar log-in right">
                <?php global $current_user; get_currentuserinfo(); echo um_get_avatar( $current_user->ID , '30' , um_get_avatar_type($current_user->ID));?>
                <p class="top-log-info">
                <span class="usr-name">
            	    <?php global $current_user; get_currentuserinfo(); echo $current_user->display_name;?>
                </span>
                <a class="top-uc" href="<?php echo home_url('/author/'.$current_user->ID);?>">个人中心</a>
                <?php if( current_user_can( 'manage_options' ) ) { ?>
                <a class="top-uc" href="<?php echo home_url('/admin/');?>">后台管理</a>
                <?php } ?>
                <a href="<?php echo wp_logout_url(home_url(add_query_arg(array()))); ?>" class="logout">退出</a>
                </p>
            </div>
        <?php endif?>
        <div class="search right">
            <form class="search-form clearfix" method="get" action="<?php bloginfo('home');?>/">
                <label for="top-search"><i class="hatsumi">&#xe618;</i></label>
                <input class="search-text" name="s" id="top-search" type="text" placeholder="搜索..." />
            </form>
        </div>
</header>
 <div class="mob-avatar">
        <?php global $current_user; get_currentuserinfo(); echo um_get_avatar( $current_user->ID , '72' , um_get_avatar_type($current_user->ID)); ?>
        <p class="mob-log-info">
        <span><?php global $current_user; get_currentuserinfo(); echo $current_user->display_name;?></span><a href="<?php echo wp_logout_url(home_url(add_query_arg(array()))); ?>" class="logout">退出</a>
        </p>
        <div class="mob-menu">
        	<a href="<?php echo home_url('/author/'.$current_user->ID.'/?tab=collect');?>">
            	<li><i class="hatsumi">&#xe62b;</i><p>文章收藏</p></li>
             	</a>
            <a href="<?php echo home_url('/author/'.$current_user->ID.'/?tab=comment');?>">
                <li><i class="hatsumi">&#xe62c;</i><p>我的评论</p></li>
                </a>
            <a href="<?php echo home_url('/author/'.$current_user->ID.'/?tab=profile');?>">
                <li><i class="hatsumi">&#xe62d;</i><p>修改资料</p></li>
                </a>
        </div>
        <div class="mob-menu-fo">
        	<?php wp_nav_menu(array(
            'theme_location' => 'menu-foo-mob',
            'container' => 'nav',
            'container_id' => 'menu-foo'
        )); ?>
        </div>
</div>
<div class="search-overlay">
</div>