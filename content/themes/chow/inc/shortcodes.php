<?php

/**
* Clear shortcode
* Usage: [clear]
*/
if (!function_exists('pp_clear')) {
    function pp_clear() {
        return '<div class="clear"></div>';
    }
    add_shortcode( 'clear', 'pp_clear' );
}

/**
* Icon shortcode
* Usage: [icon icon="icon-exclamation"]
*/
function pp_icon($atts) {
    extract(shortcode_atts(array(
        'icon'=>''), $atts));
    return '<i class="fa fa-'.$icon.'"></i>';
}
add_shortcode('icon', 'pp_icon');

/**
* Tooltip shortcode
* Usage: [tooltip title="" url=""] [/tooltip] // color, gray, light
*/
function pp_tooltip($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'url' => '#',
        'side' => 'top'
        ), $atts));
    return '<a href="'.$url.'" class="tooltip '.$side.'" title="'.esc_attr($title).'">'.$content.'</a>';
}

add_shortcode('tooltip', 'pp_tooltip');


/**
* List style shortcode
* Usage: [list type="check"] [/list] // check, arrow, checkbg, arrowbg
*/
function pp_liststyle($atts, $content = null) {
    extract(shortcode_atts(array(
        "style" => '1',
        "color" => 'no'
        ), $atts));
    if($color=='yes') { $class="color"; } else { $class = ' '; };
    $output = '<div class="list-'.$style.' '.$class.'">'.$content.'</div>';
    return $output;
}

add_shortcode('list', 'pp_liststyle');


/**
* Quote shortcode
* Usage: [quote author="icon-exclamation" source=""]
*/
function pp_quote($atts, $content = null) {
    extract(shortcode_atts(array(
        'author'=>'',
        'source'=>''
        ),
    $atts));
    $output = '
        <div class="post-quote">
            <span class="icon"></span>
            <blockquote>'.do_shortcode( $content );

    if(!empty($source)) {
        $output .= '<a href="'.$source.'">';
    }

    $output .= '<span>'.$author.'</span>';

    if(!empty($source)) {
        $output .= '</a>';
    }

    $output .= '</blockquote>
        </div>';

    return $output;
}
add_shortcode('quote', 'pp_quote');




/**
* Spacer shortcode
* Usage: [space]
*/
if (!function_exists('pp_spacer')) {
    function pp_spacer($atts, $content ) {
        extract(shortcode_atts(array(
            'class' => ''
            ), $atts));
        return '<div class="clearfix"></div><div class="'.$class.'"></div>';
    }
    add_shortcode( 'space', 'pp_spacer' );
}

if (!function_exists('pp_divider')) {
    function pp_divider($atts, $content ) {
        extract(shortcode_atts(array(
            'class' => 'margin-top-30'
            ), $atts));
        return '<div class="clearfix"></div><div class="divider '.$class.'"></div>';
    }
    add_shortcode( 'divider', 'pp_divider' );
}


/**
* Columns shortcode
* Usage: [column width="eight" place="" custom_class=""] [/column]
*/

function pp_column($atts, $content = null) {
    extract( shortcode_atts( array(
        'width' => 'eight',
        'place' => '',
        'custom_class' => ''
        ), $atts ) );

    switch ( $width ) {
        case "1/3" : $w = "column one-third"; break;
        case "one-third" : $w = "column one-third"; break;

        case "2/3" :
        $w = "column two-thirds";
        break;

        case "one" : $w = "one columns"; break;
        case "two" : $w = "two columns"; break;
        case "three" : $w = "three columns"; break;
        case "four" : $w = "four columns"; break;
        case "five" : $w = "five columns"; break;
        case "six" : $w = "six columns"; break;
        case "seven" : $w = "seven columns"; break;
        case "eight" : $w = "eight columns"; break;
        case "nine" : $w = "nine columns"; break;
        case "ten" : $w = "ten columns"; break;
        case "eleven" : $w = "eleven columns"; break;
        case "twelve" : $w = "twelve columns"; break;
        case "thirteen" : $w = "thirteen columns"; break;
        case "fourteen" : $w = "fourteen columns"; break;
        case "fifteen" : $w = "fifteen columns"; break;
        case "sixteen" : $w = "sixteen columns"; break;

        default :
        $w = 'columns eight';
    }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $column ='<div class="'.$w.' '.$custom_class.' '.$p.'">'.do_shortcode( $content ).'</div>';
    if($place=='last') {
        $column .= '<br class="clear" />';
    }
    return $column;
}

