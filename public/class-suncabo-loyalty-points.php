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
class Suncabo_Loyalty_Points extends Suncabo_Loyalty_Reward_Points{

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
	public function __construct() {

		//$this->plugin_name = $plugin_name;
		//$this->version = $version;

	}

	public function get_user_reward_history(){
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';
		$user_id = get_current_user_id();
		$query = $wpdb->prepare("SELECT * FROM $table WHERE user_id=%d", $user_id );
		$records = $wpdb->get_results( $query , ARRAY_A);
		$user_reward_data = array();

		$this->sl_handle_auto_renewal_points();

		if($records){
			foreach($records as $record){
				$user_reward_data[$record['folio_id']] = $record['folio_name'];
			}
		}
		return $user_reward_data;

	}

	public function sl_social_share(){
		global $Suncabo_Loyalty_Input;
		
		$response = array(
            'success' => false,
            'data' => array(
                'message' => __('Failed', ''),
            )
        );

        $wlr_nonce = (string)$Suncabo_Loyalty_Input->post_get('sl_nonce', '');
		$action_type = $Suncabo_Loyalty_Input->post_get('action');
		$id = $Suncabo_Loyalty_Input->post_get('id');

		//verify_nonce($wlr_nonce, 'wlr_common_user_nonce')
        if (!$this->is_valid_action($action_type)) {
            $response['data']['message'] = __('Security check failed', '');
            wp_send_json($response);
        }

        $loyalty_program = $this->get_loyalty_program($id);

        if($loyalty_program){
        	if($this->check_if_not_followed($id)){
        		if($this->add_loyalty_points($loyalty_program)){
        			$this->calculate_total_point();
        			$this->process_after_success($loyalty_program);
        			$response['success'] = true;
        			$response['data']['message'] = __('Points awarded successfully!', '');
            		wp_send_json($response);
        		}else{
        			$response['data']['message'] = __('Error occured. Please try again.', '');
            		wp_send_json($response);
        		}
        	}else{
        		$response['data']['message'] = __('Points already added.', '');
           	    wp_send_json($response);
        	}

        }else{
        	$response['data']['message'] = __('Invalid loyalty program', '');
            wp_send_json($response);
        }

	}

	public function process_after_success($loyalty_program){
		$user_id = get_current_user_id();
		$user_info = get_userdata($user_id);
		if(!empty(get_option('sl_user_approve_points_email_body_admin'))){
      		$username = $user_info->user_login;
      		$useremail = $user_info->user_email;
      		$first_name = $user_info->first_name;
      		$last_name = $user_info->last_name;

			$html_format_message_display = htmlentities(wpautop(stripslashes(get_option('sl_user_approve_points_email_body_admin'))));
			$message = wp_kses_post(html_entity_decode($html_format_message_display));
			$message =  wp_kses_post(str_replace('{points}', $loyalty_program['points'],  $message));
			$message =  wp_kses_post(str_replace('{loyalty_program}', $loyalty_program['title'],  $message));
			$message =  wp_kses_post(str_replace('{user_email}', $useremail,  $message));

		} else {		
			
      		$username = $user_info->user_login;
      		$first_name = $user_info->first_name;
      		$last_name = $user_info->last_name;
      		$name = $first_name . ' ' . $last_name;
			$message = wp_kses_post("Please verify " . $loyalty_program['points'] . "points claimed by : " . $name );
		}

		$sl_approve_msg = 'Approve points awaiting!';
		$subject = (get_option('sl_approvepoints_subject') !== '' ? esc_html(get_option('sl_approvepoints_subject')) : esc_html($sl_approve_msg));
		$headers[] = wp_kses_post('Content-Type: text/html; charset=UTF-8');
		$headers[] = wp_kses_post("From: " . (get_option('sl_user_from_email') ? get_option('sl_user_from_email') : get_option('admin_email')) . " \r\n");
		wp_mail(get_option('admin_email'), $subject, $message, $headers);

	}

