<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;
$shop_layout = ot_get_option('pp_woo_layout','right-sidebar');

// Extra post classes
$classes = array();
if($shop_layout != 'full-width') {
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'alpha first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'omega last';
}

$shop_columns = ot_get_option('pp_woocolumns','four');

if($shop_layout == 'full-width') {
	$classes[] = 'full-shop columns masonry-shop-item';
} else {
	$classes[] = 'shop columns masonry-shop-item';
}
if(is_shop() || is_product_category()){
	$classes[] = $shop_columns;
} else {
	$classes[] = 'four';
}

?>
<div <?php post_class( $classes ); ?>>
	<?php do_action( 'woocommerce_before_shop_loop_item' );
	$hover = get_post_meta($post->ID, 'pp_featured_hover', TRUE); ?>
	<figure class="product">
		<div class="mediaholder">
			<a href="<?php the_permalink(); ?>"  title="<?php the_title(); ?>">
				<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( get_the_ID(), 'shop_catalog' );
				} elseif ( wc_placeholder_img_src() ) {
					echo wc_placeholder_img('shop_catalog');
				}

				?>
			</a>
				<?php
				global $product;

				$class = $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ' : '';
                $class .= $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart ' : '';
				echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="product-button  %s"><i class="fa fa-shopping-cart"></i></a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $quantity ) ? $quantity : 1 ),
						esc_attr( $product->id ),
						esc_attr( $product->get_sku() ),
						esc_attr( isset( $class ) ? $class : '' ),
						esc_html( $product->add_to_cart_text() )
					),
				$product );
			?>

		</div>
		<a href="<?php the_permalink(); ?>">

		<section>
			<span class="product-category">
				<?php
				$product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );
				if ( $product_cats && ! is_wp_error ( $product_cats ) ){
					$single_cat = array_shift( $product_cats );
					echo $single_cat->name;
				} ?>
			</span>
			<h5><?php the_title(); ?></h5>
			<span class="product-price"><?php  wc_get_template( 'loop/price.php' ); ?></span>
		</section>


		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

		</a>

		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</figure>
</div>