add_shortcode('column', 'pp_column');

/**
* Headline shortcode
* Usage: [headline ] [/headline] // margin-down margin-both
*/
function pp_headline( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 35,
    'clearfix' => 1
    ), $atts));
  $output = '<h3 class="headline" style="margin-top:'.$margintop.'px;">'.do_shortcode( $content ).'</h3>
            <span class="line" style="margin-bottom:'.$marginbottom.'px;"></span>';
    if($clearfix == 1) {   $output .= '<div class="clearfix"></div>';}
    return $output;
}
add_shortcode( 'headline', 'pp_headline' );


function pp_accordion( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab',
        'icon' => ''
        ), $atts));
    $output = '<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span>';
    if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'"></i>'; }
    $output .= $title.'</h3><div><p>'.do_shortcode( $content ).'</p></div>';
    return $output;
}
add_shortcode( 'accordion', 'pp_accordion' );

function pp_accordion_wrap( $atts, $content ) {
    extract(shortcode_atts(array(), $atts));
    return '<div class="accordion">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'accordionwrap', 'pp_accordion_wrap' );



function etdc_tab_group( $atts, $content ) {
    $GLOBALS['pptab_count'] = 0;
    do_shortcode( $content );
    $count = 0;
    if( is_array( $GLOBALS['tabs'] ) ) {
        foreach( $GLOBALS['tabs'] as $tab ) {
            $count++;
            $tabs[] = '<li><a href="#tab'.$count.'">'.$tab['title'].'</a></li>';
            $panes[] = '<div class="tab-content" id="tab'.$count.'">'.$tab['content'].'</div>';
        }
        $return = "\n".'<ul class="tabs-nav">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="tabs-container">'.implode( "\n", $panes ).'</div>'."\n";
    }
    return $return;
}

/**
* Usage: [tab title="" ] [/tab]
*/
function etdc_tab( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab %d',
        ), $atts));

    $x = $GLOBALS['pptab_count'];
    $GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['pptab_count'] ), 'content' =>  do_shortcode( $content ) );
    $GLOBALS['pptab_count']++;
}
add_shortcode( 'tabgroup', 'etdc_tab_group' );

add_shortcode( 'tab', 'etdc_tab' );


/**
* Dropcap shortcode type = full
* Usage: [dropcap color="gray"] [/dropcap]// margin-down margin-both
*/
if (!function_exists('pp_dropcap')) {
    function pp_dropcap($atts, $content = null) {
        extract(shortcode_atts(array(
            'type'=>''), $atts));
        return '<span class="dropcap '.$type.'">'.$content.'</span>';
    }
    add_shortcode('dropcap', 'pp_dropcap');
}



if (!function_exists('pp_popup')) {
    function pp_popup($atts, $content = null) {
        extract(shortcode_atts(array(
            'buttontext'=> 'Open Popup',
            'title'=>' Modal popup',
            ), $atts));
         $randID = rand(1, 99);
  $output = '
        <a class="popup-with-zoom-anim button color" href="#small-dialog'.$randID.'" ><i class="fa fa-info-circle"></i> '.$buttontext.'</a><br/>
            <div id="small-dialog'.$randID.'" class="small-dialog zoom-anim-dialog mfp-hide">
                <h2 class="margin-bottom-10">'.$title.'</h2>
                <p class="margin-reset">'.do_shortcode( $content ).'</p>
            </div>';
    return $output;
    }
    add_shortcode('popup', 'pp_popup');
}

