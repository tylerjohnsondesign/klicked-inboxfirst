<?php
// Variables
$opts = get_option('ifklicked_subscribe_option_name');
$logo = $opts['ifklicked_subscribe_logo'];
$list = $opts['ifklicked_subscribe_list'];
$primary = $opts['ifklicked_subscribe_primary'];
$background = $opts['ifklicked_subscribe_background'];

// Checks
if(empty($background)) {
    $bg = '';
} else {
    $bg = ' style="background: '.$background.';"';
}
if(empty($primary)) {
    $rgb = '';
    $style = '';
} else {
    // Convert hex to RGB
    $hex = $primary;
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    $style = '<style type="text/css">button.ifklicked-subscribe-btn{background: '.$background.'!important;color:'.$primary.'!important;}button.ifklicked-subscribe-btn:hover{opacity: .8!important;}input.ifklicked-subscribe-email:focus{border-color:'.$primary.'; box-shadow: 0 0 0 0.2rem rgba('.$r.', '.$g.', '.$b.', .25);}.ifklicked-subscribe-form {background:'.$primary.'}</style>';
}
?>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="robots" content="noimageindex">
<meta name="googlebot" content="noimageindex">
<?php wp_head(); ?>
    <?php echo $style; ?>
    <body class="text-center"<?php echo $bg; ?>>
        <div class="ifklicked-subscribe-form">
            <div class="ifklicked-header">
                <a href="/">
                    <?php if(empty($logo)) { ?>
                    <h1 style="color: <?php echo $primary; ?>"><?php echo get_bloginfo('name'); ?></h1>
                    <?php } else { ?>
                    <img class="mb-4" src="<?php echo $logo; ?>" alt="<?php echo get_bloginfo('name'); ?>">
                    <?php } ?>
                </a>
            </div>
            <p class="font-weight-normal">Thank you for your interest in receiving the <?php echo get_bloginfo('name'); ?> newsletter. To subscribe, please submit your email address below.</p>
            <label for="inputEmail" class="sr-only">Email address</label>
            <?php echo do_shortcode('[ifform list="'.$list.'" submit="Subscribe"]'); ?>
        </div>
    </body>
<?php wp_footer(); ?>
