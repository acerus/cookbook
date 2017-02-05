<?php
/**
 * FoodiePress.
 *
 * @package   FoodiePress
 * @author    purethemes
 * @license   ThemeForest
 * @copyright 2014 Purethemes.net
 */


class FoodiePress {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.3.4';

	/**
	 * @TODO - Rename "plugin-name" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'foodiepress';


	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_recipe_post_type' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );



		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		


		// Add custom meta-boxes for recipe editor
		add_action( 'add_meta_boxes', array( $this, 'add_recipe_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_recipe_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_taxonomy_data' ) );

		add_action( 'wp_ajax_foodiepress_photo_update', array( $this, 'foodiepress_photo_update' )  );
		add_action( 'wp_ajax_foodiepress_dismiss_notice', array( $this, 'foodiepress_dismiss_notice' )  );


		add_shortcode( 'purerecipe', array( $this, 'purerecipe' ) );
		add_shortcode( 'foodiepress', array( $this, 'purerecipe' ) );

		add_filter( 'foodiepress_themes', array($this,'default_cp_themes' ),10);
		add_filter( 'the_content', array($this,'force_recipe_shortcode' ));

		


		$options = get_option( 'chow_option',array());
		if(is_array($options) && empty($options['ratings'])) {
			// Add rating for comments
			add_action( 'comment_form_logged_in_after', array( $this, 'rate_post_rating_field' ) );
			add_action( 'comment_form_after_fields', array( $this, 'rate_post_rating_field' ) );
			add_action( 'comment_post', array( $this, 'save_comment_meta_data' ) );
			add_filter( 'comment_text', array( $this, 'modify_comment' ),1000 );
			add_action( 'add_meta_boxes_comment', array( $this, 'recipe_rating_comment_add_meta_box' ) );
			add_action( 'edit_comment', array( $this, 'recipe_rating_edit_rating' ) );

			//actions

			add_action('foodiepress-rating',array($this, 'recipe_rating'));
			add_action('foodiepress-widget-rating',array($this, 'recipe_rating'));
			add_action('foodiepress-reviews',array($this, 'recipe_reviews'));
			add_action('foodiepress_post_header',array($this, 'recipe_rating'));
			add_action('foodiepress_post_header',array($this, 'recipe_reviews'));
		}
	}




	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	public function get_nutritions() {
		$nutritions = array(
			'calories' => __('Calories','foodiepress'),
			'carbohydrateContent' => __('Carbohydrate Content','foodiepress'),//'The number of grams of carbohydrates.',
			'cholesterolContent' => __('Cholesterol Content','foodiepress'),//'The number of milligrams of cholesterol.',
			'fatContent' => __('Fat Content','foodiepress'),//'The number of grams of fat.',
			'fiberContent' => __('Fiber Content','foodiepress'),//'The number of grams of fiber.',
			'proteinContent' => __('Protein Content','foodiepress'),//'The number of grams of protein.',
			'saturatedFatContent' => __('Saturated Fat Content','foodiepress'),//'The number of grams of saturated fat.',
			'servingSize' => __('Serving Size','foodiepress'),//'The serving size, in terms of the number of volume or mass',
			'sodiumContent' => __('Sodium Content','foodiepress'),//'The number of milligrams of sodium.',
			'sugarContent' => __('Sugar Content','foodiepress'),//'The number of grams of sugar.',
			'transFatContent' => __('Trans Fat Content','foodiepress'),//'The number of grams of trans fat.',
			'unsaturatedFatContent' => __('Unsaturated Fat Content','foodiepress'),//'The number of grams of unsaturated fat.',
			);
		return $nutritions;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		if ( function_exists( 'wp_get_sites ') ) {
	        $sites = @wp_get_sites();
	        $blog_ids = array();
	        foreach ( $sites as $site ) {
	            $blog_ids[] = $site['blog_id'];
	        }
	        return $blog_ids;
	    } else {
	    	return array();
	    }

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

	}

	/**
	 * Register custom taxonomies for
	 *
	 * @since    1.0.0
	 */
	public function register_taxonomies() {

		$options = get_option( 'chow_option',array());
		if(is_array($options) && !empty($options['post_type'])) {
			$post_type = 'recipe';
		} else {
			$post_type = 'post';
		}

		$time_labels = array(
			'name' => __( 'Total time needed', 'foodiepress' ),
			'singular_name' => __( 'Time needed', 'foodiepress' ),
			'search_items' => __( 'Search Time needed', 'foodiepress' ),
			'popular_items' => __( 'Popular Time needed', 'foodiepress' ),
			'all_items' => __( 'All Time needed', 'foodiepress' ),
			'parent_item' => __( 'Parent Time needed', 'foodiepress' ),
			'parent_item_colon' => __( 'Parent Time needed:', 'foodiepress' ),
			'edit_item' => __( 'Edit Time needed', 'foodiepress' ),
			'update_item' => __( 'Update Time needed', 'foodiepress' ),
			'add_new_item' => __( 'Add New Time needed', 'foodiepress' ),
			'new_item_name' => __( 'New Time needed Name', 'foodiepress' ),
			'separate_items_with_commas' => __( 'Separate time needed with commas', 'foodiepress' ),
			'add_or_remove_items' => __( 'Add or remove time needed', 'foodiepress' ),
			'choose_from_most_used' => __( 'Choose from the most used time needed', 'foodiepress' ),
			'menu_name' => __( 'Time needed', 'foodiepress' ),
			);

			$time_args = array(
				'labels' => $time_labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => apply_filters('foodiepress_timeneeded_slug','timeneeded') ),
				'query_var' => true
				);

			register_taxonomy( 'timeneeded', array($post_type), $time_args );


