<?php
/*********************************************** */
/* ENQUEUE SCRIPTS AND STYLES ********** */

// Translations ************** 
load_plugin_textdomain( 'sideoslogin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

function admin_scripts() {
	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script('utility', plugin_dir_url( __FILE__ ).'/scripts/utility.js');
	$params = array('messages' => array(__('Could not send credential', 'sideoslogin'), __('Credential sent...', 'sideoslogin')));
	wp_localize_script( 'utility', 'SIDEOS', $params );

	// Styles ************** 
	wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ).'/styles/ssilogin.css' );
}

function frontend_scripts() {
	wp_enqueue_script( 'jquery' );

	// Utility to manage Ajax functionality ************** 
	wp_enqueue_script('utility', plugin_dir_url( __FILE__ ).'/scripts/utility.js');
	$params = array('messages' => array(__('Could not send credential', 'sideoslogin'), __('Credential sent...', 'sideoslogin')));
	wp_localize_script( 'utility', 'SIDEOS', $params );

	// Styles ************** 
	wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ).'/styles/ssilogin.css' );
}

function login_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('qrcode', plugin_dir_url( __FILE__ ).'/scripts/qrcode.js');

	wp_enqueue_script('ssilogin', plugin_dir_url( __FILE__ ).'/scripts/ssilogin.js');
	$params = array('sideosurl' => get_option('sideosurl'));
	wp_localize_script( 'ssilogin', 'SIDEOS', $params );

	wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ).'/styles/ssilogin.css' );
}



add_action( 'admin_enqueue_scripts', 'admin_scripts' );
add_action( 'wp_enqueue_scripts', 'frontend_scripts' );
add_action( 'login_enqueue_scripts', 'login_scripts' );

 