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
			$sl_selected_user 	= sanitize_text_field($_POST['sl_selected_user']);
   			$sl_portfolio_id 	= sanitize_text_field($_POST['sl_portfolio_id']);
   			$sl_portfolio_name 	= sanitize_text_field($_POST['sl_portfolio_name']);
   			$sl_loyalty_points 	= sanitize_text_field($_POST['sl_loyalty_points']);	
   			$sl_loyalty_comment 	= sanitize_text_field($_POST['sl_loyalty_comment']);	
   			$sl_loyalty_status 	= sanitize_text_field($_POST['sl_status']);	

   			$loyalty_program = [
   				'id'	=> $sl_id,
   				'user_id' => $sl_selected_user,
   				'folio_id' => $sl_portfolio_id,
   				'folio_name' => $sl_portfolio_name,
   				'points' => $sl_loyalty_points,
   				'comment'	=> $sl_loyalty_comment,
   				'status' => $sl_loyalty_status
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
					echo'<script> window.location="?page=sl-manage-points&view=list"; </script> ';
				}
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
					echo'<script> window.location="?page=sl-manage-points&view=list"; </script> ';
				}
				
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
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/suncabo-loyalty-manage-points.php';

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
		$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history " . $conditions . " ORDER BY date_earned DESC" );
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

		//check if need to update the tier
		$this->user_id = $user_id;
		//$this->tierUpdate($total);
    }


	public function sl_admin_menu_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/suncabo-loyalty-admin-menu.php';
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
		
		register_setting( 'suncabo-loyalty', 'sl_user_registration_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_registration_email_body_admin' );
		register_setting( 'suncabo-loyalty', 'sl_user_forget_password_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_password_change_email_body' );
		register_setting( 'suncabo-loyalty', 'sl_user_approve_points_email_body_admin' );
	}

}
