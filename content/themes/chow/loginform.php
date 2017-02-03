<div id="login-register-password" class="columns six woo-login-form">
<?php do_action('chow-before-login'); ?>
    <?php global $user_ID, $user_identity; if (!$user_ID) { ?>
    <ul class="tabs-nav">
        <li class="active"><a href="#tab1_login"><?php _e('Login','chow'); ?></a></li>
        <li><a href="#tab2_login"><?php _e('Register','chow'); ?></a></li>
        <li><a href="#tab3_login"><?php _e('Lost your password?','chow'); ?></a></li>
    </ul>
    <div class="tabs-container loginbox">
        <div id="tab1_login" class="tab-content">

            <?php
            if(isset($_GET['register']) ){ $register = $_GET['register']; }
            if(isset($_GET['reset'] )){ $reset = $_GET['reset']; }

            if (isset($register) && $register == true) { ?>

           
            <div class="notification success closeable">
             <h3><?php _e('Success!','chow'); ?></h3>
                <p><?php _e('Check your email for the password and then return to log in.','chow'); ?></p>
            </div>
            

            <?php } elseif ( isset($reset) && $reset == true ) { ?>
            <h3><?php _e('Success!','chow'); ?></h3>
            <div class="notification success closeable">
                <p><?php _e('<span>Success!</span>Check your email to reset your password.','chow'); ?></p>
            </div>
            
            <p></p>

            <?php } else { ?>

            <h3 class="headline"><?php _e('Have an account?','chow'); ?></h3><span class="line" style="margin-bottom:25px;"></span><div class="clearfix"></div>
            <p><?php _e('You need to log in to add recipe.','chow'); ?></p>

            <?php } ?>

            <?php wp_login_form(); ?>
        </div>
        <div id="tab2_login" class="tab-content">
            <h3 class="headline"><?php _e('Register for this site!','chow'); ?></h3><span class="line" style="margin-bottom:25px;"></span><div class="clearfix"></div>
            <p><?php _e('Sign up now for the good stuff.','chow'); ?></p>
            <form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
                <p class="login-username">
                    <label for="user_login"><?php _e('Username','chow'); ?>: </label>
                    <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="101" />
                </p>
                <p class="login-password">
                    <label for="user_email"><?php _e('Your Email','chow'); ?>: </label>
                    <input type="text" name="user_email" value="" size="25" id="user_email" tabindex="102" />
                </p>
                <p class="login_fields">
                    <?php do_action('register_form'); ?>
                    <input type="submit" name="user-submit" value="<?php _e('Sign up!','chow'); ?>" class="user-submit" tabindex="103" />
                    <?php if(isset($register) && $register == true) { echo '<p>Check your email for the password!</p>'; } ?>
                    <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('register','true',$_SERVER['REQUEST_URI']); ?>" />
                    <input type="hidden" name="user-cookie" value="1" />
                </p>
            </form>
        </div>
        <div id="tab3_login" class="tab-content" >
            <h3 class="headline"><?php _e('Lost your password?','chow'); ?></h3><span class="line" style="margin-bottom:25px;"></span><div class="clearfix"></div>
            <p><?php _e('Enter your username or email to reset your password.','chow'); ?></p>
            <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
                <p class="login-username">
                    <label for="user_login" class="hide"><?php _e('Username or Email','chow'); ?>: </label>
                    <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
                </p>
                <p class="login_fields">
                    <?php do_action('login_form', 'resetpass'); ?>
                    <input type="submit" name="user-submit" value="<?php _e('Reset my password','chow'); ?>" class="user-submit" tabindex="1002" />
                    <?php if(isset($reset) && $reset == true) { _e('<p>A message will be sent to your email address.</p>','chow');  } ?>
                    <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('reset','true',$_SERVER['REQUEST_URI']); ?>" />
                    <input type="hidden" name="user-cookie" value="1" />
                </p>
            </form>
        </div>
    </div>

    <?php } else { // is logged in ?>

    <div class="sidebox">
        <h3><?php _e('Welcome','chow') ?>, <?php echo $user_identity; ?></h3>
        <div class="usericon">
            <?php global $userdata;  echo get_avatar($userdata->ID, 60); ?>
        </div>
        <div class="userinfo">
            <p><?php _e('You&rsquo;re logged in as','chow'); ?> <strong><?php echo $user_identity; ?></strong></p>
            <p>
                <a href="<?php echo wp_logout_url('index.php'); ?>"><?php _e('Log out','chow') ?></a> |
                <?php if (current_user_can('manage_options')) {
                    echo '<a href="' . esc_url(admin_url()) . '">' . __('Admin','chow') . '</a>'; } else {
                        echo '<a href="' . esc_url(admin_url()) . 'profile.php">' . __('Profile','chow') . '</a>'; } ?>
            </p>
        </div>
    </div>

    <?php } ?>

</div>
