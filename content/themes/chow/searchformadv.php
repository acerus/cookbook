
<!-- Container -->
<div class="advanced-search-container">
    <div class="container">
        <div class="sixteen columns">
            <div id="advanced-search">
                <form method="get" action="<?php  echo esc_url( home_url() ); ?>">
                <?php
                    if (isset($_GET['include_ing']) && is_array($_GET['include_ing'])) { $tags = $_GET['include_ing'];  } else { $tags = array(); }
                    if (isset($_GET['exclude_ing']) && is_array($_GET['exclude_ing'])) { $extags = $_GET['exclude_ing'];  } else { $extags = array(); }
                    if (isset($_GET['cats']) && is_array($_GET['cats'])) { $setcats = $_GET['cats'];  } else { $setcats = array(); }
                ?>
                <!-- Choose Category -->
                <div class="select select-cats">
                    <label><?php _e('Choose category','chow') ?></label>
                    <?php
                    /*$select_cats = wp_dropdown_categories( array(
                        //'show_option_all' =>  __('All','chow'),
                        'hide_empty' => 0,
                        'id' => 'cat2',
                        'class' => 'chosen-select',
                        'echo' => 0
                    )); 
                    $cattitle = __('All','chow');
                    $select_cats = str_replace( "name='cat' id=", 'name="cats[]"" multiple="multiple" data-placeholder="'.$cattitle.'" id=', $select_cats );
                    echo $select_cats;*/
                  
                    ?>
                     <select name="cats[]" id="cat2" multiple="multiple" class="chosen-select" data-placeholder="<?php _e('Choose categories','chow')?>">
                        <option value=""><?php _e( 'Choose categories', 'chow' ); ?></option>
                            <?php
                            $thecats = get_terms('category', 'orderby=name&hide_empty=0');
                            foreach ($thecats as $cat) :
                              
                                echo "<option value='".$cat->term_id."'";
                                    if (in_array($cat->term_id, $setcats)) {
                                        echo ' selected="selected" ';
                                    }
                                echo ">".$cat->name."</option>\n";
                            endforeach;
                            ?>
                    </select>

                </div>

                <!-- Choose ingredients -->
                <div class="select included-ingredients">
                    <label><?php _e('Select one or more ingredients that should be included in recipe','chow'); ?></label>
                     <?php echo chow_tags_multiselect("include_ing",$tags, __('Included Ingredients','chow')); ?>
                </div>

                <!-- Choose -->
                <div class="select">
                    <label><?php _e('Recipe needs to have','chow'); ?></label>
                    <select name="relation" class="chosen-select">
                        <option value="any" <?php if(isset( $_GET['relation'])) { echo $_GET['relation'] == 'any' ? ' selected="selected"' : ''; } ?>><?php _e('Any of selected ingredients', 'chow'); ?></option>
                        <option value="all" <?php if(isset( $_GET['relation'])) { echo $_GET['relation'] == 'all' ? ' selected="selected"' : ''; } ?>><?php _e('All of selected ingredients', 'chow'); ?></option>
                    </select>
                </div>

                <div class="clearfix"></div>

                <!-- Search Input -->
                <nav class="search-by-keyword">
                    <button><span><?php _e('Search for Recipes','chow'); ?></span><i class="fa fa-search"></i></button>
                    <input type="text" id="s" name="s"  class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php _e('Search by keyword','chow') ?>" />
                </nav>
                <div class="clearfix"></div>

                <?php 
                if( isset($_GET['allergens']) || !empty($extags) ||  !empty($_GET['level']) ||  !empty($_GET['serving']) || !empty($_GET['timeneeded']) ) {
                    $extra_class  = 'opened';
                    $carret_class = 'fa-caret-up';
                } else {
                    $extra_class  = 'closed';
                    $carret_class = 'fa-caret-down';
                }  ?>
                <!-- Advanced Search Button -->
                <a href="#" class="adv-search-btn <?php echo esc_attr($extra_class); ?>"><?php _e('Advanced Search ','chow'); ?><i class="fa <?php echo esc_attr($carret_class) ?>"></i></a>
                
                <!-- Extra Search Options -->
                <div class="extra-search-options <?php echo esc_attr($extra_class); ?>">

                    <!-- Choose Excluded Ingredients -->
                    <div class="select excluded-ingredients">
                        <label><?php _e('Select one or more ingredients that should be excluded from recipe','chow'); ?></label>
                        <?php echo chow_tags_multiselect( "exclude_ing", $extags, __('Excluded Ingredients','chow')); ?>
                    </div>

                    <!-- Choose Alergens -->
                    <div class="select alergens">
                        <label><?php _e('Choose alergens','chow'); ?></label>
                        <select name="allergens[]" multiple="true" class="chosen-select" data-placeholder="<?php _e('Select allergens','chow')?>">
                            <option value=""><?php _e( 'Choose alergens', 'chow' ); ?></option>
                                <?php
                                $theterms = get_terms('allergen', 'orderby=name&hide_empty=0');
                                foreach ($theterms as $term) :
                                    echo "<option value='".$term->slug."'";
                                        if(isset($_GET['allergens'])) { echo $_GET['allergens'] == $term->slug ? ' selected="selected"' : ''; }
                                    echo ">".$term->name."</option>\n";
                                endforeach;
                                ?>
                        </select>
                    </div>


                    <div class="clearfix"></div>


                    <!-- Choose Level -->
                    <div class="select">
                        <label><?php _e('Choose level','chow'); ?></label>
                        <select data-placeholder="<?php _e('Choose level','chow') ?>" name="level" class="chosen-select">
                            <option value=""><?php _e( 'All', 'chow' ); ?></option>
                        <?php

                            $theterms = get_terms('level', 'orderby=name');
                            foreach ($theterms AS $term) :
                                echo "<option value='".$term->slug."'";
                                    if(isset($_GET['level'])) { echo $_GET['level'] == $term->slug ? ' selected="selected"' : ''; }
                                echo ">".$term->name."</option>\n";
                            endforeach;
                        ?>
                        </select>
                    </div>

                    <!-- Choose serving -->
                    <div class="select">
                        <label><?php _e('Choose serving','chow'); ?></label>
                        <select data-placeholder="<?php _e('Choose serving','chow'); ?>" name="serving" class="chosen-select">
                            <option value=""><?php _e('All','chow'); ?></option>
                                <?php
                                $theterms = get_terms('serving', 'orderby=name');
                                foreach ($theterms AS $term) :
                                    echo "<option value='".$term->slug."'";
                                    if(isset($_GET['serving'])) { echo $_GET['serving'] == $term->slug ? ' selected="selected"' : ''; }
                                    echo ">".$term->name."</option>\n";
                                endforeach;
                                ?>
                        </select>
                    </div>

                    <!-- Choose time needed -->
                    <div class="select">
                        <label><?php _e('Choose time needed','chow'); ?></label>
                        <select name="timeneeded" data-placeholder="<?php _e('Choose time needed','chow'); ?>" class="chosen-select">
                            <option value=""><?php _e( 'All', 'chow' ); ?></option>
                             <?php
                                $theterms = get_terms('timeneeded', 'orderby=name');
                                foreach ($theterms AS $term) :
                                    echo "<option value='".$term->slug."'";
                                    if(isset($_GET['timeneeded'])) { echo $_GET['timeneeded'] == $term->slug ? ' selected="selected"' : ''; }
                                    echo ">".$term->name."</option>\n";
                                endforeach;
                                ?>
                        </select>
                    </div>

                    <div class="clearfix"></div>
                    <div class="margin-top-10"></div>
                     <?php 
                        $options = get_option( 'chow_option',array());
                        if(is_array($options) && !empty($options['post_type'])) {
                            $post_type = 'recipe';
                        } else {
                            $post_type = 'post';
                        } ?>
                    <input type="hidden" name="post_type" value="<?php echo $post_type; ?>" />
                </div>
                <!-- Extra Search Options / End -->
                </form>

            <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>