<?php
/**
 * @package Chow
 */
$is_recipe = false;
$ingredients = get_post_meta($post->ID, 'cookingpressingridients', true);
if(!empty($ingredients)) {
	$is_recipe = true;
}
?>

<!-- Recipe #1 -->
<div id="post-<?php the_ID(); ?>" <?php post_class('four recipe-box columns'); ?>>

	<!-- Thumbnail -->
	<div class="thumbnail-holder">
		<a href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_post_thumbnail(); ?>
			<div class="hover-cover"></div>
			<?php 
			$ingredients = get_post_meta($post->ID, 'cookingpressingridients', true);
			if(!empty($ingredients)) { ?>
			<div class="hover-icon"><?php _e('View Recipe','chow'); ?></div>
			<?php } else { ?>
			<div class="hover-icon"><?php _e('View Post','chow'); ?></div>
			<?php }?>
		</a>
	</div>

	<!-- Content -->
	<div class="recipe-box-content <?php $ratings=check_post_rating(); if($ratings>0) { echo 'has-stars';} else { echo 'no-stars'; } ?>">
	
		<h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>

		<?php do_action('foodiepress-rating'); ?>
	
		<?php if($is_recipe) { do_action('grid-post-meta'); } else { do_action('no-recipe-post-meta'); } ?>


		<div class="clearfix"></div>
	</div>
</div>
