<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

<section id="titlebar">
	<div class="container">
	    <div class="eight columns">
	        <h2><?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?><?php woocommerce_page_title(); ?><?php endif; ?></h2>
	    </div>
	    <div class="eight columns">
	    	<?php if(ot_get_option('pp_breadcrumbs','on') == 'on') { do_action( 'chow_woocommerce_breadcrumb' ); }?>
	    </div>
	</div>
</section>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>



<?php get_footer( 'shop' ); ?>