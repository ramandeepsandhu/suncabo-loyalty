<?php 
function sl_signin_form(){
	ob_start();?>
		<div class="login-form">
			<div class="popup-form">
				<div class="popup-form__box">
					<p class='sl_login-message-con'></p>
					<form id="sl_login" name="form" method="post" action=""> 
						<?php wp_nonce_field('sl_user_login', 'sl_user_login');?>
						
						<input type="hidden" name="redirect_to" value="<?php echo esc_url(site_url());?>">
						<h3><?php echo esc_html__("Login",'suncabo-loyalty');?></h3>
						
						<div class="contact-field field-box field-box__alt" style="width:100%;">
							<input id="sl_email" type="email" placeholder="Email Address" name="sl_email">
						</div>
						<div class="contact-field field-box field-box__alt" style="width:100%;">
							<input id="sl_password" type="password" placeholder="Password" name="sl_password">
							<!--<i class="fa fa-eye fa-eye-slash" id="sl_togglePassword" ></i>-->
						</div>
						<?php
							$sl_sign_up_url = get_bloginfo('url').'/sign-up/'; 
							$sl_forgot_pass_url = get_bloginfo('url').'/forget-password/';
						?>
						<?php $remember_forever_val = 'forever'; ?>
						
						<!-- <div class="sl_form-field remember-me checkbox">
							<input type="checkbox" name="sl_rememberme" value="<?php if(!empty($remember_forever_val)){ echo esc_attr($remember_forever_val); } ?>"><label for="subscribe"><?php echo esc_html__(" Remember Me",'iflair-user-signin-signup');?></label>
						</div>-->
						<div class="contact-field field-box field-box__alt" style="width:100%;">
							<a class="forgot-password" href="<?php echo esc_url($sl_forgot_pass_url);?>"><?php echo esc_html__("Forgot your password?",'suncabo-loyalty');?></a>
						</div>
						
						<input id="sl_submit" class="theme-btn" type="submit" name="sl_submit" value="Sign in">
					</form>

					<div class="form-footer">
						<span>
							<?php if(!empty($sl_sign_up_url)){ ?>NOT A LOYALTY MEMBER?
							<a href="<?php echo esc_url($sl_sign_up_url); ?>" ><?php echo esc_html__('SIGN UP','suncabo-loyalty'); ?></a>
							<?php } ?>
						</span> 
					</div>
				</div>
			</div>
		</div>

		<?php if(false){?>

		<div class="sl_form-wrapper iflair-plugin">
			<?php
			global $current_user;
			$sl_user_profile = get_user_meta($current_user->ID, "user_profile",true);
			$sl_user_logo_img = get_option('sl_user_logo_img');
			if(!isset($sl_user_profile[0]) && !empty($sl_user_logo_img))
			{ ?>
				<div class="sl_iflair-form-logo">
					<span class="sl_user-image" style="background-image: url('<?php echo esc_url($sl_user_logo_img); ?>');"></span>
				</div><?php
			} ?>
			<h2><?php echo esc_html__("Sign In",'suncabo-loyalty');?></h2>
			<p class='sl_login-message-con'></p>
			<form id="sl_login" name="form" method="post" action=""> 
				<?php wp_nonce_field('sl_user_login', 'sl_user_login');?>
				<div class="sl_form-field">
					<input id="sl_email" type="text" placeholder="*Please enter email or user name" name="sl_email">
				</div>
				<div class="sl_form-field">
					<input id="sl_password" type="password" placeholder="*Please enter password" name="sl_password"><i class="fa fa-eye fa-eye-slash" id="sl_togglePassword" ></i> <!--onclick="myFununction()"-->
				</div>
				<?php $remember_forever_val = 'forever'; ?>
				<div class="sl_form-field remember-me">
					<input type="checkbox" name="sl_rememberme" value="<?php if(!empty($remember_forever_val)){ echo esc_attr($remember_forever_val); } ?>"><?php echo esc_html__(" Remember Me",'iflair-user-signin-signup');?>
				</div>
				<div class="sl_action-field">
					<input id="sl_submit" type="submit" name="sl_submit" value="Submit">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url(site_url());?>">
				</div>
				<?php
				$sl_sign_up_url = get_bloginfo('url').'/sign-up/'; 
				$sl_forgot_pass_url = get_bloginfo('url').'/forget-password/';
				?>
				<div class="sl_form-footer">
					<span class="sl_forgotPassword">
						<a href="<?php echo esc_url($sl_sign_up_url); ?>"><?php echo esc_html__("Sign Up",'suncabo-loyalty');?></a>
					</span>
					<a href="<?php echo esc_url($sl_forgot_pass_url);?>"><?php echo esc_html__("Forgot Password?",'suncabo-loyalty');?></a>
				</div>
			</form>
		</div>
	<?php }?>
		<?php 
	return ob_get_clean();
}