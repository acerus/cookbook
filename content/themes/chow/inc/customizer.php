<?php
/**
 * Chow Theme Customizer
 *
 * @package Chow
 */


/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function chow_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    // color section
    $wp_customize->add_section( 'chow_color_settings', array(
        'title'          => __('Main theme color','chow'),
        'priority'       => 35,
        ) );

    $wp_customize->add_setting( 'chow_main_color', array(
        'default'   => '#73b819',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'chow_main_color', array(
        'label'    => __('Color Settings','chow'),
        'section'  => 'colors',
        'settings' => 'chow_main_color',
        )));

    // bof layout section
    $wp_customize->add_section( 'chow_layout_settings', array(
        'title'          => __('Layout','chow'),
        'priority'       => 36,
        ));


    $wp_customize->add_setting( 'chow_layout_style', array(
        'default'  => 'boxed',
        'sanitize_callback' => 'esc_attr',
        'transport' => 'postMessage'
        ));
    $wp_customize->add_control( 'chow_layout_choose', array(
        'label'    => __('Select layout type','chow'),
        'section'  => 'chow_layout_settings',
        'settings' => 'chow_layout_style',
        'type'     => 'select',
        'choices'    => array(
            'boxed' => 'Boxed',
            'fullwidth' => 'Wide',
            )
        ));

    $wp_customize->add_setting( 'chow_menu_currcolor', array(
        'default'        => '#505050',
        'transport' =>'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
        ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'chow_menu_currcolor', array(
        'label'   => __('Menu current element color','chow'),
        'section' => 'colors',
        'settings'   => 'chow_menu_currcolor',
        )));


    $wp_customize->add_setting( 'chow_tagline_switch', array(
        'default'  => 'show',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_attr',
        ));
    $wp_customize->add_control( 'chow_tagline_switcher', array(
        'settings' => 'chow_tagline_switch',
        'label'    => __( 'Display Tagline','chow' ),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'choices'    => array(
            'hide' => 'Hide',
            'show' => 'Show',

            )
        ));

    if ( $wp_customize->is_preview() && !is_admin() ) {
        add_action( 'wp_footer', 'chow_customize_preview', 21);
    }

}
add_action( 'customize_register', 'chow_customize_register' );



function chow_customize_preview() { ?>
    <script type="text/javascript">
    ( function( $ ){
        wp.customize('chow_main_color',function( value ) {
            value.bind(function(to) {

            $('.skill-bar-value,.counter-box.colored,.pagination .current,.tabs-nav li.active a,.dropcap.full,.highlight.color,.ui-accordion .ui-accordion-header-active,.trigger.active a,.share-buttons ul li:first-child a,#price-range .ui-state-default,.customSelect .selectList dd.hovered').css('background',to);
            $('.top-bar-dropdown ul li a,a.menu-trigger,.pagination ul li a,.pagination-next-prev ul li a,.ui-accordion .ui-accordion-header-active,.trigger.active a,a.caption-btn,.mfp-close,.mfp-arrow,.img-caption figcaption,.selectricItems li,.rsDefault .rsThumbsArrow,.qtyplus,.qtyminus,a.calculate-shipping,.og-close,.tags a').hover(
                function(){
                    var attr = $(this).attr('orgbackground');
                    if (typeof attr == 'undefined' || attr == false) {
                        var orgbg = $(this).css('background');
                    }
                    $(this).attr('orgbackground', orgbg).css('background', to);
                }, function(){
                    var bg = $(this).attr('orgbackground');
                    $(this).css('background', bg);
                });


            $('.cart-buttons a,.menu > li.sfHover .current,input[type="button"],input[type="submit"],a.button,a.button.color,.product-discount,.newsletter-btn,.hover-icon,#filters a.selected,#categories li a.active, .cart-buttons a.checkout,.menu > li.sfHover').css('background-color',to);
            $('.top-search button, .menu > li, li.dropdown ul li a, #jPanelMenu-menu li a, a.button.dark, a.button.gray, .icon-box span, .tp-leftarrow, .tp-rightarrow, .sb-navigation-left, .sb-navigation-right, #categories li a, .flexslider .flex-prev, .flexslider .flex-next, .rsDefault .rsArrowIcn, #filters a').hover(
                function(){
                    var attr = $(this).attr('orgbackground');
                    if (typeof attr == 'undefined' || attr == false) {
                        var orgbg = $(this).css('background-color');
                    }
                    $(this).attr('orgbackground', orgbg).css('background-color', to);
                }, function(){
                    var bg = $(this).attr('orgbackground');
                    $(this).css('background-color', bg);
                });

            $('.happy-clients-author, .mega ul li p a, #not-found i, .dropcap, .list-1.color li:before, .list-2.color li:before, .list-3.color li:before, .list-4.color li:before').css('color',to);
            $('#additional-menu ul li a,.mega a,.comment-by span.reply a,#categories li ul li a,table .cart-title a,.st-val a,.meta a').hover(
            function(){
                var attr = $(this).attr('orgbackground');
                if (typeof attr == 'undefined' || attr == false) {
                    var orgbg = $(this).css('color');
                }
                $(this).attr('orgbackground', orgbg).css('color', to);
            }, function(){
                var bg = $(this).attr('orgbackground');
                $(this).css('color', bg);
            });
        });
    });

    wp.customize('chow_menu_currcolor',function( value ) {
            value.bind(function(to) {
            $('#navigation .menu > li.current-menu-parent, #navigation .menu > li.current-menu-ancestor, #navigation .menu > li.current_page_parent, #navigation .menu > li.current-menu-item').css('backgroundColor',to);
        });
    });

    wp.customize('chow_tagline_switch',function( value ) {
      value.bind(function(to) {
          if(to === 'hide') { $('#blogdesc').hide(); } else { $('#blogdesc').show(); }
      });
    });

    wp.customize('chow_layout_style',function( value ) {
      value.bind(function(to) {
        $("body").removeClass("boxed").removeClass("wide").addClass(to);
      });
    });
    

} )( jQuery )
</script>
<?php
}



