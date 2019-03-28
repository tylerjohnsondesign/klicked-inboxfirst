/* schedule.js */
jQuery(document).ready(function() {
    // Toggle sections
    jQuery('h2.schedule-sections').next('.form-table').hide();
    jQuery('h2.schedule-sections').on('click', function() {
        jQuery(this).toggleClass('active');
        jQuery(this).next('.form-table').toggleClass('active');
    });
    
    // Set preview URL
    jQuery('select.template-preview').each(function() {
        // Variables
        var prevURL = jQuery(this).children('option:selected').data('url');

        // Set preview URL
        jQuery(this).next('a.klicked-preview').attr('href', prevURL);
    });
    
    // Set preview URL on change of template
    jQuery('select.template-preview').on('change', function() {
        // Variables
        var prevURL = jQuery(this).children('option:selected').data('url');

        // Set preview URL
        jQuery(this).next('a.klicked-preview').attr('href', prevURL);
    });
});