	public function sl_social_review(){
		global $Suncabo_Loyalty_Input;
		
		$response = array(
            'success' => false,
            'data' => array(
                'message' => __('Failed', ''),
            )
        );

        $wlr_nonce = (string)$Suncabo_Loyalty_Input->post_get('sl_nonce', '');
		$action_type = $Suncabo_Loyalty_Input->post_get('action');
		$id = $Suncabo_Loyalty_Input->post_get('id');

		//verify_nonce($wlr_nonce, 'wlr_common_user_nonce')
        if (!$this->is_valid_action($action_type)) {
            $response['data']['message'] = __('Security check failed', '');
            wp_send_json($response);
        }

        $loyalty_program = $this->get_loyalty_program($id);

        if($loyalty_program){
        	if($this->check_if_not_followed($id)){
        		if($this->add_loyalty_points($loyalty_program)){
        			$this->calculate_total_point();
        			$this->process_after_success($loyalty_program);
        			$response['success'] = true;
        			$response['data']['message'] = __('Points awarded successfully!', '');
            		wp_send_json($response);
        		}else{
        			$response['data']['message'] = __('Error occured. Please try again.', '');
            		wp_send_json($response);
        		}
        	}else{
        		$response['data']['message'] = __('Points already added.', '');
           	    wp_send_json($response);
        	}

        }else{
        	$response['data']['message'] = __('Invalid loyalty program', '');
            wp_send_json($response);
        }

	    wp_die();

	}

	

	public function sl_social_followup(){
		global $Suncabo_Loyalty_Input;
		
		$response = array(
            'success' => false,
            'data' => array(
                'message' => __('Failed', ''),
            )
        );

        $wlr_nonce = (string)$Suncabo_Loyalty_Input->post_get('sl_nonce', '');
		$action_type = $Suncabo_Loyalty_Input->post_get('action');
		$id = $Suncabo_Loyalty_Input->post_get('id');

		//verify_nonce($wlr_nonce, 'wlr_common_user_nonce')
        if (!$this->is_valid_action($action_type)) {
            $response['data']['message'] = __('Security check failed', '');
            wp_send_json($response);
        }

        $loyalty_program = $this->get_loyalty_program($id);

        if($loyalty_program){
        	if($this->check_if_not_followed($id)){
        		if($this->add_loyalty_points($loyalty_program)){
        			$this->calculate_total_point();
        			$this->process_after_success($loyalty_program);
        			$response['success'] = true;
        			$response['data']['message'] = __('Points awarded successfully!', '');
            		wp_send_json($response);
        		}else{
        			$response['data']['message'] = __('Error occured. Please try again.', '');
            		wp_send_json($response);
        		}
        	}else{
        		$response['data']['message'] = __('Points already added.', '');
           	    wp_send_json($response);
        	}

        }else{
        	$response['data']['message'] = __('Invalid loyalty program', '');
            wp_send_json($response);
        }

	    wp_die();

	}

	public function add_loyalty_points($loyalty_program){
		
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';
		if(isset($loyalty_program['user_id'])){
			$user_id = $loyalty_program['user_id'];
		}else{
			$user_id = get_current_user_id();
		}
		if(!$wpdb->insert($table, array(
		    'user_id' => $user_id,
		    'folio_id' => $loyalty_program['id'],
		    'folio_name' => $loyalty_program['title'],
		    'points' => $loyalty_program['points'],
		    'total' => $loyalty_program['points'],
		    'date_earned' => date('Y-m-d h:i:s'),
		    'point_type' => 'reward',
		    'status' => 0,
		    'created' => date('Y-m-d h:i:s'),
		))){
			//echo $wpdb->last_error ;
			return false;
		}

		return true;

	}

	public function check_if_not_followed($folio_id){
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';
		$user_id = get_current_user_id();
		$query = $wpdb->prepare("SELECT * FROM $table WHERE user_id=%d AND folio_id=%d;", $user_id, $folio_id );
		$row = $wpdb->get_row( $query );
		if(!$row){
			return true;			
		}
		return false;
	}

