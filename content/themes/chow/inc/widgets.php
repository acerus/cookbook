<?php
add_action('widgets_init', 'chow_load_widgets'); // Loads widgets here
function chow_load_widgets() {
    register_widget('chow_author');
    register_widget('chow_popular');
    register_widget('foodiepress_favposts');
    //if(class_exists('NS_MC_Plugin')){ register_widget('Chow_NS_Widget_MailChimp'); }
}


class chow_author extends WP_Widget {

  private $users_split_at = 200; //Do not run get_users() if there are more than 200 users on the website
  var $defaults;

  function chow_author() {
    $widget_ops = array( 'classname' => 'chow_author', 'description' => __('Use this widget to display author/user profile info', 'chow') );
    $control_ops = array( 'id_base' => 'chow_author' );
    parent::__construct( 'chow_author', __('Chow Author Widget', 'chow'), $widget_ops, $control_ops );


    $this->defaults = array(
        'title' => __('About Author', 'chow'),
        'author' => 0,
        'single_author' => 0,
        'display_all_posts' => 1,
        'display_email' => 1,
        'link_text' => __('View all recipes', 'chow'),
      );

    //Allow themes or plugins to modify default parameters
    $this->defaults = apply_filters('chow_author_modify_defaults', $this->defaults);

  }

  function widget( $args, $instance ) {

    extract( $args );

    $instance = wp_parse_args( (array) $instance, $this->defaults );

    //Check for user_id
    $user_id = $instance['author'];
    if($instance['single_author']){
      if(is_author()){
        $obj = get_queried_object();
        $user_id = $obj->data->ID;
      } elseif(is_single()){
        $obj = get_queried_object();
        $user_id = $obj->post_author;
      }
    }

    $author_link = get_author_posts_url(get_the_author_meta('ID',$user_id));
    $title =  apply_filters('widget_title', $instance['title'] );

    echo $before_widget;

    ?>
    <div class="author-box">
      <?php echo get_avatar( get_the_author_meta('ID', $user_id), 128 ); ?>
      <span class="title"> <?php if ( !empty($title) ) { echo esc_html($title); } ?></span>
      <span class="name"><?php echo '<a href="'.esc_url($author_link).'">' . get_the_author_meta('display_name', $user_id) . '</a>'; ?></span>

      <?php if($instance['display_email']) : ?>
        <?php $email = get_the_author_meta( 'user_email', $user_id ) ?>
        <a href="mailto:<?php echo esc_url($email); ?>"><span class="contact"><?php echo esc_html($email); ?></span></a>
      <?php endif; ?>
      
      <?php echo wpautop(get_the_author_meta('description',$user_id)); ?>
      <a href="<?php echo esc_url($author_link); ?>" class="author_link"><?php echo esc_html($instance['link_text']); ?></a>
    </div>
    <?php
    echo $after_widget;
  }


  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['author'] = absint( $new_instance['author'] );
    $instance['single_author'] = isset($new_instance['single_author']) ? 1 : 0;
    $instance['display_email'] = isset($new_instance['display_email']) ? 1 : 0;
    $instance['display_all_posts'] = isset($new_instance['display_all_posts']) ? 1 : 0;
    $instance['link_text'] = strip_tags( $new_instance['link_text'] );

