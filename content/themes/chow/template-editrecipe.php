<?php
/**
 * Template Name: Edit Recipe Template
 *
 * A custom page template without sidebar to browse recipes.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage chow
 * @since chow 1.0
 */

get_header();
$options = get_option( 'chow_option',array());
if(is_array($options) && !empty($options['post_type'])) {
    $post_type = 'recipe';
} else {
    $post_type = 'post';
}
$query = new WP_Query(array('posts_per_page' =>'-1', 'post_type' =>array('post','recipe'), 'post_status' => array('publish', 'pending', 'draft', 'private', 'trash') ) );

if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
    
    if(isset($_GET['edit_post_id'])) {
        
        if($_GET['edit_post_id'] == $post->ID)
        {
            $current_post = $post->ID;

            $title =                get_the_title();
            $content =              get_the_content();
            $category =             get_the_category(); 
            $summary =              get_post_meta($current_post, 'cookingpresssummary', true);
            $ingredients =          get_post_meta($current_post, 'cookingpressingridients', true);
            $instructions =         get_post_meta($current_post, 'cookingpressinstructions', true);
            $recipeoptions =        get_post_meta($current_post, 'cookingpressrecipeoptions', true);
            $post_ntfacts =         get_post_meta($current_post, 'cookingpressntfacts', true);
            $post_time =            get_the_terms( $current_post, 'timeneeded');
            $post_serving =         get_the_terms( $current_post, 'serving');
            $post_level =           get_the_terms( $current_post, 'level');
            $post_allergens =       get_the_terms( $current_post, 'allergen');
            $post_nutrtion_facts =  get_post_meta($current_post, 'cookingpressntfacts', true);
        } 
    }

endwhile; endif;
wp_reset_query();

?>

<!-- Titlebar
    ================================================== -->
<section id="titlebar">
    <!-- Container -->
    <div class="container">

        <div class="eight columns">

            <h2><?php the_title(); ?></h2>

        </div>

        <div class="eight columns">
                <?php if(ot_get_option('pp_breadcrumbs','on') == 'on') echo dimox_breadcrumbs(); ?>
        </div>

    </div>
    <!-- Container / End -->
</section>