	public function get_loyalty_program_by_campaign_type($campaign_type){
		$args = array(  
	        'post_type' => 'point',
	        'post_status' => 'publish',
	        'posts_per_page' => 1, 
	        'meta_query' => array(
		        array(
		           'key' => 'campaign_type',
		           'value' => $campaign_type,
		           'compare' => 'LIKE'
		        )
		     )
	    );

	    $query = new WP_Query($args);
	    if ( $query->have_posts() ) {
		    while ( $query->have_posts() ) {
		        $query->the_post();
		        $post = $query->post;
		        if($post){
		        	$fields = get_fields($id);
					$fields['id'] =   $post->ID ;
					$fields['title'] =   $post->post_title ;
					$fields['content']  =  $post->post_content ;
					return $fields;
		        }
		    }
		}

		return false;
	}

	public function get_loyalty_program($id){
		$post   = get_post( $id );
		if($post){
			$fields = get_fields($id);
			$fields['id'] =   $post->ID ;
			$fields['title'] =   $post->post_title ;
			$fields['content']  =  $post->post_content ;
			return $fields;
		}
		return false;
	}

	

    public function is_valid_action($action_type)
    {

        $status = false;
        $action_types = $this->get_action_types();
        
        if (!empty($action_type) && isset($action_types[$action_type]) && !empty($action_types[$action_type])) {
            $status = true;
        }
        return $status;
    }

    public function get_action_types()
    {
        $valid_action_types = array(
            'sl_social_followup' => 'Social Follow',
            'sl_social_share' => 'Social Share',
            'sl_social_review'	=> 'Reviews'
        );
        
        return $valid_action_types;
    }

    public function is_valid_social_action($action_type)
    {
        $status = false;
        $action_types = $this->get_social_action_list();
        if (!empty($action_type) && isset($action_types[$action_type]) && !empty($action_types[$action_type])) {
            $status = true;
        }
        return $status;
    }

    public function get_social_action_list()
    {
        $social_action_list = array(
            'facebook_share', 'twitter_share', 'whatsapp_share', 'email_share'
        );
        return $social_action_list;
        //return apply_filters('wlr_social_action_list', $social_action_list);
    }

    public function sl_get_share_url($id){
    	$points_data = get_fields($id);
    	
    	if($points_data){
	    	switch($points_data['campaign_type']){
	    		case "followup":
	    			return isset($points_data['page_url'])?$points_data['page_url']:'';
	    		break;

	    		case "share":
	    			return $this->get_share_url($points_data);
	    		break;

	    		default:
	    			return;
	    		break;
	    	}
    	}

    	return ;
    }

    public function get_share_url($points_data){
    	$share_url = '';
        $social_share_list = array();
        $social_share_message = '';
        $new_share_content = isset($points_data['share_message'])?$points_data['share_message']:'';
        $key = $points_data['social_media_type'];
        $url = $points_data['page_url'];

        if ($key === 'twitter_share') {
            $social_share_list[$key] = array(
                'name' => __('Twitter', 'suncabo-loyalty'),
                'share_content' => $new_share_content
            );
            $share_url = 'https://twitter.com/intent/tweet?text=' . urlencode($new_share_content). '&url='.$url;
        }
        if ($key === 'facebook_share') {
            $social_share_list[$key] = array(
                'name' => __('Facebook', 'suncabo-loyalty'),
                'share_content' => $new_share_content
            );
            $share_url = "https://www.facebook.com/sharer/sharer.php?quote=" . urlencode($new_share_content) . "&u=" . urlencode($url) . "&display=page";
        }
        if ($key === 'whatsapp_share') {
            $social_share_list[$key] = array(
                'name' => __('WhatsApp', 'suncabo-loyalty'),
                'share_content' => $new_share_content
            );
            $share_url = 'https://api.whatsapp.com/send?text=' . urlencode($new_share_content);
        }

        if ($key === 'linkedin_share') {
            $social_share_list[$key] = array(
                'name' => __('LinkedIn', 'suncabo-loyalty'),
                'share_content' => $new_share_content
            );
            $share_url = 'http://www.linkedin.com/shareArticle?mini=true&title=Welcome&url='.$url.'&summary='.urlencode($new_share_content);
        }

        //<a href="http://plus.google.com/share?url=URL_HERE" target="_blank" class="share-popup">Share on Googleplus</a>

        return $share_url;
        //$social_share_list[$key]['url'] = $share_url;

        //return $social_share_list;
    }

