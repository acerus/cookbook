<?php
/**
 * The template for displaying all single posts.
 *
 * @package Chow
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php

	 get_template_part( 'content', 'single' ); 
	?>
<?php endwhile; // end of the loop. ?>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
