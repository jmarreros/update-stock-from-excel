<?php

namespace dcms\update\includes;

class Configuration{

    public function __construct(){
        add_action('admin_init', [$this, 'init']);
    }

    // Register seccions and fields
    public function init(){
        register_setting('dcms_usexcel_options_bd', 'dcms_usexcel_options', [$this, 'dcms_validate_cb']);

        // Path Section
        add_settings_section('dcms_usexcel_section_file',
                            __('File Path','dcms-update-stock-excel'),
                            [$this,'dcms_section_cb'],
                            'dcms_usexcel_sfields' );


        add_settings_field('dcms_usexcel_input_file',
                            __('Path excel file','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_file',
                            ['label_for' => 'dcms_usexcel_input_file',
                            'class' => 'regular-text',
                            'description' => __('Enter a valid absolute route, ex: /home/public_html/web/mi-file.xls','dcms-update-stock-excel'),
                            'required' => true]
        );

        // Excel Fields section
        add_settings_section('dcms_usexcel_section_excel',
                            __('Excel Columns','dcms-update-stock-excel'),
                            [$this,'dcms_section_cb'],
                            'dcms_usexcel_sfields' );

        add_settings_field('dcms_usexcel_sku_field',
                            __('SKU column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_sku_field',
                             'required' => true]
        );

        add_settings_field('dcms_usexcel_stock_field',
                            __('Stock column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_stock_field',
                            'required' => true]
        );

        add_settings_field('dcms_usexcel_variant_field',
                            __('Variant column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_variant_field']
        );

        add_settings_field('dcms_usexcel_isweb_field',
                            __('Product for web column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_isweb_field']
        );

    }

    // Callback section
    public function dcms_section_cb(){
		echo '<hr/>';
	}

    // Callback input field callback
    public function dcms_section_input_cb($args){
        $id = $args['label_for'];
        $req = isset($args['required']) ? 'required' : '';
        $class = isset($args['class']) ? "class='".$args['class']."'" : '';
        $desc = isset($args['description']) ? $args['description'] : '';

        $options = get_option( 'dcms_usexcel_options' );
        $val = isset( $options[$id] ) ? $options[$id] : '';

        printf("<input id='%s' name='dcms_usexcel_options[%s]' type='text' value='%s' %s %s>",
                $id, $id, $val, $req, $class);

        if ( $desc ) printf("<p class='description'>%s</p>", $desc);

    }

    // Inputs fields sanitation and validation
    public function dcms_validate_cb($input){
        $output = array();

        foreach( $input as $key => $value ) {
            if( isset( $input[$key] ) ) {
                $output[$key] = strip_tags( $input[ $key ] );
            }
        }

        $path_file = $output['dcms_usexcel_input_file'];

        // Validate file path
        $this->validate_path_file($path_file);

        // validate columns
        $this->validate_columns_file($path_file);

        return apply_filters( 'dcms_validate_inputs', $output, $input );
    }


    // Validate that the file exists
    private function validate_path_file($path_file){
        if ( ! file_exists($path_file) ) {
            add_settings_error( 'dcms_messages', 'dcms_file_error', __( 'File doesn\'t exists', 'dcms-update-stock-excel' ), 'error' );
        }
    }

    // Validate that columns' file exists
    private function validate_columns_file($path_file){

    }
}


# TODO
# Validate settings: https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-7-validation-sanitisation-and-input--wp-25289
# add_settings_error( 'dcms_messages', 'dcms_messages', __( 'Settings Error xxx', 'dcms-update-stock-excel' ), 'error' );