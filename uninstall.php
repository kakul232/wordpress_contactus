<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();


	$meta_type  = 'user';
	$user_id    = 0; // This will be ignored, since we are deleting for all users.
	$meta_value = ''; // Also ignored. The meta will be deleted regardless of value.
	$delete_all = true;

	delete_metadata( $meta_type, $user_id, 'frankly', $meta_value, $delete_all );
	delete_metadata( $meta_type, $user_id, 'frankly_id', $meta_value, $delete_all );
	delete_metadata( $meta_type, $user_id, 'frankly_token', $meta_value, $delete_all );


	$check_title=get_page_by_title('Contact Us', 'OBJECT', 'page') ;
	wp_delete_post($check_title->ID);	

	delete_option('addASK');


	function detect_plugin_deactivation(  $plugin, $network_activation ) {
    $check_title=get_page_by_title('Contact Us', 'OBJECT', 'page') ;
	wp_delete_post($check_title->ID);
}
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );

?>