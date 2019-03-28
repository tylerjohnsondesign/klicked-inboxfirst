<?php
$this->ifklicked_main_options = get_option( 'ifklicked_main_option_name' );

// API Check
if(isset($_GET['nav'])) {
    $nav = $_GET['nav'];
} else {
    $nav = 'success';
}
?>
<div class="wrap ifklicked-wrap ifklicked-main-wrap">
    <div class="logo"><img src="<?php echo IFKLICKED_BASE_URI.'admin/assets/inboxfirst_plugin_logo.jpg'; ?>" alt="InboxFirst by Klicked Media" /></div>
    <?php if($nav === 'failed' && empty(IFKLICKED_IBF_KEY)) { ?>
    <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible" style="border-left-color: red;"> 
    <p><strong>Please enter an Inbox First API key before proceeding.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
    <?php } ?>
    <p>To get your API key, go to Inbox First <a href="https://if.inboxfirst.com/ga/" target="_blank">here</a>, and click on <strong>Admin > API Keys</strong>, while logged in.</p> 
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'ifklicked_main_option_group' );
            do_settings_sections( 'inbox-first-main' );
            submit_button('Save');
        ?>
    </form>
</div>