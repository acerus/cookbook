<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

<!-- Titlebar
================================================== -->

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


<?php $shop_layout = ot_get_option('pp_shop_layout','left-sidebar'); ?>
<div class="container <?php echo $shop_layout ?>-class">
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		 ?>

		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */

			if($shop_layout == 'full-width') {
				do_action( 'woocommerce_before_shop_loop' );
			?>
				<div class="sixteen columns full-width">
			<?php } else { ?>
				<div class="twelve columns <?php echo $shop_layout ?>-class">
			<?php do_action( 'woocommerce_before_shop_loop' );
				}


			?>
				<div class="clearfix"></div>
			<?php if($shop_layout == 'full-width') { ?> </div> <?php } ?>
			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
		<?php if($shop_layout != 'full-width') { ?></div> <!-- eof twelve --><?php } ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
		if($shop_layout == 'right-sidebar' || $shop_layout == 'left-sidebar') { do_action( 'woocommerce_sidebar' ); }
	?>

	</div> <!-- eof contaienr -->
<?php get_footer( 'shop' ); ?>
