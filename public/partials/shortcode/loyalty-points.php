<div class="earn-row">
	<?php
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
	    	$points 		= get_field( "points", get_the_ID() );
	    	$button_label 	= get_field( "button_label", get_the_ID());
	    	$campaign_type 	= get_field( "campaign_type", get_the_ID());
	    	$social_media_icon 	= get_field( "social_media_icon", get_the_ID());
	    	$class 			= get_field( "class", get_the_ID());
	    	$instructions = get_field( "instructions", get_the_ID());

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

            	<?php if($instructions){ ?>
            		<small><?php echo $instructions;?></small>
            	<?php }?>
			</div>
		</div>

	    <?php endwhile;
			wp_reset_postdata(); 
		?>
</div>