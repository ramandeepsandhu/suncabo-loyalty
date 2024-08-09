	<div class="wrap sl_a_admin">
		<h1><?php echo esc_html__('SunCabo Loyalty Program','suncabo-loyalty');?></h1>
		<form method="post" action="options.php" class="admin-sl-settings">
			<?php wp_nonce_field( 'sl-admin-settings', 'sl_admin_settings' ); ?>
		    <?php settings_fields( 'suncabo-loyalty' ); ?>
		    <?php do_settings_sections( 'suncabo-loyalty' ); ?>
		    <div class="sl_a_tabing-wrapper sl_a_admin-tabing">
				<!-- Nav tabs -->
				<ul class="sl_a_tabs">
					<li class="sl_a_tab-link sl_a_shortcode_info sl_a_current" data-tab="sl_site_shortcodes"><?php echo esc_html__('Shortcode information','suncabo-loyalty');?></li>
					<li class="sl_a_tab-link sl_a_user_role_setting" data-tab="sl_user_section"><?php echo esc_html__('User role settings','suncabo-loyalty');?></li>
					<li class="sl_a_tab-link sl_a_user_page_setting" data-tab="sl_page_customization"><?php echo esc_html__('Sign Up / Sign In form settings','suncabo-loyalty');?></li>
					<li class="sl_a_tab-link sl_a_email_setting" data-tab="sl_mail_settings"><?php echo esc_html__('Email settings','suncabo-loyalty');?></li>
				</ul>

				<div class="sl_a_tab-content sl_a_current" id="sl_site_shortcodes">
					<div class="sl_a_shortcode-copy">
						<h2><?php echo esc_html__('Copy below shortcode and paste in any page','suncabo-loyalty');?></h2>
						<div class="sl_a_click card">
							<div class="sl_a_shortcode-frm">				
								<label><?php echo esc_html__('Sign Up form :','suncabo-loyalty');?></label>
								<input type="text" name="sl_registration_form" onfocus="this.select();" value="<?php if(!empty(get_option('sl_registration_form'))) { echo esc_attr( get_option('sl_registration_form') ); } ?>" class="sl_a_copy" readonly>
								<div class="sl-copied-txt" style="display: none;"><h4><?php echo esc_html__('Copied !','suncabo-loyalty');?></h4></div><br>
							</div>
							<div class="sl_a_shortcode-frm">
								<label><?php echo esc_html__('Sign In form :','suncabo-loyalty');?></label>	
								<input type="text" name="sl_login_form" onfocus="this.select();" value="<?php if(!empty(get_option('sl_login_form'))) { echo esc_attr( get_option('sl_login_form') ); } ?>" class="sl_a_copy" readonly><div class="sl-copied-txt" style="display: none;"><h4><?php echo esc_html__('Copied !','suncabo-loyalty');?></h4></div><br>
							</div>
							<div class="sl_a_shortcode-frm">
								<label><?php echo esc_html__('My account page :','suncabo-loyalty');?></label>		
								<input type="text" name="sl_my_account" onfocus="this.select();" value="<?php if(!empty(get_option('sl_my_account'))) { echo esc_attr( get_option('sl_my_account') ); } ?>" class="sl_a_copy" readonly><div class="sl-copied-txt" style="display: none;"><h4><?php echo esc_html__('Copied !','suncabo-loyalty');?></h4></div><br>
							</div>
							<div class="sl_a_shortcode-frm">			
								<label><?php echo esc_html__('Forgot password form :','suncabo-loyalty');?></label>
								<input type="text" name="sl_forgot_password" onfocus="this.select();" value="<?php if(!empty(get_option('sl_forgot_password'))) { echo esc_attr( get_option('sl_forgot_password') ); } ?>" class="sl_a_copy" readonly><div class="sl-copied-txt" style="display: none;"><h4><?php echo esc_html__('Copied !','suncabo-loyalty');?></h4></div><br>
							</div>
						</div>
					</div>
				</div>

				<div class="sl_a_tab-content" id="sl_user_section">
					<div class="sl_a_user_roles_select">
						<h2><?php echo esc_html__('Please choose role for new user Sign Up','suncabo-loyalty');?></h2>
						<div class="card">
							<?php global $wp_roles; ?>
							<select name="sl_role">
								<option value="" disabled="disabled"><?php echo esc_html__('Choose User Role','suncabo-loyalty');?></option>
								<?php foreach ($wp_roles->roles as $key => $user_role_value)
								{ ?>
									<option value="<?php echo esc_attr($key);?>" <?php selected(esc_attr(get_option('sl_role')) , $key);?>><?php echo esc_html($user_role_value['name']);?></option>
									<?php
								} ?>
							</select>
						</div>
					</div>
				</div>

				<div class="sl_a_tab-content" id="sl_page_customization">
					<div class="sl_a_inner-column-wrapper">
						<div class="sl_a_form_colors sl_a_col-6">
							<h2><?php echo esc_html__('Set colors','suncabo-loyalty');?></h2>
							<div class="sl_a_add_color"> 
								<div class="sl_a_input">
									<label for="sl_primary_color"><?php echo esc_html__('Select background color (Primary color)','suncabo-loyalty');?></label>
									<input type="color" name="sl_primary_color" id="sl_primary_color" value="<?php if(!empty(get_option('sl_primary_color'))) { echo esc_attr( get_option('sl_primary_color') ); } ?>">
								</div>
								<div  class="sl_a_input">
									<label for="sl_secondary_color"><?php echo esc_html__('Select text color (Primary color)','suncabo-loyalty');?></label>
									<input type="color" name="sl_secondary_color" id="sl_secondary_color" value="<?php if(!empty(get_option('sl_secondary_color'))) { echo esc_attr( get_option('sl_secondary_color') ); } ?>">
								</div>
								<div  class="sl_a_input">
									<label for="sl_pre_secondary_color"><?php echo esc_html__('Select hover text color (Secondary color)','suncabo-loyalty');?></label>
									<input type="color" name="sl_pre_secondary_color" id="sl_pre_secondary_color" value="<?php if(!empty(get_option('sl_pre_secondary_color'))) { echo esc_attr( get_option('sl_pre_secondary_color') ); } ?>">
								</div>
								<div  class="sl_a_input">
									<label for="sl_pre_secondary_hover_color"><?php echo esc_html__('Select hover button background color (Secondary color)','suncabo-loyalty');?></label>
									<input type="color" name="sl_pre_secondary_hover_color" id="sl_pre_secondary_hover_color" value="<?php if(!empty(get_option('sl_pre_secondary_hover_color'))) { echo esc_attr( get_option('sl_pre_secondary_hover_color') ); } ?>">
								</div>
							</div>
						</div>
						<div class="sl_a_form_custom_message sl_a_col-6">
							<h2><?php echo esc_html__('For every forms :: Messages','suncabo-loyalty');?></h2>
							<p class="sl_a_form_success_message"><?php echo esc_html__('You can edit messages used in various situations here','suncabo-loyalty');?></p>
							<div id="sl_logo"> 
								<div class="sl_a_input">
									<label for="sl_login_msg"><?php echo esc_html__('Sign In success message','suncabo-loyalty');?></label>
									<input type="text" name="sl_login_msg" id="sl_login_msg" class="regular-text" value="<?php if(!empty(get_option('sl_login_msg'))) { echo esc_attr( get_option('sl_login_msg') ); } ?>">
								</div>
								<div class="sl_a_input">
									<label for="sl_registration_msg"><?php echo esc_html__('Sign Up success message','suncabo-loyalty');?></label>
									<input type="text" name="sl_registration_msg" id="sl_registration_msg" class="regular-text" value="<?php if(!empty(get_option('sl_registration_msg'))) { echo esc_attr( get_option('sl_registration_msg') ); } ?>">
								</div>
								<div class="sl_a_input">
									<label for="sl_forget_pass_msg"><?php echo esc_html__('Forget password success message','suncabo-loyalty');?></label>
									<input type="text" name="sl_forget_pass_msg" id="sl_forget_pass_msg" class="regular-text" value="<?php if(!empty(get_option('sl_forget_pass_msg'))) { echo esc_attr( get_option('sl_forget_pass_msg') ); } ?>">
								</div>
								<div class="sl_a_input">
									<label for="sl_change_pass_msg"><?php echo esc_html__('Change password success message','suncabo-loyalty');?></label>
									<input type="text" name="sl_change_pass_msg" id="sl_change_pass_msg" class="regular-text" value="<?php if(!empty(get_option('sl_change_pass_msg'))) { echo esc_attr( get_option('sl_change_pass_msg') ); } ?>">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="sl_a_tab-content" id="sl_mail_settings">
					<div class="sl_a_inner-column-wrapper">
						<div class='sl_a_user-mail sl_a_col-6'>
							<h2><?php echo esc_html__('All mail settings','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<label><?php echo esc_html__('From email','suncabo-loyalty');?> </label>
								<input type="text" name="sl_user_from_email" id="sl_user_from_email" class="regular-text" value="<?php if(!empty(get_option('sl_user_from_email'))) {echo esc_attr( get_option('sl_user_from_email') ); } ?>">
							</div>

							<div class='sl_a_input'>
								<label><?php echo esc_html__('Email signature','suncabo-loyalty');?></label>
								<?php  wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_signature')  ), 'sl_user_signature', array('editor_height' => 120)));  ?>
							</div>
						</div>
						<hr>
						<div class='sl_a_all-mail sl_a_col-6'>
							<h2><?php echo esc_html__('Email subjects','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<label><?php echo esc_html__("Sign Up email 'Subject' for user",'suncabo-loyalty');?> </label> 
								<input type="text" name="sl_user_subject" id="sl_user_subject" class="regular-text" value="<?php if(!empty(get_option('sl_user_subject'))) { echo esc_attr( get_option('sl_user_subject') ); } ?>">
							</div>
							<div class='sl_a_input'> 
								<label><?php echo esc_html__("Sign Up email 'Subject' for admin",'suncabo-loyalty');?> </label>
								<input type="text" name="sl_admin_subject" id="sl_admin_subject" class="regular-text" value="<?php if(!empty(get_option('sl_admin_subject'))) { echo esc_attr( get_option('sl_admin_subject') ); } ?>">
							</div>
							<div class='sl_a_input'>
								<label><?php echo esc_html__("Forgot password email 'Subject'",'suncabo-loyalty');?> </label>
								<input type="text" name="sl_userforgot_subject" id="sl_userforgot_subject" class="regular-text" value="<?php if(!empty(get_option('sl_userforgot_subject'))) { echo esc_attr( get_option('sl_userforgot_subject') ); } ?>">
							</div>
							<div class='sl_a_input'>
								<label><?php echo esc_html__("Change password email 'Subject'",'suncabo-loyalty');?> </label>
								<input type="text" name="sl_userchange_subject" id="sl_userchange_subject" class="regular-text" value="<?php if(!empty(get_option('sl_userchange_subject'))) { echo esc_attr( get_option('sl_userchange_subject') ); } ?>">
							</div>
							<div class='sl_a_input'>
								<label><?php echo esc_html__("Approve points email 'Subject'",'suncabo-loyalty');?> </label>
								<input type="text" name="sl_approvepoints_subject" id="sl_approvepoints_subject" class="regular-text" value="<?php if(!empty(get_option('sl_userchange_subject'))) { echo esc_attr( get_option('sl_approvepoints_subject') ); } ?>">
							</div>
						</div>
					</div>
					<hr>
					<div><?php echo esc_html__("In the following fields, you can use these mail-tags : {user_name} {site_name} {user_email} {user_password} {Please activate your account}", "suncabo-loyalty"); ?></div>
					<div class="sl_a_inner-column-wrapper">
						<div class='user-mail-body sl_a_col-6'>
							<h2><?php echo esc_html__('Sign Up email body for : User email','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<?php wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_registration_email_body')), 'sl_user_registration_email_body', array('editor_height' => 300)));  ?>
							</div>
						</div>
						<div class='user-mail-body sl_a_col-6'>
							<h2><?php echo esc_html__('Sign Up email body for : Admin email','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<?php  wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_registration_email_body_admin')), 'sl_user_registration_email_body_admin', array('editor_height' => 300))); ?>
							</div>
						</div>
						<div class='user-mail-body sl_a_col-6'>
							<h2><?php echo esc_html__('Forget password email body','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<?php wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_forget_password_email_body')), 'sl_user_forget_password_email_body', array('editor_height' => 300)));  ?>
							</div>
						</div>
						<div class='user-mail-body sl_a_col-6'>
							<h2><?php echo esc_html__('Change password email body','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<?php  wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_password_change_email_body')), 'sl_user_password_change_email_body', array('editor_height' => 300))); ?>
							</div>
						</div>

						<hr>
						<div><?php echo esc_html__("In the following fields, you can use these mail-tags : {user_name} {site_name} {user_email} {points} {loyalty_program} ", "suncabo-loyalty"); ?></div>

						<div class='user-mail-body sl_a_col-6'>
							<h2><?php echo esc_html__('Approve points email body : Admin email','suncabo-loyalty');?></h2>
							<div class='sl_a_input'>
								<?php  wp_kses_post(wp_editor( html_entity_decode(get_option('sl_user_approve_points_email_body_admin')), 'sl_user_approve_points_email_body_admin', array('editor_height' => 300))); ?>
							</div>
						</div>

					</div>
				</div>
			</div>
		    <?php submit_button(); ?>
		</form>
	</div>