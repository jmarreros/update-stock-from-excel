<?php

namespace dcms\update\includes;

class Configuration{

    public function __construct(){
        add_action('admin_init', [$this, 'init']);
    }

    public function init(){
        register_setting('dcms_usexcel_options_bd', 'dcms_usexcel_options');

        // Path Section
        add_settings_section('dcms_usexcel_section_file',
                            __('File Path','dcms-update-stock-excel'),
                            [$this,'dcms_section_file_cb'],
                            'dcms_usexcel_sfields' );


        add_settings_field('dcms_usexcel_input_file',
                            __('Path excel file','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_file_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_file',
                            ['label_for' => 'dcms_usexcel_input_file']
        );

        // Excel Fields section
        add_settings_section('dcms_usexcel_section_excel',
                            __('Excel Fields','dcms-update-stock-excel'),
                            [$this,'dcms_section_file_cb'],
                            'dcms_usexcel_sfields' );

        add_settings_field('dcms_usexcel_sku_field',
                            __('SKU column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_file_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_sku_field']
        );

        add_settings_field('dcms_usexcel_stock_field',
                            __('Stock column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_file_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_stock_field']
        );

        add_settings_field('dcms_usexcel_variant_field',
                            __('Variant column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_file_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_variant_field']
        );

        add_settings_field('dcms_usexcel_isweb_field',
                            __('Product for web column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_file_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_isweb_field']
        );

    }


    public function dcms_section_file_cb(){
		echo '<hr/>';
	}

    public function dcms_section_input_file_cb($args){
        $id = $args['label_for'];
        $options = get_option( 'dcms_usexcel_options' );
        $val = isset( $options[$id] ) ? $options[$id] : '';

		echo '<input id="'.$id.'" name="dcms_usexcel_options['.$id.']" type="text" value="'.$val.'"/>';
    }

}