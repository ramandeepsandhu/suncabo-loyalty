<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://author
 * @since      1.0.0
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/includes
 * @author     Ramandeep Sandhu <sandhuramansingh@gmail.com>
 */
class Suncabo_Loyalty {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Suncabo_Loyalty_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SUNCABO_LOYALTY_VERSION' ) ) {
			$this->version = SUNCABO_LOYALTY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'suncabo-loyalty';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Suncabo_Loyalty_Loader. Orchestrates the hooks of the plugin.
	 * - Suncabo_Loyalty_i18n. Defines internationalization functionality.
	 * - Suncabo_Loyalty_Admin. Defines all hooks for the admin area.
	 * - Suncabo_Loyalty_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-suncabo-loyalty-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-suncabo-loyalty-input.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-suncabo-loyalty-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 * 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-suncabo-loyalty-points.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-suncabo-loyalty-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-public.php';
		

		$this->loader = new Suncabo_Loyalty_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Suncabo_Loyalty_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Suncabo_Loyalty_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Suncabo_Loyalty_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'sl_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'sl_admin_settings' );

		$this->loader->add_action( 'wp_ajax_sl_bulk_action', $plugin_admin, 'sl_bulk_action' );
		$this->loader->add_action( 'wp_ajax_sl_birthday_email', $plugin_admin, 'sl_birthday_email' );
		
		
	} 

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $Suncabo_Loyalty_Input;
		$plugin_public = new Suncabo_Loyalty_Public( $this->get_plugin_name(), $this->get_version() );

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-points.php';

		$plugin_points = new Suncabo_Loyalty_Points();
		
		$Suncabo_Loyalty_Input = new Suncabo_Loyalty_Input();
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'sl_add_shortcode' );
		
		$this->loader->add_action( 'wp_ajax_validate_user', 'Suncabo_Loyalty_User', 'sl_validate_user' );
		$this->loader->add_action( 'wp_ajax_nopriv_validate_user', 'Suncabo_Loyalty_User', 'sl_validate_user' );

		$this->loader->add_action( 'wp_ajax_register_user', 'Suncabo_Loyalty_User', 'sl_register_user' );
		$this->loader->add_action( 'wp_ajax_nopriv_register_user', 'Suncabo_Loyalty_User', 'sl_register_user' );

		$this->loader->add_action( 'wp_ajax_sl_update_user', 'Suncabo_Loyalty_User', 'sl_update_user' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_update_user', 'Suncabo_Loyalty_User', 'sl_update_user' );

		
		$this->loader->add_action( 'wp_ajax_sl_forgot_password', 'Suncabo_Loyalty_User', 'sl_verify_forgot_password' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_forgot_password', 'Suncabo_Loyalty_User', 'sl_verify_forgot_password' );

		$this->loader->add_action( 'wp_ajax_sl_change_password', 'Suncabo_Loyalty_User', 'sl_change_password' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_change_password', 'Suncabo_Loyalty_User', 'sl_change_password' );

		$this->loader->add_action( 'wp_ajax_sl_email_preferences', 'Suncabo_Loyalty_User', 'sl_email_preferences' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_email_preferences', 'Suncabo_Loyalty_User', 'sl_email_preferences' );

		//$this->loader->add_action( 'wp_authenticate', 'sl_user_is_user_activated', 30, 2 );
		$this->loader->add_action( 'get_header', $plugin_public, 'sl_user_is_user_activated' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'sl_user_loggedin_scripts' );

		$this->loader->add_action( 'wp_ajax_sl_social_share', $plugin_points, 'sl_social_share' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_social_share', $plugin_points, 'sl_social_share' );

		$this->loader->add_action( 'wp_ajax_sl_social_followup', $plugin_points, 'sl_social_followup' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_social_followup', $plugin_points, 'sl_social_followup' );

		$this->loader->add_action( 'wp_ajax_sl_social_review', $plugin_points, 'sl_social_review' );
		$this->loader->add_action( 'wp_ajax_nopriv_sl_social_review', $plugin_points, 'sl_social_review' );
		

		$this->loader->add_action( 'init', $plugin_public, 'sl_custom_rewrite' );
		//$this->loader->add_action( 'template_redirect', $plugin_public, 'sl_on_404' );
		$this->loader->add_action( 'query_vars', $plugin_public, 'sl_query_vars' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'sl_handle_404' );

		add_filter( 'show_admin_bar', '__return_false' );


		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Suncabo_Loyalty_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}