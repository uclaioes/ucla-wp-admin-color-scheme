<?php
/**
 * Plugin Name: UCLA WordPress Admin Color Scheme
 * Plugin URI: https://github.com/uclaioes/ucla-wp-admin-color-scheme
 * Description: Adds optional UCLA light and dark color scheme to user profiles.
 * Version: 1.0.0
 * Requires PHP: 7.3
 * Author: Scott Gruber
 * Author URI: https://github.com/scottgruber
 * Text Domain: ucla-wp-admin-color-scheme
 * Domain Path: /languages
 */

 
namespace Custom_Color_Schemes;
use function add_action;
use function wp_admin_css_color;

const VERSION = '1.0';

/**
 * Helper function to get stylesheet URL.
 *
 * @param string $color The folder name for this color scheme.
 */
function get_color_url( $color ) {
	$suffix = is_rtl() ? '-rtl' : '';
	return plugins_url( "$color/colors$suffix.css?v=" . VERSION, __FILE__ );
}

/**
 * Register color schemes.
 */
function add_colors() {

	wp_admin_css_color(
		'ucla-light-theme',
		__( 'UCLA Light', 'admin_schemes' ),
		get_color_url( 'ucla-light-theme' ),
		array( '#2774ae', '#003b5c', '#ffd100', '#f1f3f3' ),
		array(
			'base' => '#003b5c',
			'focus' => '#0079bf',
			'current' => '#ffd100',
		)
	);

	wp_admin_css_color(
		'ucla-dark-theme',
		__( 'UCLA Dark', 'admin_schemes' ),
		get_color_url( 'ucla-dark-theme' ),
		array( '#003b5c', '#2774ae', '#ffd100', '#f1f3f3' ),
		array(
			'base' => '#f1f3f3',
			'focus' => '#fff',
			'current' => '#fff',
		)
	);

}

add_action( 'admin_init', __NAMESPACE__ . '\add_colors' );
