(function( $ ) {
	'use strict';


	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
})( jQuery );
jQuery(document).ready(function ($) {  
    //Plugin Form Logo Remove
    $(document).on('click',".input_img .sl_remove-image",function(){ 
        $(this).parents('.input_img').find('input[type=hidden]').val('');
        $(this).parents('.input_img').find('.sl_image-wrap').html('');
    }); 
    //My Account Page Profile Picture Remove
    $(document).on('click',".sl_profile-picture .sl_remove-image",function(){
        $(this).parents('.sl_profile-picture').find('.sl_image-wrap').html('');
    });     
   
    //nav tabs for theme options
    $('ul.sl_tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('ul.sl_tabs li').removeClass('sl_current');
        $('.sl_tab-content').removeClass('sl_current');

        $(this).addClass('sl_current');
        $("#" + tab_id).addClass('sl_current');
    });

    $.validator.addMethod('passwordvalidation', function(value, element, param) 
    {
        var nameRegex = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        return nameRegex.test(value);
    }, 'You need to create your password with minimum 8 characters and including alphnumeric and add one special character like !@#$%^&*()');
            
    $("form[name='sl_registration']").validate(
    {
        rules: {
        	sl_txt_first_name : "required",
        	sl_txt_last_name : "required",
            sl_txt_email: { 
                required: true,
            },
            sl_txt_dob : "required",
            sl_txt_password: {
                required: true,
                passwordvalidation: true
            },
            sl_txt_password_confirm : {
                required: true,
                equalTo : '[name="sl_txt_password"]'
            },
            sl_txt_street : "required",
            sl_txt_city : "required",
            sl_txt_zip_code : "required",
            sl_country : "required",
            sl_state : "required",

        },
        messages: {
        	sl_txt_first_name: {
            	required: "Please enter first name."
            },
            sl_txt_last_name: {
            	required: "Please enter last name."
            },
            sl_txt_password: {
                required: "Please enter password.",
            },
            sl_txt_password_confirm : {
                required: "Please enter your confirm password.",
                equalTo : 'Password and confirm password not match.'
            },
            sl_txt_email: {
                required: "Please enter email.",
                sl_txt_email: "Please enter a valid email address."
            },
        },
        submitHandler: function (form) {
            
            var ajax_url= sl_ajaxObj.ajax_url;
            var sl_nonce = $(form).find('#sl_wpnonce').val();
            
            var data = {
            	data : $(form).serialize(),
            	action : "register_user",
            	sl_nonce : sl_nonce,
            };

            $.ajax({
                url: ajax_url,
                type:'POST',
                data: data,
                beforeSend: function()
                {
                    $("#sl_user_loader").fadeIn(500);
                },
                success: function(response)
                {                       
                    var sl_res = JSON.parse(response);              
                    if(sl_res.sl_success_msg){
                    	$(form)[0].reset();
                    	$('#thank-you-modal').modal('show');
                        //$(".sl_success-msg").text(sl_res.sl_success_msg);
                    }
                    if(sl_res.sl_username_space){
                        $(sl_res.sl_username_space).insertAfter('.sign-up-form h3');
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);
                    } else if(sl_res.sl_username_empty){
                        $(sl_res.sl_username_empty).insertAfter('.sign-up-form h3');
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);                             
                    } else if(sl_res.sl_username_exists){
                        $(sl_res.sl_username_exists).insertAfter('.sign-up-form h3');
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);                              
                    } else if(sl_res.sl_email_valid){
                        $(sl_res.sl_email_valid).insertAfter('.sign-up-form h3');
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);                             
                    } else if(sl_res.sl_email_existence){
                        $(sl_res.sl_email_existence).insertAfter('.sign-up-form h3');
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);                             
                    } else if(sl_res.sl_password){
                        $(sl_res.sl_password).insertAfter('.sign-up-form h3');    
                        setTimeout(function() { $(".sign-up-form p.sl_user-sign-up.sl_error").remove(); }, 5000);     
                    }                   
                },
                complete:function(data)
                {
                    $("#sl_user_loader").fadeOut(500);
                }
            });
            
        }
    });
            
    //SHOW HIDE PASSWORD FIELD
    const sl_togglePassword = document.querySelector('#sl_togglePassword');
    const sl_password = document.querySelector('#sl_txt_password');
    if(sl_togglePassword) {
        sl_togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const sl_type = sl_password.getAttribute('type') === 'password' ? 'text' : 'password';
            sl_password.setAttribute('type', sl_type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    }
    //END SHOW HIDE PASSWORD FIELD

    $(document).on("click",".sl_user_password_generate",function(){
        sl_generatePassword();
    });
    
    function sl_generatePassword(length = 20) {
        let sl_generatedPassword = "";
        const sl_validChars = "0123456789" +
        "abcdefghijklmnopqrstuvwxyz" +
        "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +
        ",.-{}+!\"#$%/()=?" + "!@#$%^&*";
        for (let i = 0; i < length; i++) {
            let sl_randomNumber = crypto.getRandomValues(new Uint32Array(1))[0];
            sl_randomNumber = sl_randomNumber / 0x100000000;
            sl_randomNumber = Math.floor(sl_randomNumber * sl_validChars.length);
            sl_generatedPassword += sl_validChars[sl_randomNumber];
        }
        $("#sl_txt_password").val(sl_generatedPassword);
    }

    // User account page $
    setTimeout(function ()
    {
     var sl_currentTab = localStorage.getItem('current');
     var sl_currentur = localStorage.getItem('currentusr');
     var sl_chkur = $('#sl_chkuser').val();

        if (sl_currentTab && sl_currentur == sl_chkur) {
            $('.sl_tabs li[data-tab="' + sl_currentTab + '"]').trigger('click');
        }
    }, 200);

    $('#sl_myeditprofile').click(function ()
    {   
        // DASHBOARD EDIT PROFILE LINK
        $('.sl_tabs li[data-tab="sl_edit-profile"]').click();
    });

    $('ul.sl_tabs li.sl_tab-link').click(function (e)
    {   
        var sl_chkur = $('#sl_chkuser').val();
        localStorage.setItem('current', $(e.target).attr('data-tab'));
        localStorage.setItem('currentusr', sl_chkur);
        var tab_id = $(this).attr('data-tab');

        $('ul.sl_tabs li.sl_tab-link').removeClass('sl_current');
        $('.sl_tab-content').removeClass('sl_current');

        $(this).addClass('sl_current');
        $("#" + tab_id).addClass('sl_current');
    });

    // Start Change Password $
        $(document).on('click','#sl_change-password .sl_form-field i',function(){
            var sl_type = $(this).siblings('input').attr('type') === 'password' ? 'text' : 'password';
            $(this).siblings('input').attr('type',sl_type);
            this.classList.toggle('fa-eye-slash');
        });
                
        $.validator.addMethod('passwordvalidation', function(value, element, param){
            var sl_nameRegex = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
            return value.match(sl_nameRegex);
        }, 'Password should contains minimum 8 character and one special character like !@#$%^&*()');

        $("form[name='sl_change_password']").validate({
            rules: {
              sl_old_password: "required",
              sl_new_password:  {
                    required: true,
                    //passwordvalidation: true
                },
              sl_confirm_password : {
                    required: true,
                    equalTo : '[name="sl_new_password"]'
                },
            },
            messages: {
              sl_old_password: "Please enter your old password.",
              sl_new_password: {
                    required: "Please enter your new password.",
                    //minlength: "Your password must be at least 5 characters long"
                    },
              sl_confirm_password : {
                    required: "Please enter your confirm password.",
                    equalTo : 'New password and confirm password not match.'
                },
            },
            submitHandler: function(form) {
                var ajax_url = sl_ajaxObj.ajax_url;
                var sl_nonce = $(form).find('#sl_password_change').val();
                var data = {
                    data : $(form).serialize(),
                    action : "sl_change_password",
                    sl_nonce : sl_nonce,
                };                 
                $.ajax({
                    url: ajax_url,
                    type:'POST',
                    dataType: 'json',
                    data: data,
                    beforeSend: function()
                    {
                        // Show image container
                        $("#sl_user_loader").fadeIn(500);
                    },
                    success: function(response){
                        if(response.result == 'error'){
                            html = '<p class="sl_error">' + response.message + '</p>';
                        }else if(response.result == 'success'){
                            html = '<p class="sl_success-msg">' + response.message + '</p>';
                        }
                        $(".sl_change-password-message").html(html).show();
                        setTimeout(function() { $(".sl_change-password-message").hide(); }, 5000);
                    },
                    complete:function(data)
                    {
                        $("#sl_user_loader").fadeOut(500);
                    }
                });
            }
        });
        // End Change Password $

        // Start edit profile $
        $("form[name='sl_edit_profile']").validate({
            rules: {
                sl_txt_first_name : "required",
                sl_txt_last_name : "required",
                sl_txt_email: { required: true,},},
            messages: {
                sl_txt_first_name: {
                    required: "Please enter first name."
                },
                sl_txt_last_name: {
                    required: "Please enter last name."
                },
                sl_txt_email: {
                    required: "Please enter email",
                    sl_txt_email: "Please enter a valid email address."
                },
            },
            submitHandler: function (form) {
                var ajax_url= sl_ajaxObj.ajax_url;
                var sl_nonce = $(form).find('#sl_update_user').val();
                var sl_nonce = $(form).find('#sl_change_password').val();
                var data = {
                    data : $(form).serialize(),
                    action : "sl_update_user",
                    sl_nonce : sl_nonce,
                };

                $.ajax({
                    url: ajax_url,
                    type:'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        $("#sl_user_loader").fadeIn(500);
                    },
                    success: function(response)
                    {     
                        if(response.result == 'error'){
                            html = '<p class="sl_error">' + response.message + '</p>';
                        }else if(response.result == 'success'){
                            html = '<p class="sl_success-msg">' + response.message + '</p>';
                        }
                        $(".sl_edit-profile-message").html(html).show();
                        setTimeout(function() { $(".sl_edit-profile-message").hide(html); }, 5000);
                    },
                    complete:function(data)
                    {
                        $("#sl_user_loader").fadeOut(500);
                    }
                });
                //form.submit();
            }
        });

        // End edit profile $
           
        // Start $ login form
        $('form[id="sl_login"]').validate({
            rules: {
                sl_txt_first_name :{
                    required: true,
                },
                sl_email: {
                    required: true,
                },
                sl_password: {
                    required: true,
                    // minlength: 5,
                }
            },
            messages: {
                sl_email: {
                    required: "Please enter email.",
                },
                sl_password: {
                    required: "Please enter password.",
                    // minlength: 'Password must be at least 5 characters long'
                }
            },
            submitHandler: function(form)
            {
                var sl_email = $(form).find('#sl_email').val();
                var sl_password = $(form).find('#sl_password').val();
                var sl_rememberme = $(form).find('#sl_rememberme').val();

                var ajax_url = sl_ajaxObj.ajax_url;
                var sl_nonce = $(form).find('#sl_user_login').val();
                var data =  {
                    sl_email : sl_email,
                    sl_password : sl_password,
                    sl_rememberme : sl_rememberme,
                    sl_nonce : sl_nonce,
                    action : "validate_user"
                };
                $.ajax({
                    url: ajax_url,
                    type:'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function()
                    {
                        // Show image container
                        $("#sl_user_loader").fadeIn(500);
                    },
                    success: function(response)
                    {   
                        if(response.code == 1)
                        {
                            $(".sl_login-message-con").html(response.message);
                            $(".sl_login-message-con").addClass("success-msg");
                            setTimeout(function(){
                                window.location = response.redirect_url;
                            }, 2000);
                        }
                        else if(response.code == 0){
                            $(".sl_login-message-con").html(response.message);
                            $(".sl_login-message-con").addClass("sl_error");
                            
                        }else {
                            $(".sl_login-message-con").html('Please activate your account first.');
                            $(".sl_login-message-con").addClass("sl_error");
                        }                                                                    
                        
                    },
                    complete:function(data)
                    {
                        // Hide image container
                        $("#sl_user_loader").fadeOut(500);
                    }
                });
            }
        });

        const sl_forgot_password = document.querySelector("#sl_forgot-password");
        if(sl_forgot_password){

            // Validate Username
            $("#sl_username_email-error").hide();
            let sl_usernameError = true;

            $("#sl_forgot-password").submit(function(event) {
                event.preventDefault();

                function sl_validateUsername() {
                    let sl_usernameValue = $("#sl_username_email").val();
                    if (sl_usernameValue == "") {
                        $("#sl_username_email-error").show();
                        sl_usernameError = false;
                        return false;
                    } else {
                        $("#sl_username_email-error").hide();
                    }
                }

                sl_validateUsername();

                var sl_wpnonce = $("#sl_wpnonce").val();
                var sl_username_email = $("#sl_username_email").val();
                var ajax_url = sl_ajaxObj.ajax_url;
                var data = {
                    sl_username_email: sl_username_email,
                    sl_wpnonce: sl_wpnonce,
                    action: "sl_forgot_password"
                };

                if (sl_username_email !== "") {
                    $.ajax({
                        url: ajax_url,
                        type: "POST",
                        data: data,
                        beforeSend: function() {
                            // Show image container
                            $("#sl_user_loader").fadeIn(500);
                        },
                        success: function(html) {
                            $("#sl_username_email").css("border", "");
                            $(".sl_forgot-message").html(html);
                        },
                        complete: function(data) {
                            // Hide image container
                            $("#sl_user_loader").fadeOut(500);
                        }
                    });
                }
            });

        }

    jQuery("#sl_email_preferences").submit(function(event) {
        var ajax_url = sl_ajaxObj.ajax_url;
        var sl_nonce = jQuery("#sl_email_preferences").find('#sl_email_preferences').val();
        
        var data = {
            data : jQuery("#sl_email_preferences").serialize(),
            action : "sl_email_preferences",
            sl_nonce : sl_nonce,
        };
        $.ajax({
            url  : ajax_url,
            type : "POST",
            data : data,
            dataType: 'json',
            beforeSend: function() {
                // Show image container
                $("#sl_user_loader").fadeIn(500);
            },
            success: function(response) {
                if(response.result == 'error'){
                    html = '<p class="sl_error">' + response.message + '</p>';
                }else if(response.result == 'success'){
                    html = '<p class="sl_success-msg">' + response.message + '</p>';
                }
                $(".sl_email-preferences-message").html(html).show();
                setTimeout(function() { $(".sl_email-preferences-message").hide(html); }, 5000);

            },
            complete: function(data) {
                // Hide image container
                $("#sl_user_loader").fadeOut(500);
            }
        });
        event.preventDefault();
    });

    jQuery(document).on('sl_apply_social_share', function (e, id, url, action) {
    	sl_socialShare(id, url, action);
    });

    jQuery(document).on('sl_apply_social_followup', function (e, id, url, action) {
    	sl_followUpShare(id, url, action);
    });

     jQuery(document).on('sl_apply_social_review', function (e, id, url, action) {
        sl_socialShare(id, url, action);
    });

    

    jQuery("#sl_txt_dob").datepicker({dateFormat: 'yy-mm-dd' , changeMonth: true, changeYear: true, yearRange: '1900:+0', maxDate: new Date()});


    jQuery(".benefit, .point-awarded").click(function() {
        $('html,body').animate({
            scrollTop: ($(".histroy-wrap").offset().top + 50)},
            'slow');
    });

    //jQuery('#thank-you-modal').modal('show');

    jQuery(".btn-close").click(function() {
        jQuery('#thank-you-modal').modal('hide');
    });

    

   
});


	let click_social_status = [];
    sl_socialShare = function (id, url, action) {
        sl_ajaxObj.social_share_window_open ? window.open(url, action, 'width=626, height=436') : window.open(url, '_blank');
        if (!click_social_status.includes(action)) {
            var data = {
                action: 'sl_social_' + action,
                sl_nonce: sl_ajaxObj.apply_share_nonce,
                id: id
            };
            sl_award_social_point(data);
            click_social_status.push(action);
        }
    };

    sl_followUpShare = function (id, url, action) {
        sl_ajaxObj.followup_share_window_open ? window.open(url, action, 'width=626, height=436') : window.open(url, '_blank');
        if (!click_social_status.includes(action)) {
            var data = {
                action: 'sl_social_' + action,
                sl_nonce: sl_ajaxObj.apply_share_nonce,
                id: id
            };
            sl_award_social_point(data);
            click_social_status.push(action);
        }
    };
    sl_award_social_point = function (data) {
        jQuery.ajax({
            data: data,
            type: 'post',
            dataType: 'json',
            url: sl_ajaxObj.ajax_url,
            error: function (request, error) {
                //console.log(error);
            },
            success: function (json) {
            	//console.log(json);
            }
        });
    };



