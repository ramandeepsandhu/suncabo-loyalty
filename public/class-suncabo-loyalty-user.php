<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://author
 * @since      1.0.0
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/user
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/user
 * @author     Ramandeep Sandhu <sandhuramansingh@gmail.com>
 */
class Suncabo_Loyalty_User {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}

	//REMOVE THE ADMIN BAR FROM THE FRONT END
	/*if (!current_user_can('administrator'))
	{
		add_filter('show_admin_bar', '__return_false');
	}*/
	//END REMOVE THE ADMIN BAR FROM THE FRONT END

	
	

	public function sl_user_signin_form(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-user-signin.php';
		return sl_signin_form();
	}

	static function sl_validate_user(){
		if (isset($_POST['sl_nonce']) || wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['sl_nonce'])),'sl_user_login' ) ){ 
			$sl_email = sanitize_text_field($_POST['sl_email']);
			$sl_password = sanitize_text_field($_POST['sl_password']);
			//$sl_rememberme = sanitize_text_field($_POST['sl_rememberme']);
				
			global $wpdb;
			$sl_email = $wpdb->escape(esc_attr($sl_email));
			$sl_password = $wpdb->escape(esc_attr($sl_password));  
			//$sl_remember = $wpdb->escape(esc_attr($sl_rememberme)); 

			$sl_remember = true;
			/*if($sl_remember){
				$sl_remember = true;
			}else{
				$sl_remember = ""; 
			}*/

			$sl_creds = array();
			$sl_creds['user_login'] = $sl_email;
			$sl_creds['user_password'] = $sl_password;
			$sl_creds['remember'] = $sl_remember;
			$sl_user = wp_signon( $sl_creds , is_ssl() );

			$sl_userID = $sl_user->ID;

			if(!empty($sl_userID) && $sl_creds['remember'] == true) {
				wp_set_current_user( $sl_userID, $sl_email );
				wp_set_auth_cookie( $sl_userID, true, false );
			}

			if ( is_wp_error( $sl_user ) ) {   
				$errormsg = esc_html__("Invalid email/user name or password", "suncabo-loyalty");
				$response['message'] = $errormsg;
				$response['code'] = intval(0);
				echo wp_json_encode($response);
				wp_die();
			}else {
				$sucessmsg = esc_html__("You sign in successfully", "suncabo-loyalty");
				$sucessmsg = esc_html(get_option('sl_login_msg') ? get_option('sl_login_msg') : $sucessmsg );
				$response['redirect_url'] = esc_url(site_url('/dashboard/'));
				$response['code'] = intval(1);
				$response['message'] = $sucessmsg;
				echo wp_json_encode($response);
				wp_die();
			}			
		}else{
			wp_die(esc_html('Something went to wrong'));
		}	
		wp_die();
	}

	public function sl_user_signup_form(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-user-signup.php';
		return sl_signup_form();
	}

	static function sl_register_user(){ 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-points.php';
		$plugin_points = new Suncabo_Loyalty_Points();

		$loyalty_program = $plugin_points->get_loyalty_program_by_campaign_type('account_signup');
		
		
		if(isset($_POST['sl_nonce']))
		{
			$sl_nonce = sanitize_text_field($_POST['sl_nonce']);
		}

		if ( isset( $sl_nonce ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $sl_nonce ) ) , 'sl_user_registration' ) )
		{	
			// Recommended
			parse_str($_POST['data'], $output);
			
			$sl_firstname 	= sanitize_text_field($output['sl_txt_first_name']);
			$sl_lastname 	= sanitize_text_field($output['sl_txt_last_name']);
			$sl_email 		= sanitize_text_field($output['sl_txt_email']);
			$sl_dob 		= sanitize_text_field($output['sl_txt_dob']);
			$sl_apt 		= sanitize_text_field($output['sl_txt_apt']);
			$sl_password 	= sanitize_text_field($output['sl_txt_password']);
			$sl_street 		= sanitize_text_field($output['sl_txt_street']);
			$sl_city 		= sanitize_text_field($output['sl_txt_city']);
			$sl_state 		= sanitize_text_field($output['sl_txt_state']);
			$sl_zipcode 	= sanitize_text_field($output['sl_txt_zip_code']);
			$sl_country 	= sanitize_text_field($output['sl_txt_country']);

			$sl_email_preference = isset($output['sl_email_preference'])?'send':'do-not-send';
			$sl_full_name = $sl_firstname . ' ' . $sl_lastname;

		} else {
			wp_die(esc_html('Nonce is invalid'));
		}
		//user role from plugin management
		$sl_user_role = (get_option('sl_role') ? get_option('sl_role') : get_option('default_role'));
		$error = array();
		
		/*if (strpos($sl_username, ' ') !== FALSE)
		{
			$error['sl_username_space'] = "<p class='sl_user-sign-up sl_error'>". esc_html__('Invalid username. Spaces are not allowed.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if (empty($sl_username))
		{
			$error['sl_username_empty'] = "<p class='sl_user-sign-up sl_error'>". esc_html__('Please provide a username.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if (username_exists($sl_username))
		{
			$error['sl_username_exists'] = "<p class='sl_user-sign-up sl_error'>". esc_html__('User name already exists.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}*/
		if (empty($sl_password))
		{
			$error['sl_password'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('Please provide a valid password.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if (is_email($sl_email) == false)
		{
			$error['sl_email_valid'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('Invalid email address.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if (email_exists($sl_email))
		{
			$error['sl_email_existence'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('Email already exists','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}

		if(empty($sl_street)){
			$error['sl_street'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('This field is required.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if(empty($sl_city)){
			$error['sl_city'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('This field is required.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if(empty($sl_state)){
			$error['sl_state'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('This field is required.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if(empty($sl_zipcode)){
			$error['sl_zipcode'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('This field is required.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		if(empty($sl_country)){
			$error['sl_country'] = "<p class='sl_user-sign-up sl_error'>".  esc_html__('This field is required.','suncabo-loyalty') ."</p>";
			echo wp_json_encode($error);
			wp_die();
		}
		
		if (count($error) == 0)
		{
			//combining in one header the From and content-type
			$sl_userdata = array(
				//'user_login' => sanitize_text_field($sl_username),
				'user_login' => sanitize_text_field($sl_email),
				'user_pass' => sanitize_text_field($sl_password),
				'user_email' => sanitize_text_field($sl_email),
				'first_name' => $sl_firstname,
				'last_name' => $sl_lastname,
			);
			$user_id = wp_insert_user($sl_userdata);
			if (!empty($user_id))
			{ 
				$user = new WP_User($user_id);
				$user->set_role($sl_user_role);
				$headers[] = wp_kses_post('Content-Type: text/html; charset=UTF-8');
				$headers[] = wp_kses_post("From: " . (get_option('sl_user_from_email') ? get_option('sl_user_from_email') : get_option('admin_email')) . " \r\n");
				// create md5 code to verify later
				$code = md5(time());
				// make it into a code to send it to user via email
				$string = array('id' => $user_id, 'code' => $code);
				// create the activation code and activation status
				$null_val = 0;
				//add_user_meta($user_id, 'firstname', sanitize_text_field($sl_firstname));
				//add_user_meta($user_id, 'lastname', sanitize_text_field($sl_lastname));
				add_user_meta($user_id, 'dob', sanitize_text_field($sl_dob));
				add_user_meta($user_id, 'street', sanitize_text_field($sl_street));
				add_user_meta($user_id, 'apt', sanitize_text_field($sl_apt));
				add_user_meta($user_id, 'city', sanitize_text_field($sl_city));
				add_user_meta($user_id, 'state', sanitize_text_field($sl_state));
				add_user_meta($user_id, 'zipcode', sanitize_text_field($sl_zipcode));
				add_user_meta($user_id, 'country', sanitize_text_field($sl_country));

				add_user_meta($user_id, 'is_activated', sanitize_text_field($null_val));
				add_user_meta($user_id, 'activation_code', sanitize_text_field($code));
				add_user_meta($user_id, 'email_preference', $sl_email_preference);

				
				// create the url
				//$url = site_url() . '/?act=' . base64_encode(serialize($string));
				$url = site_url() . '/?act=' . urlencode(wp_json_encode($string));

				$sl_static_wlcm_msg = "Welcome ! you have registered successfully";

				$activate_urls = "<a href=".esc_url($url).">".esc_html__('Please activate your account','suncabo-loyalty')."</a>";

				$subject = (get_option('sl_user_subject') !== '' ? esc_html(get_option('sl_user_subject')) : esc_html($sl_static_wlcm_msg . get_bloginfo("name")));

				if(!empty(get_option('sl_user_registration_email_body')))
				{
					$activate_url = wp_kses($activate_urls,
						array( 'a'=> array(
							'href' => array(),
							'title' => array(),
						),
				    	'br'     => array(),
				    	'em'     => array(),
				    	'strong' => array(),
					));
					$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_registration_email_body'))));
					$message = wp_kses_post(html_entity_decode($html_format_message_display));
					$site_name = wp_kses_post(get_bloginfo( 'name' ));
					$message =  wp_kses_post(str_replace('{user_name}', $sl_full_name,  $message));
					$message =  wp_kses_post(str_replace('{site_name}', $site_name,  $message));
					$message =  wp_kses_post(str_replace('{Please activate your account}', $activate_url,  $message));
				}
				else
				{	
					$activate_urls = "<a href='" . esc_url($url) . "'> ".esc_html__('Please activate your account','suncabo-loyalty')."</a>";

					$activate_url = wp_kses($activate_urls,
						array( 'a'=> array(
							'href' => array(),
							'title' => array(),
						),
				    	'br'     => array(),
				    	'em'     => array(),
				    	'strong' => array(),
					));

					$message .= wp_kses_post("Hello " . $sl_full_name . "," . "<br>");
					$message .= wp_kses_post("<br>");
					$message .= wp_kses_post("Welcome to our site : " . get_bloginfo("name") . "<br>");
					$message .= wp_kses_post("Please click the following link to activate your account");
					$message .= wp_kses_post("<br>");
					$message .= $activate_url;
					$message .= wp_kses_post("<br>");
					$message .= wp_kses_post("Note : After click on above link, you need to Sign In, so your account will be activate"."<br>");
				}

				if (!empty(get_option('sl_user_signature')))
				{
					$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_signature'))));
					$message .= wp_kses_post(html_entity_decode($html_format_message_display));
				}
				else
				{
					$message .= wp_kses_post("-" . "<br>");
					$message .= wp_kses_post("Thanks," . "<br>");
					$message .= wp_kses_post(get_bloginfo('name') . " Team" . "<br>");
				}
				wp_mail($sl_email, $subject, $message,$headers);

				if (!empty(get_option('admin_email')))
				{	
					$sl_reg_msg = 'New user registered on our site';
					$headers[] = wp_kses_post('Content-Type: text/html; charset=UTF-8');
					$headers[] = wp_kses_post("From: " . (get_option('sl_user_from_email') ? get_option('sl_user_from_email') : get_option('admin_email')) . " \r\n");
					$subject = (get_option('admin_subject') !== '' ? esc_html(get_option('admin_subject')) : esc_html($sl_reg_msg));

					if(!empty(get_option('sl_user_registration_email_body_admin')))
					{
						$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_registration_email_body_admin'))));
						$message = wp_kses_post(html_entity_decode($html_format_message_display));
						$message =  wp_kses_post(str_replace('{user_name}', $sl_full_name,  $message));
						$message =  wp_kses_post(str_replace('{user_email}', $sl_email, $message));
					}
					else
					{
						$message = wp_kses_post("Hello Admin," . "<br>");
						$message .= wp_kses_post("\r\n");
						$message .= wp_kses_post("New user is registered on our site" . "<br>");
						$message .= wp_kses_post("<br>");
						$message .= wp_kses_post("Here are details :" . "<br>");
						$message .= wp_kses_post("Name : " . $sl_full_name  . "<br>");
						$message .= wp_kses_post("Email : " . $sl_email . "<br>");
						$message .= wp_kses_post("<br>");
					}
						
					if (!empty(get_option('sl_user_signature')))
					{
						$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_signature'))));
						$message .= wp_kses_post(html_entity_decode($html_format_message_display));
					}
					else
					{
						$message .= wp_kses_post("-" . "<br>");
						$message .= wp_kses_post("Thanks," . "<br>");
						$message .= wp_kses_post(get_bloginfo('name') . " Team" . "<br>");
					}
					wp_mail(get_option('admin_email'), $subject, $message,$headers);
				}
					
				$sl_resultdata['sl_success_msg'] = html_entity_decode(get_option('sl_registration_msg') ? esc_html(get_option('sl_registration_msg')) : esc_html__("User sign Up successfully, You will be get email. Please activate your account from it"));

				$loyalty_program = $plugin_points->get_loyalty_program_by_campaign_type('account_signup');
				if($loyalty_program){
					$loyalty_program['current_user_id'] = $user_id;
					if($plugin_points->add_loyalty_points($loyalty_program)){
						$plugin_points->calculate_total_point();
					}
				}
				echo wp_json_encode($sl_resultdata);
				wp_die();
			}				
		}
	}

	static function sl_update_user(){

		if ( is_user_logged_in() ) {

			if(isset($_POST['sl_nonce'])){
				$sl_nonce = sanitize_text_field($_POST['sl_nonce']);
			}

			global $current_user;
			$user_ID = get_current_user_id();

			if ( isset( $sl_nonce ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $sl_nonce ) ) , 'sl_update_user' ) ){

				parse_str($_POST['data'], $post);

				$firstName = sanitize_text_field($post['sl_txt_first_name']);
				$lastName = sanitize_text_field($post['sl_txt_last_name']);

				wp_update_user([
				    'ID' => $user_ID, // this is the ID of the user you want to update.
				    'first_name' => $firstName,
				    'last_name' => $lastName,
				]);
				update_user_meta($current_user->ID, 'dob', sanitize_text_field($post['sl_txt_dob']));
				update_user_meta($current_user->ID, 'street', sanitize_text_field($post['sl_txt_street']));
				update_user_meta($current_user->ID, 'apt', sanitize_text_field($post['sl_apt']));
				update_user_meta($current_user->ID, 'city', sanitize_text_field($post['sl_txt_city']));
				update_user_meta($current_user->ID, 'state', sanitize_text_field($post['sl_txt_state']));
				update_user_meta($current_user->ID, 'zipcode', sanitize_text_field($post['sl_txt_zip_code']));
				update_user_meta($current_user->ID, 'country', sanitize_text_field($post['sl_txt_country']));
				//update_user_meta($current_user->ID, 'email', sanitize_text_field($post['sl_txt_email']));
				update_user_meta($current_user->ID, 'phone_number', sanitize_text_field($post['sl_txt_phone_number']));
				update_user_meta($current_user->ID, 'alt_phone_number', sanitize_text_field($post['sl_txt_alt_phone_number']));

				$sl_resultdata['message'] = 'Account details updated successfully!';
				$sl_resultdata['result'] = 'success';

				echo wp_json_encode($sl_resultdata);
				wp_die();

			}else{
				wp_die(esc_html('Nonce is invalid'));
			}
		}
		wp_die();
	}



	public function sl_user_forgot_password(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-user-forgot-password.php';
		return sl_forgot_password_form();
	}

	static function sl_verify_forgot_password(){

		if(isset($_POST['sl_username_email']) || isset($_POST['sl_wpnonce']) || !empty($_POST['sl_username_email'])){

			if ( ! isset( $_POST['sl_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['sl_wpnonce'] ) ) , 'sl_forget_password' ) ){
	    		wp_die(esc_html('Something went to wrong...') );
			}else{
				$sl_fp_email_username = sanitize_text_field($_POST['sl_username_email']);			
				$sl_detailByusername = get_user_by( 'login' , trim( $sl_fp_email_username ) );
				$sl_detailByemail = get_user_by( 'email' , trim( $sl_fp_email_username ) );
				
				if($sl_detailByusername){
					$user_detail = $sl_detailByusername;
				}elseif($sl_detailByemail){
					$user_detail = $sl_detailByemail;
				}

				$user_id = $user_detail->ID;
				$sl_user_email = $user_detail->data->user_email;
				$sl_user_login = $user_detail->data->user_login;
				
				if(!empty($sl_user_email) || !empty($sl_user_login)){	
					$forgot_pass_static_msg = "Forget password";
					$sl_new_password = wp_generate_password( 12, true );
					$email = get_option('sl_user_from_email') ? esc_html(get_option('sl_user_from_email')) : esc_html(get_option('admin_email'));
					$to = esc_html($sl_user_email);
					$subject = (get_option('sl_userforgot_subject') ? esc_html(get_option('sl_userforgot_subject')) : esc_html($forgot_pass_static_msg));				
					$url = site_url().'/sign-in/';
					$url = esc_url($url);
					if(!empty(get_option('sl_user_forget_password_email_body'))){
						$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_forget_password_email_body'))));
						$message = wp_kses_post(html_entity_decode($html_format_message_display));
						$message =  wp_kses_post(str_replace('{user_name}', $sl_user_login, $message));
						$message =  wp_kses_post(str_replace('{user_email}', $sl_user_email, $message));
						$message =  wp_kses_post(str_replace('{user_password}', $sl_new_password, $message));
					}else{
						$click_url = '<a href="'.esc_url($url).'">'.esc_html__('Click here to login','suncabo-loyalty').'</a>';
						$cl_url = wp_kses($click_url,
							array( 'a'=> array(
								'href' => array(),
								'title' => array(),
							),
					    	'br'     => array(),
					    	'em'     => array(),
					    	'strong' => array(),
						));
						$message  .= wp_kses_post("Hello ".$sl_user_login."," . "<br>");
						$message  .= wp_kses_post("<br>");
						$message  .= wp_kses_post("Your username is : ".$sl_user_login . "<br>");
						$message  .= wp_kses_post("Your email address is : ".$sl_user_email."" . "<br>");
						$message  .= wp_kses_post("Your new password is : "."<br>".$sl_new_password."" . "<br>");
						$message  .= wp_kses_post("<br>");
						$message  .= $cl_url;
						$message  .= wp_kses_post("<br>");

					}	

					if (!empty(get_option('sl_user_signature'))){
						$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_signature'))));
						$message .= wp_kses_post(html_entity_decode($html_format_message_display));
					}else{
						$message .= wp_kses_post("-" . "<br>");
						$message .= wp_kses_post("Thanks," . "<br>");
						$message .= wp_kses_post(get_bloginfo('name') . " Team" . "<br>");
					}

					$headers[] = wp_kses_post('Content-Type: text/html; charset=UTF-8');
					$headers[] = wp_kses_post('From: '. $email);				
					$sl_send_email = wp_mail($to, $subject, $message, $headers);
					if(empty($sl_send_email)){
						echo wp_kses_post("<div class='sl_error sl_forgot-message'>".esc_html__("Error" , "suncabo-loyalty")."</div>");
					}else{
						echo wp_kses_post("<div class='sl_success-msg sl_forgot-message'>".(get_option('sl_forget_pass_msg') ? esc_html(get_option('sl_forget_pass_msg'))."</div>" : "<div class='sl_success-msg sl_forgot-message'>". esc_html__("We have successfully sent new password on your email address" , "suncabo-loyalty")."</div>"));
						wp_set_password($sl_new_password, $user_id);
					}
				}else{
					echo wp_kses_post("<div class='sl_error sl_forgot-message'>".esc_html__("Invalid email or user name" , "suncabo-loyalty")."</div>");
				}
			}
		}
		wp_die();
	}

	public function sl_user_account(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-user-account.php';
		return sl_user_account();
	}

	static function sl_change_password(){
		if ( is_user_logged_in() ) {

			if(isset($_POST['sl_nonce'])){
				$sl_nonce = sanitize_text_field($_POST['sl_nonce']);
			}



			global $current_user;
			global $wp_hasher;
			$user_ID = get_current_user_id();

			$sl_resultdata['message'] = '';
			$sl_resultdata['result'] = 'error';

			if ( isset( $sl_nonce ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $sl_nonce ) ) , 'sl_password_change' ) ){
				parse_str($_POST['data'], $post);

				if(($post['sl_old_password'] && $post['sl_new_password'] && $post['sl_confirm_password'])){
					$old_password 		= sanitize_text_field($post['sl_old_password']);
					$new_password 		= sanitize_text_field($post['sl_new_password']);
					$confirm_password 	= sanitize_text_field($post['sl_confirm_password']);

					if ( empty($wp_hasher) ) {
			        	require_once( ABSPATH . 'wp-includes/class-phpass.php');
			        	$wp_hasher = new PasswordHash(8, TRUE);
			        	// By default, use the portable hash from phpass
					}

					$user = wp_get_current_user();
					$user_id = get_current_user_id();
					$user_old_pass = $user->data->user_pass;

					if($wp_hasher->CheckPassword($old_password, $user_old_pass)){
						if($old_password === $new_password && $new_password === $confirm_password){
							$sl_resultdata['message'] = 'New password should be different from old password.';
						}elseif($old_password !== $new_password && $new_password === $confirm_password){

							$uppercase 		= preg_match('@[A-Z]@', $password);
							$lowercase 		= preg_match('@[a-z]@', $password);
							$number    		= preg_match('@[0-9]@', $password);
							$specialChars 	= preg_match('@[^\w]@', $password);

							if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
							    $sl_resultdata['message'] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
							}else{
							   wp_set_password( $new_password , $user_id );
								$sl_resultdata['result'] = 'success';
								$sl_resultdata['message'] =  "Your password changed successfully";
							}

						}else{
							$sl_resultdata['message'] = 'Confirm password not matching with new password';
						}
					}else{
						$sl_resultdata['message'] = 'Please enter your old password correctly';
					}

				}else{
					$sl_resultdata['message'] = 'Please enter valid password.';
				}
				echo wp_json_encode($sl_resultdata);
				wp_die();

			}else{
				wp_die(esc_html('Nonce is invalid'));
			}
		}
		wp_die();
	}

	static function sl_email_preferences(){
		
		$sl_resultdata = array('message' => '', 'result' => '');

		if ( is_user_logged_in() ) {

			if(isset($_POST['sl_nonce'])){
				$sl_nonce = sanitize_text_field($_POST['sl_nonce']);
			}

			if ( isset( $sl_nonce ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $sl_nonce ) ) , 'sl_user_email_preferences' ) ){

				parse_str($_POST['data'], $post);

				global $current_user;
				global $wp_hasher;
				$user_ID = get_current_user_id();
				$email_preference 	= sanitize_text_field($post['email_preference']);
				update_user_meta($current_user->ID, 'email_preference', $email_preference);
				$sl_resultdata['result'] = 'success';
				$sl_resultdata['message'] =  "Preferences settings successfully updated.";
				echo wp_json_encode($sl_resultdata);
				wp_die();

			}else{
				wp_die(esc_html('Not allowed'));
			}
		}
		wp_die();

	}
	

	public function sl_user_dashboard(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-user-dashboard.php';
		return sl_user_dashboard_form();
	}

	public function member_dashboard(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/suncabo-loyalty-member-dashboard.php';
		//$html = '';
		//$html .= tier_status_section();
		//return $html;
	}
}