function pp_generate_typo_css($typo){
    if($typo){
        $wpv_ot_default_fonts = array('arial','georgia','helvetica','palatino','tahoma','times','trebuchet','verdana');        
        $ot_google_fonts = get_theme_mod( 'ot_google_fonts', array() );
        foreach ($typo as  $key => $value) {
            if(isset($value) && !empty($value)) {
                if($key=='font-color') { $key = "color"; }
                if($key=='font-family') { 
                    if ( ! in_array( $value, $wpv_ot_default_fonts ) ) {
                        $value = $ot_google_fonts[$value]['family']; } 
                    }
                echo $key.":".$value.";";
                
            }
        }
    }
}
function pp_generate_bg_css($typo){
    if($typo){
        foreach ($typo as  $key => $value) {
            if(isset($value) && !empty($value)) {
                if($key=='background-image') $value = "url('".$value."')";
                echo $key.":".$value.";";
            }
        }
    }
}

function mobile_menu_css(){
    $breakpoint = ot_get_option('pp_menu_breakpoint','767');
    $bodytypo = ot_get_option( 'chow_body_font');
    $menutypo = ot_get_option( 'chow_menu_font');
    $logotypo = ot_get_option( 'chow_logo_font');
    $slidertypo = ot_get_option( 'chow_slider_font');
    $headerstypo = ot_get_option( 'chow_headers_font');
?>
<style type="text/css">
    body { <?php pp_generate_typo_css($bodytypo); ?> }
    h1, h2, h3, h4, h5, h6  { <?php pp_generate_typo_css($headerstypo); ?> }
    #logo h1 a, #logo h2 a { <?php pp_generate_typo_css($logotypo); ?> }
    #navigation .menu > li > a, #navigation ul li a {  <?php pp_generate_typo_css($menutypo); ?>  }
    a.blockTitle,
    .rsSlideTitle.title a {  <?php pp_generate_typo_css($slidertypo); ?>  }
    </style>
  <?php
}
add_action('wp_head', 'mobile_menu_css');


add_action('wp_head', 'custom_stylesheet_content');
function custom_stylesheet_content() {
 $ltopmar = ot_get_option( 'pp_logo_top_margin' );
 $menutopmar = ot_get_option( 'pp_menu_top_margin' );
 $lbotmar = ot_get_option( 'pp_logo_bottom_margin' );
 $taglinemar = ot_get_option( 'pp_tagline_margin' );
?>
<style type="text/css">
    .boxed #logo,#logo {
        <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$ltopmar[0].$ltopmar[1].';'; } ?>
        <?php if ( isset( $lbotmar[0] ) && $lbotmar[1] ) { echo 'margin-bottom:'.$lbotmar[0].$lbotmar[1].';'; } ?>
    }
    #header #navigation {
        <?php if ( isset( $menutopmar[0] ) && $menutopmar[1] ) { echo 'margin-top:'.$menutopmar[0].$menutopmar[1].';'; } ?>
    }
    #blogdesc {
        <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$taglinemar[0].$taglinemar[1].';'; } ?>
    }
<?php
$custom_main_color = get_theme_mod('chow_main_color','#73b819');
$menu_current_color = get_theme_mod('chow_menu_currcolor','#505050');
$custom_rgb = hex2RGB($custom_main_color);
if($custom_rgb) {
  $red = $custom_rgb['red'];
  $green = $custom_rgb['green'];
  $blue = $custom_rgb['blue'];
}
?>
.rsDefaultInv .rsThumb.rsNavSelected {
    -webkit-box-shadow: inset 0px -1px 0px 0px rgba(<?php echo $red.','.$green.','.$blue.','; ?> 0.12), 1px 0px 0px 0px <?php echo $custom_main_color; ?>;
    -moz-box-shadow: inset 0px -1px 0px 0px rgba(<?php echo $red.','.$green.','.$blue.','; ?>, 0.12), 1px 0px 0px 0px <?php echo $custom_main_color; ?>;
    box-shadow: inset 0px -1px 0px 0px rgba(<?php echo $red.','.$green.','.$blue.','; ?> 0.12), 1px 0px 0px 0px <?php echo $custom_main_color; ?>;
}
#current,
.menu ul li a:hover,
.menu ul > li:hover > a,
.menu ul ul,
.rsDefaultInv .rsThumb.rsNavSelected,
.rsDefault .rsThumb.rsNavSelected,
.menu > ul > li.current-menu-ancestor > a, .menu > ul > li.current-menu-item > a, #current,

