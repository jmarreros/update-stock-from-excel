<?php


namespace dcms\update\includes;

use dcms\update\libs\SimpleXLSX;


class Readfile{

    private $xlsx;
    private $path_file;

    public function __construct($path_file = ''){
        $options = get_option( 'dcms_usexcel_options' );

        $this->path_file = empty($path_file) ? $options['dcms_usexcel_input_file'] : $path_file;
        $this->sheet_number = $options['dcms_usexcel_sheet_field'];

        $this->xlsx = new SimpleXLSX($this->path_file);
    }

    // Get data from file as array
    public function get_data_from_file(){
        $data = false;
        $data = $this->xlsx->rows($this->sheet_number);

        return $data;
    }


    // Get header file
    public function get_header( $sheet_number = false){

        if ( $sheet_number === false ){
            $options = get_option( 'dcms_usexcel_options' ); // Get sheet number from database
            $sheet_number = $options['dcms_usexcel_sheet_field'];
        }

        $rows = $this->xlsx->rows($sheet_number);

        if ( ! isset($rows[0]) ) return false;

        return $rows[0];
    }

    // Get ids by column name in an array
    public function get_headers_ids(){

        $headers = $this->get_header($this->sheet_number);
        $options = get_option( 'dcms_usexcel_options' );

        $text_sku       = $options['dcms_usexcel_sku_field'];
        $text_stock     = $options['dcms_usexcel_stock_field'];
        $text_price     = $options['dcms_usexcel_price_field'];
        $text_filter    = $options['dcms_usexcel_isweb_field'];

        $headers_id             = [];
        $headers_id['sku']      = ($text_sku) ? array_search($text_sku, $headers) : -1;
        $headers_id['stock']    = ($text_stock) ? array_search($text_stock, $headers) : -1;
        $headers_id['price']    = ($text_price) ? array_search($text_price, $headers) : -1;
        $headers_id['filter']   = ($text_filter) ? array_search($text_filter, $headers) : -1;

        return $headers_id;
    }

    // Is update file, validate if the file has changed
    public function file_has_changed(){
        $modified_bd = get_option('dcms_last_modified_file',0);
        $modified_file = filemtime($this->path_file);

        if ( $modified_file > $modified_bd ){
            return $modified_file;
        }
        return false;
    }


}

