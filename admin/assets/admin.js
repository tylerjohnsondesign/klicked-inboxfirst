/**
iOS Switches
**/
/* Switches.js */
!function(e){e.fn.extend({iosCheckbox:function(){"true"!==e(this).attr("data-ios-checkbox")&&(e(this).attr("data-ios-checkbox","true"),e(this).each(function(){var c=e(this),s=jQuery("<div>",{"class":"ios-ui-select"}).append(jQuery("<div>",{"class":"inner"}));c.is(":checked")&&s.addClass("checked"),c.hide().after(s),s.click(function(){s.toggleClass("checked"),s.hasClass("checked")?c.prop("checked",!0):c.prop("checked",!1)})}))}})}(jQuery);

// Add iOS class
jQuery(document).ready(function() {
    jQuery('.wrap.ifklicked-wrap input[type=checkbox]').addClass('ios');
});

// Activate switches
jQuery(document).ready(function() {
    jQuery('.ios').iosCheckbox();
});