    return $instance;
  }

  function form( $instance ) {

    $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'chow'); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
    </p>

    <p>

      <?php if( $this->count_users() <= $this->users_split_at ) : ?>

      <?php $authors = get_users(); ?>
      <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e('Choose author/user', 'chow'); ?>:</label>
      <select name="<?php echo $this->get_field_name( 'author' ); ?>" id="<?php echo $this->get_field_id( 'author' ); ?>" class="widefat">
      <?php foreach($authors as $author) : ?>
        <option value="<?php echo $author->ID; ?>" <?php selected($author->ID, $instance['author']); ?>><?php echo $author->data->user_login; ?></option>
      <?php endforeach; ?>
      </select>

      <?php else: ?>

      <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e('Enter author/user ID', 'chow'); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'author' ); ?>" type="text" name="<?php echo $this->get_field_name( 'author' ); ?>" value="<?php echo esc_attr($instance['author']); ?>" class="small-text" />

      <?php endif; ?>

    </p>

    <p>
      <input id="<?php echo $this->get_field_id( 'single_author' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'single_author' ); ?>" value="1" <?php checked(1, $instance['single_author']); ?>/>
      <label for="<?php echo $this->get_field_id( 'single_author' ); ?>"><?php _e('Automatically detect author', 'chow'); ?></label>
      <small class="howto"><?php _e('Use this option to show author of single post instead of pre-selected author', 'chow'); ?></small>
    </p>
    <h4><?php _e('Display Options', 'chow'); ?></h4>


    <ul>
      <li>
        <input id="<?php echo $this->get_field_id( 'display_email' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_email' ); ?>" value="1" <?php checked(1, $instance['display_email']); ?>/>
        <label for="<?php echo $this->get_field_id( 'display_email' ); ?>"><?php _e('Display email of author in widget', 'chow'); ?></label>
      </li>
      <li>
        <input id="<?php echo $this->get_field_id( 'display_all_posts' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_all_posts' ); ?>" value="1" <?php checked(1, $instance['display_all_posts']); ?>/>
        <label for="<?php echo $this->get_field_id( 'display_all_posts' ); ?>"><?php _e('Display "view all posts" link', 'chow'); ?></label>
      </li>

      <li>
        <label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e('Link text:', 'chow'); ?></label>
        <input id="<?php echo $this->get_field_id( 'link_text' ); ?>" type="text" name="<?php echo $this->get_field_name( 'link_text' ); ?>" value="<?php echo esc_attr($instance['link_text']); ?>" class="widefat"/>
        <small class="howto"><?php _e('Specify text for "all posts" link', 'chow'); ?></small>
      </li>
    </ul>


  <?php
  }

  /* Check total number of users on the website */
  function count_users(){
    $user_count = count_users();
    if(isset($user_count['total_users']) && !empty($user_count['total_users'])){
      return $user_count['total_users'];
    }
    return 0;
  }
}


class chow_popular extends WP_Widget {

    function chow_popular() {
        $widget_ops = array('classname' => 'chow-popular', 'description' => 'Widget to display posts by selected order');
        $control_ops = array('width' => 300, 'height' => 350);
        parent::__construct('chow_popular', 'Chow Popular Posts', $widget_ops, $control_ops);

    $this->defaults = array(
        'title' => __('Popular Recipes', 'chow'),
        'number' => 3
      );
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $number = $instance['number'];
        $type = isset($instance['type']) ? $instance['type'] : 'post' ;
        echo $before_widget . $before_title . $instance['title'] . $after_title;

        ?>
        <?php echo self::showLatest($number, $type); ?>

        <?php echo $after_widget;
    }


function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['number'] = strip_tags($new_instance['number']);
    $instance['type'] = strip_tags($new_instance['type']);
    return $instance;
}