			$level_labels = array(
				'name' => __( 'Levels', 'foodiepress' ),
				'singular_name' => __( 'Level', 'foodiepress' ),
				'search_items' => __( 'Search Levels', 'foodiepress' ),
				'popular_items' => __( 'Popular Levels', 'foodiepress' ),
				'all_items' => __( 'All Levels', 'foodiepress' ),
				'parent_item' => __( 'Parent Level', 'foodiepress' ),
				'parent_item_colon' => __( 'Parent Level:', 'foodiepress' ),
				'edit_item' => __( 'Edit Level', 'foodiepress' ),
				'update_item' => __( 'Update Level', 'foodiepress' ),
				'add_new_item' => __( 'Add New Level', 'foodiepress' ),
				'new_item_name' => __( 'New Level Name', 'foodiepress' ),
				'separate_items_with_commas' => __( 'Separate levels with commas', 'foodiepress' ),
				'add_or_remove_items' => __( 'Add or remove levels', 'foodiepress' ),
				'choose_from_most_used' => __( 'Choose from the most used levels', 'foodiepress' ),
				'menu_name' => __( 'Levels', 'foodiepress' ),
				);

			$level_args = array(
				'labels' => $level_labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => false,
				'rewrite' => array( 'slug' => apply_filters('foodiepress_level_slug','level') ),
				'query_var' => true
				);

			register_taxonomy( 'level', array($post_type), $level_args );


			$serving_labels = array(
				'name' => __( 'Servings', 'foodiepress' ),
				'singular_name' => __( 'Serving', 'foodiepress' ),
				'search_items' => __( 'Search Servings', 'foodiepress' ),
				'popular_items' => __( 'Popular Servings', 'foodiepress' ),
				'all_items' => __( 'All Servings', 'foodiepress' ),
				'parent_item' => __( 'Parent Serving', 'foodiepress' ),
				'parent_item_colon' => __( 'Parent Serving:', 'foodiepress' ),
				'edit_item' => __( 'Edit Serving', 'foodiepress' ),
				'update_item' => __( 'Update Serving', 'foodiepress' ),
				'add_new_item' => __( 'Add New Serving', 'foodiepress' ),
				'new_item_name' => __( 'New Serving Name', 'foodiepress' ),
				'separate_items_with_commas' => __( 'Separate servings with commas', 'foodiepress' ),
				'add_or_remove_items' => __( 'Add or remove servings', 'foodiepress' ),
				'choose_from_most_used' => __( 'Choose from the most used servings', 'foodiepress' ),
				'menu_name' => __( 'Servings', 'foodiepress' ),
				);

			$serving_args = array(
				'labels' => $serving_labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => false,
				'rewrite' => array( 'slug' => apply_filters('foodiepress_serving_slug','serving' ) ),
				'query_var' => true
				);

			register_taxonomy( 'serving', array($post_type), $serving_args );



			$allergens_labels = array(
				'name' => __( 'Food Allergens', 'foodiepress' ),
				'singular_name' => __( 'Allergen', 'foodiepress' ),
				'search_items' => __( 'Search Allergens', 'foodiepress' ),
				'popular_items' => __( 'Popular Allergens', 'foodiepress' ),
				'all_items' => __( 'All Allergens', 'foodiepress' ),
				'parent_item' => __( 'Parent Allergen', 'foodiepress' ),
				'parent_item_colon' => __( 'Parent Allergen:', 'foodiepress' ),
				'edit_item' => __( 'Edit Allergen', 'foodiepress' ),
				'update_item' => __( 'Update Allergens', 'foodiepress' ),
				'add_new_item' => __( 'Add New Allergens', 'foodiepress' ),
				'new_item_name' => __( 'New Allergens Name', 'foodiepress' ),
				'separate_items_with_commas' => __( 'Separate allergens with commas', 'foodiepress' ),
				'add_or_remove_items' => __( 'Add or remove allergens', 'foodiepress' ),
				'choose_from_most_used' => __( 'Choose from the most used allergens', 'foodiepress' ),
				'menu_name' => __( 'Food Allergens', 'foodiepress' ),
				);

