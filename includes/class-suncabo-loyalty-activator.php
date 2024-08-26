<?php

/**
 * Fired during plugin activation
 *
 * @link       https://author
 * @since      1.0.0
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/includes
 * @author     Ramandeep Sandhu <sandhuramansingh@gmail.com>
 */
class Suncabo_Loyalty_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// BY DEFAULT WHEN ACTIVE PLUGIN THEN FILL DEFAULT MESSAGE
		$sl_user_form_success_msg = array(
			'sl_registration_form' => '[sl_signup_form]',
			'sl_login_form' => '[sl_signin_form]',
			'sl_my_account' => '[sl_my_account]',
			'sl_forgot_password' => '[sl_forgot_password]',
			'sl_change_password' => '[sl_change_password]',
			'sl_user_dashboard'	=> '[sl_user_dashboard]',
			'sl_primary_color' =>  '#000000',
			'sl_secondary_color' =>  '#17b4eb',
			'sl_pre_secondary_color' =>  '#ffffff',
			'sl_pre_secondary_hover_color' =>  '#000000',
			'sl_login_msg' => 'User Sign In successfully',
			'sl_registration_msg' => 'User sign Up successfully, You will be get email. Please activate your account from it',
			'sl_forget_pass_msg' => 'We have successfully sent new password on your email address',
			'sl_change_pass_msg' => 'Password has been change successfully',
			'sl_role' => 'subscriber',
			'sl_user_subject' => 'Welcome ! you have registered successfully',
			'sl_admin_subject' => 'New user registered on our site',
			'sl_userforgot_subject' => 'Forget password',
			'sl_userchange_subject' => 'Your password has been change successfully',
			'sl_user_from_email' => get_option('admin_email'),
			'sl_user_signature' => '-'."<br>".'Thanks,'."<br>".get_bloginfo( 'name' )." Team",
			'sl_user_registration_email_body' => "Hello {user_name},"."<br><br>"."Welcome to our site : {site_name}"."<br><br>"."Please click the following link to activate your account"."<br>"."{Please activate your account}"."<br><br>"."<strong>"."Note : After click on above link, you need to Sign In, so your account will be activate"."</strong>"."<br><br>",
			'sl_user_registration_email_body_admin' => "Hello Admin,"."<br><br>"."New user is registered on our site"."<br><br>"."Here are details :"."<br>"."User name : {user_name}"."<br>"."Email : {user_email}"."<br><br>",
			'sl_user_forget_password_email_body' => "Hello {user_name},"."<br><br>"."Your user name is : {user_name}"."<br>"."Your email address is : {user_email}"."<br><br>"."Your new password is :"."<br>"."{user_password}"."<br><br>"."<a href='".esc_url(site_url()).'/sign-in/'."'>Click here to login</a>"."<br><br>",
			'sl_user_password_change_email_body' => "Hello {user_name},"."<br><br>"."Your user name is : {user_name}"."<br>"."Your email address is : {user_email}"."<br><br>"."Your new password is :"."<br>"."{user_password}"."<br><br>",
		);
		foreach ($sl_user_form_success_msg as $sl_key => $sl_success_value)
		{
			update_option($sl_key, wp_kses_post($sl_success_value));
		}
		// END, BY DEFAULT WHEN ACTIVE PLUGIN THEN FILL DEFAULT MESSAGE

		if ( ! current_user_can( 'activate_plugins' ) ) return;
		global $wpdb;
		$sl_pages = array(
			'login'=>array('title'=>'Sign In','content'=>'[sl_signin_form]','option_page'=>'sl_user_login_page'),
			'registration'=>array('title'=>'Sign Up','content'=>'[sl_signup_form]','option_page'=>'sl_user_registration_page'),
			'forget-password'=>array('title'=>'Forget Password','content'=>'[sl_forgot_password]','option_page'=>'sl_user_forgot_pass_page'),
			'user-my-account'=>array('title'=>'User Account','content'=>'[sl_my_account]','option_page'=>'sl_user_my_account_page'),
			'user-dashboard'=>array('title'=>'Dashboard','content'=>'[sl_user_dashboard]','option_page'=>'sl_user_dashboard_page')
		);

		foreach ($sl_pages as $sl_pages_key => $sl_page_value)
		{		
			$sl_query = $wpdb->prepare("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = %s",$sl_pages_key);

			if ( null === $wpdb->get_row( $sl_query ) ) {
				$sl_current_user = wp_get_current_user();
				// create post object
				$sl_cnt = 'publish';
				$sl_page = 'page';
				$sl_page = array(
					'post_title'  => sanitize_text_field($sl_page_value['title']),
					'post_status' => sanitize_text_field($sl_cnt),
					'post_content'=> sanitize_textarea_field($sl_page_value['content']),
					'post_author' => (int) $sl_current_user->ID,
					'post_type'   => sanitize_text_field($sl_page),
				);

				if (!get_page_by_path( $sl_page_value['title'], OBJECT, 'page'))
				{   // Check If Page Not Exits
					// insert the post into the database
					$sl_postID = wp_insert_post($sl_page);
					update_option($sl_page_value['option_page'], (int) $sl_postID);
				}
			}
		}
	}
}