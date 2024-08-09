<?php


class Suncabo_Loyalty_Reward_Points{
	
	public $user_id ;

	public function __construct() {

	}


	

	public function get_user_tier_status($user_id = null){
    	global $wpdb;		
    	
    	if($user_id == null)
    		$this->user_id = get_current_user_id();

    	$user_subscription_type = get_the_author_meta('user_subscription_type', $this->user_id);
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
	}

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

    }

    public function get_user_total_points(){
    	global $wpdb;		
    	$user_id = get_current_user_id();
    	$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = {$user_id} AND point_type = 'reward' ORDER BY date_earned DESC LIMIT 1" );
		$row = $wpdb->get_row( $query );
		
		if($row){
			return $row->total;			
		}
		return '0';
    }


}
?>