// user country code for selected option
var user_country_code = "US";

window.onload = function() {
    // script https://www.html-code-generator.com/html/drop-down/state-name

    // Get the country name and state name from the imported script.
    const country_array = country_and_states.country;
    const states_array = country_and_states.states;

    const id_state_option = document.getElementById("sl_state");
    const id_country_option = document.getElementById("sl_country");

    // const id_state_option = jQuery("#sl_state");
     //const id_country_option = jQuery("#sl_country");
    if(id_state_option){
        const createCountryNamesDropdown = () => {
            let option =  '';
            option += '<option value="">Select Country</option>';

            for(let country_code in country_array){
                // set selected option user country
                let selected = (country_code == user_country_code) ? ' selected' : '';
                option += '<option value="'+country_code+'"'+selected+'>'+country_array[country_code]+'</option>';
            }
            id_country_option.innerHTML = option;
        };

        const createStatesNamesDropdown = () => {
            let selected_country_code = id_country_option.value;
            // get state names
            let state_names = states_array[selected_country_code];

            // if invalid country code
            if(!state_names){
                id_state_option.innerHTML = '<option value="">Select State</option>';
                return;
            }
            let option = '';
            option += '<select id="sl_state" class="form-control form-select" name="sl_txt_state">';
            option += '<option value="">Select State</option>';
            for (let i = 0; i < state_names.length; i++) {
                option += '<option value="'+state_names[i].code+'">'+state_names[i].name+'</option>';
            }
            option += '</select>';
            id_state_option.innerHTML = option;
        };

        // country select change event
        id_country_option.addEventListener('change', createStatesNamesDropdown);

        createCountryNamesDropdown();
        createStatesNamesDropdown();
    }
}