<!-- Container -->
<div class="container">
    <div id="post-<?php the_ID(); ?>" <?php post_class('sixteen columns'); ?>>
    <?php 
    if(isset($postUpdated) && $postUpdated == true){ ?>
        <div class="notification success closeable">
            <p><?php _e('<span>Success!</span> Your post was updated and will be reviewed shortly. Thank you!','chow') ?></p>
            <a class="close" href="#"></a>
        </div>
    <?php } 
    if(isset($current_post)){
    if ( is_user_logged_in()) { 
        if(current_user_can('edit_post', $current_post) ) { ?>
        <div class="submit-recipe-form">
        <?php 
            $edit_page_id = ot_get_option('pp_edit_page'); 
            $edit_post = add_query_arg('edit_post_id', $current_post, get_permalink($edit_page_id)); ?>
            <form id="new_post" name="new_post" method="post" action="<?php echo $edit_post; ?>" class="addrecipe-form" enctype="multipart/form-data">

            <!-- Recipe Title -->
            <h4><?php _e('Recipe Title','chow') ?></h4>
            <nav class="title">
                <input type="text" id="title" class="search-field" value="<?php if(isset($title)) echo $title;?>" tabindex="1" name="title" />
            </nav>
            <div class="clearfix"></div>
            <?php if (isset($submit_errors) && in_array('title',$submit_errors)): ?>
                <div class="notification error closeable">
                    <p><span><?php _e('Error!','chow') ?></span> <?php _e('You forgot to fill the title.','chow') ?></p>
                    <a class="close" href="#"></a>
                </div>
            <?php endif ?>

            <div class="margin-top-25"></div>


            <!-- Choose Category -->
            <div class="select">
                <h4><?php _e('Choose Category','chow') ?></h4>
                <?php wp_dropdown_categories( array(
                    'show_option_all' =>  __('Choose Category','chow'),
                    'hide_empty' => 0,
                    'taxonomy' => 'category',
                    'id' => 'cat2',
                    'tab_index' => '3',
                    'selected' => $category[0]->cat_ID,
                    'class' => 'chosen-select-no-single'
                )); ?>
            </div>


            <div class="margin-top-25"></div>


            <!-- Short Summary -->
            <h4><?php _e('Short summary','chow') ?></h4>
            <?php
            $editor_settings = array(
                'wpautop' => true,
                'media_buttons' => true,
                'editor_class' => 'summary',
                'textarea_rows' => 5,
                'tabindex' => 4,
                'teeny' => true

                );
            if( isset( $summary ) && !empty( $summary ) ) {
                $summary = $summary;
            } else {
                $summary = ' ';
            }
            wp_editor($summary,'summary', $editor_settings); ?>
            <div class="margin-top-25"></div>


            <!-- Upload Photos -->
            <h4><?php _e('Upload thumbnail image for this recipe','chow') ?></h4>

            <div class="recipe-gallery">
            <?php 
                if(has_post_thumbnail($current_post)) {
                   echo wp_get_attachment_image(get_post_thumbnail_id($current_post ));
                } else {
                    _e('No photo uploaded yet','chow');
                } ?>
            </div>

            <label class="upload-btn">
                <input name="uploaded"  id="chooseFiles" type="file" />
                <i class="fa fa-upload"></i> <?php _e('Upload','chow') ?>
            </label>
              <table id="previewTable">
                    <thead id="columns"></thead>
                    <tbody id="previews"></tbody>
                </table>

            <div class="clearfxix"></div>
            <div class="margin-top-15"></div>


            <!-- Ingredients -->
            <fieldset class="addrecipe-cont" name="ingredients">
                <h4><?php _e('Ingredients','chow') ?></h4>

                <table id="ingredients-sort">
                <?php 
                    if(!empty($ingredients)){
                        foreach ($ingredients as $k => $meta_box_value) {
                            if ($meta_box_value['note']=='separator') { ?>
                            <tr class="ingredients-cont separator">
                                <td class="icon"><i class="fa fa-arrows"></i></td>
                                <td><input name="ingredient_name[]" type="text" value="<?php echo $meta_box_value['name']; ?>" /></td>
                                <td><input name="ingredient_note[]" type="text" value="separator" /></td>
                                <td class="action"><a title="<?php _e('Delete','chow') ?>" class="delete" href="#"><i class="fa fa-remove"></i></a> </td>
                            </tr>
                            <?php } else { ?>
                            <tr class="ingredients-cont ing">
                                <td class="icon"><i class="fa fa-arrows"></i></td>
                                <td><input name="ingredient_name[]" type="text" value="<?php echo $meta_box_value['name']; ?>" /></td>
                                <td><input name="ingredient_note[]" type="text" value="<?php echo $meta_box_value['note']; ?>" /></td>
                                <td class="action"><a title="<?php _e('Delete','chow') ?>" class="delete" href="#"><i class="fa fa-remove"></i></a> </td>
                            </tr>
                            <?php }
                        }
                    } else {?>
                    <tr class="ingredients-cont ing">
                        <td class="icon"><i class="fa fa-arrows"></i></td>
                        <td><input name="ingredient_name[]" tabindex="5" type="text" placeholder="<?php _e('Name of ingredient','chow') ?>" /> </td>
                        <td><input name="ingredient_note[]" tabindex="6" type="text" placeholder="<?php _e('Notes (quantity, additional info)','chow') ?>" /></td>
                        <td class="action"><a title="<?php _e('Delete','chow') ?>" class="delete" href="#"><i class="fa fa-remove"></i></a> </td>
                    </tr>
                    <?php } ?>
                </table>

                <a href="#" class="button color add_ingredient"><?php _e('Add new ingredient','chow') ?></a>
                <a href="#" class="button add_separator"><?php _e('Add separator','chow') ?></a>
            </fieldset>


            <div class="margin-top-25"></div>


            <!-- Directions -->
            <h4><?php _e('Directions','chow') ?></h4>
            <?php
            $editor_settings2 = array(
                'wpautop' => true,
                'media_buttons' => true,
                'editor_class' => 'instructions',
                'textarea_rows' => 5,
                'tabindex' => 10,
                'teeny' => true
                );
            if( isset( $instructions ) && !empty( $instructions ) ) {
                $instructions = $instructions;
            } else {
                $instructions = ' ';
            }
            wp_editor($instructions,'instructions', $editor_settings2); ?>
            <?php if (isset($submit_errors) && in_array('title',$submit_errors)): ?>
                <div class="notification error closeable">
                    <p><span><?php _e('Error!','chow') ?></span> <?php _e('You forgot to provide the instructions.','chow') ?></p>
                    <a class="close" href="#"></a>
                </div>
            <?php endif ?>

            <div class="margin-top-25 clearfix"></div>


            <!-- Additional Informations -->
            <h4><?php _e('Additional Informations','chow') ?></h4>
            <fieldset class="additional-info">
                <table>
              
                <tr class="ingredients-cont">
                    <td class="label"><label for="yield"><?php _e('Yield','chow') ?></label></td>
                    <td><input id="4" type="text" name="yield" value="<?php echo (isset($recipeoptions[0])) ? $recipeoptions[0] : '';?>" /></td>
                </tr>
              
                <tr class="ingredients-cont">
                    <td class="label"><label for="preptime"><?php _e('Preparation Time','chow') ?></label></td>
                    <td><input id="1" type="text" name="preptime" value="<?php echo (isset($recipeoptions[1])) ? $recipeoptions[1] : '';?>"/></td>
                </tr>
               
                <tr class="ingredients-cont">
                    <td class="label"><label for="cooktime"><?php _e('Cooking Time','chow') ?></label></td>
                    <td><input id="2" type="text" name="cooktime" value="<?php echo (isset($recipeoptions[2])) ? $recipeoptions[2] : '';?>"/></td>
                </tr>
               
                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Level','chow') ?></label></td>
                    <td>
                        <?php 
                        $levels = get_terms('level', 'hide_empty=0');
                        $compare_levels = array();
                        if(is_array($post_level)){
                            foreach ($post_level as $key) {
                               $compare_levels[] = $key->slug;
                            }
                        }
                        ?>
                        <select data-placeholder="<?php _e('Choose Level','chow') ?>" name="level" class="chosen-select-no-single">
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($levels as $level) {
                                if(in_array($level->slug,$compare_levels)) {
                                    echo "<option selected='selected' value='" . $level->slug . "'>" . $level->name . "</option>\n";
                                } else {
                                    echo "<option  value='" . $level->slug . "'>" . $level->name . "</option>\n";
                                }
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Servings','chow') ?></label></td>
                    <td>
                        <?php 
                        $servings = get_terms('serving', 'hide_empty=0');
                        $compare_servings = array();
                        if(is_array($post_serving)){
                            foreach ($post_serving as $key) {
                               $compare_servings[] = $key->slug;
                            }
                        }
                        ?>
                        <select data-placeholder="<?php _e('Choose Serving','chow') ?>" name="serving" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php 
                            foreach ($servings as $serving) {
                                if(in_array($serving->slug,$compare_servings)) {
                                    echo "<option selected='selected' value='" . $serving->slug . "'>" . $serving->name . "</option>\n";
                                } else {
                                    echo "<option value='" . $serving->slug . "'>" . $serving->name . "</option>\n";
                                }
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Time needed','chow') ?></label></td>
                    <td>
                        <?php 
                        $timeneededs = get_terms('timeneeded', 'hide_empty=0');
                        $compare_time = array();
                        if(is_array($post_time)){
                            foreach ($post_time as $key) {
                               $compare_time[] = $key->slug;
                            }
                        }
                        ?>
                        <select data-placeholder="<?php _e('Choose Time','chow') ?>" name="timeneeded" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($timeneededs as $timeneeded) {
                                if(in_array($timeneeded->slug,$compare_time)) {
                                    echo "<option selected='selected' value='" . $timeneeded->slug . "'>" . $timeneeded->name . "</option>\n";
                                } else {
                                    echo "<option value='" . $timeneeded->slug . "'>" . $timeneeded->name . "</option>\n";
                                }
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Alergens','chow') ?></label></td>
                    <td>
                        <?php 
                        $allergen = get_terms('allergen', 'hide_empty=0'); 
                        $compare_allergens = array();
                        if(is_array($post_allergens)){
                            foreach ($post_allergens as $key) {
                               $compare_allergens[] = $key->slug;
                            }
                        }
                        ?>
                        <select data-placeholder="<?php _e('Choose Alergens','chow') ?>" name="allergen[]" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($allergen as $allergen) {
                                if(in_array($allergen->slug,$compare_allergens)) {
                                    echo "<option selected='selected' value='" . $allergen->slug . "'>" . $allergen->name . "</option>\n";
                                } else {
                                    echo "<option value='" . $allergen->slug . "'>" . $allergen->name . "</option>\n";
                                }
                            } ?>
                        </select>
                    </td>
                </tr>

                </table>
            </fieldset>


            <div class="margin-top-25"></div>


            <!-- Nutrition Facts -->
            <h4><?php _e('Nutrition Facts','chow') ?></h4>

            <fieldset class="additional-info">
                <table>
                 <?php
                    $foodiepress = new FoodiePress;
                    $nutritionsfacts = $foodiepress->get_nutritions(); ?>
                    <?php foreach ($nutritionsfacts as $key => $desc) { ?>
                        <tr class="ingredients-cont">
                            <td class="label"><label for="<?php echo esc_attr($key); ?>"><?php echo $desc; ?></label></td>
                            <td><input id="<?php echo esc_attr($key); ?>" type="text" name="cookingpressntfacts[<?php echo esc_attr($key); ?>]"  
                            value="<?php echo (isset($post_nutrtion_facts[$key])) ? $post_nutrtion_facts[$key] : '';?>"></td>
                        </tr>
                <?php } ?>

                </table>
            </fieldset>
            <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
            <div class="margin-top-30"></div>
            <input type="submit" class="button color big" value="<?php _e( 'Save Changes', 'chow' ); ?>" tabindex="40" id="submit" name="submit" />

            <input type="hidden" name="edit_post_id" value="<?php echo $current_post; ?>" />
            <input type="hidden" name="action" value="update_post" />
        </form>
        <?php 
            } else { ?>
            <div class="notification error closeable">
                <p><?php _e("<span>You don't have permission to edit that post!",'chow') ?></p>
                <a class="close" href="#"></a>
            </div>
        <?php }
        } else {
            get_template_part( 'loginform');
        }
    } else { ?>
         <div class="notification error closeable">
            <p><?php _e("<span>Post with that ID does not exists or can't be edited now",'chow') ?></p>
            <a class="close" href="#"></a>
        </div>
<?php } ?>
    </div>
       

        </div><!-- #post-## -->
<?php get_footer(); ?>