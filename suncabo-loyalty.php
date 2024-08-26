<?php
error_reporting(E_ALL);
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://author
 * @since             1.0.0
 * @package           Suncabo_Loyalty
 *
 * @wordpress-plugin
 * Plugin Name:       SunCabo Loyalty Program
 * Plugin URI:        https://suncabo.com
 * Description:       Loyalty Program to Earn Points
 * Version:           1.0.0
 * Author:            Ramandeep Sandhu
 * Author URI:        https://author/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       suncabo-loyalty
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SUNCABO_LOYALTY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-suncabo-loyalty-activator.php
 */
function activate_suncabo_loyalty() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-suncabo-loyalty-activator.php';
	Suncabo_Loyalty_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-suncabo-loyalty-deactivator.php
 */
function deactivate_suncabo_loyalty() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-suncabo-loyalty-deactivator.php';
	Suncabo_Loyalty_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_suncabo_loyalty' );
register_deactivation_hook( __FILE__, 'deactivate_suncabo_loyalty' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-suncabo-loyalty.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_suncabo_loyalty() {

	$plugin = new Suncabo_Loyalty();
	$plugin->run();

}

run_suncabo_loyalty();