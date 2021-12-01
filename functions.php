<?php

//////////////////////////////////////////////////////
// Comment disable
add_filter( 'comments_open', '__return_false' );

//////////////////////////////////////////////////////
// XML-RPC disable
add_filter( 'xmlrpc_enabled', '__return_false');
if ( function_exists( 'add_filter' ) ) {
	add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
}
function remove_xmlrpc_pingback_ping($methods) {
	unset($methods['pingback.ping']);
	return $methods;
}

//////////////////////////////////////////////////////
// remove emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );

//////////////////////////////////////////////////////
// Filter at main query
function child_for_lightning_query( $query ) {
 	if ( $query->is_home() && $query->is_main_query() ) {
	}
}
add_action( 'pre_get_posts', 'child_for_lightning_query' );

//////////////////////////////////////////////////////
// Enqueue Scripts
function child_for_lightning_scripts() {

	// css
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );

	// Google Fonts
	wp_enqueue_style( 'child-for-lightning-google-font', '//fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet', false, null, 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_for_lightning_scripts' );

//////////////////////////////////////////////////////
// login logo
function child_for_lightning_login_head() {

	$url = get_stylesheet_directory_uri() .'/images/logo.svg';
	echo '<style type="text/css">.login h1 a { background-image:url(' .$url .'); height: 60px; width: 320px; background-size: 100% 100%;}</style>';
}
add_action('login_head', 'child_for_lightning_login_head');

//////////////////////////////////////////////////////
// login logo link url
function login_logo_url() {
    return get_bloginfo('url');
}
add_filter('login_headerurl', 'login_logo_url');

//////////////////////////////////////////////////////
// set favicon
function child_for_lightning_favicon() {
	echo '<link rel="shortcut icon" type="image/x-icon" href="' .get_stylesheet_directory_uri() .'/images/favicon.ico" />'. "\n";
	echo '<link rel="apple-touch-icon" href="' .get_stylesheet_directory_uri() .'/images/webclip.png" />'. "\n";
}
add_action( 'wp_head', 'child_for_lightning_favicon' );

//////////////////////////////////////////////////////
// activated theme
function child_for_lightning_after_switch_theme () {
	// enable theme option for editor
	$role = get_role( 'editor' );
	$role->add_cap( 'edit_theme_options' ); 
}
//add_action('after_switch_theme', 'child_for_lightning_after_switch_theme');

//////////////////////////////////////////////////////
// deactivated theme
function child_for_lightning_switch_theme () {
	// disable theme option for editor
	$role = get_role( 'editor' );
	$role->remove_cap( 'edit_theme_options' ); 
}
//add_action('switch_theme', 'child_for_lightning_switch_theme');

//////////////////////////////////////////////////////
// remove default theme customize
function child_for_lightning_customize_register_menu( $wp_customize ) {

	$wp_customize->remove_control( 'header_image' );
	$wp_customize->remove_section( 'static_front_page' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'custom_css' );
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'title_tagline' );

	// remove customize menu for editor
	if( !current_user_can( 'administrator' )){
		$wp_customize->remove_panel( "widgets" );
		remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
	}
}
//add_action( 'customize_register', 'child_for_lightning_customize_register_menu' );

//////////////////////////////////////////////////////
// admin_init
function child_for_lightning_admin_init() {
	// hide the update message for not administrator
	if( !current_user_can( 'administrator' )){
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'admin_notices', 'maintenance_nag', 10 );
	}
}
add_filter( 'admin_init', 'child_for_lightning_admin_init' );