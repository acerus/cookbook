<?php
/**
 * @package Chow
 */
$is_recipe = false;
$ingredients = get_post_meta($post->ID, 'cookingpressingridients', true);
if(!empty($ingredients)) {
	$is_recipe = true;
}

$thumb = false;
if ( has_post_thumbnail() ) { $thumb = true; }
?>

<!-- Recipe #1 -->
<div id="post-<?php the_ID(); ?>"  <?php post_class('four recipe-box columns'); ?>>

	<?php if ( $thumb ) { ?><!-- Thumbnail -->
	<div class="thumbnail-holder">
		<a href="<?php echo esc_url(get_permalink()); ?>">
			<?php the_post_thumbnail('thumb-list'); ?>
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
	<?php } ?>
	<!-- Content -->
	<div class="recipe-box-content <?php if($thumb == false) { echo "no-thumb";}?>">
		<h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>
		<p>
			<?php 
			if(ot_get_option('pp_list_view_summary','off') == 'on'){
				$excerpt = get_post_meta($post->ID, 'cookingpresssummary', TRUE); 
				if(empty($excerpt)) {
					$excerpt = get_the_excerpt();	
				}
			} else {
				$excerpt = get_the_excerpt();
			}
	        echo string_limit_words($excerpt,25).'...'
			?>
		</p>
		<?php do_action('foodiepress-rating'); ?>
		<div class="meta-alignment">
			<?php if($is_recipe) { do_action('list-post-meta'); } else { do_action('no-recipe-post-meta'); } ?>
		</div>

		<div class="clearfix"></div>
	</div>
</div>
