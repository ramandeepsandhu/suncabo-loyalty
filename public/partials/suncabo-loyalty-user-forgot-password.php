<?php 
function sl_forgot_password_form(){
	ob_start();?>

	<div class="login-form">
		<div class="popup-form">
			<div class="popup-form__box">
				<p class='sl_forgot-message'></p>
				<form method="POST" id="sl_forgot-password" name="sl_forgot-password" action="">
					<?php wp_nonce_field('sl_forget_password', 'sl_wpnonce');?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url(site_url());?>">
					<h3><?php echo esc_html__("Forgot Password",'suncabo-loyalty');?></h3>
					
					<div class="contact-field field-box field-box__alt" style="width:100%;">
						<input id="sl_username_email" type="text" name="sl_fp_email_username" placeholder="Email Address/ Username"/>
						<label id="sl_username_email-error" class="sl_error" for="sl_username_email"><?php echo esc_html__('Please enter email or user name','suncabo-loyalty');?></label>
					</div>
					<?php
						$sl_sign_up_url = get_bloginfo('url').'/sign-up/'; 
						$sl_sign_in_url = get_bloginfo('url').'/sign-in/';
					?>

						<div class="signin-signup-form__box">
							<a class="" href="<?php echo esc_url($sl_sign_in_url);?>"><?php echo esc_html__("Sign In",'suncabo-loyalty');?></a> | <a class="" href="<?php echo esc_url($sl_sign_up_url); ?>"><?php echo esc_html__("Sign Up",'suncabo-loyalty');?></a>
						</div>

					
				
					<input type="submit" id="sl_Send" class="theme-btn" name="sl_Send" value="Send">
				</form>
				
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
?>