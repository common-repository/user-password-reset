<?php 
/****** Register Main Menu ******/
add_action('admin_menu', 'UPR_Plugin_Menu');



/****** Create Menu ******/
function UPR_Plugin_Menu(){

	add_menu_page( 'User Password Reset', 'User Password Reset', 'manage_options', 'user_password_reset', 'UPR_Page_Tuning');
	add_submenu_page( 'user_password_reset', 'Author', 'Author', 'manage_options', 'user_password_reset_author',  'UPR_Page_Author');

}



/****** Create Menu Page ******/
function UPR_Page_Tuning()
{
	if( current_user_can( 'manage_options' )) {
        require( dirname( __FILE__ ) . '/tuning.php' );
		add_settings_section( "page_section", "Users", "UPRDisplayPageData", "UPR-Page-Section" );
		do_settings_sections("UPR-Page-Section");
	}
}

/****** Create Menu Page ******/
function UPR_Page_Author()
{
	if( current_user_can( 'manage_options' ) ) {
		require( dirname( __FILE__ ) . '/author.php' );
	}
}


