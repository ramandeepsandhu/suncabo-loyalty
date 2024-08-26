(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	    // ON CLICK INPUT BOC SHOTCODE WILL BE COPIED
	    $(".sl_a_click .sl_a_copy").click(function(){
	        $(this).closest("sl_a_click").find("span").text();
	        document.execCommand("Copy");
	        $(this).next('.sl-copied-txt').show();
	        setTimeout(function() { $(".sl-copied-txt").hide(); }, 2500);
	    });
	    // END, ON CLICK INPUT BOC SHOTCODE WILL BE COPIED
	    setTimeout(function () {
	        var currentTab = localStorage.getItem('current_tab');
	        var currentur = localStorage.getItem('current_usr');
	        var chkur = suncabo_loyalty_admin__ajaxObj.curr_user;

	        if (currentTab && currentur == chkur) {
	            $('.sl_a_tabs li[data-tab="' + currentTab + '"]').trigger('click');
	        }
	    }, 5);
	    //nav tabs for theme options
	   $(document).on("click", 'ul.sl_a_tabs li', function (e) {
	        localStorage.setItem('current_tab', $(e.target).attr('data-tab'));
	        //alert(localStorage);
	        localStorage.setItem('current_usr', suncabo_loyalty_admin__ajaxObj.curr_user);
	        var tab_id = $(this).attr('data-tab');

	        $('ul.sl_a_tabs li.sl_a_tab-link').removeClass('sl_a_current');
	        $('.sl_a_tab-content').removeClass('sl_a_current');

	        $(this).addClass('sl_a_current');
	        $("#" + tab_id).addClass('sl_a_current');
	    });

	   //jQuery("#sl_txt_dob").datepicker({changeMonth: true, changeYear: true, yearRange: '1900:+0', maxDate: new Date()});
		
		

	

})( jQuery );

jQuery(document).ready(function() {
	jQuery('.select2-element').select2();

	jQuery('.checkAll').click(function(){
	    if(jQuery(this).prop('checked')){
	        jQuery('.cb-element').prop('checked',true);
	    }
	    else{
	        jQuery('.cb-element').prop('checked',false);
	    }
	});

	
	jQuery('.send-birthday-email').click(function(){
		var btn = jQuery(this);
		var user_id = jQuery(this).attr('data-id');
		var sl_nonce = jQuery('#sl_nonce').val();
		btn.prop('disabled', true);
		var data = {
            	action : "sl_birthday_email",
            	data : jQuery('#frm_user_'+ user_id).serialize(),
            	sl_nonce : sl_nonce
            };
			jQuery.ajax({
                url: ajaxurl,
                type:'POST',
                dataType: 'json',
                data: data,
                beforeSend: function()
                {
                   jQuery("#sl_user_loader").fadeIn(500);
                },
                success: function(response){
                	btn.prop('disabled', false);

                	if(response.code == 200){
                		html = '<div class="alert alert-success">' + response.message + '</div>';
                	}else{
                		html = '<div class="alert alert-danger">' + response.message + '</div>';
                	}
                	jQuery("#sl_email_message_"+user_id).html(html).show();
                    
                    /*setTimeout(function() { 
                    	window.location = '?page=sl-manage-points&view=list';
                	}, 2000);*/
                    
                },
                complete:function(data)
                {
                    jQuery("#sl_user_loader").fadeOut(500);
                }
            });

	});

	jQuery('#loyalty-bulk-action').click(function(){
		var selected = new Array();
		jQuery("input:checkbox[name=loyalty-record]:checked").each(function() {
		    selected.push(jQuery(this).val());
		});

		var action = jQuery('#bulk-action-selector-top').val();
		var sl_nonce = jQuery('#sl_nonce').val();
		if(action && selected.length>0){
			var data = {
            	ids : selected,
            	action : "sl_bulk_action",
            	selector : action,
            	sl_nonce : sl_nonce
            };
			jQuery.ajax({
                url: ajaxurl,
                type:'POST',
                dataType: 'json',
                data: data,
                beforeSend: function()
                {
                   jQuery("#sl_user_loader").fadeIn(500);
                },
                success: function(response){
                	if(response.code == 200){
                		html = '<div class="alert alert-success">' + response.message + '</div>';
                	}else{
                		html = '<div class="alert alert-danger">' + response.message + '</div>';
                	}
                	jQuery(".sl_bulk-action-message").html(html).show();
                    setTimeout(function() { 
                    	//jQuery(".sl_bulk-action-message").hide(); 
                    	window.location = '?page=sl-manage-points&view=list';
                }, 2000);
                    
                },
                complete:function(data)
                {
                    jQuery("#sl_user_loader").fadeOut(500);
                }
            });

		}
		
	});

	
});
