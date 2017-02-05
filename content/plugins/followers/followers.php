<?php
/**
 * Plugin Name: Followers
 * Plugin URI: http://themeforest.net/user/purethemes
 * Description: Hey there! I'm your new social icons plugin.
 * Version: 1.3
 * Author: Matty
 * Author URI: http://themeforest.net/user/purethemes
 * Requires at least: 4.0.0
 * Tested up to: 4.0.0
 *
 * Text Domain: followers
 * Domain Path: /languages/
 *
 * @package Followers
 * @category Core
 * @author Matty
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Followers to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Followers
 */
function Followers() {
	return Followers::instance();
} // End Followers()

Followers();

/**
 * Main Followers Class
 *
 * @class Followers
 * @version	1.0.0
 * @since 1.0.0
 * @package	Followers
 * @author Matty
 */
final class Followers {
	/**
	 * Followers The single instance of Followers.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	/**
	 * The services list.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $services;
	// Admin - End

	// Post Types - Start
	/**
	 * The post types we're registering.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $post_types = array();
	// Post Types - End
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct () {
		$this->token 			= 'followers';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';
		$this->services 		= array(
									'facebook'	 	=> array('fans' => 'Fans', 'like' => 'Like' ),
									'twitter'  		=> array('fans' => 'Followers', 'like' => 'Follow' ),
									'google-plus'	=> array('fans' => 'Fans', 'like' => '+1' ),
									'behance'		=> array('fans' => 'Followers', 'like' => 'Follow' ),
									'instagram'		=> array('fans' => 'Followers', 'like' => 'Follow' ),
									'dribbble'		=> array('fans' => 'Fans', 'like' => 'Like' ),
									'linkedin'		=> array('fans' => 'Shares', 'like' => 'Like' ),
									'github'		=> array('fans' => 'Followers', 'like' => 'Follow' ),
									'youtube'		=> array('fans' => 'Fans', 'like' => 'Like' ),
									'pinterest'		=> array('fans' => 'Fans', 'like' => 'Like' ),
								 );

		// Admin - Start
		require_once( 'classes/class-followers-settings.php' );
		$this->settings = Followers_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-followers-admin.php' );
			$this->admin = Followers_Admin::instance();
		}
		// Admin - End

		// Widget
		require_once( 'classes/class-followers-widget.php' );

		// Counters
		require_once( 'classes/class-followers-counters.php' );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action('admin_init', array($this, 'admin_init'));
		add_action('widgets_init',  array( $this, 'register_widget' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	} // End __construct()

	/**
	 * Main Followers Instance
	 *
	 * Ensures only one instance of Followers is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Followers()
	 * @return Main Followers instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()


	function admin_init() {
        // js
      
        global $pagenow;
        if ($pagenow == 'widgets.php') {
            wp_enqueue_script( $this->token .'-custom', plugins_url('public/js/custom.js', __FILE__), false, $this->version, false );
            wp_enqueue_style( $this->token . '-plugin-styles', plugins_url( 'public/css/style.css', __FILE__ ), array(), $this->version );
        }
    }

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'followers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()


	/**
	 * Register widget.
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'FollowersWidget' );
	}


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()


	public function get_services_list() {
		echo $this->plugin_url;
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()



	public function enqueue_styles() {
		wp_enqueue_style( $this->token . '-plugin-styles', plugins_url( 'public/css/followers.css', __FILE__ ), array(), $this->version );
	}	




} // End Class



/**
* Social icons shortcodes
*
*/
if ( ! function_exists( 'pt_followers_shortcode' ) ) :
    function pt_followers_shortcode($atts) {
        extract(shortcode_atts(array(
        	"icons" => '', // facebook,twitter,google-plus,instagram,dribbble,linkedin,github,youtube,pinterest
            "target" => '',
            ), $atts));

    		$output = ' <ul class="pt-followers-icons share-buttons">';
            
            $services_array = explode(',', $icons);
            $services_names = Followers()->services; 

            foreach ($services_array as $key) {
                $key = trim($key);
                $name = str_replace('-', '_', $key);
                if(isset($options[$name.'_url'])) {
                    $url = $options[$name.'_url'];
                } else {
                    $url = '#';
                }
                $method = $name.'_counter';  
                 $output .= '<li class="'.$key.'-share">
                    <a target="'.$target.'" href="'.esc_url($url).'">
                        <span class="counter"> '.FollowersCounters::$method().'</span>
                        <span class="counted"> '.$services_names[$key]['fans'].'</span>
                        <span class="action-button"> '.$services_names[$key]['like'].'</span>
                    </a>
                </li>';
            	}
        $output .= '</ul>';
        return $output;
    }
    add_shortcode('followers', 'pt_followers_shortcode');
endif;

/*if ( ! function_exists( 'wfsi_social_icons' ) ) :
    function wfsi_social_icons($atts,$content ) {
        extract(shortcode_atts(
            array(
                'title'=>"Social Icons"
                ), $atts));

        $output = '<ul class="ptwsi_social-icons ptwsi">'.do_shortcode( $content ).'</ul>';
        return $output;
    }
    add_shortcode('pt_social_icons', 'wfsi_social_icons');
endif;*/

?>
