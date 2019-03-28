// Color Pickers
jQuery('.klicked-sub-colors').wpColorPicker();

// Logo Upload
jQuery(document).ready(function($){
    var custom_uploader
    , click_elem = jQuery('.klicked-subscribe-logo-upload')
    , target = jQuery('#ifklicked_subscribe_logo')

    click_elem.click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            target.val(attachment.url);
            jQuery('#ifklicked_subscribe_logo_display').html('<img src="'+attachment.url+'" />');
        });
        //Open the uploader dialog
        custom_uploader.open();
    });      
});

// Image Uploader display
var baseImage = jQuery('#ifklicked_subscribe_logo').val();
jQuery('#ifklicked_subscribe_logo_display').html('<img src="'+baseImage+'" />');