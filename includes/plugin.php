<?php

namespace dcms\update\includes;

class Plugin{

    public function __construct(){
        register_activation_hook( DCMS_UPDATE_BASE_NAME, [ $this, 'dcms_activation_plugin'] );
    }

    // Create options and database table
    public function dcms_activation_plugin(){

        // Default Options
        $options = get_option( 'dcms_usexcel_options' );

        if ( empty($options) ){
             $options = [
                         'dcms_usexcel_input_file'  => '/home/public_html/file.xlsx',
                         'dcms_usexcel_sheet_field' => '3',
                         'dcms_usexcel_sku_field'	=> 'Sku',
                         'dcms_usexcel_stock_field'	=> 'Stock',
                         'dcms_usexcel_price_field'	=> 'Price',
                         'dcms_usexcel_isweb_field' => 'Web'
                 ];
            update_option('dcms_usexcel_options', $options);
        }

        // TODO
        // Database initialization
    }

}

