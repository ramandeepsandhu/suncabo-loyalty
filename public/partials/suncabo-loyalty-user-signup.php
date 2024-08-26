<?php 
function sl_signup_form(){
	ob_start();?>

	<div class="sign-up-form">
		<div class="popup-form" style="max-width:800px;">
			<div class="popup-form__box">		
				<p class="sl_success-msg"></p>
				<form id="sl-user-reg" class="form-inline" method="POST" name="sl_registration">
					<?php wp_nonce_field('sl_user_registration', 'sl_nonce' ); ?>
					<h3><?php echo esc_html__('Loyalty Rewards Sign-Up','suncabo-loyalty');?></h3>
					
					<div class="row">
						<div class="field-box contact-field">
							<input type="text" class="form-control" name="sl_txt_first_name" id="sl_txt_first_name" placeholder="First Name" required>
						</div>
						<div class="field-box contact-field">
							<input type="text" class="form-control" name="sl_txt_last_name" id="sl_txt_last_name" placeholder="Last Name" required>
						</div>
					</div>

					<div class="row">
						<div class="field-box contact-field">	
							<input type="email" class="form-control" name="sl_txt_email" id="sl_txt_email" placeholder="Email Address" required>
						</div>
						<div class="field-box contact-field">	
							<input type="text" class="form-control" name="sl_txt_dob" id="sl_txt_dob" placeholder="Date Of Birth" required>
						</div>
					</div>

					<div class="row">
						<div class="field-box contact-field">	
							<input type="password" class="form-control" name="sl_txt_password" id="sl_txt_password" placeholder="Password" required>
						</div>

						<div class="field-box contact-field">	
							<input type="password" class="form-control" name="sl_txt_password_confirm" id="sl_txt_password_confirm" placeholder="Retype Password" required>
						</div>
					</div>

					<div class="row">
						 
						<div class="field-box contact-field">
							<input type="text" class="form-control" name="sl_txt_street" id="sl_txt_street" placeholder="Street Address" required>
						</div>
						<div class="field-box contact-field">
							<input type="text" class="form-control" name="sl_txt_apt" id="sl_txt_apt" placeholder="Apt/Ste/Unit">
						</div>
					</div>

					<div class="row">
						<div class="field-box contact-field">	
							<input type="text" class="form-control" name="sl_txt_city" id="sl_txt_city" placeholder="City" required>
						</div>
						<div class="field-box contact-field">	
							<input type="text" class="form-control" name="sl_txt_zip_code" id="sl_txt_zip_code" placeholder="Zip/Postal Code" required>
						</div>
						
					</div>
					<div class="row">

						<div class="field-box contact-field">	
							
							<select id="sl_country" class="form-select form-control" name="sl_txt_country" required>
					            <option value="">Select Country</option>
					        </select>

							<!--<input type="text" class="form-control" name="sl_txt_country" id="sl_txt_country" placeholder="Country">-->
						</div>

						<div class="field-box contact-field">
							<select id="sl_state" class="form-control form-select" name="sl_txt_state" required>
					            <option value="">Select State</option>
					        </select>	
						</div>

						
						
					</div>
					
					<div class="checkbox">
						<input type="checkbox" id="subscribe" checked name="sl_email_preference" value="y">
		  				<label for="subscribe">Subscribe to our emails for exclusive offers and content.</label>
					</div>
					<input type="submit" name="sl_register" class="theme-btn" value="Sign Up" />
				</form>

				
				<?php $sl_signin_url = get_bloginfo('url').'/sign-in/'; ?>
				<?php $sl_forgot_pass_url = get_bloginfo('url').'/forget-password/'; ?>	
				
				<div class="form-footer">
					<span>
						<?php if(!empty($sl_signin_url)){ ?>ALREADY A LOYALTY MEMBER?
						<a href="<?php echo esc_url($sl_signin_url); ?>" ><?php echo esc_html__('SIGN IN','suncabo-loyalty'); ?></a>
						<?php } ?>
					</span> 
				</div>

			</div>
		</div>
	</div>

	<div class="modal fade" id="thank-you-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
		 <div class="modal-header">
		    <!--<h5 class="modal-title" id="staticBackdropLabel">Welcome to Sun Cabo Loyalty!</h5>-->
		    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
		  </div>
		  <div class="modal-body" style="padding:0px;">
		  	<div id="welcome-section">
				<div class="banner-wrap">
					<div class="container">
						<div class="banner-img">
							<img style="width:65%;" src="<?php echo get_template_directory_uri();?>/assets/images/sucabo-welcome-vacation.png" alt="image">
						</div>
					</div>
				</div>

				<div class="welcome-wrap">
					<div class="container">
						<div class="welcome-info">
							<p>welcome to</p>
							<h1>sun cabo loyalty!</h1>
							<p>Thank you for signing up! Your exclusive Aeroflex Leather Stand will be on its way to you shortly. <br>Please ensure your mailing address is correct to avoid any delays. If you need to update your address or if you have any questions, don't hesitate to reach out to us at <a href="#">loyalty@suncabo.com</a>.</p>
							<p>We're thrilled to have you on board and can't wait to enhance your stays with us in Los Cabos. </p>

						</div>
						<div class="login-btn-popup">
							<div class="d-flex justify-content-center login-btn-">
							  <a class="theme-btn "  href="<?php echo esc_url($sl_signin_url);?>">sign in</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		  </div>
		  
		</div>
		</div>
		</div>




	<?php if(false){?>

	<!--REGISTRATION FORM HTML-->
	<div class="sl_form-wrapper iflair-plugin">
		<?php
		$sl_user_logo_img = get_option('sl_user_logo_img');
		if (!empty($sl_user_logo_img))
		{?>
			<div class="sl_iflair-form-logo">
				<span class="sl_user-image" style="background-image: url('<?php echo esc_url($sl_user_logo_img); ?>');"></span>
			</div><?php
		}?>
		<h2><?php echo esc_html__('Loyalty Rewards Sign-Up','suncabo-loyalty');?></h2>
		<p class="sl_success-msg"></p>
		<form id="sl-user-reg" class="form-inline" method="POST" name="sl_registration">
			<?php wp_nonce_field('sl_user_registration', 'sl_nonce' ); ?>
			
			<div class="row">
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_first_name" id="sl_txt_first_name" placeholder="First Name">
				</div>
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_last_name" id="sl_txt_last_name" placeholder="Last Name">
				</div>
			</div>

			<div class="row">
				<div class="sl_form-field col-md-6">
					<input type="email" class="form-control" name="sl_txt_email" id="sl_txt_email" placeholder="Email Address">
				</div>
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_dob" id="sl_txt_dob" placeholder="Date Of Birth">
				</div>
			</div>

			<div class="row">
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_username" id="sl_txt_username" placeholder="Username">
				</div>
				<div class="sl_form-field col-md-6">
					<input type="password" class="form-control" name="sl_txt_password" id="sl_txt_password" placeholder="Password"><i class="fa fa-eye fa-eye-slash" id="sl_togglePassword" ></i>
					<span class="sl_user_password_generate"><?php echo esc_html__('Generate','suncabo-loyalty');?></span>
				</div>
			</div>

			<div class="row">
				<div class="sl_form-field col-md-12">
					<input type="text" class="form-control" name="sl_txt_street" id="sl_txt_street" placeholder="Street Address">
				</div>
			</div>

			<div class="row">
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_city" id="sl_txt_city" placeholder="City">
				</div>
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_state" id="sl_txt_state" placeholder="State/Province">
				</div>
			</div>

			<div class="row">
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_zip_code" id="sl_txt_zip_code" placeholder="Zip/Postal Code">
				</div>
				<div class="sl_form-field col-md-6">
					<input type="text" class="form-control" name="sl_txt_country" id="sl_txt_country" placeholder="Country">
				</div>
			</div>



			<div class="sl_action-field">
				<input type="submit" name="sl_register" class="btn btn-default" value="Sign Up" />
			</div>
			<?php $sl_signin_url = get_bloginfo('url').'/sign-in/'; ?>
			<?php $sl_forgot_pass_url = get_bloginfo('url').'/forget-password/'; ?>	<div class="sl_form-footer">
				<span class="sl_forgotPassword">
					<?php if(!empty($sl_signin_url)){ ?>
					<a href="<?php echo esc_url($sl_signin_url); ?>" ><?php echo esc_html__('Sign In','suncabo-loyalty'); ?></a>
					<?php } ?>
				</span>
				<?php if(!empty($sl_forgot_pass_url)) { ?>
				<a href="<?php echo esc_url($sl_forgot_pass_url); ?>"><?php echo esc_html__('Forgot Password?','suncabo-loyalty'); ?></a>
				<?php } ?>
			</div>
		</form>
	</div>
	<?php }?>
	<!--END REGISTRATION FORM HTML-->
	<?php
	return ob_get_clean();
}