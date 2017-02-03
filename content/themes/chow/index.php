<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Chow
 */
get_header(); ?>
<?php get_template_part( 'slider' ) ?>
<!-- Content
================================================== -->
<?php if(ot_get_option('pp_home_adv_search','off') === 'on') { get_template_part( 'searchformadv' ); } ?>
<div class="container">
<?php
$layout = ot_get_option('pp_recipes_layout','right-sidebar-grid');
if($layout == 'left-sidebar-grid' || $layout == 'right-sidebar-grid' || $layout == 'masonry') {
    get_template_part( 'loop','grid' );
} else {
    get_template_part( 'loop','list' );
}
?>


<?php if($layout != 'masonry') { get_sidebar(); } ?>
</div>
<?php get_footer(); ?>
