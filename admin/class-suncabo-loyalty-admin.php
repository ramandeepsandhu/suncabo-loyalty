<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://author
 * @since      1.0.0
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Suncabo_Loyalty
 * @subpackage Suncabo_Loyalty/admin
 * @author     Ramandeep Sandhu <sandhuramansingh@gmail.com>
 */
class Suncabo_Loyalty_Admin extends Suncabo_Loyalty_Reward_Points{

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

	public $user_id;

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

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/suncabo-loyalty-admin.css', array(), $this->version, 'all' );

		

		

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name.'-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/suncabo-loyalty-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script($this->plugin_name, 'suncabo_loyalty_admin__ajaxObj', array('ajax_url' => esc_url(admin_url('admin-ajax.php')), 'curr_user' => get_current_user_id()));

		wp_enqueue_script( $this->plugin_name.'-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );

		

	}

	public function sl_admin_menu(){
		//$plugin_icon_url = esc_url(plugin_dir_url( __FILE__ ). '/images/suncabo-logo-white.png');
		$plugin_icon_url = 'dashicons-awards';
		add_menu_page('Sun Cabo Loyality', 'Sun Cabo Loyality', 'manage_options', $this->plugin_name , array($this, 'sl_admin_menu_page'),''.($plugin_icon_url).'');
		
		$my_hook = add_submenu_page( $this->plugin_name, 'Manage Points', 'Manage Points',
	'manage_options', 'sl-manage-points', array($this, 'sl_manage_points'));
		add_submenu_page( $this->plugin_name, 'Settings', 'Settings',
	'manage_options', $this->plugin_name);
		add_action( 'load-'.$my_hook, array($this, 'sl_load_custom_scripts' ));
	}

	public function sl_load_custom_scripts(){
		  add_action( 'admin_enqueue_scripts', array($this, 'enqueue_bootstrap' ));
	}

	public function enqueue_bootstrap(){
		//wp_enqueue_style( $this->plugin_name .'-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_deregister_style('forms');
		wp_enqueue_style('forms', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css', array(), $this->version, 'all');
		
		//wp_enqueue_script( $this->plugin_name.'-slim', 'https://code.jquery.com/jquery-3.2.1.slim.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', array( 'jquery' ), $this->version, false );

		
		wp_enqueue_script( $this->plugin_name.'-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_style( $this->plugin_name.'-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), $this->version, 'all' );


   		//echo '<style>.wp-core-ui .notice{ display: none !important; }</style>';

	}

