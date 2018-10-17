<?php
/**
 * Load Theme JavaScript and CSS
 *
 * @package    ChurchPress Genesis Starter
 * @subpackage Genesis
 * @copyright  Copyright (c) 2018, ChurchPress
 * @license    GPL-3.0+
 * @since      1.0.0
 */

add_action( 'wp_enqueue_scripts', 'cp_scripts' );
function cp_scripts() {
  wp_enqueue_script( 'genesis-sample-responsive-menu', get_stylesheet_directory_uri() . '/assets/js/responsive-menus.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
  wp_localize_script(
    'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

  wp_enqueue_script( 'genesis-sample', get_stylesheet_directory_uri() . '/assets/js/genesis-sample.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
  wp_enqueue_script( 'theme-js', get_stylesheet_directory_uri() . '/assets/js/main.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'cp_styles' );
function cp_styles() {

  // Load Google Fonts
  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), null );
  // Enable use of dashicons
  wp_enqueue_style( 'dashicons' );

}
