jQuery(document).ready(function() {
    // On click of "Send" button
    jQuery('span#send-btn').on('click', function() {
        jQuery('div#confirmation-send').dialog({
            resizable: false,
            draggable: false,
            height: "auto",
            modal: true,
        });
    });
    
    // On click of "Close" button
    jQuery('div#confirmation-send .close span.close').on('click', function() {
        jQuery('div#confirmation-send').dialog('close'); 
    });
    
    // Datepicker
    jQuery('#ifklicked_send_date').datepicker({ minDate: 0});
    jQuery('#ifklicked_send_date').datepicker('option', 'dateFormat', 'yy-mm-dd');
    
    // Page Title
    var pageTitle = jQuery('select#ifklicked_send_email_template :selected').text();
    jQuery('select#ifklicked_send_subject option[value=page-title]').text('Page Title ('+pageTitle+')');
    jQuery('select#ifklicked_send_email_template').on('change', function() {
        var pageTitle = jQuery('select#ifklicked_send_email_template :selected').text();
        jQuery('select#ifklicked_send_subject option[value=page-title]').text('Page Title ('+pageTitle+')');
    });
    
    // Show subject field when "Custom" option is selected
    var subjectMeth = jQuery('select#ifklicked_send_subject').val();
    if(subjectMeth === 'custom') {
        jQuery('span.custom-subject').show();
        jQuery('input#ifklicked_send_custom_subject').show();
    } else {
        jQuery('span.custom-subject').hide();
        jQuery('input#ifklicked_send_custom_subject').hide();
    }

    jQuery('select#ifklicked_send_subject').on('change', function() {
        var subjectMeth = jQuery('select#ifklicked_send_subject').val();
        if(subjectMeth === 'custom') {
            jQuery('span.custom-subject').show();
            jQuery('input#ifklicked_send_custom_subject').show();
        } else {
            jQuery('span.custom-subject').hide();
            jQuery('input#ifklicked_send_custom_subject').hide();
        }
    });
    
    // Set preview URL
    var prevURL = jQuery('select#ifklicked_send_email_template option:selected').data('url');
    jQuery('select#ifklicked_send_email_template').next('a.klicked-preview').attr('href', prevURL);
    
    
    // Set preview URL on change of template
    jQuery('select#ifklicked_send_email_template').on('change', function() {
        // Variables
        var prevURL = jQuery(this).children('option:selected').data('url');

        // Set preview URL
        jQuery(this).next('a.klicked-preview').attr('href', prevURL);
    });
});