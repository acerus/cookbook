<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Followers_Settings Class
 *
 * @class Followers_Settings
 * @version	1.0.0
 * @since 1.0.0
 * @package	Followers
 * @author Jeffikus
 */
final class Followers_Settings {
	/**
	 * Followers_Admin The single instance of Followers_Admin.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Whether or not a 'select' field is present.
	 * @var     boolean
	 * @access  private
	 * @since   1.0.0
	 */
	private $_has_select;

	/**
	 * Main Followers_Settings Instance
	 *
	 * Ensures only one instance of Followers_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main Followers_Settings instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct () {
	} // End __construct()

	/**
	 * Validate the settings.
	 * @access  public
	 * @since   1.0.0
	 * @param   array $input Inputted data.
	 * @param   string $section field section.
	 * @return  array        Validated data.
	 */
	public function validate_settings ( $input, $section ) {
		if ( is_array( $input ) && 0 < count( $input ) ) {
			$fields = $this->get_settings_fields( $section );

			foreach ( $input as $k => $v ) {
				if ( ! isset( $fields[$k] ) ) {
					continue;
				}

				// Determine if a method is available for validating this field.
				$method = 'validate_field_' . $fields[$k]['type'];

				if ( ! method_exists( $this, $method ) ) {
					if ( true == (bool)apply_filters( 'followers-validate-field-' . $fields[$k]['type'] . '_use_default', true ) ) {
						$method = 'validate_field_text';
					} else {
						$method = '';
					}
				}

				// If we have an internal method for validation, filter and apply it.
				if ( '' != $method ) {
					add_filter( 'followers-validate-field-' . $fields[$k]['type'], array( $this, $method ) );
				}

				$method_output = apply_filters( 'followers-validate-field-' . $fields[$k]['type'], $v, $fields[$k] );

				if ( is_wp_error( $method_output ) ) {
					// if ( defined( 'WP_DEBUG' ) || true == constant( 'WP_DEBUG' ) ) print_r( $method_output ); // Add better error display.
				} else {
					$input[$k] = $method_output;
				}
			}
		}
		return $input;
	} // End validate_settings()

	/**
	 * Validate the given data, assuming it is from a text input field.
	 * @access  public
	 * @since   6.0.0
	 * @return  void
	 */
	public function validate_field_text ( $v ) {
		return (string)wp_kses_post( $v );
	} // End validate_field_text()

	/**
	 * Validate the given data, assuming it is from a textarea field.
	 * @access  public
	 * @since   6.0.0
	 * @return  void
	 */
	public function validate_field_textarea ( $v ) {
		// Allow iframe, object and embed tags in textarea fields.
		$allowed 			= wp_kses_allowed_html( 'post' );
		$allowed['iframe'] 	= array(
								'src' 		=> true,
								'width' 	=> true,
								'height' 	=> true,
								'id' 		=> true,
								'class' 	=> true,
								'name' 		=> true
								);
		$allowed['object'] 	= array(
								'src' 		=> true,
								'width' 	=> true,
								'height' 	=> true,
								'id' 		=> true,
								'class' 	=> true,
								'name' 		=> true
								);
		$allowed['embed'] 	= array(
								'src' 		=> true,
								'width' 	=> true,
								'height' 	=> true,
								'id' 		=> true,
								'class' 	=> true,
								'name' 		=> true
								);

		return wp_kses( $v, $allowed );
	} // End validate_field_textarea()

	/**
	 * Validate the given data, assuming it is from a checkbox input field.
	 * @access public
	 * @since  6.0.0
	 * @param  string $v
	 * @return string
	 */
	public function validate_field_checkbox ( $v ) {
		if ( 'true' != $v ) {
			return 'false';
		} else {
			return 'true';
		}
	} // End validate_field_checkbox()

	/**
	 * Validate the given data, assuming it is from a URL field.
	 * @access public
	 * @since  6.0.0
	 * @param  string $v
	 * @return string
	 */
	public function validate_field_url ( $v ) {
		return trim( esc_url( $v ) );
	} // End validate_field_url()

	/**
	 * Render a field of a given type.
	 * @access  public
	 * @since   1.0.0
	 * @param   array $args The field parameters.
	 * @return  void
	 */
	public function render_field ( $args ) {
		$html = '';
		if ( ! in_array( $args['type'], $this->get_supported_fields() ) ) return ''; // Supported field type sanity check.

		// Make sure we have some kind of default, if the key isn't set.
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = '';
		}

