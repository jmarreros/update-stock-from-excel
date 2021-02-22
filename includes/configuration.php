<?php

namespace dcms\update\includes;

class Configuration{

    public function __construct(){
        add_action('admin_init', [$this, 'init_configuration']);
    }

    // Register seccions and fields
    public function init_configuration(){
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
                            __('Excel Structure','dcms-update-stock-excel'),
                            [$this,'dcms_section_cb'],
                            'dcms_usexcel_sfields' );

        add_settings_field('dcms_usexcel_sheet_field',
                            __('Sheet Number','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_sheet_field',
                                'description' => __('Enter a sheet page number','dcms-update-stock-excel'),
                                'required' => true]
        );

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

        add_settings_field('dcms_usexcel_price_field',
                            __('Price column name','dcms-update-stock-excel'),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_usexcel_sfields',
                            'dcms_usexcel_section_excel',
                            ['label_for' => 'dcms_usexcel_price_field']
        );

        // add_settings_field('dcms_usexcel_isweb_field',
        //                     __('Product for web column name','dcms-update-stock-excel'),
        //                     [$this, 'dcms_section_input_cb'],
        //                     'dcms_usexcel_sfields',
        //                     'dcms_usexcel_section_excel',
        //                     ['label_for' => 'dcms_usexcel_isweb_field']
        // );

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

        // Sanitization
        foreach( $input as $key => $value ) {
            if( isset( $input[$key] ) ) {
                $output[$key] = strip_tags( $input[ $key ] );
            }
        }

        // Validation
        $path_file = $output['dcms_usexcel_input_file'];

        // Validate file path
        $this->validate_path_file($path_file);

        // validate columns
        $this->validate_columns_file($path_file, $output);

        return $output;
    }


    // Validate that the file exists
    private function validate_path_file($path_file){
        if ( ! file_exists($path_file) ) {
            add_settings_error( 'dcms_messages', 'dcms_file_error', __( 'File doesn\'t exists', 'dcms-update-stock-excel' ), 'error' );
        }
    }

    // Validate that columns' file exists
    private function validate_columns_file($path_file, $output){

        // Get input column values
        $sheet_number = $output['dcms_usexcel_sheet_field'];

        $columns = [];
        $columns['SKU']     = $output['dcms_usexcel_sku_field'];
        $columns['Stock']   = $output['dcms_usexcel_stock_field'];
        $columns['Price']   = $output['dcms_usexcel_price_field'];
        // $columns['Web']   = $output['dcms_usexcel_isweb_field'];


        // Read file and validate sheet_number headers
        $readfile = new Readfile($path_file);
        $headers = $readfile->get_header($sheet_number);

        if ( ! $headers ) {
            add_settings_error( 'dcms_messages', 'dcms_file_error', __( 'Headers columns in .xls file doesn\'t exists', 'dcms-update-stock-excel' ), 'error' );
            return false;
        }

        // Validate each header
        foreach ($columns as $key => $column) {
            if ( ! empty($column) &&  ! in_array($column, $headers) ){
                add_settings_error( 'dcms_messages', 'dcms_file_error', __( $key .' column "'. $column . '" doesn\'t exists  in .xls file', 'dcms-update-stock-excel' ), 'error' );
            }
        }

    }

}
