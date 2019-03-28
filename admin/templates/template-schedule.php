<?php
// Redirect if no API key
if(empty(IFKLICKED_IBF_KEY)) {
    $url = get_bloginfo('url').'/wp-admin/admin.php?page=inbox-first&nav=failed';
    wp_redirect($url);
    exit;
} else {
$this->ifklicked_schedule_options = get_option( 'ifklicked_schedule_option_name' ); ?>
<div class="wrap ifklicked-wrap ifklicked-schedule-wrap">
    <div class="logo"><img src="<?php echo IFKLICKED_BASE_URI.'admin/assets/inboxfirst_plugin_logo.jpg'; ?>" alt="InboxFirst by Klicked Media" /></div>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'ifklicked_schedule_option_group' );
            do_settings_sections( 'inbox-first-schedule' );
            submit_button('Save');
        ?>
    </form>
</div>
<?php } ?>