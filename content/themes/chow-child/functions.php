<?php 
add_action( 'wp_enqueue_scripts', 'chow_enqueue_styles' );
function chow_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

?>