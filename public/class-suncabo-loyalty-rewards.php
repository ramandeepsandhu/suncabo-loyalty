<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://author
 * @since      1.0.0
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/public
 * @author     Ramandeep Sandhu <sandhuramansingh@gmail.com>
 */
class Suncabo_Loyalty_Rewards {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function process_google_reviews(){
		ob_start();
        require_once (plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/loyalty_rewards/google_reviews.php');
        $output = ob_get_clean();
        return $output;
	}

	public function process_follow_fb_page(){
		ob_start();
        require_once (plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/loyalty_rewards/follow_fb_page.php');
        $output = ob_get_clean();
        return $output;
	}

	public function coming_soon(){
		ob_start();
        require_once (plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/loyalty_rewards/coming_soon.php');
        $output = ob_get_clean();
        return $output;
	}

}