    public function calculate_total_point(){
    	global $wpdb;		
    	$user_id = get_current_user_id();
    	$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = {$user_id} ORDER BY date_earned ASC" );
		$results = $wpdb->get_results($query);
		$total = 0;
		$previous_total = 0;
		foreach ($results as $index => $row) {
			if($row->point_type == 'reward'){
				if($index == 0){
					$total = $row->points; 
				}else{
					$total = $previous_total + $row->points; 
				}
			}
			if($row->point_type == 'claim'){
				$total = $previous_total - $row->points; 
			}
			$previous_total = $total;
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rewards_history SET total='$total' WHERE id=$row->id"));
		}
    }

    /*public function get_user_total_points(){
    	//$this->calculate_total_point();
    	global $wpdb;		
    	$user_id = get_current_user_id();
    	$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = {$user_id} AND point_type = 'reward' ORDER BY date_earned DESC LIMIT 1" );
		$row = $wpdb->get_row( $query );
		
		if($row){
			return $row->total;			
		}
		return '0';
    }

    //@TODO - remove from here as already moved to Parent class
    public function get_demo_subscription(){
    	global $wpdb;	
    	$post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title = '%s' AND post_type= '%s' ", 'gold' , 'subscription') );
	        if($post){
	        	$demo_subscription 				= get_fields($post->ID);
				$demo_subscription['id'] 		=  $post->ID ;
				$demo_subscription['title'] 	=  $post->post_title ;
				$demo_subscription['content']  	=  $post->post_content ;
	        }

	        return $demo_subscription;

    }*/

    //@TODO - remove from here as already moved to Parent class
    /*public function get_user_tier_status(){
    	global $wpdb;		
    	$user_id = get_current_user_id();
    	$user_subscription_type = get_the_author_meta('user_subscription_type', $user_id);
    	if(!$user_subscription_type){
    		$user_subscription_type = 'gold';
    	}
    	if($user_subscription_type){
	    	global $wpdb;
			$post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title = '%s' AND post_type= '%s' ", $wpdb->esc_like( $user_subscription_type ) , 'subscription') );
	        if($post){
	        	$current_subscription = get_fields($post->ID);
				$current_subscription['id'] 		=  $post->ID ;
				$current_subscription['title'] 		=  $post->post_title ;
				$current_subscription['content']  	=  $post->post_content ;
	        }else{
	        	$current_subscription = $this->get_demo_subscription(); 
	        }
	        //Check for next next package
	        
	        $args = array(  
		        'post_type' => 'subscription',
		        'post_status' => 'publish',
		        'posts_per_page' => 1, 
		        'orderby'   => 'meta_value_num',
            	'meta_key'  => 'order',
            	'order'     => 'ASC', 
		        'meta_query' => array(
			        array(
			           'key' => 'order',
			           'value' => $current_subscription['order'],
			           'compare' => '>'
			        )
			     )
		    );

		    $query = new WP_Query($args);
		    if ( $query->have_posts() ) {
			    while ( $query->have_posts() ) {
			        $query->the_post();
			        $next_post = $query->post;
			    }
			}
			
	        if(isset($next_post)){
	        	$next_subscription 				= get_fields($next_post->ID);
				$next_subscription['id'] 		=  $next_post->ID ;
				$next_subscription['title'] 	=  $next_post->post_title ;
				$next_subscription['content']  	=  $next_post->post_content ;

	        	$subscription['current_subscription'] = $current_subscription;
	        	$subscription['next_subscription'] = $next_subscription;

	        	
	        	$current_total_point = $this->get_user_total_points();
	        	
	        	$minimum_points = $next_subscription['minimum_points'];
	        	$point_required_to_unlock = $minimum_points - $current_total_point ;
	        	$subscription['required_points_to_unlock'] = $point_required_to_unlock;
	        	$subscription['user_total_points_earned'] = $current_total_point;
	        	$subscription['percentage_of_points'] = (($current_total_point/$minimum_points)*100);
	        	
	        	return $subscription;
	        }

	        return false;
			   
			  
		}
	}*/

