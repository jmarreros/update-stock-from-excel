<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! current_user_can( 'manage_options' ) ) return; // only administrator

if ( isset( $_GET['settings-updated'] ) ) {
    add_settings_error( 'dcms_messages', 'dcms_messages', __( 'Settings Saved', 'dcms-update-stock-excel' ), 'updated' );
}

settings_errors( 'dcms_messages' );
?>

<div class="wrap">
<h1><?php _e('Update Stock WooCommerce from Excel', 'dcms-update-stock-excel') ?></h1>

<form action="options.php" method="post">
    <?php
        settings_fields('dcms_usexcel_options_bd');
        do_settings_sections('dcms_usexcel_sfields');
        submit_button();
    ?>
</form>
</div>
