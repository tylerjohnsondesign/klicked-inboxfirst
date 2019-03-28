<?php
// Redirect if no API key
if(empty(IFKLICKED_IBF_KEY)) {
    $url = get_bloginfo('url').'/wp-admin/admin.php?page=inbox-first&nav=failed';
    wp_redirect($url);
    exit;
} else {
$this->ifklicked_send_options = get_option( 'ifklicked_send_option_name' ); ?>
<div class="wrap ifklicked-wrap ifklicked-send-wrap">
    <div class="logo"><img src="<?php echo IFKLICKED_BASE_URI.'admin/assets/inboxfirst_plugin_logo.jpg'; ?>" alt="InboxFirst by Klicked Media" /></div>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'ifklicked_send_option_group' );
            do_settings_sections( 'inbox-first-send' );
            echo '<span id="send-btn" class="button button-primary">Send</span><span id="loading" style="display: none"><svg width="28px" height="28px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="animation-play-state: running; animation-delay: 0s; background: none;"><circle cx="50" cy="50" ng-attr-r="{{config.radius}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.stroke}}" ng-attr-stroke-dasharray="{{config.dasharray}}" fill="none" stroke-linecap="round" r="40" stroke-width="15" stroke="#bebebe" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(189.733 50 50)" style="animation-play-state: running; animation-delay: 0s;"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1.2s" begin="0s" repeatCount="indefinite" style="animation-play-state: running; animation-delay: 0s;"></animateTransform></circle></svg></span>';
            submit_button('Save');
            echo '<div id="message-box"></div>';
        ?>
    </form>
</div>
<div id="confirmation-send" style="display: none">
    <div class="close"><span class="close">×</span></div>
    <p>Are you sure you want to send?</p>
    <p class="send-campaign-container"><span id="send-campaign" class="button button-primary">Yes, send.</span></p>
</div>
<?php } ?> 