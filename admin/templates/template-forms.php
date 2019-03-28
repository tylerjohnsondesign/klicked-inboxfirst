<?php
$this->ifklicked_forms_options = get_option( 'ifklicked_forms_option_name' );

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
    <?php }
    $lists = ifklicked_get_lists(IFKLICKED_IBF_KEY);
        if(!empty($lists)) {
            echo '<div class="shortcode-input"><label>List</label><select id="shortcode-list">';
            foreach($lists['data'] as $list) {
                echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
            }
            echo '</select></div>';
        }
    ?>
    <div class="shortcode-input"><label>Button Title</label><input type="text" id="shortcode-title" /></div>
    <div class="code-display"><code>[ifform]</code></div>
</div>
<style type="text/css">
.shortcode-input{margin:15px 0}.shortcode-input label{width:100px;display:inline-block}.code-display code{display:block;padding:10px}
</style>
<script type="text/javascript">
jQuery(document).ready(function(e){var t=e("select#shortcode-list").val();e(".code-display code").html('[ifform list="'+t+'"]'),e("select#shortcode-list").on("change",function(){var t=e("select#shortcode-list").val(),o=e("input#shortcode-title").val();""===o?e(".code-display code").html('[ifform list="'+t+'"]'):e(".code-display code").html('[ifform list="'+t+'" submit="'+o+'"]')}),e("input#shortcode-title").on("input",function(){var t=e("select#shortcode-list").val(),o=e("input#shortcode-title").val();""===o?e(".code-display code").html('[ifform list="'+t+'"]'):e(".code-display code").html('[ifform list="'+t+'" submit="'+o+'"]')})});
</script>