	public function sl_loyalty_subscriptions(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode/member-subscription.php';
	}

	public function sl_loyalty_points(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shortcode/loyalty-points.php';
	}

	public function sl_handle_auto_renewal_points(){
		$this->sl_sign_up_anniversary();
		$this->sl_birthday_rewards();
	}

	public function sl_birthday_rewards(){

		global $wpdb;
		$user_id = get_current_user_id();
		$reward = $this->find_user_history_reward(
			array(
				'post_type' => 'point', 
				'meta_query' => array( array('value' => 'birthday_points', 'compare' => '=')),
			)
		);


		if($reward){
			$today = new DateTime(date("Y-m-d"));

			$sql  = "SELECT * FROM {$wpdb->prefix}rewards_history WHERE user_id = '".$user_id."' AND folio_id = '".$reward['id']."' AND DATE_FORMAT(date_earned, \"%Y\") = '".$today->format("Y")."' ";
			$history_reward = $wpdb->get_row($sql);
			if(!$history_reward){
				$dob =  get_user_meta($user_id, 'dob', true);
				if($dob){
					$birthday = new DateTime($dob);
					if ($birthday->format("m-d") == $today->format("m-d")) {
					 	if($this->add_loyalty_points($reward)){
							$this->calculate_total_point();
						}
					} 
				}
			}
		}

	}

	public function sl_sign_up_anniversary(){
		global $wpdb;
		$user_id = get_current_user_id();
		$reward = $this->find_user_history_reward(
			array(
				'post_type' => 'point', 
				'meta_query' => array( array('value' => 'anniversary_signup', 'compare' => '=')),
			)
		);

		if($reward){
			$history_reward = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}rewards_history WHERE user_id = '%d' AND folio_id = '%d' ", $user_id , $reward['id']) );
		        		
			if(!$history_reward){
				$registered_date = get_the_author_meta( 'user_registered', $user_id );
				$registered_date = strtotime('+1 year', strtotime($registered_date));
				//Check if user completed one year
				if( strtotime('now') >= $registered_date ){
					if($this->add_loyalty_points($reward)){
						$this->calculate_total_point();
					}
				}
			}
		}
	}

	public function find_user_history_reward($params = array()){

		$args = array(  
	        //'post_type' => 'point',
	        'post_status' => 'publish',
	        'posts_per_page' => 1, 
	        'meta_query' => array(
		        array(
		           'key' => 'campaign_type',
		           //'value' => 'signup_anniversary',
		           //'compare' => '='
		        )
		     )
	    );
	    if($params){
	    	$args = array_replace_recursive($args,  $params); 
	    }

	    $query = new WP_Query($args);

	    if ( $query->have_posts() ) {
		    while ( $query->have_posts() ) {
		        $query->the_post();
		        $post = $query->post;

		        if($post){
		        	$reward 			= 	get_fields($post->ID);
					$reward['id'] 		=   $post->ID ;
					$reward['title'] 	=   $post->post_title ;
					$reward['content']  =  	$post->post_content ;
	        		return $reward;
		        }
		    }
		}
		return false;

	}
	
}