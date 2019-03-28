/* custom.js */
function isDark( color ) {
    var match = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
    return parseFloat(match[1])
         + parseFloat(match[2])
         + parseFloat(match[3])
           < 3 * 256 / 2; // r+g+b should be less than half of max (3 * 256)
}

jQuery(document).ready(function() {
    jQuery('.subscribe-form p').each(function() {
        jQuery(this).css("color", isDark(jQuery('body').css("background-color")) ? 'white' : 'black');
    });
    
    jQuery('button.ifklicked-subscribe-btn').each(function() {
        jQuery(this).addClass(isDark(jQuery('body').css("background-color")) ? 'klicked-light' : 'klicked-dark');
    });
});