/**
* Highlight shortcode
* Usage: [highlight style="gray"] [/highlight] // color, gray, light
*/
function pp_highlight($atts, $content = null) {
    extract(shortcode_atts(array(
        'style' => 'gray'
        ), $atts));
    return '<span class="highlight '.$style.'">'.$content.'</span>';
}
add_shortcode('highlight', 'pp_highlight');


/**
* Box shortcodes
* Usage: [box type=""] [/box]
*/

function pp_box($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => ''
        ), $atts));
    return '<div class="notification closeable '.$type.'"><p>'.do_shortcode( $content ).'</p><a class="close" href="#"></a></div>';
}

add_shortcode('box', 'pp_box');

function pp_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => '#',
        "color" => 'color',  //gray color dark
        "customcolor" => '',
        "iconcolor" => 'white',
        "icon" => '',
        "target" => '',
        "customclass" => '',
        "from_vs" => 'no',
        ), $atts));
    if($from_vs == 'yes') {
        $link = vc_build_link( $url );
        $a_href = $link['url'];
        $a_title = $link['title'];
        $a_target = $link['target'];
        $output = '<a class="button '.$color.' '.$customclass.'" href="'.$a_href.'" title="'.esc_attr( $a_title ).'" target="'.$a_target.'"';
        if(!empty($customcolor)) { $output .= 'style="background-color:'.$customcolor.'"'; }
        $output .= '>';
        if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'  '.$iconcolor.'"></i> '; }
        $output .= $a_title.'</a>';
    } else {
        $output = '<a class="button '.$color.' '.$customclass.'" href="'.$url.'" ';
        if(!empty($target)) { $output .= 'target="'.$target.'"'; }
        if(!empty($customcolor)) { $output .= 'style="background-color:'.$customcolor.'"'; }
        $output .= '>';
        if(!empty($icon)) { $output .= '<i class="fa fa-'.$icon.'  '.$iconcolor.'"></i> '; }
        $output .= $content.'</a>';
    }
    return $output;
}
add_shortcode('button', 'pp_button');


function pp_share_btn($atts) {
    extract(shortcode_atts(array(
        "facebook" => '',
        "pinit" => '',
        "twitter" => '',
        "gplus" => '',

        ), $atts));
    global $post;

    $id = $post->ID;
    $title = urlencode($post->post_title);
    $url =  urlencode( get_permalink($id) );
    $summary = urlencode(string_limit_words($post->post_excerpt,20));
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium' );
    $imageurl = urlencode($thumb[0]);

    $output ='<!-- Share Buttons -->
    <div class="share-buttons">
    <ul>
        <li><a href="#">'.__("Share","chow").'</a></li>';
        if(!empty($facebook)) $output .= '<li class="share-facebook"><a target="_blank" href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . esc_attr($title) . '&amp;p[url]=' . $url . '&amp;p[summary]=' . esc_attr($summary) . '&amp;p[images][0]=' . $imageurl . '"">Facebook</a></li>';
        if(!empty($pinit)) $output .= '<li class="share-pinit"><a target="_blank" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . esc_attr($summary) . '&media=' . $imageurl . '" onclick="window.open(this.href); return false;">Pin it</a></li>';
        if(!empty($gplus)) $output .= '<li class="share-gplus"><a target="_blank" href="https://plus.google.com/share?url=' . $url . '&amp;title="' . esc_attr($title) . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'>Google Plus</a></li>';
        if(!empty($twitter)) $output .= '<li class="share-twitter"><a target="_blank"  href="https://twitter.com/share?url=' . $url . '&amp;text=' . esc_attr($summary ). '" title="' . __( 'Twitter', 'chow' ) . '">Twitter</a></li>';
    $output .= '</ul>
    </div>
    <div class="clearfix"></div>';
    return $output;
}

