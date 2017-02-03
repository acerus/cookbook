<?php

// action - CREATE new slider
if ( array_key_exists( 'action', $_GET ) && 'save_slides' == $_GET['action'] && array_key_exists( '_wpnonce', $_REQUEST ) ) {

    if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'posts' ) ) {

        if (  ! empty( $_POST['slider_name'] ) ) {

            if(isset($_POST['cats']) && !empty($_POST['cats'])) { $cats = $_POST['cats'];} else { $cats = '';}
            if(isset($_POST['tags']) && !empty($_POST['tags'])) { $tags = $_POST['tags'];} else { $tags = '';}
            // add the new slide group
            //check if slider with that name exists
            $dis_categories = (isset($_POST['dis_categories'])) ? $_POST['dis_categories'] : '' ;
            $dis_servings   = (isset($_POST['dis_servings'])) ? $_POST['dis_servings'] : '' ;
            $dis_time       = (isset($_POST['dis_time'])) ? $_POST['dis_time'] : '' ;
            $dis_author       = (isset($_POST['dis_author'])) ? $_POST['dis_author'] : '' ;
            $slidersettings = array(
                'slidername'            => $_POST['slider_name'],
                'slidertype'            => 'posts',
                'content_type'          => $_POST['content_type'],
                'posts_type'            => $_POST['posts_type'],
                'posts_order'           => $_POST['posts_order'],
                'posts_number'          => $_POST['posts_number'],
                'cats'                  =>  $cats,
                'tags'                  => $tags,
                'autoPlay'              => $_POST['autoPlay'],
                'style'                 => $_POST['style'],
                'delay'                 => $_POST['delay'],
                'loop'                  => $_POST['loop'],
                'dis_categories'        => $dis_categories,
                'dis_servings'          => $dis_servings,
                'dis_time'              => $dis_time,
                'dis_author'            => $dis_author,
                'numImagesToPreload'    => $_POST['numImagesToPreload'],
                'fadeinLoadedSlide'     => $_POST['fadeinLoadedSlide'],
                'imageScaleMode'        => $_POST['imageScaleMode'],
                'transitionType'        => $_POST['transitionType'],
                );
            update_option( 'cp_slider_'.$_POST['slider_name'], $slidersettings );
        }
    }
}

$current_slider = get_option( 'cp_slider_'.$_GET['slider']);
if($current_slider) {
    $selectedcats = $current_slider['cats'];
    $selectedtags = $current_slider['tags'];
} else {
    $selectedcats =  array();
    $selectedtags =  array();
}
?>

<form  name="new-slider-form" id="new-slider-form" method="post" action="admin.php?page=cp-slider&slider=<?php echo esc_attr($_GET['slider']); ?>&action=save_slides">
    <p>This slider will displayed featured images from selected posts:</p>
    <h3>Slider content settings</h3>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Posts type:</th>
            <td>
                <select name="content_type" id="content_type">
                    <option <?php selected( $current_slider['content_type'], 'posts' ); ?> value="posts">Posts</option>
                    <option <?php selected( $current_slider['content_type'], 'recipes' ); ?> value="recipes">Recipes</option>
                    <option <?php selected( $current_slider['content_type'], 'both' ); ?> value="both">Both</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Posts order:</th>
            <td>
                <select name="posts_type" id="posts_type">
                    <option <?php selected( $current_slider['posts_type'], 'latest' ); ?> value="latest">Latest</option>
                    <option <?php selected( $current_slider['posts_type'], 'random' ); ?> value="random">Random</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Number of posts</th>
            <td>
                <select name="posts_number" id="posts_number">
                    <?php for ($i=0; $i < 7; $i++) { ?>
                    <option <?php selected( $current_slider['posts_number'], $i ); ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>

                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Order posts by</th>
            <td>
                <?php
                $orderby = array(
                    'none' => 'none' ,
                    'ID' => 'ID' ,
                    'author' => 'author' ,
                    'title' => 'title' ,
                    'name' => 'name' ,
                    'date' => 'date' ,
                    'modified' => 'modified' ,
                    'comment_count' => 'comment_count' ,
                    );
                    ?>
                    <select name="posts_order" id="posts_order">
                        <?php foreach ($orderby as $key => $value) { ?>
                        <option <?php selected( $current_slider['posts_order'], $key ); ?> value="<?php echo esc_attr($key); ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Specify categories</th>
                <td>
                    <select id="cpsliderselect" multiple="multiple" name="cats[]" title="Click to select categories for slider">
                        <?php
                        $args = array(
                          'hide_empty' => '0',
                          );
                        $categories = get_categories($args);
                        if ($categories) {
                            foreach($categories as $category) {
                                if ($category->count > 0) { ?>
                                <option <?php if (is_array($selectedcats) && in_array( $category->term_id, $selectedcats)) { echo "selected "; } ?> value="<?php echo esc_attr($category->term_id); ?>"><?php echo $category->name; ?></option>
                                <?php }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top" class="cpslider_tags_sel">
                <th scope="row">Specify tags</th>
                <td>
                    <select id="cpsliderselecttags" multiple="multiple" name="tags[]" title="Click to select categories for slider">
                        <?php

                        $categories = get_tags();
                        if ($categories) {
                            foreach($categories as $tag) {
                                if ($tag->count > 1) { ?>
                                <option <?php if (is_array($selectedtags) && in_array( $tag->term_id, $selectedtags)) { echo "selected "; } ?> value="<?php echo esc_attr($tag->term_id); ?>"><?php echo $tag->name; ?></option>
                                <?php }
                            }
                        }
                        ?>
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
        <tr><td colspan="2"><p> - they are displayed by default and it's only for <strong>Style 1</strong></p></td></tr>
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
        <?php wp_nonce_field( 'posts' ); ?>
        <input type="hidden" name="slider_name" value="<?php echo esc_attr($_GET['slider']); ?>">
    </table>
</form>