<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.herooutoftime.com
 * @since             1.0.0
 * @package           Cf7_Statifier
 *
 * @wordpress-plugin
 * Plugin Name:       CF7 Static Form & Mail
 * Plugin URI:        https://github.com/herooutoftime/cf7-static-contents
 * Description:       Load static files into form content, mail body & additional mail body.
 * Version:           1.0.0
 * Author:            Andreas Bilz
 * Author URI:        http://www.herooutoftime.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf7-statifier
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cf7-statifier-activator.php
 */
function activate_cf7_statifier() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-statifier-activator.php';
	Cf7_Statifier_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cf7-statifier-deactivator.php
 */
function deactivate_cf7_statifier() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-statifier-deactivator.php';
	Cf7_Statifier_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cf7_statifier' );
register_deactivation_hook( __FILE__, 'deactivate_cf7_statifier' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf7-statifier.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cf7_statifier() {

	$plugin = new Cf7_Statifier();
	$plugin->run();

}
run_cf7_statifier();