add_shortcode('shareit', 'pp_share_btn');


function pp_basic_slide($atts) {
    extract(shortcode_atts(array(
        "image" => '',
        "url" => '#',
        "caption" => '',
        ), $atts));
    $output = '';

     $output .= '<a href="'.$url.'">';
        $output .='<img class="rsImg" src="'.$image.'" alt="" /><span class="royal-caption">'.$caption.'</span>';
     $output .= '</a>';
    return $output;
}
add_shortcode('slide', 'pp_basic_slide');

function pp_basic_slider( $atts, $content ) {
    extract(shortcode_atts(array(
        "image" => '',
        "url" => '#',
        "caption" => '',

        ), $atts));
    return '<div class="basic-slider royalSlider rsDefault">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'slider', 'pp_basic_slider' );


add_shortcode('posts_grid', 'chow_posts_grid');
function chow_posts_grid($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'orderby'=> 'date',
        'order'=> 'DESC',
        'categories' => '',
        'tags' => '',
        'width' => 'sixteen',
        'place' => 'center',
        'post_type' => 'post',

        'exclude_posts' => '',
        'ignore_sticky_posts' => 1,
    
        ), $atts));

    $output = '';
    $randID = rand(1, 99); // Get unique ID for carousel

    if(empty($width)) { $width = "sixteen"; } //set width to 16 even if empty value


    wp_reset_query();


    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'orderby' => $orderby,
        'order' => $order,
        );
    if(!empty($exclude_posts)) {
        $exl = explode(",", $exclude_posts);
        $args['post__not_in'] = $exl;
    }

    if(!empty($categories)) {
        //$categories = explode(",", $categories);
        $args['category_name'] = $categories;
    }
    if(!empty($tags)) {
        $tags = explode(",", $tags);
        $args['tag__in'] = $tags;
    }
    $i = 0;
    $wp_query = new WP_Query( $args );
        $mainclass = "four columns";
        $imagesize = 'blog-size';

    if ( $wp_query->have_posts() ):
        $output .= '<div class="isotope">';
        while( $wp_query->have_posts() ) : $wp_query->the_post();
            $i++;
            $id = $wp_query->post->ID;
            $is_recipe = false;
            $ingredients = get_post_meta($wp_query->post->ID, 'cookingpressingridients', true);
            if(!empty($ingredients)) {
                $is_recipe = true;
            }
          
            $thumb = get_post_thumbnail_id();
            $img_url = wp_get_attachment_url($thumb);

            $author_id = $wp_query->post->post_author;

            $output .= '
            <div class="'.$mainclass.'  recipe-box columns">
                <!-- Thumbnail -->
                <div class="thumbnail-holder">
                    <a href="'.esc_url(get_permalink()).'">
                        '.get_the_post_thumbnail($id,$imagesize).'
                        <div class="hover-cover"></div>';
             
                $ingredients = get_post_meta($wp_query->post->ID, 'cookingpressingridients', true);
                if(!empty($ingredients)) { 
                $output .= ' <div class="hover-icon">'.__('View Recipe','chow').'</div>';
                    } else { 
                $output .= '<div class="hover-icon">'.__('View Post','chow').'</div>';
                    }
                $output .= '</a>
                </div>';

                $ratings=check_post_rating();
                if($ratings>0) {
                $output .= '<div class="recipe-box-content has-stars">';
                } else {
                    $output .= '<div class="recipe-box-content no-stars">';
                }
                   $output .= '<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';

                   ob_start();
                        do_action('foodiepress-rating');
                         if($is_recipe) { do_action('grid-post-meta'); } else { do_action('no-recipe-post-meta'); }
                        $below_shortcode = ob_get_contents();
                    ob_end_clean();
                    $output .=  $below_shortcode;

                   $output .= ' <div class="clearfix"></div>
                </div>
            </div>';

        endwhile;  // close the Loop
        $output .= '</div>';
    endif;
    wp_reset_query();
    return $output;
}

