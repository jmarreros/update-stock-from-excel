<?php

namespace dcms\update\includes;

use dcms\update\includes\Database;

class Plugin{

    public function __construct(){
        register_activation_hook( DCMS_UPDATE_BASE_NAME, [ $this, 'dcms_activation_plugin'] );
        register_deactivation_hook( DCMS_UPDATE_BASE_NAME, [ $this, 'dcms_deactivation_plugin'] );
    }

    // Activate plugin - create options and database table
    public function dcms_activation_plugin(){

        // Default Options
        $options = get_option( 'dcms_usexcel_options' );

        if ( empty($options) ){
            $options = [
                         'dcms_usexcel_input_file'  => '/home/public_html/file.xlsx',
                         'dcms_usexcel_sheet_field' => '3',
                         'dcms_usexcel_sku_field'	=> 'Sku',
                         'dcms_usexcel_stock_field'	=> 'Stock',
                         'dcms_usexcel_price_field'	=> 'Price'
                 ];
            update_option('dcms_usexcel_options', $options);
        }

        update_option('dcms_last_modified_file', 0);

        // Create table
        $db = new Database();
        $db->create_table();

        // Create cron
        if( ! wp_next_scheduled( 'dcms_cron_hook' ) ) {
            wp_schedule_event( current_time( 'timestamp' ), 'dcms_interval', 'dcms_cron_hook' );
        }

    }

    // Deactivate plugin
    public function dcms_deactivation_plugin(){
        wp_clear_scheduled_hook( 'dcms_cron_hook' );
    }

}

