<?php
/**
 * Plugin Name: UCLA WordPress Admin Color Scheme
 * Plugin URI: https://github.com/uclaioes/ucla-wp-admin-color-scheme
 * Description: Adds optional UCLA light and dark color scheme to user profiles.
 * Version: 2.0.0
 * Requires PHP: 7.3
 * Author: Scott Gruber
 * Author URI: https://github.com/scottgruber
 * Text Domain: ucla-wp-admin-color-scheme
 * Domain Path: /languages
 */

namespace Custom_Color_Schemes;
use function add_action;
use function wp_admin_css_color;

const VERSION = '2.0';

/**
 * Helper function to get the URL for a color scheme stylesheet.
 *
 * @param string $color The folder name for the color scheme.
 * @return string
 */
function get_color_url( $color ) {
	$suffix = is_rtl() ? '-rtl' : '';
	return plugins_url( "$color/colors$suffix.css?v=" . VERSION, __FILE__ );
}

/**
 * Register custom admin color schemes.
 */
function add_colors() {

	wp_admin_css_color(
		'ucla-light-theme',
		__( 'UCLA Light', 'admin_schemes' ),
		get_color_url( 'ucla-light-theme' ),
		array( '#2774ae', '#003b5c', '#ffd100', '#f1f3f3' ),
		array(
			'base'    => '#003b5c',
			'focus'   => '#0079bf',
			'current' => '#ffd100',
		)
	);

	wp_admin_css_color(
		'ucla-dark-theme',
		__( 'UCLA Dark', 'admin_schemes' ),
		get_color_url( 'ucla-dark-theme' ),
		array( '#003b5c', '#2774ae', '#ffd100', '#f1f3f3' ),
		array(
			'base'    => '#f1f3f3',
			'focus'   => '#fff',
			'current' => '#fff',
		)
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\add_colors' );

/**
 * Set the default admin color scheme for new users.
 *
 * @param int $user_id The ID of the newly registered user.
 */
function set_default_admin_color_scheme( $user_id ) {
	// Set the default admin color scheme to our custom UCLA light theme.
	update_user_meta( $user_id, 'admin_color', 'ucla-light-theme' );
}
add_action( 'user_register', __NAMESPACE__ . '\set_default_admin_color_scheme' );

/**
 * Reset the admin color scheme to the default when the plugin is deactivated.
 *
 * This function looks for users whose admin color is set to either the UCLA light or dark theme
 * and updates them to the default 'fresh' scheme.
 */
function reset_admin_color_scheme() {
	$default_color = 'fresh';
	// Retrieve users who have a custom admin color scheme (either light or dark).
	$users = get_users( array(
		'meta_query' => array(
			array(
				'key'     => 'admin_color',
				'value'   => array( 'ucla-light-theme', 'ucla-dark-theme' ),
				'compare' => 'IN',
			),
		),
	) );
	// Loop through each user and set their admin color back to the default.
	foreach ( $users as $user ) {
		update_user_meta( $user->ID, 'admin_color', $default_color );
	}
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\reset_admin_color_scheme' );

/**
 * Update all users to the UCLA light theme when the plugin is activated.
 *
 * This function sets every user's admin color scheme to 'ucla-light-theme' upon plugin activation.
 */
function set_all_users_to_ucla_light_theme() {
	$users = get_users();
	foreach ( $users as $user ) {
		update_user_meta( $user->ID, 'admin_color', 'ucla-light-theme' );
	}
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\set_all_users_to_ucla_light_theme' );