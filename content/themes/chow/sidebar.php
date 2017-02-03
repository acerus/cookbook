<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Chow
 */


?>

<div class="four columns sidebar">
	<?php
    if(is_singular()){
        $sidebar = get_post_meta($post->ID, "pp_sidebar_set", $single = true);
    } else {
        $sidebar = false;
    }
    if ($sidebar) {
        if ( ! dynamic_sidebar( $sidebar ) ) :?>

        <aside id="archives" class="widget">
            <h1 class="widget-title"><?php _e( 'Archives', 'chow' ); ?></h1>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>

        <aside id="meta" class="widget">
            <h1 class="widget-title"><?php _e( 'Meta', 'chow' ); ?></h1>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>
        <?php endif;
    }// end sidebar widget area ?>

    <?php
    if (!$sidebar) {
        if (!dynamic_sidebar('sidebar-1')) : ?>
        <aside id="archives" class="widget">
            <h1 class="widget-title"><?php _e( 'Archives', 'chow' ); ?></h1>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>

        <aside id="meta" class="widget">
            <h1 class="widget-title"><?php _e( 'Meta', 'chow' ); ?></h1>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>
        <?php endif;
    } // end primary widget area
    ?>
</div><!-- #secondary -->
