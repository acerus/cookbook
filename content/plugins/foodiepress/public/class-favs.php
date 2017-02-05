<?php 
class FoodiePressFavs {

	function __construct(){
	    add_action('init', array($this, 'init'));
	}

	function init(){
  	 	add_action("wp_ajax_add_to_fav", array($this, 'add_to_fav'));
		add_action("wp_ajax_nopriv_add_to_fav", array($this, 'add_to_fav'));

		add_action("wp_ajax_remove_fav", array($this, 'remove_fav'));
		add_action("wp_ajax_nopriv_remove_fav", array($this, 'remove_fav'));
		add_shortcode('foodiepress-addtofav', array($this, 'foodiepress_fav_button'));
  	}

  	public function add_to_fav() {

	    if ( !wp_verify_nonce( $_REQUEST['nonce'], "foodiepress_add_to_fav_nonce")) {
	    	exit("No naughty business please");
	    }   
	    $post_id = $_REQUEST["post_id"];

	    if(is_user_logged_in()){
		   $userID = $this->get_user_id();
		   	if($this->check_if_added($post_id)) {
				$result['type'] = "error";
				$result['message'] = __("You've already added that post","chow");
		   	} else {
		   		$faved_posts =  $this->get_faved_post();
		  		$faved_posts[] = $post_id;
				$action = update_user_meta( $userID, 'foodiepress-fav-posts', $faved_posts, false );
				if($action === false) {
					$result['type'] = "error";
					$result['message'] = __("Oops, something went wrong, please try again","chow");
				} else {
					$result['type'] = "success";
					$result['message'] = __("Recipe was added to the list","chow");
				}
			}
		   
		} else {
			$faved_posts = array();
			if(isset( $_COOKIE['foodiepress-favposts'] )) {
				$faved_posts = $_COOKIE['foodiepress-favposts'];
				$posts_to_fav = explode(',', $faved_posts);
			}
			$posts_to_fav[] = $post_id;
			$posts_to_fav = implode(',', $posts_to_fav);
			$expire = time()+60*60*24*30;
    		setcookie("foodiepress-favposts", $posts_to_fav, $expire, "/");
    		$result['type'] = "success";
			$result['message'] = __("Recipe was added to the list","chow");
		}
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	      $result = json_encode($result);
	      echo $result;
	   }
	   else {
	      header("Location: ".$_SERVER["HTTP_REFERER"]);
	   }
	   die();

	}	  	

	public function remove_fav() {

	   if ( !wp_verify_nonce( $_REQUEST['nonce'], "foodiepress_remove_fav_nonce")) {
	      exit("No naughty business please");
	   }   
	   $post_id = $_REQUEST["post_id"];
	   if(is_user_logged_in()){
		   	$userID = $this->get_user_id();
		
	   		$faved_posts =  $this->get_faved_post();
	   		$faved_posts = array_diff($faved_posts, array($post_id));
	        $faved_posts = array_values($faved_posts);

			$action = update_user_meta( $userID, 'foodiepress-fav-posts', $faved_posts, false );
			if($action === false) {
				$result['type'] = "error";
				$result['message'] = __("Oops, something went wrong, please try again","chow");
			} else {
				$result['type'] = "success";
				$result['message'] = __("Recipe was removed from the list","chow");
			}
		} else {
			$faved_posts = array();
			if(isset( $_COOKIE['foodiepress-favposts'] )) {
				$faved_posts = $_COOKIE['foodiepress-favposts'];
				$posts_to_fav = explode(',', $faved_posts);
			}
			$posts_to_fav = array_diff($posts_to_fav, array($post_id));
			$posts_to_fav = implode(',', $posts_to_fav);
			$expire = time()+60*60*24*30;
    		setcookie("foodiepress-favposts", $posts_to_fav, $expire, "/");
    		$result['type'] = "success";
			$result['message'] = __("Recipe was removed from the list","chow");
		}

	   	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	      $result = json_encode($result);
	      echo $result;
	   	} else {
	      header("Location: ".$_SERVER["HTTP_REFERER"]);
	   	}

	   die();

	}	


	function get_user_id() {
	    global $current_user;
	    wp_get_current_user();
	    return $current_user->ID;
	}

	function get_faved_post() {
		return get_user_meta($this->get_user_id(), 'foodiepress-fav-posts', true);
	}

	function check_if_added($id) {
		$favorite_post_ids = $this->get_faved_post();
		if ($favorite_post_ids) {
            foreach ($favorite_post_ids as $fav_id) {
                if ($fav_id == $id) { 
                	return true; 
                }
            }
        } 
        return false;
	}

	function foodiepress_fav_button($atts, $content = null) {
        extract(shortcode_atts(array(
            'id'=> '',
            ), $atts));
        if(empty($id)) {
        	global $post;
        	$id = $post->ID;
        }
		$nonce = wp_create_nonce("foodiepress_add_to_fav_nonce");
    	$link = admin_url('admin-ajax.php?action=add_to_fav&post_id='.$id.'&nonce='.$nonce);
    	if(is_user_logged_in()) {
	    	if($this->check_if_added($id)) {
	    		$myacount_id = ot_get_option('pp_account_page'); 
	    		$output = '<a href="'.get_permalink($myacount_id).'" class="foodiepress-added-to-fav">'.__('Browse Favourites','foodiepress').'</a>';
	    	} else {
	    		$output = '<a class="foodiepress-add-to-fav" data-nonce="' . $nonce . '" data-post_id="' . $id . '" href="' . $link . '"><i class="fa fa-heart"></i> '.__('Add to Favourites','foodiepress').'</a>';
	    	}
	    } else {
	    	if(isset( $_COOKIE['foodiepress-favposts'] )) {
	    		$faved_posts = $_COOKIE['foodiepress-favposts'];
				$posts_to_fav = explode(',', $faved_posts);
				if(in_array($id,$posts_to_fav)) {
					$output = '<a href="#" class="foodiepress-added-to-fav favs-popup">'.__('Browse Favourites','foodiepress').'</a>';
				} else {
					$output = '<a class="foodiepress-add-to-fav" data-nonce="' . $nonce . '" data-post_id="' . $id . '" href="' . $link . '"><i class="fa fa-heart"></i> '.__('Add to Favourites','foodiepress').'</a>';	
				}
	    	} else {
	    		$output = '<a class="foodiepress-add-to-fav" data-nonce="' . $nonce . '" data-post_id="' . $id . '" href="' . $link . '"><i class="fa fa-heart"></i> '.__('Add to Favourites','foodiepress').'</a>';	
	    	}

	    }
	   	return $output;
    }
}


$FoodiePressFavs = new FoodiePressFavs();
	


?>