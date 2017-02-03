<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Chow
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>
<?php 
$style = get_theme_mod( 'chow_layout_style', 'wide' );
if(is_page_template('template-boxed.php')) { $style = "boxed"; } ?>
<body <?php body_class($style); ?>>

<!-- Wrapper -->
<div id="wrapper">

<!-- Header
================================================== -->
<header id="header">
<?php
    $logo_area_width = ot_get_option('pp_logo_area_width',3);
    $center_header = ot_get_option('pp_center_header','off');
    $menu_area_width = 16 - $logo_area_width;
?>
    <!-- Container -->
    <div class="container <?php if( $logo_area_width == 16 && $center_header == 'on' ) echo " centered-header"; ?>">
        
        <!-- Logo / Mobile Menu -->
        <div class="<?php echo chow_number_to_width($logo_area_width); ?> columns">
            <div id="logo">
                <?php
                $logo = ot_get_option( 'pp_logo_upload' );
                if($logo) {
                    if(is_front_page()){ ?>
                    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(bloginfo('name')); ?>"/></a></h1>
                    <?php } else { ?>
                    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(bloginfo('name')); ?>"/></a></h2>
                    <?php }
                } else {
                    if(is_front_page()) { ?>
                    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php } else { ?>
                    <h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
                    <?php }
                }
                ?>
                <?php if(get_theme_mod('chow_tagline_switch','hide') == 'show') { ?><div id="blogdesc"><?php bloginfo( 'description' ); ?></div><?php } ?>
            </div>
        </div>


        <!-- Navigation
        ================================================== -->
        <?php 
        if ($menu_area_width == 0) {?>
            <div class=" columns navigation sixteen">
        <?php } else { ?>
            <div class=" columns navigation <?php if($style == 'boxed' ) { echo 'sixteen'; } else { echo chow_number_to_width($menu_area_width); } ?>">
        <?php } ?>
                <nav id="navigation" class="<?php if( $style == 'boxed') { echo 'alternative'; } ?> menu nav-collapse">
                    <?php wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'container' => false
                            ));
                    ?>
                </nav>

            </div>
        
    </div>

<!-- Container / End -->
</header>
