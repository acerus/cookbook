<?php 


//turn breadcrumbs
function chow_bbp_no_breadcrumb ($param) { return true; }
add_filter ('bbp_no_breadcrumb', 'chow_bbp_no_breadcrumb'); 

function chow_bbp_hide_before ($args = array() ) {
	$args['before'] = '';
	return $args;
}
add_filter ('bbp_before_get_forum_subscribe_link_parse_args','chow_bbp_hide_before') ;

?>