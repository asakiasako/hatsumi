<?php 
/* Template Name: 留言 */
get_header(); 
?>
<div id="main" class="fullwidth">
    <div id="content">
        <div id="container">
			<?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'loop/page/content-guestbook' ) ?>
			<?php endwhile; ?>
        </div>
    </div>
</div>
<?php get_footer();?>