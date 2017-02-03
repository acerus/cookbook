<?php
/**
 * @package Chow
 */



$format = get_post_format();
if( false === $format )  $format = 'standard';
$header_flag = false;
$header_bg_id = get_post_meta($post->ID, 'pp_header_bg', TRUE); 
$header_background   = wp_get_attachment_image_src( $header_bg_id, 'header-bg' );

if( has_shortcode( $post->post_content, 'purerecipe') || has_shortcode( $post->post_content, 'foodiepress') || is_singular( 'recipe' )) {
	$post_class = "has-recipe";
	$is_recipe = true;
} else {
	$post_class = "no-recipe";
	$is_recipe = false;
}

$ingredients = get_post_meta($post->ID, 'cookingpressingridients', true);
if(!empty($ingredients)) {
	$post_class = "has-recipe";
	$is_recipe = true;
}
?>

<?php if(!empty($header_background[0])) { $header_flag = true; ?>
	<!-- Recipe Background -->
	<div class="recipeBackground">
		<img src="<?php echo esc_attr($header_background[0]); ?>" alt="" />
	</div>
<?php } else {
	$global_header_status = ot_get_option('pp_header_bg_status','off');
	if($global_header_status == 'on') {
		$global_header =  ot_get_option('pp_header_bg');
		if(!empty($global_header)) {  $header_flag = true;?>
			<!-- Recipe Background -->
			<div class="recipeBackground">
				<img src="<?php echo esc_attr($global_header); ?>" alt="" />
			</div>
		<?php }
	}
} 

if($header_flag == false) { ?>
	<section class="<?php $ratings = check_post_rating();  if($ratings>0) { echo 'no-recipe-bg';}  ?>" id="titlebar">
	    <!-- Container -->
	    <div class="container ">

	        <div class="eight columns">
	            <h1><?php the_title( ); ?></h1>
	            <?php do_action('foodiepress_post_header'); ?>
	        </div>
	        <div class="eight columns">
	                <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
	        </div>

	    </div>
	    <!-- Container / End -->
	</section>
<?php } ?>
<!-- Content
================================================== -->
<div class="container" >