function form($instance) {
    $instance = wp_parse_args((array) $instance, array('title' => ''));
    $title = strip_tags($instance['title']);
    $number = esc_attr($instance['number']);
    ?>
    <br>
     <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo  __('Title :', 'chow'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p><label>Set number of items to display
        <select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>">
            <?php for ($i=1; $i < 10; $i++) { ?>
            <option <?php if ($number == $i) echo 'selected'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </label>
  </p> 
  <p><label>Show posts or recipes:
        <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
           
            <option <?php if ($number == 'post') echo 'selected'; ?> value="post">Posts</option>
            <option <?php if ($number == 'recipe') echo 'selected'; ?> value="recipe">Recipes</option>
            
        </select>
    </label>
  </p>
    <?php
}

/**
     * Display Latest posts
     */
static function showLatest( $posts = 3,$type = 'posts' ) {
    global $post;
 
    $latest = get_posts(
        array(
           
            'meta_key' => 'foodiepress-avg-rating',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_type' => 'NUMERIC',
            'post_type' =>  $type,  
            'posts_per_page' => $posts )
        );



    ob_start();

    $date_format = get_option('date_format');
    foreach($latest as $post) :
        setup_postdata($post);
    ?>
    <a href="<?php the_permalink(); ?>" class="featured-recipe">
      <?php if ( has_post_thumbnail() ) { ?>
        <?php the_post_thumbnail('widgets-thumb');  // 480x163?>
      <?php } ?>

      <div class="featured-recipe-content">
        <h4><?php the_title(); ?></h4>

        <?php do_action('foodiepress-widget-rating'); ?>
      </div>
      <div class="post-icon"></div>
    </a>

    <?php endforeach;
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
}
}



/**
 * @author James Lafferty
 * @since 0.1
 */

class Chow_NS_Widget_MailChimp extends WP_Widget {
    private $default_failure_message;
    private $default_loader_graphic = '/images/loader.gif';
    private $default_signup_text;
    private $default_success_message;
    private $default_title;
    private $successful_signup = false;
    private $subscribe_errors;
    private $chow_ns_mc_plugin;

    /**
     * @author James Lafferty
     * @since 0.1
     */
    public function Chow_NS_Widget_MailChimp () {
        $this->default_failure_message = __('There was a problem processing your submission.','chow');
        $this->default_signup_text = __('Join','chow');
        $this->default_success_message = __('Thank you for joining our mailing list. Please check your email for a confirmation link.','chow');
        $this->default_title = __('Newsletter.','chow');
        $widget_options = array('classname' => 'widget_ns_mailchimp', 'description' => __( "Displays a sign-up form for a MailChimp mailing list.", 'chow'));
        parent::__construct('chow_ns_widget_mailchimp', __('Chow MailChimp List Signup', 'chow'), $widget_options);
        $this->chow_ns_mc_plugin = NS_MC_Plugin::get_instance();
        $this->default_loader_graphic = get_template_directory_uri() . $this->default_loader_graphic;
        add_action('init', array(&$this, 'add_scripts'));
        add_action('parse_request', array(&$this, 'process_submission'));
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function add_scripts () {
        wp_dequeue_script('ns-mc-widget');
        wp_enqueue_script('ns-mc-widget1', get_template_directory_uri() . '/js/mailchimp-widget.js', array('jquery'), false);
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function form ($instance) {
        $mcapi = $this->chow_ns_mc_plugin->get_mcapi();
        if (false == $mcapi) {
            echo $this->chow_ns_mc_plugin->get_admin_notices();
        } else {
            $this->lists = $mcapi->lists();
            $defaults = array(
                'failure_message' => $this->default_failure_message,
                'title' => $this->default_title,
                'signup_text' => $this->default_signup_text,
                'success_message' => $this->default_success_message,
                'collect_first' => false,
                'collect_last' => false,
                'old_markup' => false
                );
            $vars = wp_parse_args($instance, $defaults);
            extract($vars);
            ?>
            <h3><?php echo  __('General Settings', 'chow'); ?></h3>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo  __('Title :', 'chow'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('desc'); ?>"><?php echo  __('Description :', 'chow'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>"><?php echo $desc; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('current_mailing_list'); ?>"><?php echo __('Select a Mailing List :', 'chow'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('current_mailing_list');?>" name="<?php echo $this->get_field_name('current_mailing_list'); ?>">
                    <?php
                    foreach ($this->lists['data'] as $key => $value) {
                        $selected = (isset($current_mailing_list) && $current_mailing_list == $value['id']) ? ' selected="selected" ' : '';
                        ?>
                        <option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo __($value['name'], 'chow'); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </p>
            <p><strong>N.B.</strong><?php echo  __('This is the list your users will be signing up for in your sidebar.', 'chow'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('signup_text'); ?>"><?php echo __('Sign Up Button Text :', 'chow'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('signup_text'); ?>" name="<?php echo $this->get_field_name('signup_text'); ?>" value="<?php echo esc_attr($signup_text); ?>" />
            </p>
            <h3><?php echo __('Personal Information', 'chow'); ?></h3>
            <p><?php echo __("These fields won't (and shouldn't) be required. Should the widget form collect users' first and last names?", 'chow'); ?></p>
            <p>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('collect_first'); ?>" name="<?php echo $this->get_field_name('collect_first'); ?>" <?php echo  checked($collect_first, true, false); ?> />
                <label for="<?php echo $this->get_field_id('collect_first'); ?>"><?php echo  __('Collect first name.', 'chow'); ?></label>
                <br />
                <input type="checkbox" class="checkbox" id="<?php echo  $this->get_field_id('collect_last'); ?>" name="<?php echo $this->get_field_name('collect_last'); ?>" <?php echo checked($collect_last, true, false); ?> />
                <label><?php echo __('Collect last name.', 'chow'); ?></label>
            </p>
            <h3><?php echo __('Notifications', 'chow'); ?></h3>
            <p><?php echo  __('Use these fields to customize what your visitors see after they submit the form', 'chow'); ?></p>
            <p>
                <label for="<?php echo $this->get_field_id('success_message'); ?>"><?php echo __('Success :', 'chow'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('success_message'); ?>" name="<?php echo $this->get_field_name('success_message'); ?>"><?php echo $success_message; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('failure_message'); ?>"><?php echo __('Failure :', 'chow'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('failure_message'); ?>" name="<?php echo $this->get_field_name('failure_message'); ?>"><?php echo $failure_message; ?></textarea>
            </p>
            <?php

        }
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function process_submission () {

        if (isset($_GET[$this->id_base . '_email'])) {

            header("Content-Type: application/json");

            //Assume the worst.
            $response = '';
            $result = array('success' => false, 'error' => $this->get_failure_message($_GET['ns_mc_number']));

            $merge_vars = array();

            if (! is_email($_GET[$this->id_base . '_email'])) { //Use WordPress's built-in is_email function to validate input.

                $response = json_encode($result); //If it's not a valid email address, just encode the defaults.

            } else {

                $mcapi = $this->chow_ns_mc_plugin->get_mcapi();

                if (false == $this->chow_ns_mc_plugin) {

                    $response = json_encode($result);

                } else {

                    if (isset($_GET[$this->id_base . '_first_name']) && is_string($_GET[$this->id_base . '_first_name'])) {

                        $merge_vars['FNAME'] = $_GET[$this->id_base . '_first_name'];

                    }

                    if (isset($_GET[$this->id_base . '_last_name']) && is_string($_GET[$this->id_base . '_last_name'])) {

                        $merge_vars['LNAME'] = $_GET[$this->id_base . '_last_name'];

                    }

                    $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_GET['ns_mc_number']), $_GET[$this->id_base . '_email'], $merge_vars);

                    if (false == $subscribed) {

                        $response = json_encode($result);

                    } else {

                        $result['success'] = true;
                        $result['error'] = '';
                        $result['success_message'] =  $this->get_success_message($_GET['ns_mc_number']);
                        $response = json_encode($result);

                    }

                }

            }

            exit($response);

        } elseif (isset($_POST[$this->id_base . '_email'])) {

            $this->subscribe_errors = '<div class="notification closeable error"><p>'  . $this->get_failure_message($_POST['ns_mc_number']) .  '</p></div>';

            if (! is_email($_POST[$this->id_base . '_email'])) {

                return false;

            }

            $mcapi = $this->chow_ns_mc_plugin->get_mcapi();

            if (false == $mcapi) {

                return false;

            }

            if (is_string($_POST[$this->id_base . '_first_name'])  && '' != $_POST[$this->id_base . '_first_name']) {

                $merge_vars['FNAME'] = strip_tags($_POST[$this->id_base . '_first_name']);

            }

            if (is_string($_POST[$this->id_base . '_last_name']) && '' != $_POST[$this->id_base . '_last_name']) {

                $merge_vars['LNAME'] = strip_tags($_POST[$this->id_base . '_last_name']);

            }

            $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_POST['ns_mc_number']), $_POST[$this->id_base . '_email'], $merge_vars);

            if (false == $subscribed) {

                return false;

            } else {

                $this->subscribe_errors = '';

                //setcookie($this->id_base . '-' . $this->number, $this->hash_mailing_list_id(), time() + 31556926);

                $this->successful_signup = true;

                $this->signup_success_message = '<p>' . $this->get_success_message($_POST['ns_mc_number']) . '</p>';

                return true;

            }

        }

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function update ($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['collect_first'] = ! empty($new_instance['collect_first']);

        $instance['collect_last'] = ! empty($new_instance['collect_last']);

        $instance['current_mailing_list'] = esc_attr($new_instance['current_mailing_list']);

        $instance['failure_message'] = esc_attr($new_instance['failure_message']);

        $instance['signup_text'] = esc_attr($new_instance['signup_text']);

        $instance['success_message'] = esc_attr($new_instance['success_message']);

        $instance['title'] = esc_attr($new_instance['title']);

        $instance['desc'] = esc_attr($new_instance['desc']);

        return $instance;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function widget ($args, $instance) {

        extract($args);



        echo $before_widget . $before_title . $instance['title'] . $after_title;

        if ($this->successful_signup) {
            echo $this->signup_success_message;
        } else {
            ?>
            <p class="margin-bottom-15"><?php echo $instance['desc']; ?></p>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="<?php echo $this->id_base . '_form-' . $this->number; ?>" method="post">
                <?php echo $this->subscribe_errors;?>
                <?php
                if ($instance['collect_first']) {
                    ?>
                    <input value="<?php echo __('First Name :', 'chow'); ?>" onblur="if(this.value=='')this.value='<?php echo __('First Name :', 'chow'); ?>';" onfocus="if(this.value=='<?php echo __('First Name :', 'chow'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_first_name'; ?>" />
                    <br />
                    <br />
                    <?php
                }
                if ($instance['collect_last']) {
                    ?>
                    <input value="<?php echo __('Last Name :', 'chow'); ?>" onblur="if(this.value=='')this.value='<?php echo __('Last Name :', 'chow'); ?>';" onfocus="if(this.value=='<?php echo __('Last Name :', 'chow'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_last_name'; ?>" />
                    <br />
                    <br />
                    <?php
                }
                ?>
                <input type="hidden" name="ns_mc_number" value="<?php echo $this->number; ?>" />
                <input class="newsletter" onblur="if(this.value=='')this.value='mail@example.com';" onfocus="if(this.value=='mail@example.com')this.value='';" value="mail@example.com" id="<?php echo $this->id_base; ?>-email-<?php echo $this->number; ?>" type="text" name="<?php echo $this->id_base; ?>_email" />
                <input class="newsletter-btn" type="submit" name="<?php echo __($instance['signup_text'], 'chow'); ?>" value="<?php echo __($instance['signup_text'], 'chow'); ?>" />
            </form>
            <script>
            /* ----------------- Start Document ----------------- */
            (function($){
              "use strict";
              $(document).ready(function(){
                $('#<?php echo $this->id_base; ?>_form-<?php echo $this->number; ?>').ns_mc_widget({"url" : "<?php echo $_SERVER['PHP_SELF']; ?>", "cookie_id" : "<?php echo $this->id_base; ?>-<?php echo $this->number; ?>", "cookie_value" : "<?php echo $this->hash_mailing_list_id(); ?>", "loader_graphic" : "<?php echo $this->default_loader_graphic; ?>"}); 
              });
            })(this.jQuery);

            </script>
            <?php
        }
        echo $after_widget;


    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function hash_mailing_list_id () {

        $options = get_option($this->option_name);

        $hash = md5($options[$this->number]['current_mailing_list']);

        return $hash;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function get_current_mailing_list_id ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['current_mailing_list'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_failure_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['failure_message'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_success_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['success_message'];

    }

}



class foodiepress_favposts extends WP_Widget {

  var $defaults;

  function foodiepress_favposts() {
    $widget_ops = array( 'classname' => 'foodiepress_favposts', 'description' => __('Use this widget to display posts from favourites list', 'chow') );
    $control_ops = array( 'id_base' => 'foodiepress_favposts' );
    parent::__construct( 'foodiepress_favposts', __('FoodiePress Favourites Widget', 'chow'), $widget_ops, $control_ops );


    $this->defaults = array(
        'text' => __('Browse Your Favourites', 'chow'),
      );

    //Allow themes or plugins to modify default parameters
    $this->defaults = apply_filters('foodiepress_favposts_modify_defaults', $this->defaults);

  }

  function widget( $args, $instance ) {

    extract( $args );

    $instance = wp_parse_args( (array) $instance, $this->defaults );

    echo $before_widget;

    ?>

    <?php if(!is_user_logged_in()){ ?>
  

          <?php 
           
            if(isset( $_COOKIE['foodiepress-favposts'] )) {
             
              $faved_posts = $_COOKIE['foodiepress-favposts'];
              $favourite_posts = explode(',', $faved_posts);
              if(!empty($favourite_posts)) { ?>
              <a href="#favs-dialog" class="popup-with-zoom-anim button favourites-list"><i class="fa fa-heart"></i> <?php echo esc_html($instance['text']); ?></a>
              <div class="small-dialog zoom-anim-dialog mfp-hide" id="favs-dialog">
              <h2 class="margin-bottom-30"><?php _e('Favourite posts','chow'); ?></h2>
              <table class="basic-table fav-recipes responsive-table">

                <thead>
                    <tr>
                        <th class="recipe-thumb"><?php _e('Recipe','chow'); ?></th>
                        <th class="recipe-title"></th>
                        <th class="recipe-cat"><?php _e('Time Needed','chow'); ?></th>
                        <th class="recipe-actions">&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                   $query = 
                    array(
                        'post__in' => $favourite_posts, 
                        'posts_per_page'=> -1, 
                        'orderby' => 'post__in', 
                    );
                    $favposts = new WP_Query($query);
                     $nonce = wp_create_nonce("foodiepress_remove_fav_nonce");
                    while ( $favposts->have_posts() ) : $favposts->the_post(); ?>
                       <tr class="recipe">
                        <td class="recipe-thumb">
                          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                        </td>
                         <td class="recipe-title" >
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </td>
                    
                        <td class="recipe-metas" style="text-align:left; white-space:nowrap;">
                            <?php $time = get_the_term_list( $favposts->post->ID, 'timeneeded', ' ', ', ', '  ' );
                            if(!empty($time)) {
                                echo '<i class="fa fa-clock-o"></i> '.$time.'';
                            } ?>        
                        </td>
                    
                        
                        <td class="recipe-actions">
                            <a href="<?php the_permalink(); ?>" class="button view small color"><?php _e('View','chow') ?></a> 
                            <?php
                                $link = admin_url('admin-ajax.php?action=remove_fav&post_id='.$favposts->post->ID.'&nonce='.$nonce);
                                echo '<a class="button small foodiepress-remove-fav" data-nonce="' . $nonce . '" data-post_id="' . $favposts->post->ID . '" href="' . $link . '">'.__('Remove','chow').'</a>';
                            ?>                 
                        </td>
                    </tr>
                <?php endwhile;  wp_reset_query(); ?>
                </tbody>
            </table> 
            </div> 
              <?php } //empty
            } //isset
           ?>
      
    <?php
    } //eof user logged in
    else { 
      /*include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
      if(is_plugin_active('ultimate-member/index.php')){ 
*/
      if(class_exists('UM_API'))  { ?>
      <a href="<?php echo um_user_profile_url(); ?>&profiletab=recipes" class="favourites-list button"><i class="fa fa-heart"></i> <?php echo esc_html($instance['text']); ?></a>
      <?php } else {
      $myacount_id = ot_get_option('pp_account_page'); ?>
       <a href="<?php echo get_permalink($myacount_id); ?>" class="favourites-list button"><i class="fa fa-heart"></i> <?php echo esc_html($instance['text']); ?></a>
    <?php 
      }
    }
    echo $after_widget;
  }


  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['text'] = strip_tags( $new_instance['text'] );

    return $instance;
  }

  function form( $instance ) {

    $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Title', 'chow'); ?>:</label>
      <input id="<?php echo $this->get_field_id( 'text' ); ?>" type="text" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo esc_attr($instance['text']); ?>" class="widefat" />
    </p>
  <?php
  }

}