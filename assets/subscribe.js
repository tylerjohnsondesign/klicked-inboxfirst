/* subscribe.js */
jQuery(document).ready(function($) {
    // On subscribe button click
	$('button.ifklicked-subscribe-btn').on('click', function() {
		// Variables
		var inputID = $(this).data('input');
		var email = $('#'+inputID).val();
        var id = $('#'+inputID).data('id');
        
        // Hide and show
        $(this).parent('.ifklicked-subscribe').hide();
        $('.ifklicked-loading-'+inputID).show();
        
        // Run ajax
        jQuery.ajax({
            url: ifsub.ajax_url,
            type: 'post',
            data: {
                action: 'ifklicked_subscribe_import',
                email: email,
                list: id,
            },
            success:function(response) {
                $('.ifklicked-loading-'+inputID).hide();
                $('.ifklicked-message-'+inputID).show();
                $('.ifklicked-message-'+inputID).html(response);
            }
        });
	});
});;