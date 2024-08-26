<?php
	$args = array('fields' => array( 'ID', 'display_name', 'user_login', 'user_email'));
	$users = get_users( $args );
	$users_records = array();
	if($users){
		foreach($users as $user){
			$firstName = get_user_meta($user->ID, 'first_name', true);
    	$lastName = get_user_meta($user->ID, 'last_name', true);
			$users_records[$user->ID] = $firstName . ' ' . $lastName . ' - ' . $user->user_email;
		}
	}
?>
<div class="content-wrapper">
    <div class="container-fluid">
      <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?php _e( 'SunCabo Loyalty Program', 'barefoot-core' ); ?>/</span> Reward Points</h4>
      	<?php
      		$view = isset($_GET['view'])?$_GET['view']:'add';
					$add_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'add'], admin_url('admin.php'));
					$manage_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'list'], admin_url('admin.php'));
					$coming_dob = add_query_arg(['page' => 'sl-manage-points', 'view' => 'dob'], admin_url('admin.php'));
				?>

				
      <!-- Basic Layout & Basic with Icons -->
      <div class="row">
		      <div class="col-12">

		      	<ul class="nav nav-tabs">
						  <li class="nav-item">
						  	<a href="<?php echo $add_rewards;?>" class="nav-link <?php echo (in_array($view, array('add', 'edit')))?'active':'';?>" aria-current="true">Add Rewards</a>
						  </li>
						  <li class="nav-item">
						  	<a href="<?php echo $manage_rewards;?>" class="nav-link <?php echo (in_array($view, array('list', 'delete')))?'active':'';?>">Manage Rewards</a>
						  </li>

						  <li class="nav-item">
						  	<a href="<?php echo $coming_dob;?>" class="nav-link <?php echo (in_array($view, array('dob')))?'active':'';?>">Coming DOB</a>
						  </li>
						</ul>
			
					
      	</div>
      </div>
      <div class="row">
      	

        <!-- Basic Layout -->
        <div class="col-12">
          
          <?php switch($view){
          	case "add":
          	case "edit":
          	?>
          <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0"><?php echo (isset($loyalty_program)?"Update":"Add");?> Loyalty Rewards To User Account</h5>
              <small class="text-muted float-end">User</small>
            </div>
            <div class="card-body">
              <form method="post">
              	<input type="hidden" name="sl_id" value="<?php echo isset($loyalty_program)?$loyalty_program->id:"";?>"/>
              	<?php wp_nonce_field('sl_add-points', 'sl_add_points') ?>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="basic-default-name">User</label>
                  <div class="col-sm-10">
                    <?php
										if($users){
											echo '<select id="user-list" name="sl_selected_user" class="form-control select2-element" required>';
											echo '<option value="">Select a user</option>';
											foreach($users as $user){
												echo '<option '. ( ((isset($loyalty_program) && $loyalty_program->user_id==$user->ID) || (isset($_GET['id'])))?'selected="selected"':"").' value="'.$user->ID.'">'. $user->display_name . ' ('.$user->user_email.')' .'</option>';
											}
											echo '</select>';
										}
									?>
                  </div>
                </div>
                <div class="row mb-3">
                	<?php 
                		$campaign_types = [
		                		'account_signup' => 'Create Account',
												'newsletter_signup' => 'Signup Newsletter',
												'anniversary_signup' => 'Signup Anniversary',
												'followup' => 'Follow',
												'share' => 'Share',
												'review' => 'Leave a Review',
												'loyalty_points' => 'Loyalty Points',
												'birthday_points' => 'Birthday Points'
										];
                	?>
                  <label class="col-sm-2 col-form-label" for="basic-default-name">Campaign Type</label>
                  <div class="col-sm-10">
                    <?php
										if($users){
											echo '<select id="campaign_type" name="sl_campaign_type" class="form-control select2-element">';
											echo '<option value="">Select a Campaign Type</option>';
											foreach($campaign_types as $key=>$campaign_type){
												echo '<option '. ( ((isset($loyalty_program) && $loyalty_program->campaign_type == $key) || (isset($_GET['campaign_types'])))?'selected="selected"':"").' value="'.$key.'">'. $campaign_type .'</option>';
											}
											echo '</select>';
										}
									?>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="portfolio-id">Portfolio ID</label>
                  <div class="col-sm-10">
                    <input type="text" name="sl_portfolio_id" class="form-control" id="portfolio-id" placeholder="Portfolio ID" required value="<?php echo (isset($loyalty_program)?$loyalty_program->folio_id:"");?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="portfolio-name">Portfolio Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="sl_portfolio_name" class="form-control" id="portfolio-name" placeholder="like SunCabo Villa..." required value="<?php echo (isset($loyalty_program)?$loyalty_program->folio_name:"");?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="points">Points</label>
                  <div class="col-sm-10">
                    <input type="text" name="sl_loyalty_points" class="form-control" id="points" placeholder="Points" required value="<?php echo (isset($loyalty_program)?$loyalty_program->points:"");?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="points">Comment</label>
                  <div class="col-sm-10">
                  	<textarea name="sl_loyalty_comment" class="form-control" id="comment" placeholder="Comment (if any)"><?php echo (isset($loyalty_program)?$loyalty_program->comment:"");?></textarea>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="points">Status</label>
                  <div class="col-sm-4">
                  	<input type="radio" name="sl_status" checked value="0" <?php echo ( isset($loyalty_program) && $loyalty_program->status==0)? "checked":"";?> >Pending
                  	<input type="radio" name="sl_status" value="1" <?php echo (isset($loyalty_program) && $loyalty_program->status==1)? "checked":"";?>>Approved
                  </div>
                </div>
                
                
               
                <div class="row justify-content-end">
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <?php break;

          	case "list":
          	case "delete":
          			
          			?>
          	<div class="jumbotron">		
						<form method="get" action=""> 
							<input type="hidden" name="page" value="sl-manage-points">
							<input type="hidden" name="view" value="list">
						  <div class="form-row align-items-center">
						   
						    <div class="col-auto">
						      <input type="text" class="form-control" name="keyword" placeholder="Search...">
						    </div>
						    <?php
						    	$user_id = ((isset($_GET['user_id']) && $_GET['user_id']!='')?$_GET['user_id']:"");
						    	$status = ( (isset($_GET['status']) && $_GET['status']!='')?$_GET['status']:"");
						    ?>
						    <div class="col-auto">
						    	<select class="form-control select2-element" id="js-search-single" name="user_id">
						    		<option value="">Select a user</option>
									  <?php foreach ($users_records as $key => $value) { ?>
									  <option value="<?php echo $key;?>" <?php echo ($user_id == $key)?"selected='selected'":"";?>><?php echo $value;?></option>
										<?php }?>
									</select>
						    </div>

						     <div class="col-auto">
						      <select class="form-control"  name="status">
						    		<option value="">Select status</option>
						    		<option value="0" <?php echo ($status=='0')?"selected='selected'":'';?>>Pending</option>
						    		<option value="1" <?php echo ($status==1)?"selected='selected'":'';?>>Approved</option>
						    	</select>
						    </div>


						    <div class="col-auto">
						    	<button type="reset" class="btn btn-secondary">Reset</button>
						      <button type="submit" class="btn btn-primary">Search</button>
						    </div>
						  </div>
						</form>
					</div>
					</div>
					<div class="col-12 sl_bulk-action-message"></div>
					<div class="col-12">
						<?php wp_nonce_field('sl_bulk_action_loyalty_points', 'sl_nonce' ); ?>
          			<table class="table table-striped">
					  <thead>
					  	<tr>
					  		<th colspan="3">
					  			<div class="row align-items-center">
					  				<div class="col-10">
							  			<select class="form-control" name="action" id="bulk-action-selector-top">
													<option value="">Bulk actions</option>
													<option value="approve">Mark as Approved</option>
													<option value="pending">Mark as Pending</option>
													<option value="delete">Move to Trash</option>
											</select>
										</div>
										<div class="col-2">
											<input type="submit" id="loyalty-bulk-action" class="button action" value="Apply">
										</div>
									</div>
					  		</th>
					  		<th colspan="6"></th>

					  	<tr>
					    <tr>
					    	<th scope="col"><input type="checkbox" class="checkAll" name="select-all"></th>
					      <th scope="col">#</th>
					      <th scope="col">Folio ID</th>
					      <th scope="col">Folio Name</th>
					      <th scope="col">User</th>
					      <th scope="col">Points</th>
					      <th scope="col">Date</th>
					      <th scope="col">Status</th>
					      <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					   <?php 
					   	if($loyalty_points){
					   		foreach($loyalty_points as $key => $points){?>
						    <tr>
						    	<td><input type="checkbox" name="loyalty-record" value="<?php echo $points->id;?>" class="cb-element"></td>
						      <th scope="row"><?php echo ($key + 1);?></th>
						      <td><?php echo $points->folio_id;?></td>
						      <td><?php echo $points->folio_name;?></td>
						      <td><?php echo $users_records[$points->user_id];?></td>
						      <td><?php echo $points->points;?></td>
						      <td><?php echo date('F d, Y', strtotime($points->date_earned));?></td>
						      <td><?php echo $this->displayStatus($points->status);?></td>
						      <td>
						      	<div class="btn-group">
								  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Action
								  </button>
								  <?php

								  $edit_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'edit', 'id' => $points->id], admin_url('admin.php'));
								  $delete_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'delete', 'id' => $points->id], admin_url('admin.php'));
								  
								  if($points->status){
								  	$approve_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'pending', 'id' => $points->id], admin_url('admin.php'));

								  }else{

								 		$approve_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'approve', 'id' => $points->id], admin_url('admin.php'));
									}

								  ?>
								  <div class="dropdown-menu dropdown-menu-right">
								  	<a class="dropdown-item" href="<?php echo $edit_rewards;?>">Edit</a>
								  	<a class="dropdown-item" href="<?php echo $delete_rewards;?>">Delete</a>
								  	<a class="dropdown-item" href="<?php echo $approve_rewards;?>"><?php echo ($points->status)?"Mark Pending":"Approve";?></a>
								  </div>
								</div>
						      </td>
						    </tr>
						<?php } }?>
					   
					  </tbody>
					</table>
          		<?php
          	break;
          	case 'dob':
          	?>
          	<?php wp_nonce_field('sl_send_birthday_email', 'sl_nonce' ); ?>
          	<table class="table table-striped">
					  <thead>
					  	
					    <tr>
					    	<th scope="col"><input type="checkbox" class="checkAll" name="select-all"></th>
					      <th scope="col">Name</th>
					      <th scope="col">Email</th>
					      <th scope="col">DOB</th>
					      <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					   <?php 
					   	if($subscribers){
					   		foreach($subscribers as $key => $subscriber){
					   			?>
						    <tr>
						    	<td><input type="checkbox" name="loyalty-record" value="<?php echo $key;?>" class="cb-element"></td>
						      <td><?php echo $subscriber['name'];?></td>
						      <td><?php echo $subscriber['email'];?></td>
						      <td><?php echo date('F d, Y', strtotime($subscriber['dob']));?></td>
						      <td>
						      	<div class="btn-group">
								  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Action
								  </button>
								  <?php

								  $add_rewards = add_query_arg(['page' => 'sl-manage-points', 'view' => 'add', 'id' => $key, 'campaign_types' => 'birthday_points'], admin_url('admin.php'));

								  $message = html_entity_decode(get_option('sl_birthdaywish_email_body'));
								 	$message = str_replace('{first_name}', $subscriber['name'],  $message);
								 	$message = str_replace('{dob}', date('F d, Y', strtotime($subscriber['dob'])),  $message);
								 	//$message = str_replace('{loyalty_points}', $loyalty_points,  $message);


								  ?>
								  <div class="modal" tabindex="<?php echo $key?>" role="dialog" id="modal<?=$key;?>">
									    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <h5 class="modal-title">Happy Birthday Email</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">

									        	<form method="get" action="" id="frm_user_<?php echo $key;?>"> 
															<input type="hidden" name="user_id" value="<?php echo $key?>">
														  	<div class="row">
															    		<div class="form-group col-md-12">
															      		<input type="text" class="form-control" value="<?php echo $subscriber['email'];?>" name="to" placeholder="To email address">
															      	</div>
															      	<div class="form-group col-md-12">
															      		<input type="text" class="form-control" value="<?php echo get_option('sl_birthdaywish_subject');?>" name="subject" placeholder="Subject">
															      	</div>
															      	<div class="form-group col-md-12">
															      		  <textarea class="form-control" name="content" rows="10" placeholder="Email content..."><?php echo $message;?></textarea>
															      	</div>
															   </div>
														</form>


									        
									      </div>
									      <div class="modal-footer">
									      	<div id="sl_email_message_<?php echo $key;?>" class="text-left"></div>
									        <button type="button" class="btn btn-primary send-birthday-email" data-id="<?php echo $key;?>">Send Email</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									      </div>
									    </div>
									  </div>
									</div>
								  <div class="dropdown-menu dropdown-menu-right">
								  	<a class="dropdown-item" data-toggle="modal" data-target="#modal<?=$key;?>" href="javascript:void(0);">Send Email</a>
								  	<a class="dropdown-item" href="<?php echo $add_rewards;?>">Add Points</a>
								  </div>
								</div>
						      </td>
						    </tr>
						<?php } }?>
					   
					  </tbody>
					</table> 
					<?php
          	break;
          }
          ?>

        </div>
        
      </div>
    </div>
</div>
<div id="sl_user_loader"></div>