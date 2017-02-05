<?php
/**
 * Plugin Name.
 *
 * @package   Plugin_Name_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name_Admin
 * @author  Your Name <email@example.com>
 */
class FoodiePress_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

  	private $options;
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * @TODO:
		 *
		 * - Rename "Plugin_Name" to the name of your initial plugin class
		 *
		 */
		$plugin = FoodiePress::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_foodiepress_settings' ));

		add_action( 'admin_notices',  array( $this, 'cp_update_notice' ) );


		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );


		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		/*add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );*/

	}



	public function cp_update_notice() {

		$option = get_option( 'foodiepress-notice-dismissed' );
		if( empty( $option ) ) {

		echo( get_option( 'foodiepress-notice-dismissed' )) ?>
		    <div class="notice foodiepress-notice notice-warning is-dismissible">
		        <p><?php _e( 'Welcome to FoodiePress 1.3! Since this version you can separate blog posts and recipe posts. You can enable it in <a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'FoodiePress Settings', $this->plugin_slug ) . '</a>. To learn more about it, visit <a href="https://secure.helpscout.net/docs/56fae3819033601d6683dc54/article/56fae3af9033601d6683dc58/">the instructions page</a>', 'foodiepress' ); ?></p>
		        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		    </div>
    <?php
		}
	}


	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || $screen->id == 'post' || $screen->id == 'page' || $screen->id == 'recipe') {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), FoodiePress::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-notice-script', plugins_url( 'assets/js/notice-update.js', __FILE__ ), array( 'jquery' ), FoodiePress::VERSION);

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		$screen = get_current_screen();
		
		if (  $screen->id == 'post' || $screen->id == 'page' || $screen->id == 'recipe' ) {  //$this->plugin_screen_hook_suffix == $screen->id  ||
			$tags = get_terms('ingredients', array('hide_empty' => false));
			$tags_arr = array();
			foreach ($tags as $tag) {
				array_push($tags_arr, $tag->name);
			}
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), FoodiePress::VERSION );
			wp_localize_script($this->plugin_slug . '-admin-script', 'foodiepress_script_vars', array(
				'availabletags' => json_encode($tags_arr),
				'title' => __( 'Choose or Upload an Image', 'prfx-textdomain' ),
				'button' => __( 'Use this image', 'prfx-textdomain' ),
				)
			);
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->options = get_option( 'chow_option' );

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'FoodiePress Options', $this->plugin_slug ),
			__( 'FoodiePress', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
			);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
		 flush_rewrite_rules();
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
				),
			$links
			);

	}

	public function register_foodiepress_settings(){
		add_settings_section(
            'foodiepress_options_section', // ID
            'Basic options', // Title
            array( $this, 'print_section_info' ), // Callback
            $this->plugin_slug // Page
            );

		register_setting(
			'foodiepress_option_group', //option group
			'chow_option'  //option name
		);		
		add_settings_field(
            'force', // ID
            'Force recipe shortcode', // Title
            array( $this, 'force_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );
		add_settings_field(
            'post_type', // ID
            'Enable "Recipe" post type to separate blog and recipes', // Title
            array( $this, 'post_type_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );		
		add_settings_field(
            'recipe_slug', // ID
            'Set "Recipe" URL slug', // Title
            array( $this, 'recipe_slug_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );
		add_settings_field(
            'print', // ID
            'Disable PrintFriendly on recipes', // Title
            array( $this, 'print_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );	
		add_settings_field(
            'ratings', // ID
            'Disable Ratings for posts and comments form', // Title
            array( $this, 'rating_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );		
		add_settings_field(
            'place', // ID
            'Display Recipe box after or before the post content (in case of "force recipe" option)', // Title
            array( $this, 'place_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );		
		add_settings_field(
            'default_style', // ID
            'Choose default style for any new recipe (doesn\'t work for already added recipes)', // Title
            array( $this, 'style_callback' ), // Callback
            $this->plugin_slug, // Page
            'foodiepress_options_section' // Section
            );
	}

	public function print_section_info()
	{
		_e('By default, if you want to display Recipe box in your post,
		 you need to add [foodiepress] shortcode to content. If you\'ll check the checkbox below,
		 FoodiePress will automatically add it to the end of each post that has recipe','foodiepress');
	}

	public function force_callback()
    {

    	$checkbox = isset( $this->options['force'] ) ? esc_attr( $this->options['force']) : '';
        printf(
            '<input type="checkbox" id="force" name="chow_option[force]" value="1" %s />',
            checked( 1, $checkbox, false )
        );
    }	
    
	public function post_type_callback()
    {

    	$checkbox = isset( $this->options['post_type'] ) ? esc_attr( $this->options['post_type']) : '';
        printf(
            '<input type="checkbox" id="post_type" name="chow_option[post_type]" value="1" %s />',
            checked( 1, $checkbox, false )
        );
    }	
    public function print_callback()
    {

    	$checkbox = isset( $this->options['print'] ) ? esc_attr( $this->options['print']) : '';
        printf(
            '<input type="checkbox" id="print" name="chow_option[print]" value="1" %s />',
            checked( 1, $checkbox, false )
        );
    }    
    public function rating_callback()
    {

    	$checkbox = isset( $this->options['ratings'] ) ? esc_attr( $this->options['ratings']) : '';
        printf(
            '<input type="checkbox" id="ratings" name="chow_option[ratings]" value="1" %s />',
            checked( 1, $checkbox, false )
        );
    }    

    public function place_callback()
    {

    	$option = isset( $this->options['place'] ) ? esc_attr( $this->options['place']) : '';
    	echo '<select name="chow_option[place]">';
    	echo '<option '.selected($option, 'before').' value="before">Before the content</option>';
    	echo '<option '.selected($option, 'after').' value="after">After the content</option>';
    	echo '</select>';
       
    }    

    public function style_callback()
    {

    	$option = isset( $this->options['default_style'] ) ? esc_attr( $this->options['default_style']) : '';
    	echo '<select name="chow_option[default_style]">';
    	echo '<option '.selected($option, 'recipe1').' value="recipe1">Basic Style 1</option>';
    	echo '<option '.selected($option, 'recipe2').' value="recipe2">Basic Style 2</option>';
    	echo '</select>';
       
    }    
    public function recipe_slug_callback()
    {

    	$option = isset( $this->options['recipe_slug'] ) ? esc_attr( $this->options['recipe_slug']) : 'recipe';
    	echo '<input type="text" name="chow_option[recipe_slug]" value="'.$option.'">';
    
       
    }

}
