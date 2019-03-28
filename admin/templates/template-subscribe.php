<?php
// Redirect if no API key
if(empty(IFKLICKED_IBF_KEY)) {
    $url = get_bloginfo('url').'/wp-admin/admin.php?page=inbox-first&nav=failed';
    wp_redirect($url);
    exit;
} else {
$this->ifklicked_subscribe_options = get_option( 'ifklicked_subscribe_option_name' ); ?>
<div class="wrap ifklicked-wrap ifklicked-subscribe-wrap">
    <div class="logo"><img src="<?php echo IFKLICKED_BASE_URI.'admin/assets/inboxfirst_plugin_logo.jpg'; ?>" alt="InboxFirst by Klicked Media" /></div>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'ifklicked_subscribe_option_group' );
            do_settings_sections( 'inbox-first-subscribe' );
            submit_button('Save');
        ?>
    </form>
</div>
<?php } ?> 