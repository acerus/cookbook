<?php
// check for submitted post

add_action ('parse_request', 'chow_post_submission');
function chow_post_submission() {

   if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post" 
    && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
     global $postAdded;
     global $submit_errors;
     $hasError = false;
     $submit_errors = array( );

    if (empty($_POST['title'])) {
        $hasError = true;
        $submit_errors[] = 'title';
    }

    $title = sanitize_text_field($_POST['title']);
    if(empty($title)) {
         $title = 'User Submitted Post';
    }


    $category  = $_POST['cat'];

    /* foreach ($_POST['cat'] as $k => $v) {
        $category[] = $_POST['cat'];
    }
    print_r($category); die();*/

    $summary = stripslashes($_POST['summary']);

    $ingredients = array();
    foreach ($_POST['ingredient_name'] as $k => $v) {
        $ingredients[] = array(
            'name' => $v,
            'note' => sanitize_text_field($_POST['ingredient_note'][$k]),
            );
    }
    
    $instructions = stripslashes($_POST['instructions']);

    if (empty($_POST['instructions']) || $_POST['instructions'] == ' ') {
        $hasError = true;
        $submit_errors[] = 'instructions';
    }

    $recipeoptions = array(
        sanitize_text_field($_POST['preptime']),
        sanitize_text_field($_POST['cooktime']),
        sanitize_text_field($_POST['yield']),
        );

    $ntfacts = $_POST['cookingpressntfacts'];

    if(isset($_POST['serving'])) {
        $serving = sanitize_text_field($_POST['serving']);
    } else {$serving='';}

    $level = sanitize_text_field($_POST['level']);

    if(isset($_POST['allergen'])) {
        $allergens = $_POST['allergen'];
    } else {$allergens='';}

    if(isset($_POST['timeneeded'])) {
        $timeneeded = sanitize_text_field($_POST['timeneeded']);
    } else {$timeneeded='';}

    $options = get_option( 'chow_option',array());
    if(is_array($options) && !empty($options['post_type'])) {
        $post_type = 'recipe';
    } else {
        $post_type = 'post';
    } 
    // ADD THE FORM INPUT TO $new_post ARRAY
    $new_post = array(
        'post_title'    =>  $title,
        'post_content'  =>  '',
        'post_status'   =>  'draft',           // Choose: publish, preview, future, draft, etc.
        'post_type' =>  $post_type,  //'post',page' or use a custom post type if you want to
        );

    //SAVE THE POST
        if($hasError==false) {
            $pid = wp_insert_post($new_post);
            if ($_FILES) {

              foreach ($_FILES as $file => $array) {
                if($array['name']){
                    $newupload = insert_attachment($file,$pid);
                    set_post_thumbnail( $pid, $newupload );
                }
              }

            } // END THE
            wp_set_post_categories($pid,$category);
            add_post_meta($pid, 'cookingpressingridients', $ingredients, true);
            add_post_meta($pid, 'cookingpressinstructions', $instructions, true);
            add_post_meta($pid, 'cookingpressrecipeoptions', $recipeoptions, true);
            add_post_meta($pid, 'cookingpressntfacts', $ntfacts, true);
            add_post_meta($pid, 'cookingpresssummary', $summary, true);

        //SET OUR TAGS UP PROPERLY
            wp_set_object_terms($pid, $level, 'level');
            wp_set_object_terms($pid, $serving, 'serving');
            wp_set_object_terms($pid, $timeneeded, 'timeneeded');
            if(isset($allergens)) { wp_set_object_terms($pid, $allergens, 'allergen'); }

            do_action('wp_insert_post', 'wp_insert_post');
            $postAdded = true;

        }
    }

    //edit

    if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && !empty( $_POST['edit_post_id'] ) 
        && $_POST['action'] == "update_post" && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
        global $postUpdated;
        global $submit_errors;
         $edited_post_id = $_POST['edit_post_id'];
         $hasError = false;
         $submit_errors = array( );

        if (empty($_POST['title'])) {
            $hasError = true;
            $submit_errors[] = 'title';
        }

        $title = sanitize_text_field($_POST['title']);
        if(empty($title)) {
             $title = 'User Submitted Post';
        }


        $category  = intval($_POST['cat']);
        $summary = stripslashes($_POST['summary']);

        $ingredients = array();
        foreach ($_POST['ingredient_name'] as $k => $v) {
            $ingredients[] = array(
                'name' => $v,
                'note' => sanitize_text_field($_POST['ingredient_note'][$k]),
                );
        }
        
        $instructions = stripslashes($_POST['instructions']);

        if (empty($_POST['instructions']) || $_POST['instructions'] == ' ') {
            $hasError = true;
            $submit_errors[] = 'instructions';
        }

        $recipeoptions = array(
            sanitize_text_field($_POST['preptime']),
            sanitize_text_field($_POST['cooktime']),
            sanitize_text_field($_POST['yield']),
            );

        $ntfacts = $_POST['cookingpressntfacts'];

        if(isset($_POST['serving'])) {
            $serving = sanitize_text_field($_POST['serving']);
        } else {$serving='';}

        $level = sanitize_text_field($_POST['level']);

        if(isset($_POST['allergen'])) {
            $allergens = $_POST['allergen'];
    
        } else {$allergens='';}

        if(isset($_POST['timeneeded'])) {
            $timeneeded = sanitize_text_field($_POST['timeneeded']);
        } else {$timeneeded='';}

        // ADD THE FORM INPUT TO $new_post ARRAY
        // 
        // 
        $options = get_option( 'chow_option',array());
        if(is_array($options) && !empty($options['post_type'])) {
            $post_type = 'recipe';
        } else {
            $post_type = 'post';
        }

        $new_post = array(
            'post_title'    =>  $title,
            'ID' => $edited_post_id,
            'post_content'  =>  '',
            'post_category' =>  array($_POST['cat']),  // Usable for custom taxonomies too
            'post_status'   =>  'pending',           // Choose: publish, preview, future, draft, etc.
            'post_type' =>   $post_type //'post',page' or use a custom post type if you want to
            );

        //SAVE THE POST
        if($hasError==false) {
           
           
            if ($_FILES) {
              foreach ($_FILES as $file => $array) {
                if(!empty($array['name'])){
                    $newupload = insert_attachment($file,$edited_post_id);
                    set_post_thumbnail( $edited_post_id, $newupload );
                }
              }

            } 
            
            $update = wp_update_post($new_post);
            if($update) {
               
                // END THE
                update_post_meta($edited_post_id, 'cookingpressingridients', $ingredients);
                update_post_meta($edited_post_id, 'cookingpressinstructions', $instructions);
                update_post_meta($edited_post_id, 'cookingpressrecipeoptions', $recipeoptions);
                update_post_meta($edited_post_id, 'cookingpressntfacts', $ntfacts);
                update_post_meta($edited_post_id, 'cookingpresssummary', $summary);

            //SET OUR TAGS UP PROPERLY
                wp_set_object_terms($edited_post_id, $level, 'level');
                wp_set_object_terms($edited_post_id, $serving, 'serving');
                wp_set_object_terms($edited_post_id, $timeneeded, 'timeneeded');
                if(isset($allergens)) { wp_set_object_terms($edited_post_id, $allergens, 'allergen'); }

            //do_action('wp_insert_post', 'wp_insert_post');
                $postUpdated = true;
            }

        }

        

    }
}

function insert_attachment($file_handler,$post_id,$setthumb='false') {

// check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
  $attach_id = media_handle_upload( $file_handler, $post_id );
  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;

}


?>