<?php

// action - CREATE new slider
if ( array_key_exists( 'action', $_GET ) && 'save_slides' == $_GET['action'] && array_key_exists( '_wpnonce', $_REQUEST ) ) {

    if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'postssel' ) ) {

        if (  ! empty( $_POST['slider_name'] ) ) {
            // add the new slide group
            //check if slider with that name exists
            //
            $dis_categories = (isset($_POST['dis_categories'])) ? $_POST['dis_categories'] : '' ;
            $dis_servings   = (isset($_POST['dis_servings'])) ? $_POST['dis_servings'] : '' ;
            $dis_time       = (isset($_POST['dis_time'])) ? $_POST['dis_time'] : '' ;
            $dis_author       = (isset($_POST['dis_author'])) ? $_POST['dis_author'] : '' ;
            $slides = array(
                'posts' => $_POST['posts'],
                'slidername' => $_POST['slider_name'],
                'slidertype' => 'postssel',
                'autoPlay' => $_POST['autoPlay'],
                'style' => $_POST['style'],
                'delay' => $_POST['delay'],
                'loop' => $_POST['loop'],
                'dis_categories' => $dis_categories,
                'dis_servings' => $dis_servings,
                'dis_time' => $dis_time,
                'dis_author' => $dis_author,
                'numImagesToPreload' => $_POST['numImagesToPreload'],
                'fadeinLoadedSlide' => $_POST['fadeinLoadedSlide'],
                'imageScaleMode' => $_POST['imageScaleMode'],
                'transitionType' => $_POST['transitionType'],
/*                'transitionSpeed' => $_POST['transitionSpeed'],*/
                
                );
            update_option( 'cp_slider_'.$_POST['slider_name'], $slides );
        }
    }
} ?>

<?php
    $current_slider = get_option( 'cp_slider_'.$_GET['slider'], $default = false );

    if($current_slider) {
        $selectedposts = $current_slider['posts'];
    } else {
        $selectedposts =  array();
    }
?>

<form  name="new-slider-form" id="new-slider-form" method="post" action="admin.php?page=cp-slider&slider=<?php echo esc_attr($_GET['slider']); ?>&action=save_slides">
    <p>This slider will displayed featured images from selected posts:</p>
<table class="form-table">
    <tr valign="top">
        <select id="cpsliderselect" multiple="multiple" name="posts[]" title="Click to select posts">
            <?php
            $args = array(
                'post_type' =>  array('recipe','post','page'),
                'numberposts' => -1,
                'meta_key'    => '_thumbnail_id',
                'post__not_in' => $selectedposts
            );
            $posts = get_posts($args);
            foreach ($selectedposts as $key) {
                $selpost = get_post($key)?>
                <option selected="selected" value="<?php echo esc_attr($selpost->ID); ?>"><?php echo $selpost->post_title; ?></option>
            <?php }
            foreach( $posts as $post ) : setup_postdata($post); ?>
                <option <?php if (in_array( $post->ID, $selectedposts)) { echo "selected "; } ?> value="<?php echo esc_attr($post->ID); ?>"><?php echo $post->post_title; ?></option>
        <?php endforeach; ?>
        </select>
    </tr>
    <tr valign="top">
        <?php submit_button(); ?>
    </tr>

<?php wp_nonce_field( 'postssel' ); ?>
<input type="hidden" name="slider_name" value="<?php echo esc_attr($_GET['slider']); ?>">

    </table>
<hr>
        <h3>Slider content settings</h3>
        <table class="form-table">
        <tr valign="top">
            <th scope="row">Slider's elements to hide</th>
            <td>
                <input type="checkbox" name="dis_categories" value="1" <?php checked( $current_slider['dis_categories'], 1 ); ?>>Categories<br>
                <input type="checkbox" name="dis_servings" value="1" <?php checked( $current_slider['dis_servings'], 1 ); ?>>Servings<br>
                <input type="checkbox" name="dis_time" value="1" <?php checked( $current_slider['dis_time'], 1 ); ?>>Time Needed<br>
                <input type="checkbox" name="dis_author" value="1" <?php checked( $current_slider['dis_author'], 1 ); ?>>Author<br>
            </td>
        </tr>
        <tr><td colspan="2"><p> - those elements are displayed by default only for <strong>Style 1</strong></p></td></tr>
        </table>

<hr>
        <h3>Slider visual settings</h3>
        <table class="form-table">
        <tr valign="top">
            <th scope="row">Slider style</th>
            <td>
                <select name="style" id="style">
                    <option <?php selected( $current_slider['style'], 'below' ); ?> value="below">Style 1 - Navigation below</option>
                    <option <?php selected( $current_slider['style'], 'right' ); ?> value="right">Style 2 - Navigation on the right</option>
                </select>
            </td>
        </tr>
         <tr valign="top">
            <th scope="row">Auto Play</th>
            <td>
                <select name="autoPlay" id="autoPlay">
                    <option <?php selected( $current_slider['autoPlay'], 'true' ); ?> value="true">True</option>
                    <option <?php selected( $current_slider['autoPlay'], 'false' ); ?> value="false">False</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Slider autoplay delay between slides, in ms.</th>
            <td>
                <input type="text" name="delay" value="<?php if( !empty($current_slider['delay'])) {
                    echo $current_slider['delay'];
                } else {
                    echo '3000';
                } ?>">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Loop slides</th>
            <td>
                <select name="loop" id="loop">
                    <option <?php selected( $current_slider['loop'], 'true' ); ?> value="true">True</option>
                    <option <?php selected( $current_slider['loop'], 'false' ); ?> value="false">False</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Number of slides(images) to preload</th>
            <td>
                <input type="text" name="numImagesToPreload" value="<?php if( !empty($current_slider['numImagesToPreload'])) {
                    echo $current_slider['numImagesToPreload'];
                } else {
                    echo '3';
                } ?>">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Fades in slide after it's loaded</th>
            <td>
                <select name="fadeinLoadedSlide" id="fadeinLoadedSlide">
                    <option <?php selected( $current_slider['fadeinLoadedSlide'], 'true' ); ?> value="true">True</option>
                    <option <?php selected( $current_slider['fadeinLoadedSlide'], 'false' ); ?> value="false">False</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Scale mode for images. "fill", "fit", "fit-if-smaller" or "none".</th>
            <td>
                <select name="imageScaleMode" id="imageScaleMode">
                    <option <?php selected( $current_slider['imageScaleMode'], 'fill' ); ?> value="fill">fill</option>
                    <option <?php selected( $current_slider['imageScaleMode'], 'fit-if-smaller' ); ?> value="fit-if-smaller">fit-if-smaller</option>
                    <option <?php selected( $current_slider['imageScaleMode'], 'fit' ); ?> value="fit">fit</option>
                    <option <?php selected( $current_slider['imageScaleMode'], 'none' ); ?> value="none">none</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">How slides change.</th>
            <td>
                <select name="transitionType" id="transitionType">
                    <option <?php selected( $current_slider['transitionType'], 'move' ); ?> value="move">move</option>
                    <option <?php selected( $current_slider['transitionType'], 'fade' ); ?> value="fade">fade</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th></th>
            <td>
                <?php submit_button(); ?>
            </td>
        </tr>

    </table>
</form>
