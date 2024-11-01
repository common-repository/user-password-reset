<?php 

/**
* Plugin Name: User Password Reset
* Plugin URI: https://umairshahblog.blogspot.com/user-password-reset
* Description: reset all and selected users password and also search user by role  
* Version: 1.0
* Author: Syed Umair Hussain Shah
* Author URI: https://umairshahblog.blogspot.com
**/


/***** No direct access to plugin PHP Files *****/

	defined( 'ABSPATH' ) or die( 'No script please!' );
	require_once( dirname( __FILE__ ) . '/admin/menu.php' );

	add_action( 'wp_ajax_nopriv_UPR_Ajax_Request', 'UPR_Ajax_Request' );
	add_action( 'wp_ajax_UPR_Ajax_Request', 'UPR_Ajax_Request' );
	function UPR_Ajax_Request() {
		check_ajax_referer( 'UPR_Ajax_Request', 'security' );
		$len = count($_POST['userID']);
		for($i = 0; $i < $len; $i++)
		{	
			$userID = sanitize_user($_POST['userID'][$i]);
			$generatedPassword = sanitize_text_field($_POST['generatedPassword']);
			wp_set_password( $generatedPassword, $userID );
		}
		echo "Password Reset Successfully";
		exit();
	}

	function UPR_Styles_And_Script() {
		wp_register_style('upr-styles', plugins_url('css/upr-style.css', __FILE__ ));
		wp_enqueue_style('upr-styles');
	}

	add_action( 'admin_init','UPR_Styles_And_Script');

?>