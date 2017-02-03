<?php
/**
 * The template for displaying search forms in Nevia
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?>
<aside class="search">
    <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
        <button><i class="fa fa-search"></i></button>
        <input class="search-field" type="text" name="s" placeholder="<?php _e('Search','chow') ?>" value=""/>
    </form>
</aside>
<div class="clearfix"></div>