.foodiepress-wrapper.recipe2 .instructions ul > li.active:before,
.foodiepress-wrapper.recipe1 .instructions ul > li.active:before,
.foodiepress-wrapper.recipe1 .ingredients li.active:before,
.foodiepress-wrapper.recipe1 .ingredients a:hover:after,
.foodiepress-wrapper.recipe2 .ingredients-container .ingredients a:hover:after,
.foodiepress-wrapper.recipe2 .ingredients li.active:before {
    border-color: <?php echo $custom_main_color; ?>;
}

.alternative #current:hover,
.wp-core-ui .button:hover,
.foodiepress-wrapper.recipe1 .instructions ul > li.active:before,
.foodiepress-wrapper.recipe2 .instructions ul > li.active:before,
.menu.alternative ul li a:hover,
.menu.alternative ul > li.sfHover > a {
    background-color: <?php echo $custom_main_color; ?> !important;
}


.rsDefault .rsArrowIcn:hover,
a.print,
.ingredients input[type=checkbox]:checked + label:before,
#slider-prev:hover,
#slider-next:hover,
.search button,
#bbpress-forums .topic-author div.bbp-reply-header,
.rsSlideTitle.tags ul li,
ul.categories li a:hover,
.post-icon,
.rate-recipe,
.comment-by a.reply:hover,
.comment-by a.comment-reply-link:hover,
.newsletter-btn,
.mc4wp-form input[type="submit"], 
.product-button,
.search-by-keyword button,
.chosen-container .chosen-results li.highlighted,
.chosen-container-multi .chosen-choices li.search-choice,
.woocommerce-MyAccount-navigation li.is-active a,
.tabs-nav li.active a,
.ui-accordion .ui-accordion-header-active:hover,
.ui-accordion .ui-accordion-header-active,
a.nav-toggle.active,
.upload-btn,
a.button.color, input[type="button"], input.button.color,
.widget_categories ul li a:hover,
input[type="submit"],
.nav-links a:hover,
a.button.light:hover,
#advanced-search .search-by-keyword button, .search .search-by-keyword button,
nav.search button, aside.search button,
.foodiepress-wrapper.recipe1 .ingredients li.active:before,
.foodiepress-wrapper.recipe2 .ingredients li.active:before,
.pagination ul li a.current-page { background-color: <?php echo $custom_main_color; ?>; }


.rsDefaultInv .rsThumb.rsNavSelected,
a.blockTitle:hover,
.rsDefault .rsThumb.rsNavSelected,
.rsDefault .rsThumbsArrow:hover,
.qtyplus:hover,
.qtyminus:hover,
body input[type="button"]:hover,
.quantity input.plus:hover, 
.quantity input.minus:hover, 
a.cart-remove:hover,
.linking .button,
.mfp-close:hover,
a.calculate-shipping:hover,
.widget_price_filter .button,
a.button.wc-forward,
.shipping-calculator-form .button,
.mfp-arrow:hover,
.pagination .current,
.pagination ul li a:hover,
.pagination-next-prev ul li a:hover,
.highlight.color { background: <?php echo $custom_main_color; ?>; }

a,
.author-box .title,
.author-box .contact a:hover,
ul.product_list_widget li a:hover,
a.adv-search-btn.active i,
a.adv-search-btn.active,
a.adv-search-btn:hover i,
a.adv-search-btn:hover,
.foodiepress-wrapper.recipe1 .ingredients a:hover,
.foodiepress-wrapper.recipe2 .ingredients a:hover,
.comment-by a.url:hover,
.author-box a:hover span,
.post-meta a:hover,
table.cart-table td.product-name a:hover,
.widget ul li a:hover,
.basic-table.fav-recipes .recipe-title a:hover,
.list-1.color li:before,
.list-2.color li:before,
.list-3.color li:before,
.list-4.color li:before { color: <?php echo $custom_main_color; ?>; }

<?php echo ot_get_option( 'pp_custom_css' ); ?>

<?php
$catalogmode = ot_get_option('pp_woo_catalog','off');
if ($catalogmode == "on") { ?>
    .product-button,
    .add_to_cart_button,
    #cart { display: none;}

<?php } ?>
</style>
<?php

}   //eof custom_stylesheet_content ?>