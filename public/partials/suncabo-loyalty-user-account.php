<?php
function sl_user_account(){
$Suncabo_Loyalty_Points = new Suncabo_Loyalty_Points();
$tier_status = $Suncabo_Loyalty_Points->get_user_tier_status();
?>

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
						<p>You currently have <a href="<?php echo esc_url(get_bloginfo('url').'/dashboard/#rewards-history'); ?>"><?php echo $tier_status['user_total_points_earned'];?></a> points</p>
					</div>
				<?php }?>
				<div class="loyalty-login-btns">
					<a href="<?php echo esc_url(get_bloginfo('url').'/dashboard/'); ?>">dashboard</a> 
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
<div class="contact-wrap">
	<div class="container">
		<div class="contact-row">
			<div class="contact-col">
				<h2>Contact Information</h2>
				<p>All fields are required unless indicated as optional.</p>
				<?php
					global $current_user, $wp_roles;
					$successmsg = "";
					$user_ID = get_current_user_id();

					$sl_firstname 	= $current_user->first_name;
					$sl_lastname 	= $current_user->last_name;
					$sl_dob 		= get_the_author_meta('dob', $current_user->ID);
					$sl_street 		= get_the_author_meta('street', $current_user->ID);
					$sl_city 		= get_the_author_meta('city', $current_user->ID);
					$sl_state 		= get_the_author_meta('state', $current_user->ID);
					$sl_zipcode 	= get_the_author_meta('zipcode', $current_user->ID);
					$sl_country 	= get_the_author_meta('country', $current_user->ID);
					$sl_phone_number = get_the_author_meta('phone_number', $current_user->ID);
					$sl_alt_phone_number = get_the_author_meta('alt_phone_number', $current_user->ID);

					$sl_email 		= get_the_author_meta('user_email', $current_user->ID);
					$sl_apt 		= get_the_author_meta('apt', $current_user->ID);
					$sl_user_profile = get_user_meta($current_user->ID, "user_profile");

					$sl_email_preference = get_user_meta($current_user->ID, "email_preference", $current_user->ID);

					if(!$sl_email_preference){
						$sl_email_preference = 'send';
					}
				?>
				<form method="post" name="sl_edit_profile" class="form-user-account" id="sl_adduser" enctype='multipart/form-data'>
					<?php wp_nonce_field('sl_update-user', 'sl_update_user') ?>
					<div class="contact-field-box">
						<div class="contact-field">
							<label for="first-name">First Name</label>
							<input type="text" class="form-control" name="sl_txt_first_name" id="sl_txt_first_name" placeholder="First Name" value="<?php if(!empty($sl_firstname)){ echo esc_attr($sl_firstname); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="last-name">Last Name</label>
							<input type="text" class="form-control" name="sl_txt_last_name" id="sl_txt_last_name" placeholder="Last Name" value="<?php if(!empty($sl_lastname)){ echo esc_attr($sl_lastname); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="date-of-birth">Date of Birth</label>
							<input type="text" class="form-control" name="sl_txt_dob" id="sl_txt_dob" placeholder="Date Of Birth" value="<?php if(!empty($sl_dob)){ echo esc_attr($sl_dob); } ?>"/>
						</div>
						<div class="contact-field  full-width">
							<label for="street-address">Street Address</label>
							<input type="text" class="form-control" name="sl_txt_street" id="sl_txt_street" placeholder="Street Name" value="<?php if(!empty($sl_street)){ echo esc_attr($sl_street); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="apt-ste-unit">Apt/Ste/Unit (Optional)</label>
							<input type="text" class="form-control" name="sl_apt" id="sl_apt" placeholder="Apt/Ste/Unit" value="<?php if(!empty($sl_apt)){ echo esc_attr($sl_apt); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="city">City</label>
							<input type="text" class="form-control" name="sl_txt_city" id="sl_txt_city" placeholder="City" value="<?php if(!empty($sl_city)){ echo esc_attr($sl_city); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="state">State</label>
							<input type="text" class="form-control" name="sl_txt_state" id="sl_txt_state" placeholder="State/Province" value="<?php if(!empty($sl_state)){ echo esc_attr($sl_state); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="country">Country</label>
							<input type="text" class="form-control" name="sl_txt_country" id="sl_txt_country" placeholder="Country" value="<?php if(!empty($sl_country)){ echo esc_attr($sl_country); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="postal-code">Zip/Postal Code</label>
							<input type="text" class="form-control" name="sl_txt_zip_code" id="sl_txt_zip_code" placeholder="Zip/Postal Code" value="<?php if(!empty($sl_zipcode)){ echo esc_attr($sl_zipcode); } ?>"/>
						</div>
						<!--<div class="contact-field full-width">
							<label for="email">Email Address</label>
							<input class="text-input" name="sl_txt_email" type="email" id="sl_txt_email" placeholder="Email Address" value="<?php if(!empty($sl_email)){ echo esc_attr($sl_email); } ?>"/>
						</div>-->
						<div class="contact-field">
							<label for="phone1">Phone Number</label>
							<input type="tel" class="form-control" name="sl_txt_phone_number" id="sl_txt_phone_number" placeholder="Phone Number" value="<?php if(!empty($sl_phone_number)){ echo esc_attr($sl_phone_number); } ?>"/>
						</div>
						<div class="contact-field">
							<label for="phone2">Alt Phone Number (Optional)</label>
							<input type="tel" class="form-control" name="sl_txt_alt_phone_number" id="sl_txt_alt_phone_number" placeholder="Alt Phone Number (optional)" value="<?php if(!empty($sl_alt_phone_number)){ echo esc_attr($sl_alt_phone_number); } ?>"/>
						</div>
					</div>

					<?php wp_nonce_field('sl_update-user') ?>
					<input name="action" type="hidden" id="action" value="sl_updates-user" />
					<input class="theme-btn" type="submit" id="sl_updateuser" value="<?php echo esc_html__('save changes','suncabo-loyalty'); ?>">
					<div class="sl_edit-profile-message"></div>
				</form>
			</div>
			<div class="contact-col change-password">
				<h2>Change Password</h2>
				<p>All fields are required.</p>
				<div class="sl_change-password-message"></div>

				<form method="post" name="sl_change_password" class="form-user-account">
					<?php wp_nonce_field('sl_password_change',"sl_password_change");?>
					<div class="pass-field-box">
						<div class="pass-field">
							<label for="current-password">Current Password</label>
							<input id="sl_old_password" type="password" name="sl_old_password" placeholder="*Please enter old password">
						</div>
						<div class="form-divider"></div>
						<div class="pass-field">
							<label for="new-password">New Password</label>
							<input id="sl_new_password" type="password" name="sl_new_password" placeholder="*Please enter new password">
						</div>
						<div class="pass-field">
							<label for="repeat-new-password">Repeat New Password</label>
							<input id="sl_confirm_password" type="password" name="sl_confirm_password" placeholder="*Please enter confirm password">
						</div>
					</div>
				

				<div class="form-requirements">
					<p>Password Requirements</p>
					<p>Cannot be a previously used password</p>
					<p>8 - 15 characters with no spaces</p>
					<p>At least one uppercase letter</p>
					<p>At least one lowercase letter</p>
					<p>At least one number or one of these six characters: !,@,#,$,&,*</p>
				</div>
				<input class="theme-btn" type="submit" id="sl_change-password" name="sl_change-password" value="save changes">

				</form>
			</div>
		</div>
		<div class="email-preference">
			<h2>Email Preferences</h2>
			<p>Primary Email Address</p>
			<p><?php if(!empty($sl_email)){ echo esc_attr($sl_email); } ?></p>
			<div class="manage-commnuication">
				<p>Email Preferences</p>
				<form method="post" name="sl_email_preferences" id="sl_email_preferences">
					<?php wp_nonce_field('sl_user_email_preferences') ?>
					<div class="radio-btn">
						<div class="radio-field">
							<input type="radio" id="send" name="email_preference"  <?php echo ($sl_email_preference== 'send')?'checked':'';?> value="send">
							<label for="send">Send</label>
						</div>
						<div class="radio-field">
							<input type="radio" id="not-send" name="email_preference" value="do-not-send" <?php echo ($sl_email_preference== 'do-not-send')?'checked':'';?>>
							<label for="not-send">Don’t Send Emails</label>
						</div>
					</div>
					<input class="theme-btn" type="submit"  name="sl_email-preferences" value="save changes">
					<div class="sl_email-preferences-message"></div>
				</form>

			</div>
		</div>
	</div>
</div>
<div id="sl_user_loader"></div>

<?php return ob_get_clean();} ?>