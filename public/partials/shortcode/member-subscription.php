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
	<p>* Subject to availability and with prior notice.</p>
	<p>**Butler Service is for 8 hours per day during the stay, and for 4 hours on Check-In and Check-Out days. Butler hours cannot be carried to other days.</p>
	<p>Please note: All Gold Membership perks are readily available to you with the exception of Early Check-Ins, which are available to you after your first stay with Sun Cabo.</p>
</div>