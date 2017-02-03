<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Chow
 */

get_header(); ?>

<!-- Content
================================================== -->
<section id="titlebar">
    <!-- Container -->
    <div class="container">

        <div class="eight columns">
            <h2><?php the_archive_title(); ?></h2>
        </div>
        <div class="eight columns">
                <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
        </div>
    </div>
    <!-- Container / End -->
</section>
<div class="container">
<?php
$layout = ot_get_option('pp_blog_layout');
    if($layout == 'left-sidebar-grid' || $layout == 'right-sidebar-grid' || $layout == 'masonry') {
        get_template_part( 'loop','grid' );
    } else {
        get_template_part( 'loop','list' );
    }
?>


<?php if($layout != 'masonry') { get_sidebar(); } ?>
<?php get_footer(); ?>

