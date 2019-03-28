// ajax-send.js
jQuery('span#send-campaign').on('click', function() {
    // Close dialog
    jQuery('div#confirmation-send').dialog('close');
    // Hide other send button while sending
    jQuery('span#send-btn').hide();
    // Show loading icon
    jQuery('span#loading').show();
    
    // Get variables
    var list = jQuery('select#ifklicked_send_list').val();
    var temp = jQuery('input#ifklicked_send_campaign_template').val();
    var url  = jQuery('select#ifklicked_send_email_template').val();
    var date = jQuery('input#ifklicked_send_date').val();
    var time = jQuery('select#ifklicked_send_time').val();
    var emai = jQuery('input#ifklicked_from_email').val();
    var name = jQuery('input#ifklicked_from_name').val();
    var sub  = jQuery('select#ifklicked_send_subject').val();
    var cus  = jQuery('input#ifklicked_send_custom_subject').val();
    var seg  = jQuery('select#ifklicked_send_segmentation').val();
    
    // Run ajax
    jQuery.ajax({
        url: ifsend.ajax_url,
        type: 'post',
        data: {
            action: 'ifklicked_send_campaign',
            list: list,
            temp: temp,
            url: url,
            date: date,
            time: time,
            email: emai,
            name: name,
            sub: sub,
            cus: cus,
            seg: seg
        },
        success:function(response) {
            // Show send button
            jQuery('span#send-btn').show();
            // Hide loading icon
            jQuery('span#loading').hide();
            // Show returned message
            jQuery('#message-box').show();
            jQuery('#message-box').html(response);
        }
    });
});