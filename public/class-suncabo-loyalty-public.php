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
class Suncabo_Loyalty_Public {

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

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Suncabo_Loyalty_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Suncabo_Loyalty_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		   
		wp_enqueue_style( $this->plugin_name.'-bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-fontawesome', 'https://use.fontawesome.com/releases/v5.7.1/css/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/suncabo-loyalty-public.css', array(), $this->version, 'all' );

	}



	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Suncabo_Loyalty_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Suncabo_Loyalty_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name. '-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( $this->plugin_name . 'states', plugin_dir_url( __FILE__ ) . 'js/country-states.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/suncabo-loyalty-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'jquery-ui-datepicker' );

	    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
	    wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );

	    wp_enqueue_script( $this->plugin_name. 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

	    wp_enqueue_style( 'jquery-ui' );

		$localize = array(
            
            'slredeem_nonce' => wp_create_nonce('sl_redeem_nonce'),
            'sl_reward_nonce' => wp_create_nonce('sl_reward_nonce'),
            'apply_share_nonce' => wp_create_nonce('sl_social_share_nonce'),
            'revoke_coupon_nonce' => wp_create_nonce('sl_revoke_coupon_nonce'),
            'home_url' => get_home_url(), 
            'ajax_url' => admin_url('admin-ajax.php'),
            'admin_url' => admin_url(),
            //'plugin_url' => WLR_PLUGIN_URL,
            'followup_share_window_open' => 1,
            'social_share_window_open' => 1,
            'curr_user' => get_current_user_id()
        );

		wp_localize_script($this->plugin_name, 'sl_ajaxObj', $localize  );

		$this->sl_user_loggedin_scripts();

	}

	public function sl_user_loggedin_scripts(){
	    global $post;

	    // Pages
	    $login_page = get_option('sl_user_login_page');
	    $reg_page = get_option('sl_user_registration_page');
	    $myaccount_page = get_option('sl_user_my_account_page');
	    $forgotpass_page = get_option('sl_user_forgot_pass_page');
	    $sl_user_dashboard_page = get_option('sl_user_dashboard_page');
	    $current_page = get_the_ID();

	    // Check if the user is not logged in and on the my account page
	    if (is_user_logged_in() == false && $current_page == $sl_user_dashboard_page) {
	        $script = "window.location='" . esc_url(site_url('/sign-in/')) . "';";
	        wp_add_inline_script($this->plugin_name, $script, 'after');
	    } elseif ((is_user_logged_in() == true && $current_page == $login_page) || (is_user_logged_in() == true && $current_page == $reg_page) || (is_user_logged_in() == true && $current_page == $forgotpass_page)) {
	        $script = "window.location.href ='" . esc_url(get_permalink($sl_user_dashboard_page)) . "';";
	        wp_add_inline_script($this->plugin_name, $script, 'after');
	    }
	}

	public function sl_add_shortcode(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-points.php';

		$plugin_points = new Suncabo_Loyalty_Points();
		$plugin_user = new Suncabo_Loyalty_User( $this->plugin_name, $this->version );

		add_shortcode('sl_signup_form', array($plugin_user, 'sl_user_signup_form'));
		add_shortcode('sl_signin_form', array($plugin_user,'sl_user_signin_form'));
		add_shortcode('sl_forgot_password', array($plugin_user,'sl_user_forgot_password'));
		add_shortcode('sl_my_account', array($plugin_user,'sl_user_account'));
		add_shortcode('sl_user_dashboard', array($plugin_user,'sl_user_dashboard'));

		add_shortcode('sl_loyalty_subscriptions', array($plugin_points, 'sl_loyalty_subscriptions'));
		add_shortcode('sl_loyalty_points', array($plugin_points, 'sl_loyalty_points'));
	}

	public function sl_query_vars($vars){
		$vars[] = 'rcode'; // rewards is the name of variable you want to add       
		return $vars;
	}

	public function sl_custom_rewrite() {
    	add_rewrite_rule('^rewards/([a-z0-9\-\_]+)/?', 'rewards?rcode=$1', 'top');
	}

	public function sl_on_404(){

		if( is_404() ){
			
		}
	}

	public function sl_handle_404($wp){
		if (!is_404())
          return; //nothing to do because our page is non-existent

      	$reward_code = get_query_var( 'rcode' );

		if($reward_code){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-rewards.php';

			$class_rewards = new Suncabo_Loyalty_Rewards( $this->plugin_name, $this->version );
			$method = "process_{$reward_code}";
			
			try{
				$post_content = $class_rewards->$method();

				header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
		      	global $wp, $wp_query;
		      	$fake = new stdClass();
		      	$post_id = -99;
		      	$fake->ID = -99;
		     	$fake->post_title = '';
		     	$fake->post_author = 1;
				$fake->post_date = current_time( 'mysql' );
				$fake->post_date_gmt = current_time( 'mysql', 1 );
		      	$fake->post_content = $post_content;
		      	$fake->post_type = 'page';
		      	$fake->post_name = 'fake-page-' . rand( 1, 99999 );
		      	$fake->post_parent = 0;
		      	$fake->post_status = 'publish';
		      	$fake->comment_status = 'closed';
		      	$fake->ping_status = 'closed';
		      	$fake->filter = 'raw';
		      	
		      	$wp_post = new WP_Post( $fake );
		      	wp_cache_add( $post_id, $wp_post, 'posts' );

		      	$wp_query->post = $wp_post;
				$wp_query->posts = array( $wp_post );
				$wp_query->queried_object = $wp_post;
				$wp_query->queried_object_id = $post_id;
				$wp_query->found_posts = 1;
				$wp_query->post_count = 1;
				$wp_query->max_num_pages = 1; 
				$wp_query->is_page = true;
				$wp_query->is_singular = true; 
				$wp_query->is_single = false; 
				$wp_query->is_attachment = false;
				$wp_query->is_archive = false; 
				$wp_query->is_category = false;
				$wp_query->is_tag = false; 
				$wp_query->is_tax = false;
				$wp_query->is_author = false;
				$wp_query->is_date = false;
				$wp_query->is_year = false;
				$wp_query->is_month = false;
				$wp_query->is_day = false;
				$wp_query->is_time = false;
				$wp_query->is_search = false;
				$wp_query->is_feed = false;
				$wp_query->is_comment_feed = false;
				$wp_query->is_trackback = false;
				$wp_query->is_home = false;
				$wp_query->is_embed = false;
				$wp_query->is_404 = false; 
				
				$wp_query->is_paged = false;
				$wp_query->is_admin = false; 
				$wp_query->is_preview = false; 
				$wp_query->is_robots = false; 
				$wp_query->is_posts_page = false;
				$wp_query->is_post_type_archive = false;
		      	
		      	$GLOBALS['wp_query'] = $wp_query;
  				$wp->register_globals();

			}catch (Error $e) {
				$post_content = $class_rewards->coming_soon();
				//echo 'Some thing wrong! ',  $e->getMessage(), "\n";
			}
			return true;
		}
	}


	public function sl_redirect_sign_in_page_not_logged_in(){
		if (is_user_logged_in() == false && is_page('home')){
			wp_redirect(esc_url(home_url('/sign-in/')));
		}
	}

	
	

	public function sl_user_is_user_activated(){

		if (is_user_logged_in()){
			global $current_user;
			wp_get_current_user();

			$username = $current_user->user_login;
			//$sl_user = wp_get_current_user();

			// First need to get the user object
			$sl_user = get_user_by('login', $username);
			
			if (!isset($sl_user) && empty($sl_user))
			{
				$sl_user = get_user_by('email', $username);
				if (isset($sl_user) && !empty($sl_user))
				{
					return $username;
				}
			}

			$sl_userStatus = get_user_meta($sl_user->ID, 'user_activation_key', 1);

			if ( in_array( 'administrator', (array) $sl_user->roles ) ) {
		    	//The user has the "author" role
				$sl_login_page = esc_url(home_url('/sign-in/'));
				//if ($iusisu_userStatus == 0)
				if (!empty($sl_userStatus))
				{
					wp_redirect($sl_login_page . "?login=failed");
					exit;
				}
			}
		}
	}
}