<?php $layout = get_post_meta($post->ID, 'pp_sidebar_layout', true); if($layout == 'left-sidebar') { $layout_class = "left-sidebar-class"; } else { $layout_class = "";} ?>
<!-- Recipe -->
<div class="twelve columns  <?php if($header_flag == false) { echo 'no-recipe-background'; } ?> <?php echo esc_attr($layout_class); ?>">
	<div class="alignment">
		<article <?php post_class($post_class); ?> id="post-<?php the_ID(); ?>" >
			<!-- Header -->
			<?php if($header_flag == true) { ?>
			<section class="recipe-header">
				<div class="title-alignment">
					<h1><?php the_title( ); ?></h1>
					<?php do_action('foodiepress_post_header'); ?>
				</div>
			</section>
			<?php } ?>

			<?php
				if($format == 'gallery') { ?>
				<?php
					$gallery = get_post_meta($post->ID, '_format_gallery', TRUE);
					preg_match( '/ids=\'(.*?)\'/', $gallery, $matches );

					  if ( isset( $matches[1] ) ) {
					    // Found the IDs in the shortcode
					    $ids = explode( ',', $matches[1] );
					  } else {
					    // The string is only IDs
					    $ids = ! empty( $gallery ) && $gallery != '' ? explode( ',', $gallery ) : array();
					  }
				  	echo '<div class="recipeSlider rsDefault">';
					foreach ($ids as $imageid) { ?>
					      <?php
					      		$image_link = wp_get_attachment_url( $imageid );
								if ( ! $image_link )
								continue;
								$image          = wp_get_attachment_image_src( $imageid, 'slider');
								$imageFull          = wp_get_attachment_image_src( $imageid, 'full');
								$imageRSthumb   = wp_get_attachment_image_src( $imageid, 'slider-small' );
								$image_title    = get_the_title( $imageid ); ?>
					        <a class="print-only <?php if(count($ids)>0) { echo "mfp-gallery"; } else { echo "mfp-image"; } ?>" href="<?php echo esc_attr($imageFull[0]); ?>"><img class="rsImg" src="<?php echo esc_attr($image[0]); ?>" alt="<?php esc_attr(the_title()); ?>" data-rsTmb="<?php echo esc_attr($imageRSthumb[0]); ?>"  /></a>
					<?php } ?>
					</div>
					<?php
				} else if ($format == 'video') { ?>
					<div class="embed">
					    <?php
					      $video = get_post_meta($post->ID, '_format_video_embed', true);
					      if(wp_oembed_get($video)) { echo wp_oembed_get($video); } else { echo $video;}
					    ?>
				  	</div>
				<?php } else {
					if(has_post_thumbnail()) {
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'slider');
						$imageFull = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
						?>
						<div class="recipeSlider recipeSlider-single rsDefault">
							<a class="print-only mfp-image" href="<?php echo esc_attr($imageFull[0]); ?>"><img class="rsImg" src="<?php echo esc_attr($thumb[0]); ?>"  alt="<?php esc_attr(the_title()); ?>" data-rsTmb="<?php echo esc_attr($thumb[0]); ?>"  /></a>
						</div>
					<?php
					}
				} // eof gallery
			?>

			<div class="post-content <?php if(ot_get_option('pp_print_content','off') == 'on') { echo "print-only"; }?>">
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'chow' ),
						'after'  => '</div>',
					) );
				?>
				<div class="clearfix"></div>
				<span class="divider"></span>
				<!-- Share Post -->
				<?php 
				$share_options = ot_get_option('pp_post_share');
				if(!empty($share_options)) {
						$id = $post->ID;
					    $title = urlencode($post->post_title);
					    $url =  urlencode( get_permalink($id) );
					    $summary = urlencode(string_limit_words($post->post_excerpt,20));
					    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium' );
					    $imageurl = urlencode($thumb[0]);
 				?>
					<ul class="share-post">
						<?php if (in_array("facebook", $share_options)) { ?><li><?php echo '<a target="_blank" class="facebook-share" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '">Facebook</a>'; ?></li><?php } ?>
						<?php if (in_array("twitter", $share_options)) { ?><li><?php echo '<a target="_blank" class="twitter-share" href="https://twitter.com/share?url=' . $url . '&amp;text=' . esc_attr($summary ). '" title="' . __( 'Twitter', 'chow' ) . '">Twitter</a>'; ?></li><?php } ?>
						<?php if (in_array("google-plus", $share_options)) { ?><li><?php echo '<a target="_blank" class="google-plus-share" href="https://plus.google.com/share?url=' . $url . '&amp;title="' . esc_attr($title) . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'>Google Plus</a>'; ?></li><?php } ?>
						<?php if (in_array("pinterest", $share_options)) { ?><li><?php echo '<a target="_blank"  class="pinterest-share" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . esc_attr($summary) . '&media=' . esc_attr($imageurl) . '" onclick="window.open(this.href); return false;">Pinterest</a>'; ?></li><?php } ?>

						<!-- <li><a href="#add-review" class="rate-recipe">Add Review</a></li> -->
					</ul>
				<?php } ?>
				<?php
					if(ot_get_option('pp_add_to_fav_status','on') == 'on' && $is_recipe) {
						echo do_shortcode('[foodiepress-addtofav]');
					}
				  	?>
				<div class="clearfix"></div><div class="post-meta"><?php chow_posted_on(); ?></div>
				


				<div class="clearfix"></div>
				
			</div>

			<!-- Headline -->
			<?php chow_related_posts($post); ?>

			<div class="clearfix"></div>

			<div class="margin-top-15"></div>

			<?php chow_post_nav(); ?>
			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				
			?>
		</article>
	</div> <!-- eof alignment -->
</div> <!-- eof twelve columns -->

