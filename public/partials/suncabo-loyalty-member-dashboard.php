<?php
function tier_status_section(){
$Suncabo_Loyalty_Points = new Suncabo_Loyalty_Points();
$tier_status = $Suncabo_Loyalty_Points->get_user_tier_status();
?>
<div class="row">
		<div class="col-4">
			<ul class="">
			  <li class="list-group-item">Tier Status</li>
			  	<?php if($tier_status){?>
			  		<li class="list-group-item">You are currently a <?php echo $tier_status['current_subscription']['title'];?> Tier member.</li>
			  		<li class="list-group-item"><b>Earn <?php echo $tier_status['required_points_to_unlock'];?> point to unlock <?php echo $tier_status['next_subscription']['title'];?></b></li>
				<?php }?>
			</ul>
		</div>
  		<div class="col-8">
  			<div class="start">You</div>
   			<div class="end"><?php echo $tier_status['next_subscription']['title'];?></div>
  			<div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="<?php echo $tier_status['user_total_points_earned'];?>" aria-valuemin="0" aria-valuemax="<?php echo $tier_status['required_points_to_unlock'];?>">
  			 
			  <div class="progress-bar" style="width: <?php echo $tier_status['percentage_of_points'];?>%"><?php echo $tier_status['user_total_points_earned'];?></div>
			</div>

			<div class="start"><?php echo $tier_status['user_total_points_earned'];?> earned</div>
   			<div class="end">1000-4999</div>
  		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<span class="badge text-bg-warning">Tier status doesn't expires once reached, your benifits lasts forever.</span>
		</div>
	</div>

	<div class="row">
		<?php
		$args = array(  
	        'post_type' => 'subscription',
	        'post_status' => 'publish',
	        'posts_per_page' => -1, 
	        'meta_key'  => 'order',
    		'orderby'  => 'meta_value',
	        'order' => 'ASC', 
	    );

	    $loop = new WP_Query( $args );
	    $subscription = [];
	    while ( $loop->have_posts() ) : $loop->the_post();
	    	$minimum_points = get_field( "minimum_points", get_the_ID() );
	    	$maximum_points = get_field( "maximum_points", get_the_ID());
	     ?>

		    <div class="col-4">
				<div class="card">
				  <div class="card-header">
				    <?php the_title(); ?>
				    <?php if($minimum_points && $maximum_points){?>
				    	<h4><?php echo $minimum_points?> - <?php echo $maximum_points?> Points</h4>
					<?php }elseif($maximum_points){ ?>
						<h4><?php echo $maximum_points?> + Points</h4>
					<?php }?>
				  </div>
				  <div class="card-body">
				    <p class="card-text"><?php the_content(); ?></p>
				    
				    <?php if($tier_status['current_subscription']['id'] != get_the_ID()){?>
				    	<a href="#" class="btn btn-primary">Upgrade</a>
					<?php }?>
				  
				  </div>
				</div>
			</div>

	       
	    <?php endwhile;
	    wp_reset_postdata(); 

		?>
	</div>

	<div class="row">
		<div class="col-12">
			<h3>Ways to Earn Points</h3>
		</div>
	</div>

	<div class="row">
		<?php
		
		$user_reward_data = $Suncabo_Loyalty_Points->get_user_reward_history();
		//echo '<pre>';
		//print_r($user_reward_data);
		$args = array(  
	        'post_type' => 'point',
	        'post_status' => 'publish',
	        'posts_per_page' => -1, 
	        'meta_key'  => 'order',
    		'orderby'  => 'meta_value',
	        'order' => 'ASC', 
	    );

	    $loop = new WP_Query( $args );
	    $subscription = [];
	    while ( $loop->have_posts() ) : $loop->the_post();
	    	$points 		= get_field( "points", get_the_ID() );
	    	$button_label 	= get_field( "button_label", get_the_ID());
	    	$campaign_type 	= get_field( "campaign_type", get_the_ID());
	    	$social_media_icon 	= get_field( "social_media_icon", get_the_ID());

	        $share_url = $Suncabo_Loyalty_Points->sl_get_share_url(get_the_ID());
	        //echo '<pre>';
	        //print_r($social_media_icon);
	     ?>

		    <div class="col-4">
				<div class="card">
				  
				  <div class="card-body">
				  	<?php if(isset($social_media_icon['url'])){?>
				  	<img src="<?php echo $social_media_icon['url'];?>">
				  	<?php }?>
				  	<h2><?php echo $points;?> Points<h2>
				    <p class="card-text"><?php the_content(); ?></p>

				    
				    <?php if($button_label){
				    		if(isset($user_reward_data[get_the_ID()])){ ?>
				    			<a href="javascript:void(0);" class="btn btn-secondary"><?php echo $button_label;?></a>
				    		<?php }else{
				    	?>
					    <a class="sl-icon-list btn btn-primary"
	                       onclick="jQuery('body').trigger( 'sl_apply_social_<?php echo $campaign_type;?>', ['<?php echo get_the_ID();?>', '<?php echo esc_js($share_url); ?>', '<?php echo $campaign_type;?>' ] )" target="_parent">
	                        <?php echo $button_label;?>
	                    </a>
                	<?php } }?>

				    
				  </div>
				</div>
			</div>

	       
	    <?php endwhile;
	    wp_reset_postdata(); 

		?>
	</div>

	<div class="row">
		<div class="col-12">
			<span>Rewards History</span>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
	<?php 
		global $wpdb;
		$user_id = get_current_user_id();
		$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = %d ORDER BY date_earned DESC" , $user_id);

		$results = $wpdb->get_results($query);

		if (is_array($results) && count($results)) { ?>

			<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">Folio#</th>
			      <th scope="col">Date</th>
			      <th scope="col">Villa</th>
			      <th scope="col">Earned</th>
			      <th scope="col">Redeemed</th>
			      <th scope="col">Current Points</th>
			    </tr>
			  </thead>
			  <tbody>
			<?php 

		    foreach ($results as $row) { ?>
		    	<tr>
			      <th scope="row"><?php echo $row->folio_id;?></th>
			      <th scope="row"><?php echo date('F d, Y', strtotime($row->date_earned));?></th>
			      <th scope="row"><?php echo $row->folio_name;?></th>
			      <th scope="row"><?php echo (($row->point_type == 'reward')? '+' .$row->points:'');?></th>
			      <th scope="row"><?php echo (($row->point_type == 'claim')? '-' .$row->points:'');?></th>
			      <th scope="row"><?php echo $row->total;?></th>
			    </tr>
		    <?php } ?>
		    </tbody>
			</table>
			<?php 
		}else{
			echp '<div>No records</div>';
		}
	 ?>
	 </div>
	</div>
	<style>
		.progress {margin-bottom:0;}
		.start {float:left;}
		.end {float:right; text-align:right;}
	</style>
<?php } ?>