<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Chow
 */
?>


</div>
<!-- Wrapper / End -->


<!-- Footer
================================================== -->
<div id="footer">

    <!-- Container -->
    <div class="container">
        <?php $footer_layout = ot_get_option('pp_footer_widgets','5,3,3,5');
        $footer_layout_array = explode(',', $footer_layout); 
        $x = 0;
        foreach ($footer_layout_array as $value) {
            $x++;
             ?>
             <div class="<?php echo chow_number_to_width($value); ?> columns">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer'.$x)) : endif; ?>
            </div>
        <?php } ?>
    </div>
    <!-- Container / End -->

</div>
<!-- Footer / End -->

<!-- Footer Bottom / Start -->
<div id="footer-bottom">

    <!-- Container -->
    <div class="container">

        <div class="eight columns">
        <?php $copyrights = ot_get_option('pp_copyrights' );
        if (function_exists('icl_register_string')) {
            icl_register_string('Copyrights in footer','copyfooter', $copyrights);
            echo icl_t('Copyrights in footer','copyfooter', $copyrights);
        } else {
            echo wp_kses($copyrights,array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),));
        } ?></div>

    </div>
    <!-- Container / End -->

</div>
<!-- Footer Bottom / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>

<?php wp_footer(); ?>

</body>
</html>
