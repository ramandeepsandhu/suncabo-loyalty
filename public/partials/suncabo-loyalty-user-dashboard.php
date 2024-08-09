<?php
function sl_user_dashboard_form(){
	$Suncabo_Loyalty_Points = new Suncabo_Loyalty_Points();
	$tier_status = $Suncabo_Loyalty_Points->get_user_tier_status();
	//ob_start();?>
	<div class="dashbord-banner">
		<div class="container">
			<div class="dashboard-row">
				<div class="loyalty-logo">
					<a href="<?php echo esc_url(get_bloginfo('url').'/dashboard/'); ?>">
					<img src="<?php echo get_template_directory_uri();?>/assets/images/loyalty-banner-logo.png" alt="Loyalty Rewards" title="Loyalty Rewards"></a>
				</div>
				<?php global $current_user; wp_get_current_user(); ?>
				<?php if ( is_user_logged_in() ) { ?>
					<?php if($tier_status){?>
						<div class="alicia-lead-title">
							<h2>Hi <?php echo ucwords($current_user->first_name);?>!</h2>
							<p class="point-awarded">You currently have <span><?php echo $tier_status['user_total_points_earned'];?></span> points</p>
						</div>
					<?php }?>
					<div class="loyalty-login-btns">
						<a href="<?php echo esc_url(get_bloginfo('url').'/user-account/'); ?>">account</a> 
						<a class="theme-btn" href="<?php echo esc_url(wp_logout_url(site_url('/sign-in/'))); ?>">
							<?php echo esc_html__("sign out", "suncabo-loyalty");?>
						</a>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
	<!-- Banner End -->

	<!-- Main Start -->
	<div class="earn-more-wrap">
		<div class="container">
			<div class="earn-more__title">
				<h2>Earn More as a Loyalty Member</h2>
				
			</div>
			<div class="earn-more">
				<div class="tier-status">
					<h3>tier status</h3>
					<p>You are currently a <?php echo $tier_status['current_subscription']['title'];?> Tier member</p>
					<p><strong>Earn <?php echo $tier_status['required_points_to_unlock'];?> points to unlock <?php echo $tier_status['next_subscription']['title'];?></strong></p>
				</div>
				<?php
				
					if($tier_status['user_total_points_earned'] > 0){
						$percentage_progress = (($tier_status['user_total_points_earned']/$tier_status['current_subscription']['maximum_points'])*100);
					}else{
						$percentage_progress = 0;
					}
				?>
				<div class="status-progress">
					<h3 class="left-title">you</h3>
					<h3 class="right-title"><?php echo $tier_status['next_subscription']['title'];?></h3>
					<div class="progress-bar">
						<div class="progress" style="max-width:<?php echo $percentage_progress;?>%;">
						</div>
					</div>
					<p class="left-info point-awarded"><?php echo $tier_status['user_total_points_earned'];?> Earned</p>
					<p class="right-info">
						
						<?php
							$point_range = ''; 
							if($tier_status['next_subscription']){
								
								if($tier_status['next_subscription']['minimum_points']){
									$point_range = $tier_status['next_subscription']['minimum_points'] . '-';
								}
								if($tier_status['next_subscription']['maximum_points']){
									$point_range .= $tier_status['next_subscription']['maximum_points'];
								}
							}
						?> 
						<?php echo $point_range;?>
					</p>
				</div>
			</div>
			<div class="benefit">
				Tier status doesn’t expire; once reached, your benefits last forever.
			</div>
		</div>
		<div class="container container--alt">
			<div class="member-row">

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
				    	$subscription_icon 	= get_field( "subscription_icon", get_the_ID());
				     ?>
				
						<div class="member-col">
							<div class="member__col-box">
								<?php if(isset($subscription_icon['url'])){?>
									<div class="earn-icon">
							  			<img src="<?php echo $subscription_icon['url'];?>">
							  		</div>
							  	<?php }?>
								<h3><?php the_title(); ?></h3>
								<?php if($minimum_points && $maximum_points){?>
							    	<p><?php echo $minimum_points?> - <?php echo $maximum_points?> Points</p>
								<?php }elseif($maximum_points){ ?>
									<p><?php echo $maximum_points?> + Points</p>
								<?php }?>
								<div class="bd-img">
									<img src="<?php echo get_template_directory_uri();?>/assets/images/border.png" alt="border">
								</div>
								<?php the_content(); ?>
							</div>
						</div>

					<?php endwhile;
					    wp_reset_postdata(); 
					?>
			</div>
			<div class="member-info">
				<p>*Subject to availability and with prior notice.</p>
				<p>**Butler Service is for 8 hours per day during the stay, and for 4 hours on Check-In and Check-Out days. Butler hours cannot be carried to other days.</p>
				<p>Please note: All Gold Membership perks are readily available to you with the exception of Early Check-Ins, which are available to you after your first stay with Sun Cabo.</p>
			</div>
		</div>
	</div>

	<div class="earn-wrap">
		<div class="container container--alt">
			<div class="earn-title">
				<h2>Ways to Earn Points</h2>
			</div>
			<div class="earn-row">
				<?php
					$user_reward_data = $Suncabo_Loyalty_Points->get_user_reward_history();
					//echo '<pre>';
					//print_r($user_reward_data);
					$args = array(  
				        'post_type' => 'point',
				        'post_status' => 'publish',
				        'posts_per_page' => -1, 
				        'meta_key'  => 'order',
			    		'orderby'  => 'meta_value_num',
				        'order' => 'ASC', 
				    );

				    $loop = new WP_Query( $args );
				    //echo '<pre>';
				    //print_r($loop);
				    $subscription = [];
				    while ( $loop->have_posts() ) : $loop->the_post();
				    	
				    	$point_type 		= get_field( "point_type", get_the_ID() );
				    	$points 			= get_field( "points", get_the_ID() );
				    	$button_label 		= get_field( "button_label", get_the_ID());
				    	$campaign_type 		= get_field( "campaign_type", get_the_ID());
				    	$social_media_icon 	= get_field( "social_media_icon", get_the_ID());
				    	$class 				= get_field( "class", get_the_ID());
				    	$instructions 		= get_field( "instructions", get_the_ID());

				        $share_url = $Suncabo_Loyalty_Points->sl_get_share_url(get_the_ID());


				        if($point_type == 'percentage'){
				        	$minimum_points = get_field( "minimum_points", get_the_ID() );
				    		$maximum_points = get_field( "maximum_points", get_the_ID());
				        	$points =  $minimum_points . '-' . $maximum_points . '% Back in'; 
				        }
				        $points = $points . ' Points';
				        if($point_type == 'text'){
				        	$points = get_field( "loyalty_label", get_the_ID()); 
				        }
				        
				     ?>

				     <div class="earn-col <?php echo $class;?>">
						<div class="earn-info">
							<div class="earn-icon">
								<?php if(isset($social_media_icon['url'])){?>
				  					<img src="<?php echo $social_media_icon['url'];?>">
				  				<?php }?>
							</div>
							<h3><?php echo $points;?></h3>
							<p><?php the_content(); ?></p>

							<?php 
							if($campaign_type != 'account_signup'){
								if($button_label){
							    	if(isset($user_reward_data[get_the_ID()])){ ?>
							    		<a href="javascript:void(0);" class="btn btn theme-btn is-disabled point-awarded"><?php echo $button_label;?></a>
							    	<?php }else{ ?>
								    <a class="sl-icon-list btn theme-btn"
				                       onclick="jQuery('body').trigger( 'sl_apply_social_<?php echo $campaign_type;?>', ['<?php echo get_the_ID();?>', '<?php echo esc_js($share_url); ?>', '<?php echo $campaign_type;?>' ] )" target="_parent">
				                        <?php echo $button_label;?>
				                    </a>
		                	<?php } } }?>

		                	<?php if($instructions){ ?>
		                		<small><?php echo $instructions;?></small>
		                	<?php }?>
						</div>
					</div>

				    <?php endwhile;
	    				wp_reset_postdata(); 
	    			?>

				

			</div>
		</div>
	</div>
	<a id="refer-friend"></a>
	<div class="refer-friend-wrap">
		<div class="container">
			<div class="refer-friend">
				<div class="refer-friend-info">
					<p>Refer A Friend</p>
					<h3>Give $200, Get $200</h3>
					<p>Gift your friends a $200 credit to our online pre-stocking store to use during their first stay, and earn a $200 online pre-stocking store credit for yourself with every successful referral.</p>
					<div class="email-form">
						<?php echo do_shortcode('[contact-form-7 id="f720a1c" title="Refer A Friend"]')?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<a id="rewards-history"></a>
	<div class="histroy-wrap">
		<div class="container">
			<h3>rewards history</h3>
			<?php 
			global $wpdb;
			$user_id = get_current_user_id();
			$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}rewards_history WHERE status = 1 AND user_id = {$user_id} ORDER BY date_earned DESC" );

			$results = $wpdb->get_results($query);

			if (is_array($results) && count($results)) { 
			?>

			<div class="histroy-table">
				<table>
					<tr class="mb-none">
						<th class="align-left">Folio #</th>
						<th>Date</th>
						<th>Villa</th>
						<th>Earned</th>
						<th>Redeemed</th>
						<th>Current Points</th>
					</tr>
					<?php 
					    foreach ($results as $row) { ?>
					    	<tr class="<?php echo ($row->point_type == 'claim')?'active':'';?>">
						      <td class="mb-block"><span>Folio #</span></td>
								<td class="align-left"><?php echo $row->folio_id;?></td>
								<td class="mb-block"><span>Date</span></td>
								<td><?php echo date('F d, Y', strtotime($row->date_earned));?></td>
								<td class="mb-block"><span>Villa</span></td>
								<td><?php echo $row->folio_name;?></td>
								<td class="mb-block"><span>Earned</span></td>
								<td><?php echo (($row->point_type == 'reward')? '+' .$row->points:'');?></td>
								<td class="mb-none"><?php echo (($row->point_type == 'claim')? '-' .$row->points:'');?></td>
								<td class="mb-block"><span>Current Points</span></td>
								<td><?php echo $row->total;?></td>

						    </tr>
					    <?php } ?>
				</table>
			</div>
		<?php }else{
			echo '<div>No records available.</div>';
		}?>
		</div>
	</div>
<?php return ob_get_clean();} ?>