<?php

//文章浏览记录
class hatsumi_sidehis extends WP_Widget {
    function hatsumi_sidehis() {
        $widget_ops = array('description' => ' [Y-M] 历史浏览记录-边栏');
        $this->WP_Widget('hatsumi_sidehis', ' 边栏-历史浏览记录', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
		?>
			<section id="sidhis">
				<h3>历史浏览记录</h3>
				<div id="view-history"></div>
			</section>
		<?php	
    }
}

add_action('widgets_init', 'hatsumi_sidehis_init');
function hatsumi_sidehis_init() {
    register_widget('hatsumi_sidehis');
}

//随机文章－边栏
class hatsumi_siderand extends WP_Widget {
    function hatsumi_siderand() {
        $widget_ops = array('description' => ' [Y-M] 随机文章');
        $this->WP_Widget('hatsumi_siderand', ' 边栏-随机文章', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
		$limit = $limit ? $limit : 5;
		?>
			<section id="randomposts" class="s-thumb">
				<h3>随机文章</h3>
				<ul class="romposts">
					<?php 
					$args = array(
						'post_type' => 'post',
						'tax_query' => array( array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => 'post-format-status',
							'operator' => 'NOT IN',
						) ),
						'ignore_sticky_posts' => 1,
						'orderby' => 'rand',
						'showposts' => $limit,
						'post_password' => '', 
						'post_status' => 'publish', // 只选公开的文章. 
					);
					$query = new WP_Query( $args );
					$side_num = 1; while($query->have_posts()) : $query->the_post();?> 
                        <li>
                        <div class="side-thumb"><img src="<?php echo hatsumi_thumbnail(80,80)?>"/></div>
                        <p class="side-art"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                        <p class="side-meta"><?php $ymcat = get_the_category();echo $ymcat[0]->cat_name;?></p>
                        </li>
					<?php endwhile; wp_reset_postdata();?>
				</ul>
			</section>
		<?php	
    }
	
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => '', 'checked' => ''));
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
		?>
	        <p><label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
	        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
		<?php
    }
}

add_action('widgets_init', 'hatsumi_siderand_init');
function hatsumi_siderand_init() {
    register_widget('hatsumi_siderand');
}

//最近文章－边栏
class hatsumi_siderecent extends WP_Widget {
    function hatsumi_siderecent() {
        $widget_ops = array('description' => ' [Y-M] 最近文章');
        $this->WP_Widget('hatsumi_siderecent', ' 边栏-最近文章', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
        ?>
            <section id="randomposts" class="s-thumb">
                <h3>最近文章</h3>
                <ul class="romposts">
                    <?php 
						$args = array(
							'post_type' => 'post',
							'tax_query' => array( array(
								'taxonomy' => 'post_format',
								'field'    => 'slug',
								'terms'    => 'post-format-status',
								'operator' => 'NOT IN',
							) ),
							'ignore_sticky_posts' => 1,
							'orderby' => 'date',
							'showposts' => $limit,
							'post_password' => '', 
							'post_status' => 'publish', // 只选公开的文章. 
						);
						$query = new WP_Query( $args );
						$side_num = 1; 
						while($query->have_posts()) : $query->the_post();?> 
                            <li>
                            	<div class="side-thumb"><img src="<?php echo hatsumi_thumbnail(80,80)?>"/></div>
                            	<p class="side-art"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                            	<p class="side-meta">发表于<?php the_time(); ?></p>
                            </li>
                    <?php 
						endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php   
    }
    
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => '', 'checked' => ''));
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
        ?>
            <p><label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
            <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}

add_action('widgets_init', 'hatsumi_siderecent_init');
function hatsumi_siderecent_init() {
    register_widget('hatsumi_siderecent');
}

//热门文章－边栏
class hatsumi_sidehot extends WP_Widget {
    function hatsumi_sidehot() {
        $widget_ops = array('description' => ' [Y-M] 热门文章：热度由评论数、点赞数、访问数加权计算');
        $this->WP_Widget('hatsumi_sidehot', ' 边栏-热门文章', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
        $checked = ($instance['checked']==NULL || $instance['checked']== 1 ) ? true : false;
        ?>
        <section id="popularposts" class="s-thumb">
            <h3>热门文章</h3>
            <ul class="widgetcon">
                <?php 
				$args = array(
						'post_type' => 'post',
						'tax_query' => array( array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => 'post-format-status',
							'operator' => 'NOT IN',
						) ),
						'ignore_sticky_posts' => 1,
						'showposts' => $limit,
						'post_password' => '', 
						'post_status' => 'publish', // 只选公开的文章. 
						'meta_key' => 'um_post_hot',
						'orderby' => 'meta_value_num', // 依热度排序. 
					);
					$query = new WP_Query( $args );
					$side_num = 1;
                while( $query->have_posts() ) { $query->the_post(); ?> 
                <li>
                	<div class="side-thumb"><img src="<?php echo hatsumi_thumbnail(80,80)?>"/></div>
                        <p class="side-art"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
                        <p class="side-meta"><?php hatsumi_count_comments( false, true);?>个评论&nbsp;&middot;&nbsp;<?php $umlikes=get_post_meta(get_the_ID(),'um_post_likes',true); if(empty($umlikes)) $umlikes=0;echo $umlikes;?>个赞</p>
                    </li> 
                <?php } wp_reset_postdata();?> 
                </ul> 
        </section>
        <?php   
    }
    
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => '', 'checked' => ''));
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
        ?>
            <p><label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
            <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}

add_action('widgets_init', 'hatsumi_sidehot_init');
function hatsumi_sidehot_init() {
    register_widget('hatsumi_sidehot');
}

//最新评论－边栏
class hatsumi_sidecomment extends WP_Widget {
    function hatsumi_sidecomment() {
        $widget_ops = array('description' => ' [Y-M] 最新评论');
        $this->WP_Widget('hatsumi_sidecomment', ' 边栏-最新评论', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
		$limit = $limit ? $limit : 5;
		?>
			<section id="sidrctcom">
				<h3>最近评论</h3>
				<ul class="sidrctcomul">
					<?php hatsumi_recent_comments($limit);?>
				</ul>
			</section>
		<?php	
    }
	
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => '', 'checked' => ''));
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 5;
		?>
	        <p><label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
	        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
		<?php
    }
}

add_action('widgets_init', 'hatsumi_sidecomment_init');
function mirana_widget_5_init() {
    register_widget('hatsumi_sidecomment');
}

//网站标签－边栏
class hatsumi_sidetags extends WP_Widget {
    function hatsumi_sidetags() {
        $widget_ops = array('description' => '[Y-M] 网站标签');
        $this->WP_Widget('hatsumi_sidetags', '边栏-网站标签', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
        $limit = $limit ? $limit : 20;      
?>
    <section id="tags">
        <h3>网站标签</h3>
        <div class="sidtags clearfix">
            <?php wp_tag_cloud( array('unit' => 'px', 'smallest' => 13, 'largest' => 13, 'number' => $limit, 'format' => 'flat', 'orderby' => 'count', 'order' => 'DESC' )); ?>
        </div>
    </section>
<?php   
    }
    
    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => ''));
        $limit = strip_tags($instance['limit']);        
?>        
    <p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('标签数量：');?><input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
    <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php
    }
}
add_action('widgets_init', 'hatsumi_sidetags_init');
function hatsumi_sidetags_init() {
    register_widget('hatsumi_sidetags');
}