		$method = 'render_field_' . $args['type'];

		if ( ! method_exists( $this, $method ) ) {
			$method = 'render_field_text';
		}

		// Construct the key.
		$key 				= Followers()->token . '-' . $args['section'] . '[' . $args['id'] . ']';
		$method_output 		= $this->$method( $key, $args );

		if ( is_wp_error( $method_output ) ) {
			// if ( defined( 'WP_DEBUG' ) || true == constant( 'WP_DEBUG' ) ) print_r( $method_output ); // Add better error display.
		} else {
			$html .= $method_output;
		}

		// Output the description, if the current field allows it.
		if ( isset( $args['type'] ) && ! in_array( $args['type'], (array)apply_filters( 'wf_no_description_fields', array( 'checkbox' ) ) ) ) {
			if ( isset( $args['description'] ) ) {
				$description = '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>' . "\n";
				if ( in_array( $args['type'], (array)apply_filters( 'wf_newline_description_fields', array( 'textarea', 'select' ) ) ) ) {
					$description = wpautop( $description );
				}
				$html .= $description;
			}
		}

		echo $html;
	} // End render_field()

	/**
	 * Retrieve the settings fields details
	 * @access  public
	 * @since   1.0.0
	 * @return  array        Settings fields.
	 */
	public function get_settings_sections () {
		$settings_sections = array();

		//$settings_sections['general'] = __( 'General', 'followers' );
		$settings_sections['apis'] = __( 'Social Sevices informations', 'followers' );
		// Add your new sections below here.
		// Admin tabs will be created for each section.
		// Don't forget to add fields for the section in the get_settings_fields() function below

		return (array)apply_filters( 'followers-settings-sections', $settings_sections );
	} // End get_settings_sections()

	/**
	 * Retrieve the settings fields details
	 * @access  public
	 * @param  string $section field section.
	 * @since   1.0.0
	 * @return  array        Settings fields.
	 */
	public function get_settings_fields ( $section ) {
		$settings_fields = array();
		// Declare the default settings fields.

		switch ( $section ) {
			
			case 'apis':
				$settings_fields['facebook_id'] = array(
												'name' => __( 'Facebook Page ID', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'You can find it for example here <a href="http://findmyfacebookid.com/">findmyfacebookid.com</a>.', 'followers' )
											);
				$settings_fields['facebook_url'] = array(
												'name' => __( 'Facebook - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['facebook_accesstoken'] = array(
												'name' => __( 'Facebook - access token', 'followers' ),
												'type' => 'text',
												'default' => '',
												'description' => 'Get access token here <a href="https://developers.facebook.com/tools/accesstoken/">developers.facebook.com/tools/accesstoken/</a> (create an app first if you haven\'t)',
												'section' => 'apis',
											);
				$settings_fields['hr0'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['twitter_user'] = array(
												'name' => __( 'Twitter User name', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Just your username without any blank spaces.', 'followers' )
											);
				$settings_fields['twitter_url'] = array(
												'name' => __( 'Twitter - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr1'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['google_api_key'] = array(
												'name' => __( 'Google API key', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Get API key at <a href="https://code.google.com/apis/console?hl=en#access">https://code.google.com/apis</a>.', 'followers' )
											);
				$settings_fields['google_page_id'] = array(
												'name' => __( 'Google Page ID', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'The vanity URL portion with the plus sign (for example +Name) ', 'followers' )
											);
				$settings_fields['google_plus_url'] = array(
												'name' => __( 'Google+ - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr2'] = array( 'type' => 'hr', 'name' => '<hr> ', 'section' => 'apis',);

				$settings_fields['behance_api_key'] = array(
												'name' => __( 'Behance API key', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Get API key at <a href="https://www.behance.net/dev">behance.net/dev</a>.', 'followers' )
											);
				$settings_fields['behance_username'] = array(
												'name' => __( 'Behance Username', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Just your username ', 'followers' )
											);
				$settings_fields['behance_url'] = array(
												'name' => __( 'Behance - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr3'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['instagram_api_key'] = array(
												'name' => __( 'Instagram Access token', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( '<a href="http://jelled.com/instagram/access-token">How to get access token from Instagram</a>', 'followers' )
											);
				$settings_fields['instagram_user_name'] = array(
											'name' => __( 'Instagram User ID', 'followers' ),
											'type' => 'text',
											'default' => '',
											'section' => 'apis',
											'description' => __( '<a href="http://jelled.com/instagram/lookup-user-id">How to get your User ID from Instagram</a>', 'followers' )
										);
				$settings_fields['instagram_url'] = array(
												'name' => __( 'Instagram - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr4'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['dribbble_username'] = array(
												'name' => __( 'Dribbble Username', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Just your username ', 'followers' )
											);
				$settings_fields['dribbble_url'] = array(
												'name' => __( 'Dribbble - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr5'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['linkedin_link'] = array(
												'name' => __( 'URL of article shared on LinkedIn', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'This will show how many times it was shared ', 'followers' )
											);
				/*$settings_fields['linkedin_url'] = array(
												'name' => __( 'Facebook - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);*/
				$settings_fields['hr6'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['github_username'] = array(
												'name' => __( 'Github Username', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Just your username ', 'followers' )
											);
				$settings_fields['github_url'] = array(
												'name' => __( 'Github - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr7'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);

				$settings_fields['youtube_api_key'] = array(
												'name' => __( 'YouTube API key', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Get it on  https://console.developers.google.com/ You may have to start a new project. Navigate to APIs & auth and go to Public API Access -> Create a New Key ', 'followers' )
											);
				$settings_fields['youtube_channel_id'] = array(
												'name' => __( 'YouTube Channel ID', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => 'Navigate to your channel in YouTube and look at the URL. The channel ID is here: https://www.youtube.com/channel/YOUR_CHANNEL_ID'
											);
				$settings_fields['youtube_url'] = array(
												'name' => __( 'YouTube - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr8'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);
				$settings_fields['pinterest_username'] = array(
												'name' => __( 'Pinterest Username', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
												'description' => __( 'Just your username ', 'followers' )
											);
				$settings_fields['pinterest_url'] = array(
												'name' => __( 'Pinterest - link to profile', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
											);
				$settings_fields['hr9'] = array( 'type' => 'hr', 'name' => '<hr>', 'section' => 'apis',);
                                
                $settings_fields['vkontakte_groupid'] = array(
                                                'name' => __('VKonakte - your group ID', 'followers'),
                                                'type' => 'text',
                                                'default' => '',
                                                'section' => 'apis',
                                                'description' => __( 'Group ID', 'followers' )
                                    		);
                
                $settings_fields['vkontakte_url'] = array(
												'name' => __( 'VKontakte - link to group', 'followers' ),
												'type' => 'text',
												'default' => '',
												'section' => 'apis',
                                                'description' => __( 'VKontakte - link to group', 'followers' )
                                            );

			break;
			
			default:
				# code...
				break;
		}

		return (array)apply_filters( 'followers-settings-fields', $settings_fields );
	} // End get_settings_fields()

	/**
	 * Render HTML markup for the "hr" field type.
	 * @access  protected
	 * @since   6.0.0
	 */
	protected function render_field_hr ( $key, $args ) {
		$html = '<hr>' . "\n";
		return $html;
	} // End render_field_hr()


	/**
	 * Render HTML markup for the "text" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_text ( $key, $args ) {
		$html = '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" size="40" type="text" value="' . esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ) . '" />' . "\n";
		return $html;
	} // End render_field_text()

	/**
	 * Render HTML markup for the "radio" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_radio ( $key, $args ) {
		$html = '';
		if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
			$html = '';
			foreach ( $args['options'] as $k => $v ) {
				$html .= '<input type="radio" name="' . esc_attr( $key ) . '" value="' . esc_attr( $k ) . '"' . checked( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), $k, false ) . ' /> ' . esc_html( $v ) . '<br />' . "\n";
			}
		}
		return $html;
	} // End render_field_radio()

	/**
	 * Render HTML markup for the "textarea" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_textarea ( $key, $args ) {
		// Explore how best to escape this data, as esc_textarea() strips HTML tags, it seems.
		$html = '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" cols="42" rows="5">' . $this->get_value( $args['id'], $args['default'], $args['section'] ) . '</textarea>' . "\n";
		return $html;
	} // End render_field_textarea()

	/**
	 * Render HTML markup for the "checkbox" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_checkbox ( $key, $args ) {
		$has_description = false;
		$html = '';
		if ( isset( $args['description'] ) ) {
			$has_description = true;
			$html .= '<label for="' . esc_attr( $key ) . '">' . "\n";
		}
		$html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="checkbox" value="true"' . checked( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), 'true', false ) . ' />' . "\n";
		if ( $has_description ) {
			$html .= wp_kses_post( $args['description'] ) . '</label>' . "\n";
		}
		return $html;
	} // End render_field_checkbox()

	/**
	 * Render HTML markup for the "select2" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_select ( $key, $args ) {
		$this->_has_select = true;

		$html = '';
		if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
			$html .= '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . "\n";
				foreach ( $args['options'] as $k => $v ) {
					$html .= '<option value="' . esc_attr( $k ) . '"' . selected( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), $k, false ) . '>' . esc_html( $v ) . '</option>' . "\n";
				}
			$html .= '</select>' . "\n";
		}
		return $html;
	} // End render_field_select()

	/**
	 * Render HTML markup for the "select_taxonomy" field type.
	 * @access  protected
	 * @since   6.0.0
	 * @param   string $key  The unique ID of this field.
	 * @param   array $args  Arguments used to construct this field.
	 * @return  string       HTML markup for the field.
	 */
	protected function render_field_select_taxonomy ( $key, $args ) {
		$this->_has_select = true;

		$defaults = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 1,
			'child_of'           => 0,
			'exclude'            => '',
			'selected'           => $this->get_value( $args['id'], $args['default'], $args['section'] ),
			'hierarchical'       => 1,
			'class'              => 'postform',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'category',
			'hide_if_empty'      => false,
			'walker'             => ''
        );

		if ( ! isset( $args['options'] ) ) {
			$args['options'] = array();
		}

		$args['options'] 			= wp_parse_args( $args['options'], $defaults );
		$args['options']['echo'] 	= false;
		$args['options']['name'] 	= esc_attr( $key );
		$args['options']['id'] 		= esc_attr( $key );

		$html = '';
		$html .= wp_dropdown_categories( $args['options'] );

		return $html;
	} // End render_field_select_taxonomy()

	/**
	 * Return an array of field types expecting an array value returned.
	 * @access public
	 * @since  1.0.0
	 * @return array
	 */
	public function get_array_field_types () {
		return array();
	} // End get_array_field_types()

	/**
	 * Return an array of field types where no label/header is to be displayed.
	 * @access protected
	 * @since  1.0.0
	 * @return array
	 */
	protected function get_no_label_field_types () {
		return array( 'info' );
	} // End get_no_label_field_types()

	/**
	 * Return a filtered array of supported field types.
	 * @access  public
	 * @since   1.0.0
	 * @return  array Supported field type keys.
	 */
	public function get_supported_fields () {
		return (array)apply_filters( 'followers-supported-fields', array( 'text', 'checkbox', 'radio', 'textarea', 'select', 'select_taxonomy', 'hr' ) );
	} // End get_supported_fields()

	/**
	 * Return a value, using a desired retrieval method.
	 * @access  public
	 * @param  string $key option key.
	 * @param  string $default default value.
	 * @param  string $section field section.
	 * @since   1.0.0
	 * @return  mixed Returned value.
	 */
	public function get_value ( $key, $default, $section ) {
		$values = get_option( 'followers-' . $section, array() );

		if ( is_array( $values ) && isset( $values[$key] ) ) {
			$response = $values[$key];
		} else {
			$response = $default;
		}

		return $response;
	} // End get_value()

	/**
	 * Return all settings keys.
	 * @access  public
	 * @param  string $section field section.
	 * @since   1.0.0
	 * @return  mixed Returned value.
	 */
	public function get_settings ( $section = '' ) {
		$response = false;

		$sections = array_keys( (array)$this->get_settings_sections() );

		if ( in_array( $section, $sections ) ) {
			$sections = array( $section );
		}

		if ( 0 < count( $sections ) ) {
			foreach ( $sections as $k => $v ) {
				$fields = $this->get_settings_fields( $v );
				$values = get_option( 'followers-' . $v, array() );

				if ( is_array( $fields ) && 0 < count( $fields ) ) {
					foreach ( $fields as $i => $j ) {
						// If we have a value stored, use it.
						if ( isset( $values[$i] ) ) {
							$response[$i] = $values[$i];
						} else {
							// Otherwise, check for a default value. If we have one, use it. Otherwise, return an empty string.
							if ( isset( $fields[$i]['default'] ) ) {
								$response[$i] = $fields[$i]['default'];
							} else {
								$response[$i] = '';
							}
						}
					}
				}
			}
		}

		return $response;
	} // End get_settings()
} // End Class