			$allergens_args = array(
				'labels' => $allergens_labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => apply_filters('foodiepress_allergen_slug','allergen' ) ),
				'query_var' => true
				);

			register_taxonomy( 'allergen', array($post_type), $allergens_args );



		    $ingredient_labels = array(
		        'name' => __( 'Ingredients', 'foodiepress' ),
		        'singular_name' => __( 'Ingredient', 'foodiepress' ),
		        'search_items' => __( 'Search Ingredients', 'foodiepress' ),
		        'popular_items' => __( 'Popular Ingredients', 'foodiepress' ),
		        'all_items' => __( 'All Ingredients', 'foodiepress' ),
		        'parent_item' => __( 'Parent Ingredient', 'foodiepress' ),
		        'parent_item_colon' => __( 'Parent Ingredient:', 'foodiepress' ),
		        'edit_item' => __( 'Edit Ingredient', 'foodiepress' ),
		        'update_item' => __( 'Update Ingredient', 'foodiepress' ),
		        'add_new_item' => __( 'Add New Ingredient', 'foodiepress' ),
		        'new_item_name' => __( 'New Ingredient', 'foodiepress' ),
		        'separate_items_with_commas' => __( 'Separate ingredients with commas', 'foodiepress' ),
		        'add_or_remove_items' => __( 'Add or remove Ingredients', 'foodiepress' ),
		        'choose_from_most_used' => __( 'Choose from most used Ingredients', 'foodiepress' ),
		        'menu_name' => __( 'Ingredients', 'foodiepress' ),
		    );

		    $ingredient_args = array(
		        'labels' => $ingredient_labels,
		        'public' => true,
		        'show_in_nav_menus' => true,
		        'show_ui' => true,
		        'show_tagcloud' => true,
		        'show_admin_column' => false,
		        'hierarchical' => false,
		        'rewrite' => array( 'slug' =>  apply_filters('foodiepress_ingredients_slug','ingredients' ) ),
		        'query_var' => true
		    );

		    register_taxonomy( 'ingredients', array($post_type), $ingredient_args );

	}

	public function register_recipe_post_type(){
		if ( post_type_exists( "recipe" ) )
			return;

		$options = get_option( 'chow_option',array());
		if(is_array($options) && empty($options['post_type'])) {
			return;
		}
		if(is_array($options) && !empty($options['recipe_slug'])) {
			$recipe_slug = $options['recipe_slug'];
		} else {
			$recipe_slug = 'recipe';
		}
		$labels = array(
			'name'                  => _x( 'Recipes', 'Post Type General Name', 'foodiepress' ),
			'singular_name'         => _x( 'Recipe', 'Post Type Singular Name', 'foodiepress' ),
			'menu_name'             => __( 'Recipes', 'foodiepress' ),
			'name_admin_bar'        => __( 'Recipes', 'foodiepress' ),
			'archives'              => __( 'Recipes Archives', 'foodiepress' ),
			'parent_item_colon'     => __( 'Parent Item:', 'foodiepress' ),
			'all_items'             => __( 'All Recipes', 'foodiepress' ),
			'add_new_item'          => __( 'Add New Recipe', 'foodiepress' ),
			'add_new'               => __( 'Add Recipe', 'foodiepress' ),
			'new_item'              => __( 'New Recipe', 'foodiepress' ),
			'edit_item'             => __( 'Edit Recipe', 'foodiepress' ),
			'update_item'           => __( 'Update Recipe', 'foodiepress' ),
			'view_item'             => __( 'View Recipe', 'foodiepress' ),
			'search_items'          => __( 'Search Recipes', 'foodiepress' ),
			'not_found'             => __( 'Not found', 'foodiepress' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'foodiepress' ),
			'featured_image'        => __( 'Featured Image', 'foodiepress' ),
			'set_featured_image'    => __( 'Set featured image', 'foodiepress' ),
			'remove_featured_image' => __( 'Remove featured image', 'foodiepress' ),
			'use_featured_image'    => __( 'Use as featured image', 'foodiepress' ),
			'insert_into_item'      => __( 'Insert into item', 'foodiepress' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'foodiepress' ),
			'items_list'            => __( 'Items list', 'foodiepress' ),
			'items_list_navigation' => __( 'Items list navigation', 'foodiepress' ),
			'filter_items_list'     => __( 'Filter items list', 'foodiepress' ),
		);
		$args = array(
			'label'                 => __( 'Recipe', 'foodiepress' ),
			'description'           => __( 'Post Type Description', 'foodiepress' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'post-formats' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-carrot',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'rewrite'            => array( 'slug' => $recipe_slug ),
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'recipe', $args );

	}

	/**
	 * Adds the meta box container.
	 *
	 * @since    1.0.0
	 */
	public function add_recipe_meta_boxes( $post_type ) {
            $post_types = array('post','recipe');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
            	add_meta_box(
            		'recipe-meta-boxes',
            		__( 'Recipe editor', 'foodiepress' ),
            		array( $this, 'render_recipe_meta_box' ),
            		$post_type,
            		'normal',
            		'high'
            		);
            }
        }


        public function get_recipe_meta_array() {
        	$shortname = 'cookingpress';
		// For CookingPress theme compatibility
		// @TODO ADD option to switch it on/off
        	$servings = $this->get_terms_array( 'serving' );
        	$levels = $this->get_terms_array( 'level' );
        	$themes = apply_filters( 'foodiepress_themes', array());
			foreach ($themes as $key => $value) {
				$selthemes[$key] = $value['name'];
			}
			$options = get_option( 'chow_option',array());
			if(!empty($options['default_style'])) {
				$recipetheme = $options['default_style'];
			} else {
				$recipetheme = 'recipe1';
			}
        	$recipe_meta_boxes = array(
        		"title" => array(
        			"name" => $shortname."title",
        			"std" => "",
        			"title" => __("Recipe title", 'foodiepress'),
        			"type" => "text",
        			"description" => __("It's neccessery for google rich snippet to put Title of recipe here, if empty - post title will be used", 'foodiepress')
        			),
        		"recipetheme" => array(
        			"name" => $shortname."recipetheme",
        			"std" => $recipetheme,
        			"title" => __("Recipe Theme", 'foodiepress'),
        			"type" => "select",
        			"options" => $selthemes,
        			"description" => __("Choose if want to show sidebar next to post content", 'foodiepress')
        			),
        		"summary" => array(
        			"name" => $shortname."summary",
        			"std" => "",
        			"title" => __("Short summary of recipe", 'foodiepress'),
        			"type" => "textarea",
        			"description" => __("Short summary of recipe", 'foodiepress')
        			),
				"ingridients" => array(  // stupid typo!!!
					"name" => $shortname."ingridients",
					"std" => "",
					"title" => __("Ingredients", 'foodiepress'),
					"type" => "ingridients"
					),
				"instructions" => array(
					"name" => $shortname."instructions",
					"std" => "",
					"title" => __("Instructions", 'foodiepress'),
					"type" => "instructions"
					),
				"recipeoptions" => array(
					"name" => $shortname."recipeoptions",
					"std" => "",
					"title" => __("Recipe options", 'foodiepress'),
					"type" => "recipeoptions"
					),
				"ntfacts" => array(
					"name" => $shortname."ntfacts",
					"std" => "",
					"title" => __("Nutrition facts <span>(per portion)</span>", 'foodiepress'),
					"type" => "ntfacts"
					),
				"photo" => array(
					"name" => $shortname."photo",
					"std" => "",
					"title" => __("Photo", 'foodiepress'),
					"type" => "photo",
					"description" => ''
					)
			);
		return $recipe_meta_boxes;
	}
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_recipe_meta_box( $post ) {
			// Add an nonce field so we can check for it later.
		wp_nonce_field( 'foodiepress_inner_custom_box', 'foodiepress_inner_custom_box_nonce' );
		$recipe_meta_boxes = $this->get_recipe_meta_array();
		$metaboxform = $this->print_meta_box_html($recipe_meta_boxes);
		echo $metaboxform;
	}

	/**
	 * Function to print HTML forms for each meta box
	 *
	 * @since    1.0.0
	 */
	public function print_meta_box_html($boxes){
		global $post;

		$form_el_start = '<div class="fp-setting-wrap">';
		$form_el_end = '</div>';
		foreach ($boxes as $meta_box) {

			$meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
			if (empty($meta_box_value)) {
				$meta_box_value = $meta_box['std'];
			}

			switch ($meta_box['type']) {
				case 'text':
					echo $form_el_start; ?>
					<label for="<?php echo $meta_box['name']; ?>">
						<?php echo $meta_box['title']; ?>
						<small><?php echo  $meta_box['description']; ?></small>
					</label>
					<input name="<?php echo $meta_box['name']; ?>" id="<?php echo $meta_box['name']; ?>" type="text" value="<?php echo $meta_box_value; ?>"/>
					<?php echo $form_el_end;
				break;

				case 'photo':
					echo $form_el_start; ?>
					<label for="<?php echo $meta_box['name']; ?>">
						<?php echo $meta_box['title']; ?>
						<small><?php echo  $meta_box['description']; ?></small>
					</label>
					<div id="foodiepress-photo-container">
						<?php if(!empty($meta_box_value)) {
							echo wp_get_attachment_image( $meta_box_value, 'recipe-thumb', false, array( 'class' => "foodiepress-photo" ));
						} ?>
					</div>
					<input name="<?php echo $meta_box['name']; ?>" id="<?php echo $meta_box['name']; ?>" class="foodiepress-upload-photo" type="hidden" value="<?php echo $meta_box_value; ?>"/>
					<input type="button" id="foodiepress-upload-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'foodiepress' )?>" />
					<?php if(!empty($meta_box_value)) { echo '<a href="#" id="remove_cpphoto">'; _e('Remove photo echo','foodiepress'); echo '</a>'; } ?>
					<?php echo $form_el_end;
				break;

				case 'select':
					echo $form_el_start; ?>
					<label for="<?php echo $meta_box['name']; ?>"><?php echo $meta_box['title']; ?></label>
					<select name="<?php echo $meta_box['name']; ?>">
						<?php if(!empty($meta_box['options'])) {
							foreach ($meta_box['options'] as $key => $option) {
								if ($meta_box_value == $key || $key == $meta_box['std']) {
									echo '<option selected="selected" value="'.$key.'">'.$option.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$option.'</option>';
								}
							}
						} ?>
					</select>
					<?php echo $form_el_end;
				break;

				case 'textarea':
					echo $form_el_start;
					echo '<h3>'.$meta_box['title'].'</h3>';
					$editor_settings = array(
						'media_buttons' => true,
						'textarea_rows' => '5',
						'teeny' => true
						);
					wp_editor($meta_box_value, $meta_box['name'], $editor_settings);
					echo $form_el_end;
				break;

				case 'recipeoptions':
					echo $form_el_start; ?>
					<div class='fp-text-section'>
						<p>
							<?php _e("Preparation time for this recipe is ", 'foodiepress'); ?><input style="width:50px" name="<?php echo $meta_box['name'] . '_preptime'; ?>" id="preptime" type="text" value="<?php echo (!empty($meta_box_value[0])) ? $meta_box_value[0] : ''; ?>" /><?php _e("minutes", 'foodiepress'); ?>,
							<?php _e(" and cooking time takes", 'foodiepress'); ?> <input style="width:50px" name="<?php echo $meta_box['name'] . '_cooktime'; ?>" id="cooktime" type="text" value="<?php echo (!empty($meta_box_value[1])) ? $meta_box_value[1] : ''; ?>" /> <?php _e("minutes", 'foodiepress'); ?>.
						</br><?php _e("Yield is", 'foodiepress'); ?> <input style="width:80px" name="<?php echo $meta_box['name'] . '_yield'; ?>" id="yield" type="text" value="<?php echo (!empty($meta_box_value[2])) ? $meta_box_value[2] : ''; ?>" />
					</p>
					</div>
					<?php
					echo $form_el_end;
				break;

				case 'instructions':
					echo $form_el_start;
					$editor_settings = array(
						'media_buttons' => true,
						'textarea_rows' => '5',
						'teeny' => true
						);
					echo '<h3>'.$meta_box['title'].'</h3>';
					wp_editor($meta_box_value, $meta_box['name'], $editor_settings);
					echo $form_el_end;
				break;

				case 'ntfacts':
					echo $form_el_start;
					$nutritionsfacts = $this->get_nutritions(); ?>
					<h3><?php echo $meta_box['title']; ?></h3>
					<ul id="nutritions">
						<?php foreach ($nutritionsfacts as $key => $trans) { ?>
						<li>
							<label for="<?php echo $key; ?>"><?php echo $trans; ?></label>
							<input id="<?php echo $key; ?>" type="text" name="<?php echo $meta_box['name']; ?>[<?php echo $key; ?>]"  value="<?php echo (!empty($meta_box_value[$key])) ? $meta_box_value[$key] : ''; ?>">
						</li>
						<?php } ?>
					</ul>
					<?php echo $form_el_end;
				break;

				case 'ingridients':
					echo $form_el_start;
					?>
					<h3><?php echo $meta_box['title']; ?></h3>
					<table id="ingridients-sort" class="widefat">
						<thead>
							<tr>
								<th width="25"></th>
								<th><?php _e("Name of ingriedient", 'foodiepress'); ?></th>
								<th><?php _e("Notes (quantity, additional info)", 'foodiepress'); ?></th>
								<th><?php _e("Actions", 'foodiepress'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($meta_box_value)) {
								$i=0; while ($i < 8 ) { ?>
								<tr class="ingridients-cont ing">
									<td><a title="Drag and drop rows to sort table" href="#" class="move"><?php _e("move", 'foodiepress'); ?></a></td>
									<td><input name="<?php echo $meta_box['name']; ?>_name[]" type="text" class="ingridient" value="" /> </td>
									<td><input name="<?php echo $meta_box['name']; ?>_note[]" type="text" class="notes"  value="" /></td>
									<td class="action">
										<a  title="Delete ingridient" href="#" class="delete"><?php _e("Delete", 'foodiepress'); ?></a>
									</td>
								</tr>
								<?php $i++; }
							} else if(is_array($meta_box_value)) {
								foreach ($meta_box_value as $k => $meta_box_value) {
									if ($meta_box_value['note']=='separator') { ?>
									<tr class="ingridients-cont separator">
										<td><a title="Drag and drop rows to sort table" href="#" class="move">move</a></td>
										<td><input name="cookingpressingridients_name[]" type="text" class="ingridient"  value="<?php echo $meta_box_value['name']; ?>" /></td>
										<td><input name="cookingpressingridients_note[]" type="text" class="notes"  value="separator" /></td>
										<td class="action">
											<a  title="Delete Separator" href="#" class="delete" ><?php _e("Delete", 'foodiepress'); ?></a>
										</td>
									</tr>
									<?php } else { ?>
									<tr class="ingridients-cont ing">
										<td><a title="Drag and drop rows to sort table" href="#" class="move">move</a></td>
										<td><input name="<?php echo $meta_box['name']; ?>_name[]" type="text" class="ingridient"  value="<?php echo $meta_box_value['name']; ?>" /></td>
										<td><input name="<?php echo $meta_box['name']; ?>_note[]" type="text" class="notes"  value="<?php echo $meta_box_value['note']; ?>" /></td>
										<td class="action">
											<a  title="Delete ingridient" href="#" class="delete"><?php _e("Delete", 'foodiepress'); ?></a>
										</td>
									</tr>
									<?php }
								}
							} //eof else
							?>
						</tbody>
					</table>

					<div id="recipe-ingredients-action">
						<a href="#" class="add_ingridient button button-primary"><?php _e("Add new ingridient", 'foodiepress'); ?></a>
						<a href="#" class="add_separator button "><?php _e("Add separator", 'foodiepress'); ?></a>
					</div>
					<?php
					echo $form_el_end;
			break;

		default:
								# code...
		break;
	}
}
}


	/**
	 * Adds the meta box container.
	 *
	 * @since    1.0.0
	 */
	public function save_recipe_meta_boxes($post_id) {

 			/*
			 * We need to verify this came from the our screen and with proper authorization,
			 * because save_post can be triggered at other times.
			 */

			// Check if our nonce is set.
 			if ( ! isset( $_POST['foodiepress_inner_custom_box_nonce'] ) )
 				return $post_id;

 			$nonce = $_POST['foodiepress_inner_custom_box_nonce'];

			// Verify that the nonce is valid.
 			if ( ! wp_verify_nonce( $nonce, 'foodiepress_inner_custom_box' ) )
 				return $post_id;

			// If this is an autosave, our form has not been submitted,
	        //     so we don't want to do anything.
 			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
 				return $post_id;

			// Check the user's permissions.
 			if ( 'page' == $_POST['post_type'] ) {

 				if ( ! current_user_can( 'edit_page', $post_id ) )
 					return $post_id;

 			} else {

 				if ( ! current_user_can( 'edit_post', $post_id ) )
 					return $post_id;
 			}

 			/* OK, its safe for us to save the data now. */

 			$recipe_meta_boxes = $this->get_recipe_meta_array();
 			$new_arr = array();
 			$data = '';
 			foreach ($recipe_meta_boxes as $meta_box) {
 				if ($meta_box['type'] == "ingridients") {
 					foreach ($_POST[$meta_box['name'] . '_name'] as $k => $v) {
 						if(!empty($v)) {
 							$new_arr[] = array(
 								'name' => $v,
 								'note' => $_POST[$meta_box['name'] . '_note'][$k],
 								);
 						}
 					}
 					$data = $new_arr;
 				} else if ($meta_box['type'] == "recipeoptions") {
 					$data = array(
 						$_POST[$meta_box['name'] . '_preptime'],
 						$_POST[$meta_box['name'] . '_cooktime'],
 						$_POST[$meta_box['name'] . '_yield'],
 						);
 				} else {
 					$data = $_POST[$meta_box['name']];
 				}
 				update_post_meta($post_id, $meta_box['name'], $data);
 			}
		}


		function save_taxonomy_data($post_id) {

		// verify this came from our screen and with proper authorization.
			if ( ! isset( $_POST['foodiepress_inner_custom_box_nonce'] ) )
				return $post_id;

			$nonce = $_POST['foodiepress_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'foodiepress_inner_custom_box' ) )
				return $post_id;

	    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return $post_id;

	    // Check permissions
			if ('page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id))
					return $post_id;
			} else {
				if (!current_user_can('edit_post', $post_id))
					return $post_id;
			}

	    // OK, we're authenticated: we need to find and save the data
			$post = get_post($post_id);
			if (($post->post_type == 'post') || ($post->post_type == 'page') || ($post->post_type == 'recipe')) {
	        // OR $post->post_type != 'revision'

				$tags = array();
				foreach ($_POST['cookingpressingridients_name'] as $k => $v) {
					if($_POST['cookingpressingridients_note'][$k] != 'separator')  {
						wp_insert_term($v, 'ingredients');
	                //if(term_exists($v, 'post_tag'))
						array_push($tags, $v);
					}
				}
				
				$sttags = $_POST['tax_input']['ingredients'];
/*				if(is_array($sttags)) {
					foreach ($sttags as  $v) {
						wp_insert_term($v, 'ingredients');
						array_push($tags, $v);
					}
				}*/
				wp_set_post_terms($post_id, $tags, 'ingredients');
				
				return $serving;
			}
		}

	/**
	 * Returns
	 *
	 * @since    1.0.0
	 */

	public function get_terms_array( $term ) {
		$temp_terms = get_terms( $term, array( "hide_empty" => 0 ));
		$count = count($temp_terms);
		$terms = array();
		if ( $count > 0 ){
			foreach ( $temp_terms as $term ) {
				$terms[$term->slug] = $term->name;

			}
		}
		return $terms;
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		$pluginpath = plugin_dir_path( __FILE__ );
		$mainpath = preg_replace('/public.$/', '', $pluginpath);
		load_textdomain( $domain,  $mainpath . '/languages/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
		global $post;
		$shortname = 'cookingpress';
		if(is_singular()) {
			$recipestyle = get_post_meta($post->ID, $shortname.'recipetheme', true);
			if(!empty($recipestyle)) {

				$recipethemes = apply_filters( 'foodiepress_themes', array());
				$origin = $recipethemes[$recipestyle]['origin'];
				if($origin == 'core') {
					wp_enqueue_style( $this->plugin_slug . '-recipe-'.$recipestyle.'-styles', plugins_url( 'templates/'.$recipestyle.'/style.css', __FILE__ ), array(), self::VERSION );
				} else {
					$upload_dir = wp_upload_dir();
					wp_enqueue_style( $this->plugin_slug . '-recipe-'.$recipestyle.'-styles',  $upload_dir['baseurl'].'/foodiepress/'.$recipestyle.'/style.css', array(), self::VERSION );
				}
			}
		} else {
			$recipethemes = apply_filters( 'foodiepress_themes', array());
			foreach ($recipethemes as $recipestyle => $value) {
				wp_enqueue_style( $this->plugin_slug . '-recipe-'.$recipestyle.'-styles', plugins_url( 'templates/'.$recipestyle.'/style.css', __FILE__ ), array(), self::VERSION );
			}
		}

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		$options = get_option( 'chow_option',array());
		if(is_array($options) && empty($options['print'])) {
			wp_enqueue_script( $this->plugin_slug . '-plugin-print', plugins_url( 'assets/js/print.js', __FILE__ ), array(), self::VERSION );
		}
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'foodiepress',
	    array(
	         'ajaxurl'=>admin_url('admin-ajax.php'),
        	 'nonce' => wp_create_nonce('ajax-nonce'),
        	 'addedtolist' => __('Added to Fav List!','foodiepress'),
	        )
	    );
		
	}



	public function purerecipe($atts){
		extract(shortcode_atts(array(
			'id' => ''
			), $atts));
		global $post;
		$shortname = 'cookingpress';
		$recipedata = array();
		if(empty($id)) { $id = get_the_id(); }
		$ingredients = get_post_meta($id, $shortname.'ingridients', true);
		if(!empty($ingredients)) {
			if( !empty($ingredients[0]['name'])) {

				$gettitle = get_post_meta($id, $shortname.'title', true);
				$title = (empty($gettitle)) ? get_the_title($id) : $gettitle;
				$recipedata['title'] = $title;

				$desc = get_post_meta($id, $shortname.'summary', true);
				if(!empty($desc)) {
					$recipedata['desc'] =  wpautop(do_shortcode($desc));
				}

				$recipeoptions = get_post_meta($id, $shortname.'recipeoptions', true);
				if(!empty($recipeoptions)) {
					$preptime = $recipeoptions[0];
					if(!empty($preptime)) {
						$recipedata['preptime'] = $this->convert_minutes($preptime);
						$recipedata['preptimept'] = $this->convert_minutes_to_pt($preptime);
					}
					$cooktime = $recipeoptions[1]; if(!empty($cooktime)) {
						$recipedata['cooktime'] =$this->convert_minutes($cooktime);
						$recipedata['cooktimept'] =$this->convert_minutes_to_pt($cooktime);
					}
					$yield = $recipeoptions[2]; if(!empty($yield)) { $recipedata['yield'] = $yield;	}
				}

				$nutrtion_facts = get_post_meta($id, $shortname.'ntfacts', true);

				if(!empty($nutrtion_facts)) {
					$rennutrtion_facts = array();
					$translatednutritions = $this->get_nutritions();
					foreach ($nutrtion_facts as $key => $value) {
						if(!empty($value)){
							$formattedkey = ucfirst(str_replace('Content', '', $key));
							if( is_numeric($key)) {
								$formattedkeyspace = preg_replace('/(?<!\ )[A-Z]/', ' $0', $formattedkey);
							} else {
								$formattedkeyspace = $translatednutritions[$key];
							}
							$rennutrtion_facts[] = array('nutr'=>$key, 'formnutr'=>$formattedkeyspace, 'fact'=>$value);
						}
					}
					$recipedata['nutrtion_facts'] = $rennutrtion_facts;
				}

				$reviews = $this->get_average_post_rating("");
				
				if(!empty($reviews)) {

					$recipedata['rating'] = $reviews['rating'];
    				$recipedata['rating_nr'] = $reviews['reviews'];
    			}
    			//if(function_exists('the_ratings'))

				$ingredients_arr = array();
				foreach ($ingredients as $ingredient) {
					
					//$tag = get_term_by('name',);
					$tagurl = get_term_link($ingredient['name'],'ingredients');
					if ( is_wp_error($tagurl) ) {
						$tagurl = '';
					}
					$ingredients_arr[] = array(
						'name' => $ingredient['name'],
						'note' => $ingredient['note'],
						'url' => $tagurl
						);
				}
				$recipedata['ingredients'] = $ingredients_arr;

				$instructions = get_post_meta( $id, 'cookingpressinstructions', true );
				
				if(!empty($instructions)) {
					$recipedata['instructions'] = wpautop(do_shortcode($instructions));
				} else {
					$recipedata['instructions'] = '';
				}

				$author = get_the_author();
				if(!empty($author)) {
					$recipedata['author'] = $author;
					$recipedata['authorurl'] = get_author_posts_url( get_the_author_meta( 'ID' ) );
				}

				$allergens =  get_the_term_list( $id, 'allergen', ' ', ', ', ' ' );
				if(!empty($allergens)) {
					$recipedata['allergens'] = $allergens;
				}

				$level =  get_the_term_list( $id, 'level', ' ', ', ', ' ' );
				if(!empty($level)) {
					$recipedata['level'] = $level;
				}

				$serving =  get_the_term_list( $id, 'serving', ' ', ', ', ' ' );
				if(!empty($serving)) {
					$recipedata['serving'] = $serving;
				}


				$cpthumb =  get_post_meta($id, $shortname.'photo', true);
				if(!empty($cpthumb)) {
					$large_image_url = wp_get_attachment_image_src( $cpthumb, 'large');
					$photo = $large_image_url[0];
					$thumb_url = wp_get_attachment_image_src( $cpthumb, 'thumbnail');
					$thumb = $thumb_url[0];
					$recipedata['photo'] = $photo;
					$recipedata['thumb'] = $thumb;

				} elseif(has_post_thumbnail()) {
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'large');
					$photo = $large_image_url[0];

					$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'thumbnail');
					$thumb = $thumb_url[0];

					$recipedata['photo'] = $photo;
					$recipedata['thumb'] = $thumb;
				} else {
					$recipedata['photo'] = '';
					$recipedata['thumb'] = '';
				}

				$recipedata['lang']  = array(
					'by' => __('By','foodiepress'),
					'preptime' => __('Prep Time','foodiepress'),
					'cooktime' => __('Cook Time','foodiepress'),
					'yield' => __('Yield','foodiepress'),
					'serving' => __('Serving','foodiepress'),
					'allergens' => __('Allergens','foodiepress'),
					'ntfacts' => __('Nutrition facts <span>(per portion)</span>','foodiepress'),
					'ingredients' => __('Ingredients','foodiepress'),
					'instructions' => __('Instructions','foodiepress'),
					'rating' => __('Rating','foodiepress'),
					'stars' => __('stars - based on','foodiepress'),
					'review' => __(' review(s)','foodiepress'),
					);
				$recipedata['date'] = get_the_date();
				$recipedata['dateformated'] = get_the_date('Y-m-d');
				$recipedata['style'] = get_post_meta($id, $shortname.'recipetheme', true);
				
				$recipestyle = $recipedata['style'];
				if(empty($recipestyle)) { $recipestyle = 'recipe1';}

				$options = get_option( 'chow_option',array());
				if(is_array($options) && !empty($options['print'])) {
					$recipedata['print'] =  '<a href="#" class="print print-simple"><i class="fa fa-print"></i>'.__('Print','foodiepress').'</a>';
				} else {
					$recipedata['print'] =  '<a href="http://www.printfriendly.com/print?url='.esc_url(get_permalink()).'" rel="nofollow" onclick="window.print(); return false;" class="print print-friendly">  <i class="fa fa-print"></i> '.__('Print','foodiepress').'</a>';
				}
				$recipethemes = apply_filters( 'foodiepress_themes', array());
				
				$origin = $recipethemes[$recipestyle]['origin'];
				if(empty($origin)) { $origin = 'core'; }

				//echo "<pre>";print_r($recipedata); echo "</pre>";die();

				$dwoo = new Dwoo();
				if($origin == 'core') {
				$tpl = new Dwoo_Template_File(dirname(__FILE__).'/templates/'.$recipestyle.'/index.tpl');
				} else {
					$upload_dir = wp_upload_dir();
					$tpl = new Dwoo_Template_File( $upload_dir['basedir'].'/foodiepress/'.$recipestyle.'/index.tpl');
				}
				return $dwoo->get($tpl, $recipedata);
			}
		}
	}

	
	function convert_minutes($minutes) {
		if($minutes > 1440) {
			$d = floor ($minutes / 1440);
			$h = floor (($minutes - $d * 1440) / 60);
			$m = $minutes - ($d * 1440) - ($h * 60);
			return $d.__('day ','foodiepress').$h.__(' h ','foodiepress').$m.__(' min','foodiepress');
		} else if($minutes > 60) {
			return sprintf(__('%dh %02d min ','foodiepress'), floor($minutes/60), $minutes%60);
		} else {
			return $minutes.''.__(" minutes", 'foodiepress');
		}
	}

	function convert_minutes_to_pt($minutes) {
		if($minutes > 1440) {
			$d = floor ($minutes / 1440);
			$h = floor (($minutes - $d * 1440) / 60);
			$m = $minutes - ($d * 1440) - ($h * 60);
			return 'PT'.$d.'D'.$h.'H'.$m.'M';
		} else if($minutes > 60) {
			return sprintf("PT%02dH%02dM", floor($minutes/60), $minutes%60);
		} else {
			return 'PT'.$minutes.'M';
		}
	}

	function default_cp_themes( $themes ){
		$themes = array(
			'recipe1' => array(
				'name'	 => 'Basic Style 1',
				'origin' => 'core'
				),
			'recipe2' => array(
				'name'	 => 'Basic Style 2',
				'origin' => 'core'
				),
			'tearedh' => array(
				'name'	 =>'Torn paper 1',
				'origin' => 'core'
				),
			'teared' => array(
				'name'=>'Torn paper 2',
				'origin' => 'core'
				),
			'elegant' => array(
				'name'=>'Elegant',
				'origin' => 'core'
				),
			'minimal' => array(
				'name'=>'Minimal',
				'origin' => 'core'
				),
			);

		return $themes;
	}


	function force_recipe_shortcode($content) {
		global $post;
		$options = get_option( 'chow_option',array());
		if( ! $post instanceof WP_Post ) return $content;
		if(is_single()){
			if(is_array($options) && !empty($options['force'])) {
				if( has_shortcode( $post->post_content, 'purerecipe') || has_shortcode( $post->post_content, 'foodiepress') ) {
					return $content;
				} else {
					if(!empty($options['place'])) {
						switch ($options['place']) {
							case 'before':
								return '[foodiepress]'.$content;
								break;
							case 'after':
								return $content.'[foodiepress]';
								break;
							
							default:
								return '[foodiepress]'.$content;
								break;
						}
					} else {
						return '[foodiepress]'.$content;
					}
				}
			} else {
				return $content;
			}
		} else {
			return $content;
		}

	}


	public function foodiepress_photo_update() {

		if ( !empty( $_POST['id'] ) )  {
			$return = wp_get_attachment_image( $_POST['id'], 'recipe-thumb', false, array( 'class' => "foodiepress-photo" ));
			echo $return;
		}
		die();
	}

	public function foodiepress_dismiss_notice(){
		add_option('foodiepress-notice-dismissed',"saved");
		
		die();
	}

	/*
	* Comments rating functionality
	*/
	public function rate_post_rating_field () {	?>
	<p class="comment-form-rating foodiepress-comment-field">
		<label><?php _e('Rating:','foodiepress') ?></label>
		<span class="rate">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span id="star'.$i.'" class="star"></span>';
			}?>
		</span>
		<input type="hidden" name="foodiepress-post-rating" id="foodiepress-post-rating" value="0">
	</p>
	<div class="clearfix"></div>

	<?php }



	public function save_comment_meta_data( $comment_id ) {
			global $post;

			if ( ( isset( $_POST['foodiepress-post-rating'] ) ) && ( $_POST['foodiepress-post-rating'] != '0') )
			$rating = wp_filter_nohtml_kses($_POST['foodiepress-post-rating']);
			add_comment_meta( $comment_id, 'foodiepress-rating', $rating );

			$commentdata = get_comment($comment_id, ARRAY_A); 
			$parent_post = get_post($commentdata['comment_post_ID']);
			$reviews = $this->get_average_post_rating($parent_post->ID);
			update_post_meta( $parent_post->ID, 'foodiepress-avg-rating', $reviews['rating']);
	}


	public function modify_comment( $text ){
	  if( $commentrating = get_comment_meta( get_comment_ID(), 'foodiepress-rating', true ) ) {
	  	$value = $this->get_rating_class($commentrating);
	    $commenttext = '<div class="rating '.$value.'">
	                <div class="star-rating"></div>
	                <div class="star-bg"></div>
	            </div>';

	    $text = $text . $commenttext;
	    return $text;
	  } else {
	    return $text;
	  }
	}

	private function get_rating_class($average) {
	$average = round($average, 0, PHP_ROUND_HALF_UP);
	switch ($average) {
		case $average >= 1 and $average < 1.5:
			$class="one-stars";
			break;
		case $average >= 1.5 and $average < 2:
			$class="one-and-half-stars";
			break;
		case $average >= 2 and $average < 2.5:
			$class="two-stars";
			break;
		case $average >= 2.5 and $average < 3:
			$class="two-and-half-stars";
			break;
		case $average >= 3 and $average < 3.5:
			$class="three-stars";
			break;
		case $average >= 3.5 and $average < 4:
			$class="three-and-half-stars";
			break;
		case $average >= 4 and $average < 4.5:
			$class="four-stars";
			break;
		case $average >= 4.5 and $average < 5:
			$class="four-and-half-stars";
			break;
		case $average >= 5:
			$class="five-stars";
			break;
		default:
			$class="no-rating";
			break;
	}
	return $class;
	}

	public function get_average_post_rating($id){
		
		global $post;
		
		$overall_ratings = 0;
		$count_ratings = 0;

		if(empty($id)){
			$args = array(
				'post_id' => $post->ID,
				'status' => 'approve',
				'meta_key' => 'foodiepress-rating'
			);
		} else {
			$args = array(
				'post_id' => $id,
				'status' => 'approve',
				'meta_key' => 'foodiepress-rating'
			);
		}

		$ratings = get_comments( $args );
		$count_ratings = 0;
		foreach ( $ratings as $rating ) {
			$rating_value = get_comment_meta( $rating->comment_ID, 'foodiepress-rating', true );
			if($rating_value > 0 ) {
				$overall_ratings += $rating_value;
				$count_ratings++;
			}
		}

		if ( $overall_ratings == 0 || $count_ratings == 0 ) {
			return 0;
		} else {
			$average_count = $overall_ratings / $count_ratings ;
			//$average_count = round($average_count, 0, PHP_ROUND_HALF_UP);
			$reviews = array(
				'reviews' => $count_ratings,
				'rating' => $average_count
				);

			return $reviews;
		}
	}

	public function recipe_rating(){
		$reviews = $this->get_average_post_rating($id="");
		if(!empty($reviews)){
		$class = $this->get_rating_class($reviews['rating']);
		echo '
		<div class="rating '.$class.'">
			<div class="star-rating"></div>
			<div class="star-bg"></div>
		</div>';

	

		}
	}

	public function recipe_reviews(){
		$more = false;
		$reviews = $this->get_average_post_rating($id="");
		if(!empty($reviews)){
			$number = $reviews['reviews'];
			if ( $number > 1 ) {
	                $output = str_replace( '%', number_format_i18n( $number ), ( false === $more ) ? __( '(% reviews)','foodiepress') : $more );
	        } elseif ( $number == 0 ) {
	                $output =  __( '(No Reviews)','foodiepress' );
	        } else { // must be one
	                $output =  __( '(1 review)','foodiepress' );
	        }
			echo '<span class="reviews-meta"><a href="#reviews">'. $output.'</a></span>';
		}
	}


	public function recipe_rating_comment_add_meta_box() {
	    add_meta_box( 'title', __( 'Rating', 'foodiepress' ), array( $this, 'recipe_rating_meta_box' ), 'comment', 'normal', 'high' );
	}

	public function recipe_rating_meta_box( $comment )	{
	    $rating = get_comment_meta( $comment->comment_ID, 'foodiepress-rating', true );
	    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
	    ?>
	    <p>
	        <label for="rating"><?php _e( 'Rating', 'foodiepress' ); ?>:</label>
			<span class="commentratingbox">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span class="commentrating"><input type="radio" name="foodiepress-rating" id="foodiepress-rating" value="'. $i .'"';
				if ( $rating == $i ) echo ' checked="checked"';
				echo ' />'. $i .' </span>';
				}
			?>
			</span>
	    </p>
	    <?php
	}

	public function recipe_rating_edit_rating( $comment_id ) {
	    if( !isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) {
	    	return;
	    }

	    if ( ( isset( $_POST['foodiepress-rating'] ) ) && ( $_POST['foodiepress-rating'] != '') )
	    {
			$rating = wp_filter_nohtml_kses( $_POST['foodiepress-rating'] );
			update_comment_meta( $comment_id, 'foodiepress-rating', $rating );

			$comment = get_comment( $comment_id ); 
    		$post_id = $comment->comment_post_ID ;
			$reviews = $this->get_average_post_rating($post_id);
	  		update_post_meta( $post_id, 'foodiepress-avg-rating', $reviews['rating']);
	    }
		else {
			delete_comment_meta( $comment_id, 'foodiepress-rating' );
		}
	}

	

	
}
