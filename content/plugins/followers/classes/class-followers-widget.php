<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class FollowersWidget extends WP_Widget {

    function __construct() {

    parent::__construct(
            'pt-followers',
            __('Followers Widget', 'wfsi'),
            array(  'classname' => 'pt-followers',
                    'description' => __('Display the number of followers/fans from social networks', 'wfsi'),
                    'idbase' => 'pt-followers'
            ),
            array( 'width' => 450, 'idbase' => 'pt-followers'));
    }

  

    function widget( $args, $instance ) {
        extract( $args );
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $services = $instance['services'];

        echo $before_widget;
        echo $before_title . $title . $after_title;
      
        if(!empty($instance['target'])) {
            $target = $instance['target'];
        } else {
            $target = '';
        }
        $randomid = rand(3, 15); //get random id for each widget
        do_action( 'before-followers-widget' );
        $options = get_option('followers-apis');
        ?>
        <ul class="pt-followers-icons ptfollowers<?php echo $randomid; ?> share-buttons">
            <?php 
            $services_array = explode(',', $instance['services']);
            $services_names = Followers()->services; 

            foreach ($services_array as $key) {
                $key = trim($key);
                $name = str_replace('-', '_', $key);
                if(isset($options[$name.'_url'])) {
                    $url = $options[$name.'_url'];
                } else {
                    $url = '#';
                }
                ?>
                <?php $method = $name.'_counter';  ?>
                <li class="<?php echo $key; ?>-share">
                    <a target="<?php echo $target; ?>" href="<?php echo esc_url($url); ?>">
                        <span class="counter"> <?php echo FollowersCounters::$method(); ?></span>
                        <span class="counted"> <?php echo $services_names[$key]['fans'] ?></span>
                        <span class="action-button"> <?php echo $services_names[$key]['like'] ?></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <?php
        do_action( 'after-followers-widget' );
        echo $after_widget;
    }

    //Update the widget
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['services'] = $new_instance['services'];
        $instance['target'] = $new_instance['target'];

        return $instance;
    }

    function form( $instance ) {

        $defaults = array(
            'title' => null
        );
        if(!empty($instance['services'])) { $services = $instance['services']; } else { $services = ''; }

        $services_array = Followers()->services;

        //Set up some default widget settings.
        $instance = wp_parse_args( (array) $instance, $defaults); ?>
        <div class="widget-content ptfollowers">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title:', 'wfsi'); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
            </p>
            <p id="selector">
                <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Choose service:', 'wfsi'); ?></label>
                <select class="widefat icontype" id="<?php echo $this->get_field_id( 'icontype' ); ?>" name="<?php echo $this->get_field_name( 'icontype' ); ?>">
                    <option value="">-</option>
                <?php foreach ($services_array as $icon => $service) { ?>
                    <option value="<?php echo $icon; ?>"><?php echo $icon; ?></option>
                <?php } ?>
                </select>
            </p>

            <input type="hidden" id="services" name="<?php echo $this->get_field_name( 'services' ); ?>" value="<?php echo $instance['services']; ?>"  />
            <div id="socialicons">
            <?php
                if(!empty($instance['services'])){
                    $icons = explode(',', $instance['services']);
                    foreach ($icons as $icon => $service) { ?>
                        <p><label><?php echo $service; ?></label><input type="hidden" class="social-name" value="<?php echo $service; ?>"/></p> 
                <?php }
                } ?>

            </div>
            <p>
                <small><strong>Hint</strong> You can sort icons by drag&drop, and delete them by dragging element outside the widget!</small>
            </p>
           
             <p>
                <label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Links target:', 'wfsi'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>">
                    <option value="_self" <?php selected( $instance['target'], '_self' ); ?>>_self</option>
                    <option value="_blank" <?php selected( $instance['target'], '_blank' ); ?>>_blank</option>
                    <option value="_parent" <?php selected( $instance['target'], '_parent' ); ?>>_parent</option>
                    <option value="_top" <?php selected( $instance['target'], '_top' ); ?>>_top</option>
                </select>
            </p>
           
        </div>
    <?php
    }
}

?>