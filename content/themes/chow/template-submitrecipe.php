<?php
/**
 * Template Name: Submit Recipe Template
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
<?php while ( have_posts() ) : the_post(); ?>

<!-- Container -->
<div class="container">
    <div id="post-<?php the_ID(); ?>" <?php post_class('sixteen columns'); ?>>
    <?php 
    if(isset($postAdded) && $postAdded == true){ ?>
        <div class="notification success closeable">
            <p><?php _e('<span>Success!</span> Your post was added and will be reviewed shortly. Thank you! You can add another one :)','chow') ?></p>
            <a class="close" href="#"></a>
        </div>
    <?php };  

    if ( is_user_logged_in() ) { ?>
        <div class="submit-recipe-form">
            <form id="new_post" name="new_post" method="post" action="<?php the_permalink(); ?>" class="addrecipe-form" enctype="multipart/form-data">

            <!-- Recipe Title -->
            <h4><?php _e('Recipe Title','chow') ?></h4>
            <nav class="title">
                <input type="text" id="title" class="search-field" value="<?php if(isset($postAdded) && $postAdded == false && isset($_POST['title'])) echo mysql_real_escape_string($_POST['title']);?>" tabindex="1" name="title" />
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
                <?php 
                $wp_dropdown_categories_args = array(
                    
                    'hide_empty' => 0,
                    'taxonomy' => 'category',
                    'id' => 'cat2',
                    'name' => 'cat[]',
                    'echo'              => FALSE,
                    'tab_index' => '3',
                    'class' => 'chosen-select-no-single'
                ); 
                $dropdown = wp_dropdown_categories($wp_dropdown_categories_args);
                $cattitle =  __('Choose Category','chow');
                $dropdown = str_replace('id=', 'data-placeholder="'.$cattitle.'" multiple="multiple" id=', $dropdown);
                echo $dropdown; 
                ?>
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
            if( isset($postAdded) && $postAdded == false && isset( $_POST['summary'] ) && !empty( $_POST['summary'] ) ) {
                $summary = $_POST['summary'];
            } else {
                $summary = ' ';
            }
            wp_editor($summary,'summary', $editor_settings); ?>
            <div class="margin-top-25"></div>


            <!-- Upload Photos -->
            <h4><?php _e('Upload thumbnail image for this recipe','chow') ?></h4>

            <div class="recipe-gallery">
               <?php _e('No photo uploaded yet','chow') ?>
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

                    <tr class="ingredients-cont ing">
                        <td class="icon"><i class="fa fa-arrows"></i></td>
                        <td><input class="ingredient_name" name="ingredient_name[]" tabindex="5" type="text" placeholder="<?php _e('Name of ingredient','chow') ?>" /> </td>
                        <td><input name="ingredient_note[]" tabindex="6" type="text" placeholder="<?php _e('Notes (quantity, additional info)','chow') ?>" /></td>
                        <td class="action"><a title="<?php _e('Delete','chow') ?>" class="delete" href="#"><i class="fa fa-remove"></i></a> </td>
                    </tr>

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
            wp_editor(' ','instructions', $editor_settings2); ?>
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
                    <td><input id="4" type="text" name="yield" /></td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label for="preptime"><?php _e('Preparation Time','chow') ?></label></td>
                    <td><input id="1" type="text" name="preptime"/></td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label for="cooktime"><?php _e('Cooking Time','chow') ?></label></td>
                    <td><input id="2" type="text" name="cooktime"/></td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Level','chow') ?></label></td>
                    <td>
                        <?php $levels = get_terms('level', 'hide_empty=0'); ?>
                        <select data-placeholder="<?php _e('Choose Level','chow') ?>" name="level" class="chosen-select-no-single">
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($levels as $level) {
                                echo "<option value='" . $level->slug . "'>" . $level->name . "</option>\n";
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Servings','chow') ?></label></td>
                    <td>
                        <?php $servings = get_terms('serving', 'hide_empty=0'); ?>
                        <select data-placeholder="<?php _e('Choose Serving','chow') ?>" name="serving" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($servings as $serving) {
                                echo "<option value='" . $serving->slug . "'>" . $serving->name . "</option>\n";
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Time needed','chow') ?></label></td>
                    <td>
                        <?php $timesneeded = get_terms('timeneeded', 'hide_empty=0'); ?>
                        <select data-placeholder="<?php _e('Choose Time','chow') ?>" name="timeneeded" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($timesneeded as $timeneeded) {
                                echo "<option value='" . $timeneeded->slug . "'>" . $timeneeded->name . "</option>\n";
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="ingredients-cont">
                    <td class="label"><label><?php _e('Alergens','chow') ?></label></td>
                    <td>
                        <?php $allergens = get_terms('allergen', 'hide_empty=0'); ?>
                        <select data-placeholder="<?php _e('Choose Alergens','chow') ?>" name="allergen[]" class="chosen-select" multiple>
                            <option value=''><?php _e('None','chow') ?></option>
                            <?php foreach ($allergens as $allergen) {
                                echo "<option value='" . $allergen->slug . "'>" . $allergen->name . "</option>\n";
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
                        <td><input id="<?php echo esc_attr($key); ?>" type="text" name="cookingpressntfacts[<?php echo esc_attr($key); ?>]"  value="<?php echo (!empty($meta_box_value[$key])) ? $meta_box_value[$key] : ''; ?>"></td>
                    </tr>
                <?php } ?>

                </table>
            </fieldset>

            <div class="margin-top-30"></div>
            <input type="submit" class="button color big" value="<?php _e( 'Submit Recipe', 'chow' ); ?>" tabindex="40" id="submit" name="submit" />
             <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
            <input type="hidden" name="cookingpressrecipetheme" value="recipe1" />
            <input type="hidden" name="action" value="new_post" />
        </form>
        <?php } //eof is user loged
        else {
            if(class_exists('UM_API'))  {
                $umoptions = get_option('um_core_forms',array() );
                $forms = array();
                
                foreach ($umoptions as $key => $value) {
                    $forms[] = $key;           
                }
                echo do_shortcode('[ultimatemember form_id='.$forms[1].']' );
            } else {
                get_template_part( 'loginform');
            }
        } ?>
    </div>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'chow' ),
            'after'  => '</div>',
            ) );
            ?>
            <div class="clearfix"></div>
            <footer class="entry-footer">
                <?php edit_post_link( __( 'Edit', 'chow' ), '<span class="edit-link">', '</span>' ); ?>
            </footer><!-- .entry-footer -->


        </div><!-- #post-## -->


<?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>