	public function sl_manage_points(){
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';

		if (isset($_POST['sl_add_points']) && wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['sl_add_points'])),'sl_add-points' ) ){ 

			$sl_id 				= sanitize_text_field($_POST['sl_id']);
			$campaign_type 		= sanitize_text_field($_POST['sl_campaign_type']);
			$sl_selected_user 	= sanitize_text_field($_POST['sl_selected_user']);
   			$sl_portfolio_id 	= sanitize_text_field($_POST['sl_portfolio_id']);
   			$sl_portfolio_name 	= sanitize_text_field($_POST['sl_portfolio_name']);
   			$sl_loyalty_points 	= sanitize_text_field($_POST['sl_loyalty_points']);	
   			$sl_loyalty_comment = sanitize_text_field($_POST['sl_loyalty_comment']);	
   			$sl_loyalty_status 	= sanitize_text_field($_POST['sl_status']);	

   			$loyalty_program = [
   				'id'			=> $sl_id,
   				'campaign_type' => $campaign_type,
   				'user_id' 		=> $sl_selected_user,
   				'folio_id' 		=> $sl_portfolio_id,
   				'folio_name' 	=> $sl_portfolio_name,
   				'points' 		=> $sl_loyalty_points,
   				'comment'		=> $sl_loyalty_comment,
   				'status' 		=> $sl_loyalty_status
   			];


   			if($this->add_loyalty_points($loyalty_program)){
   				if($loyalty_program['id']){
   					wp_admin_notice(
					  __( 'Points updated successfully.', 'suncabo-loyalty' ),
					  array(
					    'type'               => 'success',
					    'dismissible'        => true,
					    'additional_classes' => array( 'inline', 'notice-alt' ),
					    'attributes'         => array( 'data-slug' => 'plugin-slug' )
					  )
					);
   				}else{
	   				wp_admin_notice(
					  __( 'Points added successfully.', 'suncabo-loyalty' ),
					  array(
					    'type'               => 'success',
					    'dismissible'        => true,
					    'additional_classes' => array( 'inline', 'notice-alt' ),
					    'attributes'         => array( 'data-slug' => 'plugin-slug' )
					  )
					);
   				}

   			}
   			echo'<script> window.location="?page=sl-manage-points&view=list"; </script> ';
		}

		$view = isset($_GET['view'])?$_GET['view']:'add';

		switch($view){
			case "add" :

			break;
			case "approve":
				if($this->updateRecord($_GET['id'], 1)){
					wp_admin_notice(
					  __( 'Points approved successfully.', 'suncabo-loyalty' ),
					  array(
					    'type'               => 'success',
					    'dismissible'        => true,
					    'additional_classes' => array( 'inline', 'notice-alt' ),
					    'attributes'         => array( 'data-slug' => 'plugin-slug' )
					  )
					);
				}
				
				//wp_redirect( admin_url( '?page=sl-manage-points&view=list' ) );
        		//exit;
				echo'<script> window.location="?page=sl-manage-points&view=list"; </script> ';
			break;
			case "pending":
				if($this->updateRecord($_GET['id'], 0)){
					wp_admin_notice(
					  __( 'Points marked as pending successfully.', 'suncabo-loyalty' ),
					  array(
					    'type'               => 'success',
					    'dismissible'        => true,
					    'additional_classes' => array( 'inline', 'notice-alt' ),
					    'attributes'         => array( 'data-slug' => 'plugin-slug' )
					  )
					);
				}
				echo'<script> window.location="?page=sl-manage-points&view=list"; </script> ';
				
			break;
			case "edit" :
				$id = $_GET['id'];
				if(isset($_GET['id']) &&!empty($_GET['id'])){
				    $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE id = %d", $_GET['id'] );
					$loyalty_program = $wpdb->get_row($query);				   
				}
			break;
			case "list" :
			case "delete":
				if($view == 'delete'){
					if(isset($_GET['id']) &&!empty($_GET['id'])){
					    $id = $_GET['id'];
					    $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE id = %d", $id );
						$loyalty_program = $wpdb->get_row($query);	
						
						if($loyalty_program){
						    $wpdb->delete( $wpdb->prefix . 'rewards_history', array( 'id' => $id ) );  
						    wp_admin_notice(
							  __( 'Record deleted successfully.', 'suncabo-loyalty' ),
							  array(
							    'type'               => 'success',
							    'dismissible'        => true,
							    'additional_classes' => array( 'inline', 'notice-alt' ),
							    'attributes'         => array( 'data-slug' => 'plugin-slug' )
							  )
							);
							$this->update_user_total_point($loyalty_program->user_id);
						}
					}
				}
				$loyalty_points = $this->list_loyalty_points_for_users();
			break;
			case "dob":
				$subscribers = $this->comingDOBUserList();
			break;
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/suncabo-loyalty-manage-points.php';

	}

	public function comingDOBUserList(){
		global $wpdb;
		$start = date('m-d');
		$end = date('m-d', strtotime('+31 days'));
	    $sql = "SELECT {$wpdb->prefix}users.ID, {$wpdb->prefix}users.user_email,{$wpdb->prefix}usermeta.meta_value as dob, date_format(ifr_usermeta.meta_value, '%m-%d') as day_order  FROM {$wpdb->prefix}users INNER JOIN {$wpdb->prefix}usermeta ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id ) WHERE 1=1 AND ( ( ( {$wpdb->prefix}usermeta.meta_key = 'dob' AND date_format( {$wpdb->prefix}usermeta.meta_value , '%m-%d') BETWEEN '{$start}' AND '{$end}' ) ) ) ORDER BY day_order ASC ";

		$users = $wpdb->get_results($sql);
		$users_records =[];
		foreach($users as $user){
			$firstName = get_user_meta($user->ID, 'first_name', true);
    		$lastName = get_user_meta($user->ID, 'last_name', true);
			$users_records[$user->ID]['name'] = $firstName . ' ' . $lastName ;
			$users_records[$user->ID]['email'] = $user->user_email;
			$users_records[$user->ID]['dob'] = $user->dob;
		}

		
		return $users_records;
	}

	public function sl_bulk_action(){
		global $wpdb;
		if (isset($_POST['sl_nonce']) || wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['sl_nonce'])),'sl_bulk_action_loyalty_points' ) ){ 
			
			$ids = $_POST['ids'];
			$selector = $_POST['selector'];
			if($ids){
				switch($selector){
					case "pending":
						foreach($ids as $id){
							if($this->updateRecord($id, 0)){

							}
						}
					break;
					case "approve":
						foreach($ids as $id){
							if($this->updateRecord($id, 1)){

							}
						}
					break;
					case "delete":
						foreach($ids as $id){
							$wpdb->delete( $wpdb->prefix . 'rewards_history', array( 'id' => $id ) ); 
						}
					break;
				}
			}

			$response['message'] = 'Records updated successfully.';
			$response['code'] = intval(200);
			
			echo wp_json_encode($response);
			wp_die();
		}
	}

	public function updateRecord($id , $status){
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';

		$query = $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id );
		$loyalty_program = $wpdb->get_row($query);	
		if($loyalty_program){
			$wpdb->update(
			    $table,
			    array('status' => $status),
			    array('id' => $loyalty_program->id)
			);
			$this->update_user_total_point($loyalty_program->user_id);
			return true;
		}

	}

	public function displayStatus($status){
		switch($status){
			case "0":
				return '<span class="badge badge-pill badge-warning">Pending</span>';
			break;

			case "1":
				return '<span class="badge badge-pill badge-success">Approved</span>';;
			break;
		}
	}

	public function list_loyalty_points_for_users(){
		global $wpdb;
		//WHERE status = 1 AND user_id = {$user_id}
		$conditions = '';
		$condition = [];
		if(isset($_GET['user_id']) && $_GET['user_id'] != ''){
			$user_id = $_GET['user_id'];
			$condition['AND'][] = " user_id = " . $user_id ;
		}
		if(isset($_GET['status']) && $_GET['status'] != ''){
			$status = $_GET['status'];
			$condition['AND'][] = " status = " . $status ;
		}
		if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
			$keyword = $_GET['keyword'];
			$condition['OR'][] = " folio_id = '" . $keyword ."'" ;
			$condition['OR'][] = " folio_name like '%" . $keyword ."%'" ;
		}

		if($condition){
			if(isset($condition['AND'])){
				$conditions = 'WHERE 1=1 AND (' . implode(' AND ', $condition['AND']). ')';
			}
			if(isset($condition['OR'])){
				if($conditions)
					$conditions .= '  OR (' . implode(' OR ', $condition['OR']) . ' )';
				else
					$conditions = ' WHERE 1=1 AND (' . implode(' OR ', $condition['OR']) . ' )';
			}
		}
		$query =  "SELECT * FROM {$wpdb->prefix}rewards_history " . $conditions . " ORDER BY date_earned DESC" ;
		$results = $wpdb->get_results($query);
		if($results){
			return $results;
		}
	}

	public function add_loyalty_points($loyalty_program){
		
		global $wpdb;
		$table = $wpdb->prefix . 'rewards_history';
		if(isset($loyalty_program['user_id'])){
			$user_id = $loyalty_program['user_id'];
		}else{
			$user_id = get_current_user_id();
		}

		if($loyalty_program['id']){
			$wpdb->update(
			    $table,
			    array(
			        'user_id' => $user_id,
				    'folio_id' => $loyalty_program['folio_id'],
				    'folio_name' => $loyalty_program['folio_name'],
				    'points' => $loyalty_program['points'],
				    'total' => $loyalty_program['points'],
				    'comment' => $loyalty_program['comment'],
				    'status' => $loyalty_program['status'],
				    //'date_earned' => date('Y-m-d h:i:s'),
				    'campaign_type' => isset($loyalty_program['campaign_type'])?$loyalty_program['campaign_type']:'',
				    'point_type' => 'reward',
				    ),
			    array(
			        'id' => $loyalty_program['id'],
			    )
			);
		}else{
			if(!$wpdb->insert($table, array(
			    'user_id' => $user_id,
			    'folio_id' => $loyalty_program['folio_id'],
			    'folio_name' => $loyalty_program['folio_name'],
			    'points' => $loyalty_program['points'],
			    'total' => $loyalty_program['points'],
			    'comment' => $loyalty_program['comment'],
				'status' => $loyalty_program['status'],
			    'date_earned' => date('Y-m-d h:i:s'),
			    'campaign_type' => isset($loyalty_program['campaign_type'])?$loyalty_program['campaign_type']:'',
			    'point_type' => 'reward',
			    'created' => date('Y-m-d h:i:s'),
			))){
				//echo $wpdb->last_error ;
				return false;
			}
		}

		$this->update_user_total_point($user_id);

		return true;

	} 

	public function update_user_total_point($user_id){
    	global $wpdb;		

    	$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = %d ORDER BY date_earned ASC", $user_id );
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
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rewards_history SET total='$total' WHERE id=%d", $row->id));
		}

		//check if need to update the tier
		$this->user_id = $user_id;
		//$this->tierUpdate($total);
    }


	public function sl_admin_menu_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/suncabo-loyalty-admin-menu.php';
	}

	public function sl_birthday_email(){
		if (isset($_POST['sl_nonce']) && wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['sl_nonce'])),'sl_send_birthday_email' ) ){ 

			parse_str($_POST['data'], $post);
			$message = wp_kses_post($post['content']);

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-suncabo-loyalty-points.php';
			$plugin_points = new Suncabo_Loyalty_Points();
			$loyalty_program = $plugin_points->get_loyalty_program_by_campaign_type('birthday_points');
			
			if($loyalty_program){
				$message = str_replace('{loyalty_points}', $loyalty_points['points'],  $message);
			}
			
			$sl_wish_msg = 'Happy Birthday';
			$headers[] = wp_kses_post('Content-Type: text/html; charset=UTF-8');
			$headers[] = wp_kses_post("From: " . (get_option('sl_user_from_email') ? get_option('sl_user_from_email') : get_option('admin_email')) . " \r\n");
			$subject = (get_option('sl_birthdaywish_subject') !== '' ? esc_html(get_option('sl_birthdaywish_subject')) : esc_html($sl_wish_msg));

			$response['code'] = intval(200);
			if(wp_mail($post['to'], $subject, $message, $headers)){			
				$response['message'] = 'Email sent successfully.';
			}else{
				$response['message'] = 'Unexpected error occure. Please try again.';
			}
			echo wp_json_encode($response);
			wp_die();
		}
	}

	public function sl_admin_settings(){
 
		register_setting( 'suncabo-loyalty', 'sl_registration_form' );
		register_setting( 'suncabo-loyalty', 'sl_login_form' );
		register_setting( 'suncabo-loyalty', 'sl_my_account' );
		register_setting( 'suncabo-loyalty', 'sl_forgot_password' );
		register_setting( 'suncabo-loyalty', 'sl_change_password' );

		register_setting( 'suncabo-loyalty', 'sl_role' );

		register_setting( 'suncabo-loyalty', 'sl_primary_color' );
		register_setting( 'suncabo-loyalty', 'sl_secondary_color' );
		register_setting( 'suncabo-loyalty', 'sl_pre_secondary_color' );
		register_setting( 'suncabo-loyalty', 'sl_pre_secondary_hover_color' );
		register_setting( 'suncabo-loyalty', 'sl_login_msg' );
		register_setting( 'suncabo-loyalty', 'sl_registration_msg' );
		register_setting( 'suncabo-loyalty', 'sl_forget_pass_msg' );
		register_setting( 'suncabo-loyalty', 'sl_change_pass_msg' );

		register_setting( 'suncabo-loyalty', 'sl_user_from_email' );
		register_setting( 'suncabo-loyalty', 'sl_user_signature');
		register_setting( 'suncabo-loyalty', 'sl_user_subject' );
		register_setting( 'suncabo-loyalty', 'sl_admin_subject' );
		register_setting( 'suncabo-loyalty', 'sl_userforgot_subject' );
		register_setting( 'suncabo-loyalty', 'sl_userchange_subject' );
		register_setting( 'suncabo-loyalty', 'sl_approvepoints_subject' );
		register_setting( 'suncabo-loyalty', 'sl_birthdaywish_subject' );
		
		register_setting( 'suncabo-loyalty', 'sl_user_registration_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_registration_email_body_admin' );
		register_setting( 'suncabo-loyalty', 'sl_user_forget_password_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_password_change_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_approve_points_email_body_admin' );
		register_setting( 'suncabo-loyalty', 'sl_birthdaywish_email_body' );
	}

}